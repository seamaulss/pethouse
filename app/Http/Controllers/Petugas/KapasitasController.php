<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kapasitas;
use App\Models\Booking;
use Carbon\Carbon;

class KapasitasController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        
        // Ambil semua master kapasitas
        $kapasitas = Kapasitas::with('layanan')->get();

        // Tambahkan info keterisian secara dinamis ke setiap data kapasitas
        $dataStatus = $kapasitas->map(function ($item) use ($now) {
            $terisi = Booking::where('layanan_id', $item->layanan_id)
                ->where('jenis_hewan', $item->jenis_hewan)
                ->where('ukuran_hewan', $item->ukuran_hewan)
                ->whereIn('status', ['in_progress', 'perpanjangan'])
                ->where(function ($q) use ($now) {
                    $q->whereDate('tanggal_masuk', '<=', $now)
                      ->whereDate('tanggal_keluar', '>=', $now);
                })->count();

            $item->terisi = $terisi;
            $item->sisa = $item->max_kapasitas - $terisi;
            $item->persentase = ($item->max_kapasitas > 0) ? ($terisi / $item->max_kapasitas) * 100 : 0;
            
            return $item;
        });

        return view('petugas.kapasitas.index', compact('dataStatus'));
    }
}