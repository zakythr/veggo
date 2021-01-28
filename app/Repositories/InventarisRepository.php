<?php

namespace App\Repositories;
use App\Repositories\Interfaces\InventarisInterface;
use App\Models\Inventaris;
use Uuid;
use Carbon\Carbon;

class InventarisRepository implements InventarisInterface
{
    protected $model;

    public function __construct(Inventaris $model)
    {
        $this->model = $model;
    }

    public function create($data)
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

    public function inventarisIn($id_barang,$id_transaksi,$value,$keterangan)
    {   
        $data = [
            'id' => Uuid::generate()->string,
            'id_barang' => $id_barang,
            'id_transaksi' => $id_transaksi,
            'tanggal' => Carbon::now(),
            'status' => 'IN',
            'keterangan' => $keterangan, 
            'jumlah' => $value
        ];

        $this->create($data);
    }

    public function inventarisOut($id_barang,$id_transaksi,$value,$keterangan)
    {
        $data = [
            'id' => Uuid::generate()->string,
            'id_barang' => $id_barang,
            'id_transaksi' => $id_transaksi,
            'tanggal' => Carbon::now(),
            'status' => 'OUT',
            'keterangan' => $keterangan, 
            'jumlah' => $value
        ];

        $this->create($data);        
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
}
?>