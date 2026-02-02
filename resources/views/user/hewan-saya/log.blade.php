@extends('layouts.user')

@section('title', 'Log Harian ' . $booking->nama_hewan . ' - PetHouse')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="text-teal-600 hover:text-teal-700">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('user.hewan-saya') }}" class="text-teal-600 hover:text-teal-700">
                            Hewan Saya
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Log Harian</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Log Harian: <span class="text-teal-600">{{ $booking->nama_hewan }}</span>
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-gray-600">
                    <span class="flex items-center">
                        <i class="fas fa-paw mr-2 text-teal-500"></i>
                        {{ $booking->jenis_hewan }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-hashtag mr-2 text-blue-500"></i>
                        {{ $booking->kode_booking }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-calendar mr-2 text-amber-500"></i>
                        {{ $booking->tanggal_masuk->format('d M Y') }} - {{ $booking->tanggal_keluar->format('d M Y') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('user.hewan-saya') }}" 
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Status Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-teal-50 p-4 rounded-lg border border-teal-100">
                <p class="text-sm text-teal-800 font-medium mb-1">Layanan</p>
                <p class="text-lg font-bold text-teal-900">{{ $booking->layanan->nama_layanan ?? 'Standard' }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <p class="text-sm text-blue-800 font-medium mb-1">Durasi</p>
                <p class="text-lg font-bold text-blue-900">{{ $booking->tanggal_masuk->diffInDays($booking->tanggal_keluar) }} hari</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                <p class="text-sm text-purple-800 font-medium mb-1">Status</p>
                <p class="text-lg font-bold text-purple-900 capitalize">
                    {{ str_replace('_', ' ', $booking->status) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Log Harian -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-clipboard-list mr-3 text-teal-500"></i>
            Catatan Harian
        </h2>

        @if($booking->dailyLogs->isEmpty())
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-book text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Log Harian</h3>
                <p class="text-gray-500 mb-6">Log harian akan muncul di sini saat hewan sedang dititipkan.</p>
                <a href="{{ route('user.cek-status', ['kode' => $booking->kode_booking]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Cek Status Terbaru
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($booking->dailyLogs as $log)
                    <div class="border border-gray-200 rounded-xl p-5 hover:border-teal-300 transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">
                                    {{ $log->tanggal->format('d F Y') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Diperbarui: {{ $log->created_at->format('H:i') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm font-medium">
                                Day {{ $loop->iteration }}
                            </span>
                        </div>

                        <!-- Aktivitas -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Aktivitas:</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($log->makan_pagi)
                                    <div class="flex items-center bg-green-50 text-green-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-sun mr-2"></i>
                                        <span>Makan Pagi</span>
                                        @if($log->jam_makan_pagi)
                                            <span class="ml-2 text-xs bg-green-100 px-2 py-0.5 rounded">
                                                {{ date('H:i', strtotime($log->jam_makan_pagi)) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($log->makan_siang)
                                    <div class="flex items-center bg-green-50 text-green-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-sun mr-2"></i>
                                        <span>Makan Siang</span>
                                        @if($log->jam_makan_siang)
                                            <span class="ml-2 text-xs bg-green-100 px-2 py-0.5 rounded">
                                                {{ date('H:i', strtotime($log->jam_makan_siang)) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($log->makan_malam)
                                    <div class="flex items-center bg-green-50 text-green-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-moon mr-2"></i>
                                        <span>Makan Malam</span>
                                        @if($log->jam_makan_malam)
                                            <span class="ml-2 text-xs bg-green-100 px-2 py-0.5 rounded">
                                                {{ date('H:i', strtotime($log->jam_makan_malam)) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($log->minum)
                                    <div class="flex items-center bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-tint mr-2"></i>
                                        <span>Minum</span>
                                        @if($log->jam_minum)
                                            <span class="ml-2 text-xs bg-blue-100 px-2 py-0.5 rounded">
                                                {{ date('H:i', strtotime($log->jam_minum)) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($log->jalan_jalan)
                                    <div class="flex items-center bg-purple-50 text-purple-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-walking mr-2"></i>
                                        <span>Jalan-jalan</span>
                                        @if($log->jam_jalan_jalan)
                                            <span class="ml-2 text-xs bg-purple-100 px-2 py-0.5 rounded">
                                                {{ date('H:i', strtotime($log->jam_jalan_jalan)) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($log->buang_air && $log->buang_air !== 'belum')
                                    <div class="flex items-center bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-toilet mr-2"></i>
                                        <span>Buang Air: {{ ucfirst($log->buang_air) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        @if($log->catatan)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-sticky-note mr-2 text-amber-500"></i>
                                    Catatan Petugas:
                                </h4>
                                <p class="text-gray-700 italic">"{{ $log->catatan }}"</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Informasi Tambahan -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <p class="mb-2">
                        <i class="fas fa-info-circle mr-2 text-teal-500"></i>
                        Log harian diupdate setiap hari oleh petugas
                    </p>
                    <p>
                        <i class="fas fa-clock mr-2 text-teal-500"></i>
                        Update biasanya dilakukan sore/malam hari
                    </p>
                </div>
                <div>
                    <p class="mb-2">
                        <i class="fas fa-phone-alt mr-2 text-teal-500"></i>
                        Ada pertanyaan? Hubungi kami via WhatsApp
                    </p>
                    <p>
                        <i class="fas fa-calendar-check mr-2 text-teal-500"></i>
                        Status terkini: {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Floating -->
    <a href="https://wa.me/6285942173668?text={{ urlencode('Halo PetHouse, saya ingin bertanya tentang log harian ' . $booking->nama_hewan . ' (' . $booking->kode_booking . ')') }}" 
       target="_blank"
       class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition z-50">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>
</div>
@endsection