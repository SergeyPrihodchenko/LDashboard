<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\DirectHylok;
use App\Models\HylokInvoice;
use App\Models\HylokVisitor;
use Illuminate\Http\Request;

class CompaignsHylokController extends CompaignsController
{
    protected $title = 'hylok';

    public function __construct()
    {
        parent::__construct(
            new DirectHylok(),
            env('AUTH_TOKEN_METRIC_HYLOK'),
            env('COUNTER_ID_METRIC_HYLOK'),
            new HylokVisitor,
            new HylokInvoice(),
        );
    }
}
