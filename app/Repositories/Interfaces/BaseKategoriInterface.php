<?php

namespace App\Repositories\Interfaces;

interface BaseKategoriInterface
{
    public function create(array $data);
    public function all();
    public function update(int $id, array $data);
    public function delete(int $id);  
    public function findById(int $id);  
}

?>