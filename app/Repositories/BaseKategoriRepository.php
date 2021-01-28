<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BaseKategoriInterface;
use App\Models\Base_Kategori;

class BaseKategoriRepository implements BaseKategoriInterface
{
    protected $model;

    public function __construct(Base_Kategori $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function update(int $id,array $data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByNamaKategori($nama)
    {
        return $this->model->where('kategori', 'like', '%'.$nama.'%')->first();
    }
}
?>