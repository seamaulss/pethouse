<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Konsultasi;
use App\Models\Notification;

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
        
        // Ambil notifikasi untuk user - PASTIKAN INI DIPANGGIL!
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return view('user.dashboard', compact(
            'user', 
            'bookingCount', 
            'konsultasiCount', 
            'bookingAktif', 
            'notifications', 
            'unreadCount'
        ));
    }
}