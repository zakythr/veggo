<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Repositories\TransaksiRepository;
use App\Repositories\UserRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\InventarisRepository;
use App\Repositories\HariPengirimanRepository;
use App\Repositories\DetailKlaimRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\TanggalRepository;
use DB;

class OrderPetaniController extends Controller
{
    private $transaksiRepository,
            $detailTransaksiRepository,
            $produkRepository,            
            $userRepository,           
            $hariPengirimanRepository, 
            $inventarisRepository,
            $detailKlaimRepository,
            $tanggal;

    public function __construct(TransaksiRepository $transaksiRepository, DetailTransaksiRepository $detailTransaksiRepository,InventarisRepository $inventarisRepository, ProdukRepository $produkRepository, UserRepository $userRepository,HariPengirimanRepository $hariPengirimanRepository,DetailKlaimRepository $detailKlaimRepository, TanggalRepository $tanggal)
    {   
        $this->transaksiRepository = $transaksiRepository;
        $this->detailTransaksiRepository = $detailTransaksiRepository;
        $this->inventarisRepository = $inventarisRepository;
        $this->produkRepository = $produkRepository;
        $this->userRepository = $userRepository;
        $this->hariPengirimanRepository = $hariPengirimanRepository;
        $this->detailKlaimRepository = $detailKlaimRepository;
        $this->tanggal     = $tanggal;
        $this->middleware('auth');
    }
    
    public function orderPetani()
    {
        $data = [
            'orderPetani' => $this->transaksiRepository->findByTipeTransaksi("FROM_VEGGO")
        ];

        return view('penjual.orderpetani')->with('data',$data);
    }
    
    public function konfirmasiPenerimaan($invoice)
    {   
        $data = [
            'invoice' => $invoice,
            'orderPetani' => $this->transaksiRepository->findByNomorInvoice($invoice)
        ];
        
        foreach ($data['orderPetani']->detailTransaksi as $key => $value) {
            $det=$this->detailKlaimRepository->getDetailKlaimByIdDetailTransaksi($data['orderPetani']->detailTransaksi[$key]['id']);
            $count=0;
            foreach ($det as $dett) {
                $count+=$dett->volume_klaim;
            }
            // dd($data['orderPetani']->detailTransaksi[$key]['id']);

            $data['orderPetani']->detailTransaksi[$key]['klaim']=$count;
        }
        // dd($data);
        return view('penjual.konfirmasi_orderpetani')->with('data',$data);
    }

    public function _konfirmasiPenerimaan(Request $request)
    {
        // dd($request->input());   
        $detailTransaksi = $request->input('id_detail_transaksi');
        $barang = $request->input('id_barang');
        $volumeTerima = $request->input('volume_terima');
        $bobotTerima=$request->input('bobot_terima');
        $id_transaksi = $request->input('id_transaksi');
        $selisih_terima=$request->input('selisih_terima');
        try {
            DB::beginTransaction();
            $flag = 3;
            $this->transaksiRepository->updateStatusOrderKePetani($request->input('id_transaksi'),$flag);
            $this->transaksiRepository->updateTanggalTerima($request->input('id_transaksi'),Carbon::now());
            foreach ($detailTransaksi as $key => $value) {
                $id = $detailTransaksi[$key];
                $id_barang = $barang[$key];
                $value = $volumeTerima[$key];
                $value2=$bobotTerima[$key];
                $value3=$selisih_terima[$key];
                $this->detailTransaksiRepository->updatePenerimaanOrderKePetani($id,$value, $value2,$value3, $flag);
                $this->inventarisRepository->inventarisIn($id_barang,$id_transaksi,$value*$value2,"Order Ke Petani");
                $this->produkRepository->addStok($id_barang,$value*$value2);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }        

        return redirect()->back();
    }
    
    private function getTanggalPengiriman()
    {
        $getListHari=$this->tanggal->get_tanggal();
        // dd(count($getListHari));

        // $finalListHari=null;
        // dd($getListHari);
        if(count($getListHari)>0){
        for($a=0;$a<sizeof($getListHari);$a++)
        {
            $tgl=$getListHari[$a]['tanggal'];
            // string
            $finalListHari[$a]['tanggal'] = Carbon::parse($tgl)->format('D, d F Y');
            //tanggal value
            $finalListHari[$a]['tanggal_value'] = Carbon::parse($tgl)->format('Y-m-d');

            // dd($finalListHari[$a]['tanggal_value']);
        }        
        }
        else{
            $finalListHari[0]['tanggal'] = Carbon::now()->format('D, d F Y');
            //tanggal value
            $finalListHari[0]['tanggal_value'] = Carbon::now()->format('Y-m-d');
        }

        // dd($finalListHari);
        foreach ($finalListHari as $key => $value) 
        {
            $finalListHari[$key] = (object) $value;
        }

        // dd($finalListHari);
    
        return $finalListHari;
    }

    public function tambahOrderPetani(){
        
        $data = [
            'barang' => $this->produkRepository->getBarangTanpaPenjual(),
            'tanggal_kirim' => $this->getTanggalPengiriman(),
        ];

        // dd($data);

        return view('penjual.tambah_orderpetani')->with('data',$data);
    }

    public function _tambahOrderPetani(Request $request){
        $volume_arr = $request->input('volume');
        $bobot_arr = $request->input('bobot');
        $barang_arr = $request->input('barang');
        $tanggal = $request->input('tanggal_kirim');
        $barang = [];
        $supplier = [];

        // dd($tanggal);

        for($i=0;$i<count($barang_arr);$i++){
            $data = $this->produkRepository->findById($barang_arr[$i]);
            $data->volume = $volume_arr[$i];
            $data->bobot = $bobot_arr[$i];
            array_push($barang,$data);
        }
        // dd($barang);

        foreach($barang as $item){
            if(in_array($item->id_user,$supplier)){

            }else{
                array_push($supplier,$item->id_user);
            }
        }

        foreach($supplier as $s_key => $s){
            $petani = $this->userRepository->find($s);
            $kePetani = [
                'id_user'=> $s,
                'petani' => $petani,
                'tanggal' => $tanggal
            ];            
            $transaksi = $this->transaksiRepository->orderKePetani($kePetani);
            foreach($barang as $b_key => $d){
                if($d->id_user == $supplier[$s_key]){
                    $detailKePetani = [
                        'id_transaksi' => $transaksi->id,
                        'barang' => $d,
                        'akumulasi_barang' => $d
                    ];                    
                    $this->detailTransaksiRepository->detailOrderKePetani($detailKePetani);
                }
            }
        }
        // dd([$arr,$supplier]);
        return redirect()->back();
        // $this->transaksiRepository->orderKePetani();
    }

}
;