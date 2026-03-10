<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kapasitas;

class KapasitasController extends Controller
{
    public function index()
    {
        $kapasitas = DB::table('kapasitas')
            ->join('layanan', 'kapasitas.layanan_id', '=', 'layanan.id')
            ->select('kapasitas.*', 'layanan.nama_layanan')
            ->get();
            
        $layanan = DB::table('layanan')->get();
        return view('admin.kapasitas.index', compact('kapasitas', 'layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required',
            'jenis_hewan' => 'required',
            'ukuran_hewan' => 'required',
            'max_kapasitas' => 'required|numeric|min:1',
        ]);

        DB::table('kapasitas')->insert([
            'layanan_id' => $request->layanan_id,
            'jenis_hewan' => $request->jenis_hewan,
            'ukuran_hewan' => $request->ukuran_hewan,
            'max_kapasitas' => $request->max_kapasitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kapasitas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        DB::table('kapasitas')->where('id', $id)->update([
            'max_kapasitas' => $request->max_kapasitas,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kapasitas berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::table('kapasitas')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data dihapus');
    }
}