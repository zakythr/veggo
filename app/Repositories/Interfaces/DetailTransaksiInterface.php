<?php

namespace App\Repositories\Interfaces;

interface DetailTransaksiInterface
{
    public function inputDetailTransaksi($data);
    public function update($id,$data);
    public function findByTransaksiId($transaksiId);

    public function detailOrderKePetani($data);

    public function updatePengirimanPetani($id,$value, $value2, $value3, $keterangan,$flag);
    public function updatePenerimaanOrderKePetani($id,$value,$value2, $value3, $flag);
    public function updateFinalisasiPengiriman($id,$volume, $bobot,$harga, $harga_diskon);
    public function addFinalisasiItem($id_transaksi,$id_barang,$jumlah_kirim,$harga);

}

?>