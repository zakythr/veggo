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
use App\Repositories\TanggalRepository;
use App\Repositories\BarangTanggalRepository;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;
use Auth;
use Uuid;


class PengaturanController extends Controller
{

    protected $produkRepository, $userRepository, $kategoriRepository, $bobotKemasanRepository, $baseKategoriRepository, $produkKemasanRepository, $produkGroupRepository, $fotoProdukRepository, $inventarisRepository, $tanggalRepository, $barangTanggalRepository;

    public function __construct(ProdukRepository $produkRepository, UserRepository $userRepository, KategoriRepository $kategoriRepository, BobotKemasanRepository $bobotKemasanRepository, BaseKategoriRepository $baseKategoriRepository, ProdukKemasanRepository $produkKemasanRepository, ProdukGroupRepository $produkGroupRepository, FotoProdukRepository $fotoProdukRepository, InventarisRepository $inventarisRepository, TanggalRepository $tanggalRepository, BarangTanggalRepository $barangTanggalRepository)
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
        $this->tanggalRepository = $tanggalRepository;
        $this->barangTanggalRepository = $barangTanggalRepository;
        
        $this->middleware('auth');
        $this->middleware('penjual');

    }    

    public function tanggal($tanggal){
        // dd($tanggal);
        $timestamp = getdate(strtotime($tanggal));
        
        $month = $timestamp['mon']; 			     
        $year = $timestamp['year'];
        
        
        // Create array containing abbreviations of days of week.
        $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

        // What is the first day of the month in question?
        $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

        // How many days does this month contain?
        $numberDays = date('t',$firstDayOfMonth);

        // Retrieve some information about the first day of the
        // month in question.
        $dateComponents = getdate($firstDayOfMonth);

        // What is the name of the month in question?
        $monthName = $dateComponents['month'];

        // What is the index value (0-6) of the first day of the
        // month in question.
        $dayOfWeek = $dateComponents['wday'];

        // Create the table tag opener and day headers
        
        $datetoday = date('Y-m-d');
        
        
        $calendar = "<center><h2>$monthName $year</h2>"; 
        
        $calendar.= "<a class='btn btn-xs btn-success' href='/Penjual/Pengaturan/Tanggal/".date('Y-m-d',mktime(0, 0, 0, $month-1, 1, $year))."'>Bulan Sebelumnya</a> ";
    
        $calendar.= "<a class='btn btn-xs btn-success' href='/Penjual/Pengaturan/Tanggal/".date('Y-m-d')."'>Bulan Sekarang</a> ";
    
        $calendar.= "<a class='btn btn-xs btn-success' href='/Penjual/Pengaturan/Tanggal/".date('Y-m-d',mktime(0, 0, 0, $month+1, 1, $year))."'>Bulan Selanjutnya</a> </center><br>";

        $calendar .= "<table class='table table-bordered' style='overflow-x:auto;'>";
        
            
        $calendar .= "<tr>";

        // Create the calendar headers

        foreach($daysOfWeek as $day) {
            $calendar .= "<th  class='header'>$day</th>";
        } 

        // Create the rest of the calendar

        // Initiate the day counter, starting with the 1st.

        $currentDay = 1;

        $calendar .= "</tr><tr>";

        // The variable $dayOfWeek is used to
        // ensure that the calendar
        // display consists of exactly 7 columns.

        if ($dayOfWeek > 0) { 
            for($k=0;$k<$dayOfWeek;$k++){
                    $calendar .= "<td  class='empty'></td>"; 

            }
        }
        
        
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    
        while ($currentDay <= $numberDays) {

            // Seventh column (Saturday) reached. Start a new row.

            if ($dayOfWeek == 7) {

                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";

            }
            
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            
            $dayname = strtolower(date('l', strtotime($date)));
            $eventNum = 0;
            $today = $date==date('Y-m-d')? "today" : "";
            // dd(date('Y-m-d'));

            // dd($this->tanggalRepository->find_tanggal($date));
            if($this->tanggalRepository->find_tanggal($date)->isEmpty() ){
                if(strtotime($date)<strtotime(date('Y-m-d'))){
                    $calendar.="<td class='$today'><h4> $currentDay</h4> <button  class='btn btn-danger btn-xs' disabled>Tutup</button>";    
                }
                else{
                    $calendar.="<td class='$today'><h4>$currentDay</h4> <a href='/Penjual/Pengaturan/BukaTanggal/".$date."' class='btn btn-danger btn-xs'>Tutup</a>";
                }
            }
            else{
                $calendar.="<td class='$today'><h4><a href='/Penjual/Pengaturan/BukaTanggall/".$date."'>$currentDay</a></h4> <a href='/Penjual/Pengaturan/CloseTanggal/".$date."' class='btn btn-success btn-xs'>Buka</a>";
            }
      
            $calendar .="</td>";
            // Increment counters
    
            $currentDay++;
            $dayOfWeek++;

        }

        // Complete the row of the last week in month, if necessary

        if ($dayOfWeek != 7) { 
        
            $remainingDays = 7 - $dayOfWeek;
                for($l=0;$l<$remainingDays;$l++){
                    $calendar .= "<td class='empty'></td>"; 

            }

        }
        
        $calendar .= "</tr>";

        $calendar .= "</table>";

        return view('penjual.tanggal')->with('data', $calendar);
    }

    public function bukaTanggal($tanggal){
        if($this->tanggalRepository->find_tanggal_not_status($tanggal)->isEmpty() ){
            $this->tanggalRepository->create($tanggal, 1);
        }
        else{
            $this->tanggalRepository->update($tanggal, 1);
        }
        return redirect('/Penjual/Pengaturan/Tanggal/'.date("Y-m-d"));
    }

    public function submitTanggal(Request $request){
        $this->barangTanggalRepository->deleteByTanggal($request->tanggal);
        if($request->etalase==null){
            return redirect()->back();
        }
        foreach($request->etalase as $updateBarang){
            $this->barangTanggalRepository->create($request->tanggal, $updateBarang);
        }  
        return redirect('/Penjual/Pengaturan/Tanggal/'.date("Y-m-d"));
    }

    public function closeTanggal($tanggal){
        $this->barangTanggalRepository->deleteByTanggal($tanggal);
        $this->tanggalRepository->update($tanggal, 0);
        return redirect()->back();
    }

    public function tambahUser(){
        return view('penjual.tambah_user');
    }
    public function _tambahUser(Request $request){
        $id = Uuid::generate(4)->string;
        $this->userRepository->create([
            'id' => $id,
            'name' => $request['name'],
            'email' => $request['email'],
            'nomor_hp'=> $request['nomor_hp'],
            'password' => Hash::make($request['password']),
            'role' => $request['role']
        ]);
        return redirect()->back();
    }

    public function bukaTanggall($tanggal){
        $data=[
            'barang' => $this->produkRepository->all(),
            'tanggal' => $tanggal
        ];
        return view('penjual.buka_tanggal')->with(compact('data'));
        // dd($tanggal);
    }
    
    public function rekening(){
        $data=$this->userRepository->detail(Auth::user()->id);
        // dd($data);
        return view('penjual.edit_rekening')->with(compact('data'));
    }
    public function _rekening(Request $request){
        $this->userRepository->update(Auth::user()->id, [
            'nomor_rek' => $request['nomor_rek'],
            'bank' => $request['bank'],
            'atas_nama'=> $request['atas_nama'],
        ]);
        return redirect()->back();
    }
}
