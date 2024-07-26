<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwageloPhone extends Model
{
    use HasFactory;

    protected $connection = 'swagelo';
    protected $table = 'calls_data';
}
