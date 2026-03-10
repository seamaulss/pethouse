<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Konsultasi;
use App\Models\JenisHewan;
use App\Models\Notification;
use Carbon\Carbon;

class KonsultasiController extends Controller
{
    public function index()
    {
        $consultations = Konsultasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.konsultasi.index', [
            'consultations' => $consultations,
            'total' => $consultations->count(),
            'pending' => $consultations->where('status', 'pending')->count(),
            'diterima' => $consultations->where('status', 'diterima')->count(),
            'selesai' => $consultations->where('status', 'selesai')->count(),
        ]);
    }

    public function create()
    {
        $jenisHewan = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();
        return view('user.konsultasi.create', compact('jenisHewan'));
    }

    public function store(Request $request)
    {
        $today = Carbon::today();

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
            'jenis_hewan' => 'required|string',
            'topik' => 'required|string|max:100',
            'tanggal_janji' => 'required|date|after_or_equal:today',
            'jam_janji' => 'required',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Cek Double Booking
        $isBooked = Konsultasi::where('tanggal_janji', $validated['tanggal_janji'])
            ->where('jam_janji', $validated['jam_janji'])
            ->whereIn('status', ['pending', 'diterima'])
            ->exists();

        if ($isBooked) {
            return back()->withErrors(['jam_janji' => 'Maaf, jam ini baru saja dipesan orang lain. Silakan pilih jam lain.'])->withInput();
        }

        try {
            $konsultasi = Konsultasi::create([
                'user_id' => Auth::id(),
                'kode_konsultasi' => $this->generateKodeKonsultasi(),
                'nama_pemilik' => $validated['nama_pemilik'],
                'no_wa' => $this->normalizeWhatsApp($validated['no_wa']),
                'jenis_hewan' => $validated['jenis_hewan'],
                'topik' => $validated['topik'],
                'tanggal_janji' => $validated['tanggal_janji'],
                'jam_janji' => $validated['jam_janji'],
                'catatan' => $validated['catatan'],
                'status' => 'pending',
            ]);

            try {
                // 1. Ambil data dokter secara dinamis dari tabel users
                // Berdasarkan SQL Anda, Dokter memiliki role 'dokter'
                $dokter = \App\Models\User::where('role', 'dokter')->first();

                if ($dokter) {
                    // 2. Gunakan Notification::create agar kita bisa mengatur user_id dan role_target secara manual
                    \App\Models\Notification::create([
                        'user_id'     => $dokter->id,      // Ini akan mengisi ID 4 (ID Dokter Anda)
                        'role_target' => 'dokter',         // Sesuai enum 'dokter' di tabel notifications
                        'title'       => 'Konsultasi Baru Masuk! 🩺',
                        'message'     => "Pemilik {$konsultasi->nama_pemilik} telah melakukan booking untuk {$konsultasi->jenis_hewan} pada " . date('d M Y', strtotime($konsultasi->tanggal_janji)) . " jam {$konsultasi->jam_janji} WIB.",
                        'type'        => 'warning',        // Menentukan warna/ikon notifikasi
                        'is_read'     => 0,                // Set belum dibaca
                        'booking_id'  => null,             // Karena ini konsultasi, bukan booking hotel
                    ]);
                }

                return redirect()->route('user.konsultasi.index')
                    ->with('success', "Booking Berhasil! Kode: <strong>{$konsultasi->kode_konsultasi}</strong>.");
            } catch (\Exception $e) {
                // ...
            }

            return redirect()->route('user.konsultasi.index')
                ->with('success', "Booking Berhasil! Kode: <strong>{$konsultasi->kode_konsultasi}</strong>. Silakan datang sesuai jadwal.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    public function getJam(Request $request)
    {
        // Mengambil jam dan memformatnya menjadi HH:mm (contoh: 08:00)
        $jamTerpakai = Konsultasi::where('tanggal_janji', $request->tanggal)
            ->whereIn('status', ['pending', 'diterima', 'selesai'])
            ->get()
            ->map(function ($item) {
                return date('H:i', strtotime($item->jam_janji));
            })
            ->toArray();

        return response()->json($jamTerpakai);
    }

    private function normalizeWhatsApp($nomor)
    {
        $nomor = preg_replace('/[^\d]/', '', $nomor);
        if (str_starts_with($nomor, '0')) return '62' . substr($nomor, 1);
        return $nomor;
    }

    private function generateKodeKonsultasi()
    {
        $prefix = 'KONS-' . date('Y');

        // Cari nomor terakhir berdasarkan kode_konsultasi, bukan jumlah data (count)
        $lastRecord = Konsultasi::where('kode_konsultasi', 'like', "$prefix%")
            ->orderBy('kode_konsultasi', 'desc')
            ->first();

        if (!$lastRecord) {
            $nextNumber = 1;
        } else {
            // Ambil 4 angka terakhir dari kode (misal: 0007 jadi 7) dan tambah 1
            $lastNumber = (int) substr($lastRecord->kode_konsultasi, -4);
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . '-' . sprintf('%04d', $nextNumber);
    }
}
