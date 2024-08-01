<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\DirectSwagelo;
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
            new DirectSwagelo()
        );
    }
}
