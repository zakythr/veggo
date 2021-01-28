<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;

use Illuminate\Support\Facades\Storage;
use Response;
use DB;


class BayarPetaniController extends Controller
{

    protected $transaksiRepository;

    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        
        $this->middleware('auth');
        $this->middleware('penjual');

    }    

    public function showAll($date){
        $transaksi=DB::select('call sp_get_total_bayar_veggo_by_tanggal(?)',array($date));
        $total=DB::select('call sp_get_total_bayar_veggo_by_petani(?)',array($date));
        $tanggal=$this->transaksiRepository->getTglPetani();
        // dd($transaksi);

        $data=[
            'filter_tanggal' =>$date,
            'transaksi' => $transaksi,
            'tanggal' => $tanggal,
            'total' => $total
        ];

        return view('penjual.pembayaran')->with('data',$data);
    }
    
}
