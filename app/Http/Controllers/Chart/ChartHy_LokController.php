<?php

namespace App\Http\Controllers\Chart;

use App\Models\DirectSwagelo;
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
            new DirectSwagelo()
        );
    }
}
