<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\JenisHewan;
use App\Models\LayananHarga;
use App\Models\Kapasitas;
use App\Models\Notification;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Tampilkan form booking
     */
    public function create()
    {
        $user = Auth::user();
        $layanan = Layanan::all();
        $jenisHewan = JenisHewan::where('aktif', 'ya')->orderBy('nama')->get();
        $lastBooking = Booking::where('user_id', $user->id)->latest()->first();

        return view('user.booking.create', compact('user', 'layanan', 'jenisHewan', 'lastBooking'));
    }

    /**
     * Tampilkan riwayat booking user (Method yang tadi hilang)
     */
    public function riwayat()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->with('layanan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.booking.riwayat', compact('user', 'bookings'));
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->translatedFormat('Y-F-d');

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
        ]);

        $wa_bersih = $this->normalizeWhatsApp($validated['nomor_wa'] ?? '');

        // 1. Cek Kapasitas
        $kapasitas = $this->cekKapasitas($validated['layanan_id'], $validated['jenis_hewan'], $validated['ukuran_hewan'], $validated['tanggal_masuk'], $validated['tanggal_keluar']);
        if (!$kapasitas['tersedia']) {
            return back()->withErrors(['kapasitas' => $kapasitas['pesan']])->withInput();
        }

        // 2. Hitung Harga
        $durasi = max(1, Carbon::parse($validated['tanggal_masuk'])->diffInDays(Carbon::parse($validated['tanggal_keluar'])));
        $total_harga = 0;
        $jh = JenisHewan::where('nama', $validated['jenis_hewan'])->first();
        if ($jh) {
            $lh = LayananHarga::where('layanan_id', $validated['layanan_id'])->where('jenis_hewan_id', $jh->id)->first();
            $total_harga = $lh ? $durasi * $lh->harga_per_hari : 0;
        }

        $bukti_dp = $request->hasFile('bukti_dp') ? $request->file('bukti_dp')->store('bukti_dp', 'public') : null;

        try {
            $booking = Booking::create([
                'user_id' => $user->id,
                'kode_booking' => $this->generateKodeBooking(),
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
                'total_harga' => $total_harga,
            ]);

            Notification::create(['user_id' => $user->id, 'role_target' => 'user', 'title' => 'Booking Berhasil', 'message' => "Booking #{$booking->kode_booking} berhasil dibuat.", 'booking_id' => $booking->id, 'type' => 'success']);
            Notification::create(['role_target' => 'admin', 'title' => 'Booking Baru', 'message' => "Booking baru dari {$user->username}.", 'booking_id' => $booking->id, 'type' => 'info']);

            return redirect()->route('user.booking.riwayat')->with('success', "✅ Booking berhasil! Kode: {$booking->kode_booking}");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Logic Overlap Kapasitas
     */
    private function cekKapasitas($layanan_id, $jenis_hewan, $ukuran_hewan, $tgl_masuk, $tgl_keluar, $exclude_id = null)
    {
        $config = Kapasitas::where('layanan_id', $layanan_id)->where('jenis_hewan', $jenis_hewan)->where('ukuran_hewan', $ukuran_hewan)->first();
        if (!$config) return ['tersedia' => false, 'pesan' => "Kuota belum diatur admin."];

        $terisi = Booking::where('layanan_id', $layanan_id)
            ->where('jenis_hewan', $jenis_hewan)
            ->where('ukuran_hewan', $ukuran_hewan)
            ->whereIn('status', ['pending', 'diterima', 'in_progress', 'perpanjangan'])
            ->when($exclude_id, fn($q) => $q->where('id', '!=', $exclude_id))
            ->where(function ($q) use ($tgl_masuk, $tgl_keluar) {
                $q->where('tanggal_masuk', '<=', $tgl_keluar)->where('tanggal_keluar', '>=', $tgl_masuk);
            })->count();

        return [
            'tersedia' => $terisi < $config->max_kapasitas,
            'sisa' => $config->max_kapasitas - $terisi,
            'pesan' => "Slot penuh untuk kategori ini."
        ];
    }

    /**
     * AJAX Check Kapasitas (Untuk View)
     */
    public function checkKapasitasAjax(Request $request)
    {
        return response()->json($this->cekKapasitas($request->layanan_id, $request->jenis_hewan, $request->ukuran_hewan, $request->tanggal_masuk, $request->tanggal_keluar));
    }

    public function getHarga(Request $request)
    {
        $harga = DB::table('layanan_harga as lh')->join('jenis_hewan as jh', 'lh.jenis_hewan_id', '=', 'jh.id')->where('lh.layanan_id', $request->layanan_id)->where('jh.nama', $request->jenis_hewan)->value('lh.harga_per_hari');
        return response()->json(['harga' => $harga ?? 0]);
    }

    public function showExtendForm($id)
    {
        $booking = Booking::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        if (!in_array($booking->status, ['diterima', 'in_progress'])) return redirect()->route('user.booking.riwayat')->with('error', 'Tidak bisa perpanjang.');

        $minDate = Carbon::parse($booking->tanggal_keluar)->addDay()->translatedFormat('Y-m-d');
        $maxDate = Carbon::parse($booking->tanggal_keluar)->addDays(30)->translatedFormat('Y-m-d');

        $hargaPerHari = 0;
        $jh = JenisHewan::where('nama', $booking->jenis_hewan)->first();
        if ($jh) {
            $lh = LayananHarga::where('layanan_id', $booking->layanan_id)->where('jenis_hewan_id', $jh->id)->first();
            $hargaPerHari = $lh ? $lh->harga_per_hari : 0;
        }

        return view('user.booking.extend', compact('booking', 'minDate', 'maxDate', 'hargaPerHari'));
    }

    public function extend(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $request->validate(['tanggal_keluar_baru' => 'required|date|after:' . $booking->tanggal_keluar]);

        $kapasitas = $this->cekKapasitas($booking->layanan_id, $booking->jenis_hewan, $booking->ukuran_hewan, Carbon::parse($booking->tanggal_keluar)->addDay()->translatedFormat('Y-m-d'), $request->tanggal_keluar_baru);
        if (!$kapasitas['tersedia']) return back()->withErrors(['tanggal_keluar_baru' => $kapasitas['pesan']])->withInput();

        $booking->update([
            'status' => 'perpanjangan',
            'tanggal_perpanjangan' => $request->tanggal_keluar_baru,
            'alasan_perpanjangan' => $request->alasan_perpanjangan ?? 'Permintaan perpanjangan'
        ]);

        return redirect()->route('user.booking.riwayat')->with('success', 'Permintaan terkirim.');
    }

    public function cancel(Request $request, $id)
    {
        // 1. Cari data dan pastikan milik user yang login
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // 2. Cek apakah status saat ini valid untuk dibatalkan
        // Jika status sudah 'selesai' atau 'batal', tidak boleh cancel lagi
        if (!in_array($booking->status, ['pending', 'diterima'])) {
            return redirect()->route('user.booking.riwayat')
                ->with('error', 'Status booking saat ini tidak memungkinkan untuk pembatalan.');
        }

        // 3. Validasi input alasan
        $request->validate([
            'alasan_cancel' => 'required|string|min:10|max:500'
        ], [
            'alasan_cancel.required' => 'Alasan pembatalan wajib diisi.',
            'alasan_cancel.min' => 'Alasan pembatalan terlalu singkat (minimal 10 karakter).',
        ]);

        try {
            // 4. Update status dan alasan
            $booking->update([
                'status' => 'pembatalan', // Pastikan kolom 'status' di DB menerima value ini
                'alasan_cancel' => $request->alasan_cancel,
            ]);

            // 5. Kirim Notifikasi (Gunakan logic Notification yang sudah Anda punya)
            Notification::create([
                'user_id' => Auth::id(),
                'role_target' => 'user',
                'title' => 'Permintaan Pembatalan',
                'message' => "Permintaan pembatalan untuk booking #{$booking->kode_booking} sedang diproses.",
                'booking_id' => $booking->id,
                'type' => 'info'
            ]);

            Notification::create([
                'role_target' => 'admin',
                'title' => 'Request Pembatalan',
                'message' => "User " . Auth::user()->username . " mengajukan pembatalan untuk #{$booking->kode_booking}.",
                'booking_id' => $booking->id,
                'type' => 'warning'
            ]);

            return redirect()->route('user.booking.riwayat')
                ->with('success', '✅ Permintaan pembatalan berhasil diajukan dan akan segera ditinjau oleh admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // 1. Ambil data booking
        $booking = Booking::where('user_id', Auth::id())
            ->with(['layanan', 'dailyLogs'])
            ->findOrFail($id);

        // 2. Hitung durasi (sudah benar)
        $durasi = max(1, Carbon::parse($booking->tanggal_masuk)->diffInDays(Carbon::parse($booking->tanggal_keluar)));

        // 3. Ambil nilai dari Accessor Model
        // Ini memicu fungsi getHargaPerHariAttribute() di Model Booking
        $hargaPerHari = $booking->harga_per_hari;

        // Ini memicu fungsi getTotalBiayaAttribute() di Model Booking
        $totalBiaya = $booking->total_biaya;

        // 4. Kirim semua variabel ke view
        return view('user.booking.show', compact('booking', 'durasi', 'hargaPerHari', 'totalBiaya'));
    }

    public function downloadPdf($id)
    {
        // 1. Load booking beserta relasi layanannya
        $booking = Booking::with(['layanan'])->findOrFail($id);

        // 2. Hitung Durasi (Selisih Hari)
        $masuk = \Carbon\Carbon::parse($booking->tanggal_masuk);
        $keluar = \Carbon\Carbon::parse($booking->tanggal_keluar);
        $durasi = $masuk->diffInDays($keluar) ?: 1;

        // 3. Ambil total biaya dari DB
        $totalBiaya = $booking->total_harga > 0 ? $booking->total_harga : 0;

        // 4. Load View dengan opsi REMOTE agar gambar muncul
        $pdf = Pdf::loadView('user.booking.booking_invoice_pdf', compact('booking', 'durasi', 'totalBiaya'))
            ->setOption([
                'isRemoteEnabled' => true, // WAJIB agar QR API muncul
                'chroot' => public_path(), // Agar file lokal di public/ bisa diakses
            ]);

        return $pdf->download('Invoice-' . $booking->kode_booking . '.pdf');
    }

    private function normalizeWhatsApp($nomor)
    {
        if (!$nomor) return null; // Pastikan jika kosong kembali NULL agar terdeteksi ?? '-' di Blade

        // Hapus semua karakter selain angka
        $nomor = preg_replace('/[^\d]/', '', $nomor);

        // Jika diawali '0', ubah jadi '62'
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        // Jika diawali '8' (langsung angka 8), tambahkan '62'
        elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }

    private function generateKodeBooking()
    {
        $tahun = date('Y');
        do {
            $last = Booking::where('kode_booking', 'like', "BOOK-$tahun-%")->latest('id')->first();
            $num = $last ? ((int)substr($last->kode_booking, -4) + 1) : 1;
            $kode = 'BOOK-' . $tahun . '-' . sprintf('%04d', $num);
        } while (Booking::where('kode_booking', $kode)->exists());
        return $kode;
    }
}
