<?php

namespace App\Repositories;
use App\Models\Detail_transaksi as DetailTransaksi;
use App\Repositories\Interfaces\DetailTransaksiInterface;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class DetailTransaksiRepository implements DetailTransaksiInterface
{
    protected $model;

    public function __construct(DetailTransaksi $model)
    {
        $this->model = $model;
    }

    public function inputDetailTransaksi($data)
    {
        $dataInput = [
            'id'          => Uuid::generate()->string,
            'id_barang'   => $data['id_barang'],
            'harga'       => $data['harga'],
            'harga_diskon'=> $data['harga_diskon'],
            'volume'      => $data['volume'],
            'id_transaksi'=> $data['id_transaksi'],
            'bobot_kemasan'=> $data['bobot_kemasan']
        ];

        return $this->model->create($dataInput);
    }

    public function findByTransaksiId($transaksiId)
    {
        return $this->model->where('id_transaksi',$transaksiId)->get();
    }

    public function excludeDetailPreOrder($id)
    {
            $data = $this->model->find($id);
            $data->is_exclude_rekap = 1;
            $data->save();
    }

    public function cancelDetailPreOrder($id)
    {
            $data = $this->model->find($id);
            $data->is_canceled_by_veggo = 1;
            $data->save();
    }

    public function detailOrderKePetani($data){
        
        $harga_beli_terkecil = ($data['barang']->harga_beli / $data['barang']->bobot);
        $harga = $harga_beli_terkecil * $data['akumulasi_barang']->volume * $data['akumulasi_barang']->bobot;
        
        $dataInput = [
            'id'          => Uuid::generate()->string,
            'id_barang'   => $data['barang']->id,
            'harga'       => $harga,
            'status'       => 1,
            'volume'      => $data['akumulasi_barang']->volume,
            'id_transaksi'=> $data['id_transaksi'],
            'bobot_kemasan'=> $data['akumulasi_barang']->bobot,
        ];
        return $this->model->create($dataInput);
    }    
    
    public function updatePengirimanPetani($id,$value,$value2, $value3, $keterangan,$flag){
        $detail = $this->model->find($id);
        $detail->volume_kirim_petani = $value;
        $detail->bobot_kirim_petani = $value2;
        $detail->status = $flag;
        $detail->volume_selisih = ($value-$detail->volume);
        $detail->bobot_selisih = ($value2-$detail->bobot_kemasan);
        $detail->selisih_kirim = $value3;
        $detail->keterangan = $keterangan;
        return $detail->save();
    }

    public function updatePenerimaanOrderKePetani($id,$value, $value2, $value3,$flag){
        $detail = $this->model->find($id);
        $detail->volume_terima = $value;
        $detail->bobot_terima=$value2;
        $detail->selisih_terima=$value3;
        $detail->status = $flag;
        return $detail->save();
    }

    public function updateFinalisasiPengiriman($id,$volume, $bobot,$harga, $harga_diskon){
        $detail = $this->model->find($id);
        $detail->volume_kirim_kurir = $volume;
        $detail->bobot_kirim_kurir = $bobot;
        $detail->harga_akhir = $harga;
        $detail->harga_akhir_diskon = $harga_diskon;
        $detail->status = 4;
        return $detail->save();        
    }

    public function update($id,$data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function updateByIdTransaksi($id,$data)
    {
        return $this->model->where('id_transaksi',$id)->update($data);
    } 

    public function addFinalisasiItem($id_transaksi,$id_barang,$jumlah_kirim,$harga){
        $dataInput = [
            'id'            => Uuid::generate()->string,
            'id_barang'     => $id_barang,
            'harga'         => $harga,
            'harga_akhir'   => $harga,
            'status'        => 1,
            'volume'        => $jumlah_kirim,
            'id_transaksi'  => $id_transaksi,
            'bobot_kemasan' => 1,
            'volume_kirim_kurir'=> $jumlah_kirim,
        ];        

        $this->model->create($dataInput);
    }

    public function removeFinalisasiItem($id_detail_transaksi){
        $detailTransaksi = $this->model->find($id_detail_transaksi);
        $detailTransaksi->status = 7;
        return $detailTransaksi->save();
    }

    public function findByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
    
}
?>