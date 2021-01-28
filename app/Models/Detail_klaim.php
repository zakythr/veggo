<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_klaim extends Model
{
    //  
    public $fillable = [
        'id',
        'id_klaim',
        'id_barang',
        'volume_klaim',
        'keterangan',
        'foto_bukti',
        'id_detail_transaksi'
    ];
    public $incrementing = false;

    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    } 
    public function klaim(){
        return $this->hasOne('App\Models\Klaim','id','id_klaim');
    }
    public function detail_transaksi(){
        return $this->hasOne('App\Models\Detail_transaksi','id','id_detail_transaksi');
    }
}
