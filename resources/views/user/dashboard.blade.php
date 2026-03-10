@extends('layouts.user')

@section('title', 'User - Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <!-- Header dengan Notifikasi -->
    <div class="flex flex-row justify-between items-center mb-8 gap-4">
        <div data-aos="fade-up" class="flex-1">
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-2 sm:mb-4">
                Selamat Datang Kembali, {{ Auth::user()->username }}! 🐾
            </h1>
            <p class="text-sm sm:text-xl text-gray-600 hidden sm:block">
                Kelola hewan kesayangan dan layanan Anda dengan mudah di sini.
            </p>
        </div>

        <div class="relative" id="notification-container">
            <div class="flex items-center gap-3 sm:gap-6 bg-white px-4 py-2 sm:px-8 sm:py-4 rounded-full sm:rounded-3xl shadow-lg border border-gray-100">
                <span class="text-gray-600 font-medium hidden sm:inline">Notifikasi</span>
                <div class="relative">
                    <button id="notification-button" class="relative focus:outline-none flex items-center group">
                        <i class="fas fa-bell text-2xl sm:text-4xl text-teal-700 cursor-pointer group-hover:text-teal-600 transition-colors"></i>
                        @if($unreadCount > 0)
                        <span id="notification-badge" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] sm:text-xs font-bold rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shadow-lg animate-bounce border-2 border-white">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notification-dropdown" class="absolute right-[-20px] sm:right-0 mt-4 w-[85vw] sm:w-96 bg-white rounded-2xl shadow-2xl z-[100] border border-gray-200 max-h-[70vh] overflow-y-auto hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                            @if($unreadCount > 0)
                            <button id="mark-all-read-btn"
                                class="text-[10px] sm:text-xs text-teal hover:text-teal-600 font-medium transition-colors duration-200"
                                onclick="markAllAsRead()">
                                <i class="fas fa-check-double mr-1"></i>Tandai terbaca
                            </button>
                            @endif
                        </div>

                        <div id="notification-list">
                            @forelse($notifications as $notification)
                            @php
                            // Logika Ikon Spesifik
                            $isKonsultasi = str_contains($notification->title, 'Konsultasi');
                            $isBooking = str_contains($notification->title, 'Booking');

                            $icon = match(true) {
                            $isBooking && str_contains($notification->title, 'Batal') => 'fa-calendar-times text-pink',
                            $isBooking => 'fa-calendar-check text-teal',
                            $isKonsultasi => 'fa-stethoscope text-blue-500',
                            default => 'fa-bell text-gray-500'
                            };

                            // Tentukan link tujuan otomatis
                            $targetUrl = $isKonsultasi ? route('user.konsultasi.index') : ($notification->booking_id ? route('user.booking.show', $notification->booking_id) : '#');
                            @endphp

                            <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 
        {{ !$notification->is_read ? 'bg-blue-50' : '' }} cursor-pointer"
                                onclick="markSingleAsRead('{{ $notification->id }}', this, '{{ $targetUrl }}')"
                                id="notif-{{ $notification->id }}">

                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas {{ $icon }} text-base sm:text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm sm:text-base font-semibold text-gray-800 leading-tight">
                                            {{ $notification->title }}
                                        </h4>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-2">
                                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notification->is_read)
                                    <span class="unread-dot w-2 h-2 bg-pink rounded-full flex-shrink-0 mt-2"></span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            @endforelse
                        </div>

                        @if($notifications->count() > 0)
                        <div class="p-4 border-t border-gray-200 text-center">
                            <a href="{{ route('user.notifikasi.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                Lihat semua notifikasi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layanan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Hewan Saya -->
        <a href="{{ route('user.hewan-saya') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="100">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-teal-500">🐕</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Hewan Saya</h2>
                <p class="text-gray-600 leading-relaxed">
                    Lihat profil, riwayat penitipan, vaksin, dan status kesehatan semua hewan kesayangan Anda.
                </p>
            </div>
        </a>

        <!-- Booking Penitipan -->
        <a href="{{ route('user.booking.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="200">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-pink-500">🏠</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Booking Penitipan</h2>
                <p class="text-gray-600 leading-relaxed">
                    Booking jadwal penitipan baru, lihat status ongoing, dan dapatkan update foto harian.
                </p>
            </div>
        </a>

        <!-- Konsultasi Dokter -->
        <a href="{{ route('user.konsultasi.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="300">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-amber-500">🩺</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Konsultasi Dokter</h2>
                <p class="text-gray-600 leading-relaxed">
                    Chat langsung dengan dokter hewan, lihat riwayat konsultasi, dan resep obat.
                </p>
            </div>
        </a>
    </div>

    <!-- Info Bantuan -->
    <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="400">
        <p class="text-lg text-gray-600">
            Butuh bantuan cepat? Hubungi kami langsung via WhatsApp:
        </p>
        <a href="https://wa.me/6285942173668?text=Halo%20LARAPetHouse,%20saya%20butuh%20bantuan%20di%20dashboard"
            class="inline-block mt-4 text-xl font-bold text-teal-600 hover:text-teal-700 underline">
            <i class="fab fa-whatsapp mr-2"></i> +62 859-4217-3668
        </a>
    </div>
