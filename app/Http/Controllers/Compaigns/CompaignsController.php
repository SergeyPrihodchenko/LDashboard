<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\UpdateDirect;
use App\Services\APIHook\Yandex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

abstract class CompaignsController extends Controller
{
    protected UpdateDirect $updateDirect;
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
        $this->updateDirect = new UpdateDirect();

    }

    public function index()
    {
        $dateUpdateDirect = $this->updateDirect::select(['date_check_update'])->limit(1)->get()->toArray()[0]['date_check_update'];

        $data['dateUpdateDirect'] = $dateUpdateDirect;

        $data['routePath'] = $this->title;

        return Inertia::render('CompaignsPage', ['data' => $data]);
    }

    public function dataByCompaigns()
    {
        $metrics = $this->yandex->metricCompaign();

        $dataOfMetrics = $this->prepareDataOfMetric($metrics['data']);

        unset($metrics);

        $compaignsID = [];
        foreach ($dataOfMetrics as $key => $value) {
            $compaignsID[] = $key;
        }

        $directData = $this->getDirectDataByCompaignsId($compaignsID);

        $data = $this->prepareDataOfDirect($directData);

        unset($directData);

        try {

            $invoices = $this->modelInvoice::select(['client_id', 'invoice_price', 'client_mail'])->where('invoice_status', 2)->distinct()->get()->toArray();

        } catch (\PDOException $e) {
            Log::error($e->getMessage());

            $invoices = [];
        }

        $invoicesId = [];
        foreach ($invoices as $value) {
            $invoicesId[] = $value['client_id'];
        }

        try {

            $invoiceByMetric = $this->modelVisitor::select(['_ym_uid', 'client_id'])->whereIn('client_id', $invoicesId)->get()->toArray();

        } catch (\PDOException $e) {
            Log::error($e->getMessage());

            $invoiceByMetric = [];
        }

        $invoiceList = $this->findClientInInvocie($dataOfMetrics, $invoiceByMetric);

        foreach ($data as $key => $value) {
            $data[$key]['clients'] = [];

            if(isset($invoiceList[$key])) {
                $data[$key]['clients'] = $invoiceList[$key];
            }
        }

        return ['direct' => $data];
    }

    private function findClientInInvocie($metric, $invoiceClients)
    {
        $data = [];
        foreach ($invoiceClients as $ymUid) {
            foreach ($metric as $compaign => $groups) {
                foreach ($groups as $key => $value) {
                    if(in_array($ymUid['_ym_uid'], $groups[$key])) {
                        $data[$compaign][$key][] = $this->modelInvoice::where('client_id', $ymUid['client_id'])
                        ->where('invoice_status', 2)
                        ->get([
                            'client_mail',
                            'invoice_date',
                            'invoice_price'
                        ])->toArray(); 
                    }
                }
            }
        }
        return $data;
    }

    private function getDirectDataByCompaignsId($ids)
    {
        $data = $this->direct::select('CampaignId', 'CampaignName', 'AdGroupId', 'AdGroupName', 'Clicks', 'Cost', 'Date')
        ->whereIn('CampaignId', $ids)
        ->get()
        ->toArray();

        return $data;
    }

    private function prepareDataOfDirect($direct)
    {
        $data = [];
        foreach ($direct as $value) {
            if(!array_key_exists($value['CampaignId'],$data)) {
                $data[$value['CampaignId']] = [
                    'compaignName' => $value['CampaignName'],
                    'groups' => [
                        $value['AdGroupId'] => [
                            'adGroupName' => $value['AdGroupName'],
                            'clicks' => (int)$value['Clicks'],
                            'cost' => (float)$value['Cost']
                        ]
                    ],
                    'clicks' => (int)$value['Clicks'],
                    'cost' => (float)$value['Cost']
                ];
            } else {
                $data[$value['CampaignId']]['clicks'] += (int)$value['Clicks'];
                $data[$value['CampaignId']]['cost'] += (float)$value['Cost'];

                if(!array_key_exists($value['AdGroupId'],$data[$value['CampaignId']]['groups'])) {
                    $data[$value['CampaignId']]['groups'][$value['AdGroupId']] = [
                        'adGroupName' => $value['AdGroupName'],
                        'clicks' => (int)$value['Clicks'],
                        'cost' => (float)$value['Cost']
                    ];
                } else {
                    $data[$value['CampaignId']]['groups'][$value['AdGroupId']]['clicks'] += (int)$value['Clicks'];
                    $data[$value['CampaignId']]['groups'][$value['AdGroupId']]['cost'] += (float)$value['Cost'];
                }
            }

        }
        return $data;
    }


    private function prepareDataOfMetric($metrics)
    {
        $data = [];

        foreach ($metrics as $value) {
            $compaignId = $value['dimensions'][0]['name'];
            if(!preg_match('/^[0-9]+$/', $compaignId)) {
                $expoldeCompaignId = explode('_', $compaignId);
                $compaignId = $expoldeCompaignId[count($expoldeCompaignId) - 1];
            }
            if(!preg_match('/^[0-9]+$/', $compaignId)) {
                continue;
            }
            $data[$compaignId][$this->prepare($value['dimensions'][1]['name'])][] = $value['dimensions'][2]['name'];
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

}
