<?php

namespace App\Repositories\Interfaces;

interface ParentKeranjangResellerInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    
}

?>