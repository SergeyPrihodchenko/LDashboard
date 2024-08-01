<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HylokVisitor extends Model
{
    use HasFactory;

    protected $connection = 'hylok';

    protected $table = 'visitors_info';
}
