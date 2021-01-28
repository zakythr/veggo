<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

use App\Repositories\KategoriRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\UserRepository;

class ProfilController extends Controller
{
    protected $kategoriRepository, $produkRepository, $baseKategoriRepository, $userRepository;

    public function __construct(KategoriRepository $kategoriRepository, ProdukRepository $produkRepository, BaseKategoriRepository $baseKategoriRepository, UserRepository $userRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
        $this->produkRepository = $produkRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->userRepository=$userRepository;

        $this->middleware('auth');
        $this->middleware('reseller');
    }

    public function viewProfile()
    {
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
            'user' => Auth::user()
        ];
        
        return view('reseller.profil')
            ->with(compact('data'))
            ->with(compact('kategori'));
    }

    public function editProfile(){
        $data=[
            'user'=>Auth::user()
        ];
        return view('reseller.ubah-profil')->with(compact('data'));
    }

    public function _editProfile(Request $request){
        try 
        {
            DB::beginTransaction();

            $this->userRepository->update($request->all());
            
            DB::commit();
            
            return redirect('Reseller/Profil/');
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return $th;
        }
    }
}
