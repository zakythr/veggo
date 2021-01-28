<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BarangKemasanInterface;
use App\Models\Barangs_kemasan;

class BarangKemasanRepository implements BarangKemasanInterface
{
    protected $model;

    public function __construct(Barangs_kemasan $model)
    {
        $this->model = $model;
    }

    public function findByIdBarang($id_barang)
    {
        return $this->model->where('id_barang', $id_barang)->get();
    }
}
?>