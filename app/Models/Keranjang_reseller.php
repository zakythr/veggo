<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang_reseller extends Model
{
    //

    public $fillable = [
        'id',
        'id_parent_keranjang',
        'id_barang',
        'volume',
        'harga',
        'bobot_kemasan',
        'harga_diskon'
    ];
    
    public $incrementing = false;
    
    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    }    
}