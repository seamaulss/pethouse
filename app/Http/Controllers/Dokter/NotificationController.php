<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Helper untuk filter query Konsultasi
     */
    private function konsultasiQuery()
    {
        return Notification::where('role_target', 'admin')
            ->where(function($q) {
                $q->where('title', 'like', '%Konsultasi%')
                  ->orWhere('message', 'like', '%Konsultasi%');
            });
    }

    public function index()
    {
        $notifications = $this->konsultasiQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dokter.notifikasi.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update(['is_read' => true]);

            // Hitung sisa unread khusus konsultasi
            $unreadCount = $this->konsultasiQuery()->where('is_read', false)->count();

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
            $this->konsultasiQuery()
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getNewNotifications()
    {
        $unreadCount = $this->konsultasiQuery()
            ->where('is_read', false)
            ->count();

        $latest = $this->konsultasiQuery()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'unreadCount' => $unreadCount,
            'notifications' => $latest
        ]);
    }
}