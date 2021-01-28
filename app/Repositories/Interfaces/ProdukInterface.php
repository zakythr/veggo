<?php

namespace App\Repositories\Interfaces;

interface ProdukInterface
{
    public function create(array $data);
    public function all();
    public function showEtalase();
    public function showByJenis($jenis);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findById($id);
    public function findByKode($kode);
    public function findByIsPaket($flag);
    public function findByIdKategori($id_kategori);
    public function findByIdKategoriOrNama($id_kategori,$nama);
    public function findByNamaBarang($nama); 
    
    
    public function addStok($id,$value);
    public function removeStok($id,$value);
 
}

?>