<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Isi_resep extends Model
{
    protected $table = 'isi_reseps';

    protected $fillable = [
        'id_parent_resep', 'id_barang', 'volume'
    ];
}
