<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonsultasiController extends Controller
{
    public function show($id)
    {
        // Gunakan eager loading untuk optimasi query
        $konsultasi = Konsultasi::with(['user', 'dokter'])->findOrFail($id);
        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:terima,selesai',
            'balasan_dokter' => 'required_if:aksi,selesai|nullable|string|min:5',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $konsultasi) {
                if ($request->aksi === 'terima') {
                    $konsultasi->update([
                        'status' => 'diterima',
                        'dokter_id' => auth()->id(),
                    ]);
                } else {
                    // Saat selesai, simpan rekam medis ke kolom balasan_dokter
                    $konsultasi->update([
                        'status' => 'selesai',
                        'balasan_dokter' => $request->balasan_dokter,
                    ]);
                }
            });

            $statusMsg = $request->aksi === 'terima' ? 'diterima' : 'diselesaikan';
            return redirect()->route('dokter.konsultasi.show', $id)
                ->with('success', "Konsultasi berhasil $statusMsg.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}