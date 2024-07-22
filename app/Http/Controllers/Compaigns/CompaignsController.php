<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\Direct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompaignsController extends Controller
{
    public function index()
    {
        $direct = Direct::all('CampaignId', 'CampaignName', 'AdGroupId', 'AdGroupName', 'Clicks', 'Cost', 'Date');

        $data = [];
        foreach ($direct as $key => $value) {
            if(!array_key_exists($value['CampaignId'], $data)) {
                $data[$value['CampaignId']] = [
                    'campaignName' => $value['CampaignName'],
                    'AdGroupId' => [$value['AdGroupId'] => ['name' => $value['CampaignName'], 'cost' => (float)$value['cost']]],
                    'cost' => (float)$value['Cost']
                ];
            } else {
                if(!array_key_exists($value['AdGroupId'], $data[$value['CampaignId']]['AdGroupId']))
                {
                    $data[$value['CampaignId']]['AdGroupId'][$value['AdGroupId']] = ['name' => $value['AdGroupName'], 'cost' => (float)$value['Cost']];
                } else {
                    $data[$value['CampaignId']]['AdGroupId'][$value['AdGroupId']]['cost'] += (float)$value['Cost'];
                }
                $data[$value['CampaignId']]['cost'] += (float)$value['Cost'];
            }
        }

        foreach ($data as $key => $value) {
            $data[$key]['cost'] = number_format($value['cost'], 2, '.', '');
            foreach ($value['AdGroupId'] as $i => $el) {
                $data[$key]['AdGroupId'][$i]['cost'] = number_format($el['cost'], 2, '.', '');
            }
        }

        return Inertia::render('Compaigns', ['data' => $data]);
    }

}
