<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProdukInterface;
use App\Models\Barang;
use Uuid;
use DB;


class ProdukRepository implements ProdukInterface
{
    protected $model;

    public function __construct(Barang $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
    
    public function create_paket($data)
    {
        return $this->model->create($data);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getBarangTanpaPenjual(){
        return $this->model->where('id_user', '!=', '3dceb0cc-9983-470e-9e2b-67facc51175d')
                            ->get()->all();
    }

    public function showEtalase()
    {
        return $this->model->where('show_etalase', 1)->get();
    }

    public function showByJenis($jenis)
    {
        return $this->model->where('show_etalase', 1)->where('jenis', $jenis)->get();
    }

    public function update($id,$data)
    {
        return $this->model->find($id)->update($data);  
    }  

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByUserId($id)
    {
        return $this->model->where('id_user',$id)->get();
    }

    public function findByKode($kode)    
    {
        return $this->model->where('kode',$kode)->first();
    }

    public function findByIsPaket($flag)
    {
        return $this->model->where('is_paket',$flag)->get();
    }

    public function findByIdKategori($id_kategori)
    {
        return $this->model->where([
            ['id_kategori',$id_kategori],
            ['show_etalase', 1]
            ])->get();
    }

    public function findByIdKategoriOrNama($id_kategori,$nama)
    {
        return $this->model->where('id_kategori',$id_kategori)->where('nama','like','%'.$nama.'%')->get();
    }

    public function findByNamaSubKategori($nama, $tanggal)
    {
        return DB::select("CALL sp_get_barang_by_kategori(?, ?)", array($nama, $tanggal));
    }

    public function findByNamaBarang($nama)
    {
        return $this->model->where('nama', 'like', '%'.$nama.'%')->get();
    }

    public function addStok($id,$value){
        $produk = $this->model->find($id);
        $produk->stok += $value;
        return $produk->save();
    }

    public function removeStok($id,$value){
        $produk = $this->model->find($id);
        $produk->stok -= $value;
        return $produk->save();
    }

    public function getProduct(){
        return $this->model->where('jenis', '!=', 'Paket')->get()->all();
    }
    public function getPaket(){
        return $this->model->where('jenis', '=', 'Paket')->get()->all();
    }
}
?>