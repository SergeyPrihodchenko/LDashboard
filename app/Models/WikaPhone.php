<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikaPhone extends Model
{
    use HasFactory;

    protected $connection = 'wika';
    protected $table = 'calls_data';
}
