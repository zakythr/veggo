<?php

namespace App\Repositories\Interfaces;

interface KeranjangResellerInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    
}

?>