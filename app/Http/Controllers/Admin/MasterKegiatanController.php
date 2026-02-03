<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKegiatan;
use Illuminate\Http\Request;

class MasterKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kegiatan = MasterKegiatan::orderBy('urutan')->get();
        return view('admin.master-kegiatan.index', compact('kegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
            'urutan' => 'required|integer|min:1',
            'aktif' => 'required|in:ya,tidak',
        ]);

        MasterKegiatan::create($request->all());

        return redirect()->route('admin.master-kegiatan.index')
            ->with('success', 'Master kegiatan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kegiatan = MasterKegiatan::findOrFail($id);
        return view('admin.master-kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:20',
            'urutan' => 'required|integer|min:1',
            'aktif' => 'required|in:ya,tidak',
        ]);

        $kegiatan = MasterKegiatan::findOrFail($id);
        $kegiatan->update($request->all());

        return redirect()->route('admin.master-kegiatan.index')
            ->with('success', 'Master kegiatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kegiatan = MasterKegiatan::findOrFail($id);
        
        // Cek apakah kegiatan sudah digunakan di log
        if ($kegiatan->logs()->count() > 0) {
            return redirect()->route('admin.master-kegiatan.index')
                ->with('error', 'Tidak bisa menghapus karena kegiatan sudah digunakan dalam log!');
        }
        
        $kegiatan->delete();

        return redirect()->route('admin.master-kegiatan.index')
            ->with('success', 'Master kegiatan berhasil dihapus!');
    }
}