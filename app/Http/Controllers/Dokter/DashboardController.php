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

        // TAMBAHKAN FILTER KONSULTASI DI SINI
        $notifications = Notification::where('role_target', 'admin')
            ->where(function($q) {
                $q->where('title', 'like', '%Konsultasi%')
                  ->orWhere('message', 'like', '%Konsultasi%');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // HITUNG UNREAD KHUSUS KONSULTASI
        $unreadCount = Notification::where('role_target', 'admin')
            ->where('is_read', false)
            ->where(function($q) {
                $q->where('title', 'like', '%Konsultasi%')
                  ->orWhere('message', 'like', '%Konsultasi%');
            })
            ->count();

        return view('dokter.dashboard', compact('pending', 'diterima', 'notifications', 'unreadCount'));
    }
}