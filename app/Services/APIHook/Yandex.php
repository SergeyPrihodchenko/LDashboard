<?php

namespace App\Services\APIHook;

final class Yandex {

    private const YANDEX_METRIC_URL = 'https://api-metrika.yandex.net/';
    private const YANDEX_DIRECT_URL = 'https://api.direct.yandex.com/json/v5/reports';
    private $dateFrom;
    private $dateTo;

    public function __construct(
        private string $token,
        private ?string $counter_id = NULL,
    )
    {
        $this->token = $token;
        $this->dateFrom = date('Y-m-d', mktime(0,0,0,0,0,2023));
        $this->dateTo = date('Y-m-d');
    }

    public function fetchDirect(string $clientLogin, $id, $counter = 1)
    {
        $uniqId = $id;

        $json = [
            'params' => [
                'SelectionCriteria' => [
                    'DateFrom' => $this->dateFrom,
                    'DateTo' => $this->dateTo
                ],
            'DateRangeType' => 'CUSTOM_DATE',
            'ReportType' => 'SEARCH_QUERY_PERFORMANCE_REPORT',
            'FieldNames' => ["Cost", "Date"],
            'ReportName' => "$uniqId",
            'Format' => 'TSV',
            'IncludeVAT' => 'YES',
            'IncludeDiscount' => 'NO'
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
            'headers' => [
                'returnMoneyInMicros' => 'false',
                'Authorization' => $this->token,
                'Client-Login' => $clientLogin,
                'Accept-Language' => 'ru',
                'Content-Type' => 'application/json;charset=utf-8',
                'skipReportHeader' => 'true',
                'skipColumnHeader' => 'true',
                'skipReportSummary' => 'true'
            ],
            'json' => $json
        ]);

        $status = $request->getStatusCode();

        if($status == '200') {

            $data = $request->getBody();
            return $data;

        }

        if($status == '202') {

            sleep(3);

            $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
                'headers' => [
                    'returnMoneyInMicros' => 'false',
                    'Authorization' => $this->token,
                    'Client-Login' => $clientLogin,
                    'Accept-Language' => 'ru',
                    'Content-Type' => 'application/json;charset=utf-8',
                    'skipReportHeader' => 'true',
                    'skipColumnHeader' => 'true',
                    'skipReportSummary' => 'true'
                ],
                'json' => $json
            ]);

            $status = $request->getStatusCode();

