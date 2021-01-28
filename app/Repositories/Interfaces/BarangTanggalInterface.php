<?php

namespace App\Repositories\Interfaces;

interface BarangTanggalInterface
{
    public function create($tanggal, $barang);
    public function update(int $id, array $data);
    public function delete(int $id);
}

?>