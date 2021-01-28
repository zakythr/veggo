<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klaim extends Model
{
    //

    public $fillable = [
        'id',
        'kode_klaim',
        'id_transaksi',
        'klaim_from',
        'klaim_to',
        'tanggal_kirim',
        'status',
    ];
    
    public $incrementing = false;

    public function dari(){
        return $this->hasOne('App\User','id','klaim_from');
    }

    public function untuk(){
        return $this->hasOne('App\User','id','klaim_to');
    }

    public function transaksi(){
        return $this->hasOne('App\Models\Transaksi','id','id_transaksi');
    }
}
