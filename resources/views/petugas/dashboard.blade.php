@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">

    <div class="relative z-50 flex flex-col sm:flex-row justify-between items-center mb-12 gap-6" data-aos="fade-up">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-2">
                <i class="fas fa-dog mr-3 text-teal-600"></i>
                Dashboard Petugas
            </h1>
            <p class="text-gray-500">Pantau dan update kondisi harian hewan kesayangan pelanggan.</p>
        </div>

        <div class="relative" x-data="{ open: false }">
            {{-- Tombol Ikon --}}
            <button @click="open = !open"
                class="relative p-4 bg-white rounded-2xl shadow-sm border border-gray-200 text-gray-600 hover:text-teal-600 hover:shadow-md transition-all focus:outline-none">
                <i class="fas fa-bell text-2xl"></i>

                @if(isset($unreadCount) && $unreadCount > 0)
                <span class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white border-2 border-white">
                    {{ $unreadCount }}
                </span>
                @endif
            </button>

            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-[100]"
                style="display: none;">

                <div class="bg-teal-600 px-5 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold flex items-center text-sm">
                        <i class="fas fa-bell mr-2"></i> Notifikasi Terbaru
                    </h3>
                    <a href="{{ route('petugas.notifications.index') }}" class="text-[11px] text-teal-100 hover:underline uppercase tracking-wider font-semibold">
                        Lihat Semua
                    </a>
                </div>

                <div class="max-h-96 overflow-y-auto divide-y divide-gray-50 bg-white">
                    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                    @foreach($recentNotifications as $notif)
                    <div class="p-4 hover:bg-gray-50 transition flex items-start {{ !$notif->is_read ? 'bg-blue-50/50' : '' }}">
                        {{-- Ikon Berdasarkan Tipe --}}
                        <div class="flex-shrink-0 mr-3">
                            @php
                            $iconType = [
                            'assignment' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'fa' => 'fa-user-plus'],
                            'status' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'fa' => 'fa-sync-alt'],
                            'extend' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'fa' => 'fa-calendar-plus'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'fa' => 'fa-check-circle'],
                            'cancel' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'fa' => 'fa-times-circle'],
                            ][$notif->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'fa' => 'fa-bell'];
                            @endphp
                            <span class="w-9 h-9 rounded-full flex items-center justify-center {{ $iconType['bg'] }} {{ $iconType['text'] }} text-sm">
                                <i class="fas {{ $iconType['fa'] }}"></i>
                            </span>
                        </div>

                        <div class="flex-1 min-w-0 text-left">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-bold text-gray-800 truncate pr-2">{{ $notif->title }}</h4>
                                <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[11px] text-gray-600 line-clamp-2 mt-1">{{ $notif->message }}</p>

                            @if(!$notif->is_read)
                            <div class="mt-2">
                                <a href="{{ route('petugas.notifications.markAsRead', $notif->id) }}"
                                    class="text-[10px] font-bold text-teal-600 hover:underline">
                                    Tandai dibaca
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="py-12 text-center">
                        <i class="fas fa-bell-slash text-gray-200 text-4xl mb-3"></i>
                        <p class="text-gray-400 text-sm">Belum ada notifikasi</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION DAFTAR HEWAN --}}
    <div data-aos="fade-up" class="mb-8 text-center sm:text-left">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <i class="fas fa-paw mr-3 text-teal-600"></i>
            Hewan yang Sedang Dititipkan
        </h2>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        @php $delay = 100; @endphp
        @foreach($bookings as $booking)
        <div class="card-hewan bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $delay }}">
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-teal-600 mb-2">
                            {{ $booking->nama_hewan }}
                        </h3>
                        <p class="text-gray-600 text-lg">
                            <i class="fas fa-paw mr-2 text-pink-500"></i>
                            {{ $booking->jenis_hewan }}
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

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @php
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (substr($wa_clean, 0, 1) === '0') {
                    $wa_clean = '62' . substr($wa_clean, 1);
                    } elseif (substr($wa_clean, 0, 2) !== '62') {
                    $wa_clean = '62' . $wa_clean;
                    }
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20Bapak/Ibu%20{{ urlencode($booking->nama_pemilik) }},%20ini%20update%20dari%20PetHouse%20untuk%20{{ urlencode($booking->nama_hewan) }}"
                        target="_blank"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl shadow-md text-center flex items-center justify-center transition">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>
                        WhatsApp
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl shadow-md text-center flex items-center justify-center transition">
                        <i class="fas fa-edit mr-2 text-xl"></i>
                        Input Log
                    </a>
                </div>
            </div>
        </div>
        @php $delay += 100; @endphp
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100" data-aos="fade-up">
        <div class="text-7xl mb-8 text-gray-200">🐾</div>
        <p class="text-2xl text-gray-600 mb-4 font-semibold">
            Tidak ada hewan yang sedang dititipkan saat ini.
        </p>
        <p class="text-gray-400 max-w-md mx-auto">
            Tunggu booking baru dari pelanggan atau pastikan admin telah mengubah status menjadi <strong>"sedang dititipkan"</strong>.
        </p>
    </div>
    @endif

</div>
@endsection