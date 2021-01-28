<?php

namespace App\Repositories\Interfaces;

interface IsiPaketInterface
{
    public function create($id,$data);
    public function all();
    public function update($id,$data);
    public function delete($id);
    public function findByIdBarang($id_barang);
    public function read($id_barang_parent);
}

?>