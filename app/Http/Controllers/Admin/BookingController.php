<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\JenisHewan;
use App\Models\Kapasitas;
use App\Models\LayananHarga;
use App\Models\Notification; // Tambahkan ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $bookings = Booking::with(['layanan', 'layanan.hargas', 'petugas'])
            ->when($search, function ($query) use ($search) {
                $query->where('kode_booking', 'like', "%{$search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$search}%")
                    ->orWhere('nama_hewan', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $petugas = User::where('role', 'petugas')->get();

        // Stats for dashboard
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'diterima' => Booking::where('status', 'diterima')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'selesai' => Booking::where('status', 'selesai')->count(),
            'pembatalan' => Booking::where('status', 'pembatalan')->count(),
            'perpanjangan' => Booking::where('status', 'perpanjangan')->count(),
        ];

        return view('admin.booking.index', compact('bookings', 'petugas', 'search', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,in_progress,selesai,pembatalan,perpanjangan'
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        $status = $request->status;

        // Mapping status untuk notifikasi
        $statusMessages = [
            'pending' => 'Menunggu Konfirmasi',
            'diterima' => 'Diterima',
            'in_progress' => 'Sedang Berjalan',
            'selesai' => 'Selesai',
            'pembatalan' => 'Dibatalkan',
            'perpanjangan' => 'Permintaan Perpanjangan'
        ];

        $data = ['status' => $status];

        // Jika ada alasan cancel
        if ($request->has('alasan_cancel')) {
            $data['alasan_cancel'] = $request->alasan_cancel;
        }

        // Jika ada alasan perpanjangan
        if ($request->has('alasan_perpanjangan')) {
            $data['alasan_perpanjangan'] = $request->alasan_perpanjangan;
        }

        // Jika ada tanggal perpanjangan
        if ($request->has('tanggal_perpanjangan')) {
            $data['tanggal_perpanjangan'] = $request->tanggal_perpanjangan;
        }

        // Jika status in_progress, tambahkan petugas
        if ($status === 'in_progress') {
            $request->validate([
                'petugas_id' => 'required|exists:users,id'
            ]);
            $data['petugas_id'] = $request->petugas_id;
        }

        // Jika status pembatalan, bisa hapus petugas
        if ($status === 'pembatalan') {
            $data['petugas_id'] = null;
        }

        $booking->update($data);

        // Buat notifikasi untuk user berdasarkan status baru
        if ($booking->user_id) {
            $title = "Status Booking Diperbarui";

            // Custom message berdasarkan status
            switch ($status) {
                case 'diterima':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DITERIMA. Siapkan hewan Anda untuk penitipan pada tanggal " .
                        Carbon::parse($booking->tanggal_masuk)->format('d M Y');
                    $type = 'success';
                    break;

                case 'in_progress':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DIMULAI. Anda akan menerima update harian selama penitipan.";
                    $type = 'info';
                    break;

                case 'selesai':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah SELESAI. Hewan Anda sudah bisa diambil. Total biaya: Rp " .
                        number_format($booking->total_harga, 0, ',', '.');
                    $type = 'success';
                    break;

                case 'pembatalan':
                    $alasan = $request->alasan_cancel ?? 'tidak disebutkan';
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DIBATALKAN. Alasan: {$alasan}";
                    $type = 'warning';
                    break;

                default:
                    $message = "Status booking #{$booking->kode_booking} telah diubah dari " .
                        ($statusMessages[$oldStatus] ?? $oldStatus) . " menjadi " .
                        ($statusMessages[$status] ?? $status);
                    $type = 'info';
            }

            Notification::createForUser(
                $booking->user_id,
                $title,
                $message,
                $booking->id,
                $type
            );
        }

        return redirect()->route('admin.booking.index')
            ->with('success', 'Status booking berhasil diperbarui!');
    }

    public function handleExtension(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak',
            'tanggal_perpanjangan' => 'required_if:action,terima|date'
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->action === 'terima') {
            // PROTEKSI: Cek kapasitas sebelum menerima perpanjangan
            $terisi = Booking::where('layanan_id', $booking->layanan_id)
                ->where('jenis_hewan', $booking->jenis_hewan)
                ->where('ukuran_hewan', $booking->ukuran_hewan)
                ->where('id', '!=', $id)
                ->whereIn('status', ['pending', 'diterima', 'in_progress', 'perpanjangan'])
                ->where(function ($q) use ($booking, $request) {
                    $q->where('tanggal_masuk', '<=', $request->tanggal_perpanjangan)
                        ->where('tanggal_keluar', '>=', $booking->tanggal_masuk);
                })->count();

            $max = Kapasitas::where('layanan_id', $booking->layanan_id)
                ->where('jenis_hewan', $booking->jenis_hewan)
                ->where('ukuran_hewan', $booking->ukuran_hewan)
                ->value('max_kapasitas');

            if ($terisi >= $max) {
                return back()->with('error', "Gagal menerima perpanjangan! Slot sudah penuh dipesan orang lain (Maks: $max).");
            }

            // Hitung Biaya Tambahan
            $hargaTambahan = 0;
            $jh = JenisHewan::where('nama', $booking->jenis_hewan)->first();
            if ($jh) {
                $lh = LayananHarga::where('layanan_id', $booking->layanan_id)->where('jenis_hewan_id', $jh->id)->first();
                if ($lh) {
                    $durasiLama = Carbon::parse($booking->tanggal_masuk)->diffInDays(Carbon::parse($booking->tanggal_keluar));
                    $durasiBaru = Carbon::parse($booking->tanggal_masuk)->diffInDays(Carbon::parse($request->tanggal_perpanjangan));
                    $hargaTambahan = ($durasiBaru - $durasiLama) * $lh->harga_per_hari;
                }
            }

            $booking->update([
                'tanggal_keluar' => $request->tanggal_perpanjangan,
                'total_harga' => $booking->total_harga + $hargaTambahan,
                'status' => 'in_progress',
                'alasan_perpanjangan' => null,
                'tanggal_perpanjangan' => null
            ]);

            return redirect()->route('admin.booking.index')->with('success', 'Perpanjangan diterima.');
        } else {
            // Tolak
            $booking->update(['status' => 'in_progress', 'alasan_perpanjangan' => null, 'tanggal_perpanjangan' => null]);
            return redirect()->route('admin.booking.index')->with('warning', 'Perpanjangan ditolak.');
        }
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Kirim notifikasi sebelum menghapus
        if ($booking->user_id) {
            Notification::createForUser(
                $booking->user_id,
                'Booking Dihapus',
                "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah dihapus dari sistem.",
                $booking->id,
                'warning'
            );
        }

        $booking->delete();

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil dihapus!');
    }
}
