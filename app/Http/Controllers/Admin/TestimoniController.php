<?php
// app/Http/Controllers/Admin/TestimoniController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $edit_data = null;
    
    // Jika ada parameter edit
    if ($request->has('edit')) {
        $edit_data = Testimoni::find($request->edit);
    }

    $testimoni = Testimoni::orderBy('created_at', 'desc')->get();
    $jenisHewanList = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();
    
    return view('admin.testimoni.index', compact('testimoni', 'edit_data', 'jenisHewanList'));
}

    /**
     * Store a newly created resource or update existing.
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'isi_testimoni' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'foto_hewan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->only([
            'nama_pemilik', 
            'nama_hewan', 
            'jenis_hewan', 
            'isi_testimoni', 
            'rating', 
            'status'
        ]);

        // Handle upload foto
        if ($request->hasFile('foto_hewan')) {
            $file = $request->file('foto_hewan');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            
            // Simpan file ke storage
            $path = $file->storeAs('testimoni', $filename, 'public');
            $data['foto_hewan'] = $filename;
        }

        // Cek apakah update atau insert baru
        if ($request->id) {
            // Update existing
            $testimoni = Testimoni::findOrFail($request->id);
            
            // Hapus foto lama jika ada foto baru
            if ($request->hasFile('foto_hewan') && $testimoni->foto_hewan) {
                Storage::disk('public')->delete('testimoni/' . $testimoni->foto_hewan);
            }
            
            $testimoni->update($data);
            $message = 'Testimoni berhasil diperbarui!';
        } else {
            // Create new
            Testimoni::create($data);
            $message = 'Testimoni berhasil ditambahkan!';
        }

        return redirect()->route('admin.testimoni.index')
                         ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $testimoni = Testimoni::findOrFail($id);
        
        // Hapus foto jika ada
        if ($testimoni->foto_hewan) {
            Storage::disk('public')->delete('testimoni/' . $testimoni->foto_hewan);
        }
        
        $testimoni->delete();
        
        return redirect()->route('admin.testimoni.index')
                         ->with('success', 'Testimoni berhasil dihapus!');
    }
}