<?php

namespace App\Http\Controllers\Reseller;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\KeranjangResellerRepository;
use App\Repositories\ParentKeranjangResellerRepository;
use App\Repositories\TanggalRepository;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    private $keranjangReseller;

    public function __construct(KeranjangResellerRepository $keranjangReseller, ParentKeranjangResellerRepository $parentKeranjangReseller, TanggalRepository $tanggal)
    {
        $this->middleware('auth');
        $this->middleware('reseller');

        $this->keranjangReseller = $keranjangReseller;
        $this->parentKeranjangReseller = $parentKeranjangReseller;
        $this->tanggal = $tanggal;
    }

    public function show($id, $date)
    {
        $data = [
            'tanggal' =>$this->getTanggalPengiriman(),
            'tanggals'=>$date,
            'keranjangReseller' => $this->parentKeranjangReseller->getAllUserByUserandStatus(Auth::user()->id, $date)
        ];

        return view('reseller.users')->with(compact('data'));
    }
    public function getTanggalPengiriman()
    {       

        $getListHari=$this->tanggal->get_tanggal();

        for($a=0;$a<sizeof($getListHari);$a++)
        {
            $tgl=$getListHari[$a]['tanggal'];
            // string
            $finalListHari[$a]['tanggal'] = Carbon::parse($tgl)->format('D, d F Y');
            //tanggal value
            $finalListHari[$a]['tanggal_value'] = Carbon::parse($tgl)->format('Y-m-d');

            // dd($finalListHari[$a]['tanggal_value']);
        }

        

        foreach ($finalListHari as $key => $value) 
        {
            $finalListHari[$key] = (object) $value;
        }

        // dd($finalListHari);
    
        return $finalListHari;
    }

    public function hapus($id)
    {
        $this->keranjangReseller->hapusUser($id);
        $this->parentKeranjangReseller->hapusUser($id);
        
        return redirect()->back();
    }

    public function detail($id)
    {
        $keranjang=$this->keranjangReseller->getUserById($id);
        $total=0;
        foreach($keranjang as $ker){
            $total+=$ker->harga_diskon;
        }
        $data=[
            'keranjangReseller'=>$keranjang,
            'dataPembeli'=>$this->parentKeranjangReseller->getUserById($id),
            'total'=>$total,
            
        ];
        // dd($data['keranjangReseller']);
        return view('reseller.detail-user')->with(compact('data'));
    }

    public function hapusBarang($id_barang, $id_parent){
        $this->keranjangReseller->hapusBarang($id_barang, $id_parent);
        return redirect()->back();
    }
}
