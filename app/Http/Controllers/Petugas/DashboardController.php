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

        // Hitung notifikasi belum dibaca
        $unreadCount = Notification::where(function ($query) use ($petugasId) {
            $query->where('user_id', $petugasId)
                ->orWhereNull('user_id')
                ->orWhere('user_id', 0);
        })
            ->where('role_target', 'petugas')
            ->where('is_read', 0)
            ->count();

        // Ambil 5 notifikasi terbaru (yang belum dibaca lebih dulu)
        $recentNotifications = Notification::where(function ($query) use ($petugasId) {
            $query->where('user_id', $petugasId)
                ->orWhereNull('user_id')
                ->orWhere('user_id', 0);
        })
            ->where('role_target', 'petugas')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Booking aktif (sudah ada)
        $bookings = Booking::where('status', 'in_progress')
            ->where(function ($query) use ($petugasId) {
                $query->where('petugas_id', $petugasId)
                    ->orWhereNull('petugas_id');
            })
            ->with(['user', 'layanan'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return view('petugas.dashboard', compact('unreadCount', 'recentNotifications', 'bookings'));
    }
}
