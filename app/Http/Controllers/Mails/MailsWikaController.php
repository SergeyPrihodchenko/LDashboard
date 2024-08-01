<?php

namespace App\Http\Controllers\Mails;

use App\Models\DirectWika;
use App\Models\WikaInvoice;
use App\Models\WikaVisitor;
use Illuminate\Http\Request;

class MailsWikaController extends MailsController
{
    protected string $title = 'wika';

    public function __construct(
    )
    {
        parent::__construct(
            new WikaInvoice(),
            new WikaVisitor(),
            env('AUTH_TOKEN_METRIC_WIKA'),
            env('COUNTER_ID_METRIC_WIKA'),
            new DirectWika()
        );
    }

}
