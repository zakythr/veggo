<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('pembeli');
    }
}
