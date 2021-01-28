<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\KategoriRepository;
use App\Repositories\BaseKategoriRepository;
use App\Repositories\ProdukGroupRepository;
use Response;


class KategoriController extends Controller
{

    protected $kategoriRepository, $baseKategoriRepository, $produkGroupRepository;

    public function __construct(KategoriRepository $kategoriRepository, BaseKategoriRepository $baseKategoriRepository,ProdukGroupRepository $produkGroupRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
        $this->baseKategoriRepository = $baseKategoriRepository;
        $this->produkGroupRepository = $produkGroupRepository;

        $this->middleware('auth');
        $this->middleware('penjual');

    }    

    public function listKategori(){

        $getAllKategori = $this->kategoriRepository->all();
        $getBaseKategori = $this->baseKategoriRepository->all();

        $data = [
            "kategoris" => $getAllKategori,
            "baseKategoris" => $getBaseKategori,
        ];
        return view('penjual.kategori')->with('data',$data);
    }

    public function _tambahKategori(Request $request){

        $this->kategoriRepository->create($request->all());
        return redirect()->back();
    }

    public function _hapusKategori(Request $request){
        $id = $request->id;
        $this->kategoriRepository->delete($id);
        return redirect()->back();
    }

    public function _ubahKategori(Request $request){
        $id = $request->id;
        $this->kategoriRepository->update($id, $request->all());
        return redirect()->back();
    }


}
