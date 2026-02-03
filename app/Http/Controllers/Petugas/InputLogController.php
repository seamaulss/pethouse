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
     * Tampilkan form input log fleksibel
     */
    public function show($id, Request $request)
    {
        $petugas = Auth::user();
        
        // Validasi booking
        $booking = Booking::where('id', $id)
            ->where('status', 'in_progress')
            ->with(['user', 'layanan'])
            ->firstOrFail();
        
        // Tanggal yang dipilih (default: hari ini)
        $selectedDate = $request->get('tanggal', now()->toDateString());
        
        // Validasi tanggal harus dalam range booking
        $masuk = Carbon::parse($booking->tanggal_masuk);
        $keluar = Carbon::parse($booking->tanggal_keluar);
        $selected = Carbon::parse($selectedDate);
        
        if ($selected->lt($masuk) || $selected->gt($keluar)) {
            abort(400, 'Tanggal tidak valid untuk booking ini.');
        }
        
        // Ambil semua log untuk tanggal yang dipilih
        $logs = DailyLog::where('booking_id', $id)
            ->where('tanggal', $selectedDate)
            ->with('kegiatan')
            ->orderBy('waktu', 'asc')
            ->get();
        
        // Ambil semua master kegiatan yang aktif
        $masterKegiatan = MasterKegiatan::where('aktif', 'ya')
            ->orderBy('urutan')
            ->get();
        
        // Ambil semua tanggal booking untuk dropdown
        $dates = [];
        $current = $masuk->copy();
        while ($current->lte($keluar)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }
        
        // Cek tanggal mana yang sudah ada log
        $filledDates = DailyLog::where('booking_id', $id)
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->pluck('date')
            ->toArray();
        
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
     * Simpan log kegiatan fleksibel
     */
    public function store(Request $request, $id)
    {
        $petugasId = Auth::id();
        
        // Validasi booking
        $booking = Booking::where('id', $id)
            ->where('status', 'in_progress')
            ->firstOrFail();
        
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
        
        // Simpan log
        $log = DailyLog::create([
            'booking_id' => $id,
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
        
        // Redirect kembali dengan parameter tanggal
        return redirect()->route('petugas.input-log.show', ['booking' => $id, 'tanggal' => $validated['tanggal']])
            ->with('success', 'Log kegiatan berhasil ditambahkan!');
    }
    
    /**
     * Hapus log kegiatan
     */
    public function destroyLog($id)
    {
        $log = DailyLog::where('id', $id)
            ->where('petugas_id', Auth::id())
            ->firstOrFail();
            
        $bookingId = $log->booking_id;
        $tanggal = $log->tanggal;
        
        $log->delete();
        
        return redirect()->route('petugas.input-log.show', ['booking' => $bookingId, 'tanggal' => $tanggal])
            ->with('success', 'Log kegiatan berhasil dihapus!');
    }
}