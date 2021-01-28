<?php

namespace App\Repositories\Interfaces;

interface KeranjangInterface
{
    public function getKeranjang($id);
    public function getIDKeranjang($tanggal);
    public function getKeteranganKeranjang($date);
    public function tambahKeranjang($tanggal);
    public function updateKeranjang($id, $data);
    public function hapusKeranjang($id);
    public function findKeranjang($tanggal);
}

?>