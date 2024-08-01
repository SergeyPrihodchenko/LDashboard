<?php

namespace App\Http\Controllers\Mails;

use App\Http\Controllers\Controller;
use App\Services\APIHook\Yandex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class MailsController extends Controller
{

    protected Model $model;
    protected Model $modelVisits;
    protected Yandex $yandex;
    protected $direct;
    protected string $title;
    
    public function __construct(
        $model,
        $modelVisits,
        $metricToken,
        $metricCounter,
        $direct
    )
    {
        $this->model = $model;
        $this->modelVisits = $modelVisits;
        $this->yandex = new Yandex($metricToken, $metricCounter);
        $this->direct = $direct;
    }

    public function index()
    {
        $data = $this->model::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('MailsPage', ['data' => ['rows' => $data, 'title' => $this->title]]);
    }

    public function general(Request $request)
    {
        $mail = $request->post('mail');

        $data1C = $this->model::select('client_id', 'invoice_id', 'invoice_status', 'invoice_date', 'invoice_price', 'client_code', 'client_mail')->where('client_mail', $mail)->distinct()->get();

        $ym_uid = $this->modelVisits::select('_ym_uid')->where('client_id', $data1C[0]['client_id'])->limit(1)->get();

        $data = [];
        $sumPrice = 0;

        if(empty($ym_uid[0]['_ym_uid'])) {
            return $this->parse1CData($data1C);
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

        $dataMetric = $this->yandex->metricById($ym_uid[0]['_ym_uid']);

        $countClicks = count($dataMetric['data']);
        $data['countClicks'] = $countClicks;
        $directs = [];

        foreach ($dataMetric['data'] as $key => $value) {

            if($cmId = $this->serchCmId($dataMetric['data'][0]['dimensions'][2]['name'])) {
                if(!array_key_exists(date("Y-m-d", strtotime($value['dimensions'][1]['name'])), $directs)) {
                    $metricParams['cmId'] = $cmId;
                    $metricParams['date'] = $value['dimensions'][1]['name'];
                    $metricParams['regionId'] = $value['dimensions'][4]['id'];
                    $direct = $this->direct::select('CampaignName', 'AdGroupName', 'Cost', 'LocationOfPresenceName', 'AvgCpc')
                    ->where('CampaignId', $metricParams['cmId']['CompaignId'])
                    ->where('AdGroupId', $metricParams['cmId']['AdGroupId'])
                    ->where('LocationOfPresenceId', $metricParams['regionId'])
                    ->where('Clicks', $countClicks)
                    ->where('Date', $metricParams['date'])
                    ->get()
                    ->toArray();
                    if(!empty($direct)) {
                        $directs[date("Y-m-d", strtotime($value['dimensions'][1]['name']))] = [
                            'costClicks' => $direct[0]['Cost'],
                            'avgCpc' => $direct[0]['AvgCpc'],
                            'adGroupName' => $direct[0]['AdGroupName'],
                            'campaignName' => $direct[0]['CampaignName']
                        ];
                    }
                }
            }

            $path = $value['dimensions'][2]['name'];
            if(strripos($value['dimensions'][2]['name'], '?') !== false) {
                $path = mb_substr($value['dimensions'][2]['name'], 0, strripos($value['dimensions'][2]['name'], '?'));
            }

            $data['data'][date("Y-m-d", strtotime($value['dimensions'][1]['name']))][] = [
                'title' => 'Яндекс',
                'client_id' => $value['dimensions'][0]['name'],
                'date' => $value['dimensions'][1]['name'],
                'url' => $path,
                'favicon' => $value['dimensions'][2]['favicon'],
                'keyPhrase' => $value['dimensions'][3]['name'],
                'meric_visits' => $value['metrics'][0],
                'meric_users' => $value['metrics'][1],
                'avgCpc' => isset($direct[0]['AvgCpc']) ? $direct[0]['AvgCpc'] : null,
                'adGroupName' => isset($direct[0]['AdGroupName']) ? $direct[0]['AdGroupName'] : null,
                'campaignName' => isset($direct[0]['CampaignName']) ? $direct[0]['CampaignName'] : null,
                'city' => isset($direct[0]['LocationOfPresenceName']) ? $direct[0]['LocationOfPresenceName'] : null
            ];

            unset($dataMetric['data'][$key]);
        }

        $sumCost = 0;
        foreach ($directs as $direct) {
            $sumCost += (float) $direct['costClicks'];
        }
        $data['costClicks'] = $sumCost;

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

    private function parse1CData($data1C)
    {
        $data = [];
        $sumPrice = 0;

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
}
