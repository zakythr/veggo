<?php

namespace App\Http\Controllers\reseller;

use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\DetailKeranjangRepository;
use App\Repositories\AlamatRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\KeranjangResellerRepository;
use App\Repositories\ParentKeranjangResellerRepository;
use App\Repositories\UserRepository;
use Uuid;
use DB;

class TransaksiController extends Controller
{
    protected $barang;
    protected $transaksi;
    protected $keranjang;
    protected $detailTransaksi;
    protected $detailKeranjang;
    protected $alamat;
    protected $isiPaket;
    protected $keranjangReseller;
    protected $parentKeranjangReseller;
    protected $user;

    public function __construct(ProdukRepository $barang, TransaksiRepository $transaksi, KeranjangRepository $keranjang, DetailTransaksiRepository $detailTransaksi, DetailKeranjangRepository $detailKeranjang, AlamatRepository $alamat, IsiPaketRepository $isiPaket, KeranjangResellerRepository $keranjangReseller, ParentKeranjangResellerRepository $parentKeranjangReseller, UserRepository $user)
    {
        $this->middleware('auth');
        
        $this->transaksi        = $transaksi;
        $this->detailTransaksi  = $detailTransaksi;
        $this->keranjang        = $keranjang;
        $this->detailKeranjang  = $detailKeranjang;
        $this->alamat           = $alamat;
        $this->barang           = $barang;
        $this->isiPaket         = $isiPaket;
        $this->keranjangReseller = $keranjangReseller;
        $this->parentKeranjangReseller = $parentKeranjangReseller;
        $this->user             = $user;
    }

    public function viewCheckout(Request $request)
    {
        $alamat=$this->alamat->getAllAlamatByUser(Auth::user()->id);
        if(count($alamat)==0){
            return redirect('/Reseller/TambahAlamat/');
        }
        $getBarangs           = $this->keranjangReseller->getTotalById($request->tanggal);
        // dd($getBarang);
        $totalHarga             = 0;
        $detailPaketKeranjang   = array();

        foreach($getBarangs as $detailKeranjang)
        {
            $getBarang                      = $this->barang->findById($detailKeranjang->id_barang);

            if($getBarang->jenis == 'Kemas')
            {
                $detailKeranjang->nama          = $detailKeranjang->bobot_kemasan.' '.'Gram'.' '.$getBarang->nama;
                $detailKeranjang->jenis         = $getBarang->jenis;
                $detailKeranjang->satuan        = $getBarang->satuan;
                $detailKeranjang->bobot         = $getBarang->bobot;
                $detailKeranjang->harga_satuan  = $getBarang->harga_jual;
                $detailKeranjang->volume        = $detailKeranjang->volume * ($getBarang->bobot/10);
                $detailKeranjang->diskon        = $getBarang->diskon;
                $detailKeranjang->jenis_diskon  = $getBarang->jenis_diskon;
                
                $totalHarga = $totalHarga + $detailKeranjang->harga_diskon;
            }
            else if($getBarang->jenis == 'Paket')
            {
                $detailKeranjang->nama          = $getBarang->nama;
                $detailKeranjang->jenis         = $getBarang->jenis;
                $detailKeranjang->satuan        = $getBarang->satuan;
                $detailKeranjang->bobot         = $getBarang->bobot;
                $detailKeranjang->harga_satuan  = $getBarang->harga_jual;
                $detailKeranjang->isPaket       = $getBarang->is_paket;
                $detailKeranjang->diskon        = $getBarang->diskon;
                $detailKeranjang->jenis_diskon  = $getBarang->jenis_diskon;
    
                $getIsiPaket = $this->isiPaket->findByIdBarang($detailKeranjang->id_barang);
                $getIsiPaket = array($getIsiPaket);

                for($a=0;$a<sizeof($getIsiPaket[0]);$a++)
                {
                    $getBarang = $this->barang->findById($getIsiPaket[0][$a]->id_barang);

                    $detailPaketKeranjang[$detailKeranjang->id_barang][$a]['nama']   = $getBarang->nama;
                    $detailPaketKeranjang[$detailKeranjang->id_barang][$a]['volume'] = $getIsiPaket[0][$a]->volume;
                    $detailPaketKeranjang[$detailKeranjang->id_barang][$a]['satuan'] = $getBarang->satuan;
                }

                $totalHarga = $totalHarga + $detailKeranjang->harga_diskon;
            }
            else if($getBarang->jenis == 'Timbang')
            {
                $detailKeranjang->nama          = $getBarang->nama;
                $detailKeranjang->jenis         = $getBarang->jenis;
                $detailKeranjang->satuan        = $getBarang->satuan;
                $detailKeranjang->bobot         = $getBarang->bobot;
                $detailKeranjang->harga_satuan  = $getBarang->harga_jual;
                $detailKeranjang->diskon        = $getBarang->diskon;
                $detailKeranjang->jenis_diskon  = $getBarang->jenis_diskon;

                $totalHarga = $totalHarga + $detailKeranjang->harga_diskon;
            }
        // dd($totalHargaKemas);

        }
        // $totalHarga = $totalHarga + 10000;
        // dd($this->transaksi->checkTransaksi($request->tanggal));
        if($this->transaksi->checkTransaksi($request->tanggal) == null)
        {
            $data = [
                'tanggal_pre_order'     => $request->tanggal,
                'isCheckout'            => 0,
                'total_bayar'           => $totalHarga
            ];

            $this->transaksi->halamanCheckoutReseller($data);
        }

        else
        {
            $data = [
                'tanggal_pre_order'     => $request->tanggal,
                'total_bayar'           => $totalHarga
            ];

            $this->transaksi->updateHalamanCheckout($data);
        }

        $getAlamat = $this->alamat->getAllAlamatByUser(Auth::user()->id);

        $data = [
            'keranjang'              => $this->parentKeranjangReseller->getKeteranganKeranjang($request->tanggal),
            'detail_keranjang'       => $getBarangs,
            'total'                  => $totalHarga,
            'detail_paket_keranjang' => $detailPaketKeranjang,
            'alamat'                 => $getAlamat
        ];

        // dd($request->tanggal);

        return view('reseller.checkout')->with(compact('data'));
    }

