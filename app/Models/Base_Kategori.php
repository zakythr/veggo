<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base_Kategori extends Model
{
    protected $table = 'base_kategoris';

    protected $fillable = [
        'kategori'
    ];

    public $incrementing = false;
}
