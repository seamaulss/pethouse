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
}
