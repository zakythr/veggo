<?php

namespace App\Http\Middleware\RoleMiddleware;

use Closure;
use Auth;

class ResellerMiddleware
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
        if(Auth::user()->role == 4)
        {
            return $next($request);
        }
        else
        {
            return redirect('/home');
        }
    }
}
