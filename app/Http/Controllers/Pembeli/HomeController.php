<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pembeli');
    }    
    public function index()
    {
        return redirect('/Pembeli/Etalase');
    }
}
