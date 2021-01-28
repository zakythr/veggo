<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TransaksiRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\ProdukRepository;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    private $transaksiRepository,
            $detailTransaksiRepository,
            $produkRepository;

    public function __construct(TransaksiRepository $transaksiRepository, DetailTransaksiRepository $detailTransaksiRepository, ProdukRepository $produkRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->detailTransaksiRepository = $detailTransaksiRepository;
        $this->produkRepository = $produkRepository;

        $this->middleware('auth');
        $this->middleware('petani');
    }    

    public function index()
    {
        return view('petani.home');
    }

    public function demand($date)
    {
        
        $data = [
            'demand' => "",
            'selected' => $date,
            'tanggal' => $transcationDate = $this->transaksiRepository->getTransactionDate(),
        ];
        
        return view('petani.demand')->with('data',$data);
    }

    public function order(){

        $data = [
            'order' => $this->transaksiRepository->findByIdUser(Auth::user()->id)
        ];

        return view('petani.order')->with('data',$data);
    }

    public function orderDetail($id){
        return "order:".$id;
    }

    public function stok(){

        $barang = $this->produkRepository->findByUserId(Auth::user()->id);
        
        $data = [
            'barang' => $barang
        ];
        return view('petani.stok')->with('data',$data);
    }

    public function _stok(Request $request){


        $size = count($request->input());
        for ($i=0; $i < $size; $i++) { 

            $index_id_barang = 'id_barang_'.$i;
            $index_ketersediaan = 'ketersediaan_'.$i;

            $id = $request->input($index_id_barang);
            $barang = ['ketersediaan' => $request->input($index_ketersediaan)];

            try {
                DB::beginTransaction();
                $this->produkRepository->update($id,$barang);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
            }
            
        }

        return redirect()->back();
    }

    public function orderKonfirmasi($id){
        $transaksi = $this->transaksiRepository->find($id);
        $data = [
            'order' => $transaksi
        ];
        return view('petani.konfirmasi_order')->with('data',$data);

    }

    public function _orderKonfirmasi(Request $request){
        // dd($request->input());
        $detailTransaksi = $request->input('id_detail_transaksi');
        $volumeKirim = $request->input('volume_kirim');
        $bobotKirim = $request->input('bobot_kirim');
        $selisihKirim=$request->input('selisih_kirim');
        $keterangan = $request->input('keterangan');
        // dd($keterangan);
        try {
            DB::beginTransaction();
            $flag = 2;
            $this->transaksiRepository->updateStatusOrderKePetani($request->input('id_transaksi'),$flag);
            $this->transaksiRepository->updateTanggalPengiriman($request->input('id_transaksi'),Carbon::now());
            foreach ($detailTransaksi as $key => $value) {
                $id = $detailTransaksi[$key];
                $value = $volumeKirim[$key];
                $value2=$bobotKirim[$key];
                $value3=$selisihKirim[$key];
                // dd($keterangan[$key]);
                $keterangans = $keterangan[$key];
                $this->detailTransaksiRepository->updatePengirimanPetani($id,$value, $value2,$value3, $keterangans,$flag);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
        
        return redirect('/Petani/Order');
    }
}
