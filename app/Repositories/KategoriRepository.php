<?php

namespace App\Repositories;
use App\Repositories\Interfaces\KategoriInterface;
use App\Models\Kategori;

class KategoriRepository implements KategoriInterface
{
    protected $model;

    public function __construct(Kategori $model)
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

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function update($id,array $data)
    {
        return $this->model->find($id)->update($data);  
    } 

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function array()
    {
        $kategori = [
            'sayur' => $this->kategoriSayur(),
            'buah' => $this->kategoriBuah(),
            'makanansehat' => $this->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriMinumanSehat(),
            'beras' => $this->kategoriBeras(),
            'bahanolahan' => $this->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriBerkebun(),
            'lainlain' => $this->kategoriLainLain()
        ];
        return $kategori;
    }

    public function getNamaKategori(){
        return $this->model->select('sub_kategori')->get();
    }

    public function kategoriSayur()
    {
        return $this->model->where('kategori', 1)->get();
    }

    public function kategoriBuah()
    {
        return $this->model->where('kategori', 2)->get();
    }

    public function kategoriMakananSehat()
    {
        return $this->model->where('kategori', 5)->get();
    }

    public function kategoriMinumanSehat()
    {
        return $this->model->where('kategori', 6)->get();
    }

    public function kategoriBeras()
    {
        return $this->model->where('kategori', 3)->get();
    }

    public function kategoriBahanOlahan()
    {
        return $this->model->where('kategori', 4)->get();
    }

    public function kategoriBerkebun()
    {
        return $this->model->where('kategori', 7)->get();
    }

    public function kategoriLainLain()
    {
        return $this->model->where('kategori', 8)->get();
    }
}
?>