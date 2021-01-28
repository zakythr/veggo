<?php

namespace App\Repositories\Interfaces;

interface DetailKeranjangInterface
{
    public function getDetailKeranjang($id);
    public function tambahDetailKeranjang($data);
    public function updateDetailKeranjang($id, $data);
    public function hapusDetailKeranjang($id);
    public function cekDetailKeranjang($id);
    public function findByIdKeranjangIdBarang($id_keranjang, $id_barang);
}

?>