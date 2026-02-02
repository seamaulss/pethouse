@extends('layouts.dokter')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 text-center sm:text-left">
            <i class="fas fa-stethoscope mr-4 text-teal-600"></i>
            Dashboard Dokter Hewan
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-10 text-center sm:text-left">
            Kelola konsultasi kesehatan hewan dengan penuh perhatian dan profesionalisme.
        </p>
    </div>

    <!-- Konsultasi Menunggu -->
    <section class="mb-16" data-aos="fade-up">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-hourglass-half mr-3 text-yellow-500"></i>
                Konsultasi Menunggu Konfirmasi
            </h2>
            <span class="status-badge bg-yellow-100 text-yellow-800">
                {{ $pending->count() }} Permintaan
            </span>
        </div>

        @if($pending->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($pending as $konsultasi)
                    @php
                        $wa_clean = preg_replace('/[^\d]/', '', $konsultasi->no_wa);
                        if (substr($wa_clean, 0, 1) === '0') $wa_clean = '62' . substr($wa_clean, 1);
                        elseif (substr($wa_clean, 0, 2) !== '62') $wa_clean = '62' . $wa_clean;
                    @endphp
                    <div class="card-konsultasi" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="p-6 sm:p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                                        {{ $konsultasi->nama_pemilik }}
                                    </h3>
                                    <p class="text-gray-600">
                                        <i class="fas fa-paw mr-2 text-pink-500"></i>
                                        {{ $konsultasi->jenis_hewan }}
                                    </p>
                                    <p class="text-gray-700 mt-2 italic">
                                        "{{ $konsultasi->topik }}"
                                    </p>
                                </div>
                                <span class="status-badge bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 mb-6">
                                <p><strong>WA:</strong> {{ $konsultasi->no_wa }}</p>
                                <p><strong>Kode:</strong> <span class="font-mono text-teal-600">{{ $konsultasi->kode_konsultasi }}</span></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="terima">
                                    <button type="submit" 
                                            onclick="return confirm('Yakin menerima konsultasi ini?')"
                                            class="btn-terima text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center w-full">
                                        <i class="fas fa-check mr-2"></i>
                                        Terima Konsultasi
                                    </button>
                                </form>
                                <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20Dokter,%20saya%20{{ urlencode($konsultasi->nama_pemilik) }}%20ingin%20konsultasi%20tentang%20{{ urlencode($konsultasi->jenis_hewan) }}"
                                    target="_blank"
                                    class="btn-wa text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    Chat via WA
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-konsultasi p-16 text-center">
                <div class="text-7xl mb-8 text-gray-300">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="text-2xl text-gray-600">
                    Tidak ada konsultasi yang menunggu konfirmasi.
                </p>
            </div>
        @endif
    </section>

    <!-- Konsultasi Aktif -->
    <section data-aos="fade-up">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-calendar-check mr-3 text-blue-600"></i>
                Konsultasi Aktif Hari Ini
            </h2>
            <span class="status-badge bg-blue-100 text-blue-800">
                {{ $diterima->count() }} Jadwal
            </span>
        </div>

        @if($diterima->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($diterima as $konsultasi)
                    @php
                        $wa_clean = preg_replace('/[^\d]/', '', $konsultasi->no_wa);
                        if (substr($wa_clean, 0, 1) === '0') $wa_clean = '62' . substr($wa_clean, 1);
                        elseif (substr($wa_clean, 0, 2) !== '62') $wa_clean = '62' . $wa_clean;
                    @endphp
                    <div class="card-konsultasi" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="p-6 sm:p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                                        {{ $konsultasi->nama_pemilik }}
                                    </h3>
                                    <p class="text-gray-600">
                                        <i class="fas fa-paw mr-2 text-pink-500"></i>
                                        {{ $konsultasi->jenis_hewan }}
                                    </p>
                                    <p class="text-gray-700 mt-2 italic">
                                        "{{ $konsultasi->topik }}"
                                    </p>
                                </div>
                                <span class="status-badge bg-blue-100 text-blue-800">
                                    Aktif
                                </span>
                            </div>

                            <div class="space-y-2 text-gray-700 mb-6">
                                <p class="font-semibold text-teal-600">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($konsultasi->tanggal_janji)->translatedFormat('d F Y') }}
                                    <span class="text-pink-600">pukul {{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</span>
                                </p>
                                <p><strong>WA:</strong> {{ $konsultasi->no_wa }}</p>
                                <p><strong>Kode:</strong> <span class="font-mono text-teal-600">{{ $konsultasi->kode_konsultasi }}</span></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="aksi" value="selesai">
                                    <button type="submit" 
                                            onclick="return confirm('Tandai konsultasi ini sebagai selesai?')"
                                            class="btn-selesai text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center w-full">
                                        <i class="fas fa-check-double mr-2"></i>
                                        Tandai Selesai
                                    </button>
                                </form>
                                <a href="{{ route('dokter.konsultasi.show', $konsultasi->id) }}"
                                    class="btn-wa text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-konsultasi p-16 text-center">
                <div class="text-7xl mb-8 text-gray-300">
                    <i class="fas fa-calendar"></i>
                </div>
                <p class="text-2xl text-gray-600">
                    Tidak ada jadwal konsultasi aktif hari ini.
                </p>
                <p class="text-gray-500 mt-4">
                    Nikmati hari ini atau siapkan diri untuk konsultasi berikutnya!
                </p>
            </div>
        @endif
    </section>
</div>
@endsection