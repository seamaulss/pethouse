<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DailyLog;
use App\Models\MasterKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InputLogController extends Controller
{
    /**
     * Tampilkan form input log
     * Parameter menggunakan $booking sesuai dengan {booking} di route
     */
    public function show(Booking $booking, Request $request)
    {
        $petugas = Auth::user();

        // Otomatis update ke in_progress jika sebelumnya masih confirmed (Logika Check-in)
        if ($booking->status === 'confirmed') {
            $booking->update(['status' => 'in_progress']);
        }

        $selectedDate = $request->get('tanggal', now()->toDateString());

        $masuk = Carbon::parse($booking->tanggal_masuk);
        $keluar = Carbon::parse($booking->tanggal_keluar);
        $selected = Carbon::parse($selectedDate);

        if ($selected->lt($masuk) || $selected->gt($keluar)) {
            return redirect()->route('petugas.input-log.show', ['booking' => $booking->id, 'tanggal' => $booking->tanggal_masuk]);
        }

        $logs = DailyLog::where('booking_id', $booking->id)
            ->where('tanggal', $selectedDate)
            ->with('kegiatan')
            ->orderBy('waktu', 'asc')
            ->get();

        $masterKegiatan = MasterKegiatan::where('aktif', 'ya')
            ->orderBy('urutan')
            ->get();

        $dates = [];
        $current = $masuk->copy();
        while ($current->lte($keluar)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        $filledDates = DailyLog::where('booking_id', $booking->id)
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->pluck('date')
            ->toArray();

        return view('petugas.input-log', compact(
            'booking', 'logs', 'masterKegiatan', 'selectedDate', 'dates', 'filledDates'
        ));
    }

    /**
     * Simpan log kegiatan
     * PERBAIKAN: Parameter kedua HARUS bernama $booking agar sinkron dengan Route
     */
    public function store(Request $request, Booking $booking)
    {
        $petugasId = Auth::id();

        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date|after_or_equal:' . $booking->tanggal_masuk . '|before_or_equal:' . $booking->tanggal_keluar,
            'kegiatan_id' => 'required|exists:master_kegiatan,id',
            'waktu' => 'required|date_format:H:i',
            'keterangan' => 'nullable|string|max:500',
            'jumlah' => 'nullable|string|max:50',
            'satuan' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Simpan log menggunakan ID dari objek $booking yang sudah di-binding
        DailyLog::create([
            'booking_id' => $booking->id,
            'petugas_id' => $petugasId,
            'kegiatan_id' => $validated['kegiatan_id'],
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'keterangan' => $validated['keterangan'] ?? null,
            'jumlah' => $validated['jumlah'] ?? null,
            'satuan' => $validated['satuan'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'status_pelaksanaan' => 'selesai',
        ]);

        return redirect()->route('petugas.input-log.show', ['booking' => $booking->id, 'tanggal' => $validated['tanggal']])
            ->with('success', 'Log kegiatan berhasil ditambahkan!');
    }

    /**
     * Hapus log kegiatan
     */
    public function destroyLog(DailyLog $log)
    {
        $bookingId = $log->booking_id;
        $tanggal = $log->tanggal;

        $log->delete();

        return redirect()->route('petugas.input-log.show', ['booking' => $bookingId, 'tanggal' => $tanggal])
            ->with('success', 'Log kegiatan berhasil dihapus!');
    }
}