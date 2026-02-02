<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galeri = Galeri::latest()->get();
        return view('admin.galeri.index', compact('galeri'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Upload foto ke public/storage/galeri
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Simpan ke storage/galeri (akan ter-link ke public/storage/galeri)
            $path = $image->storeAs('galeri', $imageName, 'public');
            $validated['foto'] = $path; // Hasil: 'galeri/nama_file.jpg'
        }

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil ditambahkan ke galeri!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)  // Ubah menjadi $id, bukan Galeri $galeri
    {
        $galeri = Galeri::findOrFail($id);
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)  // Ubah menjadi $id, bukan Galeri $galeri
    {
        $galeri = Galeri::findOrFail($id);
        
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari public/storage
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            
            $image = $request->file('foto');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Simpan ke public/storage/galeri
            $path = $image->storeAs('galeri', $imageName, 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)  // Ubah menjadi $id, bukan Galeri $galeri
    {
        $galeri = Galeri::findOrFail($id);
        
        // Hapus foto dari public/storage
        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus!');
    }
}