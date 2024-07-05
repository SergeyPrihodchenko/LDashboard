<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\WikaInvoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartWikaController extends Controller
{
    public function index()
    {
        $data = WikaInvoice::all('invoice_date', 'invoice_status', 'client_mail_id')->unique('client_mail_id');

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

        return Inertia::render('Main', ['chartData' => ['chart' => $chartData, 'emailsCount' => $fullEmail]]);
    }
}
