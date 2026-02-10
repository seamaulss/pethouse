@extends('layouts.dokter')

@section('title', "Konsultasi #{$konsultasi->kode_konsultasi}")

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-6">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6 flex items-center shadow-sm">
            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 border border-gray-200">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-6 text-white">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <p class="text-teal-100 text-sm font-medium uppercase tracking-wider">Antrean Klinik PetHouse</p>
                    <h1 class="text-3xl font-bold">#{{ $konsultasi->kode_konsultasi }}</h1>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase shadow-sm
                            @if($konsultasi->status === 'pending') bg-yellow-400 text-yellow-900
                            @elseif($konsultasi->status === 'diterima') bg-blue-400 text-blue-900
                            @else bg-emerald-400 text-emerald-900
                            @endif">
                            {{ $konsultasi->status_label }}
                        </span>
                        <span class="text-teal-50 text-sm">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $konsultasi->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="https://wa.me/{{ $konsultasi->wa_link }}" target="_blank" class="bg-white/20 hover:bg-white/30 text-white p-3 rounded-xl transition backdrop-blur-sm" title="Hubungi Pemilik">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                    <a href="{{ route('dokter.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white p-3 rounded-xl transition backdrop-blur-sm" title="Kembali">
                        <i class="fas fa-th-large text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Pasien</h3>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="mb-3">
                                <p class="text-xs text-gray-500">Nama Pemilik</p>
                                <p class="font-bold text-gray-800">{{ $konsultasi->nama_pemilik }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-xs text-gray-500">Jenis Hewan</p>
                                <p class="font-bold text-gray-800">{{ $konsultasi->jenis_hewan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Topik Konsultasi</p>
                                <p class="font-bold text-teal-700">{{ $konsultasi->topik }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Jadwal Kedatangan</h3>
                        <div class="bg-teal-50 p-4 rounded-2xl border border-teal-100">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-teal-600">Tanggal Janji</p>
                                    <p class="font-bold text-gray-800">{{ $konsultasi->tanggal_janji->format('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-teal-600">Jam Kedatangan</p>
                                    <p class="font-bold text-gray-800">{{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Keluhan & Catatan Awal</h3>
                <div class="bg-white p-5 rounded-2xl border-2 border-gray-100 shadow-inner italic text-gray-700">
                    "{{ $konsultasi->catatan ?? 'Tidak ada catatan awal dari pemilik.' }}"
                </div>
            </div>

            <hr class="mb-10 border-gray-100">

            <div class="bg-gray-50 rounded-3xl p-6 md:p-8 border border-gray-100">
                @if($konsultasi->status === 'pending')
                    <div class="text-center py-4">
                        <div class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-walking text-3xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Pasien Sudah Datang?</h2>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">Silakan konfirmasi jika pemilik hewan sudah berada di klinik untuk memulai konsultasi fisik.</p>
                        
                        <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}">
                            @csrf
                            <input type="hidden" name="aksi" value="terima">
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-10 py-4 rounded-2xl font-bold transition shadow-lg hover:shadow-teal-200">
                                Konfirmasi Kedatangan & Terima
                            </button>
                        </form>
                    </div>

                @elseif($konsultasi->status === 'diterima')
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-file-medical text-teal-600"></i>
                        Input Hasil Pemeriksaan & Diagnosa
                    </h2>
                    
                    <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}">
                        @csrf
                        <input type="hidden" name="aksi" value="selesai">
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Saran Medis, Diagnosa, atau Resep:</label>
                            <textarea name="balasan_dokter" rows="8" required
                                class="w-full border-2 border-gray-200 rounded-2xl p-5 focus:ring-4 focus:ring-teal-100 focus:border-teal-500 transition text-gray-700 shadow-sm"
                                placeholder="Tuliskan hasil pemeriksaan secara lengkap di sini. Catatan ini akan tersimpan permanen di dashboard pemilik hewan."></textarea>
                            <p class="mt-2 text-xs text-gray-500 italic"><i class="fas fa-info-circle mr-1"></i> Data yang Anda input akan menjadi rekam medis digital bagi user.</p>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <button type="submit" onclick="return confirm('Apakah pemeriksaan sudah selesai? Data rekam medis akan dikunci setelah ini.')"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl font-bold transition shadow-lg flex-1">
                                <i class="fas fa-check-circle mr-2"></i> Simpan Rekam Medis & Selesaikan
                            </button>
                        </div>
                    </form>

                @else
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">
                            <i class="fas fa-lock"></i> REKAM MEDIS TERKUNCI
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 uppercase tracking-widest text-xs">Saran Medis & Diagnosa Dokter:</h4>
                        <div class="bg-white p-6 rounded-2xl border-2 border-emerald-100 shadow-sm text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $konsultasi->balasan_dokter }}
                        </div>
                        <div class="mt-4 flex justify-between items-center text-xs text-gray-400 italic">
                            <span>Ditangani oleh: {{ $konsultasi->dokter->username ?? 'Dokter PetHouse' }}</span>
                            <span>Selesai pada: {{ $konsultasi->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection