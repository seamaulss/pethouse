@extends('layouts.user')

@section('title', 'Konsultasi Saya - LARAPetHouse')

@push('styles')
<style>
    /* Tab Active State - Disesuaikan dengan tema Dashboard */
    .tab-active {
        border-bottom: 3px solid #0d9488;
        color: #0d9488;
    }
    .card-ongoing {
        border-left: 6px solid #f59e0b; /* Warna Amber untuk Aktif */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-history {
        border-left: 6px solid #10b981; /* Warna Emerald untuk Selesai */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-ongoing:hover, .card-history:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ activeTab: 'ongoing' }">
    
    <div class="mb-10" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Konsultasi <span class="text-teal-600">Saya</span> 🩺
                </h1>
                <p class="text-gray-500 mt-2 text-base sm:text-lg">Kelola jadwal janji temu dan pantau hasil diagnosis anabul Anda.</p>
            </div>
            <a href="{{ route('user.konsultasi.create') }}" class="inline-flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-lg shadow-teal-100 group active:scale-95">
                <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform"></i>
                Buat Janji Temu
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-12" data-aos="fade-up">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <span class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 mb-4"><i class="fas fa-list-ul"></i></span>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total</p>
                <p class="text-3xl font-black text-gray-800">{{ $total }}</p>
            </div>
        </div>
        <div class="bg-amber-50 p-6 rounded-3xl shadow-sm border border-amber-100 flex flex-col justify-between">
            <span class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 mb-4"><i class="fas fa-clock"></i></span>
            <div>
                <p class="text-xs text-amber-500 font-bold uppercase tracking-widest">Menunggu</p>
                <p class="text-3xl font-black text-amber-700">{{ $pending }}</p>
            </div>
        </div>
        <div class="bg-blue-50 p-6 rounded-3xl shadow-sm border border-blue-100 flex flex-col justify-between">
            <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-4"><i class="fas fa-check-circle"></i></span>
            <div>
                <p class="text-xs text-blue-500 font-bold uppercase tracking-widest">Diterima</p>
                <p class="text-3xl font-black text-blue-700">{{ $diterima }}</p>
            </div>
        </div>
        <div class="bg-emerald-50 p-6 rounded-3xl shadow-sm border border-emerald-100 flex flex-col justify-between">
            <span class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mb-4"><i class="fas fa-clipboard-check"></i></span>
            <div>
                <p class="text-xs text-emerald-500 font-bold uppercase tracking-widest">Selesai</p>
                <p class="text-3xl font-black text-emerald-700">{{ $selesai }}</p>
            </div>
        </div>
    </div>

    <div class="flex border-b border-gray-100 mb-8 overflow-x-auto no-scrollbar">
        <button @click="activeTab = 'ongoing'" 
                :class="activeTab === 'ongoing' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="pb-4 px-8 font-bold text-base sm:text-lg border-b-2 transition-all whitespace-nowrap flex items-center gap-2">
            <i class="fas fa-calendar-alt"></i>
            Konsultasi Aktif 
            <span class="ml-1 bg-teal-50 text-teal-600 px-2 py-0.5 rounded-lg text-xs">{{ $pending + $diterima }}</span>
        </button>
        <button @click="activeTab = 'history'" 
                :class="activeTab === 'history' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="pb-4 px-8 font-bold text-base sm:text-lg border-b-2 transition-all whitespace-nowrap flex items-center gap-2">
            <i class="fas fa-history"></i>
            Riwayat Selesai
            <span class="ml-1 bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg text-xs">{{ $selesai }}</span>
        </button>
    </div>

    <div class="min-h-[400px]">
        <div x-show="activeTab === 'ongoing'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            @php $ongoing = $consultations->whereIn('status', ['pending', 'diterima']); @endphp
            
            @forelse($ongoing as $konsultasi)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 mb-6 card-ongoing overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col lg:flex-row justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase {{ $konsultasi->status_class }}">
                                        {{ $konsultasi->status_label }}
                                    </span>
                                    <span class="text-xs font-mono text-gray-300 bg-gray-50 px-2 py-1 rounded">ID: {{ $konsultasi->kode_konsultasi }}</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">{{ $konsultasi->topik }}</h3>
                                
                                <div class="flex flex-wrap gap-3 sm:gap-4">
                                    <div class="flex items-center bg-gray-50 px-4 py-2 rounded-xl text-sm text-gray-600">
                                        <i class="fas fa-paw mr-3 text-teal-500"></i>
                                        <span class="font-medium">{{ $konsultasi->jenis_hewan }}</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 px-4 py-2 rounded-xl text-sm text-gray-600">
                                        <i class="fas fa-calendar-day mr-3 text-teal-500"></i>
                                        <span class="font-medium">{{ $konsultasi->tanggal_janji->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 px-4 py-2 rounded-xl text-sm text-gray-600">
                                        <i class="fas fa-clock mr-3 text-teal-500"></i>
                                        <span class="font-medium">{{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($konsultasi->status === 'diterima')
                            <div class="flex items-center lg:justify-end">
                                <div class="bg-teal-50 border border-teal-100 p-5 rounded-2xl flex items-center gap-4 w-full lg:w-auto">
                                    <div class="bg-teal-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-teal-200">
                                        <i class="fas fa-hospital-user text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-teal-600 font-bold uppercase tracking-tighter">Lokasi Konsultasi</p>
                                        <p class="text-sm font-bold text-teal-900">Datang ke Klinik</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100" data-aos="zoom-in">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <i class="fas fa-calendar-times text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Jadwal Aktif</h3>
                    <p class="text-gray-400 max-w-xs mx-auto text-sm">Anda belum memiliki janji temu konsultasi yang sedang berjalan.</p>
                </div>
            @endforelse
        </div>

        <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            @php $history = $consultations->where('status', 'selesai'); @endphp
            
            @forelse($history as $konsultasi)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 mb-6 card-history overflow-hidden hover:grayscale-0 transition-all group">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-teal-600 transition-colors">{{ $konsultasi->topik }}</h3>
                                <p class="text-xs text-gray-400 mt-1 flex items-center">
                                    <i class="fas fa-check-double text-emerald-500 mr-2"></i>
                                    Selesai pada {{ $konsultasi->updated_at->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                            <span class="bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">Verified Selesai</span>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 relative">
                            <i class="fas fa-quote-left absolute top-4 left-4 text-gray-200 text-3xl opacity-50"></i>
                            
                            <h4 class="text-[10px] font-black text-teal-600 uppercase tracking-[0.2em] mb-4 flex items-center relative z-10">
                                <i class="fas fa-notes-medical mr-2 text-base"></i> Catatan & Rekam Medis Dokter
                            </h4>
                            <div class="text-gray-600 leading-relaxed relative z-10 pl-2 border-l-2 border-teal-200 ml-1">
                                @if($konsultasi->balasan_dokter)
                                    <p class="italic text-gray-700 font-medium">"{{ $konsultasi->balasan_dokter }}"</p>
                                @else
                                    <p class="text-gray-400 italic">Dokter tidak meninggalkan catatan tambahan untuk sesi ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Riwayat Kosong</h3>
                    <p class="text-gray-400 max-w-xs mx-auto text-sm">Belum ada catatan medis dari konsultasi sebelumnya.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Menghilangkan scrollbar tapi tetap bisa scroll secara horizontal di mobile */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection