@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">

    {{-- HEADER UTAMA --}}
    <div data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 text-center sm:text-left">
            <i class="fas fa-dog mr-3 text-teal-600"></i>
            Dashboard Petugas
        </h1>
    </div>

    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
    <div class="mb-10 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" data-aos="fade-up">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center">
                <i class="fas fa-bell mr-3"></i>
                Notifikasi Terbaru
                @if($unreadCount > 0)
                <span class="ml-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                    {{ $unreadCount }} belum dibaca
                </span>
                @endif
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentNotifications as $notif)
            <div class="px-6 py-4 hover:bg-gray-50 transition flex items-start {{ !$notif->is_read ? 'bg-blue-50' : '' }}">
                {{-- Ikon berdasarkan tipe notifikasi --}}
                <div class="flex-shrink-0 mr-4">
                    @if($notif->type == 'assignment')
                        <span class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                            <i class="fas fa-user-plus"></i>
                        </span>
                    @elseif($notif->type == 'status')
                        <span class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                    @elseif($notif->type == 'extend')
                        <span class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                            <i class="fas fa-calendar-plus"></i>
                        </span>
                    @elseif($notif->type == 'completed')
                        <span class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    @elseif($notif->type == 'cancel')
                        <span class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl">
                            <i class="fas fa-times-circle"></i>
                        </span>
                    @else
                        <span class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xl">
                            <i class="fas fa-bell"></i>
                        </span>
                    @endif
                </div>

                {{-- Konten notifikasi --}}
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-800 {{ !$notif->is_read ? 'text-teal-700' : '' }}">
                                {{ $notif->title }}
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $notif->message }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap ml-4">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Tombol tandai dibaca (hanya untuk yg belum dibaca) --}}
                    @if(!$notif->is_read)
                    <div class="mt-2">
                        <a href="{{ route('petugas.notifications.markAsRead', $notif->id) }}"
                           class="text-xs bg-white border border-teal-500 text-teal-600 px-3 py-1 rounded-full hover:bg-teal-50 transition">
                            Tandai sudah dibaca
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Link ke halaman semua notifikasi --}}
        <div class="px-6 py-3 bg-gray-50 text-right">
            <a href="{{ route('petugas.notifications.index') }}"
               class="text-teal-600 hover:text-teal-800 font-medium text-sm">
                Lihat semua notifikasi →
            </a>
        </div>
    </div>
    @endif

    <div data-aos="fade-up">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">
            <i class="fas fa-paw mr-3 text-teal-600"></i>
            Hewan yang Sedang Dititipkan
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 mb-10">
            Pantau dan update kondisi harian hewan kesayangan pelanggan.
        </p>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        @php $delay = 100; @endphp
        @foreach($bookings as $booking)
        <div class="card-hewan" data-aos="fade-up" data-aos-delay="{{ $delay }}">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                    // Normalisasi nomor WA
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (substr($wa_clean, 0, 1) === '0') {
                        $wa_clean = '62' . substr($wa_clean, 1);
                    } elseif (substr($wa_clean, 0, 2) !== '62') {
                        $wa_clean = '62' . $wa_clean;
                    }
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20Bapak/Ibu%20{{ urlencode($booking->nama_pemilik) }},%20ini%20update%20dari%20PetHouse%20untuk%20{{ urlencode($booking->nama_hewan) }}"
                        target="_blank"
                        class="btn-wa text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-3 text-xl"></i>
                        Hubungi Pemilik
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="btn-update text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                        <i class="fas fa-edit mr-3 text-xl"></i>
                        Input Log Kegiatan
                    </a>
                </div>
            </div>
        </div>
        @php $delay += 100; @endphp
        @endforeach
    </div>
    @else
    <div class="card-hewan p-16 text-center" data-aos="fade-up">
        <div class="text-7xl mb-8 text-gray-300">🐾</div>
        <p class="text-2xl text-gray-600 mb-4">
            Tidak ada hewan yang sedang dititipkan saat ini.
        </p>
        <p class="text-gray-500">
            Tunggu booking baru dari pelanggan atau pastikan admin telah mengubah status menjadi <strong>"sedang dititipkan"</strong>.
        </p>
    </div>
    @endif

</div>
@endsection