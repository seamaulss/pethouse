<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\CatatanMedis;
use App\Models\User;
use App\Models\Booking;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanMedisController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk mendapatkan pemilik dari riwayat konsultasi/booking
        $pemilikList = User::select(
                'users.id',
                'users.username as nama_pemilik',
                DB::raw("COALESCE(k.jenis_hewan, b.jenis_hewan) as jenis_hewan")
            )
            ->leftJoin('konsultasi as k', function($join) {
                $join->on('users.id', '=', 'k.user_id')
                    ->whereIn('k.status', ['selesai', 'diterima']);
            })
            ->leftJoin('booking as b', function($join) {
                $join->on('users.id', '=', 'b.user_id')
                    ->whereIn('b.status', ['selesai', 'in_progress']);
            })
            ->where(function($query) {
                $query->whereNotNull('k.id')
                      ->orWhereNotNull('b.id');
            })
            ->groupBy('users.id', 'users.username')
            ->addSelect(DB::raw('COALESCE(k.jenis_hewan, b.jenis_hewan) as jenis_hewan'))
            ->orderBy('users.username')
            ->get();

        return view('dokter.catatan_medis.index', compact('pemilikList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'nama_hewan' => 'required|string|max:100',
            'jenis_hewan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'diagnosis' => 'nullable|string',
            'resep' => 'nullable|string',
            'vaksin' => 'nullable|string|max:100',
            'catatan_lain' => 'nullable|string',
        ]);

        // Tambahkan dokter yang sedang login
        $validated['dokter_id'] = auth()->id();

        // Simpan catatan medis
        CatatanMedis::create($validated);

        return redirect()
            ->route('dokter.catatan-medis.index')
            ->with('success', "Catatan medis untuk <strong>{$validated['nama_hewan']}</strong> ({$validated['jenis_hewan']}) berhasil disimpan!");
    }
}