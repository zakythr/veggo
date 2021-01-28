<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\BarangRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\KategoriRepository;

use DB;

class ProdukController extends Controller
{
    protected $barang;
    protected $baseKategori;
    protected $kategori;
    protected $keranjang;

    public function __construct(BarangRepository $barang, BaseKategoriRepository $baseKategori, KategoriRepository $kategori, KeranjangRepository $keranjang)
    {
        $this->middleware('auth');
        $this->middleware('pembeli');

        $this->barang       = $barang;
        $this->baseKategori = $baseKategori;
        $this->kategori     = $kategori;
        $this->keranjang    = $keranjang;
    }


    private function showDetailBarangPaket($id)
    {

    }

    private function showDetailBarangResep($id)
    {

    }

    private function showDetailBarangBiasa($id)
    {
        $data = [
            'detailBarang' => $this->barang->showDetilBarang(),
            'baseKategori' => $this->baseKategori->getAllBaseKategori(),
            'kategori' => $this->kategori->getAll()
        ];
    }

    public function showBarang()
    {
        $data = [
            'barang'        => $this->barang->getAllBarang(),
            'baseKategori'  => $this->baseKategori->getAllBaseKategori(),
            'kategori'      => $this->kategori->getAll()
        ];

        if($this->keranjang->findKeranjang() == null)
        {
            $this->keranjang->tambahKeranjang();
        }

        return view('pembeli.etalase')->with(compact('data'));
    }

    public function showBarangByBaseKategori($id)
    {
        $data = [
            'barang' => $this->barang->barangByKategori(),
            'baseKategori' => $this->baseKategori->getAllBaseKategori(),
            'kategori' => $this->kategori->getAll()
        ];
    }

    public function percobaan()
    {

    }

}
