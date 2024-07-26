<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateDirectSwagelo;
use App\Models\DirectSwagelo;
use App\Models\SwageloInvoice;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateDirectSwageloMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lastDate = DirectSwagelo::checkUpdate();
        
        if(!$lastDate) {

            return $next($request);

        }

        $date = new DateTime($lastDate);
        $date->modify('+1 day');

        $yandex = new Yandex(env('AUTH_TOKEN_DIRECT_SWAGELO'));

        $data = $yandex->directUpdate(SwageloInvoice::CLIENT_LOGIN, uniqid(), $date->format('Y-m-d'));

        $file_path = Parser::recordingCSV($data, 'swagelo');

        if(!$file_path) {
            Log::error("Ошибка при записи данных из директа в файл -- " . date('Y-m-d') . "\n");
            return $next($request);
        }

        UpdateDirectSwagelo::dispatch($file_path);

        return $next($request);
    }
}
