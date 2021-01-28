<?php

namespace App\Http\Controllers\Penjual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\TransaksiRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\UserRepository;
use App\Repositories\ParentKeranjangResellerRepository;
use App\Repositories\KeranjangResellerRepository;
use Carbon\Carbon;
use Uuid;
use Auth;
use View;
use DB;


class PreOrderController extends Controller
{
    //

    protected $transaksiRepository,
              $detailTransaksiRepository,
              $userRepository,
              $produkRepository,
              $parentKeranjangResellerRepository,
              $keranjangResellerRepository;
    
    public function __construct(TransaksiRepository $transaksiRepository, DetailTransaksiRepository $detailTransaksiRepository, ProdukRepository $produkRepository, UserRepository $userRepository, ParentKeranjangResellerRepository $parentKeranjangResellerRepository, KeranjangResellerRepository $keranjangResellerRepository)
    {   
        $this->transaksiRepository = $transaksiRepository;
        $this->detailTransaksiRepository = $detailTransaksiRepository;
        $this->userRepository = $userRepository;
        $this->produkRepository = $produkRepository;
        $this->parentKeranjangResellerRepository = $parentKeranjangResellerRepository;
        $this->keranjangResellerRepository = $keranjangResellerRepository;

        $this->middleware('auth');
        $this->middleware('penjual');
    }

    public function preOrder($date){
        // dd("test");
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 1;
        $cancel=0;

        $result = $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis($tanggal,$status,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];
        


        return view('penjual.preorder')->with('data',$data);
    }

    public function prosesOrder($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 2;
        $cancel=0;

        $result = $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis($tanggal,$status,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];


        return view('penjual.preorder_proses')->with('data',$data);
    }

    public function siapKirim($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 4;
        $cancel=0;

        $result = $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis($tanggal,$status,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];
        
        return view('penjual.preorder_siap_kirim')->with('data',$data);
    }

