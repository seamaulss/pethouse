<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $petugasId = Auth::id();
        
        // Hitung notifikasi yang belum dibaca
        $unreadCount = Notification::where(function($query) use ($petugasId) {
            $query->where('user_id', $petugasId)
                  ->orWhereNull('user_id')
                  ->orWhere('user_id', 0);
        })
        ->where('role_target', 'petugas')
        ->where('is_read', 0)
        ->count();

        // AMBIL BOOKING YANG SEDANG in_progress DAN PETUGAS_ID SESUAI ATAU NULL
        $bookings = Booking::where('status', 'in_progress')
            ->where(function($query) use ($petugasId) {
                $query->where('petugas_id', $petugasId)
                      ->orWhereNull('petugas_id');
            })
            ->with(['user', 'layanan'])  // LOAD RELASI UNTUK EFFISIENSI
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('petugas.dashboard', compact('unreadCount', 'bookings'));
    }
}