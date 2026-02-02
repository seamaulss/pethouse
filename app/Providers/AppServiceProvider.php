<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {

        // View Composer untuk user-navbar
        View::composer('partials.user-navbar', function ($view) {
            $jml_notif = 0;
            if (Auth::check()) {
                $user = Auth::user();
                // Jika user adalah role 'user', hitung notifikasi
                if ($user->role === 'user') {
                    $jml_notif = Booking::where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count();
                }
            }
            $view->with('jml_notif', $jml_notif);
        });
    }
}
