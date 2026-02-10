<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil konsultasi yang baru masuk (Booking baru)
        $pending = Konsultasi::where('status', 'pending')
            ->orderBy('tanggal_janji', 'asc')
            ->orderBy('jam_janji', 'asc')
            ->get();

        // 2. Ambil konsultasi yang sudah datang/dikonfirmasi (Sedang diperiksa)
        $diterima = Konsultasi::where('status', 'diterima')
            ->orderBy('tanggal_janji', 'asc')
            ->orderBy('jam_janji', 'asc')
            ->get();

        return view('dokter.dashboard', compact('pending', 'diterima'));
    }
}