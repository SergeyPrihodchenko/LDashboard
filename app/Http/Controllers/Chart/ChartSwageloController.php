<?php

namespace App\Http\Controllers\Chart;

use App\Models\Direct;
use App\Models\SateliPhone;
use App\Models\SwageloInvoice;
use App\Models\SwageloPhone;
use Illuminate\Http\Request;

class ChartSwageloController extends ChartController
{
    protected $title = 'swagelo';

    public function __construct()
    {
        parent::__construct(
            new SwageloInvoice(),
            new SwageloPhone(),
            new SateliPhone(),
            new Direct(),
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_HY_LOK')
        );
    }
}
