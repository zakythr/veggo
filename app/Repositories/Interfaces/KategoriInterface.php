<?php

namespace App\Repositories\Interfaces;

interface KategoriInterface
{
    public function create(array $data);
    public function all();
    public function update(int $id, array $data);
    public function findById(int $id);
    public function delete(int $id);
    public function kategoriSayur();
    public function kategoriBuah();
    public function kategoriMakananSehat();
    public function kategoriMinumanSehat();
    public function kategoriBeras();
    public function kategoriBahanOlahan();
    public function kategoriBerkebun();
    public function kategoriLainLain();
}

?>