@extends('layouts.user')

@section('title', 'User - Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-10 gap-6 relative z-[50]">
        <div data-aos="fade-right" class="flex-1">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                Selamat Datang, <span class="text-teal-600">{{ Auth::user()->username }}!</span> 🐾
            </h1>
            <p class="mt-2 text-base sm:text-lg text-gray-500 max-w-2xl">
                Kelola kesehatan dan kenyamanan hewan kesayangan Anda dalam satu dasbor terpadu yang profesional.
            </p>
        </div>

        <div class="relative inline-block text-left" id="notification-container" data-aos="fade-left">
            <div class="flex items-center gap-4 bg-white p-2 pr-4 sm:pr-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative z-[60]">
                <button id="notification-button" class="relative p-3 bg-teal-50 rounded-xl text-teal-700 hover:bg-teal-100 transition-colors focus:outline-none">
                    <i class="fas fa-bell text-xl sm:text-2xl"></i>
                    @if($unreadCount > 0)
                    <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm animate-pulse">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </button>
                <div class="hidden sm:block">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Pusat Pesan</p>
                    <p class="text-sm font-bold text-gray-700">{{ $unreadCount > 0 ? 'Ada kabar baru!' : 'Tidak ada notif' }}</p>
                </div>
            </div>

            <div id="notification-dropdown" class="absolute right-0 mt-3 w-[calc(100vw-2rem)] sm:w-[400px] bg-white rounded-2xl shadow-2xl z-[100] border border-gray-100 overflow-hidden hidden transform origin-top-right transition-all">
                <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Notifikasi Terbaru</h3>
                    @if($unreadCount > 0)
                    <button id="mark-all-read-btn" onclick="markAllAsRead()" class="text-xs text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-1">
                        <i class="fas fa-check-double"></i> Tandai Baca Semua
                    </button>
                    @endif
                </div>

                <div id="notification-list" class="max-h-[60vh] overflow-y-auto">
                    @forelse($notifications as $notification)
                        @php
                            $isKonsultasi = str_contains($notification->title, 'Konsultasi');
                            $isBooking = str_contains($notification->title, 'Booking');
                            $icon = match(true) {
                                $isBooking && str_contains($notification->title, 'Batal') => 'fa-calendar-times text-red-500 bg-red-50',
                                $isBooking => 'fa-calendar-check text-teal-600 bg-teal-50',
                                $isKonsultasi => 'fa-stethoscope text-blue-500 bg-blue-50',
                                default => 'fa-bell text-gray-500 bg-gray-50'
                            };
                            $targetUrl = $isKonsultasi ? route('user.konsultasi.index') : ($notification->booking_id ? route('user.booking.show', $notification->booking_id) : '#');
                        @endphp

                        <div class="notification-item p-4 border-b border-gray-50 hover:bg-gray-50 transition-all cursor-pointer {{ !$notification->is_read ? 'bg-blue-50/40' : '' }}"
                             onclick="markSingleAsRead('{{ $notification->id }}', this, '{{ $targetUrl }}')"
                             id="notif-{{ $notification->id }}">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $icon }}">
                                    <i class="fas {{ explode(' ', $icon)[0] }}"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $notification->title }}</h4>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $notification->message }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-[10px] text-gray-400"><i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                        @if(!$notification->is_read)
                                            <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-500 text-sm">Belum ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->count() > 0)
                <a href="{{ route('user.notifikasi.index') }}" class="block p-4 text-center text-sm font-bold text-teal-600 hover:bg-teal-50 border-t border-gray-50 transition-colors">
                    Lihat Semua Notifikasi
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 relative z-10">
        <a href="{{ route('user.hewan-saya') }}" class="group card-dashboard" data-aos="fade-up" data-aos-delay="100">
            <div class="p-8 text-center sm:text-left">
                <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto sm:mx-0 group-hover:bg-teal-500 group-hover:text-white transition-all duration-300">
                    🐕
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Hewan Saya</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Pantau profil, riwayat medis, dan status kesehatan semua hewan kesayangan Anda dalam satu tempat.
                </p>
            </div>
        </a>

        <a href="{{ route('user.booking.create') }}" class="group card-dashboard" data-aos="fade-up" data-aos-delay="200">
            <div class="p-8 text-center sm:text-left">
                <div class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto sm:mx-0 group-hover:bg-pink-500 group-hover:text-white transition-all duration-300">
                    🏠
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Booking Penitipan</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Reservasi tempat penitipan dengan mudah, cek ketersediaan kuota, dan lihat update harian anabul.
                </p>
            </div>
        </a>

        <a href="{{ route('user.konsultasi.create') }}" class="group card-dashboard" data-aos="fade-up" data-aos-delay="300">
            <div class="p-8 text-center sm:text-left">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-3xl mb-6 mx-auto sm:mx-0 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                    🩺
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Konsultasi Dokter</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Butuh saran ahli? Hubungi dokter hewan kami secara online untuk konsultasi kesehatan yang cepat.
                </p>
            </div>
        </a>
    </div>

    <div class="mt-16 bg-white rounded-3xl p-8 border border-gray-100 shadow-sm text-center relative overflow-hidden" data-aos="fade-up">
        <div class="relative z-10">
            <p class="text-lg text-gray-600 mb-4">Butuh bantuan cepat atau kendala teknis?</p>
            <a href="https://wa.me/6285942173668?text=Halo%20LARAPetHouse,%20saya%20butuh%20bantuan%20di%20dashboard"
               class="inline-flex items-center gap-3 text-xl font-bold text-teal-600 hover:text-teal-700 transition-transform hover:scale-105">
                <i class="fab fa-whatsapp text-2xl"></i> +62 859-4217-3668
            </a>
        </div>
        <i class="fas fa-paw absolute -bottom-4 -right-4 text-gray-50 text-8xl rotate-12"></i>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // 1. Polling Notifikasi Otomatis
    async function updateUserNotificationDropdown() {
        try {
            const response = await fetch("{{ route('user.notifikasi.get-new') }}");
            const data = await response.json();

            if (data.success) {
                const badge = document.getElementById('notification-badge');
                const btnContainer = document.getElementById('notification-button');

                if (data.unreadCount > 0) {
                    if (badge) {
                        badge.innerText = data.unreadCount;
                    } else {
                        btnContainer.insertAdjacentHTML('beforeend', `<span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm animate-pulse">${data.unreadCount}</span>`);
                    }
                } else if (badge) {
                    badge.remove();
                }

                const listContainer = document.getElementById('notification-list');
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(notif => {
                        let iconClass = 'fa-bell text-gray-500 bg-gray-50';
                        if (notif.title.includes('Konsultasi')) iconClass = 'fa-stethoscope text-blue-500 bg-blue-50';
                        if (notif.title.includes('Booking')) iconClass = 'fa-calendar-check text-teal-600 bg-teal-50';

                        html += `
                            <div class="notification-item p-4 border-b border-gray-50 hover:bg-gray-50 transition-all cursor-pointer ${notif.is_read == 0 ? 'bg-blue-50/40' : ''}"
                                 onclick="markSingleAsRead('${notif.id}', this, '#')" id="notif-${notif.id}">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center ${iconClass}">
                                        <i class="fas ${iconClass.split(' ')[0]}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-800 leading-tight">${notif.title}</h4>
                                        <p class="text-xs text-gray-500 mt-1">${notif.message}</p>
                                        <p class="text-[10px] text-gray-400 mt-2"><i class="far fa-clock mr-1"></i>Baru saja</p>
                                    </div>
                                    ${notif.is_read == 0 ? '<span class="w-2 h-2 bg-teal-500 rounded-full mt-1"></span>' : ''}
                                </div>
                            </div>`;
                    });
                    listContainer.innerHTML = html;
                }
            }
        } catch (error) {
            console.warn('Polling silent error');
        }
    }

    setInterval(updateUserNotificationDropdown, 60000);

    // 2. Mark Single Read
    async function markSingleAsRead(id, element, redirectUrl) {
        try {
            const response = await fetch(`/user/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success && redirectUrl !== '#') {
                window.location.href = redirectUrl;
            } else {
                element.classList.remove('bg-blue-50/40');
                const dot = element.querySelector('.bg-teal-500');
                if (dot) dot.remove();
                updateUserNotificationDropdown();
            }
        } catch (error) {
            if(redirectUrl !== '#') window.location.href = redirectUrl;
        }
    }

    // 3. Mark All Read
    async function markAllAsRead() {
        const btn = document.getElementById('mark-all-read-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch("{{ route('user.notifikasi.read-all') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                window.location.reload();
            }
        } catch (error) {
            window.location.reload();
        }
    }

    // Toggle Dropdown
    document.getElementById('notification-button').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notification-dropdown').classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#notification-container')) {
            document.getElementById('notification-dropdown').classList.add('hidden');
        }
    });
</script>

<style>
    /* Styling Card Dashboard */
    .card-dashboard {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f3f4f6;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        text-decoration: none;
    }

    .card-dashboard:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #5eead4;
    }

    /* Scrollbar Dropdown */
    #notification-list::-webkit-scrollbar {
        width: 4px;
    }
    #notification-list::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 20px;
    }

    /* Utilitas */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection