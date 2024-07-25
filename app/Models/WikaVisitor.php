<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikaVisitor extends Model
{
    use HasFactory;

    // protected $table = 'fluidline_wika_visitors_info';

    protected $connection = 'wika';

    protected $table = 'visitors_info';
}
