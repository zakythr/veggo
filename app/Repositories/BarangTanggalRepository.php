<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BarangTanggalInterface;
use App\User;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Models\Barang_tanggal;

class BarangTanggalRepository implements BarangTanggalInterface
{
    protected $model;

    public function __construct(Barang_tanggal $model)
    {
        $this->model = $model;
    }

    public function create($tanggal, $barang)
    {
        $data = [
            'id' => Uuid::generate(4),
            'tanggal' => $tanggal,
            'id_barang' => $barang
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

    public function deleteByTanggal($tanggal){
        return $this->model->where('tanggal',$tanggal)->delete();
    }

    public function deleteByTanggalKurang($tanggal){
        return $this->model->where('tanggal','<=',$tanggal)->delete();
    }

    public function showEtalaseByTanggal($date){
        return $this->model::whereHas('barang', function ($query) use ($date) {
            return $query->where('tanggal', '=',$date);
        })->get();
    }
    public function showByJenisByTanggal($jenis, $date)
    {
        return $this->model::whereHas('barang', function ($query) use ($date) {
            // dd($jenis);
            return $query->where('tanggal', '=',$date);
        })->whereHas('barang', function ($query) use ($jenis) {
            // dd($jenis);
            return $query->where('jenis', '=',$jenis);
        })->get();
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
}
?>