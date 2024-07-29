<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    use HasFactory;

    protected $table = 'direct';

    protected $fillable = [  
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

}
