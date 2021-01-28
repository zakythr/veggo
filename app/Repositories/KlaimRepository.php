<?php

namespace App\Repositories;
use App\Repositories\Interfaces\KlaimInterface;
use App\Models\Klaim;
use Uuid;
use Carbon\Carbon;

class KlaimRepository implements KlaimInterface
{
    protected $model;

    public function __construct(Klaim $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {

        #tanggal pre order
        $date = Carbon::now();

        #generate nomor invoice
        $rand           = substr(uniqid('', true), -5);
        $kode_klaim  = 'KL' . $rand;

        $data['id'] = Uuid::generate()->string;
        $data['kode_klaim'] = $kode_klaim;

        return $this->model->create($data);
    }

    public function all()
    {
        return $this->model->orderBy('created_at', 'DESC')->get()->all();
    }

    public function update(int $id,array $data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    public function getById($id){
        return $this->model->where('klaim_to', $id)->get()->all();
    }

    public function getByIdTrx($id){
        return $this->model->where('id', $id)->get()->all();
    }
}
?>