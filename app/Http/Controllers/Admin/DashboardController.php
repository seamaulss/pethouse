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
            ->where('dp_dibayar', 'Ya')
            ->count();

        // 3. Pending Konsultasi
        $pendingKonsultasi = Konsultasi::where('status', 'pending')
            ->count();

        // 4. Pendapatan Bulan Ini - PERBAIKAN BESAR
        $pendapatanBulanIni = $this->hitungPendapatanBulanIni($startOfMonth, $endOfMonth);

        // 5. Occupancy Rate (asumsi kapasitas total 20)
        $kapasitasTotal = 20;
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
                return [
                    'tipe' => 'booking',
                    'kode' => $booking->kode_booking,
                    'pelanggan' => $booking->nama_pemilik,
                    'detail' => $booking->nama_hewan . ' (' . $booking->jenis_hewan . ')',
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

        // Gabungkan dan urutkan
        $recentActivity = collect()
            ->merge($recentBookings)
            ->merge($recentKonsultasi)
            ->sortByDesc('tanggal')
            ->take(5)
            ->values();

        // 8. Revenue Chart Data (7 hari terakhir) - PERBAIKAN BESAR
        $revenueData = $this->hitungPendapatan7Hari($now);

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

        // 11. Data Hewan Berdasarkan Jenis
        $jenisHewanStats = DB::table('booking')
            ->join('jenis_hewan', 'booking.jenis_hewan_id', '=', 'jenis_hewan.id')
            ->whereBetween('booking.created_at', [$startOfMonth, $endOfMonth])
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
            'revenueLabels' => $revenueData['labels'],
            'revenueValues' => $revenueData['values'],
            'totalHewanSelesaiBulanIni' => $totalHewanSelesaiBulanIni,
            'rataRataLamaInap' => round($rataRataLamaInap, 1),
            'jenisHewanStats' => $jenisHewanStats,
            'kapasitasTotal' => $kapasitasTotal,
            'recentNotifications' => $recentNotifications,
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Hitung pendapatan bulan ini dengan logika yang benar
     */
    private function hitungPendapatanBulanIni($startOfMonth, $endOfMonth)
    {
        // Method 1: Gunakan total_harga yang sudah ada (PILIHAN TERBAIK)
        $pendapatanDariTotalHarga = Booking::whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])
            ->where('dp_dibayar', 'Ya')
            ->whereIn('status', ['diterima', 'in_progress', 'selesai'])
            ->sum('total_harga');

        // Jika total_harga tidak ada, hitung manual
        if ($pendapatanDariTotalHarga > 0) {
            return $pendapatanDariTotalHarga;
        }

        // Method 2: Hitung manual dengan join yang benar
        return DB::table('booking as b')
            ->join('layanan_harga as lh', function ($join) {
                $join->on('b.layanan_id', '=', 'lh.layanan_id')
                    ->whereRaw('lh.jenis_hewan_id = 
                        CASE 
                            WHEN b.jenis_hewan = "Anjing" THEN 2
                            WHEN b.jenis_hewan = "Kucing" THEN 1
                            ELSE lh.jenis_hewan_id
                        END');
            })
            ->whereBetween('b.tanggal_masuk', [$startOfMonth, $endOfMonth])
            ->where('b.dp_dibayar', 'Ya')
            ->whereIn('b.status', ['diterima', 'in_progress', 'selesai'])
            ->select(DB::raw('SUM(
                CASE 
                    WHEN DATEDIFF(b.tanggal_keluar, b.tanggal_masuk) = 0 THEN 1
                    ELSE DATEDIFF(b.tanggal_keluar, b.tanggal_masuk)
                END * lh.harga_per_hari
            ) as total'))
            ->first()->total ?? 0;
    }

    /**
     * Hitung pendapatan 7 hari terakhir
     */
    private function hitungPendapatan7Hari($now)
    {
        $revenueLabels = [];
        $revenueValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $revenueLabels[] = $date->format('D');

            // Method 1: Gunakan total_harga
            $dailyRevenue = Booking::whereDate('tanggal_masuk', $date->toDateString())
                ->where('dp_dibayar', 'Ya')
                ->whereIn('status', ['diterima', 'in_progress', 'selesai'])
                ->sum('total_harga');

            // Jika total_harga tidak ada, hitung manual
            if ($dailyRevenue == 0) {
                $dailyRevenue = DB::table('booking as b')
                    ->join('layanan_harga as lh', function ($join) {
                        $join->on('b.layanan_id', '=', 'lh.layanan_id')
                            ->whereRaw('lh.jenis_hewan_id = 
                                CASE 
                                    WHEN b.jenis_hewan = "Anjing" THEN 2
                                    WHEN b.jenis_hewan = "Kucing" THEN 1
                                    ELSE lh.jenis_hewan_id
                                END');
                    })
                    ->whereDate('b.tanggal_masuk', $date->toDateString())
                    ->where('b.dp_dibayar', 'Ya')
                    ->whereIn('b.status', ['diterima', 'in_progress', 'selesai'])
                    ->select(DB::raw('SUM(
                        CASE 
                            WHEN DATEDIFF(b.tanggal_keluar, b.tanggal_masuk) = 0 THEN 1
                            ELSE DATEDIFF(b.tanggal_keluar, b.tanggal_masuk)
                        END * lh.harga_per_hari
                    ) as total'))
                    ->first()->total ?? 0;
            }

            $revenueValues[] = (int)$dailyRevenue;
        }

        return [
            'labels' => $revenueLabels,
            'values' => $revenueValues
        ];
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