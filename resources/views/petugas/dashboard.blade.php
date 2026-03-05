@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16">

    {{-- HEADER SECTION --}}
    <div class="flex flex-row justify-between items-center mb-12 gap-4">
        <div data-aos="fade-up" class="flex-1">
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-2 sm:mb-4">
                <i class="fas fa-dog mr-3 text-teal-600"></i>
                Dashboard Petugas 🐾
            </h1>
            <p class="text-sm sm:text-xl text-gray-600 hidden sm:block">
                Pantau dan update kondisi harian hewan kesayangan pelanggan.
            </p>
        </div>

        {{-- NOTIFIKASI (SAMA PERSIS DENGAN USER) --}}
        <div class="relative" id="notification-container">
            <div class="flex items-center gap-3 sm:gap-6 bg-white px-4 py-2 sm:px-8 sm:py-4 rounded-full sm:rounded-3xl shadow-lg border border-gray-100">
                <span class="text-gray-600 font-medium hidden sm:inline">Notifikasi</span>
                <div class="relative">
                    <button id="notification-button" class="relative focus:outline-none flex items-center">
                        <i class="fas fa-bell text-2xl sm:text-4xl text-teal-600 cursor-pointer hover:text-teal-700 transition-colors"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                        <span id="notification-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] sm:text-xs font-bold rounded-full w-5 h-5 sm:w-7 sm:h-7 flex items-center justify-center shadow-lg animate-pulse">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notification-dropdown" class="absolute right-[-20px] sm:right-0 mt-4 w-[85vw] sm:w-96 bg-white rounded-2xl shadow-2xl z-[100] border border-gray-200 max-h-[70vh] overflow-y-auto hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                            @if(isset($unreadCount) && $unreadCount > 0)
                            <button id="mark-all-read-btn"
                                class="text-[10px] sm:text-xs text-teal-600 hover:text-teal-700 font-medium transition-colors duration-200"
                                onclick="markAllAsRead()">
                                <i class="fas fa-check-double mr-1"></i>Tandai terbaca
                            </button>
                            @endif
                        </div>

                        <div id="notification-list">
                            @forelse($recentNotifications as $notification)
                            <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 
                            {{ !$notification->is_read ? 'bg-blue-50' : '' }} cursor-pointer"
                                data-id="{{ $notification->id }}"
                                id="notif-{{ $notification->id }}">

                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        @php
                                        $icon = match($notification->type) {
                                            'assignment' => 'fa-user-plus text-purple-500',
                                            'status'     => 'fa-sync-alt text-blue-500',
                                            'extend'     => 'fa-calendar-plus text-teal-600',
                                            'completed'  => 'fa-check-circle text-green-500',
                                            default      => 'fa-bell text-gray-500'
                                        };
                                        @endphp
                                        <i class="fas {{ $icon }} text-base sm:text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm sm:text-base font-semibold text-gray-800 leading-tight">{{ $notification->title }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-2">
                                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notification->is_read)
                                    <span class="unread-dot w-2 h-2 bg-red-500 rounded-full flex-shrink-0 mt-2"></span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center">
                                <i class="fas fa-bell-slash text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Belum ada notifikasi</p>
                            </div>
                            @endforelse
                        </div>

                        @if($recentNotifications->count() > 0)
                        <div class="p-4 border-t border-gray-200 text-center">
                            <a href="{{ route('petugas.notifications.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                Lihat semua notifikasi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR HEWAN --}}
    <div data-aos="fade-up" class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-paw mr-3 text-teal-600"></i>
            Hewan yang Sedang Dititipkan
        </h2>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-teal-600 mb-2">{{ $booking->nama_hewan }}</h3>
                        <p class="text-gray-600">
                            <i class="fas fa-paw mr-2 text-pink-500"></i>{{ $booking->jenis_hewan }}
                        </p>
                    </div>
                    <span class="bg-teal-100 text-teal-800 px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $booking->kode_booking }}
                    </span>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700">
                        <i class="fas fa-user mr-2 text-amber-500"></i>
                        <strong>Pemilik:</strong> {{ $booking->nama_pemilik }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @php
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (str_starts_with($wa_clean, '0')) $wa_clean = '62' . substr($wa_clean, 1);
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20{{ urlencode($booking->nama_pemilik) }},%20update%20untuk%20{{ urlencode($booking->nama_hewan) }}"
                        target="_blank"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl text-center transition">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>WA
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl text-center transition">
                        <i class="fas fa-edit mr-2 text-xl"></i>Log
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100">
        <div class="text-7xl mb-8">🐾</div>
        <p class="text-2xl text-gray-600 font-semibold">Tidak ada hewan titipan aktif.</p>
    </div>
    @endif

</div>

{{-- SCRIPT UNTUK DROPDOWN (PASTIKAN ADA) --}}
<script>
    document.getElementById('notification-button').addEventListener('click', function() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', function(e) {
        const container = document.getElementById('notification-container');
        const dropdown = document.getElementById('notification-dropdown');
        if (!container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection