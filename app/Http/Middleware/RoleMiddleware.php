<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role sesuai
        if (Auth::user()->role !== $roles) {
            return redirect('/login')->with('error', 'Akses ditolak.');
        }


        return $next($request);
    }
}
