<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\Direct;
use App\Models\WikaVisitor;
use App\Services\APIHook\Yandex;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompaignsController extends Controller
{
    public function index()
    {
        $direct = Direct::all('CampaignId', 'CampaignName', 'AdGroupId', 'AdGroupName', 'Clicks', 'Cost', 'Date');

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

        $yandex = new Yandex(env('AUTH_TOKEN_METRIC'), env('COUNTER_ID_METRIC'));

        $metrics = $yandex->metricCompaign();

        $metrics = $this->parserForMetric($metrics['data']);

        $data['metrics'] = $metrics;

        $clientsByCompaign = $this->findClientForCompaign($metrics);

        $clientsIdInVisitor = [];
        foreach ($clientsByCompaign as $compaignId => $clients) {
            $clientsIdInVisitor[$compaignId] = WikaVisitor::whereIn('_ym_uid', $clients)->get('client_id');
        }

        $data['test'] = $clientsIdInVisitor;

        return Inertia::render('Compaigns', ['data' => $data]);
    }

    private function parserForMetric(array $metrics): array
    {
        $data = [];

        foreach ($metrics as $value) {
            if(!array_key_exists($value['dimensions'][0]['name'], $data)) {
                $data[$value['dimensions'][0]['name']][] = [
                    'compaignGroupId' => $this->prepare($value['dimensions'][1]['name']),
                    'clientId' => $value['dimensions'][2]['name'],
                    'date' => $value['dimensions'][3]['name'],
                    'clicks' => $value['metrics'][0]
                ];
            } else {
                $data[$value['dimensions'][0]['name']][] = [
                    'compaignGroupId' => $this->prepare($value['dimensions'][1]['name']),
                    'clientId' => $value['dimensions'][2]['name'],
                    'date' => $value['dimensions'][3]['name'],
                    'clicks' => $value['metrics'][0]
                ];
            }
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

}
