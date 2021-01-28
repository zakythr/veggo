<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bobot_Kemasan extends Model
{
    protected $table = 'bobot_kemasans';

    protected $fillable = [
        'bobot_kemasan'
    ];

    public $incrementing = false;

}
