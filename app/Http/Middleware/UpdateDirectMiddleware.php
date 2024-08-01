<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateDirect as JobsUpdateDirect;
use App\Models\Direct;
use App\Models\UpdateDirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateDirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dateTo = strtotime(date('Y-m-d'));
        $params = [];
        $dateUpdateHylok = strtotime(UpdateDirect::where('direct_id', UpdateDirect::HYLOK)->get('date_check_update')->toArray()[0]['date_check_update']);
        $dateUpdateWika = strtotime(UpdateDirect::where('direct_id', UpdateDirect::WIKA)->get('date_check_update')->toArray()[0]['date_check_update']);
        $dateUpdateSwagelo = strtotime(UpdateDirect::where('direct_id', UpdateDirect::SWAGELO_HY_LOK)->get('date_check_update')->toArray()[0]['date_check_update']);

        if($dateTo != $dateUpdateHylok) {
            UpdateDirect::where('direct_id', UpdateDirect::HYLOK)->update(['status_update' => 0]);
        }
        if($dateTo != $dateUpdateWika) {
            UpdateDirect::where('direct_id', UpdateDirect::WIKA)->update(['status_update' => 0]);
        }
        if($dateTo != $dateUpdateSwagelo) {
            UpdateDirect::where('direct_id', UpdateDirect::SWAGELO_HY_LOK)->update(['status_update' => 0]);
        }

        if (Direct::checkUpdate(UpdateDirect::HYLOK)) {
            $params[] = UpdateDirect::HYLOK;
        }
        if (Direct::checkUpdate(UpdateDirect::WIKA)) {
            $params[] = UpdateDirect::WIKA;
        }
        if (Direct::checkUpdate(UpdateDirect::SWAGELO_HY_LOK)) {
            $params[] = UpdateDirect::SWAGELO_HY_LOK;
        }

        if(!empty($params)) {
            JobsUpdateDirect::dispatch($params);
        }

        return $next($request);
    }
}
