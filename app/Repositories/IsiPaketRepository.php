<?php

namespace App\Repositories;
use App\Repositories\Interfaces\IsiPaketInterface;
use App\Models\Isi_paket;
use Uuid;
use DB;

class IsiPaketRepository implements IsiPaketInterface
{
    protected $model;

    public function __construct(Isi_paket $model)
    {
        $this->model = $model;
    }

    public function create($id,$items)
    {
        if($this->model->where('id_barang_parent',$id) != null){
            $this->model->where('id_barang_parent',$id)->delete();
        }

        foreach($items['isi_paket'] as $key => $item){

            $data = [
                'id' => Uuid::generate(4),
                'id_barang_parent' => $id,
                'id_barang' => $item,
                'volume' => $items['volume_isi_paket'][$key],
                'harga' => $items['harga_isi_paket'][$key],
            ];

            $this->model->create($data);
        }

    }

    public function findByIdBarang($id_barang)
    {
        return $this->model->where('id_barang_parent', $id_barang)->get();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function update($id,$data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function read($id_barang_parent)
    {
        return DB::select('call sp_get_isi_paket(?)',array($id_barang_parent));
    }
    public function deleteByIdBarang($id_barang){
        return $this->model->where('id_barang',$id_barang)->delete();
    }
    public function deleteByIdParentBarang($id_barang){
        return $this->model->where('id_barang_parent',$id_barang)->delete();
    }
}
?>