@extends('layouts.user')

@section('title', 'User - Hewan Saya')

@push('styles')
<style>
    .card-hewan {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: white;
        border: 2px solid transparent;
    }

    .card-hewan:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: #0d9488;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3);
    }

    /* Warna untuk kegiatan */
    .bg-success-100 {
        background-color: #d1f7c4;
    }

    .bg-primary-100 {
        background-color: #cfe2ff;
    }

    .bg-warning-100 {
        background-color: #fff3cd;
    }

    .bg-danger-100 {
        background-color: #f8d7da;
    }

    .bg-info-100 {
        background-color: #d1ecf1;
    }

    .bg-secondary-100 {
        background-color: #e2e3e5;
    }

    .bg-dark-100 {
        background-color: #d6d8d9;
    }

    .text-success-600 {
        color: #198754;
    }

    .text-primary-600 {
        color: #0d6efd;
    }

    .text-warning-600 {
        color: #ffc107;
    }

    .text-danger-600 {
        color: #dc3545;
    }

    .text-info-600 {
        color: #0dcaf0;
    }

    .text-secondary-600 {
        color: #6c757d;
    }

    .text-dark-600 {
        color: #212529;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8" data-aos="fade-up">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Hewan Saya 🐾</h1>
                <p class="text-gray-600 mt-2">Daftar lengkap hewan peliharaan yang pernah atau sedang dititipkan</p>
            </div>
            <a href="{{ route('user.booking.create') }}"
                class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-6 py-3 rounded-xl font-medium transition duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                <i class="fas fa-plus-circle"></i>
                <span>Titip Hewan Baru</span>
            </a>
        </div>
    </div>

    @if($bookings->isEmpty())
    <!-- Empty State -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-3xl shadow-lg p-8 md:p-12 text-center max-w-2xl mx-auto mt-8 border border-gray-200" data-aos="zoom-in">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-r from-teal-100 to-pink-100 mb-6">
            <i class="fas fa-paw text-4xl text-teal-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Hewan</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Anda belum pernah menitipkan hewan di PetHouse. Mulai pengalaman penitipan hewan pertama Anda!
        </p>
        <div class="space-y-4">
            <a href="{{ route('user.booking.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-8 py-3 rounded-xl font-medium transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus-circle mr-3"></i>
                Titip Hewan Pertama
            </a>
            <p class="text-sm text-gray-500 mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                Proses booking mudah dan cepat
            </p>
        </div>
    </div>
    @else
    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Hewan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $bookings->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-paw text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Sedang Dititip</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $bookings->where('status', 'in_progress')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-home text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Log</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $bookings->sum(function($booking) { return $booking->dailyLogs->count(); }) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Hewan -->
    <div class="space-y-6">
        @foreach($bookings as $booking)
        @php
        $status_class = match($booking->status) {
        'pending' => 'bg-yellow-100 text-yellow-800',
        'diterima' => 'bg-green-100 text-green-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'selesai' => 'bg-purple-100 text-purple-800',
        'perpanjangan' => 'bg-orange-100 text-orange-800',
        'pembatalan' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800'
        };

        // Ambil 3 log terbaru untuk preview
        $recentLogs = $booking->dailyLogs->take(3);
        $logCount = $booking->dailyLogs->count();
        @endphp

        <div class="card-hewan" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h2 class="text-2xl font-bold text-teal-700 hover:text-teal-800">
                                <a href="{{ route('user.hewan-saya.log', $booking->id) }}" class="hover:underline">
                                    {{ $booking->nama_hewan }}
                                </a>
                            </h2>
                            <span class="status-badge {{ $status_class }}">
                                {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                            </span>
                            @if($logCount > 0)
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-clipboard-list mr-1"></i> {{ $logCount }} log
                            </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-gray-700 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Jenis Hewan</p>
                                <p class="font-medium">{{ $booking->jenis_hewan }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Ukuran</p>
                                <p class="font-medium">{{ $booking->ukuran_hewan }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Layanan</p>
                                <p class="font-medium">{{ $booking->layanan->nama_layanan ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Kode Booking</p>
                                <p class="font-mono font-bold text-teal-600">{{ $booking->kode_booking }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check mr-2 text-teal-500"></i>
                                <span>Masuk: {{ $booking->tanggal_masuk->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-times mr-2 text-pink-500"></i>
                                <span>Keluar: {{ $booking->tanggal_keluar->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-amber-500"></i>
                                <span>Durasi: {{ $booking->tanggal_masuk->diffInDays($booking->tanggal_keluar) }} hari</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Terbaru (jika sedang dititip dan ada log) -->
                @if($booking->status === 'in_progress' && $recentLogs->count() > 0)
                <div class="mt-4 p-4 bg-gradient-to-r from-teal-50 to-blue-50 rounded-xl border border-teal-100">
                    <p class="font-semibold text-teal-800 mb-3 flex items-center">
                        <i class="fas fa-heart mr-2"></i>
                        Update Terbaru
                    </p>
                    <div class="space-y-3">
                        @foreach($recentLogs as $log)
                        @if($log->kegiatan)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-{{ $log->kegiatan->warna }}-100 flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-{{ $log->kegiatan->icon }} text-{{ $log->kegiatan->warna }}-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $log->kegiatan->nama_kegiatan }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $log->waktu ? \Carbon\Carbon::parse($log->waktu)->format('H:i') : '' }} •
                                            {{ $log->tanggal->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                @if($log->keterangan)
                                <p class="text-sm text-gray-700 mt-1">{{ $log->keterangan }}</p>
                                @endif
                                @if($log->catatan)
                                <p class="text-sm text-gray-600 italic mt-1">"{{ $log->catatan }}"</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @if($logCount > 3)
                    <div class="mt-3 pt-3 border-t border-teal-200">
                        <a href="{{ route('user.hewan-saya.log', $booking->id) }}" class="text-sm text-teal-600 hover:text-teal-800 font-medium">
                            <i class="fas fa-arrow-right mr-1"></i> Lihat {{ $logCount - 3 }} log lainnya
                        </a>
                    </div>
                    @endif
                </div>
                @elseif($booking->status === 'in_progress' && $logCount == 0)
                <div class="mt-4 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-100">
                    <p class="font-semibold text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Menunggu Update Pertama
                    </p>
                    <p class="text-sm text-yellow-700">
                        Log harian akan muncul setelah petugas menginput kegiatan untuk hewan ini.
                        Biasanya diupdate 2-3 kali sehari.
                    </p>
                </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                    @if(!in_array($booking->status, ['pending', 'pembatalan']))
                    <a href="{{ route('user.hewan-saya.log', $booking->id) }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Lihat Log Harian</span>
                        @if($logCount > 0)
                        <span class="bg-white/30 text-xs px-2 py-1 rounded-full">{{ $logCount }}</span>
                        @endif
                    </a>
                    @endif

                    <a href="{{ route('user.booking.show', $booking->id) }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-info-circle"></i>
                        <span>Detail Booking</span>
                    </a>

                    @if($booking->status === 'in_progress')
                    <a href="https://wa.me/6285942173668?text={{ urlencode('Halo PetHouse, saya ingin bertanya tentang booking ' . $booking->kode_booking . ' untuk ' . $booking->nama_hewan) }}"
                        target="_blank"
                        class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                        <i class="fab fa-whatsapp"></i>
                        <span>Tanya Petugas</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Footer Navigasi -->
    <div class="mt-12 pt-8 border-t border-gray-200" data-aos="fade-up">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <a href="{{ route('user.dashboard') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
            <div class="text-gray-600 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Total {{ $bookings->count() }} hewan •
                {{ $bookings->sum(function($booking) { return $booking->dailyLogs->count(); }) }} log kegiatan
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            once: true,
            offset: 100
        });
    }

    // Tooltip untuk icon kegiatan
    document.addEventListener('DOMContentLoaded', function() {
        const kegiatanIcons = document.querySelectorAll('[data-kegiatan-tooltip]');
        kegiatanIcons.forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                const tooltip = this.getAttribute('data-kegiatan-tooltip');
                // Anda bisa implementasikan tooltip custom di sini
                // atau gunakan library tooltip seperti Tippy.js
            });
        });
    });
</script>
@endpush