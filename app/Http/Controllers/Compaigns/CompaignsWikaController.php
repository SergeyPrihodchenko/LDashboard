<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\DirectWika;
use App\Models\WikaInvoice;
use App\Models\WikaVisitor;
use Illuminate\Http\Request;

class CompaignsWikaController extends CompaignsController
{
    protected $title = 'wika';

    public function __construct()
    {
        parent::__construct(
            new DirectWika(),
            env('AUTH_TOKEN_METRIC_WIKA'),
            env('COUNTER_ID_METRIC_WIKA'),
            new WikaVisitor(),
            new WikaInvoice()
        );
    }
}
