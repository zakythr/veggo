<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TransaksiRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\InventarisRepository;

use DB;



class PengirimanController extends Controller
{

    private $transaksiRepository,
            $userRepository,
            $inventarisRepository,
            $produkRepository,
            $detailTransaksisRepository;

    public function __construct(TransaksiRepository $transaksiRepository, DetailTransaksiRepository $detailTransaksisRepository,UserRepository $userRepository,InventarisRepository $inventarisRepository,ProdukRepository $produkRepository)
    {   
        $this->transaksiRepository = $transaksiRepository;
        $this->detailTransaksisRepository = $detailTransaksisRepository;
        $this->userRepository = $userRepository;
        $this->inventarisRepository = $inventarisRepository;
        $this->produkRepository = $produkRepository;
        $this->middleware('auth');
    }
    
    public function pengiriman($date){
        
        $tipe = "FROM_BUYER";
        $tanggal = $date;
        $status = 5;
        $status2 = 6;

        $resultCount = $this->transaksiRepository->getCountVerif2($status, $status2,$tipe);
        $tanggals=$this->transaksiRepository->getTransactionDateWithStatus2($status, $status2, $tipe);

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
            'preorder' => $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis2($tanggal,$status, $status2,$tipe),
            'filter_tanggal' => $date,
            'tanggal' => $tanggals,
        ];
        // dd($data);
        return view('penjual.pengiriman')->with('data',$data);
    }

    public function tambahPengiriman(){
        $data = [
            'kurir' => $this->userRepository->getKurir(),
            'reseller' => $this->userRepository->getReseller(),
            'current_date' => session('kirim_date'),
            'current_kurir' => session('kirim_kurir'),
            'current_reseller' => session('kirim_reseller'),
            'tanggal' => $this->transaksiRepository->getTransactionDate(),
            'preorder' => $this->transaksiRepository->findByTanggalPreOrderAndStatusAndTipeTransakis(session('kirim_date'),4,"FROM_BUYER"),
        ];
        return view('penjual.tambah_pengiriman')->with('data',$data);
    }

    public function _filterTambahPengiriman(Request $request){
        // dd($request->input());
        session(['kirim_date' => $request->input('tanggal')]);
        session(['kirim_kurir' => $request->input('kurir')]);
        return redirect()->back();

    }
    
    public function _tambahPengiriman(Request $request){
        // dd($request->input());
        $id_kurir = $request->input('current_kurir');
        $id_reseller = $request->input('current_reseller');
        $pengiriman = $request->input('pengiriman');
        
        if($pengiriman==null){
            return redirect()->back();
        }
        else{
        try {
            DB::begintransaction();
            for($i=0;$i<count($pengiriman);$i++){
                $id = $pengiriman[$i];
                $kurir = $id_kurir;
                $reseller = $id_reseller;
                if(strlen($kurir)<30){
                    if($id_reseller==null){
                        $flag = 7;
                    }
                    else{
                        $flag = 6;
                    }
                }
                else{
                    $flag = 5;
                }
                $this->transaksiRepository->updateKurirPengiriman($id,$kurir, $reseller,$flag);
                $transaksi = $this->transaksiRepository->find($id);
                
                $detail = $transaksi->detailTransaksi;
                // dd($detail[2]->barang);
                for($j=0;$j<count($detail);$j++){
                    $barang = $detail[$j];
                    
                    if($barang->is_canceled_by_veggo === 0){
                        $this->kelolaInventaris($barang,$transaksi,$id);
                        $this->detailTransaksisRepository->updateByIdTransaksi($transaksi->id,['status' => 5]);
                    }
                }            
            }
            
            DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            dd($th);
        }}
    }    

    private function kelolaInventaris($barang,$transaksi,$id){
        $keterangan = "PENGIRIMAN KE PEMBELI OLEH ".$transaksi->id_kurir;
        
        if($barang->barang->jenis == "Timbang" && $barang->barang->is_paket == 0){
            $volume = (int) $barang->volume_kirim_kurir;
            $this->inventarisRepository->inventarisOut($barang->id_barang,$id,$volume,$keterangan);
            $this->produkRepository->removeStok($barang->id_barang,$volume);

        }else if($barang->barang->is_paket == 1){
            $volume = (int) $barang->volume_kirim_kurir;
            $isipaket = $barang->barang->isiPaket;
            // $this->inventarisRepository->inventarisOut($barang->id_barang,$id,$volume,$keterangan);
            // $this->produkRepository->removeStok($barang->id_barang,$volume);            
            for($i=0;$i<count($isipaket);$i++){
                $isi = $isipaket[$i];
                $this->inventarisRepository->inventarisOut($isi->id_barang,$id,$isi->volume,$keterangan);
                $this->produkRepository->removeStok($isi->id_barang,(int) $isi->volume);
            }
        }else if($barang->barang->jenis == "Kemas" && $barang->barang->is_paket == 0){
            $volume = (int) ($barang->volume_kirim_kurir * $barang->bobot_kemasan);
            $this->inventarisRepository->inventarisOut($barang->id_barang,$id,$volume,$keterangan);
            $this->produkRepository->removeStok($barang->id_barang,$volume);

        }else{
            dd("error");
        }
    }

    public function finalisasi(){

    }    

    public function finalisasiPengiriman($id){
        
        $data = [
            'produk' => $this->produkRepository->all(),
            'transaksi' => $this->transaksiRepository->find($id)
        ];

        return view('penjual.finalisasi_pengiriman')->with('data',$data);

    }
    
    public function _finalisasiPengiriman(Request $request){
        // dd($request->input());
        $idDetailtransaksi = $request->input('id_detail_transaksi');
        $volumeKirimKurir = $request->input('volume_kirim_kurir');
        $bobotKirimKurir = $request->input('bobot_kirim_kurir');
        $harga_akhir = $request->input('harga_akhir');
        $idTransaksi = $request->input('id_transaksi');
        $keterangan = $request->input('keterangan');
        $harga_akhir_diskon=$request->input('harga_akhir_diskon');
        $total_harga_akhir=$request->input('total_harga');
        $ongkir=$request->input('ongkir');

        // $transaksi = $this->transaksiRepository->find($idTransaksi);
            
        // $detail = $transaksi->detailTransaksi;
        // dd($detail);

        try {
            DB::begintransaction();
            $this->transaksiRepository->update($idTransaksi, ['keterangan' => $keterangan, 'total_bayar_akhir' => $total_harga_akhir, 'ongkir'=>$ongkir]);
            $this->transaksiRepository->updateStatusOrderKePetani($idTransaksi,4);
            for($i=0;$i<count($idDetailtransaksi);$i++){
                $id = $idDetailtransaksi[$i];
                $volume = $volumeKirimKurir[$i];
                $bobot = $bobotKirimKurir[$i];
                $harga = $harga_akhir[$i];
                $harga_diskon=$harga_akhir_diskon[$i];
                $this->detailTransaksisRepository->updateFinalisasiPengiriman($id,$volume, $bobot,$harga, $harga_diskon);
            }

            //dari siap kirim
            $this->transaksiRepository->updateKurirPengiriman($idTransaksi,"id_kurir", null,7);
            $transaksi = $this->transaksiRepository->find($idTransaksi);
            
            $detail = $transaksi->detailTransaksi;
            // dd($detail[2]->barang);
            for($j=0;$j<count($detail);$j++){
                $barang = $detail[$j];
                // dd($barang->is_canceled_by_veggo);
                
                if($barang->is_canceled_by_veggo == 0){
                    $this->kelolaInventaris($barang,$transaksi,$id);
                    $this->detailTransaksisRepository->updateByIdTransaksi($transaksi->id,['status' => 5]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }
        
        return redirect('Penjual/PreOrder/Selesai/Tanggal/'.date("Y-m-d"));
    }    

    public function _tambahItemFinalisasiPengiriman(Request $request){
        // dd($request->input());
        $id_transaksi=$request->input('id_transaksi');
        $id_barang=$request->input('id_barang');
        $jumlah_kirim=$request->input('volume_kirim_kurir');
        $harga=$request->input('harga_akhir');

        $this->detailTransaksisRepository->addFinalisasiItem($id_transaksi,$id_barang,$jumlah_kirim,$harga);
        $url = "/Penjual/Pengiriman/Finalisasi/".$id_transaksi;
        return redirect($url);
    }

    public function _hapusItemFinalisasiPengiriman(Request $request){
        $this->detailTransaksisRepository->removeFinalisasiItem($request->input('id_detail_transaksi'));
    }

    

}
