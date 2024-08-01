<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    use HasFactory;

    protected $table = 'direct';

    protected $fillable = [  
        'direct_id',
        'CampaignId',
        'LocationOfPresenceId',
        'LocationOfPresenceName',
        'CampaignName',
        'Device',
        'AdGroupId',
        'ConversionRate',
        'Ctr',
        'AdGroupName',
        'Clicks',
        'AvgCpc',
        'Cost',
        'Date',  
        ];

    public $timestamps = false;

    public static function checkUpdate(int $direct_id): bool
    {

        $updateData = UpdateDirect::where('direct_id', $direct_id)->get(['status_update'])->toArray()[0];

        $statusUpdate = $updateData['status_update'];

        if($statusUpdate == UpdateDirect::UPDATED) {
            return false;
        }

        return true;
    }

}
