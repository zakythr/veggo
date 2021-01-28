<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProdukRepository;
use App\Repositories\UserRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\BobotKemasanRepository;
use App\Repositories\ProdukKemasanRepository;
use App\Repositories\ProdukGroupRepository;
use App\Repositories\FotoProdukRepository;
use App\Repositories\InventarisRepository;
use App\Repositories\DetailTransaksiRepository;
use App\Repositories\DetailKeranjangRepository;
use App\Repositories\DetailKlaimRepository;
use App\Repositories\KeranjangResellerRepository;
use App\Repositories\BarangTanggalRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\IsiResepRepository;

use Illuminate\Support\Facades\Storage;
use Response;
use DB;
use Auth;


class ProdukController extends Controller
{

    protected $produkRepository, $userRepository, $kategoriRepository, $bobotKemasanRepository, $baseKategoriRepository, $produkKemasanRepository, $produkGroupRepository, $fotoProdukRepository, $inventarisRepository, $detailTransaksiRepository, $detailKeranjangRepository,$detailKlaimRepository, $keranjangResellerRepository, $barangTanggalRepository, $isiPaketRepository, $isiResepRepository;

    public function __construct(ProdukRepository $produkRepository, UserRepository $userRepository, KategoriRepository $kategoriRepository, BobotKemasanRepository $bobotKemasanRepository, BaseKategoriRepository $baseKategoriRepository, ProdukKemasanRepository $produkKemasanRepository, ProdukGroupRepository $produkGroupRepository, FotoProdukRepository $fotoProdukRepository, InventarisRepository $inventarisRepository, DetailTransaksiRepository $detailTransaksiRepository, DetailKeranjangRepository $detailKeranjangRepository,DetailKlaimRepository $detailKlaimRepository, KeranjangResellerRepository $keranjangResellerRepository,BarangTanggalRepository $barangTanggalRepository,IsiPaketRepository $isiPaketRepository,IsiResepRepository $isiResepRepository)
    {
        $this->produkRepository = $produkRepository;
        $this->userRepository = $userRepository;
        $this->kategoriRepository = $kategoriRepository;
        $this->bobotKemasanRepository = $bobotKemasanRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->produkKemasanRepository = $produkKemasanRepository;
        $this->produkGroupRepository = $produkGroupRepository;
        $this->fotoProdukRepository = $fotoProdukRepository;
        $this->inventarisRepository = $inventarisRepository;
        $this->detailTransaksiRepository=$detailTransaksiRepository;
        $this->detailKeranjangRepository=$detailKeranjangRepository;
        $this->detailKlaimRepository=$detailKlaimRepository; 
        $this->keranjangResellerRepository= $keranjangResellerRepository;
        $this->barangTanggalRepository=$barangTanggalRepository;
        $this->isiPaketRepository=$isiPaketRepository;
        $this->isiResepRepository=$isiResepRepository;
        
        
        $this->middleware('auth');
        $this->middleware('penjual');

    }    

    public function listProduk()
    {
        $getAllProduk = $this->produkRepository->getProduct();

        return view('penjual.produk')->with('produks', $getAllProduk);
    }

    public function tambahProduk()
    {

        $getAllSupplier = $this->userRepository->getPetani();
        $kodebarang = $this->generateKode();
        $subKategori = $this->kategoriRepository->all();
        $bobotKemasan = $this->bobotKemasanRepository->all();
        $baseKategori = $this->baseKategoriRepository->all();

        $data = [
            'supplier' => $getAllSupplier,
            'kodebarang' => $kodebarang,
            'group' => $subKategori,
            'kemasan_value' => $bobotKemasan,
            'kategori_value' => $baseKategori,
            'jenis_value' => ['Kemas', 'Timbang'],
            'satuan_value' => ['Gram','Pcs']
        ];

        return view('penjual.tambah_produk')->with('data', $data);
    }

