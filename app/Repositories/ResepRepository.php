<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ResepInterface;
use App\Models\Resep;

class ResepRepository implements ResepInterface
{
    protected $model;

    public function __construct(Resep $model)
    {
        $this->model = $model;
    }

    public function getResep()
    {
        return $this->model->all();
    }
}

?> 