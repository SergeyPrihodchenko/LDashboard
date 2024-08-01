<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikaInvoice extends Model
{
    use HasFactory;

    protected $connection = 'wika';

    protected $table = 'InvoiceList';

    // protected $table = 'fluidline_wika_InvoiceList';
}
