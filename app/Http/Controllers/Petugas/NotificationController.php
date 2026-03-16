<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $petugasId = Auth::id();

        // Ambil notifikasi untuk petugas
        $notifications = Notification::where(function ($query) use ($petugasId) {
            $query->where('user_id', $petugasId)
                ->orWhereNull('user_id')
                ->orWhere('user_id', 0);
        })
            ->where('role_target', 'petugas')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.notifications', compact('notifications'));
    }

    /**
     * Method baru untuk melayani request AJAX dari Dashboard
     * Memperbaiki Error 500 & Unexpected Token <
     */
    public function getNewNotifications()
    {
        try {
            $petugasId = Auth::id();

            // Ambil notifikasi terbaru (5 terakhir saja agar ringan)
            $notifications = Notification::where(function ($query) use ($petugasId) {
                $query->where('user_id', $petugasId)
                    ->orWhereNull('user_id')
                    ->orWhere('user_id', 0);
            })
                ->where('role_target', 'petugas')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Hitung yang belum dibaca
            $unreadCount = Notification::where(function ($query) use ($petugasId) {
                $query->where('user_id', $petugasId)
                    ->orWhereNull('user_id')
                    ->orWhere('user_id', 0);
            })
                ->where('role_target', 'petugas')
                ->where('is_read', 0)
                ->count();

            // Kembalikan data dalam format JSON murni
            return response()->json([
                'success' => true,
                'unreadCount' => $unreadCount,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('role_target', 'petugas')
            ->firstOrFail();

        $notification->update(['is_read' => 1]);

        // Cek jika request datang dari AJAX (tombol di dropdown)
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('petugas.notifications.index')
            ->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }
}
