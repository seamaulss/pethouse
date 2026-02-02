<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil riwayat booking terbaru
        $bookings = Booking::where('user_id', $user->id)
            ->with('layanan')
            ->orderBy('tanggal_masuk', 'desc')
            ->limit(10)
            ->get();

        // Ambil riwayat konsultasi terbaru
        $konsultasi = Konsultasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Statistik
        $totalBooking = $bookings->count();
        $totalKonsultasi = $konsultasi->count();
        $bookingAktif = $bookings->whereIn('status', ['pending', 'diterima', 'in_progress'])->count();

        return view('user.profil.index', compact(
            'user',
            'bookings',
            'konsultasi',
            'totalBooking',
            'totalKonsultasi',
            'bookingAktif'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_wa' => 'nullable|string|max:20',
            // 'alamat' dihapus karena tidak ada di database
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        try {
            // Update data dasar
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->nomor_wa = $validated['nomor_wa'] ?? $user->nomor_wa;
            // Tidak ada field alamat, jadi dihapus

            // Update password jika diisi
            if ($request->filled('new_password')) {
                $user->password = Hash::make($validated['new_password']);
            }

            $user->save();

            return redirect()->route('user.profil')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
        }
    }
}
