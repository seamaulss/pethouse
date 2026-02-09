<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    protected $userActivityTypes = ['booking', 'konsultasi'];

    public function getNotificationCount()
    {
        $count = Notification::where('role_target', 'admin')
            ->whereIn('type', $this->userActivityTypes)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}