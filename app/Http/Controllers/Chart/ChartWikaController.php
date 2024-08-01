<?php

namespace App\Http\Controllers\Chart;

use App\Models\DirectWika;
use App\Models\SateliPhone;
use App\Models\WikaInvoice;
use App\Models\WikaPhone;

class ChartWikaController extends ChartController
{
    protected $title = 'wika';

    public function __construct()
    {
        parent::__construct(
            new WikaInvoice(),
            new WikaPhone(),
            new SateliPhone(),
            new DirectWika()
        );
    }
}
