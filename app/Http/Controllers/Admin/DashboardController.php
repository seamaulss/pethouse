<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Konsultasi;
use App\Models\Testimoni;
use App\Models\CatatanMedis;
use App\Models\DailyLogs;

class DashboardController extends Controller
{
    public function index()
    {
        $adminName = Auth::user()->username ?? Auth::user()->name;
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. Total Booking Bulan Ini
        $totalBookingBulanIni = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 2. Hewan Dititipkan Sekarang (status: in_progress atau diterima dengan tanggal masuk <= sekarang <= tanggal keluar)
        $hewanDititipkanSekarang = Booking::where(function ($query) use ($now) {
            $query->where('status', 'in_progress')
                ->orWhere(function ($q) use ($now) {
                    $q->where('status', 'diterima')
                        ->whereDate('tanggal_masuk', '<=', $now)
                        ->whereDate('tanggal_keluar', '>=', $now);
                });
        })
            ->count();

        // 3. Pending Konsultasi
        $pendingKonsultasi = Konsultasi::where('status', 'pending')
            ->count();

        // 4. Pendapatan Bulan Ini (hanya booking dengan status selesai)
        $pendapatanBulanIni = DB::table('booking as b')
            ->join('layanan_harga as lh', function ($join) {
                $join->on('b.layanan_id', '=', 'lh.layanan_id')
                    ->on('b.jenis_hewan_id', '=', 'lh.jenis_hewan_id');
            })
            ->whereBetween('b.tanggal_keluar', [$startOfMonth, $endOfMonth])
            ->where('b.status', 'selesai')
            ->select(DB::raw('SUM(lh.harga_per_hari * (DATEDIFF(b.tanggal_keluar, b.tanggal_masuk) + 1)) as total'))
            ->first()->total ?? 0;

        // 5. Occupancy Rate (asumsi kapasitas total 20)
        $kapasitasTotal = 20; // Ganti dengan data real dari tabel kapasitas jika ada
        $occupancyRate = $kapasitasTotal > 0 ?
            round(($hewanDititipkanSekarang / $kapasitasTotal) * 100, 1) : 0;

        // 6. Testimoni Baru (bulan ini)
        $testimoniBaru = Testimoni::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 7. Recent Activity (gabungan booking dan konsultasi)
        $recentBookings = Booking::with(['layanan'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                // Cek apakah ada kolom jenis_hewan atau gunakan fallback
                $jenisHewan = 'Unknown';
                if ($booking->jenis_hewan) {
                    // Jika ada kolom jenis_hewan langsung di booking
                    $jenisHewan = $booking->jenis_hewan;
                } elseif (method_exists($booking, 'jenisHewan') && $booking->jenisHewan) {
                    // Jika relasi jenisHewan ada
                    $jenisHewan = $booking->jenisHewan->nama ?? 'Unknown';
                } elseif (isset($booking->jenisHewan()->first()->nama)) {
                    // Jika relasi ada tetapi belum di-load
                    $jenisHewan = $booking->jenisHewan()->first()->nama ?? 'Unknown';
                }

                return [
                    'tipe' => 'booking',
                    'kode' => $booking->kode_booking,
                    'pelanggan' => $booking->nama_pemilik,
                    'detail' => $booking->nama_hewan . ' (' . $jenisHewan . ')',
                    'tanggal' => $booking->created_at,
                    'status' => $booking->status
                ];
            });

        $recentKonsultasi = Konsultasi::with(['dokter', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($konsultasi) {
                return [
                    'tipe' => 'konsultasi',
                    'kode' => $konsultasi->kode_konsultasi,
                    'pelanggan' => $konsultasi->nama_pemilik ?? ($konsultasi->user->username ?? 'N/A'),
                    'detail' => 'Topik: ' . ($konsultasi->topik ?? 'Tidak ada topik'),
                    'tanggal' => $konsultasi->created_at,
                    'status' => $konsultasi->status
                ];
            });

        // Gabungkan dan urutkan - PERBAIKAN DI SINI
        $recentActivity = collect()
            ->merge($recentBookings)
            ->merge($recentKonsultasi)
            ->sortByDesc('tanggal')
            ->take(5)
            ->values();

        // 8. Revenue Chart Data (7 hari terakhir)
        $revenueLabels = [];
        $revenueValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $revenueLabels[] = $date->format('D');

            $dailyRevenue = DB::table('booking as b')
                ->join('layanan_harga as lh', function ($join) {
                    $join->on('b.layanan_id', '=', 'lh.layanan_id')
                        ->on('b.jenis_hewan_id', '=', 'lh.jenis_hewan_id');
                })
                ->whereDate('b.tanggal_keluar', $date->toDateString())
                ->where('b.status', 'selesai')
                ->select(DB::raw('SUM(lh.harga_per_hari * (DATEDIFF(b.tanggal_keluar, b.tanggal_masuk) + 1)) as total'))
                ->first()->total ?? 0;

            $revenueValues[] = (int)$dailyRevenue;
        }

        // 9. Data Statistik Tambahan
        $totalHewanSelesaiBulanIni = Booking::whereBetween('tanggal_keluar', [$startOfMonth, $endOfMonth])
            ->where('status', 'selesai')
            ->count();

        $rataRataLamaInap = Booking::where('status', 'selesai')
            ->whereBetween('tanggal_keluar', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('AVG(DATEDIFF(tanggal_keluar, tanggal_masuk) + 1) as rata_rata'))
            ->first()->rata_rata ?? 0;

        // 10. Notifikasi Count dari tabel notifications untuk admin
        $notifCount = \App\Models\Notification::where('role_target', 'admin')
            ->where('is_read', false)
            ->count();

        // 11. Data Hewan Berdasarkan Jenis (PERBAIKAN: gunakan prefix tabel pada created_at)
        $jenisHewanStats = Booking::whereBetween('booking.created_at', [$startOfMonth, $endOfMonth])
            ->join('jenis_hewan', 'booking.jenis_hewan_id', '=', 'jenis_hewan.id')
            ->select('jenis_hewan.nama', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_hewan.nama')
            ->get();

        // 12. Ambil notifikasi terbaru untuk ditampilkan di dropdown
        $recentNotifications = \App\Models\Notification::with('booking')
            ->where('role_target', 'admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'adminName' => $adminName,
            'totalBookingBulanIni' => $totalBookingBulanIni,
            'hewanDititipkanSekarang' => $hewanDititipkanSekarang,
            'pendingKonsultasi' => $pendingKonsultasi,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'occupancyRate' => $occupancyRate,
            'testimoniBaru' => $testimoniBaru,
            'notifCount' => $notifCount,
            'recentActivity' => $recentActivity,
            'revenueLabels' => $revenueLabels,
            'revenueValues' => $revenueValues,
            'totalHewanSelesaiBulanIni' => $totalHewanSelesaiBulanIni,
            'rataRataLamaInap' => round($rataRataLamaInap, 1),
            'jenisHewanStats' => $jenisHewanStats,
            'kapasitasTotal' => $kapasitasTotal,
            'notifCount' => $notifCount,
            'recentNotifications' => $recentNotifications,
        ];

        return view('admin.dashboard', $data);
    }

    // Method untuk mark notification as read
    public function markAsRead($id)
    {
        $notification = \App\Models\Notification::find($id);

        if ($notification && $notification->role_target === 'admin') {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
    }

    // Method untuk mark all notifications as read
    public function markAllAsRead()
    {
        \App\Models\Notification::where('role_target', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // Method untuk mendapatkan count notifikasi yang belum dibaca
    public function getNotificationCount()
    {
        $count = \App\Models\Notification::where('role_target', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
