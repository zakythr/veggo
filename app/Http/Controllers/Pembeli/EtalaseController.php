<?php

namespace App\Http\Controllers\Pembeli;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProdukRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\EtalaseRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\BarangKemasanRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\BarangTanggalRepository;
use App\Repositories\TanggalRepository;

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
              $keranjangRepository,
              $barangTanggalRepository,
              $tanggal;

    public function __construct(BarangKemasanRepository $barangKemasanRepository, ProdukRepository $produkRepository, KategoriRepository $kategoriRepository, BaseKategoriRepository $baseKategoriRepository, EtalaseRepository $etalaseRepository, KeranjangRepository $keranjangRepository, IsiPaketRepository $isiPaketRepository, BarangTanggalRepository $barangTanggalRepository, TanggalRepository $tanggal)
    {
        $this->produkRepository = $produkRepository;
        $this->kategoriRepository = $kategoriRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->etalaseRepository = $etalaseRepository;
        $this->keranjangRepository = $keranjangRepository;
        $this->barangKemasanRepository = $barangKemasanRepository;
        $this->isiPaketRepository = $isiPaketRepository;
        $this->barangTanggalRepository = $barangTanggalRepository;
        $this->tanggal     = $tanggal;

        $this->middleware('auth');
        $this->middleware('pembeli');
    }        

    public function etalase()
    {
        // $kategori = [
        //     'sayur' => $this->kategoriRepository->kategoriSayur(),
        //     'buah' => $this->kategoriRepository->kategoriBuah(),
        //     'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
        //     'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
        //     'beras' => $this->kategoriRepository->kategoriBeras(),
        //     'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
        //     'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
        //     'lainlain' => $this->kategoriRepository->kategoriLainLain()
        // ];
        // $data = [
        //     'kategori' => $this->kategoriRepository->array(),
        //     'baseKategori' => $this->baseKategoriRepository->all(),
        //     'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            
        // ];

        // return view('pembeli.test')->with(compact('kategori'))->with(compact('data'));
        return redirect('/Pembeli/Etalase/'.date('Y-m-d'));
    }

    public function etalaseByKategori($NamaProduk)
    {
        return redirect('/Pembeli/Etalase/LihatProduks/'.$NamaProduk.'/'.date('Y-m-d'));
    }

    public function etalaseBySubKategori($NamaKategori, $NamaSubKategori)
    {
        // dd("aaa");
        // dd('/Pembeli/Etalase/LihatProduk/'.$NamaKategori.'/'.$NamaSubKategori.'/'.date('Y-m-d'));
        return redirect('/Pembeli/Etalase/LihatProduk/'.$NamaKategori.'/'.$NamaSubKategori.'/'.date('Y-m-d'));
    }

    public function cariEtalase($nama)
    {
        return redirect('/Pembeli/Etalase/CariProduk/'.$nama.'/'.date('Y-m-d'));
        
    }

    public function detailProduk($id_barang)
    {
        $kategori = [
            'sayur' => $this->kategoriRepository->kategoriSayur(),
            'buah' => $this->kategoriRepository->kategoriBuah(),
            'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
            'beras' => $this->kategoriRepository->kategoriBeras(),
            'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
            'lainlain' => $this->kategoriRepository->kategoriLainLain()
        ];

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
            
            return view('pembeli.detail-produk-timbang')
                ->with(compact('data'))
                ->with(compact('kategori'));
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

            return view('pembeli.detail-produk-kemas')
                ->with(compact('data'))
                ->with(compact('kategori'));
        }

        else if($get_barang->jenis == 'Paket')
        {
            $data = [
                'barang' => $get_barang,
                'isi_paket' => $this->isiPaketRepository->read($get_barang->id),
                'kategori' => $this->kategoriRepository->array(),
                'baseKategori' => $this->baseKategoriRepository->all()
            ];
            
            return view('pembeli.detail-produk-paket')
                ->with(compact('data'))
                ->with(compact('kategori'));
        }
    }

    public function etalaseDate($date)
    {
        $tanggal= Carbon::now()->addDays(1)->format('Y-m-d');
        // dd($tanggal);
        $hapus=$this->tanggal->hapusTanggal($tanggal);
        $this->barangTanggalRepository->deleteByTanggalKurang($tanggal);

        $data = [
            'barang' => DB::select('call sp_get_barang_by_tanggal(?)',array($date)),
            'barang_paket' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Paket')),
            'barang_timbang' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Timbang')),
            'barang_kemas' => DB::select('call sp_get_barang_by_tanggal_and_jenis(?, ?)',array($date, 'Kemas')),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal'=>$date
        ];
        
        return view('pembeli.etalase')
            ->with(compact('data'));
    }
    public function etalaseByKategoriDate($NamaProduk, $date)
    {
        // dd($NamaProduk);
        switch($NamaProduk){
            case 'Sayur':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Sayur');
                $subkategori= $this->kategoriRepository->kategoriSayur();
                break;
            case 'Buah':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Buah');
                $subkategori= $this->kategoriRepository->kategoriBuah();
                break;
            case 'Makanan Sehat':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Makanan Sehat');
                $subkategori = $this->kategoriRepository->kategoriMakananSehat();
            
                break;
            case 'Minuman Sehat':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Minuman Sehat');
                $subkategori = $this->kategoriRepository->kategoriMinumanSehat();
            
                break;
            case 'Daging dan Telor':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Daging dan Telor');
                $subkategori = $this->kategoriRepository->kategoriBahanOlahan();
                break;
            case 'Beras':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Beras');
                $subkategori = $this->kategoriRepository->kategoriBeras();
                
                break;
            case 'Berkebun':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Berkebun');
                $subkategori = $this->kategoriRepository->kategoriBerkebun();
                
                break;
            case 'Lain - Lain':
                $baseKategori =  $this->baseKategoriRepository->findByNamaKategori('Lain - Lain');
                $subkategori = $this->kategoriRepository->kategoriLainLain();
                break;
        }
                
        $kategori = [
            'sayur' => $this->kategoriRepository->kategoriSayur(),
            'buah' => $this->kategoriRepository->kategoriBuah(),
            'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
            'beras' => $this->kategoriRepository->kategoriBeras(),
            'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
            'lainlain' => $this->kategoriRepository->kategoriLainLain()
        ];

        $data_barang = [];
        $barang=DB::select('call sp_get_barang_by_tanggal_and_kategori(?, ?)',array($date, $baseKategori->id));
        
        // dd($subkategori);
        foreach($subkategori as $key => $bar){
            $data_barang[$key]['subkategori'] = $bar;
            $data_barang[$key]['barang'] = DB::select('call sp_get_sub_kategor_by_barang_and_kateg(?, ?, ?)',array($baseKategori->id, $bar->id, $date));
            
        }

        // dd($datas);
        
        $data = [
            'barang' => collect($data_barang),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'nama_produk' => $baseKategori->kategori,
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal'=>$date,
            'flag'=>0
        ];

        // dd($data['barang']);
        

        return view('pembeli.etalase-subkategori')
            ->with(compact('data'))
            ->with(compact('kategori'));
    }
    public function etalaseBySubKategoriDate($NamaKategori, $NamaSubKategori, $tanggal)
    {
        $kategori = [
            'sayur' => $this->kategoriRepository->kategoriSayur(),
            'buah' => $this->kategoriRepository->kategoriBuah(),
            'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
            'beras' => $this->kategoriRepository->kategoriBeras(),
            'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
            'lainlain' => $this->kategoriRepository->kategoriLainLain()
        ];

        $getProduk = $this->produkRepository->findByNamaSubKategori($NamaSubKategori, $tanggal);

        foreach ($getProduk as $key => $value) 
        {
            $getProduk[$key] = (object) $value;
        }

        $data = [
            'barang' => $getProduk,
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'nama_subproduk' => $NamaSubKategori,
            'nama_kategori'=>$NamaKategori,
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal'=>$tanggal
        ];

        // dd($data);

        return view('pembeli.etalase-kategori')
            ->with(compact('data'))
            ->with(compact('kategori'));
    }

    public function cariEtalaseDate($nama, $date)
    {
        $kategori = [
            'sayur' => $this->kategoriRepository->kategoriSayur(),
            'buah' => $this->kategoriRepository->kategoriBuah(),
            'makanansehat' => $this->kategoriRepository->kategoriMakananSehat(),
            'minumansehat' => $this->kategoriRepository->kategoriMinumanSehat(),
            'beras' => $this->kategoriRepository->kategoriBeras(),
            'bahanolahan' => $this->kategoriRepository->kategoriBahanOlahan(),
            'berkebun' => $this->kategoriRepository->kategoriBerkebun(),
            'lainlain' => $this->kategoriRepository->kategoriLainLain()
        ];

        $data = [
            'pencarian_produk' => $nama,
            'barang' => DB::select('call sp_get_barang_by_tanggal_and_search(?, ?)',array($date, $nama)),
            'kategori' => $this->kategoriRepository->array(),
            'baseKategori' => $this->baseKategoriRepository->all(),
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal'=>$date
        ];

        return view('pembeli.cariproduk')
            ->with(compact('data'))
            ->with(compact('kategori'));
    }

    public function getTanggalPengiriman()
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
}
