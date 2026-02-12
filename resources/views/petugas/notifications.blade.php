@extends('petugas.layouts.app')

@section('title', 'Notifikasi Petugas')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 text-center sm:text-left flex items-center justify-center sm:justify-start">
            <i class="fas fa-bell mr-4 text-teal-600 text-4xl"></i>
            Notifikasi Petugas
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-10 text-center sm:text-left">
            Pantau semua pemberitahuan penting terkait hewan yang dititipkan.
        </p>
    </div>

    @if($notifications->count() == 0)
        <div class="card-hewan p-16 text-center" data-aos="fade-up">
            <div class="text-7xl mb-8 text-gray-300">
                <i class="fas fa-bell-slash"></i>
            </div>
            <p class="text-2xl text-gray-600 mb-4">
                Belum ada notifikasi baru.
            </p>
            <p class="text-gray-500">
                Semua pemberitahuan akan muncul di sini ketika ada update penting.
            </p>
        </div>
    @else
        <div class="space-y-8">
            @php $delay = 100; @endphp
            @foreach($notifications as $notification)
                <div class="card-hewan {{ !$notification->is_read ? 'border-l-4 border-teal-500' : '' }}" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold {{ !$notification->is_read ? 'text-teal-700' : 'text-gray-700' }} mb-2 flex items-center flex-wrap">
                                    {{ $notification->title }}
                                    @if(!$notification->is_read)
                                        <span class="ml-3 px-3 py-1 text-xs font-bold bg-red-500 text-white rounded-full">BARU</span>
                                    @endif
                                </h3>
                                <p class="text-gray-700 leading-relaxed mt-3">
                                    {{ $notification->message }}
                                </p>

                                @if($notification->booking)
                                    <div class="mt-4 p-4 bg-teal-50 rounded-xl border border-teal-200">
                                        <p class="text-teal-800 font-medium">
                                            <i class="fas fa-barcode mr-2"></i>
                                            Kode Booking: <strong>{{ $notification->booking->kode_booking }}</strong>
                                        </p>
                                        <p class="text-teal-700 mt-1">
                                            <i class="fas fa-paw mr-2"></i>
                                            Hewan: <strong>{{ $notification->booking->nama_hewan }}</strong>
                                        </p>
                                    </div>
                                @endif

                                <p class="text-sm text-gray-500 mt-4 flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $notification->created_at->format('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if(!$notification->is_read)
                                <div class="mt-4 sm:mt-0 sm:ml-6">
                                    <a href="{{ route('petugas.notifications.markAsRead', $notification->id) }}" 
                                       class="bg-teal-500 hover:bg-teal-600 text-white font-bold px-6 py-3 rounded-full shadow-md inline-flex items-center">
                                        <i class="fas fa-check mr-2"></i>
                                        Tandai Dibaca
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @php $delay += 100; @endphp
            @endforeach
        </div>
    @endif

    <!-- Tombol Kembali -->
    <div class="mt-12 text-center" data-aos="fade-up">
        <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium text-lg">
            <i class="fas fa-arrow-left mr-3"></i>
            Kembali ke Dashboard Petugas
        </a>
    </div>
</div>
@endsection