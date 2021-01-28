<?php

namespace App\Repositories\Interfaces;

interface ProdukGroupInterface
{
    public function create($id,$groups);
    public function all();
    public function delete($id);
    public function findByIdBarang($id_barang);
}

?>