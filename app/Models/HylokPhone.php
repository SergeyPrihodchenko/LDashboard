<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HylokPhone extends Model
{
    use HasFactory;

    protected $connection = 'hylok';

    protected $table = 'calls_data';
}
