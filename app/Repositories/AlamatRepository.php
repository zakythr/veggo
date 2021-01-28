<?php

namespace App\Repositories;

use App\Models\Alamat;
use GuzzleHttp\Client;
use Webpatser\Uuid\Uuid;
use App\Adapters\GeocodeAdapter;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\GuzzleException;
use App\Repositories\Interfaces\AlamatInterface;

class AlamatRepository implements AlamatInterface
{
    protected $model;
    protected $geocodeAdapter;

    public function __construct(Alamat $model, GeocodeAdapter $geocodeAdapter)
    {
        $this->model          = $model;
        $this->geocodeAdapter = $geocodeAdapter;
    }
    
    public function getAllAlamatByUser($id)
    {
        return $this->model->where('id_user', $id)->get();
    }

    public function getAlamatById($id)
    {
        return $this->model->where('id', $id)->get();
    }

    public function hapusAlamat($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function ubahAlamat($id, $data)
    {
        $getLatLong = $this->geocodeAdapter->getLatLong($data);

        $inputData = [
            'id_user'   => Auth::user()->id,
            'kotkab'    => "Surabaya",
            'daerah' => $data['daerah'],
            'kodepos'   => $data['kodepos'],
            'long'      => $getLatLong['lon'],
            'lat'       => $getLatLong['lat'],
            'blok_nomor'=> $data['blok_nomor'],
            'alamat'    => $data['alamat'],
            'info_tambahan'=>$data['kotkab']
        ];

        return $this->model->where('id', $id)->update($inputData);
    }

    public function tambahAlamat($data)
    {
        $getLatLong = $this->geocodeAdapter->getLatLong($data);

        $inputData = [
            'id'        => Uuid::generate()->string,
            'id_user'   => Auth::user()->id,
            'kotkab'    => "Surabaya",
            'daerah' => $data['daerah'],
            'kodepos'   => $data['kodepos'],
            'long'      => $getLatLong['lon'],
            'lat'       => $getLatLong['lat'],
            'blok_nomor'=> $data['blok_nomor'],
            'alamat'    => $data['alamat'],
            'info_tambahan'=>$data['kotkab']
        ];

        return $this->model->create($inputData);
    }
}
?>