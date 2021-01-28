<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangs_kemasan extends Model
{
    //

    protected $fillable = [
        'id',
        'id_barang',
        'bobot_kemasan',
    ];

    public $incrementing = false;
}
