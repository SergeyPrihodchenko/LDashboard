<?php

namespace App\Jobs;

use App\Models\Direct;
use App\Models\UpdateDirect as ModelsUpdateDirect;
use App\Services\APIHook\Yandex;
use App\Services\Parser\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDirect implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $params
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->params as $value) {
            switch ($value) {
                case ModelsUpdateDirect::HYLOK:
                    $this->updateDirect($value, env('CLIENT_LOGIN_HYLOK'), env('AUTH_TOKEN_DIRECT_HYLOK'), 'hylok');
                    break;

                case ModelsUpdateDirect::WIKA:
                    $this->updateDirect($value, env('CLIENT_LOGIN_WIKA'), env('AUTH_TOKEN_DIRECT_WIKA'), 'wika');
                    break;

                case ModelsUpdateDirect::SWAGELO_HY_LOK:
                    $this->updateDirect($value, env('CLIENT_LOGIN_SWAGELO_HY_LOK'), env('AUTH_TOKEN_DIRECT_SWAGELO_HY_LOK'), 'swagelo_hy_lok');
                    break;
                
                default:
                    dd(1);
                    break;
            }
        }
    }

    private function updateDirect($value, $clientLogin, $authToken, $title)
    {
        try {
            $dateLastUpdate = ModelsUpdateDirect::where('direct_id', $value)
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
        ModelsUpdateDirect::where('direct_id', $value)->update([
            'date_update' => date('Y-m-d', strtotime($date)),
            'date_check_update' => date('Y-m-d'),
            'status_update' => ModelsUpdateDirect::UPDATED
        ]);
    }
}
