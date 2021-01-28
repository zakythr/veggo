<?php

namespace App\Http\Controllers\Reseller;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BarangKemasanRepository;
use App\Repositories\KeranjangRepository;
use App\Repositories\DetailKeranjangRepository;
use App\Repositories\BobotKemasanRepository;
use App\Repositories\ResepRepository;
use App\Repositories\IsiResepRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\HariPengirimanRepository;
use App\Repositories\TanggalRepository;
use App\Repositories\KeranjangResellerRepository;
use App\Repositories\ParentKeranjangResellerRepository;


use App\Models\Detail_keranjang as DetailKeranjang;


class KeranjangController extends Controller
{
    protected $keranjang;
    protected $detailKeranjang;
    protected $bobotKemasan;
    protected $resep;
    protected $isiResep;
    protected $barang;
    protected $isiPaket;
    protected $hariPengiriman;
    protected $barangKemasan;
    protected $tanggal;
    protected $keranjangReseller;
    protected $parentKeranjangReseller;

    public function __construct(BarangKemasanRepository $barangKemasan, HariPengirimanRepository $hariPengiriman ,KeranjangRepository $keranjang, DetailKeranjangRepository $detailKeranjang, BobotKemasanRepository $bobotKemasan, ResepRepository $resep, IsiResepRepository $isiResep, ProdukRepository $barang, IsiPaketRepository $isiPaket, TanggalRepository $tanggal, KeranjangResellerRepository $keranjangReseller, ParentKeranjangResellerRepository $parentKeranjangReseller)
    {
        $this->middleware('auth');

        $this->keranjang         = $keranjang;
        $this->detailKeranjang   = $detailKeranjang;
        $this->bobotKemasan      = $bobotKemasan;
        $this->resep             = $resep;
        $this->isiResep          = $isiResep;
        $this->barang            = $barang;
        $this->isiPaket          = $isiPaket;
        $this->hariPengiriman    = $hariPengiriman;
        $this->barangKemasan     = $barangKemasan;
        $this->tanggal           = $tanggal;
        $this->keranjangReseller = $keranjangReseller;
        $this->parentKeranjangReseller = $parentKeranjangReseller;
    }

    public function tambahItemKeranjang($idKeranjang,Request $request)
    {
        // dd($request->all());
        try
        {
            DB::beginTransaction();

            $this->detailKeranjang->tambahDetailKeranjang($request->all());

            DB::commit();

            return 'ok';
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return 'failed';
        }
    }

    public function ubahItemKeranjang($id, Request $request)
    {
        try
        {
            DB::beginTransaction();

            $this->detailKeranjang->updateDetailKeranjang($id, $request->all());

            DB::commit();

            return 1;
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return 0;
        }
    }

    public function hapusItemKeranjang($id)
    {
        try
        {
            DB::beginTransaction();

            $this->detailKeranjang->hapusDetailKeranjang($id);

            DB::commit();

            return 'ok';
        }
        catch (\Throwable $th)
        {
            DB::rollback();

            return 'failed';
        }
    }

    public function getBobotKemasan()
    {
        return $this->bobotKemasan->all();
    }

    public function showInputItemKeranjang($id)
    {
        $cekBarang          = $this->barang->findById($id);

        if($cekBarang->jenis == 'Kemas')
        {

            $data = [
                'barang' => $cekBarang,
                // 'bobot_kemasan' => $this->bobotKemasan->all()
                'bobot_kemasan' => $this->barangKemasan->findByIdBarang($id)
            ];

            return view('reseller.components.input-item-kemas')
                ->with(compact('data'))
                ->render();
        }
        else if($cekBarang->jenis == 'Paket')
        {
            $getIsiPaket = $this->isiPaket->findByIdBarang($id);

            foreach($getIsiPaket as $isiPaket)
            {
                $getBarang             = $this->barang->findById($isiPaket->id_barang);
                
                $isiPaket->nama_barang = $getBarang->nama;
                $isiPaket->satuan      = $getBarang->satuan;
            }

            $data = [
                'barang'    => $cekBarang,
                'isiPaket'  => $getIsiPaket
            ];

            return view('reseller.components.input-item-paket')
                ->with(compact('data'))
                ->render();
        }
        else if($cekBarang->jenis == 'Timbang')
        {
            $data = [
                'barang' => $cekBarang,
            ];

            return view('reseller.components.input-item-timbang')
                ->with(compact('data'))
                ->render();   
        }
    }