            if($status == '200') {

                $data = $request->getBody();
                return $data;
    
            }
        }

        if ($status == '201') {
            
            sleep(3);

            $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
                'headers' => [
                    'returnMoneyInMicros' => 'false',
                    'Authorization' => $this->token,
                    'Client-Login' => $clientLogin,
                    'Accept-Language' => 'ru',
                    'Content-Type' => 'application/json;charset=utf-8',
                    'skipReportHeader' => 'true',
                    'skipColumnHeader' => 'true',
                    'skipReportSummary' => 'true'
                ],
                'json' => $json
            ]);

          $status = $request->getStatusCode();

          if($status == '200') {

            $data = $request->getBody();
            return $data;

            }
        }

        if($request->getStatusCode() != '200') {
            $counter++;
            if($counter > 3) {
                return false;
            }

            $result = $this->fetchDirect($clientLogin, $uniqId, $counter);

            return $result;
        }
    }

    public function directUpdate(string $clientLogin, $id, $date, $counter = 1)
    {
        $uniqId = $id;
        $dateFrom = $date;

        $json = [
            'params' => [
                'SelectionCriteria' => [
                    'DateFrom' => $dateFrom,
                    'DateTo' => $this->dateTo
                ],
            'DateRangeType' => 'CUSTOM_DATE',
            'ReportType' => 'CUSTOM_REPORT',
            'FieldNames' => [
                "CampaignId",
                "LocationOfPresenceId",
                "LocationOfPresenceName",
                "CampaignName",
                "Device",
                "AdGroupId",
                "ConversionRate", 
                "Ctr",
                "AdGroupName",
                "Clicks", 
                "AvgCpc",
                "Cost",
                "Date"
                    ],
            'ReportName' => "$uniqId",
            'Format' => 'TSV',
            'IncludeVAT' => 'YES',
            'IncludeDiscount' => 'NO'
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
            'headers' => [
                'returnMoneyInMicros' => 'false',
                'Authorization' => $this->token,
                'Client-Login' => $clientLogin,
                'Accept-Language' => 'ru',
                'Content-Type' => 'application/json;charset=utf-8',
                'skipReportHeader' => 'true',
                'skipColumnHeader' => 'true',
                'skipReportSummary' => 'true'
            ],
            'json' => $json
        ]);

        $status = $request->getStatusCode();

        if($status == '200') {

            $data = $request->getBody();
            return $data;

        }

        if($status == '202') {

            sleep(2);

            $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
                'headers' => [
                    'returnMoneyInMicros' => 'false',
                    'Authorization' => $this->token,
                    'Client-Login' => $clientLogin,
                    'Accept-Language' => 'ru',
                    'Content-Type' => 'application/json;charset=utf-8',
                    'skipReportHeader' => 'true',
                    'skipColumnHeader' => 'true',
                    'skipReportSummary' => 'true'
                ],
                'json' => $json
            ]);

            $status = $request->getStatusCode();

            if($status == '200') {

                $data = $request->getBody();
                return $data;
    
            }
        }

        if ($status == '201') {
            
            sleep(2);

            $request = $client->request('POST', Yandex::YANDEX_DIRECT_URL, [
                'headers' => [
                    'returnMoneyInMicros' => 'false',
                    'Authorization' => $this->token,
                    'Client-Login' => $clientLogin,
                    'Accept-Language' => 'ru',
                    'Content-Type' => 'application/json;charset=utf-8',
                    'skipReportHeader' => 'true',
                    'skipColumnHeader' => 'true',
                    'skipReportSummary' => 'true'
                ],
                'json' => $json
            ]);

          $status = $request->getStatusCode();

          if($status == '200') {

            $data = $request->getBody();
            return $data;

            }
        }

        if($request->getStatusCode() != '200') {
            $counter++;
            if($counter > 3) {
                return false;
            }

            $result = $this->directUpdate($clientLogin, $uniqId, $dateFrom, $counter);

            return $result;
        }
    }

    private function fetchMetric(string $id, $counter = 1) 
    {
        $client = new \GuzzleHttp\Client(['base_uri' => Yandex::YANDEX_METRIC_URL]);
        $result = $client->request('GET', 'stat/v1/data', [
            'headers' => [
                'Authorization' => $this->token,
                'Content-Type' => 'application/x-yametrika+json'
            ],
            'query' => [
                'accuracy' => 1,
                'preset' => 'sources_search_phrases',
                'ids' => $this->counter_id,
                'metrics' => 'ym:s:visits,ym:s:users',
                'date1' => $this->dateFrom,
                'date2' => $this->dateTo,
                'dimensions' => 'ym:s:clientID,ym:s:firstVisitDate,ym:s:startURL,ym:s:<attribution>UTMTerm,ym:s:regionCity,ym:s:deviceCategory',
                'filters' => "ym:s:clientID=={$id}",
                'limit' => 10000
            ]
        ]);

        if($counter == 3) {
            file_put_contents(__DIR__ . '/api_logs.txt', 'Ошибка при запросе к яндекс метрике' . "\n", FILE_APPEND);
            exit();
        }

        $status = $result->getStatusCode();

        if($status != '200') {

            $counter = $counter + 1;
            sleep(1);

            $this->fetchMetric($id, $counter);

        }

        $data = $result->getBody();

        return $data;
    }


    private function fetchMetricCompaign($counter = 1) 
    {
        $client = new \GuzzleHttp\Client(['base_uri' => Yandex::YANDEX_METRIC_URL]);
        $result = $client->request('GET', 'stat/v1/data', [
            'headers' => [
                'Authorization' => $this->token,
                'Content-Type' => 'application/x-yametrika+json'
            ],
            'query' => [
                'ids' => $this->counter_id,
                'metrics' => 'ym:s:visits',
                'date1' => '2023-04-01',
                'date2' => $this->dateTo,
                'dimensions' => 'ym:s:<attribution>UTMCampaign,ym:s:<attribution>UTMContent,ym:s:ClientID,ym:s:browserDate',
                'limit' => 10000
            ]
        ]);

        if($counter == 3) {
            file_put_contents(__DIR__ . '/api_logs.txt', 'Ошибка при запросе к яндекс метрике' . "\n", FILE_APPEND);
            exit();
        }

        $status = $result->getStatusCode();

        if($status != '200') {

            $counter = $counter + 1;
            sleep(1);

            $this->fetchMetricCompaign($counter);

        }

        $data = $result->getBody();

        return $data;
    }

    private function fetchMetricIdCompaign($counter = 1)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => Yandex::YANDEX_METRIC_URL]);
        $result = $client->request('GET', 'stat/v1/data', [
            'headers' => [
                'Authorization' => $this->token,
                'Content-Type' => 'application/x-yametrika+json'
            ],
            'query' => [
                'ids' => $this->counter_id,
                'metrics' => 'ym:s:visits',
                'date1' => '2023-04-01',
                'date2' => $this->dateTo,
                'dimensions' => 'ym:s:<attribution>UTMCampaign',
                'limit' => 10000
            ]
        ]);

        if($counter == 3) {
            file_put_contents(__DIR__ . '/api_logs.txt', 'Ошибка при запросе к яндекс метрике' . "\n", FILE_APPEND);
            exit();
        }

        $status = $result->getStatusCode();

        if($status != '200') {

            $counter = $counter + 1;
            sleep(1);

            $this->fetchMetricCompaign($counter);

        }

        $data = $result->getBody();

        return $data;
    }

    private function fetchMetricVisits($dateFrom, $dateTo, $counter = 1)
    {
        $client = new \GuzzleHttp\Client(['base_uri' => Yandex::YANDEX_METRIC_URL]);
        $result = $client->request('GET', 'stat/v1/data', [
            'headers' => [
                'Authorization' => $this->token,
                'Content-Type' => 'application/x-yametrika+json'
            ],
            'query' => [
                'ids' => $this->counter_id,
                'metrics' => 'ym:s:visits',
                'date1' => $dateFrom,
                'date2' => $dateTo,
                'limit' => 10000
            ]
        ]);

        if($counter == 3) {
            file_put_contents(__DIR__ . '/api_logs.txt', 'Ошибка при запросе к яндекс метрике' . "\n", FILE_APPEND);
            exit();
        }

        $status = $result->getStatusCode();

        if($status != '200') {

            $counter = $counter + 1;
            sleep(1);

            $this->fetchMetricVisits($dateFrom, $dateTo, $counter);

        }

        $data = $result->getBody();

        return $data;
    }

    public function metricVisits($dateFrom, $dateTo): array
    {
        $data = $this->fetchMetricVisits($dateFrom, $dateTo);

        $data = json_decode($data, true);

        return $data;
    }

    public function metricById($id): array
    {
       $data = $this->fetchMetric($id);

       $data = json_decode($data, true);

       return $data;
    }
    
    public function metricCompaign(): array
    {
       $data = $this->fetchMetricCompaign();

       $data = json_decode($data, true);

       return $data;
    }

    public function metricIdCompaign(): array
    {
       $data = $this->fetchMetricIdCompaign();

       $data = json_decode($data, true);

       return $data;
    }


}