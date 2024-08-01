<?php

namespace App\Http\Controllers\Mails;

use App\Models\DirectHylok;
use App\Models\HylokInvoice;
use App\Models\HylokVisitor;
use Illuminate\Http\Request;

class MailsHylokController extends MailsController
{
    protected string $title = 'hylok';

    public function __construct()
    {
        parent::__construct(
            new HylokInvoice(),
            new HylokVisitor(),
            env('AUTH_TOKEN_METRIC_HYLOK'),
            env('COUNTER_ID_METRIC_HYLOK'),
            new DirectHylok(),
        );
    }
}
