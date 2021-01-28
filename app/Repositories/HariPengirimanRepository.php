<?php

namespace App\Repositories;
use App\Repositories\Interfaces\HariPengirimanInterface;
use App\Models\HariPengiriman;

class HariPengirimanRepository implements HariPengirimanInterface
{
    protected $model;

    public function __construct(HariPengiriman $model)
    {
        $this->model = $model;
    }

    public function getHariTersedia()
    {
        return $this->model->where('tersedia', 1)->get();
    }

    public function cariUrutan($hari)
    {
        return $this->model->where('nama_hari', $hari)->first();
    }
}
?>