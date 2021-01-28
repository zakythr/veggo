<?php

namespace App\Repositories;
use App\Repositories\Interfaces\EtalaseInterface;
use App\Models\Barang;

class EtalaseRepository implements EtalaseInterface
{
    protected $model;

    public function __construct(Barang $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->where('show_etalase', 1)->get();
    }

    public function findByKategori($id_kategori)
    {
        return $this->model->where('id_kategori',$id_kategori)->get();
    }

    public function updateShowEtalase($id,$flag)
    {
        $data = [
            'show_etalase' => $flag
        ];

        return $this->model->find($id)->update($data);        
    }
    
}
?>