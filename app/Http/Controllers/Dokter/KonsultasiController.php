<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\KonsultasiBalasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonsultasiController extends Controller
{
    public function show($id)
    {
        $konsultasi = Konsultasi::with(['user', 'balasan', 'dokter'])
            ->findOrFail($id);
        
        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:terima,selesai',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        
        // Update status berdasarkan aksi
        if ($request->aksi === 'terima') {
            $konsultasi->status = 'diterima';
            $konsultasi->dokter_id = auth()->id(); // Tetapkan dokter yang menerima
        } else {
            $konsultasi->status = 'selesai';
        }
        
        $konsultasi->save();

        return redirect()
            ->route('dokter.konsultasi.show', $id)
            ->with('success', "Status konsultasi berhasil diperbarui menjadi " . ($request->aksi === 'terima' ? 'diterima' : 'selesai'));
    }

    public function kirimBalasan(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|min:3',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);

        // Pastikan konsultasi sudah diterima
        if ($konsultasi->status === 'pending') {
            return back()->with('error', 'Konsultasi belum diterima.');
        }

        DB::transaction(function () use ($konsultasi, $request) {
            // Simpan balasan ke tabel balasan
            KonsultasiBalasan::create([
                'konsultasi_id' => $konsultasi->id,
                'pengirim' => 'dokter',
                'isi' => $request->balasan,
                'dibaca_user' => false,
                'dibaca_dokter' => true,
            ]);

            // Update balasan dokter di tabel konsultasi
            $konsultasi->update([
                'balasan_dokter' => $request->balasan,
            ]);
        });

        return redirect()
            ->route('dokter.konsultasi.show', $id)
            ->with('success', 'Balasan berhasil dikirim.');
    }
}