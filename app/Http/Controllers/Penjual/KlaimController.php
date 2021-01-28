<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Repositories\TransaksiRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\KlaimRepository;
use App\Repositories\DetailKlaimRepository;
use Illuminate\Http\Request;
use \stdClass;
use DB;
use Auth;
use Uuid;

class KlaimController extends Controller
{
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
    }

    public function klaim(){
        $data = [
            'klaim' => $this->klaimRepository->all()
        ];

        // dd($data['klaim']->dari);

        return view('penjual.klaim')->with('data',$data);
        
    }
    
    public function detailKlaim($id){
        $data = [
            'klaim' => $this->detailKlaimRepository->getDetailKlaimById($id),
            'klaims' => $this->klaimRepository->getByIdTrx($id)
        ];
        // dd($data['klaims']);

        return view('penjual.ubah_klaim')->with('data', $data);
    }

    public function tambahKlaim(){
        
        $nomor_invoice = $this->transaksiRepository->getTransaksiSudahKirim();
        // dd($nomor_invoice[0]->nomor_invoice);

        if(session('filter_tambah_klaim_invoice')){
            $id = session('filter_tambah_klaim_invoice');
        }else{
            $id = $nomor_invoice[0]->nomor_invoice;
        }

        $transaksi = $this->transaksiRepository->findByNomorInvoice($id);
        $detailTransaksi = $transaksi->detailTransaksi;
        
        foreach ($detailTransaksi as $key => $value) {
            $detailTransaksi[$key]->nama_barang = $value->barang->nama;
        }

        $data = [
            'list_invoice' => $nomor_invoice,
            'transaksi' => $transaksi,
            'detailTransaksi_json' => json_encode($detailTransaksi),
            'detailTransaksi' => $detailTransaksi
        ];        

        // dd($data);
        return view('penjual.tambah_klaim')->with('data',$data);
    }    

    public function _filterTambahKlaim(Request $request){
        
        session(['filter_tambah_klaim_invoice' => $request->input('nomor_invoice')]);
        return redirect('/Penjual/Klaim/Tambah');

    }

    public function _tambahKlaim(){

    }    

    public function _ubahKlaim(){

    }    

    public function klaimOrderPetani($id){
        $transaksi = $this->transaksiRepository->findByNomorInvoice($id);
        $detailTransaksi = $transaksi->detailTransaksi;
        
        foreach ($detailTransaksi as $key => $value) {
            $detailTransaksi[$key]->nama_barang = $value->barang->nama;
        }

        $data = [
            'transaksi' => $transaksi,
            'detailTransaksi_json' => json_encode($detailTransaksi),
            'detailTransaksi' => $detailTransaksi
        ];
        
        // dd($data['detailTransaksi']);
        return view('penjual.tambah_klaim_orderpetani')->with('data',$data);

    }    

    public function _klaimOrderPetani(Request $request){
        // dd($request->input('nama'));
        $klaim = new stdClass();
        $arr = array();
        for($i=0;$i<count($request->input('nama'));$i++){
            $klaim->nama = $request->input('nama')[$i];
            $klaim->volume = $request->input('volume')[$i];
            $klaim->keterangan = $request->input('keterangan')[$i];
            $klaim->foto_bukti = $request->file('bukti')[$i];
            $klaim->id_detail_transaksi=$request->input('id_detail_transaksi')[$i];

            // $arr[$i]=$klaim;
            // dd($arr[0]); 
            array_push($arr,$klaim);
            $klaim = clone $klaim;
        }
        // dd($arr);    

        $data = [
            'id_transaksi' => $request->input('id_transaksi'),
            'klaim_from' => Auth::user()->id,
            'klaim_to' => $request->input('id_petani'),
            'tanggal_kirim' => $request->input('tanggal_kirim'),
            'status' => 1,
        ];

        
        try {
            //code...
            DB::begintransaction();            
            $result = $this->klaimRepository->create($data);
            foreach($arr as $detail)
            {
                $filename = Uuid::generate(4)->string.'.'.$detail->foto_bukti->getClientOriginalExtension();
                $path = public_path().'/img/bukti_klaim';
                $detail->foto_bukti->move($path,$filename);
                $detailKlaim = [
                    'id_klaim' => $result->id,
                    'id_barang' => $detail->nama,
                    'id_detail_transaksi' =>$detail->id_detail_transaksi,
                    'volume_klaim' => $detail->volume,
                    'keterangan' => $detail->keterangan,
                    'foto_bukti' => $filename,
                ];
                $this->detailKlaimRepository->create($detailKlaim);
            }
            DB::commit();
            
            return redirect('/Penjual/OrderPetani/Konfirmasi/Terima/'.$request->nomor_invoice);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            DB::rollback();
        }
        

        
        
    }   

}
