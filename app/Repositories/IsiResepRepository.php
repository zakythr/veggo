<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IsiResepInterface;
use App\Models\Isi_resep;

class IsiResepRepository implements IsiResepInterface
{
    protected $model;

    public function __construct(Isi_resep $model)
    {
        $this->model = $model;
    }

    public function getIsiResep($id)
    {
        return $this->model->where('id_parent_resep', $id)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
    public function deleteByIdParentBarang($id_barang){
        return $this->model->where('id_parent_resep',$id_barang)->delete();
    }
}

?> 