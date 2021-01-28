<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_keranjang extends Model
{
    protected $table = 'detail_keranjangs';

    protected $fillable = [
        'id_keranjang', 'id_barang', 'volume', 'harga', 'id', 'bobot_kemasan', 'harga_diskon'
    ];

    public $incrementing = false;
}
