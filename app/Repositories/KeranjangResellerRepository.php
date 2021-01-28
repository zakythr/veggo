<?php

namespace App\Repositories;
use App\Repositories\Interfaces\KeranjangResellerInterface;
use App\User;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Models\Keranjang_reseller;
use DB;

class KeranjangResellerRepository implements KeranjangResellerInterface
{
    protected $model;

    public function __construct(Keranjang_reseller $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $inputData = [
            'id'        => Uuid::generate()->string,
            'id_parent_keranjang'   => $data['id_parent_keranjang'],
            'id_barang' => $data['id_barang'],
            'volume' =>$data['volume'],
            'harga' =>$data['harga'],
            'bobot_kemasan' => $data['bobot_kemasan'],
            'harga_diskon' =>$data['harga_diskon'],
        ];
        return $this->model->create($inputData);
    }

    public function getTotalById($date){
        return DB::select('call sp_get_barang_by_id_reseller(?, ?)', [Auth::user()->id, $date]);
    }
    public function getBarangFromIdBarangAndBobot($getParentKeranjang, $id_barang, $bobot_kemasan){
        return $this->model->where('id_parent_keranjang', $getParentKeranjang)
                            ->where('id_barang', $id_barang)
                            ->where('bobot_kemasan', $bobot_kemasan)
                            ->first();
    }

    public function read()
    {
        return $this->model->all();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data); 
    } 

    public function hapusUser($id)
    {
        return $this->model->where('id_parent_keranjang', $id)->delete();
    }
    public function hapusBarang($id, $idd)
    {
        return $this->model->where('id_barang', $id)
                            ->where('id_parent_keranjang', $idd)
                            ->delete();
    }

    public function getAllUserByUser($id)
    {
        return $this->model->where('id_user', $id)->get();
    }
    public function getUserById($id)
    {
        return $this->model->where('id_parent_keranjang', $id)->get()->all();
    }
    public function findByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }

}
?>