    public function _tambahProduk(Request $request)
    {
        if(count($request->file('foto_barang')) > 5){
            return redirect()->back()->with('error',"Maksimal Upload 5 Foto!")->withInput();
        }

        session(['id_supplier' => $request->input('id_user')]);
        if($request->jenis_diskon=="Potongan Persen"){
            if ($request->jenis_diskon_reseller=="Potongan Persen") {
                $harga_diskon=$request->harga_jual-($request->harga_jual*($request->diskon/100));
                $reseller=($harga_diskon-($harga_diskon*($request->diskon_reseller/100)));
            } else {
                $harga_diskon=$request->harga_jual-($request->harga_jual*($request->diskon/100));
                $reseller=$harga_diskon-$request->diskon_reseller;
            }
        }
        else{
            if ($request->jenis_diskon_reseller=="Potongan Persen") {
                $harga_diskon=$request->harga_jual-$request->diskon;
                $reseller=($harga_diskon-($harga_diskon*($request->diskon_reseller/100)));
            } else {
                $harga_diskon=$request->harga_jual-$request->diskon;
                $reseller=$harga_diskon-$request->diskon_reseller;
            }
        }

        $request->merge([
            'harga_jual_reseller' => $reseller,
        ]);

        DB::beginTransaction();
        try {
            $this->produkRepository->create($request->all());
            if($request->input('bobot_kemasan') !== null){
                $this->produkKemasanRepository->create($request->input('id'), $request->input('bobot_kemasan'));
            }
            $this->produkGroupRepository->create($request->input('id'), $request->input('group_etalase'));
            $this->uploadPhoto($request->input('id'),$request->file('foto_barang'));

            DB::commit();
            return redirect()->back()->with('success',"Berhasil Menambah!");                    
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error',"Gagal menambahkan barang!")->withInput();
            
        }
        
        return redirect()->back();
    }

    private function generateKode(){

        $finalStr = 'B'.rand(1000,9999);

        $kode = $this->produkRepository->findByKode($finalStr);
        if($kode){
            $this->generateKode();
        }else{
            return $finalStr;
        }
    }

    private function uploadPhoto($id,$datas){

        $fotos = $this->fotoProdukRepository->findByIdBarang($id);

        foreach($fotos as $foto){
            $path = public_path().'\img\foto_barang\\';
            $filename = $path.$foto->path;
            if(file_exists($filename)){
                unlink($filename);
                $this->fotoProdukRepository->delete($id,$foto->path);
            }
        }

        
        foreach($datas as $data){
            $filename = time().$data->getClientOriginalName();
            $path = public_path().'/img/foto_barang';
            $data->move($path,$filename);
            $this->fotoProdukRepository->create($id, $filename);
        }
        
    }

    public function index()
    {
        return view('penjual.home');
    }

    public function ubahProduk($id){

        $getAllSupplier = $this->userRepository->getPetani();
        $getBarang = $this->produkRepository->findById($id);
        $subKategori = $this->kategoriRepository->all();
        $barangKemasan = $this->produkKemasanRepository->findByIdBarang($id);
        $barangGroup = $this->produkGroupRepository->findByIdBarang($id);
        $bobotKemasan = $this->bobotKemasanRepository->all();
        $baseKategori = $this->baseKategoriRepository->all();
    
            
        for($i=0;$i<count($bobotKemasan);$i++){
            for($j=0;$j<count($barangKemasan);$j++){
                if(($bobotKemasan[$i]->bobot_kemasan == $barangKemasan[$j]->bobot_kemasan) && ($bobotKemasan[$i]->selected != 1)){                    
                    $bobotKemasan[$i]->selected = 1;
                 }elseif($bobotKemasan[$i]->selected == 0){
                     $bobotKemasan[$i]->selected = 0;
                 }
            }
        }

        for($i=0;$i<count($subKategori);$i++){
            for($j=0;$j<count($barangGroup);$j++){
                if(($subKategori[$i]->id == $barangGroup[$j]->id_kategori) && ($subKategori[$i]->selected != 1)){                    
                    $subKategori[$i]->selected = 1;
                 }elseif($subKategori[$i]->selected == 0){
                     $subKategori[$i]->selected = 0;
                 }
            }
        }        
        
        $data = [
            'supplier' => $getAllSupplier,
            'barang' => $getBarang,
            'group' => $subKategori,
            'kemasan_barang' => $barangKemasan,
            'group_barang' => $barangGroup,
            'kemasan_value' => $bobotKemasan,
            'kategori_value' => $baseKategori,
            'jenis_value' => ['Kemas', 'Timbang'],
            'satuan_value' => ['Gram','Pcs']
        ];

        return view('penjual.ubah_produk')->with('data',$data);
    }

