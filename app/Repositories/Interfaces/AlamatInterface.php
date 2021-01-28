<?php

namespace App\Repositories\Interfaces;

interface AlamatInterface
{
    public function getAllAlamatByUser($id);
    public function hapusAlamat($id);
    public function ubahAlamat($id, $data);
    public function tambahAlamat($data);
}

?>