<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HylokInvoice extends Model
{
    use HasFactory;

    // protected $table = 'fluidline_hylok_InvoiceList';

    protected $connection = 'hylok';

    protected $table = 'InvoiceList';

    public const CLIENT_LOGIN = 'kk-hylok@yandex.ru';
}
