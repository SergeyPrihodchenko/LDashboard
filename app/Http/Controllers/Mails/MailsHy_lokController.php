<?php

namespace App\Http\Controllers\Mails;

use App\Models\DirectSwagelo;
use App\Models\Hy_LokInvoice;
use App\Models\HylokVisitor;
use Illuminate\Http\Request;

class MailsHy_lokController extends MailsController
{
    protected string $title = 'hy-lok';

    public function __construct()
    {
        parent::__construct(
            new Hy_LokInvoice(),
            new HylokVisitor(), // mokeeeeee
            env('AUTH_TOKEN_METRIC_SWAGELO_HY_LOK'),
            env('COUNTER_ID_METRIC_HY_LOK'),
            new DirectSwagelo()
        );
    }
}
