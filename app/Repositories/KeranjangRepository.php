<?php

namespace App\Repositories;
use App\Repositories\Interfaces\KeranjangInterface;
use App\Models\Keranjang;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class KeranjangRepository implements KeranjangInterface
{
    protected $model;

    public function __construct(Keranjang $model)
    {
        $this->model = $model;
    }

    public function getKeranjang($id)
    {
        return $this->model->where('id_keranjang', $id)->get();
    }

    public function getIDKeranjang($tanggal)
    {
        // dd($tanggal);
        return $this->model->where('id_user', Auth::user()->id)->where('tanggal_pre_order', $tanggal)->first()->id;
    }

    public function getKeteranganKeranjang($date)
    {
        return $this->model->where('id_user', Auth::user()->id)->where('tanggal_pre_order', $date)->first();
    }

    public function tambahKeranjang($tanggal)
    {
        $inputData = [
            'id'                => Uuid::generate(4)->string,
            'id_user'           => Auth::user()->id,
            'tanggal_pre_order' => $tanggal
        ];

        return $this->model->create($inputData);
    }

    public function updateKeranjang($id, $data)
    {
        #tanggal pre order
        $date = Carbon::parse($data['tanggal_pre_order']);

        $inputData = [
            'tanggal_pre_order' => $date
        ];

        return $this->model->where('id',$id)->update($data);
    }

    public function hapusKeranjang($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function hapusKeranjangByID($id)
    {
        return $this->model->where('id_user', Auth::user()->id)->delete();
    }

    public function findKeranjang($tanggal)
    {
        return $this->model->where('id_user', Auth::user()->id)
        ->where('tanggal_pre_order', $tanggal)->first();
    }
}
?>