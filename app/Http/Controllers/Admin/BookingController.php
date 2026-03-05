<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\JenisHewan;
use App\Models\Kapasitas;
use App\Models\LayananHarga;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    /**
     * Helper untuk konsistensi filter antara halaman index dan export PDF.
     */
    private function applyFilters($query, Request $request)
    {
        return $query->when($request->search, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('kode_booking', 'like', "%{$request->search}%")
                    ->orWhere('nama_pemilik', 'like', "%{$request->search}%")
                    ->orWhere('nama_hewan', 'like', "%{$request->search}%");
            });
        })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            // TAMBAHKAN LOGIKA BARU:
            ->when($request->date, function ($q) use ($request) {
                // Menampilkan data yang TANGGAL MASUK-nya tepat pada tanggal yang dipilih
                return $q->whereDate('tanggal_masuk', $request->date);
            });
    }

    public function index(Request $request)
    {
        $query = Booking::with(['layanan', 'layanan.hargas', 'petugas']);
        $query = $this->applyFilters($query, $request);

        // Menggunakan clone agar query utama tidak terganggu untuk pagination
        $bookings = (clone $query)->orderBy('id', 'desc')->paginate(10);
        $petugas = User::where('role', 'petugas')->get();

        // Statistik Dinamis (Berubah sesuai filter yang aktif)
        $stats = [
            'total'        => (clone $query)->count(),
            'pending'      => (clone $query)->where('status', 'pending')->count(),
            'diterima'     => (clone $query)->where('status', 'diterima')->count(),
            'in_progress'  => (clone $query)->where('status', 'in_progress')->count(),
            'selesai'      => (clone $query)->where('status', 'selesai')->count(),
            'pembatalan'   => (clone $query)->where('status', 'pembatalan')->count(),
            'perpanjangan' => (clone $query)->where('status', 'perpanjangan')->count(),
        ];

        return view('admin.booking.index', compact('bookings', 'petugas', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $query = Booking::with('layanan');
        $query = $this->applyFilters($query, $request);

        $data = $query->orderBy('tanggal_masuk', 'asc')->get();

        // Menghitung total pendapatan khusus status 'selesai' dari data yang difilter
        $totalPendapatan = $data->where('status', 'selesai')->sum('total_harga');

        $pdf = Pdf::loadView('admin.booking.pdf', [
            'data' => $data,
            'date' => $request->date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_pendapatan' => $totalPendapatan
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan-LARAPetHouse-' . now()->format('Ymd') . '.pdf');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,in_progress,selesai,pembatalan,perpanjangan'
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        $status = $request->status;

        $statusMessages = [
            'pending' => 'Menunggu Konfirmasi',
            'diterima' => 'Diterima',
            'in_progress' => 'Sedang Berjalan',
            'selesai' => 'Selesai',
            'pembatalan' => 'Dibatalkan',
            'perpanjangan' => 'Permintaan Perpanjangan'
        ];

        $data = ['status' => $status];

        if ($request->has('alasan_cancel')) {
            $data['alasan_cancel'] = $request->alasan_cancel;
        }

        if ($request->has('alasan_perpanjangan')) {
            $data['alasan_perpanjangan'] = $request->alasan_perpanjangan;
        }

        if ($request->has('tanggal_perpanjangan')) {
            $data['tanggal_perpanjangan'] = $request->tanggal_perpanjangan;
        }

        if ($status === 'in_progress') {
            $request->validate([
                'petugas_id' => 'required|exists:users,id'
            ]);
            $data['petugas_id'] = $request->petugas_id;
        }

        if ($status === 'pembatalan') {
            $data['petugas_id'] = null;
        }

        $booking->update($data);

        if ($booking->user_id) {
            $title = "Status Booking Diperbarui";

            switch ($status) {
                case 'diterima':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DITERIMA. Siapkan hewan Anda pada tanggal " .
                        Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y');
                    $type = 'success';
                    break;

                case 'in_progress':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DIMULAI.";
                    $type = 'info';
                    break;

                case 'selesai':
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah SELESAI. Total: Rp " .
                        number_format($booking->total_harga, 0, ',', '.');
                    $type = 'success';
                    break;

                case 'pembatalan':
                    $alasan = $request->alasan_cancel ?? 'tidak disebutkan';
                    $message = "Booking #{$booking->kode_booking} untuk {$booking->nama_hewan} telah DIBATALKAN. Alasan: {$alasan}";
                    $type = 'warning';
                    break;

                default:
                    $message = "Status booking #{$booking->kode_booking} diubah menjadi " . ($statusMessages[$status] ?? $status);
                    $type = 'info';
            }

            Notification::createForUser($booking->user_id, $title, $message, $booking->id, $type);
        }

        return redirect()->route('admin.booking.index')->with('success', 'Status booking berhasil diperbarui!');
    }

    public function handleExtension(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak',
            'tanggal_perpanjangan' => 'required_if:action,terima|date'
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->action === 'terima') {
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
                return back()->with('error', "Gagal menerima perpanjangan! Slot sudah penuh (Maks: $max).");
            }

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
            $booking->update(['status' => 'in_progress', 'alasan_perpanjangan' => null, 'tanggal_perpanjangan' => null]);
            return redirect()->route('admin.booking.index')->with('warning', 'Perpanjangan ditolak.');
        }
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id) {
            Notification::createForUser(
                $booking->user_id,
                'Booking Dihapus',
                "Booking #{$booking->kode_booking} telah dihapus dari sistem.",
                $booking->id,
                'warning'
            );
        }

        $booking->delete();
        return redirect()->route('admin.booking.index')->with('success', 'Booking berhasil dihapus!');
    }
}
