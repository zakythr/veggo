<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamats';

    protected $fillable = [
        'id', 'id_user', 'kotkab', 'daerah', 'kodepos', 'long', 'lat',
        'blok_nomor', 'alamat', 'info_tambahan'
    ];
    
    public $incrementing = false;
}
