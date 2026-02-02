<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Pastikan menggunakan facade View yang sudah diimport
        View::composer('user.partials.navbar', function ($view) {
            if (auth()->check() && auth()->user()->role === 'user') {
                // Cek apakah model sudah ada sebelum digunakan
                if (class_exists('App\Models\KonsultasiBalasan')) {
                    $jml_notif = \App\Models\KonsultasiBalasan::join('konsultasi', 'konsultasi_balasan.konsultasi_id', '=', 'konsultasi.id')
                        ->where('konsultasi.user_id', auth()->id())
                        ->where('konsultasi_balasan.pengirim', 'dokter')
                        ->where('konsultasi_balasan.dibaca_user', 0)
                        ->count();
                } else {
                    $jml_notif = 0; // Default jika model belum ada
                }
                $view->with('jml_notif', $jml_notif);
            }
        });
    }
}