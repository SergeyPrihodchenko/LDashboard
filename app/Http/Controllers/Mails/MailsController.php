<?php

namespace App\Http\Controllers\Mails;

use App\Http\Controllers\Controller;
use App\Jobs\Test;
use App\Models\Direct;
use App\Models\HylokInvoice;
use App\Models\SwageloInvoice;
use App\Models\WikaInvoice;
use App\Models\WikaVisitor;
use App\Services\APIHook\Yandex;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailsController extends Controller
{
    public function indexWika()
    {
        $data = WikaInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('MailsPage', ['data' => ['rows' => $data, 'title' => 'Wika']]);
    }

    public function indexSwagelo()
    {
        $data = SwageloInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('MailsPage', ['data' => ['rows' => $data, 'title' => 'Swagelo']]);
    }

    public function indexHylok()
    {
        $data = HylokInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('MailsPage', ['data' => ['rows' => $data, 'title' => 'Hylok']]);
    }

    public function wikaGeneral(Request $request)
    {
        $mail = $request->post('mail');

        $data1C = WikaInvoice::select('client_id', 'invoice_id', 'invoice_status', 'invoice_date', 'invoice_price', 'client_code', 'client_mail')->where('client_mail', $mail)->distinct()->get();

        $ym_uid = WikaVisitor::select('_ym_uid')->where('client_id', $data1C[0]['client_id'])->limit(1)->get();

        $data = [];
        $sumPrice = 0;

        if(empty($ym_uid[0]['_ym_uid'])) {
            foreach ($data1C as $el) {
                $el['title'] = '1С';
                if($el['invoice_status'] == 2) {
                    $sumPrice += $el['invoice_price'];
                }
                $data['data'][date("Y-m-d", strtotime($el['invoice_date']))][] = $el;
            }

            $data['client_code'] = $data1C[0]['client_code'];
            $data['client_mail'] = $data1C[0]['client_mail'];
            $data['client_id'] = $data1C[0]['client_id'];
            $data['sum_price'] = $sumPrice;

            return $data;
        }


        foreach ($data1C as $el) {
            $el['title'] = '1С';
            if($el['invoice_status'] == 2) {
                $sumPrice += $el['invoice_price'];
            }
            $data['data'][date("Y-m-d", strtotime($el['invoice_date']))][] = $el;
        }

        $data['client_code'] = $data1C[0]['client_code'];
        $data['client_mail'] = $data1C[0]['client_mail'];
        $data['client_id'] = $data1C[0]['client_id'];
        $data['sum_price'] = $sumPrice;
        $data['client_ym_uid'] = $ym_uid[0]['_ym_uid'];

        $yandex = new Yandex($_SERVER['AUTH_TOKEN_METRIC'], $_SERVER['COUNTER_ID_METRIC']);

        $dataMetric = $yandex->metricById($ym_uid[0]['_ym_uid']);

        $countClicks = count($dataMetric['data']);
        $data['countClicks'] = $countClicks;
        foreach ($dataMetric['data'] as $value) {
            $path = mb_substr($value['dimensions'][2]['name'], 0, strripos($value['dimensions'][2]['name'], '?'));
            $data['data'][date("Y-m-d", strtotime($value['dimensions'][1]['name']))][] = [
                'title' => 'Яндекс',
                'client_id' => $value['dimensions'][0]['name'],
                'date' => $value['dimensions'][1]['name'],
                'url' => $path,
                'favicon' => $value['dimensions'][2]['favicon'],
                'keyPhrase' => $value['dimensions'][3]['name'],
                'meric_visits' => $value['metrics'][0],
                'meric_users' => $value['metrics'][1]
            ];
        }

        if($cmId = $this->serchCmId($dataMetric['data'][0]['dimensions'][2]['name'])) {
            $metricParams['cmId'] = $cmId;
            $metricParams['date'] = $value['dimensions'][1]['name'];
            $metricParams['regionId'] = $value['dimensions'][4]['id'];
            $metricParams['device'] = $value['dimensions'][5]['id'];
            $direct = Direct::select('CampaignName', 'AdGroupName', 'Cost', 'LocationOfPresenceName', 'AvgCpc', 'Ctr')
            ->where('CampaignId', $metricParams['cmId']['CompaignId'])
            ->where('AdGroupId', $metricParams['cmId']['AdGroupId'])
            ->where('LocationOfPresenceId', $metricParams['regionId'])
            ->where('Device', strtoupper($metricParams['device']))
            ->where('Clicks', $countClicks)
            ->where('Date', $metricParams['date'])
            ->get()
            ->toArray();
            $data['costClicks'] = $direct[0]['Cost'];
            $data['ctr'] = $direct[0]['Ctr'];
            $data['avgCpc'] = $direct[0]['AvgCpc'];
            $data['adGroupName'] = $direct[0]['AdGroupName'];
            $data['campaignName'] = $direct[0]['CampaignName'];
            $data['city'] = $direct[0]['LocationOfPresenceName'];
        }

        return $data;
    }

    private function serchCmId(string $url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if(!$query) {
            return false;
        }

        $checkCmId = mb_strpos($query, 'cm_id');
        if($checkCmId === false) {
            return false;
        }

        $cmId = mb_substr($query, 0, strpos($query, '&'));
        $params = mb_substr($cmId, mb_strpos($cmId, '=') + 1, mb_strlen($cmId));
        $arryCmId = explode('_', $params);
        $data = [
            'CompaignId' => $arryCmId[0],
            'AdGroupId' => $arryCmId[1]
        ];

        return $data;
    }
}
