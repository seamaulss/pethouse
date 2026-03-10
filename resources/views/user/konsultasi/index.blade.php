@extends('layouts.user')

@section('title', 'Konsultasi Saya - LARAPetHouse')

@push('styles')
<style>
    /* Tab Active State */
    .tab-active {
        border-bottom: 3px solid #0d9488;
        color: #0d9488;
    }
    .card-active {
        border-left: 6px solid #fbbf24; /* Amber untuk yang sedang berjalan */
    }
    .card-history {
        border-left: 6px solid #10b981; /* Emerald untuk yang selesai */
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'ongoing' }">
    
    <div class="mb-8" data-aos="fade-up">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Konsultasi <span class="text-teal-600">Saya</span></h1>
                <p class="text-gray-500 mt-2 text-lg">Kelola jadwal pertemuan dan lihat rekam medis anabul Anda.</p>
            </div>
            <a href="{{ route('user.konsultasi.create') }}" class="inline-flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white px-6 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-teal-200 group">
                <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform"></i>
                Buat Janji Temu Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10" data-aos="fade-up">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total</p>
            <p class="text-2xl font-black text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-amber-50 p-6 rounded-3xl shadow-sm border border-amber-100">
            <p class="text-sm text-amber-600 font-medium">Menunggu</p>
            <p class="text-2xl font-black text-amber-700">{{ $pending }}</p>
        </div>
        <div class="bg-blue-50 p-6 rounded-3xl shadow-sm border border-blue-100">
            <p class="text-sm text-blue-600 font-medium">Dikonfirmasi</p>
            <p class="text-2xl font-black text-blue-700">{{ $diterima }}</p>
        </div>
        <div class="bg-emerald-50 p-6 rounded-3xl shadow-sm border border-emerald-100">
            <p class="text-sm text-emerald-600 font-medium">Selesai</p>
            <p class="text-2xl font-black text-emerald-700">{{ $selesai }}</p>
        </div>
    </div>

    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto">
        <button @click="activeTab = 'ongoing'" 
                :class="activeTab === 'ongoing' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="pb-4 px-6 font-bold text-lg border-b-2 transition-all whitespace-nowrap">
            <i class="fas fa-calendar-alt mr-2"></i>Konsultasi Aktif 
            <span class="ml-2 bg-gray-100 px-2 py-0.5 rounded-lg text-xs">{{ $pending + $diterima }}</span>
        </button>
        <button @click="activeTab = 'history'" 
                :class="activeTab === 'history' ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="pb-4 px-6 font-bold text-lg border-b-2 transition-all whitespace-nowrap">
            <i class="fas fa-history mr-2"></i>Riwayat Selesai
            <span class="ml-2 bg-gray-100 px-2 py-0.5 rounded-lg text-xs">{{ $selesai }}</span>
        </button>
    </div>

    <div>
        <div x-show="activeTab === 'ongoing'" x-transition x-cloak>
            @php $ongoing = $consultations->whereIn('status', ['pending', 'diterima']); @endphp
            
            @forelse($ongoing as $konsultasi)
                <div class="bg-white rounded-3xl shadow-md border border-gray-100 mb-6 card-active overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 rounded-lg text-xs font-black tracking-widest uppercase {{ $konsultasi->status_class }}">
                                        {{ $konsultasi->status_label }}
                                    </span>
                                    <span class="text-xs font-mono text-gray-400">#{{ $konsultasi->kode_konsultasi }}</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $konsultasi->topik }}</h3>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full"><i class="fas fa-paw mr-2 text-teal-500"></i>{{ $konsultasi->jenis_hewan }}</span>
                                    <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full"><i class="fas fa-calendar mr-2 text-teal-500"></i>{{ $konsultasi->tanggal_janji->translatedFormat('d F Y') }}</span>
                                    <span class="flex items-center bg-gray-50 px-3 py-1 rounded-full"><i class="fas fa-clock mr-2 text-teal-500"></i>{{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</span>
                                </div>
                            </div>
                            
                            @if($konsultasi->status === 'diterima')
                            <div class="flex items-center">
                                <div class="bg-teal-50 border border-teal-100 p-4 rounded-2xl flex items-center gap-3">
                                    <div class="bg-teal-500 text-white p-2 rounded-xl ring-4 ring-teal-100">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-teal-600 font-bold uppercase">Lokasi Klinik</p>
                                        <p class="text-sm font-semibold text-teal-900 leading-tight text-nowrap">Datang Tepat Waktu</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                @include('user.konsultasi.partials.empty-ongoing')
            @endforelse
        </div>

        <div x-show="activeTab === 'history'" x-transition x-cloak>
            @php $history = $consultations->where('status', 'selesai'); @endphp
            
            @forelse($history as $konsultasi)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 mb-6 card-history overflow-hidden grayscale hover:grayscale-0 transition-all opacity-80 hover:opacity-100">
                    <div class="p-6 md:p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-700">{{ $konsultasi->topik }}</h3>
                                <p class="text-sm text-gray-500">Selesai pada {{ $konsultasi->updated_at->format('d M Y') }}</p>
                            </div>
                            <span class="bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-xs font-bold uppercase">Selesai</span>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 border border-dashed border-gray-200">
                            <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center">
                                <i class="fas fa-file-prescription mr-2 text-lg"></i> Hasil Rekam Medis
                            </h4>
                            <p class="text-gray-700 leading-relaxed italic">
                                "{{ $konsultasi->balasan_dokter ?? 'Tidak ada catatan tambahan dari dokter.' }}"
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <i class="fas fa-folder-open text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 font-medium">Belum ada riwayat konsultasi yang selesai.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection