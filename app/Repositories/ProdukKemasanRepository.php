<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProdukKemasanInterface;
use App\Models\Barangs_kemasan;
use Uuid;

class ProdukKemasanRepository implements ProdukKemasanInterface
{
    protected $model;

    public function __construct(Barangs_kemasan $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function delete($id){
        return $this->model->where('id_barang',$id)->delete();
    }

    public function create($id, $bobot_kemasan)
    {
        
        if($this->model->find($id) != null){
            $this->delete($id);
        }

        foreach($bobot_kemasan as $bobot){

            $data = [
                'id' => Uuid::generate(4),
                'id_barang' => $id,
                'bobot_kemasan' => $bobot
            ];

            $this->model->create($data);
        }
    }    

    public function findByIdBarang($id_barang)
    {
        return $this->model->where('id_barang',$id_barang)->get();
    }

    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
}
?>