    public function dalamPengiriman($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 6;
        $cancel=0;

        $result = $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis($tanggal,$status,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        // dd($result);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];

        return view('penjual.preorder_proses')->with('data',$data);
    }

    public function selesai($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 7;
        $cancel=0;

        $result = $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis($tanggal,$status,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];

        return view('penjual.preorder_selesai')->with('data',$data);
    }

    public function batal($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $cancel=1;
        $status=0;

        $result = $this->transaksiRepository->getAllTransaksiIsCancelled($tanggal,$tipe);
        $resultCount = $this->transaksiRepository->getCountVerif($status,$tipe, $cancel);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus($status, $tipe, $cancel);

        foreach($tanggals as $tgl){
            $tgl->total=0;
            foreach($resultCount as $resulttcount){
                if($resulttcount->tanggal_pre_order==$tgl->tanggal_pre_order){
                    $tgl->total+=$resulttcount->total;
                }
                else{
                    $tgl->total+=0;
                }
            }
        }

        $data = [
            'preorder' => $result,
            'isPaid' => [["Belum Lunas","0"],["Lunas","1"]],
            'status' => [['Pre Order',1],['Siap Kirim',4],['Pengiriman',5],['Batal',7],['Selesai',6]],
            'status_pembayaran' => [['Tunggu Konfirmasi',1],['Gagal',2],['Lunas',3],['Belum Lunas',0]],
            'tanggal' => $tanggals,
            'filter_tanggal' => $tanggal
        ];

        return view('penjual.preorder_batal')->with('data',$data);
    }

    public function tambahPreOrder(){
        return view('penjual.tambah_preorder');
    }

    public function _tambahPreOrder(){
        
    }

    public function _filterTanggal(Request $request){
        session(['tanggal_pre_order' => $request->input('tanggal')]);
        return redirect('/Penjual/PreOrder/Akumulasi');
    }    

    private function findObjectAkumulasi($kode_barang,$akumulasi){
        if(count($akumulasi) < 0){
            return false;
        }else{
            for($i=0;$i<count($akumulasi);$i++){
                if($akumulasi[$i]->kode_barang == $kode_barang){
                    return "key|".$i;
                }
            }
        }
    }
    
    public function rekap_akumulasi($tanggal){
        // dd($request->input());

        $rekap = DB::select('call sp_get_total_pre_order(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_paket = DB::select('call sp_get_total_paket(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_non_paket = DB::select('call sp_get_total_non_paket(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_timbang=DB::select('call sp_get_total_timbang(?, ?)', [$tanggal, Auth::user()->id]);
        
        $merged = array_merge($rekap_paket, $rekap_non_paket, $rekap_timbang);
        $akumulasi = [];

        $data = [
            'current_date' => $tanggal,
            'rekap' => $rekap,
            'rekap_sayur' => $merged
        ];

        // dd($data);

        return view('penjual.rekap_akumulasi')->with('data',$data);
    }

    public function rekap_detail($kode_barang,$date){
        // $pembeli = DB::select('call sp_get_buyer_list(?,?)', [$kode_barang,$date]);
        // dd($pembeli);
        return $pembeli = DB::select('call sp_get_buyer_list(?,?)', [$kode_barang,$date]);
    }

    public function _rekap_akumulasi(Request $request){

        
        $tanggal = $request->input('date');

        $rekap = DB::select('call sp_get_total_pre_order(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_paket = DB::select('call sp_get_total_paket(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_non_paket = DB::select('call sp_get_total_non_paket(?, ?)', [$tanggal, Auth::user()->id]);
        $rekap_timbang=DB::select('call sp_get_total_timbang(?, ?)', [$tanggal, Auth::user()->id]);
        
        $merged = array_merge($rekap_paket, $rekap_non_paket, $rekap_timbang);
        $akumulasi = [];
        $supplier = [];

        // dd($merged);
        
        // for($i = 0 ; $i<count($merged) ; $i++){
        //     $current = $merged[$i];
        //     if($this->findObjectAkumulasi($current->kode_barang, $akumulasi)){
        //         $temp = $this->findObjectAkumulasi($current->kode_barang, $akumulasi);
        //         $key = (int) explode("|", $temp)[1];
        //         $temp_total = (int) $akumulasi[$key]->total;
        //         $current_total = (int) $current->total;
        //         $akumulasi[$key]->total = $temp_total + $current_total;
        //     }else{
        //         array_push($akumulasi,$current);
        //     }
        // }         

        for($i = 0 ; $i<count($merged) ; $i++){
            $next = $merged[$i];
            if(in_array($next->supplier_barang,$supplier)){
                
            }else{
                array_push($supplier,$next->supplier_barang);
            }
        }

        // dd([$supplier,$akumulasi]);

        try {
            DB::beginTransaction();
            $list_transaksi = $this->transaksiRepository->getIncludedRekap($request->input('date'),0);

            for($i = 0 ; $i<count($list_transaksi) ; $i++)
            {
                $this->transaksiRepository->update($list_transaksi[$i]->id,["status" => 2]);
                $this->detailTransaksiRepository->updateByIdTransaksi($list_transaksi[$i]->id,["status" => 2]);
            }


            for($i = 0 ; $i<count($supplier) ; $i++){
                $petani = $this->userRepository->find($supplier[$i]);
                $kePetani = [
                    'id_user'=> $supplier[$i],
                    'petani' => $petani,
                    'tanggal' => $tanggal
                ];
                // dd($kePetani);
                $transaksi = $this->transaksiRepository->orderKePetani($kePetani);
                // dd($transaksi);
                for($j = $i ; $j<count($merged) ; $j++){
                    if($merged[$j]->supplier_barang == $supplier[$i]){
                        // dd($akumulasi[$j]);
                        $barang = $this->produkRepository->findByKode($merged[$j]->kode_barang);
                        $detailKePetani = [
                            'id_transaksi' => $transaksi->id,
                            'barang' => $barang,
                            'akumulasi_barang' => $merged[$j]
                        ];
                        $this->detailTransaksiRepository->detailOrderKePetani($detailKePetani);
                    }
                }
            }            

            $list_transaksi = $this->transaksiRepository->getIncludedRekap($request->input('date'),1);
            for($i = 0 ; $i<count($list_transaksi) ; $i++){
                $this->transaksiRepository->update($list_transaksi[$i]->id,["is_exclude_rekap" => 0]);
                $this->detailTransaksiRepository->updateByIdTransaksi($list_transaksi[$i]->id,["is_exclude_rekap" => 0]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
        }

        return redirect('/Penjual/OrderPetani');
    }

    public function akumulasi(){
        $transcationDate = $this->transaksiRepository->getTransactionDateWithStatus(1, "FROM_BUYER", 0);
        if(count($transcationDate) == 1){
            $date = $transcationDate[0]->tanggal_pre_order;
            session(['tanggal_pre_order' => $date]);
        }
        elseif(session('tanggal_pre_order') == null){
            $date = Carbon::now()->toDateString();
            session(['tanggal_pre_order' => $transcationDate[0]->tanggal_pre_order]);
        }else{
            $date = session('tanggal_pre_order');
        }
        $status = 1;
        $tipe = "FROM_BUYER";
        $preordeer=$this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakisAkumulasi($date,$status,$tipe);
        $data = [
            'preorder' => $preordeer,
            'current_date' => Carbon::now()->toDateString(),
            'date' => $transcationDate
        ];

        // dd($data['preorder'][1]->detailTransaksi[0]);
        return view('penjual.tambah_akumulasi')->with('data',$data);
    }

    public function _excludeDetailPreOrder(Request $request){    
        try {
            DB::beginTransaction();
            $this->detailTransaksiRepository->excludeDetailPreOrder($request->id);
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }
    }

    public function _excludePreOrder(Request $request){    
        try {
            DB::beginTransaction();
            $details = $this->detailTransaksiRepository->findByTransaksiId($request->id);
            $this->transaksiRepository->excludeRekapPreOrder($request->id);
            foreach($details as $detail){
                $this->detailTransaksiRepository->excludeDetailPreOrder($detail->id);
            }
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }
    }

    public function _batalkanDetailPreOrder(Request $request){    
        try {
            DB::beginTransaction();
            $this->detailTransaksiRepository->cancelDetailPreOrder($request->id);
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }
    }

    public function _batalkanPreOrder(Request $request){    
        try {
            DB::beginTransaction();
            $details = $this->detailTransaksiRepository->findByTransaksiId($request->id);
            $this->transaksiRepository->cancelPreOrder($request->id);
            foreach($details as $detail){
                $this->detailTransaksiRepository->cancelDetailPreOrder($detail->id);
            }
            DB::commit();
            return 1;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }
    }

    public function _updatePembayaran(Request $request){
        // dd($request->input());
        try {
            //code...
            DB::begintransaction();
                $id = $request->input('id_transaksi');
                $data = [
                    'isAlreadyPay' => $request->input('status_transaksi')
                ];
                $this->transaksiRepository->update($id,$data);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            dd($th);
        }

        return redirect()->back();


    }
    public function pembeliOffline($id){
        $pembeliOffline=$this->getPembeliOffline($id);

        $data = [];
        foreach($pembeliOffline as $key => $off){
            $data[$key]['name'] = $off->name;
            $data[$key]['alamat'] = $off->alamat;
            $data[$key]['nohp'] = $off->nohp;
            // $data[$key]['data'] = $this->produkRepository->getEtalaseBarangByIdKategori($kategori->id);
            $data[$key]['data'] = $this->keranjangResellerRepository->getUserById($off->id);
            
        }

        $collection = collect($data);
        // dd($collection);
        return view('penjual.pembeli_offline')->with('collection',$collection);
    }
    
    public function getPembeliOffline($id){
        $getParentKeranjang=$this->parentKeranjangResellerRepository->getFromIdTransaksi($id);
        return $getParentKeranjang;
    }

    public function orderDetail($id){
        $getDetail=$this->detailTransaksiRepository->findByTransaksiId($id);
        $getTransaksi=$this->transaksiRepository->find($id);
        $data=[
            'detail'=>$getDetail,
            'transaksi'=>$getTransaksi
        ];
        // dd($collection);
        return view('penjual.detail_order')->with('data',$data);
    }
}