</div>

<!-- JavaScript untuk Notifikasi -->
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // 1. Fungsi Polling Notifikasi Baru
    async function updateUserNotificationDropdown() {
        try {
            const response = await fetch("{{ route('user.notifikasi.get-new') }}");
            const data = await response.json();

            if (data.success) {
                // Update Badge Angka
                const badge = document.getElementById('notification-badge');
                const bellIconContainer = document.getElementById('notification-button');

                if (data.unreadCount > 0) {
                    if (badge) {
                        badge.innerText = data.unreadCount;
                    } else {
                        const newBadge = `<span id="notification-badge" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] sm:text-xs font-bold rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shadow-lg animate-bounce border-2 border-white">${data.unreadCount}</span>`;
                        bellIconContainer.insertAdjacentHTML('beforeend', newBadge);
                    }
                    // Tampilkan tombol "Tandai terbaca" jika sebelumnya sembunyi
                    const markAllBtn = document.getElementById('mark-all-read-btn');
                    if (markAllBtn) markAllBtn.classList.remove('hidden');
                } else if (badge) {
                    badge.remove();
                }

                // Update List Notifikasi di Dropdown secara dinamis
                const listContainer = document.getElementById('notification-list');
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notif => {
                        const isUnread = notif.is_read == 0 ? 'bg-blue-50' : '';
                        const unreadDot = notif.is_read == 0 ? '<span class="unread-dot w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-2"></span>' : '';

                        // Logika Icon (Bisa disesuaikan dengan helper JS jika perlu)
                        let iconClass = 'fa-bell text-gray-500';
                        if (notif.title.includes('Konsultasi')) iconClass = 'fa-stethoscope text-blue-500';
                        if (notif.title.includes('Booking')) iconClass = 'fa-calendar-check text-teal-600';

                        html += `
                            <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 ${isUnread} cursor-pointer"
                                 onclick="markSingleAsRead('${notif.id}', this, '#')" id="notif-${notif.id}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="fas ${iconClass} text-base sm:text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm sm:text-base font-semibold text-gray-800 leading-tight">${notif.title}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">${notif.message}</p>
                                        <p class="text-[10px] text-gray-400 mt-2"><i class="far fa-clock mr-1"></i>Baru saja</p>
                                    </div>
                                    ${unreadDot}
                                </div>
                            </div>`;
                    });
                    listContainer.innerHTML = html;
                }
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    // Jalankan polling setiap 30 detik
    setInterval(updateUserNotificationDropdown, 30000);

    // 2. Fungsi Tandai Satu Sebagai Baca
    async function markSingleAsRead(id, element, redirectUrl) {
        try {
            const response = await fetch(`/user/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            });
            const data = await response.json();
            if (data.success) {
                // Jika ingin responsif instan tanpa reload:
                element.classList.remove('bg-blue-50');
                const dot = element.querySelector('.unread-dot');
                if (dot) dot.remove();
                // Opsional: reload jika ingin sinkron total
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // 3. Fungsi Tandai Semua Sebagai Baca
    async function markAllAsRead() {
        const btn = document.getElementById('mark-all-read-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch("{{ route('user.notifikasi.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
            window.location.reload();
        }
    }

    // Toggle Dropdown Logic
    document.getElementById('notification-button').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notification-dropdown').classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notification-dropdown');
        if (!e.target.closest('#notification-container')) {
            dropdown.classList.add('hidden');
        }
    });
</script>

<style>
    .notification-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .notification-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .notification-item:hover {
        transform: translateX(5px);
    }

    /* Style untuk dropdown */
    #notification-dropdown {
        scrollbar-width: thin;
        scrollbar-color: #0d9488 #f0fdfa;
    }

    #notification-dropdown::-webkit-scrollbar {
        width: 6px;
    }

    #notification-dropdown::-webkit-scrollbar-track {
        background: #f0fdfa;
        border-radius: 10px;
    }

    #notification-dropdown::-webkit-scrollbar-thumb {
        background: #0d9488;
        border-radius: 10px;
    }

    /* Animation for card hover */
    .card-dashboard {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        display: block;
        overflow: hidden;
    }

    .card-dashboard:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: #5eead4;
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endsection