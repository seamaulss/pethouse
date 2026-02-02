<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\DailyLog;

class HewanSayaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua booking milik user ini
        $bookings = Booking::where('user_id', $user->id)
            ->with(['layanan', 'dailyLogs'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
            
        return view('user.hewan-saya.index', compact('user', 'bookings'));
    }
    
    public function logHarian($id)
    {
        $user = Auth::user();
        
        // Cek booking milik user
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['layanan', 'dailyLogs' => function($query) {
                $query->orderBy('tanggal', 'desc')
                      ->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();
            
        return view('user.hewan-saya.log', compact('booking'));
    }
}