<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;

use Illuminate\Support\Facades\Storage;
use Response;
use DB;


class PembayaranController extends Controller
{

    protected $transaksiRepository;

    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        
        $this->middleware('auth');
        $this->middleware('petani');

    }    

    public function showAll($date){
        $transaksi=$this->transaksiRepository->hargaBarangByPetani($date);
        $tanggal=$this->transaksiRepository->getTglByIdPetani();
        $total=0;
        
        foreach($transaksi as $tgl){
            $total+=$tgl->harga;
        }

        $data=[
            'filter_tanggal' =>$date,
            'transaksi' => $transaksi,
            'tanggal' => $tanggal,
            'total'=>$total
        ];

        // dd($data);

        return view('petani.pembayaran')->with('data',$data);
    }
    
}
