<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjangs';

    protected $fillable = [
        'id', 'id_user', 'tanggal_pre_order'
    ];

    public $incrementing = false;
}
