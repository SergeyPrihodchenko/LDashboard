<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateDirect extends Model
{
    use HasFactory;

    public const HYLOK = 1;
    public const WIKA = 2;
    public const SWAGELO_HY_LOK = 3;

    public const UPDATED = 1;
    public const DONT_UPDATED = 0;

    protected $table = 'direct_updatable';

    public $timestamps = false;

}
