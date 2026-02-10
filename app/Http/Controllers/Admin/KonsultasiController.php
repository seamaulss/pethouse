<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function index()
    {
        // Menggunakan eager loading agar query lebih ringan
        $konsultasi = Konsultasi::with(['user', 'dokter'])->latest()->paginate(10);
        return view('admin.konsultasi.index', compact('konsultasi'));
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
            'status' => 'required|in:pending,diterima,selesai',
            'balasan_dokter' => 'nullable|string',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        $oldStatus = $konsultasi->status;
        
        $konsultasi->update([
            'status' => $request->status,
            'balasan_dokter' => $request->balasan_dokter,
        ]);

        // Audit Trail: Mencatat siapa yang merubah data antrean
        if ($oldStatus !== $request->status) {
            logger('Status konsultasi diperbarui oleh Admin', [
                'admin_id' => Auth::id(),
                'konsultasi_id' => $konsultasi->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);
        }

        // Generate WA message otomatis dari logic Model
        $waLink = "https://wa.me/{$konsultasi->wa_link}?text=" . $konsultasi->generateWaMessage();

        return redirect()->route('admin.konsultasi.index')
            ->with('success', 'Data antrean berhasil diperbarui!')
            ->with('wa_link', $waLink);
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
            logger()->error('Gagal menghapus antrean', ['error' => $e->getMessage()]);
            return redirect()->route('admin.konsultasi.index')->with('error', 'Gagal menghapus data.');
        }
    }
}