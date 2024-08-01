<?php

namespace App\Http\Controllers\Compaigns;

use App\Models\DirectSwagelo;
use App\Models\Hy_LokInvoice;
use App\Models\Hy_LokVisitor;
use Illuminate\Http\Request;

class CompaignsHy_LokController extends CompaignsController
{
    protected $title = 'hy-lok';

    public function __construct()
    {
        parent::__construct(
            new DirectSwagelo(),
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_HY_LOK'),
            new Hy_LokVisitor(),
            new Hy_LokInvoice()
        );
    }
}
