<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransaksiInterface;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;
use DB;

class TransaksiRepository implements TransaksiInterface
{
    protected $model;

    public function __construct(Transaksi $model)
    {
        $this->model = $model;
    }

    public function getAllTransaksiByUser($id)
    {
        return $this->model->where('id_user', $id)->get();
    }

    public function getAllTransaksiByUserAndDate($id, $tanggal)
    {
        return $this->model->where([
            ['id_user', $id],
            ['tanggal_pre_order',$tanggal]
            ])->orderBy('created_at', 'DESC')->get();
    }

    public function getAllTransaksiByUserAndNotPay($id)
    {
        return $this->model->where([
            ['id_user', $id],
            ['isAlreadyPay','!=', 3],
            ['status','<', 7],
            ['is_canceled_by_veggo', 0],
            ])->orderBy('created_at', 'DESC')->get();
    }

    public function getAllTransaksiByUserAndInProcess($id)
    {
        return $this->model->where([
            ['id_user', $id],
            ['isAlreadyPay', 3],
            ['status', '<', 7],
            ['is_canceled_by_veggo', 0],
            ])->orderBy('created_at', 'DESC')->get();
    }

    public function getAllTransaksiByUserAndIsFinish($id)
    {
        return $this->model->where([
            ['id_user', $id],
            ['status', 7],
            ['is_canceled_by_veggo', 0],
            ])->orderBy('created_at', 'DESC')->get();
    }

    public function getAllTransaksiByUserAndIsCancelled($id)
    {
        return $this->model->where([
            ['id_user', $id],
            ['is_canceled_by_veggo', 1],
            ])->orderBy('created_at', 'DESC')->get();
    }

    public function updateTransaksi($id_transaksi, $status)
    {
        return $this->model->where('id', $id_transaksi)->update(['status' => $status]);
    }

    public function konfirmasiTransaksi($id_transaksi){
        return $this->model->where([
            ['id_user', Auth::user()->id],
            ['id', $id_transaksi],
            ])->update(['is_confirm_finish_byuser'=> 1]);
    }

    public function konfirmasiDiterima($id_transaksi, $nama_penerima, $foto_penerima, $keterangan_penerima, $tanggal_terima)
    {
        return $this->model->where('id', $id_transaksi)->update([
            'nama_penerima' => $nama_penerima,
            'foto_penerima' => $foto_penerima, 
            'keterangan_penerima'    => $keterangan_penerima,
            'tanggal_terima' => $tanggal_terima,
            'status'        => 7,
            'isAlreadyPay' =>3
        ]);
    }

    public function kirimBukti($id_transaksi, $bukti_transfer)
    {
        return $this->model->where('id', $id_transaksi)->update([
            'bukti_transfer' => $bukti_transfer,
            'isAlreadyPay'    => 1,
        ]);
    }

    public function all(){
        return $this->model->all()->sortByDesc("created_at");
    }

    public function update($id,$data)
    {
        return $this->model->find($id)->update($data);  
    }

