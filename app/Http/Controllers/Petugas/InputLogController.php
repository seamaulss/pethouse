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
        // 1. Logika Check-in otomatis
        if ($booking->status === 'confirmed') {
            $booking->update(['status' => 'in_progress']);
        }

        // 2. Ambil tanggal hari ini dan tanggal dari request
        $today = now()->toDateString();
        $requestedDate = $request->get('tanggal');

        // 3. LOGIKA FALLBACK (Anti-Loop): 
        // Jika tidak ada request tanggal, cek apakah hari ini masuk dalam range booking.
        // Jika hari ini di luar range, paksa pakai tanggal_masuk.
        if (!$requestedDate) {
            $selectedDate = ($today >= $booking->tanggal_masuk && $today <= $booking->tanggal_keluar)
                ? $today
                : $booking->tanggal_masuk;
        } else {
            $selectedDate = $requestedDate;
        }

        // 4. VALIDASI RANGE (Kunci mati agar tidak bisa input tanggal sembarangan di URL)
        if ($selectedDate < $booking->tanggal_masuk) $selectedDate = $booking->tanggal_masuk;
        if ($selectedDate > $booking->tanggal_keluar) $selectedDate = $booking->tanggal_keluar;

        // --- SELESAI, LANJUT AMBIL DATA ---

        $logs = DailyLog::where('booking_id', $booking->id)
            ->where('tanggal', $selectedDate)
            ->with('kegiatan')
            ->orderBy('waktu', 'asc')
            ->get();

        $masterKegiatan = MasterKegiatan::where('aktif', 'ya')->orderBy('urutan')->get();

        // Buat list tanggal untuk navigasi di view
        $dates = [];
        $current = \Carbon\Carbon::parse($booking->tanggal_masuk);
        $keluar = \Carbon\Carbon::parse($booking->tanggal_keluar);
        while ($current->lte($keluar)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        $filledDates = DailyLog::where('booking_id', $booking->id)
            ->distinct()->pluck('tanggal')->toArray();

        return view('petugas.input-log', compact(
            'booking',
            'logs',
            'masterKegiatan',
            'selectedDate',
            'dates',
            'filledDates'
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
