<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parent_keranjang_reseller extends Model
{
    //

    public $fillable = [
        'id',
        'id_user',
        'tanggal_pre_order',
        'name',
        'status',
        'id_transaksi',
        'alamat',
        'nohp',
    ];
    
    public $incrementing = false;
}