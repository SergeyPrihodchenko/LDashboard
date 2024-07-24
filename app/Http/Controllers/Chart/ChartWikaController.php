<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\Direct;
use App\Models\SateliPhone;
use App\Models\WikaInvoice;
use App\Models\WikaPhone;
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

        $sumPriceForCalls = 0.00;
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
                $sumPriceForCalls += $value['invoice_price'];
            }
        }

        $entryPoints = array_unique($entryPoints);

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


        $newChartMail = [];
        $newChartPhone = [];

        foreach ($entryPoints as $point) {
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

        return Inertia::render('ChartPage', [
            'entryPoints' => $entryPoints,
            'chartMail' => $newChartMail,
            'chartPhone' => $newChartPhone,
            'generalData' => [
                'countMails' => $countMail,
                'countCalls' => $countPhone,
                'sumPriceForCalls' => number_format($sumPriceForCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', '')
            ]
        ]);

    }

    public function dataWikaByDate(Request $request)
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date'
        ]);

        $dateFrom = date('Y-m-d', strtotime($validated['dateFrom']));
        $dateTo = date('Y-m-d', strtotime($validated['dateTo']));
        $entryPoints = [];

        $dataWikaInvoice = WikaInvoice::select('invoice_date', 'invoice_status', 'client_mail_id', 'invoice_price')->where('invoice_date', '>', "$dateFrom 00:00:00", 'AND', 'invoice_date', '<', "$dateTo 23:59:59")->distinct()->get();

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

        $countMail = 0;
        $sumPriceForMails = 0.00;
        $chartInvoice = [];
        foreach ($dataWikaInvoice as $key => $value) {
            $countMail++;
            $entryPoints[] = date('Y-m-d', strtotime($value['invoice_date']));
            if(array_key_exists(date('Y-m-d', strtotime($value['invoice_date'])), $chartInvoice)) {
                $chartInvoice[date('Y-m-d', strtotime($value['invoice_date']))]++;
            } else {
                $chartInvoice[date('Y-m-d', strtotime($value['invoice_date']))] = 1;
            }
            if($value['invoice_status'] == 2) {
                $sumPriceForMails += $dataWikaInvoice[$key]['invoice_price'];
            }
        }

        $sumPriceForCalls = 0.00;
        $countPhone = 0;
        $chartPhone = [];
        foreach ($wikaPhone as $value) {
            if(strtotime("$dateFrom 00:00:00") < strtotime($value['invoice_date']) && strtotime("$dateTo 23:59:59") > strtotime($value['invoice_date'])) {
                $countPhone++;
                $entryPoints[] = date('Y-m-d', strtotime($value['invoice_date']));
                if(array_key_exists(date('Y-m-d', strtotime($value['invoice_date'])), $chartPhone)) {
                    $chartPhone[date('Y-m-d', strtotime($value['invoice_date']))]++;
                } else {
                    $chartPhone[date('Y-m-d', strtotime($value['invoice_date']))] = 1;
                }
                if($value['invoice_status'] == 2) {
                    $sumPriceForCalls += $value['invoice_price'];
                }
            }
        }

        $entryPoints = array_unique($entryPoints);

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

        $newChartPhone = [];
        $newChartInvoice = [];
        foreach ($entryPoints as $point) {
            if(isset($chartInvoice[$point])) {
                $newChartInvoice[$point] = $chartInvoice[$point];
            } else {
                $newChartInvoice[$point] = 0;
            }
            if(isset($chartPhone[$point])) {
                $newChartPhone[$point] = $chartPhone[$point];
            } else {
                $newChartPhone[$point] = 0;
            }
        }

        return [
                'entryPoints' => $entryPoints,
                'chartPhone' => $newChartPhone,
                'chartInvoice' => $newChartInvoice,
                'countMails' => $countMail,
                'countCalls' => $countPhone,
                'sumPriceForCalls' => number_format($sumPriceForCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', '')
            ];
    }

    public function fetchDirect()
    {
        $data = Direct::all()->toArray();
        $fromDate = date('Y-m-d', strtotime($data[0]['Date']));
        $toDate = date('Y-m-d', strtotime($data[count($data) - 1]['Date']));
        $sumPrice = 0;
        $countCliks = 0;

        foreach ($data as $key => $value) {
            $sumPrice += (float)$value['Cost'];
            $countCliks += (int) $value['Clicks'];
        }

        return [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'sumPrice' => number_format($sumPrice, 2, '.', ','),
            'countCliks' => $countCliks
            ];
    }
}
