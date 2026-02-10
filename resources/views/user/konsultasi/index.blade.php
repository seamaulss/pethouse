@extends('layouts.user')

@section('title', 'Konsultasi Saya - PetHouse')

@push('styles')
<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .empty-state {
        background: linear-gradient(135deg, #faf9f6 0%, #f5f3eb 100%);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header dengan statistik -->
    <div class="mb-8" data-aos="fade-up">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Konsultasi Saya</h1>
                <p class="text-gray-600 mt-2">Riwayat konsultasi hewan peliharaan Anda dengan dokter kami</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-2 text-gray-600">
                    <i class="fas fa-calendar-day text-teal-600"></i>
                    <span>{{ now()->format('d F Y') }}</span>
                </div>
                <a href="{{ route('user.konsultasi.create') }}" class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-5 py-3 rounded-xl font-medium transition duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Konsultasi Baru</span>
                </a>
            </div>
        </div>
    </div>

    @if($consultations->count() == 0)
    <!-- Empty State -->
    <div class="empty-state rounded-3xl shadow-lg p-8 md:p-12 text-center max-w-2xl mx-auto mt-8 border border-amber-100" data-aos="zoom-in">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-r from-amber-100 to-pink-100 mb-6">
            <i class="fas fa-comments text-4xl text-amber-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Konsultasi</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Anda belum pernah melakukan konsultasi dengan dokter kami. Ajukan konsultasi pertama Anda sekarang!
        </p>
        <div class="space-y-4">
            <a href="{{ route('user.konsultasi.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-8 py-3 rounded-xl font-medium transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-plus-circle mr-3"></i>
                Ajukan Konsultasi Pertama
            </a>
            <p class="text-sm text-gray-500 mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                Konsultasi akan ditanggapi dalam 1x24 jam oleh dokter kami
            </p>
        </div>
    </div>
    @else
    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Konsultasi</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center">
                    <i class="fas fa-stethoscope text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Dalam Proses</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pending + $diterima }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Selesai</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $selesai }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-pink-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Konsultasi -->
    <div class="space-y-6">
        @foreach($consultations as $konsultasi)
        <div class="card-hover bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-200 mb-6" data-aos="fade-up">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $konsultasi->status_class }}">
                            {{ $konsultasi->status_label }}
                        </span>
                        <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $konsultasi->topik }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">ID Reservasi</p>
                        <p class="font-mono font-bold text-teal-600">{{ $konsultasi->kode_konsultasi }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-50">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Hewan</p>
                        <p class="font-semibold">{{ $konsultasi->jenis_hewan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Jadwal</p>
                        <p class="font-semibold">{{ $konsultasi->tanggal_janji->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Waktu</p>
                        <p class="font-semibold">{{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Pemilik</p>
                        <p class="font-semibold text-truncate">{{ $konsultasi->nama_pemilik }}</p>
                    </div>
                </div>

                @if($konsultasi->status === 'selesai')
                <div class="mt-6 p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <div class="flex items-center mb-3 text-emerald-700">
                        <i class="fas fa-file-medical-alt mr-2 text-xl"></i>
                        <h4 class="font-bold">Hasil Rekam Medis</h4>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm text-gray-700 italic">
                        {{ $konsultasi->balasan_dokter ?? 'Dokter tidak memberikan catatan tambahan.' }}
                    </div>
                </div>
                @elseif($konsultasi->status === 'diterima')
                <div class="mt-6 p-4 rounded-2xl bg-blue-50 border border-blue-100 flex items-center text-blue-700">
                    <i class="fas fa-info-circle mr-3 text-lg"></i>
                    <p class="text-sm font-medium">Jadwal Anda sudah dikonfirmasi. Silakan datang ke klinik sesuai waktu di atas.</p>
                </div>
                @endif
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
                Butuh bantuan? Hubungi kami di
                <a href="mailto:support@pethouse.com" class="text-teal-600 hover:underline">support@pethouse.com</a>
            </div>
        </div>
    </div>
</div>
@endsection