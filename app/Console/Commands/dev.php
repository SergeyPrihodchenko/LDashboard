<?php

namespace App\Console\Commands;

use App\Http\Controllers\Compaigns\CompaignsWikaController;
use App\Models\Direct;
use App\Models\DirectHylok;
use App\Models\DirectSwagelo;
use App\Models\DirectWika;
use App\Models\UpdateDirect;
use Illuminate\Console\Command;
use ReflectionClass;

class dev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $dataS = DirectHylok::all([
        //     'CampaignId',
        //     'AdGroupId',
        //     'LocationOfPresenceId',
        //     'Clicks',
        //     'Date',
        //     'Device',
        //     'LocationOfPresenceName',
        //     'CampaignName',
        //     'ConversionRate',
        //     'Ctr',
        //     'AdGroupName',
        //     'AvgCpc',
        //     'Cost'
        // ])->toArray();

        // foreach ($dataS as $key => $value) {
        //     Direct::create([
        //         'direct_id' => 3, 
        //         'CampaignId' => $value['CampaignId'],
        //         'AdGroupId' => $value['AdGroupId'],
        //         'LocationOfPresenceId' => $value['LocationOfPresenceId'],
        //         'Clicks' => $value['Clicks'],
        //         'Date' => $value['Date'],
        //         'Device' => $value['Device'],
        //         'LocationOfPresenceName' => $value['LocationOfPresenceName'],
        //         'CampaignName' => $value['CampaignName'],
        //         'ConversionRate' => $value['ConversionRate'],
        //         'Ctr' => $value['Ctr'],
        //         'AdGroupName' => $value['AdGroupName'],
        //         'AvgCpc' => $value['AvgCpc'],
        //         'Cost' => $value['Cost'],        
        //     ]);
        //     unset($dataS[$key]);
        // }

        // UpdateDirect::where('direct_id', UpdateDirect::HYLOK)->update(['status_update' => 1]);

    }
}
