<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\HewanSayaController;
use App\Http\Controllers\User\KonsultasiController;
use App\Http\Controllers\User\ProfilController;
use App\Http\Controllers\User\CekStatusController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\Petugas\InputLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;


// ======================
// ROUTE PUBLIC (Tidak Perlu Login)
// ======================

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('galeri');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

// ======================
// ROUTE AUTH (Breeze)
// ======================

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// ======================
// ROUTE ADMIN
// ======================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Hanya gunakan route yang benar-benar dibutuhkan
    Route::get('/booking', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('booking.index');
    Route::put('/booking/{id}', [App\Http\Controllers\Admin\BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('booking.destroy');
    // Booking routes
    Route::resource('booking', \App\Http\Controllers\Admin\BookingController::class);
    Route::post('booking/{booking}/handle-extension', [\App\Http\Controllers\Admin\BookingController::class, 'handleExtension'])
        ->name('booking.handle-extension');

    // Route untuk admin konsultasi
    Route::get('/konsultasi', [App\Http\Controllers\Admin\KonsultasiController::class, 'index'])->name('konsultasi.index');
    Route::get('/konsultasi/{id}', [App\Http\Controllers\Admin\KonsultasiController::class, 'show'])->name('konsultasi.show');
    Route::get('/konsultasi/{id}/edit', [App\Http\Controllers\Admin\KonsultasiController::class, 'edit'])->name('konsultasi.edit');
    Route::put('/konsultasi/{id}', [App\Http\Controllers\Admin\KonsultasiController::class, 'update'])->name('konsultasi.update');
    Route::delete('/konsultasi/{id}', [App\Http\Controllers\Admin\KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');
    Route::post('/konsultasi/{id}/add-balasan', [KonsultasiController::class, 'updateBalasan'])->name('konsultasi.add-balasan');

    // Jenis Hewan
    Route::get('/jenis-hewan', [App\Http\Controllers\Admin\JenisHewanController::class, 'index'])->name('jenis-hewan.index');
    Route::get('/jenis-hewan/create', [App\Http\Controllers\Admin\JenisHewanController::class, 'create'])->name('jenis-hewan.create');
    Route::post('/jenis-hewan', [App\Http\Controllers\Admin\JenisHewanController::class, 'store'])->name('jenis-hewan.store');
    Route::get('/jenis-hewan/{id}/edit', [App\Http\Controllers\Admin\JenisHewanController::class, 'edit'])->name('jenis-hewan.edit');
    Route::put('/jenis-hewan/{id}', [App\Http\Controllers\Admin\JenisHewanController::class, 'update'])->name('jenis-hewan.update');
    Route::delete('/jenis-hewan/{id}', [App\Http\Controllers\Admin\JenisHewanController::class, 'destroy'])->name('jenis-hewan.destroy');

    // Layanan
    Route::get('/layanan', [App\Http\Controllers\Admin\LayananController::class, 'index'])->name('layanan.index');
    Route::get('/layanan/create', [App\Http\Controllers\Admin\LayananController::class, 'create'])->name('layanan.create');
    Route::post('/layanan', [App\Http\Controllers\Admin\LayananController::class, 'store'])->name('layanan.store');
    Route::get('/layanan/{id}/edit', [App\Http\Controllers\Admin\LayananController::class, 'edit'])->name('layanan.edit');
    Route::put('/layanan/{id}', [App\Http\Controllers\Admin\LayananController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{id}', [App\Http\Controllers\Admin\LayananController::class, 'destroy'])->name('layanan.destroy');

    // Route untuk atur harga
    Route::get('/layanan/{id}/atur-harga', [App\Http\Controllers\Admin\LayananController::class, 'aturHarga'])->name('layanan.atur-harga');
    Route::post('/layanan/{id}/simpan-harga', [App\Http\Controllers\Admin\LayananController::class, 'simpanHarga'])->name('layanan.simpan-harga');

    // Galeri Admin
    Route::get('/galeri', [App\Http\Controllers\Admin\GaleriController::class, 'index'])->name('galeri.index');
    Route::get('/galeri/create', [App\Http\Controllers\Admin\GaleriController::class, 'create'])->name('galeri.create');
    Route::post('/galeri', [App\Http\Controllers\Admin\GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{id}/edit', [App\Http\Controllers\Admin\GaleriController::class, 'edit'])->name('galeri.edit');
    Route::put('/galeri/{id}', [App\Http\Controllers\Admin\GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{id}', [App\Http\Controllers\Admin\GaleriController::class, 'destroy'])->name('galeri.destroy');

    // Testimoni Admin
    Route::get('/testimoni', [App\Http\Controllers\Admin\TestimoniController::class, 'index'])->name('testimoni.index');
    Route::get('/testimoni/create', [App\Http\Controllers\Admin\TestimoniController::class, 'create'])->name('testimoni.create');
    Route::post('/testimoni', [App\Http\Controllers\Admin\TestimoniController::class, 'store'])->name('testimoni.store');
    Route::get('/testimoni/{id}/edit', [App\Http\Controllers\Admin\TestimoniController::class, 'edit'])->name('testimoni.edit');
    Route::put('/testimoni/{id}', [App\Http\Controllers\Admin\TestimoniController::class, 'update'])->name('testimoni.update');
    Route::delete('/testimoni/{id}', [App\Http\Controllers\Admin\TestimoniController::class, 'destroy'])->name('testimoni.destroy');

    // Tentang Kami Admin
    Route::get('/tentang', [App\Http\Controllers\Admin\TentangController::class, 'index'])->name('tentang.index');
    Route::get('/tentang/create', [App\Http\Controllers\Admin\TentangController::class, 'create'])->name('tentang.create');
    Route::post('/tentang', [App\Http\Controllers\Admin\TentangController::class, 'store'])->name('tentang.store');
    Route::get('/tentang/{id}/edit', [App\Http\Controllers\Admin\TentangController::class, 'edit'])->name('tentang.edit');
    Route::put('/tentang/{id}', [App\Http\Controllers\Admin\TentangController::class, 'update'])->name('tentang.update');
    Route::delete('/tentang/{id}', [App\Http\Controllers\Admin\TentangController::class, 'destroy'])->name('tentang.destroy');

    // Hero Slider Admin
    Route::get('/hero', [App\Http\Controllers\Admin\HeroController::class, 'index'])->name('hero.index');
    Route::get('/hero/create', [App\Http\Controllers\Admin\HeroController::class, 'create'])->name('hero.create');
    Route::post('/hero', [App\Http\Controllers\Admin\HeroController::class, 'store'])->name('hero.store');
    Route::get('/hero/{id}/edit', [App\Http\Controllers\Admin\HeroController::class, 'edit'])->name('hero.edit');
    Route::put('/hero/{id}', [App\Http\Controllers\Admin\HeroController::class, 'update'])->name('hero.update');
    Route::delete('/hero/{id}', [App\Http\Controllers\Admin\HeroController::class, 'destroy'])->name('hero.destroy');

    // NOTIFIKASI ROUTES - GUNAKAN AdminDashboardController
    Route::post('/notifications/{id}/read', [AdminDashboardController::class, 'markAsRead'])
        ->name('notifications.read');
    Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::get('/notifications/count', [AdminDashboardController::class, 'getNotificationCount'])
        ->name('notifications.count');

    // Master Kegiatan (Tambahkan ini)
    Route::resource('master-kegiatan', \App\Http\Controllers\Admin\MasterKegiatanController::class)
        ->except(['show']);
});

// ======================
// Petugas Routes
// =====================

Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

    // Input Log Kegiatan (Sistem Fleksibel Baru)
    Route::get('/input-log/{booking}', [App\Http\Controllers\Petugas\InputLogController::class, 'show'])->name('input-log.show');
    Route::post('/input-log/{booking}', [App\Http\Controllers\Petugas\InputLogController::class, 'store'])->name('input-log.store');
    Route::delete('/input-log/{log}', [App\Http\Controllers\Petugas\InputLogController::class, 'destroyLog'])->name('input-log.destroy-log');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Petugas\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [App\Http\Controllers\Petugas\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Petugas\ProfileController::class, 'index'])->name('profile.index');
});


// ======================
// ROUTE USER
// ======================

Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard User
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');                
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');   
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all'); 
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');      
        Route::get('/get-new', [NotificationController::class, 'getNewNotifications'])->name('get-new'); 
    });

    // Hewan Saya
    Route::get('/hewan-saya', [HewanSayaController::class, 'index'])->name('hewan-saya');
    Route::get('/hewan-saya/{id}/log', [HewanSayaController::class, 'logHarian'])->name('hewan-saya.log');

    // Booking
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/riwayat', [BookingController::class, 'riwayat'])->name('booking.riwayat');
    Route::get('/booking/get-harga', [BookingController::class, 'getHarga'])->name('booking.get-harga');
    // Perpanjangan Booking
    Route::get('/booking/{id}/extend', [BookingController::class, 'showExtendForm'])->name('booking.extend.form');
    Route::post('/booking/{id}/extend', [BookingController::class, 'extend'])->name('booking.extend');
    // Pembatalan Booking
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    // Detail booking
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');

    // Konsultasi
    Route::get('/konsultasi', [KonsultasiController::class, 'create'])->name('konsultasi.create');
    Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
    Route::get('/konsultasi-saya', [KonsultasiController::class, 'index'])->name('konsultasi.index');
    Route::get('/konsultasi/get-jam', [KonsultasiController::class, 'getJam'])->name('konsultasi.get-jam');
    Route::post('/konsultasi/balas', [KonsultasiController::class, 'balas'])->name('konsultasi.balas');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');

});

// ======================
// ROUTE DOKTER
// ======================

Route::middleware(['auth', 'dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    // Dashboard Dokter
    Route::get('/dashboard', [\App\Http\Controllers\Dokter\DashboardController::class, 'index'])->name('dashboard');

    // Konsultasi Dokter
    Route::prefix('konsultasi')->name('konsultasi.')->group(function () {
        Route::get('/{id}', [\App\Http\Controllers\Dokter\KonsultasiController::class, 'show'])->name('show');
        Route::post('/{id}/status', [\App\Http\Controllers\Dokter\KonsultasiController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/balas', [\App\Http\Controllers\Dokter\KonsultasiController::class, 'kirimBalasan'])->name('balas');
    });

    // Catatan Medis Dokter
    Route::get('/catatan-medis', [\App\Http\Controllers\Dokter\CatatanMedisController::class, 'index'])->name('catatan-medis.index');
    Route::post('/catatan-medis', [\App\Http\Controllers\Dokter\CatatanMedisController::class, 'store'])->name('catatan-medis.store');

    // Cek Status (dokter juga bisa cek)
    Route::get('/cek-status', [\App\Http\Controllers\User\CekStatusController::class, 'index'])->name('cek-status');
    Route::post('/cek-status', [\App\Http\Controllers\User\CekStatusController::class, 'show'])->name('cek-status.show');
});

require __DIR__ . '/auth.php';
