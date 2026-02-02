<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil konsultasi pending
        $pending = Konsultasi::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil konsultasi diterima (aktif hari ini)
        $diterima = Konsultasi::with('user')
            ->where('status', 'diterima')
            ->orderBy('tanggal_janji', 'asc')
            ->orderBy('jam_janji', 'asc')
            ->get();

        return view('dokter.dashboard', compact('pending', 'diterima'));
    }
}