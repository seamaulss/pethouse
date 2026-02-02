<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login dan role-nya adalah petugas
        if (Auth::check() && Auth::user()->role === 'petugas') {
            return $next($request);
        }

        // Jika tidak, redirect ke halaman login dengan pesan error
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}