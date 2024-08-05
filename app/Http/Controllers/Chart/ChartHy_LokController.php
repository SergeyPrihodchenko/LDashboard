<?php

namespace App\Http\Controllers\Chart;

use App\Models\Direct;
use App\Models\Hy_LokInvoice;
use App\Models\Hy_LokPhone;
use App\Models\SateliPhone;

class ChartHy_LokController extends ChartController
{
    protected $title = 'hy-lok';

    public function __construct()
    {
        parent::__construct(
            new Hy_LokInvoice(),
            new Hy_LokPhone(),
            new SateliPhone(),
            new Direct(),
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_HY_LOK')
        );
    }
}
