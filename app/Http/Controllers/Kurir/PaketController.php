<?php

namespace App\Http\Controllers\Kurir;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use App\Repositories\UserRepository;
use Uuid;
use DB;
use Auth;

class PaketController extends Controller
{
    protected $transaksiRepository;
    protected $userRepository;

    public function __construct(TransaksiRepository $transaksiRepository, UserRepository $userRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->userRepository = $userRepository;

        $this->middleware('auth');
        $this->middleware('kurir');
    }

    public function PaketYangAkanDikirim()
    {
        // dd(Auth::user()->id);
        $data = [
            'transaksi' => DB::select('call sp_get_paket_akan_dikirim(?)',array(Auth::user()->id)),
        ];
        
        return view('kurir.akandikirim')->with(compact('data'));
    }

    public function AkanDikirim_DetailPaket($id_transaksi)
    {
        $get_detail_barang_paket = DB::select('call sp_get_kurir_detail_barang(?)',array($id_transaksi));
        $isi_paket = array();

        foreach($get_detail_barang_paket as $get_detail)
        {
            if($get_detail->jenis == 'Paket')
            {
                $get_isi_paket = DB::select('call sp_get_isi_paket(?)',array($get_detail->barang_id));
                
                $get_isi_paket = array($get_isi_paket);

                for($a=0;$a<sizeof($get_isi_paket[0]);$a++)
                {
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['nama'] = $get_isi_paket[0][$a]->nama;
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['volume'] = $get_isi_paket[0][$a]->volume;
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['satuan'] = $get_isi_paket[0][$a]->satuan;
                }
            }
        }

        $data = [
            'transaksi' => DB::select('call sp_get_paket_akan_dikirim_by_id_transaksi(?)',array($id_transaksi)), 
            'detail_barang_transaksi' => $get_detail_barang_paket, 
            'isi_paket' => $isi_paket   
        ];

        // dd($data);
        return view('kurir.akandikirim_detail')->with(compact('data'));
    }

    public function KonfirmasiDalamPengiriman($id_transaksi)
    {
        $this->transaksiRepository->updateTransaksi($id_transaksi, 6);

        return redirect('Kurir/Paket/SedangDikirim')
            ->withSuccess('Paket telah masuk ke dalam Proses Pengiriman');
    }

    public function PaketDalamPengiriman()
    {
        
        $getHariIni = Carbon::now()->format('yy-m-d')."T".Carbon::now()->format('H:m');
        $data = [
            'transaksi' => DB::select('call sp_get_paket_sedang_dikirim(?)',array(Auth::user()->id)),
            'hari_ini' => $getHariIni
        ];
        // dd($getHariIni);
        return view('kurir.sedangdikirim')->with(compact('data'));
    }

    public function SedangDikirim_DetailPaket($id_transaksi)
    {
        $get_detail_barang_paket = DB::select('call sp_get_kurir_detail_barang(?)',array($id_transaksi));
        $isi_paket = array();

        foreach($get_detail_barang_paket as $get_detail)
        {
            if($get_detail->jenis == 'Paket')
            {
                $get_isi_paket = DB::select('call sp_get_isi_paket(?)',array($get_detail->barang_id));
                
                $get_isi_paket = array($get_isi_paket);

                for($a=0;$a<sizeof($get_isi_paket[0]);$a++)
                {
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['nama'] = $get_isi_paket[0][$a]->nama;
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['volume'] = $get_isi_paket[0][$a]->volume;
                    $isi_paket[$get_detail->detail_transaksi_id][$a]['satuan'] = $get_isi_paket[0][$a]->satuan;
                }
            }
        }

        $data = [
            'transaksi' => DB::select('call sp_get_paket_akan_dikirim_by_id_transaksi(?)',array($id_transaksi)), 
            'detail_barang_transaksi' => $get_detail_barang_paket, 
            'isi_paket' => $isi_paket
        ];

        // dd($data);
        return view('kurir.sedangdikirim_detail')->with(compact('data'));
    }

    public function KonfirmasiSelesaiDikirim($id_transaksi,Request $request)
    {
        // dd('masuk bang');
        // dd($request->foto_penerima);
        if($request->hasFile('foto_penerima'))
        {
            // dd('masuk bang');
            $file = $request->file('foto_penerima');
            // dd($file);
            $filename = Uuid::generate(4)->string.'.'.$file->getClientOriginalExtension();
            $path = public_path().'/img/foto_penerima';
            $file->move($path,$filename);
        }

        else
        {
            // dd('gamasuk bang');
            $filename = null;
        }

        $namaPenerima = $request->nama_penerima;
        $keteranganPenerima = $request->keterangan_penerima;
        $tanggalTerima=$request->tanggal_terima;

        $this->transaksiRepository->konfirmasiDiterima($id_transaksi, $namaPenerima, $filename, $keteranganPenerima, $tanggalTerima);
        
        return redirect('Kurir/Paket/SelesaiDikirim')
            ->withSuccess('Paket telah Selesai Dikirim');
    }

    public function PaketSelesaiDikirim()
    {
        $data = [
            'transaksi' => DB::select('call sp_get_paket_selesai_dikirim(?)',array(Auth::user()->id)),
        ];
        return view('kurir.selesaidikirim')->with(compact('data'));
    }
}
