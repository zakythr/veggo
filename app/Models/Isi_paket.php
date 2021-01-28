<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Isi_paket extends Model
{
    protected $table = 'isi_pakets';

    protected $fillable = [
        'id',
        'id_barang_parent',
        'id_barang',
        'volume',
        'harga',
    ];
    
    public $incrementing = false;

    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    }
}
