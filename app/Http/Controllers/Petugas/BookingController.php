<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // Menampilkan hewan yang sedang aktif (diterima atau dititipkan)
        $bookings = Booking::whereIn('status', ['diterima', 'in_progress', 'perpanjangan'])
            ->orderBy('tanggal_masuk', 'asc')
            ->paginate(10);

        return view('petugas.booking.index', compact('bookings'));
    }

    public function show($id)
    {
        // Menggunakan eager loading 'dailyLogs' (asumsi nama relasi di model Booking)
        $booking = Booking::with(['layanan', 'user', 'dailyLogs'])->findOrFail($id);
        return view('petugas.booking.show', compact('booking'));
    }

    public function search(Request $request)
    {
        $kode = $request->query('kode_booking');

        // Ambil data booking berdasarkan kode
        $booking = Booking::where('kode_booking', $kode)->first();

        // Jika data tidak ditemukan
        if (!$booking) {
            return redirect()->route('petugas.dashboard')
                ->with('error', 'Booking dengan kode ' . $kode . ' tidak ditemukan.');
        }

        // Jika ditemukan, tampilkan view verifikasi
        // Pastikan file ini ada: resources/views/petugas/booking/checkin_verifikasi.blade.php
        return view('petugas.booking.checkin_verifikasi', compact('booking'));
    }
}
