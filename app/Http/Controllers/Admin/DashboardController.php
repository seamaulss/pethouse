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
use App\Models\Notification;

class DashboardController extends Controller
{
    // Filter kategori notifikasi yang dipicu oleh tindakan User
    protected $userActivityTypes = ['booking', 'konsultasi'];

    public function index(Request $request)
    {
        $adminName = Auth::user()->username ?? Auth::user()->name;
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. Total Booking Bulan Ini
        $totalBookingBulanIni = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // 2. Hewan Dititipkan Sekarang
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

        // 3. Selesai Konsultasi
        $selesaiKonsultasi = Konsultasi::where('status', 'selesai')->count();

        // 4. Pendapatan Bulan Ini
        $pendapatanBulanIni = $this->hitungPendapatanBulanIni($startOfMonth, $endOfMonth);

        // 5. Occupancy Rate
        $kapasitasTotal = \App\Models\Kapasitas::sum('max_kapasitas');
        $occupancyRate = $kapasitasTotal > 0
            ? round(($hewanDititipkanSekarang / $kapasitasTotal) * 100, 1)
            : 0;

        // 6. Testimoni Baru
        $testimoniBaru = Testimoni::where('status', 'aktif')->count();

        // 7. Recent Activity (Tetap 5 Transaksi Terakhir secara umum)
        $recentBookings = Booking::with(['layanan'])->orderBy('created_at', 'desc')->limit(5)->get()
            ->map(fn($b) => [
                'tipe' => 'booking',
                'kode' => $b->kode_booking,
                'pelanggan' => $b->nama_pemilik,
                'detail' => $b->nama_hewan . " ({$b->jenis_hewan})",
                'tanggal' => $b->created_at,
                'status' => $b->status
            ]);

        $recentKonsultasi = Konsultasi::orderBy('created_at', 'desc')->limit(5)->get()
            ->map(fn($k) => [
                'tipe' => 'konsultasi',
                'kode' => $k->kode_konsultasi,
                'pelanggan' => $k->nama_pemilik,
                'detail' => 'Topik: ' . ($k->topik ?? 'N/A'),
                'tanggal' => $k->created_at,
                'status' => $k->status
            ]);

        $recentActivity = collect()->merge($recentBookings)->merge($recentKonsultasi)->sortByDesc('tanggal')->take(10)->values();

        // --- 8. REVENUE CHART DATA (DENGAN FILTER) ---
        $revenueData = $this->hitungPendapatanChart($request);

        // 9. Statistik Tambahan
        $totalHewanSelesaiBulanIni = Booking::whereBetween('tanggal_keluar', [$startOfMonth, $endOfMonth])->where('status', 'selesai')->count();
        $rataRataLamaInap = Booking::where('status', 'selesai')->whereBetween('tanggal_keluar', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('AVG(DATEDIFF(tanggal_keluar, tanggal_masuk) + 1) as rata_rata'))->first()->rata_rata ?? 0;

        // 10. Notifikasi Admin
        $notifCount = Notification::where('role_target', 'admin')
            ->whereIn('type', $this->userActivityTypes)
            ->where('is_read', false)
            ->count();

        // 11. Jenis Hewan Stats
        $jenisHewanStats = DB::table('booking')
            ->join('jenis_hewan', 'booking.jenis_hewan_id', '=', 'jenis_hewan.id')
            ->whereBetween('booking.created_at', [$startOfMonth, $endOfMonth])
            ->select('jenis_hewan.nama', DB::raw('COUNT(*) as total'))->groupBy('jenis_hewan.nama')->get();

        // 12. Notifikasi Dropdown
        $recentNotifications = Notification::with('booking')
            ->where('role_target', 'admin')
            ->whereIn('type', $this->userActivityTypes)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'adminName',
            'totalBookingBulanIni',
            'hewanDititipkanSekarang',
            'selesaiKonsultasi',
            'pendapatanBulanIni',
            'occupancyRate',
            'testimoniBaru',
            'notifCount',
            'recentActivity',
            'totalHewanSelesaiBulanIni',
            'rataRataLamaInap',
            'jenisHewanStats',
            'kapasitasTotal',
            'recentNotifications'
        ) + ['revenueLabels' => $revenueData['labels'], 'revenueValues' => $revenueData['values']]);
    }

    // --- Private Methods untuk Hitung Pendapatan ---
    private function hitungPendapatanBulanIni($start, $end)
    {
        return Booking::whereBetween('tanggal_masuk', [$start, $end])
            ->where('dp_dibayar', 'Ya')
            ->whereIn('status', ['diterima', 'in_progress', 'selesai'])
            ->sum('total_harga');
    }

    /**
     * Logika Filter Chart Pendapatan Dinamis
     */
    private function hitungPendapatanChart(Request $request)
    {
        $labels = [];
        $values = [];

        // 1. Logika Penentuan Tanggal
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : $startDate->copy()->addDays(6);
        $tipe = $request->type ?? 'all';

        // Hitung selisih hari untuk looping
        $diffInDays = $startDate->diffInDays($endDate);
        
        // Batasi maksimal 31 hari agar chart tidak hancur/terlalu rapat
        $limit = ($diffInDays > 31) ? 31 : $diffInDays;

        for ($i = 0; $i <= $limit; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->locale('id')->translatedFormat('d M'); // Format: 10 Mar

            $income = 0;

            // Jika filter "Semua" atau "Booking"
            if ($tipe == 'all' || $tipe == 'booking') {
                $income += (int) Booking::whereDate('tanggal_masuk', $date->toDateString())
                    ->where('dp_dibayar', 'Ya')
                    ->whereIn('status', ['diterima', 'in_progress', 'selesai'])
                    ->sum('total_harga');
            }

            // Jika filter "Semua" atau "Konsultasi"
            // Catatan: Pastikan tabel konsultasi punya kolom biaya/total_harga jika ingin dihitung
            if ($tipe == 'all' || $tipe == 'konsultasi') {
                // Contoh jika konsultasi ada biaya (asumsi kolom 'harga' di tabel Konsultasi)
                // $income += (int) Konsultasi::whereDate('created_at', $date->toDateString())
                //     ->where('status', 'selesai')
                //     ->sum('harga');
            }

            $values[] = $income;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    // --- Notification Actions ---
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->role_target === 'admin') {
            $notification->update(['is_read' => true]);
            $count = Notification::where('role_target', 'admin')->whereIn('type', $this->userActivityTypes)->where('is_read', false)->count();
            return response()->json(['success' => true, 'unread_count' => $count]);
        }
        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        Notification::where('role_target', 'admin')->whereIn('type', $this->userActivityTypes)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}