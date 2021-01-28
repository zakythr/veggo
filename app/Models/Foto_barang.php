<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto_barang extends Model
{
    //
    protected $fillable = [
        'id',
        'id_barang',
        'path',
    ];

    public $incrementing = false;

    public function barang(){
        return $this->hasOne('App\Models\Barang','id','id_barang');
    }
}
