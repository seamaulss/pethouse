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
                        <p class="text-gray-500 text-sm font-medium">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ $bookings->where('status', 'selesai')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
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
                        default => 'bg-gray-100 text-gray-800'
                    };
                    
                    $latestLog = $booking->dailyLogs->first();
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

                        <!-- Update Terbaru (jika sedang dititip) -->
                        @if($booking->status === 'in_progress' && $latestLog)
                            <div class="mt-4 p-4 bg-gradient-to-r from-teal-50 to-blue-50 rounded-xl border border-teal-100">
                                <p class="font-semibold text-teal-800 mb-2 flex items-center">
                                    <i class="fas fa-heart mr-2"></i>
                                    Update Terbaru - {{ $latestLog->tanggal->format('d F Y') }}
                                </p>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @if($latestLog->makan_pagi)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                                            <i class="fas fa-utensils mr-1"></i> Pagi
                                        </span>
                                    @endif
                                    @if($latestLog->makan_siang)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                                            <i class="fas fa-utensils mr-1"></i> Siang
                                        </span>
                                    @endif
                                    @if($latestLog->makan_malam)
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                                            <i class="fas fa-utensils mr-1"></i> Malam
                                        </span>
                                    @endif
                                    @if($latestLog->minum)
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center">
                                            <i class="fas fa-tint mr-1"></i> Minum
                                        </span>
                                    @endif
                                    @if($latestLog->jalan_jalan)
                                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm flex items-center">
                                            <i class="fas fa-walking mr-1"></i> Jalan-jalan
                                        </span>
                                    @endif
                                </div>
                                @if($latestLog->catatan)
                                    <p class="text-gray-700 italic text-sm mt-2">
                                        <i class="fas fa-comment mr-2"></i> "{{ $latestLog->catatan }}"
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Tombol Aksi -->
                        <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                            <a href="{{ route('user.hewan-saya.log', $booking->id) }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fas fa-history"></i>
                                <span>Lihat Log Harian</span>
                            </a>
                            
                            <!-- <a href="{{ route('user.cek-status', ['kode' => $booking->kode_booking]) }}" 
                               class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fas fa-search"></i>
                                <span>Cek Status</span>
                            </a> -->
                            
                            <a href="https://wa.me/6285942173668?text={{ urlencode('Halo PetHouse, saya ingin bertanya tentang booking ' . $booking->kode_booking) }}" 
                               target="_blank"
                               class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium transition duration-200 flex items-center space-x-2">
                                <i class="fab fa-whatsapp"></i>
                                <span>Tanya via WA</span>
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
                Total {{ $bookings->count() }} hewan yang pernah dititipkan
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
</script>
@endpush