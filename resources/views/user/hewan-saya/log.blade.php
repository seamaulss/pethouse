@extends('layouts.user')

@section('title', 'Log Harian ' . $booking->nama_hewan . ' - PetHouse')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
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
                        {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d M Y') }}
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-teal-50 p-4 rounded-lg border border-teal-100">
                <p class="text-sm text-teal-800 font-medium mb-1">Layanan</p>
                <p class="text-lg font-bold text-teal-900">{{ $booking->layanan->nama_layanan ?? 'Standard' }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <p class="text-sm text-blue-800 font-medium mb-1">Durasi</p>
                <p class="text-lg font-bold text-blue-900">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->diffInDays($booking->tanggal_keluar) }} hari</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                <p class="text-sm text-purple-800 font-medium mb-1">Status</p>
                <p class="text-lg font-bold text-purple-900 capitalize">
                    {{ str_replace('_', ' ', $booking->status) }}
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <p class="text-sm text-green-800 font-medium mb-1">Total Kegiatan</p>
                <p class="text-lg font-bold text-green-900">
                    {{ \App\Models\DailyLog::where('booking_id', $booking->id)->count() }} log
                </p>
            </div>
        </div>
    </div>

    <!-- Pilih Tanggal -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Tanggal</h2>
                <form method="GET" action="{{ route('user.hewan-saya.log', $booking->id) }}" class="flex items-center gap-4">
                    <select name="tanggal" class="flex-1 border rounded-lg px-4 py-3" onchange="this.form.submit()">
                        <option value="">-- Pilih Tanggal untuk Melihat Log --</option>
                        @foreach($dateOptions as $dateValue => $dateLabel)
                            <option value="{{ $dateValue }}" {{ $selectedDate == $dateValue ? 'selected' : '' }}>
                                {{ $dateLabel }}
                            </option>
                        @endforeach
                    </select>
                    @if(count($dateOptions) > 0)
                        <a href="{{ route('user.hewan-saya.log', $booking->id) }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            
            @if(count($dateOptions) > 0)
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Tanggal Tersedia</p>
                    <p class="text-2xl font-bold text-teal-600">{{ count($dateOptions) }}</p>
                </div>
            @endif
        </div>
        
        <!-- Navigasi Tanggal Cepat -->
        @if(count($dateOptions) > 0)
            @php
                $dates = array_keys($dateOptions);
                $currentIndex = array_search($selectedDate, $dates);
            @endphp
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    @if($currentIndex > 0)
                        @php $prevDate = $dates[$currentIndex - 1]; @endphp
                        <a href="{{ route('user.hewan-saya.log', ['booking' => $booking->id, 'tanggal' => $prevDate]) }}"
                           class="flex items-center text-teal-600 hover:text-teal-800 font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ \Carbon\Carbon::parse($prevDate)->format('d M') }}
                        </a>
                    @else
                        <div></div>
                    @endif
                    
                    <span class="text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
                    </span>
                    
                    @if($currentIndex < count($dates) - 1)
                        @php $nextDate = $dates[$currentIndex + 1]; @endphp
                        <a href="{{ route('user.hewan-saya.log', ['booking' => $booking->id, 'tanggal' => $nextDate]) }}"
                           class="flex items-center text-teal-600 hover:text-teal-800 font-medium">
                            {{ \Carbon\Carbon::parse($nextDate)->format('d M') }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <div></div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Log Harian -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-clipboard-list mr-3 text-teal-500"></i>
                Kegiatan Harian
            </h2>
            
            @if($logs->count() > 0)
                <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm font-medium">
                    {{ $logs->count() }} kegiatan
                </span>
            @endif
        </div>

        @if($logs->isEmpty())
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-book text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">Belum Ada Kegiatan</h3>
                <p class="text-gray-500 mb-6">
                    @if($booking->status === 'in_progress')
                        Belum ada kegiatan yang dicatat untuk tanggal {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}.
                        Kegiatan biasanya diupdate 2-3 kali sehari.
                    @else
                        Tidak ada kegiatan yang tercatat untuk tanggal ini.
                    @endif
                </p>
                @if($booking->status === 'in_progress')
                    <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded-lg inline-block">
                        <i class="fas fa-info-circle mr-2"></i>
                        Update akan muncul setelah petugas menginput kegiatan
                    </div>
                @endif
            </div>
        @else
            <!-- Timeline Kegiatan -->
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-4 md:left-6 top-0 bottom-0 w-0.5 bg-teal-200"></div>
                
                <div class="space-y-8">
                    @foreach($logs as $log)
                        @if($log->kegiatan)
                            <div class="relative">
                                <!-- Timeline dot -->
                                <div class="absolute left-4 md:left-6 transform -translate-x-1/2 w-3 h-3 rounded-full bg-{{ $log->kegiatan->warna }}-500 border-4 border-white shadow"></div>
                                
                                <!-- Content -->
                                <div class="ml-10 md:ml-12">
                                    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-{{ $log->kegiatan->warna }}-300 transition">
                                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <!-- Icon Kegiatan -->
                                                    <div class="w-10 h-10 rounded-full bg-{{ $log->kegiatan->warna }}-100 flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-{{ $log->kegiatan->icon }} text-{{ $log->kegiatan->warna }}-600"></i>
                                                    </div>
                                                    
                                                    <div class="flex-1">
                                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                                            <h3 class="text-lg font-bold text-gray-800">
                                                                {{ $log->kegiatan->nama_kegiatan }}
                                                            </h3>
                                                            <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                                                {{ \Carbon\Carbon::parse($log->waktu)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                        
                                                        @if($log->kegiatan->deskripsi)
                                                            <p class="text-sm text-gray-600 mt-1">
                                                                {{ $log->kegiatan->deskripsi }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Keterangan -->
                                                @if($log->keterangan)
                                                    <div class="mt-3">
                                                        <p class="text-gray-700">
                                                            <span class="font-medium">Keterangan:</span> {{ $log->keterangan }}
                                                        </p>
                                                    </div>
                                                @endif
                                                
                                                <!-- Jumlah & Satuan -->
                                                @if($log->jumlah)
                                                    <div class="mt-3 flex items-center gap-2">
                                                        <span class="px-3 py-1 bg-{{ $log->kegiatan->warna }}-50 text-{{ $log->kegiatan->warna }}-800 rounded-lg text-sm font-medium">
                                                            <i class="fas fa-hashtag mr-1"></i>
                                                            Jumlah: {{ $log->jumlah }}
                                                            @if($log->satuan)
                                                                {{ $log->satuan }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Catatan -->
                                                @if($log->catatan)
                                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border-l-4 border-{{ $log->kegiatan->warna }}-400">
                                                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                            <i class="fas fa-sticky-note mr-2 text-{{ $log->kegiatan->warna }}-500"></i>
                                                            Catatan Petugas:
                                                        </h4>
                                                        <p class="text-gray-700 italic">"{{ $log->catatan }}"</p>
                                                    </div>
                                                @endif
                                                
                                                <!-- Petugas -->
                                                <div class="mt-4 pt-4 border-t border-gray-100">
                                                    <p class="text-sm text-gray-600 flex items-center">
                                                        <i class="fas fa-user mr-2 text-gray-400"></i>
                                                        Dicatat oleh: <span class="font-medium ml-1">{{ $log->petugas->username ?? 'Petugas' }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $log->created_at->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Summary -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Statistik Hari Ini</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Kegiatan</span>
                                <span class="font-bold text-teal-600">{{ $logs->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Kegiatan Berdasarkan Jenis</h3>
                        <div class="space-y-3">
                            @php
                                $kegiatanCounts = $logs->groupBy('kegiatan.nama_kegiatan')->map->count();
                            @endphp
                            @foreach($kegiatanCounts as $nama => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ $nama }}</span>
                                    <span class="font-bold text-teal-600">{{ $count }}x</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Informasi Tambahan -->
    <div class="mt-8 bg-gradient-to-r from-teal-50 to-blue-50 rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Penting</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div class="space-y-2">
                <p class="flex items-center">
                    <i class="fas fa-info-circle mr-2 text-teal-500"></i>
                    Log diupdate setiap hari oleh petugas
                </p>
                <p class="flex items-center">
                    <i class="fas fa-clock mr-2 text-teal-500"></i>
                    Update biasanya 2-3 kali sehari (pagi, siang, malam)
                </p>
                <p class="flex items-center">
                    <i class="fas fa-bell mr-2 text-teal-500"></i>
                    Status booking dapat berubah kapan saja
                </p>
            </div>
            <div class="space-y-2">
                <p class="flex items-center">
                    <i class="fas fa-phone-alt mr-2 text-teal-500"></i>
                    Ada pertanyaan? Hubungi via WhatsApp
                </p>
                <p class="flex items-center">
                    <i class="fas fa-calendar-check mr-2 text-teal-500"></i>
                    Status terkini: {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                </p>
                <p class="flex items-center">
                    <i class="fas fa-user-md mr-2 text-teal-500"></i>
                    Petugas selalu siap membantu 24/7
                </p>
            </div>
        </div>
        
        <!-- WhatsApp Button -->
        <div class="mt-6 pt-6 border-t border-teal-200">
            <a href="https://wa.me/6285942173668?text={{ urlencode('Halo PetHouse, saya ingin bertanya tentang log harian ' . $booking->nama_hewan . ' (' . $booking->kode_booking . ') untuk tanggal ' . $selectedDate) }}" 
               target="_blank"
               class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg font-medium transition shadow-md hover:shadow-lg">
                <i class="fab fa-whatsapp mr-3 text-xl"></i>
                Tanya Petugas via WhatsApp
            </a>
        </div>
    </div>
</div>

<style>
    /* Custom CSS for activity colors */
    .bg-success-100 { background-color: #d1f7c4; }
    .bg-success-50 { background-color: #e8f5e9; }
    .bg-success-500 { background-color: #4caf50; }
    .text-success-600 { color: #2e7d32; }
    .border-success-300 { border-color: #81c784; }
    .border-success-400 { border-color: #66bb6a; }
    
    .bg-primary-100 { background-color: #cfe2ff; }
    .bg-primary-50 { background-color: #e3f2fd; }
    .bg-primary-500 { background-color: #2196f3; }
    .text-primary-600 { color: #1976d2; }
    .border-primary-300 { border-color: #64b5f6; }
    .border-primary-400 { border-color: #42a5f5; }
    
    .bg-warning-100 { background-color: #fff3cd; }
    .bg-warning-50 { background-color: #fff8e1; }
    .bg-warning-500 { background-color: #ff9800; }
    .text-warning-600 { color: #f57c00; }
    .border-warning-300 { border-color: #ffb74d; }
    .border-warning-400 { border-color: #ffa726; }
    
    .bg-danger-100 { background-color: #f8d7da; }
    .bg-danger-50 { background-color: #ffebee; }
    .bg-danger-500 { background-color: #f44336; }
    .text-danger-600 { color: #e53935; }
    .border-danger-300 { border-color: #e57373; }
    .border-danger-400 { border-color: #ef5350; }
    
    .bg-info-100 { background-color: #d1ecf1; }
    .bg-info-50 { background-color: #e0f7fa; }
    .bg-info-500 { background-color: #00bcd4; }
    .text-info-600 { color: #00acc1; }
    .border-info-300 { border-color: #4dd0e1; }
    .border-info-400 { border-color: #26c6da; }
    
    .bg-secondary-100 { background-color: #e2e3e5; }
    .bg-secondary-50 { background-color: #f5f5f5; }
    .bg-secondary-500 { background-color: #6c757d; }
    .text-secondary-600 { color: #5a6268; }
    .border-secondary-300 { border-color: #b5b5b5; }
    .border-secondary-400 { border-color: #9e9e9e; }
    
    .bg-dark-100 { background-color: #d6d8d9; }
    .bg-dark-50 { background-color: #f5f5f5; }
    .bg-dark-500 { background-color: #343a40; }
    .text-dark-600 { color: #212529; }
    .border-dark-300 { border-color: #6c757d; }
    .border-dark-400 { border-color: #495057; }
</style>
@endsection