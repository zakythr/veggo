<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Repositories\ProdukRepository;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\FotoProdukRepository;
use App\Repositories\IsiPaketRepository;
use App\Repositories\ProdukGroupRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Uuid;
use Auth;
use View;
use DB;


class PaketController extends Controller
{
    protected $produkRepository, $kategoriRepository, $baseKategoriRepository, $fotoProdukRepository, $isiPaketRepository, $produkGroupRepository;

    public function __construct(ProdukRepository $produkRepository, KategoriRepository $kategoriRepository, BaseKategoriRepository $baseKategoriRepository, FotoProdukRepository $fotoProdukRepository, IsiPaketRepository $isiPaketRepository, ProdukGroupRepository $produkGroupRepository, UserRepository $userRepository)
    {
        $this->produkRepository = $produkRepository;
        $this->kategoriRepository = $kategoriRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->fotoProdukRepository = $fotoProdukRepository;
        $this->isiPaketRepository = $isiPaketRepository;
        $this->produkGroupRepository = $produkGroupRepository;
        $this->userRepository = $userRepository;
        
        $this->middleware('auth');

    }
    public function listPaket()
    {
        $getAllProduk = $this->produkRepository->getPaket();

        return view('penjual.paket')->with('produks', $getAllProduk);
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


    private function generateKode(){

        $finalStr = 'BP'.rand(100,999);

        $kode = $this->produkRepository->findByKode($finalStr);
        if($kode){
            $this->generateKode();
        }else{
            return $finalStr;
        }
    }

    public function tambahPaket(){
        $produk = $this->produkRepository->all();
        $subKategori = $this->kategoriRepository->all();
        $kategori = $this->baseKategoriRepository->all();
        $getAllSupplier = $this->userRepository->getPetani();
        
        $data = [
            'produk' => $produk,
            'subKategori' => $subKategori,
            'kategori' => $kategori,
            'supplier' => $getAllSupplier
        ];

        return view('penjual.tambah_paket')->with('data',$data);
    }

    public function _tambahPaket(Request $request){
        // dd($request->file('foto_barang'));
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

        try {

            DB::beginTransaction();
            if($request->diskon==null){
                $diskon=0;
            }
            else{
                $diskon=$request->diskon;
            }

            if(count($request->file('foto_barang')) > 5){
                return redirect()->back()->with('error',"Maksimal Upload 5 Foto!")->withInput();
            }           
    
            $id_barang = Uuid::generate(4)->string; 
            // dd($request->harga_beli);
            $newProduk = [
                'id' => $id_barang,
                'id_user' => $request->id_user,
                'id_kategori' => $request->id_kategori,
                'nama' => $request->nama,
                'kode' => $this->generateKode(),
                'jenis' => "Paket",
                'satuan' => "Pcs",
                'bobot' => 1,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'deskripsi' => "-",
                'diskon' => $diskon,
                'jenis_diskon' => $request->jenis_diskon,
                'jenis_diskon' => $request->jenis_diskon_reseller,
                'diskon' => $request->diskon_reseller,
                'show_etalase' => 0,
                'is_paket' => 1,
                'ketersediaan' => 0,
                'stok'  => 0,    
                'harga_jual_reseller' => $reseller,
            ];
            
            $item = [
                'isi_paket' => $request->isi_paket,
                'harga_isi_paket' => $request->harga_isi_paket,
                'volume_isi_paket' => $request->volume_isi_paket,
            ];

            $this->produkRepository->create_paket($newProduk);
            $this->isiPaketRepository->create($id_barang,$item);
            $this->produkGroupRepository->create($id_barang, $request->group);
            $this->uploadPhoto($id_barang,$request->file('foto_barang'));

            DB::commit();
            return redirect()->back()->with('success',"Berhasil Menambah!");                    
            
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            dd($th);
        }

    }

    public function ubahPaket($id){

        $paket = $this->produkRepository->findById($id);

        $produk = $this->produkRepository->all();
        $subKategori = $this->kategoriRepository->all();
        $kategori = $this->baseKategoriRepository->all();

        $barangGroup = $this->produkGroupRepository->findByIdBarang($id);
        $isiPaket = $this->isiPaketRepository->findByIdBarang($id);

        $getAllSupplier = $this->userRepository->getPetani();
        
        for($i=0;$i<count($subKategori);$i++){
            for($j=0;$j<count($barangGroup);$j++){
                if(($subKategori[$i]->id == $barangGroup[$j]->id_kategori) && ($subKategori[$i]->selected != 1)){                    
                    $subKategori[$i]->selected = 1;
                 }elseif($subKategori[$i]->selected == 0){
                     $subKategori[$i]->selected = 0;
                 }
            }
        }

        $index = count($isiPaket);

        $data = [
            'paket' => $paket,
            'isiPaket' => $isiPaket,
            'produk' => $produk,
            'subKategori' => $subKategori,
            'kategori' => $kategori,
            'index' => $index - 1,
            'supplier'=>$getAllSupplier
        ];
        // dd($data);

        return view('penjual.ubah_paket')->with('data',$data);

    }

    public function _ubahPaket(Request $request){
        // dd($request->input());

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
        // dd($reseller);
        $request->merge([
            'harga_jual_reseller' => $reseller,
        ]);
        // dd($request->harga_jual_reseller);
        
        // dd($request->all());
        DB::beginTransaction();
        try {
            
            $item = [
                'isi_paket' => $request->isi_paket,
                'harga_isi_paket' => $request->harga_isi_paket,
                'volume_isi_paket' => $request->volume_isi_paket,
            ];

            
            $this->isiPaketRepository->create($request->id,$item);
            $this->produkGroupRepository->delete($request->id);
            $this->produkRepository->update($request->id,$request->all());
            $this->produkGroupRepository->create($request->input('id'), $request->input('group'));
            
            DB::commit();
            return redirect()->back()->with('success',"Berhasil Mengubah!")->withInput();                    

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->back()->with('error',"Gagal menambahkan barang!")->withInput();
            
        }
        
        return redirect()->back();              


    }

    public function getPaketItem($jumlah)
    {
        $produk = $this->produkRepository->getProduct();
        $data = [
            'produk' => $produk,
            'jumlah' => $jumlah
        ];

        return View::make('penjual.layouts.item_paket')
            ->with('data', $data)
            ->render();
    }    


}
