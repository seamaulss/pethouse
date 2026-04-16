@extends('layouts.frontend')

@section('title', 'Layanan - LARAPetHouse')

@section('content')
<!-- Hero Section -->
<section class="py-12 md:py-20 bg-teal-50 relative overflow-hidden">
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-64 h-64 bg-pink-100 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-64 h-64 bg-teal-100 rounded-full blur-3xl opacity-50"></div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4" data-aos="fade-down">
            Layanan Kami
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto" data-aos="fade-up">
            Perawatan terbaik penuh kasih sayang untuk hewan kesayangan Anda 🐶🐱
        </p>
        <div class="mt-6 flex justify-center" data-aos="zoom-in">
            <div class="h-1.5 w-16 bg-pink-500 rounded-full"></div>
        </div>
    </div>
</section>

<!-- Daftar Layanan -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800" data-aos="fade-up">
                Pilih Layanan Terbaik untuk Teman Berbulu Anda
            </h2>
            <p class="mt-4 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Setiap layanan dirancang dengan cinta dan profesionalisme
            </p>
        </div>

        @if($layanans->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
            @foreach($layanans as $index => $layanan)
            <div class="card-modern bg-gradient-to-br from-pink-50 to-teal-50"
                data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">

                @if($layanan->gambar && file_exists(public_path('storage/layanan/' . $layanan->gambar)))
                <img src="{{ asset('storage/layanan/' . $layanan->gambar) }}"
                    alt="{{ $layanan->nama_layanan }}" {{-- Perbaikan: nama -> nama_layanan --}}
                    class="w-full h-64 sm:h-72 object-cover"
                    loading="lazy" decoding="async">
                @else
                <div class="h-64 sm:h-72 bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-dashed border-gray-300 rounded-t-2xl flex items-center justify-center">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
                @endif

                <div class="p-6 sm:p-8 lg:p-10 text-center">
                    @php
                    $icon = 'fa-paw';
                    // Perbaikan: nama -> nama_layanan untuk logika icon
                    $nama_layanan = strtolower($layanan->nama_layanan);
                    if (strpos($nama_layanan, 'grooming') !== false) $icon = 'fa-scissors';
                    elseif (strpos($nama_layanan, 'penitipan') !== false || strpos($nama_layanan, 'hotel') !== false) $icon = 'fa-home';
                    elseif (strpos($nama_layanan, 'dokter') !== false || strpos($nama_layanan, 'konsultasi') !== false) $icon = 'fa-stethoscope';
                    elseif (strpos($nama_layanan, 'play') !== false || strpos($nama_layanan, 'daycare') !== false) $icon = 'fa-football';
                    elseif (strpos($nama_layanan, 'spa') !== false) $icon = 'fa-bath';
                    elseif (strpos($nama_layanan, 'training') !== false || strpos($nama_layanan, 'pelatihan') !== false) $icon = 'fa-graduation-cap';
                    @endphp

                    <i class="fas {{ $icon }} text-5xl sm:text-6xl text-pink-500 mb-6"></i>

                    <h3 class="font-bold text-2xl sm:text-3xl mb-4 text-teal-600">
                        {{ $layanan->nama_layanan }} {{-- Perbaikan: nama -> nama_layanan --}}
                    </h3>

                    <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-4">
                        {{ $layanan->deskripsi }}
                    </p>

                    {{-- Menampilkan harga jika tersedia --}}
                    @if($layanan->harga)
                    <div class="mb-6 pt-4 border-t border-gray-200">
                        <p class="text-2xl font-bold text-teal-700">
                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                            @if($layanan->satuan)
                            <span class="text-base font-normal text-gray-500">/{{ $layanan->satuan }}</span>
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20" data-aos="fade-up">
            <div class="inline-block p-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl mb-6">
                <i class="fas fa-paw text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-700 mb-3">Belum ada layanan tersedia saat ini 🐾</h3>
            <p class="text-gray-600 text-lg max-w-md mx-auto">
                Layanan sedang dalam persiapan. Hubungi kami untuk informasi lebih lanjut.
            </p>
        </div>
        @endif
    </div>
</section>

<!-- Floating WhatsApp -->
<a href="https://wa.me/6285942173668?text=Halo%20LARAPetHouse,%20saya%20ingin%20informasi%20layanan"
    class="whatsapp-float" target="_blank" rel="noopener" aria-label="Hubungi via WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>
@endsection

@push('styles')
<style>
    :root {
        --primary: #0d9488;
        /* teal utama */
        --secondary: #f43f5e;
        /* pink cute */
        --accent: #fbbf24;
        /* amber ceria */
    }

    .card-modern {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #2dd4bf);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(13, 148, 136, 0.4);
    }

    .whatsapp-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #25d366, #128C7E);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .whatsapp-float:hover {
        transform: scale(1.15) rotate(5deg);
    }

    @media (max-width: 640px) {
        .whatsapp-float {
            width: 55px;
            height: 55px;
            bottom: 15px;
            right: 15px;
        }

        .whatsapp-float i {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Inisialisasi AOS untuk animasi
    AOS.init({
        duration: 800,
        once: true,
        offset: 80,
        easing: 'ease-out-quart'
    });
</script>
@endpush