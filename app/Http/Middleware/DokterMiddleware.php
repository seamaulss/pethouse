<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DokterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'dokter') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya untuk dokter.');
        }

        return $next($request);
    }
}