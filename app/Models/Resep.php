<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'reseps';

    protected $fillable = [
        'judul', 'foto', 'artikel', 'is_show'
    ];

    public $incrementing = false;
}