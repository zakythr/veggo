<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BobotKemasanInterface;
use App\Models\Bobot_Kemasan;

class BobotKemasanRepository implements BobotKemasanInterface
{
    protected $model;

    public function __construct(Bobot_Kemasan $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function update(int $id,array $data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }
    
}
?>