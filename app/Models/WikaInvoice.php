<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikaInvoice extends Model
{
    use HasFactory;

    protected $connection = 'wika';

    protected $table = 'InvoiceList';

    public const CLIENT_LOGIN = 'av-wika-ads@yandex.ru';
}
