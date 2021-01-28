<?php

namespace App\Repositories\Interfaces;

interface KlaimInterface
{
    public function create(array $data);
    public function all();
    public function update(int $id, array $data);
    public function delete(int $id);
}

?>