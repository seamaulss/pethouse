<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // 1. Set Timezone & Locale Indonesia secara Global
        config(['app.locale' => 'id']);
        config(['app.timezone' => 'Asia/Jakarta']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        // Force HTTPS untuk ngrok
        if (str_contains(request()->getHost(), 'jacquelin-humpier-uncandidly.ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        // View Composer untuk user-navbar
        View::composer('partials.user-navbar', function ($view) {
            $jml_notif = 0;
            if (Auth::check()) {
                $user = Auth::user();
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