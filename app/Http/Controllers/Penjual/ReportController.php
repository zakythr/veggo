<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;

use Illuminate\Support\Facades\Storage;
use Response;
use DB;


class ReportController extends Controller
{

    protected $transaksiRepository;

    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        
        $this->middleware('auth');
        $this->middleware('penjual');

    }    

    public function reportHarian($date){
        $getPemasukan=DB::select('call sp_get_pemasukan_pertgl(?)',array($date));
        $getPengeluaran=DB::select('call sp_get_pengeluaran_pertgl(?)',array($date));
        $getPemasukanTotal=$this->transaksiRepository->getPemasukanTotalHarian($date);
        $getPengeluaranTotal=DB::select('call sp_get_total_bayar_veggo_by_petani(?)',array($date)); 
        // dd($getPengeluaranTotal);
        $pemasukanTotalInt=(int)$getPemasukanTotal->pemasukan;
        if(count($getPengeluaranTotal)>0){
            $pengeluaranTotalInt=(int)$getPengeluaranTotal[0]->harga;
        }
        else{
            $pengeluaranTotalInt=0;
        }
        
        // dd($pengeluaranTotalInt);
        $labaTotal=$pemasukanTotalInt-$pengeluaranTotalInt;
        $hasil=array();
        $hasil2=array();
        $flag=0;

        foreach($getPemasukan as $key => $masuk){
            $hasil[$key]['nama']= $masuk->nama;
            $hasil[$key]['pemasukan']= $masuk->pemasukan;
            $hasil[$key]['pengeluaran']= 0;
            $hasil[$key]['laba']= $hasil[$key]['pemasukan'] - $hasil[$key]['pengeluaran'] ;
        }
        foreach($getPengeluaran as $key => $keluar){
            foreach($hasil as $key=> $hasill){
                if($hasil[$key]['nama']==$keluar->nama){
                    $hasil[$key]['pengeluaran']=$keluar->pengeluaran;
                    $hasil[$key]['laba']=$hasil[$key]['pemasukan']-$hasil[$key]['pengeluaran'];
                    $flag=1;
                    break;
                }
            }
            if($flag==0){
                $hasil2[$key]['nama']= $keluar->nama;
                $hasil2[$key]['pemasukan']= 0;
                $hasil2[$key]['pengeluaran']= $keluar->pengeluaran;
                $hasil2[$key]['laba']=$hasil2[$key]['pemasukan']-$hasil2[$key]['pengeluaran'];
            } 
            $flag=0; 
        }
        $data=
        [
            'hasil'=>array_merge($hasil, $hasil2),
            'tanggal'=>$this->transaksiRepository->getTgl(),
            'filter_tanggal' =>$date,
            'pengeluaranTotal'=>$pengeluaranTotalInt,
            'pemasukanTotal'=>$pemasukanTotalInt,
            'labaTotal'=>$labaTotal
        ];
        // dd($data['hasil'][0]['nama']);
        // $produk=;

        return view('penjual.report_harian')->with(compact('data'));
    }

    public function reportBulanan($bulan, $tahun){
        $getPemasukan=DB::select('call sp_get_pemasukan_perbln(?, ?)',array($bulan, $tahun));
        $getPengeluaran=DB::select('call sp_get_pengeluaran_perbln(?, ?)',array($bulan, $tahun));
        $getPemasukanTotal=$this->transaksiRepository->getPemasukanTotalBulanan($bulan, $tahun);
        // dd($getPemasukanTotal);
        $getPengeluaranTotal=DB::select('call sp_get_pengeluaran_total_perbln(?, ?)',array($bulan, $tahun)); 
        // dd($getPemasukanTotal->pemasukan);
        // dd($getPengeluaranTotal);
        $pemasukanTotalInt=(int)$getPemasukanTotal->pemasukan;
        $pengeluaranTotalInt=(int)$getPengeluaranTotal[0]->pengeluaran;
        // dd($pengeluaranTotalInt);
        $labaTotal=$pemasukanTotalInt-$pengeluaranTotalInt;
        $hasil=array();
        $hasil2=array();
        $flag=0;

        foreach($getPemasukan as $key => $masuk){
            $hasil[$key]['nama']= $masuk->nama;
            $hasil[$key]['pemasukan']= $masuk->pemasukan;
            $hasil[$key]['pengeluaran']= 0;
            $hasil[$key]['laba']= $hasil[$key]['pemasukan'] - $hasil[$key]['pengeluaran'] ;
        }
        foreach($getPengeluaran as $key => $keluar){
            foreach($hasil as $key=> $hasill){
                if($hasil[$key]['nama']==$keluar->nama){
                    $hasil[$key]['pengeluaran']=$keluar->pengeluaran;
                    $hasil[$key]['laba']=$hasil[$key]['pemasukan']-$hasil[$key]['pengeluaran'];
                    $flag=1;
                    break;
                }
            }
            if($flag==0){
                $hasil2[$key]['nama']= $keluar->nama;
                $hasil2[$key]['pemasukan']= 0;
                $hasil2[$key]['pengeluaran']= $keluar->pengeluaran;
                $hasil2[$key]['laba']=$hasil2[$key]['pemasukan']-$hasil2[$key]['pengeluaran'];
            } 
            $flag=0; 
        }
        $data=
        [
            'hasil'=>array_merge($hasil, $hasil2),
            'tanggal'=>$this->transaksiRepository->getBulanTahun(),
            'bulan' =>date('F', mktime(0, 0, 0, $bulan, 10)),
            'bulanint'=>$bulan,
            'tahun'=>$tahun,
            'pengeluaranTotal'=>$pengeluaranTotalInt,
            'pemasukanTotal'=>$pemasukanTotalInt,
            'labaTotal'=>$labaTotal
        ];
        foreach($data['tanggal'] as $tanggal){
            // dd($tanggal->bulan);
            $tanggal->bulanNama = date('F', mktime(0, 0, 0, $tanggal->bulan, 10)); 
        }
        
        // dd($data['tanggal']);

        return view('penjual.report_bulanan')->with('data',$data);
    }

    public function reportReseller($date){
        $reseller=$this->transaksiRepository->getResellerBydate($date);
        $data=[
            'tanggal'=>$this->transaksiRepository->getTgl(),
            'reseller'=>$reseller,
            'filter_tanggal'=> $date
        ];

        // dd($data['reseller']);

        return  view('penjual.report_reseller')->with('data', $data);
        
    }

    public function reportResellerDetail($date, $id){
        $kemas=DB::select('call sp_get_report_reseller_by_date_and_kemas(?, ?)',array($date, $id));
        $paket=DB::select('call sp_get_report_reseller_by_date_and_paket(?,?)',array($date, $id));
        $timbang=DB::select('call sp_get_report_reseller_by_date_and_timbang(?,?)',array($date, $id));
        $total=DB::select('call sp_get_report_reseller_total(?,?)',array($date, $id));

        $data=[
            'kemas'=>$kemas,
            'timbang'=>$timbang,
            'paket'=>$paket,
            'total'=>$total,
        ];

        return view('penjual.report_reseller_detail')->with('data', $data);
        
    }
    
}
