<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\WikaInvoice;
use App\Models\WikaVisitor;
use App\Services\APIHook\Yandex;
use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class CompaignsController extends Controller
{

    protected $direct;
    protected $yandex;
    protected $title;
    protected $modelVisitor;
    protected $modelInvoice;

    public function __construct(
        $direct,
        $metricaToken,
        $metricaCounter,
        $modelVisitor,
        $modelInvoice,
    )
    {
        $this->direct = $direct;
        $this->yandex = new Yandex($metricaToken, $metricaCounter);
        $this->modelVisitor = $modelVisitor;
        $this->modelInvoice = $modelInvoice;
        
    }

    public function index()
    {
        $direct = $this->direct::all('CampaignId', 'CampaignName', 'AdGroupId', 'AdGroupName', 'Clicks', 'Cost', 'Date');

        $data = [];
        $data['direct'] = [];

        foreach ($direct as $key => $value) {
            if(!array_key_exists($value['CampaignId'], $data['direct'])) {
                $data['direct'][$value['CampaignId']] = [
                    'campaignName' => $value['CampaignName'],
                    'AdGroupId' => [$value['AdGroupId'] => ['name' => $value['CampaignName'], 'cost' => (float)$value['cost']]],
                    'cost' => (float)$value['Cost']
                ];
            } else {
                if(!array_key_exists($value['AdGroupId'], $data['direct'][$value['CampaignId']]['AdGroupId']))
                {
                    $data['direct'][$value['CampaignId']]['AdGroupId'][$value['AdGroupId']] = ['name' => $value['AdGroupName'], 'cost' => (float)$value['Cost']];
                } else {
                    $data['direct'][$value['CampaignId']]['AdGroupId'][$value['AdGroupId']]['cost'] += (float)$value['Cost'];
                }
                $data['direct'][$value['CampaignId']]['cost'] += (float)$value['Cost'];
            }
        }

        foreach ($data['direct'] as $key => $value) {
            $data['direct'][$key]['cost'] = number_format($value['cost'], 2, '.', '');
            foreach ($value['AdGroupId'] as $i => $el) {
                $data['direct'][$key]['AdGroupId'][$i]['cost'] = number_format($el['cost'], 2, '.', '');
            }
        }

        $data['routePath'] = $this->title;

        return Inertia::render('CompaignsPage', ['data' => $data]);
    }

    public function invoiceClientByDirect(): array
    {
        $data = [];

        $metrics = $this->yandex->metricCompaign();

        $metricsByCompany = $this->parserForMetricByCompaign($metrics['data']);
        $metricsByGroup = $this->parserForMetricByGroup($metrics['data']);

        $sortClientsByCompaign = $this->findClientForCompaign($metricsByCompany);
        $sortClientsByGroup = $this->findClientForGroup($metricsByGroup);

        $clientsDataByCompaign = $this->findClientsInInvoice($sortClientsByCompaign);
        $clientsDataByGroup = $this->findClientsInInvoice($sortClientsByGroup);

        $data['clientsByGroup'] = $clientsDataByGroup;
        $data['clientsByCompaign'] = $clientsDataByCompaign;

        return $data;
    }

    private function parserForMetricByCompaign(array $metrics): array
    {
        $data = [];

        foreach ($metrics as $value) {
            $data[$value['dimensions'][0]['name']][] = [
                'compaignGroupId' => $this->prepare($value['dimensions'][1]['name']),
                'clientId' => $value['dimensions'][2]['name'],
                'date' => $value['dimensions'][3]['name'],
                'clicks' => $value['metrics'][0]
            ];
        }

        return $data;
    }

    private function parserForMetricByGroup(array $metrics): array
    {
        $data = [];

        foreach ($metrics as $value) {
            $data[$this->prepare($value['dimensions'][1]['name'])][] = [
                'clientId' => $value['dimensions'][2]['name'],
                'date' => $value['dimensions'][3]['name'],
                'clicks' => $value['metrics'][0]
            ];
        }

        return $data;
    }

    private function prepare(string $str): string 
    {
        $string = substr($str, 1);

        $filthyId = explode('|', $string)[0];

        $id = explode(':', $filthyId)[1];

        return $id;
    }

    private function findClientForCompaign(array $metrics): array
    {
        $data = [];
        foreach ($metrics as $compaignId => $metric) {
            foreach ($metric as $key => $value) {
                $data[$compaignId][] = $value['clientId'];
            }
            $data[$compaignId] = array_unique($data[$compaignId]);
        }

        return $data;
    }

    private function findClientForGroup(array $metrics): array
    {
        $data = [];
        foreach ($metrics as $groupId => $metric) {
            foreach ($metric as $key => $value) {
                $data[$groupId][] = $value['clientId'];
            }
            $data[$groupId] = array_unique($data[$groupId]);
        }

        return $data;
    }

    private function findClientsInInvoice(array $metricClients): array
    {
        $clientsVisitor = [];
        foreach ($metricClients as $compaignId => $clients) {
            $clientsVisitor[$compaignId] = $this->modelVisitor::whereIn('_ym_uid', $clients)->distinct()->get('client_id');
        }

        $clientsInVisitor = [];
        foreach ($clientsVisitor as $compaignId => $clientsIds) {
            $clientsInVisitor[$compaignId] = $this->modelInvoice::where('invoice_status', 2)->whereIn('client_id', $clientsIds)->distinct()->get();
        }

        return $clientsInVisitor;
    }

}
