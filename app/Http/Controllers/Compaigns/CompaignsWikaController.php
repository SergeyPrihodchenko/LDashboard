<?php

namespace App\Http\Controllers\Compaigns;

use App\Models\Direct;
use App\Models\WikaInvoice;
use App\Models\WikaVisitor;
use Illuminate\Http\Request;

class CompaignsWikaController extends CompaignsController
{
    protected $title = 'wika';

    public function __construct()
    {
        parent::__construct(
            new Direct(),
            env('AUTH_TOKEN_METRIC_WIKA'),
            env('COUNTER_ID_METRIC_WIKA'),
            new WikaVisitor(),
            new WikaInvoice()
        );
    }
}
