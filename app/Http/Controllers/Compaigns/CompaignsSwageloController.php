<?php

namespace App\Http\Controllers\Compaigns;

use App\Models\Direct;
use App\Models\SwageloInvoice;
use App\Models\SwageloVisitor;
use Illuminate\Http\Request;

class CompaignsSwageloController extends CompaignsController
{
    protected $title = 'swagelo';

    public function __construct()
    {
        parent::__construct(
            new Direct(),
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_SWAGELO'),
            new SwageloVisitor(),
            new SwageloInvoice()
        );
    }
}
