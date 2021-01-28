<?php

namespace App\Repositories\Interfaces;

interface TanggalInterface
{
    public function create($tanggal, $flag);
    public function update(int $id, array $data);
    public function delete(int $id);
}

?>