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
                    'CampaignName' => $row[1],
                    'AdGroupId' => $row[2],
                    'Query' => $row[3],
                    'Impressions' => $row[4],
                    'CampaignType' => $row[5],
                    'ConversionRate' => $row[6],
                    'Ctr' => $row[7],
                    'AdGroupName' => $row[8],
                    'AvgPageviews' => $row[9],
                    'Clicks' => $row[10],
                    'BounceRate' => $row[11],
                    'Criteria' => $row[12],
                    'AvgCpc' => $row[13],
                    'Cost' => $row[14],
                    'Date' => $row[15]
                ]);
            }
        } catch (\PDOException $e) {

            Log::error($e->getMessage() . ' : ' . date('Y-m-d') . "\n");

        }
    }
}
