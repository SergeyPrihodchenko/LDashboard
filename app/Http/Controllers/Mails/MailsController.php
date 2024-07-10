<?php

namespace App\Http\Controllers\Mails;

use App\Http\Controllers\Controller;
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

        return Inertia::render('Mails', ['data' => ['rows' => $data, 'title' => 'Wika']]);
    }

    public function indexSwagelo()
    {
        $data = SwageloInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('Mails', ['data' => ['rows' => $data, 'title' => 'Swagelo']]);
    }

    public function indexHylok()
    {
        $data = HylokInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        return Inertia::render('Mails', ['data' => ['rows' => $data, 'title' => 'Hylok']]);
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

        foreach ($dataMetric['data'] as $value) {
            $data['data'][date("Y-m-d", strtotime($value['dimensions'][1]['name']))][] = [
                'title' => 'Яндекс',
                'client_id' => $value['dimensions'][0]['name'],
                'date' => $value['dimensions'][1]['name'],
                'url' => $value['dimensions'][2]['name'],
                'favicon' => $value['dimensions'][2]['favicon'],
                'meric_visits' => $value['metrics'][0],
                'meric_users' => $value['metrics'][1]
            ];
        }

        return $data;
    }
}
