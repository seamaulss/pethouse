<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\KonsultasiBalasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konsultasi = Konsultasi::latest()->paginate(10);
        
        return view('admin.konsultasi.index', compact('konsultasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        
        return view('admin.konsultasi.edit', compact('konsultasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,selesai',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        $oldStatus = $konsultasi->status;
        
        $konsultasi->update([
            'status' => $request->status,
        ]);

        // Generate WA message
        $waMessage = $konsultasi->generateWaMessage();
        $waLink = "https://wa.me/{$konsultasi->wa_link}?text={$waMessage}";

        // Log activity menggunakan logger() helper
        if ($oldStatus !== $request->status) {
            logger('Status konsultasi diubah', [
                'admin_id' => Auth::id(),
                'konsultasi_id' => $konsultasi->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'time' => now()
            ]);
        }

        return redirect()->route('admin.konsultasi.edit', $id)
            ->with('success', 'Status berhasil diperbarui!')
            ->with('wa_link', $waLink);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Hapus balasan terlebih dahulu
            if (class_exists(KonsultasiBalasan::class)) {
                $konsultasi->balasan()->delete();
            }
            
            // Hapus konsultasi
            $konsultasi->delete();
            
            DB::commit();
            
            return redirect()->route('admin.konsultasi.index')
                ->with('success', 'Konsultasi berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Gunakan logger() untuk error
            logger()->error('Gagal menghapus konsultasi', [
                'error' => $e->getMessage(),
                'konsultasi_id' => $id,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->route('admin.konsultasi.index')
                ->with('error', 'Gagal menghapus konsultasi. Silakan coba lagi.');
        }
    }

    /**
     * Show detail konsultasi.
     */
    public function show(string $id)
    {
        $konsultasi = Konsultasi::with(['balasan', 'dokter'])->findOrFail($id);
        
        return view('admin.konsultasi.show', compact('konsultasi'));
    }

    /**
     * Update balasan dokter.
     */
    public function updateBalasan(Request $request, string $id)
    {
        $request->validate([
            'balasan_dokter' => 'required|string',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        
        $konsultasi->update([
            'balasan_dokter' => $request->balasan_dokter,
        ]);

        // Juga buat entri di tabel balasan jika model tersedia
        if (class_exists(KonsultasiBalasan::class)) {
            $konsultasi->balasan()->create([
                'pengirim' => 'dokter',
                'isi' => $request->balasan_dokter,
                'dibaca_user' => false,
            ]);
        }

        return redirect()->route('admin.konsultasi.edit', $id)
            ->with('success', 'Balasan dokter berhasil disimpan!');
    }

    /**
     * Hapus konsultasi dengan method GET (untuk kompatibilitas dengan PHP native)
     */
    public function hapus(string $id)
    {
        return $this->destroy($id);
    }
}