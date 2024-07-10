<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\SateliPhone;
use App\Models\WikaInvoice;
use App\Models\WikaPhone;
use App\Services\Parser\Parser;
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

        $wikaPhoneBy1C = WikaPhone::select('contact_phone_number')->distinct()->get();

        $wikaPhone = [];
        foreach ($wikaPhoneBy1C as $value) {
            $phone = $value['contact_phone_number'];
            $subPhone = mb_substr($phone, 1, strlen($phone) - 1);
            $key = array_search($subPhone, $haystack);

            if(!empty($key) && !empty($sateliPhone[$key])) {
                $wikaPhone[] = $sateliPhone[$key];
            }
        }

        $entryPoints = [];

        $chartMail = [];
        $countMail = 0;
        $sumPriceForMails = 0.00;

        foreach ($dataWikaInvoice as $key => $value) {
            $dataWikaInvoice[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            $countMail++;
            if(!isset($chartMail[$value['invoice_date']])) {
                $entryPoints[] = date('Y-m-d', strtotime($value['invoice_date']));
                $chartMail[$value['invoice_date']] = 1;
            } else {
                $chartMail[$value['invoice_date']]++;
            }
            if($value['invoice_status'] == 2) {
                $sumPriceForMails = $sumPriceForMails + $dataWikaInvoice[$key]['invoice_price'];
            }
        }

        $sumPriceByCalls = 0.00;
        $chartPhone = [];
        $countPhone = 0;

        foreach ($wikaPhone as $key => $value) {
            $countPhone++;
            $wikaPhone[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            if(!isset($chartPhone[$value['invoice_date']])) {
                $entryPoints[] = date('Y-m-d', strtotime($value['invoice_date']));
                $chartPhone[$value['invoice_date']] = 1;
            } else {
                $chartPhone[$value['invoice_date']]++;
            }
            if($value['invoice_status'] == 2) {
                $sumPriceByCalls += $value['invoice_price'];
            }
        }

        usort($entryPoints, function($a, $b) {
            $dateA = strtotime($a);
            $dateB = strtotime($b);

            if ($dateA == $dateB) {
                return 0;
            } elseif ($dateA > $dateB) {
                return 1;
            } else {
                return -1;
            }
        });

        $newEntryPoints = array_unique($entryPoints);

        $newChartMail = [];
        $newChartPhone = [];

        foreach ($newEntryPoints as $point) {
            if(isset($chartMail[$point])) {
                $newChartMail[$point] = $chartMail[$point];
            } else {
                $newChartMail[$point] = 0;
            }
            if(isset($chartPhone[$point])) {
                $newChartPhone[$point] = $chartPhone[$point];
            } else {
                $newChartPhone[$point] = 0;
            }
        }

        return Inertia::render('Main', [
            'entryPoints' => $newEntryPoints,
            'chartMail' => $newChartMail,
            'chartPhone' => $newChartPhone,
            'test' => ['phone' => $chartPhone, 'mail' => $chartMail],
            'generalData' => [
                'countMails' => "общее количество писем: $countMail",
                'countCalls' =>"общее количество звонков: "  . $countPhone,
                'sumPriceForCalls' => number_format($sumPriceByCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', '')
            ]
        ]);

    }

    public function fetchDirect()
    {
        $direct = Parser::fileReader();

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
            'sumPrice' => "общая сумма за клики : $sum",
            'countCliks' => "общее количество кликов :" . count($direct)
            ];
    }
}
