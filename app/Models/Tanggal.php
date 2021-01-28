<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanggal extends Model
{
    //

    public $fillable = [
        'id',
        'tanggal',
        'flag',
    ];
    
    public $incrementing = false;
}
