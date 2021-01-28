<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Barang extends Model
{
    protected $table = 'barangs';
    //
    protected $fillable = [
        'id',
        'id_user',
        'id_kategori',
        'nama',
        'kode',
        'jenis',
        'satuan',
        'bobot',
        'harga_beli',
        'harga_jual',
        'deskripsi',
        'diskon',
        'jenis_diskon',
        'show_etalase',
        'is_paket',
        'ketersediaan',
        'stok',
        'bobot_minimum_timbang',
        'bobot_kemasan_kemas',
        'jenis_diskon_reseller',
        'diskon_reseller',
        'harga_jual_reseller',
        'jumlah_pcs'
    ];    

    public $incrementing = false;

    public function supplier(){
        return $this->hasOne('App\User','id','id_user');
    }

    public function isiPaket(){
        return $this->hasMany('App\Models\Isi_paket','id_barang_parent','id');
    }

    public function detailTransaksi(){
        return $this->hasMany('App\Models\Detail_Transaksi','id_barang','id');
    }

    public function kategori(){
        return $this->hasOne('App\Models\Base_Kategori','id','id_kategori');
    }

    public function foto_barang(){
        return $this->hasMany('App\Models\Foto_barang','id_barang','id');
    }

    public function barangTanggal()
    {
        return $this->hasMany('App\Models\Barang_tanggal', 'id_barang', 'id');
    }

}
