<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaPaketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('kurir');
    }

    public function paketAkanDikirim()
    {
        
    }

    public function paketDalamProses()
    {
        
    }

    public function paketSelesaiDikirim()
    {

    }
}
