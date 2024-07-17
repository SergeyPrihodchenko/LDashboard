<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direct extends Model
{
    use HasFactory;

    protected $table = 'direct';

    protected $fillable = [  
        "CampaignId",
        "LocationOfPresenceId",
        "LocationOfPresenceName",
        "CampaignName",
        "Device",
        "AdGroupId",
        "ConversionRate",
        "Ctr",
        "AdGroupName",
        "Clicks",
        "AvgCpc",
        "Cost",
        "Date"
    ];

    public $timestamps = false;

    static function checkUpdate(): bool|string
    {
        $data = self::select('date')->orderByDesc('id')->limit(1)->get()->toArray();

        $lastDate = '2024-01-01';
        
         if(isset($data[0]['date'])) {

         $lastDate = $data[0]['date'];

         }

        $currentDate = date('Y-m-d');

        $dateDiff = date_diff(new \DateTime($lastDate), new \DateTime($currentDate));

        if ($dateDiff->days >= 1) {
            return $lastDate;
        } else {
            return false;
        }

    }
}
