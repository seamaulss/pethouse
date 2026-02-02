<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    // Fungsi untuk cek admin
    private function checkAdmin()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        
        return true;
    }

    // Menampilkan semua data dengan search
    public function index(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;
        
        $search = $request->input('search');
        
        $query = JenisHewan::query();
        
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
        
        // GUNAKAN paginate() BUKAN get()
        $jenisHewan = $query->orderBy('nama')->paginate(10);
        
        return view('admin.jenis-hewan.index', [
            'jenisHewan' => $jenisHewan,
            'search' => $search
        ]);
    }

    // Menampilkan form tambah
    public function create()
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;
        
        return view('admin.jenis-hewan.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;
        
        $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_hewan,nama',
        ]);

        DB::beginTransaction();
        try {
            JenisHewan::create([
                'nama' => $request->nama,
                'aktif' => 'ya'
            ]);

            DB::commit();
            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan jenis hewan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    // Menampilkan form edit
    public function edit($id)
{
    $check = $this->checkAdmin();
    if ($check !== true) return $check;

    $jenisHewan = JenisHewan::findOrFail($id);

    return view('admin.jenis-hewan.edit', [
        'jenisHewan' => $jenisHewan
    ]);
}

    // Mengupdate data
    public function update(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;
        
        $jenis = JenisHewan::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:50|unique:jenis_hewan,nama,' . $id,
            'aktif' => 'required|in:ya,tidak'
        ]);

        DB::beginTransaction();
        try {
            $jenis->update([
                'nama' => $request->nama,
                'aktif' => $request->aktif
            ]);

            DB::commit();
            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui jenis hewan: ' . $e->getMessage());
        }
    }

    // Menghapus data
    public function destroy($id)
    {
        $check = $this->checkAdmin();
        if ($check !== true) return $check;
        
        $jenis = JenisHewan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $jenis->delete();
            
            DB::commit();
            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus jenis hewan: ' . $e->getMessage());
        }
    }
}