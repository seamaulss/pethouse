<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\DailyLog;
use Carbon\Carbon;

class HewanSayaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua booking milik user ini
        $bookings = Booking::where('user_id', $user->id)
            ->with(['layanan'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
            
        return view('user.hewan-saya.index', compact('user', 'bookings'));
    }
    
    public function logHarian($id, Request $request)
    {
        $user = Auth::user();
        
        // Cek booking milik user
        $booking = Booking::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['layanan'])
            ->firstOrFail();
        
        // Tanggal yang dipilih (default: hari ini atau tanggal terakhir ada log)
        $selectedDate = $request->get('tanggal', null);
        
        // Jika tidak ada tanggal yang dipilih, ambil tanggal terakhir ada log
        if (!$selectedDate) {
            $lastLog = DailyLog::where('booking_id', $id)
                ->orderBy('tanggal', 'desc')
                ->first();
            $selectedDate = $lastLog ? $lastLog->tanggal->format('Y-m-d') : now()->format('Y-m-d');
        }
        
        // Validasi tanggal dalam range booking
        $masuk = Carbon::parse($booking->tanggal_masuk);
        $keluar = Carbon::parse($booking->tanggal_keluar);
        $selected = Carbon::parse($selectedDate);
        
        if ($selected->lt($masuk) || $selected->gt($keluar)) {
            $selectedDate = now()->format('Y-m-d');
        }
        
        // Ambil semua log untuk tanggal yang dipilih
        $logs = DailyLog::where('booking_id', $id)
            ->where('tanggal', $selectedDate)
            ->with(['kegiatan', 'petugas'])
            ->orderBy('waktu', 'desc')
            ->get();
        
        // Ambil semua tanggal yang memiliki log untuk dropdown
        $datesWithLogs = DailyLog::where('booking_id', $id)
            ->selectRaw('DATE(tanggal) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();
        
        // Format dates untuk dropdown
        $dateOptions = [];
        foreach ($datesWithLogs as $date) {
            $dateObj = Carbon::parse($date);
            $dateOptions[$date] = $dateObj->format('d M Y') . 
                ($date == now()->format('Y-m-d') ? ' (Hari Ini)' : '') .
                ' (' . DailyLog::where('booking_id', $id)->where('tanggal', $date)->count() . ' kegiatan)';
        }
        
        // Jika belum ada log sama sekali
        if (empty($dateOptions) && $booking->status == 'in_progress') {
            $dateOptions[now()->format('Y-m-d')] = now()->format('d M Y') . ' (Hari Ini) - Belum ada log';
        }
        
        return view('user.hewan-saya.log', compact(
            'booking', 
            'logs', 
            'selectedDate',
            'dateOptions'
        ));
    }
}