<?php

namespace App\Http\Controllers\Chart;

use App\Models\DirectWika;
use App\Models\HylokInvoice;
use App\Models\HylokPhone;
use App\Models\SateliPhone;
use Illuminate\Http\Request;

class ChartHylokController extends ChartController
{
    protected $title = 'hylok';

    public function __construct()
    {
        parent::__construct(
            new HylokInvoice(),
            new HylokPhone(),
            new SateliPhone(),
            new DirectWika()
        );
    }
}
