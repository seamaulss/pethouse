<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    /**
     * Constructor - Apply admin middleware
     */
    public function __construct()
    {
        // Middleware admin akan dihandle oleh route group
        // Jadi tidak perlu apply lagi di sini
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = HeroSlider::orderBy('urutan')->get();
        return view('admin.hero.index', compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:500',
            'tombol_text' => 'nullable|string|max:50',
            'tombol_link' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Siapkan data
        $data = $request->only(['judul', 'subjudul', 'tombol_text', 'tombol_link', 'urutan']);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan di public/storage/hero
            $path = $request->file('gambar')->storeAs('hero', $filename, 'public');
            $data['gambar'] = $filename;
        }

        // Simpan ke database
        HeroSlider::create($data);

        return redirect()->route('admin.hero.index')
            ->with('success', 'Slide hero berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak digunakan di admin panel, redirect ke index
        return redirect()->route('admin.hero.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hero = HeroSlider::findOrFail($id);
        return view('admin.hero.edit', compact('hero'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hero = HeroSlider::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:500',
            'tombol_text' => 'nullable|string|max:50',
            'tombol_link' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Siapkan data
        $data = $request->only(['judul', 'subjudul', 'tombol_text', 'tombol_link', 'urutan']);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($hero->gambar && Storage::disk('public')->exists('hero/' . $hero->gambar)) {
                Storage::disk('public')->delete('hero/' . $hero->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan di public/storage/hero
            $path = $request->file('gambar')->storeAs('hero', $filename, 'public');
            $data['gambar'] = $filename;
        }

        // Update data
        $hero->update($data);

        return redirect()->route('admin.hero.index')
            ->with('success', 'Slide hero berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hero = HeroSlider::findOrFail($id);

        // Hapus gambar jika ada
        if ($hero->gambar && Storage::disk('public')->exists('hero/' . $hero->gambar)) {
            Storage::disk('public')->delete('hero/' . $hero->gambar);
        }

        // Hapus dari database
        $hero->delete();

        return redirect()->route('admin.hero.index')
            ->with('success', 'Slide hero berhasil dihapus.');
    }
}