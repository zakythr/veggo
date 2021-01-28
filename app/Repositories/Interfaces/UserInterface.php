<?php

namespace App\Repositories\Interfaces;

interface UserInterface
{
    public function create(array $data);
    public function read();
    public function find($id);
    public function update(array $data);
    public function delete(int $id);

    public function getPetani();
    public function detail($id);
    public function getKurir();
    
}

?>