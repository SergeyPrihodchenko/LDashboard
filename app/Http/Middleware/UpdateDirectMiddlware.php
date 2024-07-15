<?php

namespace App\Http\Middleware;

use App\Models\Direct;
use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateDirectMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lastDate = Direct::checkUpdate();

        if(!$lastDate) {

            return $next($request);

        }

        $yandex = new Yandex(env('AUTH_TOKEN_DIRECT '));

        $data = $yandex->directUpdate(WikaInvoice::CLIENT_LOGIN, uniqid(), $lastDate);

        $file_path = Parser::recordingCSV($data);

        if(!$file_path) {
            Log::error("Ошибка при записи данных из директа в файл" . date('Y-m-d') . "\n");
            return $next($request);
        }

        $stream = fopen($file_path, 'r');

        try {
            while ($row = fgetcsv($stream, null, "\t")) {
                Direct::create([
                    'CampaignId' => $row[0],
                    'CampaignName' => $row[1],
                    'AdGroupId' => $row[2],
                    'CompaingType' => $row[3],
                    'AdGroupName' => $row[4],
                    'AvgCpc' => $row[5],
                    'Cost' => $row[6],
                    'Date' => $row[7]
                ]);
            }
        } catch (\PDOException $e) {

            Log::error($e->getMessage() . ' : ' . date('Y-m-d') . "\n");
            return $next($request);

        }

        return $next($request);
    }
}
