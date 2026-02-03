@extends('layouts.user')

@section('title', 'Log Harian ' . $booking->nama_hewan . ' - PetHouse')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ route('user.hewan-saya') }}" class="text-teal-600 hover:underline flex items-center gap-1">
            ← Kembali ke Hewan Saya
        </a>
        <h1 class="text-2xl font-bold mt-2">📊 Log Harian: {{ $booking->nama_hewan }}</h1>
        <p class="text-gray-600">
            {{ $booking->jenis_hewan }} • 
            {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/Y') }} s/d 
            {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}
        </p>
        
        <!-- Status Booking -->
        <div class="mt-2 inline-block px-3 py-1 rounded-full text-sm 
            @if($booking->status == 'in_progress') bg-yellow-100 text-yellow-800
            @elseif($booking->status == 'selesai') bg-green-100 text-green-800
            @else bg-gray-100 text-gray-800 @endif">
            Status: {{ str_replace('_', ' ', ucfirst($booking->status)) }}
        </div>
    </div>

    <!-- Form Pilih Tanggal -->
    <div class="bg-white p-5 rounded-lg shadow mb-6">
        <h3 class="font-semibold mb-3">Pilih Tanggal</h3>
        <form method="GET" action="{{ route('user.hewan-saya.log', $booking->id) }}" class="flex items-center gap-4">
            <select name="tanggal" class="flex-1 border rounded-lg px-4 py-2" onchange="this.form.submit()">
                <option value="">-- Pilih Tanggal --</option>
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

    @if($logs->count() > 0)
        <!-- Statistik Hari Ini -->
        <div class="bg-teal-50 p-5 rounded-lg shadow mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-teal-800">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
                    </h3>
                    <p class="text-teal-600">
                        {{ $logs->count() }} kegiatan tercatat
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-teal-600">
                        @php
                            $kegiatanTypes = $logs->pluck('kegiatan.nama_kegiatan')->unique()->count();
                        @endphp
                        {{ $kegiatanTypes }} jenis kegiatan berbeda
                    </p>
                    <p class="text-xs text-teal-500">
                        Terakhir diupdate: {{ $logs->last()->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Daftar Kegiatan -->
        <div class="space-y-4">
            @foreach($logs as $log)
                <div class="bg-white p-5 rounded-lg shadow border border-gray-200 hover:border-teal-300 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <!-- Icon Kegiatan -->
                                @if($log->kegiatan->icon)
                                    <div class="w-10 h-10 rounded-full bg-{{ $log->kegiatan->warna }}-100 flex items-center justify-center">
                                        <i class="fas fa-{{ $log->kegiatan->icon }} text-{{ $log->kegiatan->warna }}-600"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-gray-800">
                                            {{ $log->kegiatan->nama_kegiatan }}
                                        </h4>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ \Carbon\Carbon::parse($log->waktu)->format('H:i') }}
                                        </span>
                                    </div>
                                    
                                    <!-- Keterangan -->
                                    @if($log->keterangan)
                                        <p class="text-gray-700 mt-2">{{ $log->keterangan }}</p>
                                    @endif
                                    
                                    <!-- Jumlah & Satuan -->
                                    @if($log->jumlah)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <span class="font-semibold">Jumlah:</span> 
                                            {{ $log->jumlah }}
                                            @if($log->satuan)
                                                {{ $log->satuan }}
                                            @endif
                                        </p>
                                    @endif
                                    
                                    <!-- Catatan -->
                                    @if($log->catatan)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-md border-l-4 border-teal-400">
                                            <p class="text-gray-700">
                                                <span class="font-semibold">Catatan:</span> {{ $log->catatan }}
                                            </p>
                                        </div>
                                    @endif
                                    
                                    <!-- Petugas -->
                                    <p class="text-xs text-gray-500 mt-3">
                                        Dicatat oleh: <span class="font-semibold">{{ $log->petugas->username ?? 'Petugas' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Summary -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <p class="text-center text-gray-600">
                🐾 Hewan kesayangan Anda dalam pengawasan penuh petugas kami.
                @if($booking->status == 'in_progress')
                    Log akan terus diperbarui setiap hari.
                @endif
            </p>
        </div>
        
    @else
        <!-- Tidak ada log -->
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">
                Belum ada log kegiatan
            </h3>
            <p class="text-gray-600 mb-4">
                @if($booking->status == 'in_progress')
                    Log harian akan muncul setelah petugas menginput kegiatan untuk hari ini.
                @else
                    Booking ini sudah selesai dan tidak ada log yang tercatat.
                @endif
            </p>
            
            @if($booking->status == 'in_progress')
                <div class="inline-block bg-blue-50 text-blue-800 px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-clock mr-2"></i>
                    Log biasanya diupdate 2-3 kali sehari oleh petugas
                </div>
            @endif
        </div>
    @endif
    
    <!-- Navigasi Tanggal -->
    @if(count($dateOptions) > 0)
        <div class="mt-6 flex justify-between">
            @php
                $dates = array_keys($dateOptions);
                $currentIndex = array_search($selectedDate, $dates);
                $prevDate = $currentIndex > 0 ? $dates[$currentIndex - 1] : null;
                $nextDate = $currentIndex < count($dates) - 1 ? $dates[$currentIndex + 1] : null;
            @endphp
            
            @if($prevDate)
                <a href="{{ route('user.hewan-saya.log', ['booking' => $booking->id, 'tanggal' => $prevDate]) }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    ← {{ \Carbon\Carbon::parse($prevDate)->format('d M') }}
                </a>
            @else
                <div></div>
            @endif
            
            @if($nextDate)
                <a href="{{ route('user.hewan-saya.log', ['booking' => $booking->id, 'tanggal' => $nextDate]) }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    {{ \Carbon\Carbon::parse($nextDate)->format('d M') }} →
                </a>
            @endif
        </div>
    @endif
</div>

<style>
    .bg-success-100 { background-color: #d1f7c4; }
    .bg-primary-100 { background-color: #cfe2ff; }
    .bg-warning-100 { background-color: #fff3cd; }
    .bg-danger-100 { background-color: #f8d7da; }
    .bg-info-100 { background-color: #d1ecf1; }
    .bg-secondary-100 { background-color: #e2e3e5; }
    .bg-dark-100 { background-color: #d6d8d9; }
    
    .text-success-600 { color: #198754; }
    .text-primary-600 { color: #0d6efd; }
    .text-warning-600 { color: #ffc107; }
    .text-danger-600 { color: #dc3545; }
    .text-info-600 { color: #0dcaf0; }
    .text-secondary-600 { color: #6c757d; }
    .text-dark-600 { color: #212529; }
</style>
@endsection