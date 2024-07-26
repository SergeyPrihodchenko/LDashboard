<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateDirectWika;
use App\Models\DirectWika;
use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateDirectWikaMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lastDate = DirectWika::checkUpdate();
        
        if(!$lastDate) {

            return $next($request);

        }

        $date = new DateTime($lastDate);
        $date->modify('+1 day');

        $yandex = new Yandex(env('AUTH_TOKEN_DIRECT_WIKA'));

        $data = $yandex->directUpdate(WikaInvoice::CLIENT_LOGIN, uniqid(), $date->format('Y-m-d'));

        $file_path = Parser::recordingCSV($data, 'wika');

        if(!$file_path) {
            Log::error("Ошибка при записи данных из директа в файл -- " . date('Y-m-d') . "\n");
            return $next($request);
        }

        UpdateDirectWika::dispatch($file_path);

        return $next($request);
    }
}
