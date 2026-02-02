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
                @php
                    $status_class = '';
                    $status_icon = '';
                    if ($konsultasi->status === 'pending') {
                        $status_class = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                        $status_icon = 'fas fa-clock';
                    } elseif ($konsultasi->status === 'diterima') {
                        $status_class = 'bg-blue-100 text-blue-800 border border-blue-200';
                        $status_icon = 'fas fa-user-md';
                    } elseif ($konsultasi->status === 'selesai') {
                        $status_class = 'bg-green-100 text-green-800 border border-green-200';
                        $status_icon = 'fas fa-check-circle';
                    }
                @endphp
                
                <div class="card-hover bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-200" data-aos="fade-up">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-800">{{ $konsultasi->topik }}</h3>
                                    <span class="status-badge {{ $status_class }}">
                                        <i class="{{ $status_icon }} mr-1"></i>
                                        {{ ucfirst($konsultasi->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center">
                                            <i class="fas fa-paw text-teal-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Jenis Hewan</p>
                                            <p class="font-medium">{{ $konsultasi->jenis_hewan }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tanggal Janji</p>
                                            <p class="font-medium">{{ $konsultasi->tanggal_janji->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-pink-50 flex items-center justify-center">
                                            <i class="fas fa-clock text-pink-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Jam Janji</p>
                                            <p class="font-medium">{{ date('H:i', strtotime($konsultasi->jam_janji)) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-hashtag mr-2 text-gray-400"></i>
                                        <span>Kode: <span class="font-mono font-bold text-gray-800">{{ $konsultasi->kode_konsultasi }}</span></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                                        <span>Dibuat: {{ $konsultasi->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Balasan dari Dokter -->
                        @if($konsultasi->balasan_dokter)
                            <div class="mt-6 p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-teal-50 border border-blue-100">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-teal-500 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-md text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Balasan dari Dokter</h4>
                                        <p class="text-sm text-gray-600">Dikirim pada {{ $konsultasi->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="pl-13">
                                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $konsultasi->balasan_dokter }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($konsultasi->status !== 'pending')
                            <div class="mt-6 p-4 rounded-2xl bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200">
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-half text-amber-600 mr-3 text-xl"></i>
                                    <div>
                                        <h4 class="font-medium text-amber-800">Menunggu Balasan Dokter</h4>
                                        <p class="text-sm text-amber-700">Dokter sedang mempersiapkan respon untuk konsultasi Anda.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 p-4 rounded-2xl bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200">
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-start text-gray-600 mr-3 text-xl"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Menunggu Konfirmasi Dokter</h4>
                                        <p class="text-sm text-gray-700">Konsultasi Anda sedang menunggu untuk diterima oleh dokter.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Aksi -->
                        <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                            <a href="{{ route('user.cek-status.show', ['kode' => $konsultasi->kode_konsultasi]) }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fas fa-search"></i>
                                <span>Detail Status</span>
                            </a>
                            
                            @if($konsultasi->status === 'diterima')
                                <a href="https://wa.me/{{ ltrim($konsultasi->no_wa, '0') }}" 
                                   target="_blank"
                                   class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Chat Dokter</span>
                                </a>
                            @endif
                            
                            <a href="{{ route('user.konsultasi.create') }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fas fa-copy"></i>
                                <span>Konsultasi Baru</span>
                            </a>
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
                Butuh bantuan? Hubungi kami di 
                <a href="mailto:support@pethouse.com" class="text-teal-600 hover:underline">support@pethouse.com</a>
            </div>
        </div>
    </div>
</div>
@endsection