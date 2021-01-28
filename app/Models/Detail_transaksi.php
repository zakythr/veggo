<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_transaksi extends Model
{
    protected $table = 'detail_transaksis';

    protected $fillable = [
        'bobot_kemasan',
        'id_transaksi', 
        'id_barang',
        'volume',
        'harga',
        'harga_diskon',
        'volume_terima',
        'volume_selisih',
        'volume_kirim_petani',
        'volume_kirim_kurir',
        'bobot_kirim_kurir',
        'harga_akhir',
        'keterangan',
        'id',
        'id_keranjang',
        'id_barang',
        'volume', 
        'harga_akhir',
        'harga_akhir_diskon',
        'is_info_petani',
        'is_canceled_by_veggo',        
        'is_include_rekap',        
    ];

    public $incrementing = false;

    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    }    

    public function transaksi(){
        return $this->hasOne('App\Models\Transaksi','id','id_transaksi');
    }
}
