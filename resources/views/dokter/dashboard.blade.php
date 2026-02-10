@extends('layouts.dokter')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-10" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-id-badge mr-3 text-teal-600"></i>
                    Panel Antrean Dokter
                </h1>
                <p class="text-gray-600 mt-2 italic">Pantau kunjungan fisik dan catat rekam medis anabul secara digital.</p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tanggal Hari Ini</p>
                    <p class="font-bold text-gray-800">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
                <i class="fas fa-calendar-check text-teal-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <section class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-bell mr-3 text-amber-500"></i>
                Menunggu Konfirmasi Datang
            </h2>
            <span class="px-4 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold shadow-sm">
                {{ $pending->count() }} Antrean Baru
            </span>
        </div>

        @if($pending->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pending as $konsul)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300" data-aos="zoom-in">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-500">#{{ $konsul->kode_konsultasi }}</span>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg italic">Booking Online</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $konsul->nama_pemilik }}</h3>
                        <p class="text-sm text-teal-600 font-semibold mb-3">🐾 {{ $konsul->jenis_hewan }} - <span class="text-gray-600 font-normal">{{ $konsul->topik }}</span></p>
                        
                        <div class="bg-gray-50 p-3 rounded-xl mb-6 text-xs space-y-2">
                            <p><i class="far fa-calendar-alt mr-2"></i>{{ $konsul->tanggal_janji->translatedFormat('d M Y') }}</p>
                            <p><i class="far fa-clock mr-2 text-pink-500"></i>Jam {{ date('H:i', strtotime($konsul->jam_janji)) }} WIB</p>
                        </div>

                        <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsul->id) }}">
                            @csrf
                            <input type="hidden" name="aksi" value="terima">
                            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i> Konfirmasi Kedatangan
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
                <p class="text-gray-400 italic">Belum ada antrean baru yang perlu dikonfirmasi.</p>
            </div>
        @endif
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-stethoscope mr-3 text-blue-600"></i>
                Pasien di Klinik (Sedang Diperiksa)
            </h2>
            <span class="px-4 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold shadow-sm">
                {{ $diterima->count() }} Sedang Proses
            </span>
        </div>

        @if($diterima->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($diterima as $konsul)
                <div class="bg-white rounded-2xl shadow-md border-2 border-blue-100 overflow-hidden hover:border-blue-300 transition duration-300" data-aos="zoom-in">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-mono bg-blue-50 px-2 py-1 rounded text-blue-500 font-bold">#{{ $konsul->kode_konsultasi }}</span>
                            <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-ping"></span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $konsul->nama_pemilik }}</h3>
                        <p class="text-sm text-pink-600 mb-4 font-medium"><i class="fas fa-paw mr-1"></i> {{ $konsul->jenis_hewan }}</p>

                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <p class="text-xs text-gray-400 mb-3">Silakan periksa anabul, lalu klik tombol di bawah untuk mengisi rekam medis digital.</p>
                            <a href="{{ route('dokter.konsultasi.show', $konsul->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center">
                                <i class="fas fa-file-signature mr-2"></i> Input Rekam Medis
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
                <p class="text-gray-400 italic">Tidak ada pasien yang sedang diperiksa saat ini.</p>
            </div>
        @endif
    </section>
</div>
@endsection