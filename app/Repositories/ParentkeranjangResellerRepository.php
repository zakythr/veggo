<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ParentKeranjangResellerInterface;
use App\User;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Models\Parent_keranjang_reseller;
use Carbon\Carbon;

class ParentKeranjangResellerRepository implements ParentKeranjangResellerInterface
{
    protected $model;

    public function __construct(Parent_keranjang_reseller $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $inputData = [
            'id'        => Uuid::generate()->string,
            'id_user'   => Auth::user()->id,
            'name' => $data['name'],
            'alamat' => $data['alamat'],
            'nohp' => $data['nohp'],
            'tanggal_pre_order'=>$data['tanggal_pre_order'],
        ];
        return $this->model->create($inputData);
    }
    public function getIDKeranjang($name, $tanggal)
    {
        return $this->model->where('id_user', Auth::user()->id)
                            ->where('tanggal_pre_order', $tanggal)
                            ->where('name', $name)->first()->id;
    }
    public function getIdFromName($name, $tanggal){
        return $this->model->where('id_user', Auth::user()->id)
                            ->where('name', $name)
                            ->where('tanggal_pre_order', $tanggal)
                            ->where('status', 0)
                            ->first();
    }
    public function settingFlag($num, $id, $date){
        $data=[
            'status' => $num,
            'id_transaksi'=> $id
        ];
        $this->model->where('id_user', Auth::user()->id)
                    ->where('status', 0)
                    ->where('tanggal_pre_order', $date)->update($data);

    }

    public function getAllIDKeranjang()
    {
        return $this->model->where('id_user', Auth::user()->id)->get();
    }

    public function updateKeranjang($data)
    {
        #tanggal pre order
        $date = Carbon::parse($data['tanggal_pre_order']);

        $inputData = [
            'tanggal_pre_order' => $date
        ];

        return $this->model->where('id_user',Auth::user()->id)->update($data);
    }
    public function getKeteranganKeranjang($date)
    {
        return $this->model->where('id_user', Auth::user()->id)->where('tanggal_pre_order', $date)->first();
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
        return $this->model->where('id', $id)->delete();
    }

    public function getAllUserByUserAndStatus($id, $date)
    {
        return $this->model->where('id_user', $id)
                    ->where('tanggal_pre_order', $date)
                    ->where('status', 0)->get();
    }
    public function getFromIdTransaksi($id_transaksi){
        return $this->model->where('id_transaksi', $id_transaksi)
                    ->get();
    }
    public function getUserById($id)
    {
        return $this->model->where('id', $id)->first();
    }

}
?>