<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pending = Konsultasi::where('status', 'pending')
            ->orderBy('tanggal_janji', 'asc')
            ->orderBy('jam_janji', 'asc')
            ->get();

        $diterima = Konsultasi::where('status', 'diterima')
            ->orderBy('tanggal_janji', 'asc')
            ->orderBy('jam_janji', 'asc')
            ->get();

        $user = Auth::user(); // Ambil data dokter yang login

        // Ambil list notifikasi milik dokter ini
        $notifications = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('role_target', 'dokter'); // Agar notifikasi role-based juga muncul
        })
            ->where(function ($q) {
                $q->where('title', 'like', '%Konsultasi%')
                    ->orWhere('message', 'like', '%Konsultasi%');
            })
            ->latest()
            ->take(5)
            ->get();

        // Hitung unread yang BENAR-BENAR milik dokter ini
        $unreadCount = Notification::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('role_target', 'dokter');
        })
            ->where('is_read', false)
            ->count();

        return view('dokter.dashboard', compact('pending', 'diterima', 'notifications', 'unreadCount'));
    }
}
