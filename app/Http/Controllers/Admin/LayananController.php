<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\JenisHewan;
use App\Models\LayananHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanan = Layanan::with(['hargas' => function ($query) {
            $query->with('jenisHewan');
        }])->latest()->get();

        $jenisHewan = JenisHewan::where('aktif', 'ya')
            ->orderBy('nama')
            ->get();

        return view('admin.layanan.index', compact('layanan', 'jenisHewan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_layanan' => 'required|string|max:100',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Upload gambar
            $gambarName = null;
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $gambarName = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
                
                // Simpan ke public/storage/layanan
                $destinationPath = public_path('storage/layanan');
                
                // Buat folder jika belum ada
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                
                // Pindahkan file
                $gambar->move($destinationPath, $gambarName);
                
                Log::info("File uploaded to: " . $destinationPath . '/' . $gambarName);
            }

            // Simpan layanan
            $layanan = Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'gambar' => $gambarName,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.layanan.atur-harga', $layanan->id)
                ->with('success', 'Layanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $layanan = Layanan::with('hargas.jenisHewan')->findOrFail($id);
        return view('admin.layanan.show', compact('layanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            return view('admin.layanan.edit', compact('layanan'));
        } catch (\Exception $e) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Layanan tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_layanan' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'nama_layanan' => $request->nama_layanan,
                'deskripsi' => $request->deskripsi,
            ];

            // Update gambar jika ada
            if ($request->hasFile('gambar')) {
                $destinationPath = public_path('storage/layanan');
                
                // Hapus gambar lama
                if ($layanan->gambar && file_exists($destinationPath . '/' . $layanan->gambar)) {
                    unlink($destinationPath . '/' . $layanan->gambar);
                    Log::info("Old image deleted: " . $destinationPath . '/' . $layanan->gambar);
                }

                // Upload gambar baru
                $gambar = $request->file('gambar');
                $gambarName = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
                
                // Pastikan folder ada
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                
                $gambar->move($destinationPath, $gambarName);
                $data['gambar'] = $gambarName;
                
                Log::info("New image uploaded to: " . $destinationPath . '/' . $gambarName);
            }

            $layanan->update($data);

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui layanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);

        // Cek apakah layanan digunakan di booking
        if ($layanan->bookings()->exists()) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Layanan tidak dapat dihapus karena sudah digunakan dalam booking.');
        }

        try {
            // Hapus gambar
            if ($layanan->gambar) {
                $imagePath = public_path('storage/layanan/' . $layanan->gambar);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    Log::info("Image deleted from: " . $imagePath);
                }
            }

            // Hapus harga terkait
            $layanan->hargas()->delete();

            // Hapus layanan
            $layanan->delete();

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Layanan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Destroy error: ' . $e->getMessage());
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Gagal menghapus layanan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form atur harga
     */
    public function aturHarga($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            $jenisHewan = JenisHewan::where('aktif', 'ya')
                ->orderBy('nama')
                ->get();

            // Ambil harga yang sudah ada
            $hargas = LayananHarga::where('layanan_id', $id)
                ->get()
                ->keyBy('jenis_hewan_id');

            return view('admin.layanan.atur-harga', compact('layanan', 'jenisHewan', 'hargas'));
        } catch (\Exception $e) {
            return redirect()->route('admin.layanan.index')
                ->with('error', 'Gagal memuat halaman: ' . $e->getMessage());
        }
    }

    /**
     * Simpan/update harga
     */
    public function simpanHarga(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'harga.*' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            foreach ($request->harga as $jenis_hewan_id => $harga) {
                if ($harga !== null && $harga > 0) {
                    LayananHarga::updateOrCreate(
                        [
                            'layanan_id' => $layanan->id,
                            'jenis_hewan_id' => $jenis_hewan_id,
                        ],
                        [
                            'harga_per_hari' => $harga,
                        ]
                    );
                } else {
                    // Hapus jika harga kosong
                    LayananHarga::where('layanan_id', $layanan->id)
                        ->where('jenis_hewan_id', $jenis_hewan_id)
                        ->delete();
                }
            }

            return redirect()->route('admin.layanan.index')
                ->with('success', 'Harga layanan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan harga: ' . $e->getMessage())
                ->withInput();
        }
    }
}