<?php

namespace App\Http\Middleware\RoleMiddleware;

use Closure;
use Auth;

class KurirMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // KODE ROLE
        // 1 = Penjual
        // 2 = Pembeli
        // 3 = Petani
        // 4 = Reseller
        // 5 = Kurir
        if(Auth::user()->role == 5)
        {
            return $next($request);
        }
        else
        {
            return redirect('/home');
        }
    }
}
