<?php

namespace App\Repositories\Interfaces;

interface FotoProdukInterface
{
    public function create($id,$data);
    public function all();
    public function delete($id_barang, $path);
    public function findByIdBarang($id_barang);

}

?>