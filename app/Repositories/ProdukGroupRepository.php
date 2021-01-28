<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProdukGroupInterface;
use App\Models\Barangs_group;
use Uuid;

class ProdukGroupRepository implements ProdukGroupInterface
{
    protected $model;

    public function __construct(Barangs_group $model)
    {
        $this->model = $model;
    }

    
    public function all()
    {
        return $this->model->all();
    }
    
    public function delete($id)
    {
        return $this->model->where('id_barang',$id)->delete();
    }

    public function create($id, $groups)
    {
        if($this->model->find($id) != null){
            $this->delete($id);
        }

        foreach($groups as $group){

            $data = [
                'id' => Uuid::generate(4),
                'id_barang' => $id,
                'id_kategori' => $group
            ];

            $this->model->create($data);
        }
    }

    public function findByIdBarang($id_barang)
    {
        return $this->model->where('id_barang',$id_barang)->get();
    }
    public function findByIdKategori($id)
    {
        return $this->model->where('id_kategori',$id)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
}
?>