<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'id',
        'kategori',
        'sub_kategori',
    ];

    public $incrementing = false;

    public function baseKategori(){
        return $this->hasOne('App\Models\Base_Kategori','id','kategori');
    }
}
