<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPetugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $petugasId = Auth::id();

        // Ambil riwayat perawatan petugas
        $riwayat = RiwayatPetugas::with('booking')
            ->where('petugas_id', $petugasId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.profile', [
            'user' => Auth::user(),
            'riwayat' => $riwayat
        ]);
    }
}