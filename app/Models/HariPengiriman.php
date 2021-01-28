<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariPengiriman extends Model
{
    protected $table = 'hari_pengiriman';

    protected $fillable = [
        'nama_hari', 'tersedia'
    ];
}
