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
        'CampaignName',
        'AdGroupId',
        'AdGroupName',
        'AvgCpc',
        'Cost',
        'Date',
        'CompaingType'
    ];

    public $timestamps = false;

    static function checkUpdate(): bool
    {
        $lastDate = self::select('date')->orderByPivot('id', 'desc')->limit(1)->get()[0]['date'];
        $currentDate = date('Y-m-d');

        $dateDiff = date_diff(new \DateTime($lastDate), new \DateTime($currentDate));

        if ($dateDiff->days < 8) {
            return false;
        } else {
            return $lastDate;
        }

    }
}
