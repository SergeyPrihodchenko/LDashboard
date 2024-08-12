<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\UpdateDirect;
use App\Services\APIHook\Yandex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class ChartController extends Controller
{
    protected UpdateDirect $updateDirect;
    protected int $directID;
    protected Model $modelInvoice;
    protected Model $modelPhone;
    protected Model $sateliPhone;
    protected Yandex $yandex;
    protected $direct;
    protected $title;

    public function __construct(
        $modelInvoice,
        $modelPhone,
        $sateliPhone,
        $direct,
        $metricaToken,
        $metricaCounter,
    )
    {
        $this->modelInvoice = $modelInvoice;
        $this->modelPhone = $modelPhone;
        $this->sateliPhone = $sateliPhone;
        $this->direct = $direct;
        $this->yandex = new Yandex($metricaToken, $metricaCounter);
        $this->updateDirect = new UpdateDirect();
    }

    public function index()
    {
        $dateUpdateDirect = $this->updateDirect::select(['date_check_update'])->limit(1)->get()->toArray()[0]['date_check_update'];

        $dataInvoice = $this->modelInvoice::select('invoice_date', 'invoice_status', 'client_mail_id', 'invoice_price')->distinct()->get();

        $sateliPhone = $this->sateliPhone::select('client_phone', 'invoice_status', 'invoice_price', 'invoice_date')->get();
 
        $haystack = [];
        foreach ($sateliPhone as $key => $value) {
            $haystack[$key] = $value['client_phone'];
        }

        $wikaPhoneBy1C = $this->modelPhone::select('contact_phone_number')->distinct()->get();

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

        foreach ($dataInvoice as $key => $value) {
            $dataInvoice[$key]['invoice_date'] = date('Y-m-d', strtotime($value['invoice_date']));
            $countMail++;
            if(!isset($chartMail[$value['invoice_date']])) {
                $entryPoints[] = date('Y-m-d', strtotime($value['invoice_date']));
                $chartMail[$value['invoice_date']] = 1;
            } else {
                $chartMail[$value['invoice_date']]++;
            }
            if($value['invoice_status'] == 2) {
                $sumPriceForMails = $sumPriceForMails + $dataInvoice[$key]['invoice_price'];
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
            'title' => $this->title,
            'entryPoints' => $entryPoints,
            'chartMail' => $newChartMail,
            'chartPhone' => $newChartPhone,
            'generalData' => [
                'countMails' => $countMail,
                'countCalls' => $countPhone,
                'sumPriceForCalls' => number_format($sumPriceForCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', ''),
            ],
            'dateUpdateDirect' => $dateUpdateDirect
        ]);
    }

    public function dataByDate(Request $request)
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date'
        ]);

        $dateFrom = date('Y-m-d', strtotime($validated['dateFrom']));
        $dateTo = date('Y-m-d', strtotime($validated['dateTo']));
        $entryPoints = [];

        $dataInvoice = $this->modelInvoice::select('invoice_date', 'invoice_status', 'client_mail_id', 'invoice_price')
        ->where([
            ['invoice_date', '>=', "$dateFrom 00:00:00"],
            ['invoice_date', '<=', "$dateTo 23:59:59"]
        ])->distinct()->get();

        $sateliPhone = $this->sateliPhone::select('client_phone', 'invoice_status', 'invoice_price', 'invoice_date')->get();

        $haystack = [];
        foreach ($sateliPhone as $key => $value) {
            $haystack[$key] = $value['client_phone'];
        }

        $wikaPhoneBy1C = $this->modelPhone::select('contact_phone_number')->distinct()->get();

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
        foreach ($dataInvoice as $key => $dateInvoice) {
            $countMail++;
            $entryPoints[] = date('Y-m-d', strtotime($dateInvoice['invoice_date']));
            if(array_key_exists(date('Y-m-d', strtotime($dateInvoice['invoice_date'])), $chartInvoice)) {
                $chartInvoice[date('Y-m-d', strtotime($dateInvoice['invoice_date']))]++;
            } else {
                $chartInvoice[date('Y-m-d', strtotime($dateInvoice['invoice_date']))] = 1;
            }
            if($dateInvoice['invoice_status'] == 2) {
                $sumPriceForMails += $dataInvoice[$key]['invoice_price'];
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





        $compaignsId = $this->parserForMetricByCompaign($this->yandex->metricIdCompaign()['data']);

        $countCliks = $this->direct::whereIn('CampaignId', $compaignsId)->where('Date', '>=', $dateFrom, 'AND', 'Date', '<=', $dateTo)->get('Clicks')->sum('Clicks');

        $invoicePhone = $this->modelPhone::select('contact_phone_number')->where('notification_time', '=>', $dateFrom, 'AND', 'notification_time', '<=', $dateTo)->distinct()->get();

        $phones = [];
        foreach ($invoicePhone as $value) {
            $phones[] = mb_substr($value['contact_phone_number'], 1, strlen($value['contact_phone_number']) - 1);
        }

        $invoicePhones = $this->sateliPhone::whereIn('client_phone', $phones)->distinct()->get('client_phone', 'invoice_status', 'invoice_price');

        $invoicesMail = $this->modelInvoice::select('client_mail', 'invoice_status', 'invoice_price')->where('invoice_date', '>=', $dateFrom, 'AND', 'invoice_date', '<=', $dateTo)->distinct()->get();

        $sumPrice = $this->direct::whereIn('CampaignId', $compaignsId)->where('Date', '>=', $dateFrom, 'AND', 'Date', '<=', $dateTo)->get()->sum('Cost');

        $cpl = 0;
        if((int)$sumPrice != 0 || (int)$countCliks != 0) {
            $cpl = (int)$sumPrice / (int)$countCliks;
        }

        $cpc = 0;
        if($invoicesMail->count() != 0 || $invoicePhones->count() != 0) {
            $cpc = (int)$sumPrice / ($invoicesMail->count() + $invoicePhones->count());
        }

        return [
                'entryPoints' => $entryPoints,
                'chartPhone' => $newChartPhone,
                'chartInvoice' => $newChartInvoice,
                'countMails' => $countMail,
                'countCalls' => $countPhone,
                'sumPriceForCalls' => number_format($sumPriceForCalls, 2, '.', ''),
                'sumPriceForMails' => number_format($sumPriceForMails, 2, '.', ''),
                'castomMetric' => [
                    'cpl' => number_format($cpl, 2, '.', ''),
                    'cpc' => number_format($cpc, 2, '.', ''),
                    'invoices' => $invoicesMail->count() + $invoicePhones->count(),
                    'visits' => $countCliks,
                    'invoicesMail' => $countMail,
                    'invoicePhones' => $countPhone,
                    'mailPrice' => number_format($sumPriceForMails, 2, '.', ''),
                    'phonePrice' => number_format($sumPriceForCalls, 2, '.', ''),
                    ]
            ];
    }

    // НЕ ИСПОЛЬЗУЕТСЯ
    public function fetchDirect()
    {
        $compaignsId = $this->parserForMetricByCompaign($this->yandex->metricIdCompaign()['data']);

        $fromDate = date('Y-m-d', strtotime($this->direct::select(['Date'])->whereIn('CampaignId', $compaignsId)->limit(1)->get()->toArray()[0]['Date']));
        $toDate = date('Y-m-d', strtotime($this->direct::select(['Date'])->whereIn('CampaignId', $compaignsId)->orderByRaw('Date DESC')->limit(1)->get()->toArray()[0]['Date']));
        $sumPrice = $this->direct::whereIn('CampaignId', $compaignsId)->sum('Cost');
        $countCliks = $this->direct::whereIn('CampaignId', $compaignsId)->sum('Clicks');

        return [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'sumPrice' => number_format($sumPrice, 2, '.', ''),
            'countCliks' => $countCliks
            ];
    }

    public function getCastomMetric()
    {
        $compaignsId = $this->parserForMetricByCompaign($this->yandex->metricIdCompaign()['data']);

        $countCliks = $this->direct::whereIn('CampaignId', $compaignsId)->sum('Clicks');

        $invoicePhone = $this->modelPhone::select('contact_phone_number')->distinct()->get();

        $phones = [];
        foreach ($invoicePhone as $value) {
            $phones[] = mb_substr($value['contact_phone_number'], 1, strlen($value['contact_phone_number']) - 1);
        }

        $invoicePhones = $this->sateliPhone::whereIn('client_phone', $phones)->distinct()->get('client_phone', 'invoice_status', 'invoice_price');

        $phonesPrice = $this->sateliPhone::whereIn('client_phone', $phones)->distinct()->get('invoice_price')->where('invoice_status', 2)->sum('invoice_price');

        $invoicesMail = $this->modelInvoice::select('client_mail', 'invoice_status', 'invoice_price')->distinct()->get();

        $mailPrice = $this->modelInvoice::select('invoice_price')->where('invoice_status', 2)->distinct()->get()->sum('invoice_price');

        $sumPrice = $this->direct::whereIn('CampaignId', $compaignsId)->sum('Cost');

        $cpl = (int)$sumPrice / (int)$countCliks;

        $cpc = (int)$sumPrice / ($invoicesMail->count() + $invoicePhones->count());

        return [
            'cpl' => number_format($cpl, 2, '.', ''),
            'cpc' => number_format($cpc, 2, '.', ''),
            'invoices' => $invoicesMail->count() + $invoicePhones->count(),
            'visits' => $countCliks,
            'invoicesMail' => $invoicesMail->count(),
            'invoicePhones' => $invoicePhones->count(),
            'mailPrice' => number_format($mailPrice, 2, '.', ''),
            'phonePrice' => number_format($phonesPrice, 2, '.', ''),

            ];
    }

    private function parserForMetricByCompaign(array $metrics): array
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

            $data[] = $compaignId;
        }

        return $data;
    }

    private function prepareMetricVisits($metric): int
    {
        $data = $metric[0]['metrics'][0];

        return $data;
    }
}
