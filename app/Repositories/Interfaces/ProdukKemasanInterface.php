<?php

namespace App\Repositories\Interfaces;

interface ProdukKemasanInterface
{
    public function create($id, $bobot_kemasan);
    public function all();
    public function delete($id);
    public function findByIdBarang($id_barang);
}

?>