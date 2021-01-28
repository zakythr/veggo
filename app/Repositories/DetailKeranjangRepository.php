<?php

namespace App\Repositories;
use App\Repositories\Interfaces\DetailKeranjangInterface;
use App\Models\Detail_keranjang;
use Webpatser\Uuid\Uuid;
use Auth;

class DetailKeranjangRepository implements DetailKeranjangInterface
{
    protected $model;

    public function __construct(Detail_keranjang $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getDetailKeranjang($id)
    {
        return $this->model->where('id_keranjang', $id)->get();
    }

    public function tambahDetailKeranjang($data)
    {
        return $this->model->create($data);
    }

    public function updateDetailKeranjang($id, $data)
    {
        return $this->model->where('id_barang',$id)->update($data);
    }

    public function updateDetailKeranjangKemas($id, $bobot, $data)
    {
        return $this->model
            ->where('id_barang',$id)
            ->where('bobot_kemasan', $bobot)
            ->update($data);
    }

    public function hapusDetailKeranjang($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function hapusDetailKeranjangByIdKeranjang($id)
    {
        return $this->model->where('id_keranjang', $id)->delete();
    }

    public function cekDetailKeranjang($id)
    {
        return $this->model->where('id_barang', $id)->first();
    }

    public function findByIdBarangBobotKemasan($id_keranjang, $id_barang, $bobot)
    {
        return $this->model->where('id_keranjang', $id_keranjang)->where('id_barang', $id_barang)->where('bobot_kemasan', $bobot)->first();
    }

    public function findByIdKeranjangIdBarang($id_keranjang,$id_barang)
    {
        return $this->model->where('id_keranjang', $id_keranjang)->where('id_barang', $id_barang)->first();
    }
    public function findByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }

}

?>