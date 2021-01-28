<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Repositories\TransaksiRepository;
use Carbon\Carbon;

class PengirimanController extends Controller
{
    protected $transaksi;
    public function __construct(TransaksiRepository $transaksi)
    {
        $this->middleware('auth');
        $this->middleware('reseller');

        $this->transaksi = $transaksi;
    }    
    public function show()
    {
        $data = [
            'transaksi' => DB::select('call sp_get_paket_ke_reseller(?)',array(Auth::user()->id)),
        ];
        
        return view('reseller.pengiriman')->with(compact('data'));
    }

    public function konfirmasiSampai($id){
        $confirmReseller=2;
        $this->transaksi->konfirmSampai($confirmReseller, $id);
        return redirect()->back();
    }
    public function konfirmasiDiterima($id){
        $confirmDiterima=7;
        $getHariIni = Carbon::now()->format('yy-m-d')."T".Carbon::now()->format('H:m');
        $this->transaksi->konfirmDiterima($confirmDiterima, $getHariIni, $id);
        return redirect()->back();
    }
    public function detail($id_transaksi){
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
        return view('reseller.pengiriman_detail')->with(compact('data'));
    }
}
