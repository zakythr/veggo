<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'id',
        'id_barang',
        'id_transaksi',
        'tanggal',
        'status',
        'keterangan',
        'jumlah',
    ];

    public $incrementing = false;

    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    }    

    public function transaksi(){
        return $this->hasOne('App\Models\Transaksi','id','id_transaksi');
    }    
}
