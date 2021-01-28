<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Repositories\ProdukRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\KategoriRepository;

class EtalaseController extends Controller
{
    protected $barang;
    protected $baseKategori;
    protected $kategori;

    public function __construct(ProdukRepository $barang, BaseKategoriRepository $baseKategori, KategoriRepository $kategori)
    {
        $this->barang = $barang;
        $this->baseKategori = $baseKategori;
        $this->kategori = $kategori;
    }

    public function showBarang()
    {
        if(Auth::user() != null)
        {
            return redirect('/Pembeli/Etalase');
        }

        $kategori = [
            'sayur' => $this->kategori->kategoriSayur(),
            'buah' => $this->kategori->kategoriBuah(),
            'makanansehat' => $this->kategori->kategoriMakananSehat(),
            'minumansehat' => $this->kategori->kategoriMinumanSehat(),
            'beras' => $this->kategori->kategoriBeras(),
            'bahanolahan' => $this->kategori->kategoriBahanOlahan(),
            'berkebun' => $this->kategori->kategoriBerkebun(),
            'lainlain' => $this->kategori->kategoriLainLain()
        ];

        $data = [
            'barang' => $this->barang->all(),
            'barang_paket' => $this->barang->showByJenis('Paket'),
            'barang_timbang' => $this->barang->showByJenis('Timbang'),
            'barang_kemas' => $this->barang->showByJenis('Kemas'),
            'kategori' => $this->kategori->array(),
            'baseKategori' => $this->baseKategori->all()
        ];

        return view('pembeli.etalase')
            ->with(compact('data'))
            ->with(compact('kategori'));
    }
}
