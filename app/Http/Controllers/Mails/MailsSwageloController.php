<?php

namespace App\Http\Controllers\Mails;

use App\Models\DirectSwagelo;
use App\Models\SwageloInvoice;
use App\Models\SwageloVisitor;
use Illuminate\Http\Request;

class MailsSwageloController extends MailsController
{
    protected string $title = 'swagelo';

    public function __construct(
        )
        {
            parent::__construct(
                new SwageloInvoice(),
                new SwageloVisitor(),
                env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
                env('COUNTER_ID_METRIC_SWAGELO'),
                new DirectSwagelo()
            );
        }
}
