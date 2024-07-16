<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateDirect;
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

        $yandex = new Yandex(env('AUTH_TOKEN_DIRECT'));

        $data = $yandex->directUpdate(WikaInvoice::CLIENT_LOGIN, uniqid(), $lastDate);

        $file_path = Parser::recordingCSV($data);

        if(!$file_path) {
            Log::error("Ошибка при записи данных из директа в файл" . date('Y-m-d') . "\n");
            return $next($request);
        }

        UpdateDirect::dispatch($file_path);

        return $next($request);
    }
}
