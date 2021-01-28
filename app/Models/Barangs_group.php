<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangs_group extends Model
{
    //

    protected $fillable = [
        'id',
        'id_barang',
        'id_kategori'
    ];


    public $incrementing = false;
}
