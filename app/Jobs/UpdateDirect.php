<?php

namespace App\Jobs;

use App\Models\Direct;
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
        public string $file_path
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $stream = fopen($this->file_path, 'r');

        try {
            while ($row = fgetcsv($stream, null, "\t")) {
                Direct::create([
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
        } catch (\PDOException $e) {

            Log::error($e->getMessage() . ' : ' . date('Y-m-d') . "\n");

        }
    }
}
