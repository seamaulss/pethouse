<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function konsultasiQuery()
    {
        return Notification::where(function ($query) {
            // Mencari notifikasi yang ditujukan khusus untuk ID dokter ini
            // ATAU yang role_targetnya adalah 'dokter' (sebagai cadangan)
            $query->where('user_id', Auth::id())
                ->orWhere('role_target', 'dokter');
        })
            ->where(function ($q) {
                // Tetap pertahankan filter kata "Konsultasi" agar tidak bercampur dengan notifikasi lain
                $q->where('title', 'like', '%Konsultasi%')
                    ->orWhere('message', 'like', '%Konsultasi%');
            });
    }

    public function markAsRead($id)
    {
        try {
            Notification::where('id', $id)->update(['is_read' => 1]);
            $unreadCount = $this->konsultasiQuery()->where('is_read', 0)->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $this->konsultasiQuery()->where('is_read', 0)->update(['is_read' => 1]);

            // Gunakan back() untuk otomatis refresh halaman sebelumnya
            return back()->with('success', 'Semua notifikasi telah dibaca');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui notifikasi');
        }
    }

    public function getNewNotifications()
    {
        $unreadCount = $this->konsultasiQuery()->where('is_read', 0)->count();
        $latest = $this->konsultasiQuery()->orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'success' => true,
            'unreadCount' => $unreadCount,
            'notifications' => $latest
        ]);
    }
}
