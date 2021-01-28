<?php

namespace App\Repositories\Interfaces;

interface EtalaseInterface
{
    public function all();
    public function updateShowEtalase($id,$flag);
    public function findByKategori($id_kategori);

}

?>