    public function _ubahProduk(Request $request){

        session(['id_supplier' => $request->input('id_user')]);

        if($request->jenis_diskon=="Potongan Persen"){
            if ($request->jenis_diskon_reseller=="Potongan Persen") {
                $harga_diskon=$request->harga_jual-($request->harga_jual*($request->diskon/100));
                $reseller=($harga_diskon-($harga_diskon*($request->diskon_reseller/100)));
            } else {
                $harga_diskon=$request->harga_jual-($request->harga_jual*($request->diskon/100));
                $reseller=$harga_diskon-$request->diskon_reseller;
            }
        }
        else{
            if ($request->jenis_diskon_reseller=="Potongan Persen") {
                $harga_diskon=$request->harga_jual-$request->diskon;
                $reseller=($harga_diskon-($harga_diskon*($request->diskon_reseller/100)));
            } else {
                $harga_diskon=$request->harga_jual-$request->diskon;
                $reseller=$harga_diskon-$request->diskon_reseller;
            }
        }

        $request->merge([
            'harga_jual_reseller' => $reseller,
        ]);       

        DB::beginTransaction();
        try {
            $this->produkGroupRepository->delete($request->id);
            $this->produkKemasanRepository->delete($request->id);
            $this->produkRepository->update($request->id,$request->all());

            if($request->input('bobot_kemasan') !== null){
                $this->produkKemasanRepository->create($request->input('id'), $request->input('bobot_kemasan'));
            }
            $this->produkGroupRepository->create($request->input('id'), $request->input('group_etalase'));
            

            DB::commit();
            return redirect()->back()->with('success',"Berhasil Mengubah!")->withInput();                    

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with('error',"Gagal menambahkan barang!")->withInput();
            
        }
        
        return redirect()->back();        

    }

    public function inventarisProduk(){
        
        $data = [
            'inventaris' => $this->produkRepository->getProduct(),
            'keluar_masuk' => $this->inventarisRepository->all(),
            'nama'=>"Produk"
        ];

        // dd($data);
        
        return view('penjual.inventaris')->with('data',$data);
        
    }
    public function inventarisPaket(){
        
        $data = [
            'inventaris' => $this->produkRepository->getPaket(),
            'keluar_masuk' => $this->inventarisRepository->all(),
            'nama'=>"Paket"
        ];

        // dd($data);
        
        return view('penjual.inventaris')->with('data',$data);
        
    }

    public function tambahInventaris(){
        $getAllSupplier = $this->userRepository->getPetani();
        $kodebarang = $this->generateKode();
        $subKategori = $this->kategoriRepository->all();
        $bobotKemasan = $this->bobotKemasanRepository->all();
        $baseKategori = $this->baseKategoriRepository->all();

        $data = [
            'supplier' => $getAllSupplier,
            'kodebarang' => $kodebarang,
            'group' => $subKategori,
            'kemasan_value' => $bobotKemasan,
            'kategori_value' => $baseKategori,
            'jenis_value' => ['Kemas', 'Timbang'],
            'satuan_value' => ['Gram','Pcs']
        ];

        return view('penjual.tambah_inventaris')->with('data',$data);
    }

