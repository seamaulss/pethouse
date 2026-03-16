<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProfileController, PublicController};

// Controller Aliases (untuk kerapihan)
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    BookingController as AdminBooking,
    KapasitasController as AdminKapasitas,
    KonsultasiController as AdminKonsultasi,
    JenisHewanController as AdminJenisHewan,
    LayananController as AdminLayanan,
    GaleriController as AdminGaleri,
    TestimoniController as AdminTestimoni,
    TentangController as AdminTentang,
    HeroController as AdminHero,
    MasterKegiatanController,
    BookingController as BookingController,
};

use App\Http\Controllers\Petugas\{
    DashboardController as PetugasDashboard,
    BookingController as PetugasBooking,
    InputLogController as PetugasInputLog,
    KapasitasController as PetugasKapasitas,
    NotificationController as PetugasNotification,
    ProfileController as PetugasProfile
};

use App\Http\Controllers\User\{
    DashboardController as UserDashboard,
    BookingController as UserBooking,
    HewanSayaController,
    KonsultasiController as UserKonsultasi,
    NotificationController as UserNotification,
    ProfilController as UserProfil
};

use App\Http\Controllers\Dokter\{
    DashboardController as DokterDashboard,
    KonsultasiController as DokterKonsultasi,
    ProfileController as DokterProfile
};

// ======================================================
// PUBLIC ROUTES
// ======================================================
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/layanan', 'layanan')->name('layanan');
    Route::get('/galeri', 'galeri')->name('galeri');
    Route::get('/kontak', 'kontak')->name('kontak');
});

// ======================================================
// AUTH ROUTES (Breeze & Shared Profile)
// ======================================================
Route::middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

// ======================================================
// ADMIN ROUTES
// ======================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Booking Management
    Route::post('booking/{booking}/handle-extension', [AdminBooking::class, 'handleExtension'])->name('booking.handle-extension');
    Route::resource('booking', AdminBooking::class);

    // Route khusus untuk Export PDF
    Route::get('booking-export-pdf', [BookingController::class, 'exportPdf'])->name('booking.export-pdf');

    // PINDAHKAN KE SINI (Di atas Resource Konsultasi)
    Route::get('konsultasi/export-pdf', [AdminKonsultasi::class, 'exportPdf'])->name('konsultasi.export-pdf');

    // Master Data Resources
    Route::resource('kapasitas', AdminKapasitas::class)->except(['show', 'create', 'edit']);
    Route::resource('konsultasi', AdminKonsultasi::class);
    Route::post('konsultasi/{id}/add-balasan', [AdminKonsultasi::class, 'updateBalasan'])->name('konsultasi.add-balasan');

    Route::resource('jenis-hewan', AdminJenisHewan::class);
    Route::resource('galeri', AdminGaleri::class);
    Route::resource('testimoni', AdminTestimoni::class);
    Route::resource('tentang', AdminTentang::class);
    Route::resource('hero', AdminHero::class);
    Route::resource('master-kegiatan', MasterKegiatanController::class)->except(['show']);

    // Layanan & Custom Pricing
    Route::controller(AdminLayanan::class)->prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/{id}/atur-harga', 'aturHarga')->name('atur-harga');
        Route::post('/{id}/simpan-harga', 'simpanHarga')->name('simpan-harga');
    });
    Route::resource('layanan', AdminLayanan::class);

    // Notifications
    Route::controller(AdminDashboard::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/{id}/read', 'markAsRead')->name('read');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
        Route::get('/count', 'getNotificationCount')->name('count');
    });
});

// ======================================================
// PETUGAS ROUTES
// ======================================================
Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

    // Ganti dari /booking/verifikasi menjadi /verifikasi-booking
    Route::get('/verifikasi-booking', [PetugasBooking::class, 'search'])->name('booking.search');

    Route::resource('booking', PetugasBooking::class)->only(['index', 'show']);

    Route::get('/kapasitas', [PetugasKapasitas::class, 'index'])->name('kapasitas.index');

    // Log Kegiatan
    Route::controller(PetugasInputLog::class)->prefix('input-log')->name('input-log.')->group(function () {
        Route::get('/{booking}', 'show')->name('show');
        Route::post('/{booking}', 'store')->name('store');
        Route::delete('/{log}', 'destroyLog')->name('destroy-log');
    });

    // Profile Petugas
    Route::controller(PetugasProfile::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    // Notifikasi
    Route::controller(PetugasNotification::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');

        // Pastikan 'getNewNotifications' sesuai dengan nama function di Controller
        Route::get('/get-new', 'getNewNotifications')->name('get-new');

        Route::get('/{id}/read', 'markAsRead')->name('markAsRead');
    });
});

// ======================================================
// USER ROUTES
// ======================================================
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    // Hewan & Log
    Route::controller(HewanSayaController::class)->group(function () {
        Route::get('/hewan-saya', 'index')->name('hewan-saya');
        Route::get('/hewan-saya/{id}/log', 'logHarian')->name('hewan-saya.log');
    });

    // Booking User
    Route::controller(UserBooking::class)->prefix('booking')->name('booking.')->group(function () {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/riwayat', 'riwayat')->name('riwayat');

        // TAMBAHKAN DI SINI (Tanpa prefix /user/booking lagi)
        Route::get('/bayar-simulasi/{id}', 'bayarSimulasi')->name('bayar_simulasi');

        Route::get('/{id}/pdf', 'downloadPdf')->name('pdf');
        Route::get('/get-harga', 'getHarga')->name('get-harga');
        Route::get('/{id}/extend', 'showExtendForm')->name('extend.form');
        Route::post('/{id}/extend', 'extend')->name('extend');
        Route::post('/{id}/cancel', 'cancel')->name('cancel');
        Route::get('/{id}', 'show')->name('show');
    });

    // Konsultasi User
    Route::controller(UserKonsultasi::class)->prefix('konsultasi')->name('konsultasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/buat', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/get-jam', 'getJam')->name('get-jam');
        Route::post('/balas', 'balas')->name('balas');
    });

    // Notifikasi User
    Route::controller(UserNotification::class)->prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{id}/read', 'markAsRead')->name('read');
        Route::post('/read-all', 'markAllAsRead')->name('read-all');
        Route::get('/get-new', 'getNewNotifications')->name('get-new');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // Profile User
    Route::controller(UserProfil::class)->prefix('profil')->name('profil')->group(function () {
        Route::get('/', 'index');
        Route::put('/update', 'update')->name('.update');
    });
});

// ======================================================
// DOKTER ROUTES
// ======================================================
Route::middleware(['auth', 'dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DokterDashboard::class, 'index'])->name('dashboard');

    // Konsultasi Dokter
    Route::controller(DokterKonsultasi::class)->prefix('konsultasi')->name('konsultasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/status', 'updateStatus')->name('update-status');
        Route::post('/{id}/balas', 'kirimBalasan')->name('balas');
    });

    // Profile Dokter
    Route::controller(DokterProfile::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    // Sesuaikan name agar sesuai dengan pemanggilan di Blade
    // Hapus 'dokter.notifications.' jika sudah ada di luar, cukup gunakan 'notifikasi.'
    Route::controller(UserNotification::class)
        ->prefix('notifikasi')
        ->name('notifikasi.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/read', 'markAsRead')->name('read');
            Route::post('/read-all', 'markAllAsRead')->name('read-all');
            Route::get('/get-notifications', 'getNewNotifications')->name('get-new');
        });
});

require __DIR__ . '/auth.php';