    public function showUbahItemKeranjang($id)
    {

        $getDetailKeranjang = $this->detailKeranjang->findById($id);
        $cekBarang = $this->barang->findById($getDetailKeranjang->id_barang);

        if($cekBarang->jenis == 'Kemas')
        {

            $data = [
                'barang' => $cekBarang,
                'bobot_kemasan' => $this->barangKemasan->findByIdBarang($getDetailKeranjang->id_barang),
                'isi_keranjang' => $getDetailKeranjang
            ];

            return view('reseller.components.ubah-item-kemas')
                ->with(compact('data'))
                ->render();
        }
        else if($cekBarang->jenis == 'Paket')
        {
            $getIsiPaket = $this->isiPaket->findByIdBarang($getDetailKeranjang->id_barang);

            foreach($getIsiPaket as $isiPaket)
            {
                $getBarang             = $this->barang->findById($isiPaket->id_barang);
                
                $isiPaket->nama_barang = $getBarang->nama;
                $isiPaket->satuan      = $getBarang->satuan;
            }

            $data = [
                'barang'    => $cekBarang,
                'isiPaket'  => $getIsiPaket,
                'isi_keranjang' => $getDetailKeranjang
            ];

            return view('reseller.components.ubah-item-paket')
                ->with(compact('data'))
                ->render();
        }
        else if($cekBarang->jenis == 'Timbang')
        {
            $data = [
                'barang' => $cekBarang,
                'isi_keranjang' => $getDetailKeranjang
            ];

            return view('reseller.components.ubah-item-timbang')
                ->with(compact('data'))
                ->render();   
        }
    }

    public function submitInputItemKeranjang($id, Request $request)
    {
        // dd($this->detailKeranjang->findByIdBarangBobotKemasan($id, (int) $request->volume_order));

        // if($this->barang->findById($id)->diskon>100){
        //     $harga_diskon=$this->barang->findById($id)->harga_jual * $request->total_order;
        // }
        

        try
        {
            DB::beginTransaction();   

            if($this->keranjang->findKeranjang($request->tanggal) == null)
            {
                $this->keranjang->tambahKeranjang($request->tanggal);
            }
            
            $getIdKeranjang = $this->keranjang->getIDKeranjang($request->tanggal);

            switch($this->barang->findById($id)->jenis) 
            {
                case 'Paket':
                    if($this->barang->findById($id)->diskon>100){
                        $harga_diskon=($this->barang->findById($id)->harga_jual-$this->barang->findById($id)->diskon) * $request->total_order;
                    }
                    else{
                        $harga_diskon=($this->barang->findById($id)->harga_jual-($this->barang->findById($id)->harga_jual*($this->barang->findById($id)->diskon/100))) * $request->total_order;
                    }
                    if($this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id) == null)
                    {
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'harga'        => $this->barang->findById($id)->harga_jual * $request->total_order,
                            'harga_diskon' => $harga_diskon
                        ];
            
                        $this->detailKeranjang->tambahDetailKeranjang($data);   

                        DB::commit();
                        return 1;
                    }
                    else
                    {
                        $currentVolume = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->volume;
                        $currentHarga  = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->harga;
                        $currentHargaDiskon  = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->harga_diskon;

                        $data = [
                            'volume'       => $request->total_order + $currentVolume,
                            'harga'        => ($this->barang->findById($id)->harga_jual * $request->total_order) + $currentHarga,
                            'harga_diskon' => $harga_diskon+$currentHargaDiskon,
                        ];

                        $this->detailKeranjang->updateDetailKeranjang($id, $data);

                        DB::commit();
                        return 1;
                    }

                case 'Timbang':
                    if($this->barang->findById($id)->diskon>100){
                        $harga_diskon=(($this->barang->findById($id)->harga_jual/10)-($this->barang->findById($id)->diskon/10)) * ($request->total_order/100);
                    }
                    else{
                        $harga_diskon=(($this->barang->findById($id)->harga_jual/10)-(($this->barang->findById($id)->harga_jual/10)*($this->barang->findById($id)->diskon/100))) * (($request->total_order)/100);
                    }

                    if($this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id) == null)
                    {
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'harga'        => ($this->barang->findById($id)->harga_jual/10) * (($request->total_order)/100),
                            'harga_diskon' => $harga_diskon
                        ];

                        $this->detailKeranjang->tambahDetailKeranjang($data);   

