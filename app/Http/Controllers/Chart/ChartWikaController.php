<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartWikaController extends Controller
{
    public function index()
    {
        $data = WikaInvoice::all('invoice_date', 'invoice_status', 'client_mail_id')->unique('client_mail_id');

        $chartData = [];

        foreach ($data as $key => $value) {
            $data[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            if(!isset($chartData[$value['invoice_date']])) {
                $chartData[$value['invoice_date']] = 1;
            } else {
                $chartData[$value['invoice_date']]++;
            }
        }

        $test = new Yandex($_SERVER['AUTH_TOKEN_DIRECT']);

        $id = uniqid();

        file_put_contents(__DIR__ . '/../../../../public/test.csv', $test->fetchDirect(WikaInvoice::CLIENT_LOGIN, $id));

        return Inertia::render('Main', ['chartData' => $chartData]);
    }
}
