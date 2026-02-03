@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<!-- Konten Utama -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 text-center sm:text-left">
            <i class="fas fa-dog mr-3 text-teal-600"></i>
            Hewan yang Sedang Dititipkan
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-10 text-center sm:text-left">
            Pantau dan update kondisi harian hewan kesayangan pelanggan.
        </p>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        @php $delay = 100; @endphp
        @foreach($bookings as $booking)
        <div class="card-hewan" data-aos="fade-up" data-aos-delay="{{ $delay }}">
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-teal-600 mb-2">
                            {{ $booking->nama_hewan }}
                        </h3>
                        <p class="text-gray-600 text-lg">
                            <i class="fas fa-paw mr-2 text-pink-500"></i>
                            {{ $booking->jenis_hewan }}
                        </p>
                    </div>
                    <span class="bg-teal-100 text-teal-800 px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $booking->kode_booking }}
                    </span>
                </div>

                <div class="mb-6">
                    <p class="text-gray-700">
                        <i class="fas fa-user mr-2 text-amber-500"></i>
                        <strong>Pemilik:</strong> {{ $booking->nama_pemilik }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                    // Normalisasi nomor WA
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (substr($wa_clean, 0, 1) === '0') {
                    $wa_clean = '62' . substr($wa_clean, 1);
                    } elseif (substr($wa_clean, 0, 2) !== '62') {
                    $wa_clean = '62' . $wa_clean;
                    }
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20Bapak/Ibu%20{{ urlencode($booking->nama_pemilik) }},%20ini%20update%20dari%20PetHouse%20untuk%20{{ urlencode($booking->nama_hewan) }}"
                        target="_blank"
                        class="btn-wa text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-3 text-xl"></i>
                        Hubungi Pemilik
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="btn-update text-white font-bold py-3 rounded-xl shadow-lg text-center flex items-center justify-center">
                        <i class="fas fa-edit mr-3 text-xl"></i>
                        Input Log Kegiatan
                    </a>
                </div>
            </div>
        </div>
        @php $delay += 100; @endphp
        @endforeach
    </div>
    @else
    <div class="card-hewan p-16 text-center" data-aos="fade-up">
        <div class="text-7xl mb-8 text-gray-300">🐾</div>
        <p class="text-2xl text-gray-600 mb-4">
            Tidak ada hewan yang sedang dititipkan saat ini.
        </p>
        <p class="text-gray-500">
            Tunggu booking baru dari pelanggan atau pastikan admin telah mengubah status menjadi <strong>"sedang dititipkan"</strong>.
        </p>
    </div>
    @endif
</div>

<!-- Floating WhatsApp -->
<a href="https://wa.me/6285942173668?text=Halo%20admin%20PetHouse,%20saya%20petugas%20dan%20butuh%20bantuan"
    class="whatsapp-float text-4xl sm:text-5xl" target="_blank" rel="noopener" aria-label="Hubungi Admin via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
@endsection