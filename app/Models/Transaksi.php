<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'id',
        'id_user',
        'id_alamat',
        'id_kurir',
        'id_reseller',
        'nomor_invoice',
        'total_bayar',
        'status',
        'is_info_petani',
        'is_canceled_by_veggo',
        'bukti_transfer',
        'tanggal_pre_order',
        'keterangan',
        'isAlreadyPay',
        'tipe_transaksi',
        'tanggal_pengiriman',
        'tanggal_terima',
        'is_exclude_rekap',
        'is_confirm_finish_byuser',
        'is_diterima_reseller',
        'total_bayar_akhir',
        'ongkir'
    ];
    
    public $incrementing = false;

    public function user(){
        return $this->hasOne('App\User','id','id_user');
    }

    public function alamat(){
        return $this->hasOne('App\Models\Alamat','id','id_alamat');
    }

    public function kurir(){
        return $this->hasOne('App\User','id','id_kurir');
    }
    
    public function detailTransaksi(){
        return $this->hasMany('App\Models\Detail_transaksi','id_transaksi','id');
    }
}
