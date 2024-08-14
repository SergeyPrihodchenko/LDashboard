<?php

namespace App\Http\Controllers\Direct;

use App\Http\Controllers\Controller;
use App\Models\Direct;
use App\Models\UpdateDirect;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateDirectController extends Controller
{
    public function initUpdateDirect()
    {
        $status = UpdateDirect::where('direct_id', UpdateDirect::HYLOK)->limit(1)->get('conf_update_status')[0]['conf_update_status'];

        if($status) {
            return [
                'date' => UpdateDirect::select(['date_check_update'])->limit(1)->get()->toArray()[0]['date_check_update'],
                'status' => false,
            ];
        }

        UpdateDirect::where('direct_id', UpdateDirect::HYLOK)->update(['conf_update_status' => 1]);

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
            foreach ($params as $value) {
                switch ($value) {
                    case UpdateDirect::HYLOK:
                        $this->updateDirect($value, env('CLIENT_LOGIN_HYLOK'), env('AUTH_TOKEN_DIRECT_HYLOK'), 'hylok');
                        break;
    
                    case UpdateDirect::WIKA:
                        $this->updateDirect($value, env('CLIENT_LOGIN_WIKA'), env('AUTH_TOKEN_DIRECT_WIKA'), 'wika');
                        break;
    
                    case UpdateDirect::SWAGELO_HY_LOK:
                        $this->updateDirect($value, env('CLIENT_LOGIN_SWAGELO_HY_LOK'), env('AUTH_TOKEN_DIRECT_SWAGELO_HY_LOK'), 'swagelo_hy_lok');
                        break;
                }
            }
        }

        UpdateDirect::where('direct_id', 1)->update(['conf_update_status' => 0]);

        return [
            'date' => UpdateDirect::select(['date_check_update'])->limit(1)->get()->toArray()[0]['date_check_update'],
            'status' => true
        ];
    }

    private function updateDirect($value, $clientLogin, $authToken, $title)
    {
        try {
            $dateLastUpdate = UpdateDirect::where('direct_id', $value)
        ->get('date_update')->toArray()[0]['date_update'];
        $date = new \DateTime($dateLastUpdate);
        $date->modify('+1 day');
        $yandex = new Yandex($authToken);
        $data = $yandex->directUpdate($clientLogin, uniqid(), $date->format('Y-m-d'));
        $file_path = Parser::recordingCSV($data, $title);
        $stream = fopen($file_path, 'r');
        } catch (\Exception $th) {
            Log::error($th->getMessage() . ' : ' . date('Y-m-d') . "\n");
        }
        try {
            while ($row = fgetcsv($stream, null, "\t")) {
                Direct::create([
                    'direct_id' => $value,
                    'CampaignId' => $row[0],
                    'LocationOfPresenceId' => $row[1],
                    'LocationOfPresenceName' => $row[2],
                    'CampaignName' => $row[3],
                    'Device' => $row[4],
                    'AdGroupId' => $row[5],
                    'ConversionRate' => $row[6],
                    'Ctr' => $row[7],
                    'AdGroupName' => $row[8],
                    'Clicks' => $row[9],
                    'AvgCpc' => $row[10],
                    'Cost' => $row[11],
                    'Date' => $row[12]
                ]);
            }
            unlink($file_path);
        } catch (\PDOException $e) {
            Log::error($e->getMessage() . ' : ' . date('Y-m-d') . "\n");
        }
        $date = Direct::select('date')->where('direct_id', $value)->orderByDesc('date')->limit(1)->get()->toArray()[0]['date'];
        // $date = date('Y-m-d');
        UpdateDirect::where('direct_id', $value)->update([
            'date_update' => date('Y-m-d', strtotime($date)),
            'date_check_update' => date('Y-m-d'),
            'status_update' => UpdateDirect::UPDATED
        ]);
    }
}
