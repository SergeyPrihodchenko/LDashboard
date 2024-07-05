<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SateliPhone extends Model
{
    use HasFactory;

    protected $table = 'fluidline_sateli_InvoiceCallList';
    protected $primaryKey = 'client_phone';
}
