<?php

namespace App\Repositories\Interfaces;

interface InventarisInterface
{
    public function create($data);
    public function all();
    public function update(int $id, array $data);
    public function delete(int $id);
    public function inventarisOut($id_barang,$id_transaksi,$value,$keterangan);
    public function inventarisIn($id_barang,$id_transaksi,$value,$keterangan);
}

?>