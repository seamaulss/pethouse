<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki role 'user'
        if (auth()->user()->role !== 'user') {
            // Redirect ke halaman sesuai role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak! Halaman ini hanya untuk user.');
            }
            // Jika role dokter, arahkan ke dashboard dokter (jika ada)
            return redirect('/')
                ->with('error', 'Akses ditolak! Halaman ini hanya untuk user.');
        }

        return $next($request);
    }
}