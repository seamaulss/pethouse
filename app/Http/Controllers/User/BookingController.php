<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\JenisHewan;
use App\Models\LayananHarga; // Tambahkan ini
use App\Models\Kapasitas;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $layanan = Layanan::all();
        $jenisHewan = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();

        return view('user.booking.create', compact('user', 'layanan', 'jenisHewan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'nomor_wa' => 'nullable|string|max:20',
            'nama_hewan' => 'required|string|max:100',
            'jenis_hewan' => 'required|string|max:50',
            'ukuran_hewan' => 'required|in:Kecil,Sedang,Besar',
            'layanan_id' => 'required|exists:layanan,id',
            'tanggal_masuk' => 'required|date|after_or_equal:' . $today,
            'tanggal_keluar' => 'required|date|after:tanggal_masuk',
            'dp_dibayar' => 'required|in:Ya,Tidak',
            'bukti_dp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan' => 'nullable|string|max:1000',
            'alasan_cancel' => 'nullable|string|max:1000',
            'alasan_perpanjangan' => 'nullable|string|max:1000',
        ]);

        // Normalisasi nomor WhatsApp
        $wa_bersih = $this->normalizeWhatsApp($validated['nomor_wa'] ?? '');

        // Cek kapasitas
        $kapasitas = $this->cekKapasitas(
            $validated['layanan_id'],
            $validated['jenis_hewan'],
            $validated['ukuran_hewan'],
            $validated['tanggal_masuk'],
            $validated['tanggal_keluar']
        );

        if (!$kapasitas['tersedia']) {
            return back()->withErrors(['kapasitas' => "Slot penuh (maksimal {$kapasitas['max']} hewan)."])->withInput();
        }

        // HITUNG TOTAL HARGA - TAMBAHKAN KODE INI
        $tanggal_masuk_obj = Carbon::parse($validated['tanggal_masuk']);
        $tanggal_keluar_obj = Carbon::parse($validated['tanggal_keluar']);
        $durasi = $tanggal_masuk_obj->diffInDays($tanggal_keluar_obj);
        $durasi = max(1, $durasi); // Minimal 1 hari

        // Cari harga per hari
        $jenisHewan = JenisHewan::where('nama', $validated['jenis_hewan'])->first();
        $harga_per_hari = 0;
        $total_harga = 0;

        if ($jenisHewan) {
            $layananHarga = LayananHarga::where('layanan_id', $validated['layanan_id'])
                ->where('jenis_hewan_id', $jenisHewan->id)
                ->first();

            if ($layananHarga) {
                $harga_per_hari = $layananHarga->harga_per_hari;
                $total_harga = $durasi * $harga_per_hari;
            }
        }

        // Upload bukti DP jika ada
        $bukti_dp = null;
        if ($request->hasFile('bukti_dp') && $validated['dp_dibayar'] === 'Ya') {
            $bukti_dp = $request->file('bukti_dp')->store('bukti_dp', 'public');
        } elseif ($validated['dp_dibayar'] === 'Ya' && !$request->hasFile('bukti_dp')) {
            return back()->withErrors(['bukti_dp' => 'Bukti transfer DP wajib diupload!'])->withInput();
        }

        // Generate kode booking
        $kode_booking = $this->generateKodeBooking();

        // Simpan booking - TAMBAHKAN total_harga
        try {
            $booking = Booking::create([
                'user_id' => $user->id,
                'kode_booking' => $kode_booking,
                'nama_pemilik' => $validated['nama_pemilik'],
                'email' => $validated['email'],
                'nomor_wa' => $wa_bersih,
                'nama_hewan' => $validated['nama_hewan'],
                'jenis_hewan' => $validated['jenis_hewan'],
                'ukuran_hewan' => $validated['ukuran_hewan'],
                'layanan_id' => $validated['layanan_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'dp_dibayar' => $validated['dp_dibayar'],
                'bukti_dp' => $bukti_dp,
                'catatan' => $validated['catatan'] ?? '',
                'status' => 'pending',
                'alasan_cancel' => $validated['alasan_cancel'] ?? null,
                'alasan_perpanjangan' => $validated['alasan_perpanjangan'] ?? null,
                'total_harga' => $total_harga, // TAMBAHKAN INI
            ]);

            return redirect()->route('user.booking.riwayat')
                ->with('success', "✅ Booking berhasil dikirim! Kode Booking: $kode_booking | Total Harga: Rp " . number_format($total_harga, 0, ',', '.'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan booking: ' . $e->getMessage()])->withInput();
        }
    }

    public function riwayat()
    {
        $user = Auth::user();

        $bookings = Booking::where('user_id', $user->id)
            ->with('layanan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.booking.riwayat', compact('user', 'bookings'));
    }

    public function getHarga(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'jenis_hewan' => 'required|string',
        ]);

        $harga = DB::table('layanan_harga as lh')
            ->join('jenis_hewan as jh', 'lh.jenis_hewan_id', '=', 'jh.id')
            ->where('lh.layanan_id', $request->layanan_id)
            ->where('jh.nama', $request->jenis_hewan)
            ->value('lh.harga_per_hari');

        return response()->json([
            'harga' => $harga ? number_format($harga, 0, ',', '.') : '0'
        ]);
    }

    /**
     * Show extend booking form
     */
    public function showExtendForm($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Hanya bisa extend booking yang statusnya 'diterima' atau 'in_progress'
        if (!in_array($booking->status, ['diterima', 'in_progress'])) {
            return redirect()->route('user.booking.riwayat')
                ->with('error', 'Booking hanya bisa diperpanjang jika statusnya "Diterima" atau "Sedang Dititipkan".');
        }

        // Tanggal minimal untuk perpanjangan adalah tanggal keluar + 1 hari
        $minDate = Carbon::parse($booking->tanggal_keluar)->addDay()->format('Y-m-d');
        $maxDate = Carbon::parse($booking->tanggal_keluar)->addDays(30)->format('Y-m-d');

        // Ambil harga per hari untuk layanan dan jenis hewan ini
        $jenisHewan = JenisHewan::where('nama', $booking->jenis_hewan)->first();
        $hargaPerHari = 0;

        if ($jenisHewan) {
            $layananHarga = LayananHarga::where('layanan_id', $booking->layanan_id)
                ->where('jenis_hewan_id', $jenisHewan->id)
                ->first();
            if ($layananHarga) {
                $hargaPerHari = $layananHarga->harga_per_hari;
            }
        }

        return view('user.booking.extend', compact('booking', 'minDate', 'maxDate', 'hargaPerHari'));
    }

    /**
     * Extend booking
     */
    public function extend(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Cek apakah booking bisa diperpanjang
        if (!in_array($booking->status, ['diterima', 'in_progress'])) {
            return redirect()->route('user.booking.riwayat')
                ->with('error', 'Booking hanya bisa diperpanjang jika statusnya "Diterima" atau "Sedang Dititipkan".');
        }

        $request->validate([
            'tanggal_keluar_baru' => [
                'required',
                'date',
                'after:' . $booking->tanggal_keluar,
                function ($attribute, $value, $fail) use ($booking) {
                    $maxDate = Carbon::parse($booking->tanggal_keluar)->addDays(30);
                    if (Carbon::parse($value)->gt($maxDate)) {
                        $fail('Maksimal perpanjangan adalah 30 hari dari tanggal keluar saat ini.');
                    }
                }
            ],
            'alasan_perpanjangan' => 'nullable|string|max:500',
        ]);

        // Cek kapasitas untuk periode perpanjangan
        $kapasitas = $this->cekKapasitas(
            $booking->layanan_id,
            $booking->jenis_hewan,
            $booking->ukuran_hewan,
            $booking->tanggal_keluar, // mulai dari tanggal keluar lama
            $request->tanggal_keluar_baru
        );

        if (!$kapasitas['tersedia']) {
            return back()->withErrors([
                'tanggal_keluar_baru' => "Slot penuh untuk periode perpanjangan (maksimal {$kapasitas['max']} hewan)."
            ])->withInput();
        }

        // Hitung harga tambahan untuk perpanjangan
        $hargaTambahan = 0;
        $jenisHewan = JenisHewan::where('nama', $booking->jenis_hewan)->first();
        if ($jenisHewan) {
            $layananHarga = LayananHarga::where('layanan_id', $booking->layanan_id)
                ->where('jenis_hewan_id', $jenisHewan->id)
                ->first();

            if ($layananHarga) {
                $durasiTambahan = Carbon::parse($booking->tanggal_keluar)
                    ->diffInDays(Carbon::parse($request->tanggal_keluar_baru));
                $durasiTambahan = max(1, $durasiTambahan);
                $hargaTambahan = $durasiTambahan * $layananHarga->harga_per_hari;
            }
        }

        // Simpan permintaan perpanjangan
        $booking->update([
            'status' => 'perpanjangan',
            'tanggal_keluar' => $request->tanggal_keluar_baru,
            'tanggal_perpanjangan' => $request->tanggal_keluar_baru,
            'alasan_perpanjangan' => $booking->alasan_perpanjangan . "\n\n[PERPANJANGAN DIAJUKAN]\n" .
                "Tanggal keluar baru: " . $request->tanggal_keluar_baru . "\n" .
                "Alasan: " . ($request->alasan_perpanjangan ?? '-') . "\n" .
                "Diajukan pada: " . now()->format('d/m/Y H:i') . "\n" .
                "Harga tambahan: Rp " . number_format($hargaTambahan, 0, ',', '.')
        ]);

        return redirect()->route('user.booking.riwayat')
            ->with('success', 'Permintaan perpanjangan telah dikirim. Menunggu konfirmasi admin.');
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Hanya bisa membatalkan booking yang statusnya 'pending' atau 'diterima'
        if (!in_array($booking->status, ['pending', 'diterima'])) {
            return redirect()->route('user.booking.riwayat')
                ->with('error', 'Booking hanya bisa dibatalkan jika statusnya "Pending" atau "Diterima".');
        }

        $request->validate([
            'alasan_cancel' => 'required|string|max:500',
        ]);

        // Update status booking
        $booking->update([
            'status' => 'pembatalan',
            'alasan_cancel' => $booking->alasan_cancel . "\n\n[PEMBATALAN DIAJUKAN]\n" .
                "Alasan: " . $request->alasan_cancel . "\n" .
                "Dibatalkan pada: " . now()->format('d/m/Y H:i') . "\n" .
                "Total yang sudah dibayar: Rp " . number_format($booking->total_harga, 0, ',', '.')
        ]);

        return redirect()->route('user.booking.riwayat')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Normalize WhatsApp number
     */
    private function normalizeWhatsApp($nomor)
    {
        if (empty($nomor)) {
            return '';
        }

        $nomor = preg_replace('/[^\d]/', '', $nomor);

        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        } elseif (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }

    /**
     * Check capacity for booking
     */
    private function cekKapasitas($layanan_id, $jenis_hewan, $ukuran_hewan, $tanggal_masuk, $tanggal_keluar)
    {
        // Hitung jumlah booking yang overlap
        $jumlah = Booking::where('layanan_id', $layanan_id)
            ->where('jenis_hewan', $jenis_hewan)
            ->where('ukuran_hewan', $ukuran_hewan)
            ->whereIn('status', ['pending', 'diterima', 'in_progress', 'perpanjangan'])
            ->where(function ($query) use ($tanggal_masuk, $tanggal_keluar) {
                $query->where(function ($q) use ($tanggal_masuk, $tanggal_keluar) {
                    $q->where('tanggal_masuk', '<=', $tanggal_keluar)
                        ->where('tanggal_keluar', '>=', $tanggal_masuk);
                });
            })
            ->count();

        // Ambil kapasitas maksimal
        $kapasitas = Kapasitas::where('layanan_id', $layanan_id)
            ->where('jenis_hewan', $jenis_hewan)
            ->where('ukuran_hewan', $ukuran_hewan)
            ->first();

        $max = $kapasitas ? $kapasitas->max_kapasitas : 5; // default 5

        return [
            'tersedia' => $jumlah < $max,
            'max' => $max,
            'terpakai' => $jumlah
        ];
    }

    /**
     * Generate booking code
     */
    private function generateKodeBooking()
    {
        $tahun = date('Y');

        do {
            $last = Booking::where('kode_booking', 'like', "BOOK-$tahun-%")
                ->orderBy('kode_booking', 'desc')
                ->first();

            $num = $last ? ((int)substr($last->kode_booking, -4) + 1) : 1;
            $kode = 'BOOK-' . $tahun . '-' . sprintf('%04d', $num);

            $exists = Booking::where('kode_booking', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    /**
     * Show booking detail
     */
    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['layanan', 'dailyLogs'])
            ->findOrFail($id);

        // Hitung durasi
        $tanggal_masuk = Carbon::parse($booking->tanggal_masuk);
        $tanggal_keluar = Carbon::parse($booking->tanggal_keluar);
        $durasi = $tanggal_masuk->diffInDays($tanggal_keluar);
        $durasi = max(1, $durasi);

        // Ambil harga per hari dari layanan_harga
        $jenisHewan = JenisHewan::where('nama', $booking->jenis_hewan)->first();
        $hargaPerHari = 0;
        if ($jenisHewan) {
            $layananHarga = LayananHarga::where('layanan_id', $booking->layanan_id)
                ->where('jenis_hewan_id', $jenisHewan->id)
                ->first();
            if ($layananHarga) {
                $hargaPerHari = $layananHarga->harga_per_hari;
            }
        }

        // Total biaya dari booking
        $totalBiaya = $booking->total_harga;

        return view('user.booking.show', compact('booking', 'durasi', 'hargaPerHari', 'totalBiaya'));
    }
}
