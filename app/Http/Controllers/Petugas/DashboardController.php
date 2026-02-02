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

        // Ambil booking dengan status in_progress
        $bookings = Booking::where('status', 'in_progress')
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('petugas.dashboard', compact('unreadCount', 'bookings'));
    }
}