    public function getTransactionDateWithStatus($status, $tipe, $cancel)
    {
        // $current = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        if($cancel==0){
            return $dates = $this->model->select("tanggal_pre_order")
                                        ->where('status', $status)
                                        ->where('tipe_transaksi',$tipe)
                                        ->where('is_canceled_by_veggo',$cancel)
                                        ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
        }
        else{
            return $dates = $this->model->select("tanggal_pre_order")
                                    ->where('tipe_transaksi',$tipe)
                                    ->where('is_canceled_by_veggo',$cancel)
                                    ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
        }
        
    }
    public function getTransactionDateWithStatus2($status,$status2, $tipe)
    {
        // $current = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        return $dates = $this->model->select("tanggal_pre_order")
                                    ->whereBetween("status",[$status,$status2])
                                    ->where('tipe_transaksi',$tipe)
                                    ->where('is_canceled_by_veggo',0)
                                    ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
    }
    public function getTransactionDate()
    {
        // $current = Carbon::now()->toDateString();
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        return $dates = $this->model->select("tanggal_pre_order")->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get();         
    }

    public function findByTanggalPreOrder($date){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('status',1)
                            ->get();
    }

    public function findByTanggalPreOrderAndStatusAndTipeTransakisAkumulasi($date,$status,$tipe){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('status',$status)
                            ->where('tipe_transaksi',$tipe)
                            ->where('is_canceled_by_veggo',0)
                            ->where('is_exclude_rekap',0)
                            ->orderBy('created_at', 'DESC')
                            ->get();
    }    

    public function findByTanggalPreOrderAndStatusAndTipeTransakis($date,$status,$tipe){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('status',$status)
                            ->where('tipe_transaksi',$tipe)
                            ->where('is_canceled_by_veggo',0)
                            ->orderBy('created_at', 'DESC')
                            ->get();
    }  
    public function findByTanggalPreOrderAndStatusAndTipeTransakis2($date,$status,$status2,$tipe){
        return $this->model->where('tanggal_pre_order',$date)
                            ->whereBetween('status',[$status, $status2])
                            ->where('tipe_transaksi',$tipe)
                            ->where('is_canceled_by_veggo',0)
                            ->orderBy('created_at', 'DESC')
                            ->get();
    }  
    
    public function getAllTransaksiIsCancelled($date,$tipe)
    {
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('tipe_transaksi',$tipe)
                            ->where('is_canceled_by_veggo',1)
                            ->orderBy('created_at', 'DESC')
                            ->get();
    }

    public function findByTanggalPreOrderAndTipeTransaksi($date,$tipe){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('status',1)
                            ->where('tipe_transaksi',$tipe)
                            ->get();
    }

    public function excludeRekapPreOrder($id)
    {
        $data = $this->model->find($id);
        $data->is_exclude_rekap = 1;
        $data->save();        
    }

    public function cancelPreOrder($id)
    {
        $data = $this->model->find($id);
        $data->is_canceled_by_veggo = 1;
        $data->save();        
    }

    public function halamanCheckout($data)
    {
        #tanggal pre order
        $date = Carbon::parse($data['tanggal_pre_order']);

        #generate nomor invoice
        $rand           = substr(uniqid('', true), -5);
        $nomor_invoice  = 'VG' . $rand;

        $inputData = [
            'id'                => Uuid::generate()->string,
            'id_user'           => Auth::user()->id,
            'nomor_invoice'     => $nomor_invoice,
            'total_bayar'       => $data['total_bayar'],
            'tanggal_pre_order' => $date,
            'isCheckout'        => $data['isCheckout']
        ];

        return $this->model->create($inputData);
    }

    public function halamanCheckoutReseller($data)
    {
        #tanggal pre order
        $date = Carbon::parse($data['tanggal_pre_order']);

        #generate nomor invoice
        $rand           = substr(uniqid('', true), -5);
        $nomor_invoice  = 'VGRES' . $rand;

        $inputData = [
            'id'                => Uuid::generate()->string,
            'id_user'           => Auth::user()->id,
            'nomor_invoice'     => $nomor_invoice,
            'total_bayar'       => $data['total_bayar'],
            'tanggal_pre_order' => $date,
            'isCheckout'        => $data['isCheckout']
        ];

        return $this->model->create($inputData);
    }

    public function purchaseCheckout($data, $date)
    {

        $getTransaksi = $this->checkTransaksi($date);
        $dataInput = [
            'isCheckout' => 1, 
            'status'     => 1,
            'keterangan' => $data['keterangan'],
            'id_alamat'  => $data['id_alamat']
        ];

        return $this->model
            ->where('id', $getTransaksi->id)
            ->where('id_user', Auth::user()->id)
            ->update($dataInput);
    }

    public function getIncludedRekap($date,$flag){
        return $this->model->where('is_exclude_rekap',$flag)
                           ->where('tanggal_pre_order',$date)
                           ->get();
    }

    public function checkTransaksi($date)
    {
        return $this->model
            ->where('id_user', Auth::user()->id)
            ->where('isCheckout', 0)
            ->where('tanggal_pre_order', $date)
            ->first();
    }

    public function updateHalamanCheckout($data)
    {
        #tanggal pre order
        $date = Carbon::parse($data['tanggal_pre_order']);

        $inputData = [
            'total_bayar'       => $data['total_bayar'],
            'tanggal_pre_order' => $date,
        ];

        return $this->model
            ->where('id_user', Auth::user()->id)
            ->where('isCheckout', 0)
            ->update($inputData);       
    }

    public function getPaketYangAkanDikirim()
    {
        $id_kurir = Auth::user()->id;

        return $this->model
            ->where('id_kurir', $id_kurir)
            ->where('status', 5)
            ->get();
    }

    public function getPaketDalamPengiriman()
    {
        $id_kurir = Auth::user()->id;

        return $this->model
            ->where('id_kurir', $id_kurir)
            ->where('status', 6)
            ->get();
    }

    public function getPaketSelesaiDikirim()
    {
        $id_kurir = Auth::user()->id;

        return $this->model
            ->where('id_kurir', $id_kurir)
            ->where('status', 7)
            ->get();
    }
    public function orderKePetani($data){
        #tanggal pre order
        $date = Carbon::parse($data['tanggal']);

        #generate nomor invoice
        $rand           = substr(uniqid('', true), -5);
        $nomor_invoice  = 'VGPETANI' . $rand;        

        $inputData = [
            'id'=>Uuid::generate()->string,
            'id_user'=> $data['id_user'],
            'id_alamat'=> "alamat-petani",
            'id_kurir'=> "veggo",
            'nomor_invoice'=> $nomor_invoice,
            'total_bayar'=> 0,
            'status'=> 1,
            'bukti_transfer'=> 0,
            'tanggal_pre_order'=> $data['tanggal'],
            'keterangan'=> "order ke petani",
            'tipe_transaksi'=> "FROM_VEGGO",
        ];

        return $this->model->create($inputData);
    }

    public function updateStatusOrderKePetani($id,$flag){
        $transaksi = $this->model->find($id);
        $transaksi->status = $flag;
        return $transaksi->save();
    }

    public function updateTanggalPengiriman($id,$date){
        $transaksi = $this->model->find($id);
        $transaksi->tanggal_pengiriman = $date;
        return $transaksi->save();
    }

    public function updateTanggalTerima($id,$date){
        $transaksi = $this->model->find($id);
        $transaksi->tanggal_terima = $date;
        return $transaksi->save();
    }



    public function updateKurirPengiriman($id,$kurir, $reseller,$flag){
        $transaksi = $this->model->find($id);
        $transaksi->id_kurir = $kurir;
        if($reseller==null){
            $transaksi->is_diterima_reseller=0;
        }
        else{
            $transaksi->is_diterima_reseller=1;
        }
        $transaksi->id_reseller = $reseller;
        $transaksi->status = $flag;
        $transaksi->tanggal_pengiriman = Carbon::now();
        return $transaksi->save();        
    }

    public function findByIdUser($id){
        return $this->model->where('id_user',$id)->orderBy('created_at', 'DESC')->get();
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function findByTipeTransaksi($tipe){
        return $this->model->where('tipe_transaksi',$tipe)->orderBy('created_at', 'DESC')->get();

    }

    public function findByNomorInvoice($invoice){
        return $this->model->where('nomor_invoice',$invoice)->first();
    }

    public function findByTipeTransaksiAndTanggalPreOrderAndStatusOrIsAlreadyPay($tipe,$tanggal,$status,$ispaid){
        return $this->model->where('is_preorder',$tipe)
                           ->where('tanggal_pre_order',$tanggal)                   
                           ->where(function($query) use ($status,$ispaid){
                               $query->orwhere('status',$status)->orwhere('isAlreadyPay',$ispaid);
                           })
                           ->get();
    }

    public function getTransaksiPengiriman($date){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->where(function($q){
                                $q->orwhere('status',4)
                                ->orwhere('status',5)
                                ->orwhere('status',6);
                            })
                            ->get();        
    }  

    public function getPreOrderAndTanggal($tipe,$date){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->where('is_preorder',1)
                            ->where('is_order_to_petani',0)->get();

    }

    public function getProsesOrderAndTanggal($tipe,$date){
        return $this->model->where('tanggal_pre_order',$date)
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->where('is_order_to_petani',1)->get();

    }

    public function getCountVerif($status,$tipe, $cancel){
        if($cancel==0){
            return $this->model->select("tanggal_pre_order" , DB::raw('count(*) as total'))
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->where('status',$status)
                            ->where('tipe_transaksi',$tipe)
                            ->where('isAlreadyPay',1)
                            ->where('is_canceled_by_veggo',$cancel)
                            ->groupBy('tanggal_pre_order')
                            ->get();
        }
        else{
            return $this->model->select("tanggal_pre_order" , DB::raw('count(*) as total'))
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->where('tipe_transaksi',$tipe)
                            ->where('isAlreadyPay',1)
                            ->where('is_canceled_by_veggo',$cancel)
                            ->groupBy('tanggal_pre_order')
                            ->get();
        }
        
    }
    public function getCountVerif2($status,$status2,$tipe){
            return $this->model->select("tanggal_pre_order" , DB::raw('count(*) as total'))
                            ->where('tipe_transaksi',"FROM_BUYER")
                            ->whereBetween('status',[$status, $status2])
                            ->where('tipe_transaksi',$tipe)
                            ->where('isAlreadyPay',1)
                            ->where('is_canceled_by_veggo',0)
                            ->groupBy('tanggal_pre_order')
                            ->get();        
    }

    public function getTransaksiSudahKirim(){
        return $this->model->where('nomor_invoice', 'like', '%VGPETANI%')
                            ->whereNotNull('tanggal_pengiriman')
                            ->get()->all();
    }

    public function getTglPetani(){
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        
        return $dates = $this->model->select("tanggal_pre_order")
                                    ->where('nomor_invoice', 'like', '%VGPETANI%')
                                    ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
    }

    public function hargaBarangByPetani($date){
        return collect(DB::select('call sp_get_total_bayar_veggo_by_tanggal(?)',array($date)))->where('id_petani', Auth::user()->id);
    }
    public function getTglByIdPetani(){
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        
        return $dates = $this->model->select("tanggal_pre_order")
                                    ->where('id_user', Auth::user()->id)
                                    ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
    }

    public function konfirmSampai($confirmReseller, $id){
        return $this->model->where('id', $id)->update(['is_diterima_reseller' => $confirmReseller]);
    }
    public function konfirmDiterima($confirmDiterima, $getHariIni, $id){
        return $this->model->where('id', $id)
                    ->update([
                        'status' => $confirmDiterima, 
                        'tanggal_terima'=>$getHariIni,
                        'isAlreadyPay' =>3
                        ]);
    }

    public function getPemasukan($date){
        return $this->model->select(DB::raw("ifnull(SUM(total_bayar_akhir), 0) as pemasukan"))
	    ->where('status', 7)
	    ->where('tanggal_pre_order', $date)
	    ->get();
    }

    public function getTgl(){
        $from = Carbon::now()->subDays(14)->toDateString();
        $to = Carbon::now()->addDays(14)->toDateString();
        
        return $dates = $this->model->select("tanggal_pre_order")
                                    ->whereBetween("tanggal_pre_order",[$from,$to])->groupby("tanggal_pre_order")->get(); 
    }
    public function getBulanTahun(){
        $from = Carbon::now()->subMonths(6)->toDateString();
        $to = Carbon::now()->addMonths(6)->toDateString();
        
        return $dates = $this->model->select(DB::raw('DISTINCT EXTRACT(YEAR FROM tanggal_pre_order) as tahun,EXTRACT(MONTH FROM tanggal_pre_order) as bulan'))
                                    ->whereBetween("tanggal_pre_order",[$from,$to])
                                    ->groupby("tanggal_pre_order")
                                    ->get(); 
    }
    public function getPemasukanTotalHarian($date){
        return $this->model->select(DB::raw('SUM(COALESCE(total_bayar_akhir, 0)) AS pemasukan'))
                            ->where("status",7)
                            ->where("tanggal_pre_order", $date)
                            ->first(); 
    }
    public function getPemasukanTotalBulanan($month, $year){
        return $this->model->select(DB::raw('SUM(COALESCE(total_bayar_akhir, 0)) AS pemasukan'))
                            ->whereRaw("status=7 AND EXTRACT(MONTH FROM tanggal_pre_order)=? AND EXTRACT(YEAR FROM tanggal_pre_order)=?", [$month, $year])
                            ->first(); 
    }

    public function getResellerBydate($date){
        $messages = collect($this->model->distinct()
                                        ->where("tanggal_pre_order",$date)
                                        ->where('nomor_invoice','like', '%VGRES%')
                                        ->where('status', 7)
                                        ->get()
                );

        $messagesUnique = $messages->unique('id_user');
        return $messagesUnique->values()->all();
    }
}
?>