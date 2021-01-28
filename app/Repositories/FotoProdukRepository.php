<?php

namespace App\Repositories;
use App\Repositories\Interfaces\FotoProdukInterface;
use App\Models\Foto_barang;
use Uuid;


class FotoProdukRepository implements FotoProdukInterface
{
    protected $model;

    public function __construct(Foto_barang $model)
    {
        $this->model = $model;
    }

    public function create($id, $photo)
    {
        $data = [
            'id' => Uuid::generate(4),
            'id_barang' => $id,
            'path' => $photo
        ];

        return $this->model->create($data);        
    }

    public function all()
    {
        return $this->model->all();
    } 


    public function delete($id_barang, $path)
    {
        $data = $this->model
                ->where('id_barang', $id_barang)
                ->where('path', $path)
                ->first();
                
        return $data->delete();

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