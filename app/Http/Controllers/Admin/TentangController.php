<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TentangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        $tentang = Tentang::latest()->get();
        return view('admin.tentang.index', compact('tentang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.tentang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Simpan data
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
        ];

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            // Validasi ekstensi file
            $ext = $request->file('gambar')->getClientOriginalExtension();
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array(strtolower($ext), $allowed)) {
                return redirect()->back()->withErrors([
                    'gambar' => 'Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau WEBP.'
                ]);
            }

            // Generate nama file unik
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $ext;
            
            // Pastikan folder public/storage/tentang ada
            $folderPath = public_path('storage/tentang');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }
            
            // Simpan file ke public/storage/tentang
            $request->file('gambar')->move($folderPath, $filename);
            
            // Simpan hanya nama file ke database
            $data['gambar'] = $filename;
        }

        // Simpan ke database
        Tentang::create($data);

        return redirect()->route('admin.tentang.index')
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        $tentang = Tentang::findOrFail($id);
        return view('admin.tentang.edit', compact('tentang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Temukan data yang akan diupdate
        $tentang = Tentang::findOrFail($id);
        
        // Data untuk diupdate
        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
        ];

        // Handle hapus gambar jika checkbox dicentang
        if ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            // Hapus gambar lama jika ada
            if ($tentang->gambar && File::exists(public_path('storage/tentang/' . $tentang->gambar))) {
                File::delete(public_path('storage/tentang/' . $tentang->gambar));
            }
            $data['gambar'] = null;
        }
        // Handle upload gambar baru
        else if ($request->hasFile('gambar')) {
            // Validasi ekstensi file
            $ext = $request->file('gambar')->getClientOriginalExtension();
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array(strtolower($ext), $allowed)) {
                return redirect()->back()->withErrors([
                    'gambar' => 'Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau WEBP.'
                ]);
            }

            // Hapus gambar lama jika ada
            if ($tentang->gambar && File::exists(public_path('storage/tentang/' . $tentang->gambar))) {
                File::delete(public_path('storage/tentang/' . $tentang->gambar));
            }

            // Generate nama file unik
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $ext;
            
            // Pastikan folder public/storage/tentang ada
            $folderPath = public_path('storage/tentang');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }
            
            // Simpan file ke public/storage/tentang
            $request->file('gambar')->move($folderPath, $filename);
            
            // Simpan nama file ke data
            $data['gambar'] = $filename;
        }

        // Update data di database
        $tentang->update($data);

        return redirect()->route('admin.tentang.index')
            ->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('login');
        }

        $tentang = Tentang::findOrFail($id);
        
        // Hapus gambar dari public/storage/tentang jika ada
        if ($tentang->gambar && File::exists(public_path('storage/tentang/' . $tentang->gambar))) {
            File::delete(public_path('storage/tentang/' . $tentang->gambar));
        }
        
        // Hapus data dari database
        $tentang->delete();

        return redirect()->route('admin.tentang.index')
            ->with('success', 'Konten berhasil dihapus.');
    }

    /**
     * Helper method untuk mengecek dan membuat folder jika belum ada
     */
    private function ensureFolderExists()
    {
        $folderPath = public_path('storage/tentang');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        return $folderPath;
    }
}