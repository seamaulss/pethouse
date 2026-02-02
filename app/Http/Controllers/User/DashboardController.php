<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data untuk dashboard
        $bookingCount = Booking::where('user_id', $user->id)->count();
        $konsultasiCount = Konsultasi::where('user_id', $user->id)->count();
        $bookingAktif = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'diterima', 'in_progress'])
            ->latest()
            ->limit(3)
            ->get();
            
        return view('user.dashboard', compact('user', 'bookingCount', 'konsultasiCount', 'bookingAktif'));
    }
}