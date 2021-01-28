<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // KODE ROLE
        // 1 = Penjual
        // 2 = Pembeli
        // 3 = Petani
        // 4 = Reseller
        // 5 = Kurir

        $getRole = Auth::user()->role;

        switch ($getRole) {
            case 1:
                return redirect('Penjual/Home');
            case 2:
                return redirect('Pembeli/Home');
            case 3:
                return redirect('Petani/Home');
            case 4:
                return redirect('Reseller/Home');
            case 5:
                return redirect('Kurir/Home');
        }
    }

    public function nyoba()
    {
        
    }
}
