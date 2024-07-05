<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwageloInvoice extends Model
{
    use HasFactory;

    protected $table = 'fluidacy_swagelo_InvoiceList';
    protected $primaryKey = 'client_mail';
    
}
