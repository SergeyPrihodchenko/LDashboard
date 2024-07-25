<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwageloVisitor extends Model
{
    use HasFactory;

    protected $connection = 'swagelo';

    protected $table = 'visitors_info';

}
