<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProdukRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\EtalaseRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\BarangKemasanRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\TanggalRepository;
use App\Repositories\AlamatRepository;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use DB;

class EtalaseController extends Controller
{
    protected $produkRepository, 
              $kategoriRepository, 
              $baseKategoriRepository, 
              $etalaseRepository, 
              $barangKemasanRepository,
              $isiPaketRepository,
              $keranjangRepository;

    public function __construct(BarangKemasanRepository $barangKemasanRepository, ProdukRepository $produkRepository, KategoriRepository $kategoriRepository, BaseKategoriRepository $baseKategoriRepository, EtalaseRepository $etalaseRepository, KeranjangRepository $keranjangRepository, IsiPaketRepository $isiPaketRepository, TanggalRepository $tanggal, AlamatRepository $alamat)
    {
        $this->produkRepository = $produkRepository;
        $this->kategoriRepository = $kategoriRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->etalaseRepository = $etalaseRepository;
        $this->keranjangRepository = $keranjangRepository;
        $this->barangKemasanRepository = $barangKemasanRepository;
        $this->isiPaketRepository = $isiPaketRepository;
        $this->tanggal = $tanggal;
        $this->alamat = $alamat;

        $this->middleware('auth');
    }        

    public function etalase()
    {
        return redirect('/Reseller/Etalase/'.date('Y-m-d'));
    }

    public function etalaseDate($date)
    {
        $tanggal= Carbon::now()->format('Y-m-d');
        $hapus=$this->tanggal->hapusTanggal($tanggal);
        $getAlamat = $this->alamat->getAllAlamatByUser(Auth::user()->id);
        $data = [
            'barang' => DB::select('call sp_get_barang_by_tanggal(?)',array($date)),
            'barang_paket' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Paket')),
            'barang_timbang' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Timbang')),
            'barang_kemas' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Kemas')),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'alamat'                 => $getAlamat,
            'tanggal'=>$date,
        ];
        
        return view('reseller.etalase')
            ->with(compact('data'));
    }

    public function getTanggalPengiriman()
    {       

        $getListHari=$this->tanggal->get_tanggal();
        // dd($getListHari);
        if(count($getListHari) > 0){
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

        

        

        foreach ($finalListHari as $key => $value) 
        {
            $finalListHari[$key] = (object) $value;
        }

        // dd($finalListHari);
    
        return $finalListHari;
    }

    public function cariEtalase($nama)
    {
        return redirect('/Reseller/Etalase/CariProduk/'.$nama.'/'.date('Y-m-d'));
        $data = [
            'pencarian_produk' => $nama,
            'barang' => $this->produkRepository->findByNamaBarang($nama),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all()
        ];

        return view('reseller.cariproduk')
            ->with(compact('data'));
    }

    public function cariEtalaseDate($nama, $date)
    {
        $data = [
            'pencarian_produk' => $nama,
            'barang' => DB::select('call sp_get_barang_by_tanggal_and_search(?, ?)',array($date, $nama)),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal'=>$date
        ];

        return view('reseller.cariproduk')
            ->with(compact('data'));
    }

    public function detailProduk($id_barang)
    {
        $get_barang = $this->produkRepository->findById($id_barang);
        // dd($get_barang);
        
        if($get_barang->jenis == 'Timbang')
        {
            $barang_kemasan = $this->barangKemasanRepository->findByIdBarang($id_barang);

            $data = [
                'barang' => $get_barang,
                'kategori' => $this->kategoriRepository->array(),
                'baseKategori' => $this->baseKategoriRepository->all()
            ];
            
            return view('reseller.detail-produk-timbang')
                ->with(compact('data'));
        }

        else if($get_barang->jenis == 'Kemas')
        {
            $barang_kemasan = $this->barangKemasanRepository->findByIdBarang($id_barang);

            $data = [
                'barang' => $get_barang,
                'kemasan' => $barang_kemasan,
                'kategori' => $this->kategoriRepository->array(),
                'baseKategori' => $this->baseKategoriRepository->all()
            ];

            return view('reseller.detail-produk-kemas')
                ->with(compact('data'));
        }

        else if($get_barang->jenis == 'Paket')
        {
            $data = [
                'barang' => $get_barang,
                'isi_paket' => $this->isiPaketRepository->read($get_barang->id),
                'kategori' => $this->kategoriRepository->array(),
                'baseKategori' => $this->baseKategoriRepository->all()
            ];
            
            return view('reseller.detail-produk-paket')
                ->with(compact('data'));
        }
    }
}
