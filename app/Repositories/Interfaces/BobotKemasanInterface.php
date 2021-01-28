<?php

namespace App\Repositories\Interfaces;

interface BobotKemasanInterface
{
    public function create(array $data);
    public function all();
    public function update(int $id, array $data);
    public function delete(int $id);
}

?>