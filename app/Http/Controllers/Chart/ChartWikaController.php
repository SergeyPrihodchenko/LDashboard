<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\SateliPhone;
use App\Models\WikaInvoice;
use App\Models\WikaPhone;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartWikaController extends Controller
{
    public function indexWika()
    {
        $dataWikaInvoice = WikaInvoice::select('invoice_date', 'invoice_status', 'client_mail_id', 'invoice_price')->distinct()->get();

        $sateliPhone = SateliPhone::select('client_phone', 'invoice_status', 'invoice_price', 'invoice_date')->get();
 
        $haystack = [];
        foreach ($sateliPhone as $key => $value) {
            $haystack[$key] = $value['client_phone'];
        }

        $wikaPhone = WikaPhone::select('contact_phone_number')->distinct()->get();

        $wikaCalls = [];
        foreach ($wikaPhone as $value) {
            $phone = $value['contact_phone_number'];
            $subPhone = mb_substr($phone, 1, strlen($phone) - 1);
            $key = array_search($subPhone, $haystack);

            if(!empty($key)) {
                $wikaCalls[] = $sateliPhone[$key];
            }
        }

        $chartData = [];
        $fullEmail = 0;
        $sumPriceForMails = 0.00;

        foreach ($dataWikaInvoice as $key => $value) {
            $dataWikaInvoice[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            $fullEmail++;
            if(!isset($chartData[$value['invoice_date']])) {
                $chartData[$value['invoice_date']] = 1;
            } else {
                $chartData[$value['invoice_date']]++;
            }
            if($value['invoice_status'] == 2) {
                $sumPriceForMails = $sumPriceForMails + $dataWikaInvoice[$key]['invoice_price'];
            }
        }

        $sumPriceByCalls = 0.00;
        foreach ($wikaCalls as $value) {
            if($value['invoice_status'] == 2) {
                $sumPriceByCalls += $value['invoice_price'];
            }
        }

        return Inertia::render('Main', [
            'chartData' => $chartData,
            'generalData' => [
                'countPhone' => $wikaCalls,
                'countMails' => "общее количество писем: $fullEmail",
                'countCalls' =>"общее количество звонков: " . count($wikaPhone),
                'sumPriceForCalls' => number_format($sumPriceByCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', '')
            ]
        ]);

    }

    public function fetchDirect()
    {
        $direct = Parser::fileReader();

        // if(!$direct) {
        //   $result = $this->fetchDirect();
        //   if(!$result) {
        //     $this->fetchDirect();
        //   } else {
        //     return $result;
        //   }
        // }

        $sum = 0;


        foreach ($direct as $key => $value) {
            $sum += $value;
        }

        $directKeys = array_keys($direct);

        $sum = number_format($sum, 2, '.', '');

        $fromDate = $directKeys[0];
        $toDate = $directKeys[count($directKeys) - 1];

        return [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'sumPrice' => "общая сумма за переход: $sum",
            'countCliks' => 'общее количество переходов: ' . count($direct)
            ];
    }
}
