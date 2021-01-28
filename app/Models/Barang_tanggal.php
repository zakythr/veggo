<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Barang_tanggal extends Model
{
    //
    protected $fillable = [
        'id',
        'id_barang',
        'tanggal',
    ];    

    public $incrementing = false;

    public function barang()
    {
        return $this->belongsTo('App\Models\Barang', 'id_barang', 'id');
    }

}
