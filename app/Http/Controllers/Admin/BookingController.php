<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\JenisHewan;
use App\Models\LayananHarga;
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
        $status = $request->status;

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

        // Create notification for user
        if ($booking->user_id) {
            \App\Models\Notification::create([
                'user_id' => $booking->user_id,
                'role_target' => 'user',
                'title' => 'Status Booking Diperbarui',
                'message' => "Booking {$booking->kode_booking} statusnya diubah menjadi " . $booking->status_text,
                'booking_id' => $booking->id,
                'is_read' => false
            ]);
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
            // Hitung harga tambahan untuk perpanjangan
            $hargaTambahan = 0;
            $jenisHewan = JenisHewan::where('nama', $booking->jenis_hewan)->first();
            
            if ($jenisHewan) {
                $layananHarga = LayananHarga::where('layanan_id', $booking->layanan_id)
                    ->where('jenis_hewan_id', $jenisHewan->id)
                    ->first();
                
                if ($layananHarga) {
                    // Hitung durasi tambahan
                    $durasiLama = Carbon::parse($booking->tanggal_masuk)
                        ->diffInDays(Carbon::parse($booking->tanggal_keluar));
                    $durasiBaru = Carbon::parse($booking->tanggal_masuk)
                        ->diffInDays(Carbon::parse($request->tanggal_perpanjangan));
                    $durasiTambahan = $durasiBaru - $durasiLama;
                    
                    $hargaTambahan = $durasiTambahan * $layananHarga->harga_per_hari;
                }
            }

            // Update tanggal keluar baru dan total harga
            $booking->update([
                'tanggal_keluar' => $request->tanggal_perpanjangan,
                'total_harga' => $booking->total_harga + $hargaTambahan,
                'status' => 'in_progress',
                'alasan_perpanjangan' => null,
                'tanggal_perpanjangan' => null
            ]);
            
            // Notification to user
            if ($booking->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $booking->user_id,
                    'role_target' => 'user',
                    'title' => 'Perpanjangan Diterima',
                    'message' => "Perpanjangan booking {$booking->kode_booking} telah diterima hingga " . 
                                Carbon::parse($request->tanggal_perpanjangan)->format('d M Y') .
                                " dengan biaya tambahan Rp " . number_format($hargaTambahan, 0, ',', '.') .
                                ". Total harga baru: Rp " . number_format($booking->total_harga, 0, ',', '.'),
                    'booking_id' => $booking->id,
                    'is_read' => false
                ]);
            }
            
            $message = 'Perpanjangan booking telah diterima.';
        } else {
            // Tolak perpanjangan - kembalikan ke status sebelumnya
            $previousStatus = $booking->status === 'perpanjangan' ? 'in_progress' : $booking->status;
            
            $booking->update([
                'status' => $previousStatus,
                'alasan_perpanjangan' => null,
                'tanggal_perpanjangan' => null
            ]);
            
            // Notification to user
            if ($booking->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $booking->user_id,
                    'role_target' => 'user',
                    'title' => 'Perpanjangan Ditolak',
                    'message' => "Permintaan perpanjangan booking {$booking->kode_booking} telah ditolak.",
                    'booking_id' => $booking->id,
                    'is_read' => false
                ]);
            }
            
            $message = 'Perpanjangan booking telah ditolak.';
        }

        return redirect()->route('admin.booking.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking berhasil dihapus!');
    }
}