<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InputLogController extends Controller
{
    public function show($id, Request $request)
    {
        $petugas = Auth::user();
        $petugasId = $petugas->id;
        
        // Validasi booking
        $booking = Booking::where('id', $id)
            ->where('status', 'in_progress')
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
        
        // Cek log untuk tanggal yang dipilih
        $log = DailyLog::where('booking_id', $id)
            ->where('tanggal', $selectedDate)
            ->first();
        
        // Ambil semua tanggal booking untuk dropdown
        $dates = [];
        $current = $masuk->copy();
        while ($current->lte($keluar)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }
        
        // Cek log mana yang sudah diisi
        $filledDates = DailyLog::where('booking_id', $id)
            ->pluck('tanggal')
            ->map(function($date) {
                return Carbon::parse($date)->toDateString();
            })
            ->toArray();
        
        return view('petugas.input-log', compact(
            'booking', 
            'log', 
            'selectedDate',
            'dates',
            'filledDates'
        ));
    }
    
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
            'buang_air' => 'required|in:belum,normal,diare,sembelit',
            'catatan' => 'nullable|string|max:1000',
        ]);
        
        // Ambil tanggal dari request
        $tanggal = $validated['tanggal'];
        
        // Cek log untuk tanggal ini
        $log = DailyLog::where('booking_id', $id)
            ->where('tanggal', $tanggal)
            ->first();
        
        // Logika checkbox (sama seperti sebelumnya)
        function getCheckboxValue($requestName, $log, $fieldName) {
            if ($requestName->has($fieldName)) {
                return 1;
            } else {
                return $log ? $log->$fieldName : 0;
            }
        }
        
        function getTimeValue($requestName, $fieldName, $log, $timeField) {
            if (!empty($requestName->$fieldName)) {
                return $requestName->$fieldName;
            } else {
                return $log ? $log->$timeField : null;
            }
        }
        
        // Data untuk disimpan
        $data = [
            'booking_id' => $id,
            'petugas_id' => $petugasId,
            'tanggal' => $tanggal,
            'makan_pagi' => getCheckboxValue($request, $log, 'makan_pagi'),
            'jam_makan_pagi' => getTimeValue($request, 'jam_makan_pagi', $log, 'jam_makan_pagi'),
            'makan_siang' => getCheckboxValue($request, $log, 'makan_siang'),
            'jam_makan_siang' => getTimeValue($request, 'jam_makan_siang', $log, 'jam_makan_siang'),
            'makan_malam' => getCheckboxValue($request, $log, 'makan_malam'),
            'jam_makan_malam' => getTimeValue($request, 'jam_makan_malam', $log, 'jam_makan_malam'),
            'minum' => getCheckboxValue($request, $log, 'minum'),
            'jam_minum' => getTimeValue($request, 'jam_minum', $log, 'jam_minum'),
            'jalan_jalan' => getCheckboxValue($request, $log, 'jalan_jalan'),
            'jam_jalan_jalan' => getTimeValue($request, 'jam_jalan_jalan', $log, 'jam_jalan_jalan'),
            'buang_air' => $validated['buang_air'],
            'catatan' => $validated['catatan'] ?? null,
        ];
        
        // Update atau create
        if ($log) {
            $log->update($data);
        } else {
            DailyLog::create($data);
        }
        
        // Redirect kembali dengan parameter tanggal
        return redirect()->route('petugas.input-log.show', ['booking' => $id, 'tanggal' => $tanggal])
            ->with('success', 'Update harian berhasil disimpan.');
    }
}