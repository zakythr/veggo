<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AlamatRepository;
use Illuminate\Support\Facades\DB;

use App\Repositories\KategoriRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\BaseKategoriRepository;
use Illuminate\Support\Facades\Auth;
use URL;

class AlamatController extends Controller
{
    private $alamat;

    public function __construct(AlamatRepository $alamat, KategoriRepository $kategoriRepository, ProdukRepository $produkRepository, BaseKategoriRepository $baseKategoriRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
        $this->produkRepository = $produkRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->middleware('auth');
        $this->middleware('pembeli');

        $this->alamat = $alamat;
    }

    public function showAlamat()
    {
        $data = [
            'alamat' => $this->alamat->getAllAlamatByUser(Auth::user()->id)
        ];

        return view('pembeli.alamat')->with(compact('data'));
    }

    public function showAlamatbyUser($id)
    {
        $alamat = [
            'alamat' => $this->alamat->getAllAlamatByUser($id)
        ];

        $kategori = [
            'sayur' => $this->kategoriRepository->kategoriSayur(),
            'buah' => $this->kategoriRepository->kategoriBuah(),
            'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
            'beras' => $this->kategoriRepository->kategoriBeras(),
            'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
            'lainlain' => $this->kategoriRepository->kategoriLainLain()
        ];

        $data = [
            'barang' => $this->produkRepository->showEtalase(),
            'barang_paket' => $this->produkRepository->showByJenis('Paket'),
            'barang_timbang' => $this->produkRepository->showByJenis('Timbang'),
            'barang_kemas' => $this->produkRepository->showByJenis('Kemas'),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
        ];
        
        //dd($alamat);

        return view('pembeli.alamat')->with(compact('alamat'))
            ->with(compact('data'))
            ->with(compact('kategori'));
    }

    public function hapusAlamat($id)
    {
        $this->alamat->hapusAlamat($id);
        
        return redirect('Pembeli/LihatAlamat/'.Auth::user()->id);
    }

    public function _tambahAlamat(Request $request)
    {
        //dd($request);
        try 
        {
            DB::beginTransaction();

            $this->alamat->tambahAlamat($request->all());
            
            DB::commit();
            
            return redirect(session('url.intended')); 
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return $th;
        }
    }

    public function tambahAlamat()
    {
        session()->put('url.intended', URL::previous());
        return view('pembeli.tambah-alamat');
    }

    public function ubahAlamat($id)
    {
        $alamat=$this->alamat->getAlamatById($id);
        return view('pembeli.ubah-alamat')->with(compact('alamat'));
    }

    public function _ubahAlamat($id, Request $request)
    {
        try 
        {
            DB::beginTransaction();

            $this->alamat->ubahAlamat($id, $request->all());
            
            DB::commit();
            
            return redirect('Pembeli/LihatAlamat/'.Auth::user()->id);
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return $th;
        }
    }
}
