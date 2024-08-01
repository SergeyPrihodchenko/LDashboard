<?php

namespace App\Http\Controllers\Compaigns;

use App\Http\Controllers\Controller;
use App\Models\DirectSwagelo;
use App\Models\SwageloInvoice;
use App\Models\SwageloVisitor;
use Illuminate\Http\Request;

class CompaignsSwageloController extends CompaignsController
{
    protected $title = 'swagelo';

    public function __construct()
    {
        parent::__construct(
            new DirectSwagelo(),
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_SWAGELO'),
            new SwageloVisitor(),
            new SwageloInvoice()
        );
    }
}
