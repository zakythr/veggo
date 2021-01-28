<?php

namespace App\Repositories;
use App\Repositories\Interfaces\UserInterface;
use App\User;
use Auth;

class UserRepository implements UserInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function read()
    {
        return $this->model->all();
    }

    public function update(array $data)
    {
        return $this->model->find(Auth::user()->id)->update($data);  
    } 

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    public function getPetani()
    {
        return $this->model->where('role',3)->get();
    }

    public function detail($id)
    {
        return $this->model->where('id', $id)->first();
    }
    public function getKurir()
    {
        return $this->model->where('role',5)->get();
    }
    public function getVeggo()
    {
        return $this->model->select('nomor_rek', 'bank', 'atas_nama')->where('role',1)->first();
    }
    public function getReseller()
    {
        return $this->model->where('role',4)->get();
    }

    public function find($id){
        return $this->model->find($id);
    }

}
?>