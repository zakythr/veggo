<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProdukRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\EtalaseRepository;

use DB;

class EtalaseController extends Controller
{
    protected $produkRepository, $kategoriRepository, $baseKategoriRepository, $etalaseRepository;

    public function __construct(ProdukRepository $produkRepository, KategoriRepository $kategoriRepository, BaseKategoriRepository $baseKategoriRepository, EtalaseRepository $etalaseRepository)
    {
        $this->produkRepository = $produkRepository;
        $this->kategoriRepository = $kategoriRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->etalaseRepository = $etalaseRepository;

        $this->middleware('auth');
        $this->middleware('penjual');

    }        

    public function showEtalase()
    {
        $data = [
            'barang'        => $this->produkRepository->all(),
            'baseKategori'  => $this->baseKategoriRepository->all(),
            'kategori'      => $this->kategoriRepository->all()
        ];
        // dd($collecion);

        if($this->keranjang->findKeranjang() == null)
        {
            $this->keranjang->tambahKeranjang();
        }

        return view('pembeli.etalase')->with(compact('data'));
    }

    public function etalase(){
        
        $kategoris = $this->baseKategoriRepository->all();

        // dd($kategoris);
        
        $data = [];
        
        foreach($kategoris as $key => $kategori){
            $data[$key]['kategori'] = $kategori->kategori;
            // $data[$key]['data'] = $this->produkRepository->getEtalaseBarangByIdKategori($kategori->id);
            $data[$key]['data'] = $this->etalaseRepository->findByKategori($kategori->id);
            
        }

        $collection = collect($data);
        // dd($collection);

        return view('penjual.etalase')->with('collection', $collection);
    }    

    public function kelolaEtalase(){
        if(isset($_GET['kategori'])){
            $id_kategori = $_GET['kategori'];
            session(['search_kategori' => $id_kategori]);
        }
        else if(isset($_GET['nama'])){
            $nama_barang = $_GET['nama'];
            session(['search_nama_barang' => $nama_barang]);
        }


        $getAllBarang = $this->produkRepository->findByIdKategoriOrNama(session('search_kategori'),session('search_nama_barang'));
        $getKategori = $this->baseKategoriRepository->all();        

        $data = [
            "kategoris" > $getKategori,
            "barang" => $getAllBarang,
            "id_kategori" => session('search_kategori'),
            "nama_barang" => session('search_nama_barang')
        ];

        return view('penjual.tambah_etalase')->with('data',$data)->with('kategoris',$getKategori);

    }

    public function _kelolaEtalase(Request $request){
        
        $updateBarangs = $request->etalase;

        if($updateBarangs != null){
            foreach($updateBarangs as $updateBarang){
                $this->etalaseRepository->updateShowEtalase($updateBarang,1);
            }        
        }

        return redirect()->back();


    }

}