    public function _tambahInventaris(Request $request){

    }

    public function ubahInventaris($id){
        $getAllSupplier = $this->userRepository->getPetani();
        $getBarang = $this->produkRepository->findById($id);
        $subKategori = $this->kategoriRepository->all();
        $barangKemasan = $this->produkKemasanRepository->findByIdBarang($id);
        $barangGroup = $this->produkGroupRepository->findByIdBarang($id);
        $bobotKemasan = $this->bobotKemasanRepository->all();
        $baseKategori = $this->baseKategoriRepository->all();
    
            
        for($i=0;$i<count($bobotKemasan);$i++){
            for($j=0;$j<count($barangKemasan);$j++){
                if(($bobotKemasan[$i]->bobot_kemasan == $barangKemasan[$j]->bobot_kemasan) && ($bobotKemasan[$i]->selected != 1)){                    
                    $bobotKemasan[$i]->selected = 1;
                 }elseif($bobotKemasan[$i]->selected == 0){
                     $bobotKemasan[$i]->selected = 0;
                 }
            }
        }

        for($i=0;$i<count($subKategori);$i++){
            for($j=0;$j<count($barangGroup);$j++){
                if(($subKategori[$i]->id == $barangGroup[$j]->id_kategori) && ($subKategori[$i]->selected != 1)){                    
                    $subKategori[$i]->selected = 1;
                 }elseif($subKategori[$i]->selected == 0){
                     $subKategori[$i]->selected = 0;
                 }
            }
        }        
        
        $data = [
            'supplier' => $getAllSupplier,
            'barang' => $getBarang,
            'group' => $subKategori,
            'kemasan_barang' => $barangKemasan,
            'group_barang' => $barangGroup,
            'kemasan_value' => $bobotKemasan,
            'kategori_value' => $baseKategori,
            'jenis_value' => ['Kemas', 'Timbang'],
            'satuan_value' => ['Gram','Pcs']
        ];


        return view('penjual.ubah_inventaris')->with('data',$data);        
    }

    public function _ubahInventaris(Request $request){
        // dd($request->input());
        $id_barang = $request->input('id');
        $stok = $request->input('stok')+$request->input('stok_awal');
        $stok_manual=$request->input('stok');
        $keterangan = "Tambah Stok";
        $this->produkRepository->update($id_barang,['stok' => $stok]);
        $this->inventarisRepository->inventarisIn($id_barang,'tambah manual',$stok_manual,$keterangan);
        return redirect('/Penjual/Inventaris/Produk');

    }

    public function stok(){

        $barang = $this->produkRepository->findByUserId(Auth::user()->id);
        
        $data = [
            'barang' => $barang
        ];
        return view('penjual.stok')->with('data',$data);
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

    public function _hapusProduk(Request $request){
        $id = $request->id;
        try {
            DB::beginTransaction();
            $this->produkRepository->delete($id);
            $this->produkKemasanRepository->deleteByIdBarang($id);
            $this->produkGroupRepository->deleteByIdBarang($id);
            $this->fotoProdukRepository->deleteByIdBarang($id);
            $this->inventarisRepository->deleteByIdBarang($id);
            $this->detailTransaksiRepository->deleteByIdBarang($id);
            $this->detailKeranjangRepository->deleteByIdBarang($id);
            $this->detailKlaimRepository->deleteByIdBarang($id);
            $this->keranjangResellerRepository->deleteByIdBarang($id);
            $this->barangTanggalRepository->deleteByIdBarang($id);
            $this->isiPaketRepository->deleteByIdBarang($id);
            $this->isiResepRepository->deleteByIdBarang($id);
            $this->isiPaketRepository->deleteByIdParentBarang($id);
            $this->isiResepRepository->deleteByIdParentBarang($id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
        // $this->produkRepository->delete($id);
        return redirect()->back();
    }
    
}
