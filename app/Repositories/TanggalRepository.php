<?php

namespace App\Repositories;
use App\Repositories\Interfaces\TanggalInterface;
use App\User;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Models\Tanggal;

class TanggalRepository implements TanggalInterface
{
    protected $model;

    public function __construct(Tanggal $model)
    {
        $this->model = $model;
    }

    public function create($tanggal, $flag)
    {
        $data = [
            'id' => Uuid::generate(4),
            'tanggal' => $tanggal,
            'flag' => $flag
        ];
        return $this->model->create($data);
    }

    public function read()
    {
        return $this->model->all();
    }

    public function update($tanggal, $flag)
    {
        $data = [
            'flag' => $flag
        ];
        return $this->model->where([
            ['tanggal', $tanggal]
            ])->update($data);  
    } 

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    public function hapusTanggal($tanggal)
    {
        $data = [
            'flag' => 0
        ];
        return $this->model->where([
            ['tanggal', '<=', $tanggal]
            ])->update($data); 
    }
    public function find_tanggal($tanggal)
    {
        return $this->model->where([
            ['tanggal', $tanggal],
            ['flag',1]
            ])->orderBy('tanggal')
            ->get();
    }

    public function find_tanggal_not_status($tanggal)
    {
        return $this->model->where([
            ['tanggal', $tanggal]
            ])->orderBy('tanggal')
            ->get();
    }

    public function get_tanggal(){
        return $this->model->where([
            ['flag', 1]
            ])->orderBy('tanggal')
            ->get();
    }


}
?>