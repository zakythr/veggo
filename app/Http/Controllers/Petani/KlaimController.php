<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Repositories\TransaksiRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\KlaimRepository;
use App\Repositories\DetailKlaimRepository;
use Illuminate\Http\Request;
use \stdClass;
use DB;
use Auth;

class KlaimController extends Controller
{
    //
    protected $transaksiRepository,
              $klaimRepository,
              $detailKlaimRepository,
              $produkRepository;    


    public function __construct(TransaksiRepository $transaksiRepository, ProdukRepository $produkRepository,KlaimRepository $klaimRepository,DetailKlaimRepository $detailKlaimRepository)
    {   
        $this->transaksiRepository = $transaksiRepository;
        $this->produkRepository = $produkRepository;
        $this->klaimRepository = $klaimRepository;
        $this->detailKlaimRepository = $detailKlaimRepository;
        $this->middleware('auth');
        $this->middleware('petani');
    }

    public function klaim(){
        $data = [
            'klaim' => $this->klaimRepository->getById(Auth::user()->id)
        ];

        // dd($data['klaim']->dari);

        return view('petani.klaim')->with('data',$data);
        
    }
    
    public function detailKlaim($id){
        $data = [
            'klaim' => $this->detailKlaimRepository->getDetailKlaimById($id)
        ];
        // dd($data['klaim']);

        return view('petani.detail_klaim')->with('data', $data);
    }
}
