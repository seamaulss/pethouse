<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki role 'admin'
        if (auth()->user()->role !== 'admin') {
            // Redirect ke halaman dashboard biasa
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak! Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}