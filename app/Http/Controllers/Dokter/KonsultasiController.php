<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonsultasiController extends Controller
{
    /**
     * Menampilkan daftar semua konsultasi (Halaman Index)
     */
    public function index()
    {
        // Menggunakan first() agar yang diambil hanya 1 data (Model), bukan Collection
        $konsultasi = Konsultasi::with('user')
            ->where('dokter_id', auth()->id())
            ->orWhereNull('dokter_id')
            ->orderBy('created_at', 'desc')
            ->first(); // <-- Ubah dari get() ke first()

        if (!$konsultasi) {
            return redirect()->back()->with('error', 'Belum ada data konsultasi.');
        }

        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    /**
     * Menampilkan detail satu konsultasi (Halaman Show)
     */
    public function show($id)
    {
        $konsultasi = Konsultasi::with(['user', 'dokter'])->findOrFail($id);
        return view('dokter.konsultasi.show', compact('konsultasi'));
    }

    /**
     * Update status terima atau selesai
     */
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

    /**
     * Method tambahan sesuai dengan Route::post('/{id}/balas')
     */
    public function kirimBalasan(Request $request, $id)
    {
        $request->validate([
            'balasan_dokter' => 'required|string|min:5',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);

        $konsultasi->update([
            'balasan_dokter' => $request->balasan_dokter,
            'status' => 'selesai'
        ]);

        return redirect()->route('dokter.konsultasi.show', $id)
            ->with('success', 'Balasan berhasil dikirim.');
    }
}
