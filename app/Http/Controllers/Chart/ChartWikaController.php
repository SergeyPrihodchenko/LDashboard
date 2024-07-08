<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartWikaController extends Controller
{
    public function indexWika()
    {
        $data = WikaInvoice::select('invoice_date', 'invoice_status', 'client_mail_id')->distinct('client_mail_id')->get();

        $chartData = [];
        $fullEmail = 0;

        foreach ($data as $key => $value) {
            $data[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            $fullEmail++;
            if(!isset($chartData[$value['invoice_date']])) {
                $chartData[$value['invoice_date']] = 1;
            } else {
                $chartData[$value['invoice_date']]++;
            }
        }

        return Inertia::render('Main', ['chartData' => ['chart' => $chartData, 'generalData' => "общее количество писем: $fullEmail"]]);

    }

    public function indexDirect()
    {
        $direct = Parser::fileReader();

        $sum = 0;

        foreach ($direct as $key => $value) {
            $sum += $value;
        }

        $sum = number_format($sum, 2, '.', '');

        return Inertia::render('Main', ['chartData' => ['chart' => $direct, 'generalData' => "общая сумма: $sum"]]);
    }
}
