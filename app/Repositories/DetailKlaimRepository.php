<?php

namespace App\Repositories;
use App\Repositories\Interfaces\DetailKlaimInterface;
use App\Models\Detail_klaim;
use Uuid;

class DetailKlaimRepository implements DetailKlaimInterface
{
    protected $model;

    public function __construct(Detail_klaim $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $data['id'] = Uuid::generate()->string;
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

    public function getDetailKlaimById($id){
        return $this->model->where('id_klaim',$id)->get()->all();
    }
    public function getDetailKlaimByIdDetailTransaksi($id){
        return $this->model->where('id_detail_transaksi',$id)->get();
    }
    public function findByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
}
?>