    public function submitCheckout(Request $request)
    {
        // dd($request->alamat);
        // $getKeranjang           = $this->keranjang->getIDKeranjang();
        
        // $getDetailKeranjang     = $this->detailKeranjang->getDetailKeranjang($getKeranjang);
        $getBarangs           = $this->keranjangReseller->getTotalById($request->tanggal);
        $getTransaksi           = $this->transaksi->checkTransaksi($request->tanggal);
        $nomor=$getTransaksi->nomor_invoice;
        
        foreach($getBarangs as $detail)
        {
            // dd($detail->bobot_kemasan);
            $data = [                
                'id_barang'     => $detail->id_barang,
                'harga'         => $detail->harga,
                'harga_diskon'  => $detail->harga_diskon,
                'volume'        => $detail->volume,
                'id_transaksi'  => $getTransaksi->id,
                'bobot_kemasan' => $detail->bobot_kemasan
            ];

            $this->detailTransaksi->inputDetailTransaksi($data);
        }
        
        
        $data = [
            'keterangan' => $request->keterangan,
            'id_alamat'  => $request->alamat
        ];

        $this->transaksi->purchaseCheckout($data, $request->tanggal);
        
        // $this->keranjang->hapusKeranjang($getKeranjang);
        // $this->detailKeranjang->hapusDetailKeranjangByIdKeranjang($getKeranjang);
        $this->parentKeranjangReseller->settingFlag(1, $getTransaksi->id, $request->tanggal);

        return view('reseller.transaksi-sukses')->with(compact('nomor'));
    }

    public function showTransaksi($tipe){
        
        $alltype=[
            [
                'status'=>"BelumDibayar",
                'status2' => "Belum Dibayar"
            ],
            [
                'status'=>"Proses",
                'status2' => "Dalam Proses"
            ],
            [
                'status'=>"Selesai",
                'status2' => "Selesai"
            ],
            [
                'status'=>"Dibatalkan",
                'status2' => "Dibatalkan"
            ],
        ];
        if($tipe=="BelumDibayar"){
            $tipe2="Belum Dibayar";
            $transaksi = $this->transaksi->getAllTransaksiByUserAndNotPay(Auth::user()->id);
        }
        else if($tipe=="Proses"){
            $tipe2="Dalam Proses";
            $transaksi = $this->transaksi->getAllTransaksiByUserAndInProcess(Auth::user()->id);
        }
        else if($tipe=="Selesai"){
            $tipe2="Selesai";
            $transaksi = $this->transaksi->getAllTransaksiByUserAndIsFinish(Auth::user()->id);
        }
        else if($tipe=="Dibatalkan"){
            $tipe2="Dibatalkan";
            $transaksi = $this->transaksi->getAllTransaksiByUserAndIsCancelled(Auth::user()->id);
        }
        else{
            return redirect('/');
        }
        $data = [
            'alltype'=>$alltype,
            'tipe'=>$tipe,
            'tipe2'=>$tipe2,
            'transaksi'=>$transaksi,
            'rekening'=>$this->user->getVeggo()
        ];

        return view('reseller.transaksi')->with('data',$data);
    }

    public function _filterTanggal(Request $request){
        session(['tanggal_pre_order' => $request->input('tanggal')]);
        return redirect('/reseller/Transaksi');
    }

    public function kirimBukti($id_transaksi,Request $request)
    {
        
        // dd('masuk bang');
        $file = $request->file('foto_bukti');
        // dd($file);
        $filename = Uuid::generate(4)->string.'.'.$file->getClientOriginalExtension();
        $path = public_path().'/img/foto_bukti';
        $file->move($path,$filename);

        $this->transaksi->kirimBukti($id_transaksi, $filename);
        
        return redirect()->back();
            
    }  
    
    public function detailTransaksi($id_transaksi){
        //get id parent dari id transaksi
        $parent=$this->parentKeranjangReseller->getFromIdTransaksi($id_transaksi);
        // dd($parent);
        //get barang dari id parent
        $detail =[ 
            'transaksi'=>$parent
        ];
        // dd($detail);
        return view('reseller.detail-transaksi')
            ->with(compact('detail'));
    }

    public function konfirmasiTransaksi($id_transaksi){
        $this->transaksi->konfirmasiTransaksi($id_transaksi);
        // dd($detail);
        return redirect()->back();
    }
}