                        DB::commit();
                        return 1;
                    }
                    else
                    {
                        $currentVolume = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->volume;
                        $currentHarga  = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->harga;
                        $currentHargaDiskon  = $this->detailKeranjang->findByIdKeranjangIdBarang($getIdKeranjang,$id)->harga_diskon;

                        $data = [
                            'volume'       => $request->total_order + $currentVolume,
                            'harga'        => ($this->barang->findById($id)->harga_jual/10) * (($request->total_order)/100) + $currentHarga,
                            'harga_diskon' => $harga_diskon + $currentHargaDiskon
                        ];
        
                        $this->detailKeranjang->updateDetailKeranjang($id, $data);

                        DB::commit();
                        return 1;
                    }

                case 'Kemas':
                    if($this->barang->findById($id)->diskon>100){
                        $harga_diskon=(($this->barang->findById($id)->harga_jual/10)-($this->barang->findById($id)->diskon/10)) * (($request->total_order*$request->volume_order)/100);
                    }
                    else{
                        $harga_diskon=(($this->barang->findById($id)->harga_jual/10)-(($this->barang->findById($id)->harga_jual/10)*($this->barang->findById($id)->diskon/100))) * (($request->total_order*$request->volume_order)/100);
                    }
                    if($this->detailKeranjang->findByIdBarangBobotKemasan($getIdKeranjang, $id, (int) $request->volume_order) == null)
                    {
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'bobot_kemasan'=> $request->volume_order,
                            'harga'        => ($this->barang->findById($id)->harga_jual/10) * (($request->total_order*$request->volume_order)/100),
                            'harga_diskon' => $harga_diskon
                        ];

                        $this->detailKeranjang->tambahDetailKeranjang($data);
                        
                        DB::commit();
                        return 1;
                    }
                    else
                    {
                        $currentVolume = $this->detailKeranjang->findByIdBarangBobotKemasan($getIdKeranjang,$id,(int) $request->volume_order)->volume;
                        $currentHarga  = $this->detailKeranjang->findByIdBarangBobotKemasan($getIdKeranjang,$id,(int) $request->volume_order)->harga;
                        $currentHargaDiskon  = $this->detailKeranjang->findByIdBarangBobotKemasan($getIdKeranjang,$id,(int) $request->volume_order)->harga_diskon;

                        $data = [
                            'volume'       => $request->total_order + $currentVolume,
                            'harga'        => (($this->barang->findById($id)->harga_jual/10) * (($request->total_order*$request->volume_order)/100)) + $currentHarga,
                            'harga_diskon' => $harga_diskon+$currentHargaDiskon
                        ];

                        $this->detailKeranjang->updateDetailKeranjangKemas($id,(int) $request->volume_order,$data);  
                        
                        DB::commit();
                        return 1;
                    }
                }
        }
        catch (\Throwable $th)
        {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function submitUbahItemKeranjang($id, Request $request)
    {
        try
        {
            DB::beginTransaction();   

            if($this->keranjang->findKeranjang($request->tanggal) == null)
            {
                $this->keranjang->tambahKeranjang($request->tanggal);
            }
            
            $getIdKeranjang = $this->keranjang->getIDKeranjang($request->tanggal);

            switch($this->barang->findById($id)->jenis) 
            {
                case 'Paket':
                        if($this->barang->findById($id)->diskon>100){
                            $harga_diskon=($this->barang->findById($id)->harga_jual-$this->barang->findById($id)->diskon) * $request->total_order;
                        }
                        else{
                            $harga_diskon=($this->barang->findById($id)->harga_jual-($this->barang->findById($id)->harga_jual*($this->barang->findById($id)->diskon/100))) * $request->total_order;
                        }
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'harga'        => $this->barang->findById($id)->harga_jual * $request->total_order,
                            'harga_diskon' => $harga_diskon,
                        ];
            
                        $this->detailKeranjang->updateDetailKeranjang($id, $data);

                        DB::commit();
                        return 1;

                case 'Timbang':
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'harga'        => ($this->barang->findById($id)->harga_jual/10) * (($request->total_order)/100),
                            'harga_diskon' => (($this->barang->findById($id)->harga_jual/10)-(($this->barang->findById($id)->harga_jual/10)*($this->barang->findById($id)->diskon/100))) * (($request->total_order)/100)
                        ];

                        $this->detailKeranjang->updateDetailKeranjang($id, $data);  

                        DB::commit();
                        return 1;

                case 'Kemas':
                        $data = [
                            'id'           => Uuid::generate(),
                            'id_keranjang' => $getIdKeranjang,
                            'id_barang'    => $id,
                            'volume'       => $request->total_order,
                            'bobot_kemasan'=> $request->volume_order,
                            'harga'        => ($this->barang->findById($id)->harga_jual/10) * (($request->total_order*$request->volume_order)/100),
                            'harga_diskon' => (($this->barang->findById($id)->harga_jual/10)-(($this->barang->findById($id)->harga_jual/10)*($this->barang->findById($id)->diskon/100))) * (($request->total_order*$request->volume_order)/100)
                        ];

                        $this->detailKeranjang->updateDetailKeranjang($id, $data);
                        
                        DB::commit();
                        return 1;
                }
        }
        catch (\Throwable $th)
        {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function lihatItemKeranjang($date)
    {
        if($this->keranjang->findKeranjang($date) == null){
            $this->keranjang->tambahKeranjang($date);
        }

        $getIDKeranjang       = $this->keranjang->getIDKeranjang($date);
        $getDetailKeranjang   = $this->detailKeranjang->getDetailKeranjang($getIDKeranjang);
        $total=0;

        foreach($getDetailKeranjang as $detailKeranjang)
        {
            $getBarang = $this->barang->findById($detailKeranjang->id_barang);
            if($getBarang->jenis == 'Kemas')
            {
                $detailKeranjang->nama       = $getBarang->nama.' ('.$detailKeranjang->bobot_kemasan.' '.'Gram'.')';
                $detailKeranjang->jenis      = $getBarang->jenis;
                $detailKeranjang->satuan     = 'Kemas';
                $detailKeranjang->bobot      = $getBarang->bobot;
                $detailKeranjang->volume     = $detailKeranjang->volume;
            }
            else if($getBarang->jenis == 'Paket' || $getBarang->jenis == 'Timbang')
            {
                $detailKeranjang->nama       = $getBarang->nama;
                $detailKeranjang->jenis      = $getBarang->jenis;
                $detailKeranjang->satuan     = $getBarang->satuan;
                $detailKeranjang->bobot      = $getBarang->bobot;
            }
            $total+= $detailKeranjang->harga_diskon; 
        }

        $data = [
            'total'=>$total,
            'detail_keranjang'  => $getDetailKeranjang,
            'tanggal_pengiriman' => $this->getTanggalPengiriman(),
            'tanggal_pengirimans' => $date
        ];

        return view('reseller.components.lihat-keranjang')
            ->with(compact('data'))
            ->render();        
    }

    public function getTanggalPengiriman()
    {       

        $getListHari=$this->tanggal->get_tanggal();

        for($a=0;$a<sizeof($getListHari);$a++)
        {
            $tgl=$getListHari[$a]['tanggal'];
            // string
            $finalListHari[$a]['tanggal'] = Carbon::parse($tgl)->format('D, d F Y');
            //tanggal value
            $finalListHari[$a]['tanggal_value'] = Carbon::parse($tgl)->format('Y-m-d');

            // dd($finalListHari[$a]['tanggal_value']);
        }

        

        foreach ($finalListHari as $key => $value) 
        {
            $finalListHari[$key] = (object) $value;
        }

        // dd($finalListHari);
    
        return $finalListHari;
    }

    public function simpan(Request $request){ 
        // dd($request->tanggal);         
        $getKeranjang           = $this->keranjang->getIDKeranjang($request->tanggal);
        $getDetailKeranjang     = $this->detailKeranjang->getDetailKeranjang($getKeranjang);
        // $getKeranjangReseller   = $this->keranjangReseller->get(Auth::user()->id)
        
        $datas=[
            'name'=>$request->name,
            'alamat' =>$request->alamat,
            'nohp' =>$request->nohp,
            'tanggal_pre_order' => $request->tanggal,
        ];
        //cek nama
        $cek_nama=$this->parentKeranjangReseller->getIdFromName($request->name, $request->tanggal);
        // dd($cek_nama);
        if($cek_nama==null){
            $this->parentKeranjangReseller->create($datas);
        }
        
        $getParentKeranjang = $this->parentKeranjangReseller->getIDKeranjang($request->name, $request->tanggal);

        foreach($getDetailKeranjang as $detail){
            // dd($detail->id_barang);
            // if($detail->id_barang==)

            //cek barang
            $cek_barang=$this->keranjangReseller->getBarangFromIdBarangAndBobot($getParentKeranjang, $detail->id_barang, $detail->bobot_kemasan);
            // dd($cek_barang);
            if($cek_barang==null){
                $data = [
                    'id_parent_keranjang'   => $getParentKeranjang,
                    'id_barang' => $detail->id_barang,
                    'volume' =>$detail->volume,
                    'harga' =>$detail->harga,
                    'bobot_kemasan' => $detail->bobot_kemasan,
                    'harga_diskon' =>$detail->harga_diskon,
                ];
                $this->keranjangReseller->create($data);
            }
            else{
                $data=[
                    'volume' =>$detail->volume + $cek_barang->volume,
                    'harga' =>$detail->harga + $cek_barang->harga,
                    'harga_diskon' =>$detail->harga_diskon + $cek_barang->harga_diskon,
                ];
                $this->keranjangReseller->update($cek_barang->id,$data);
            }
            
            // DB::commit();
        }
        $this->keranjang->hapusKeranjang($getKeranjang);
        $this->detailKeranjang->hapusDetailKeranjangByIdKeranjang($getKeranjang);
        return redirect()->back();
        // }
        // catch (\Throwable $th)
        // {
        //     DB::rollback();
        //     return $th->getMessage();
        // }
        // dd($getDetailKeranjang);        
    }
}