<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk error Log
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan ini untuk error PDF

class KonsultasiController extends Controller
{
    /**
     * Menampilkan daftar antrean dengan filter.
     */
    public function index(Request $request)
    {
        $query = Konsultasi::with(['user', 'dokter']);

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_konsultasi', 'like', "%$search%")
                    ->orWhere('nama_pemilik', 'like', "%$search%")
                    ->orWhere('nama_hewan', 'like', "%$search%");
            });
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('tanggal_janji', $request->date);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konsultasi = $query->latest()->paginate(10);
        return view('admin.konsultasi.index', compact('konsultasi'));
    }

    /**
     * Export ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Konsultasi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_konsultasi', 'like', "%$search%")
                    ->orWhere('nama_pemilik', 'like', "%$search%");
            });
        }
        if ($request->filled('date')) {
            $query->whereDate('tanggal_janji', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        $pdf = PDF::loadView('admin.konsultasi.pdf', compact('data'))
            ->setPaper('a4', 'landscape');

        // GANTI download() MENJADI stream()
        return $pdf->stream('Laporan-Antrean-Konsultasi.pdf');
    }

    public function show(string $id)
    {
        $konsultasi = Konsultasi::with(['user', 'dokter'])->findOrFail($id);
        return view('admin.konsultasi.show', compact('konsultasi'));
    }

    public function edit(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        return view('admin.konsultasi.edit', compact('konsultasi'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,selesai,batal',
            'balasan_dokter' => 'nullable|string',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        $oldStatus = $konsultasi->status;

        $konsultasi->update([
            'status' => $request->status,
            'balasan_dokter' => $request->balasan_dokter,
        ]);

        if ($oldStatus !== $request->status) {
            logger('Status diperbarui oleh Admin', [
                'id' => $konsultasi->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);
        }

        // Logic WA (Sesuaikan field nomor wa di model Anda)
        $waLink = "https://wa.me/{$konsultasi->nomor_wa}?text=" . urlencode($konsultasi->generateWaMessage());

        return redirect()->route('admin.konsultasi.index')
            ->with('success', 'Data antrean berhasil diperbarui!')
            ->with('wa_link', $waLink);
    }

    public function updateBalasan(Request $request, $id)
    {
        $request->validate(['balasan_dokter' => 'required|string']);
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->update(['balasan_dokter' => $request->balasan_dokter]);

        return back()->with('success', 'Balasan berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        DB::beginTransaction();
        try {
            $konsultasi->delete();
            DB::commit();
            return redirect()->route('admin.konsultasi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.konsultasi.index')->with('error', 'Gagal menghapus data.');
        }
    }
}
