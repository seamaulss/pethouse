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
        $notifications = Notification::where(function($query) use ($petugasId) {
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

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('role_target', 'petugas')
            ->firstOrFail();

        $notification->update(['is_read' => 1]);

        return redirect()->route('petugas.notifications.index')
            ->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }
}