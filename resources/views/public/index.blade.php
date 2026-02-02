@extends('layouts.frontend')

@section('title', 'Home')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-slide {
        position: relative;
        height: 600px;
        overflow: hidden;
    }

    .hero-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
    }

    .no-image {
        background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
    }

    .swiper-pagination-bullet {
        background: white;
        opacity: 0.5;
        width: 12px;
        height: 12px;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #0d9488;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: white;
        background: rgba(0, 0, 0, 0.3);
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
    }
</style>
@endpush

@section('content')
<!-- Hero Slider Section -->
<section class="relative">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($slides as $slide)
            <div class="swiper-slide">
                <div class="hero-slide ">
                    @if($slide->gambar && file_exists(public_path('storage/hero/' . $slide->gambar)))
                    <img src="{{ asset('storage/hero/' . $slide->gambar) }}"
                        alt="{{ $slide->judul }}"
                        onerror="this.style.display='none'; this.parentElement.classList.add('no-image');">
                    @else
                    <div class="no-image w-full h-full"></div>
                    @endif
                    <div class="hero-content">
                        <div class="container mx-auto px-4 text-white">
                            <div class="max-w-2xl mx-auto px-8 md:px-12 lg:px-16 text-center">
                                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                                    {{ $slide->judul }}
                                </h1>
                                @if($slide->subjudul)
                                <p class="text-xl mb-8 opacity-90">
                                    {{ $slide->subjudul }}
                                </p>
                                @endif
                                @if($slide->tombol_text)
                                <a href="{{ $slide->tombol_link ?? '#' }}"
                                    class="inline-block bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300 transform hover:scale-105">
                                    {{ $slide->tombol_text }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<!-- Testimoni Section -->
@if($testimonis->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Testimoni dari pelanggan yang puas dengan layanan PetHouse
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonis as $index => $testimoni)
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 hover:shadow-xl transition duration-300"
                data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <!-- Bagian Atas: Gambar Hewan, Nama Pemilik, dan Nama Hewan -->
                <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                    <!-- Gambar Hewan -->
                    <div class="flex-shrink-0 mr-4">
                        @if($testimoni->foto_hewan && file_exists(public_path('storage/testimoni/' . $testimoni->foto_hewan)))
                        <img src="{{ asset('storage/testimoni/' . $testimoni->foto_hewan) }}"
                            alt="{{ $testimoni->nama_hewan ?? 'Hewan' }}"
                            class="w-16 h-16 rounded-full object-cover border-2 border-teal-100">
                        @else
                        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center border-2 border-teal-200">
                            @if($testimoni->jenis_hewan == 'Kucing')
                            <i class="fas fa-cat text-teal-600 text-xl"></i>
                            @elseif($testimoni->jenis_hewan == 'Anjing')
                            <i class="fas fa-dog text-teal-600 text-xl"></i>
                            @elseif($testimoni->jenis_hewan == 'Burung')
                            <i class="fas fa-dove text-teal-600 text-xl"></i>
                            @elseif($testimoni->jenis_hewan == 'Kelinci')
                            <i class="fas fa-paw text-teal-600 text-xl"></i>
                            @else
                            <i class="fas fa-paw text-teal-600 text-xl"></i>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Nama Pemilik dan Nama Hewan -->
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">{{ $testimoni->nama_pemilik }}</h4>
                        <p class="text-gray-600 text-sm">
                            {{ $testimoni->nama_hewan ?? 'Hewan Peliharaan' }}
                            @if($testimoni->jenis_hewan)
                            <span class="text-teal-600 font-medium">({{ $testimoni->jenis_hewan }})</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Testimoni Text -->
                <div class="mb-6">
                    <p class="text-gray-700 italic text-lg leading-relaxed">
                        "{{ Str::limit($testimoni->isi_testimoni, 120) }}"
                    </p>
                </div>

                <!-- Rating dan Tanggal -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=($testimoni->rating ?? 5))
                            <i class="fas fa-star text-yellow-400 mr-1 text-lg"></i>
                            @else
                            <i class="far fa-star text-yellow-400 mr-1 text-lg"></i>
                            @endif
                            @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ $testimoni->created_at->format('d M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@else
<!-- Jika belum ada testimoni, tampilkan placeholder -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Belum ada testimoni yang tersedia
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Placeholder testimoni 1 -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-400 text-lg">Nama Pelanggan</h4>
                        <p class="text-gray-400 text-sm">Nama Hewan (Jenis)</p>
                    </div>
                </div>
                <p class="text-gray-400 italic mb-6">"Testimoni dari pelanggan akan muncul di sini"</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex">
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                    </div>
                    <span class="text-sm text-gray-300">Tanggal</span>
                </div>
            </div>

            <!-- Placeholder testimoni 2 -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-400 text-lg">Nama Pelanggan</h4>
                        <p class="text-gray-400 text-sm">Nama Hewan (Jenis)</p>
                    </div>
                </div>
                <p class="text-gray-400 italic mb-6">"Testimoni dari pelanggan akan muncul di sini"</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex">
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                    </div>
                    <span class="text-sm text-gray-300">Tanggal</span>
                </div>
            </div>

            <!-- Placeholder testimoni 3 -->
            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-400 text-lg">Nama Pelanggan</h4>
                        <p class="text-gray-400 text-sm">Nama Hewan (Jenis)</p>
                    </div>
                </div>
                <p class="text-gray-400 italic mb-6">"Testimoni dari pelanggan akan muncul di sini"</p>
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex">
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                        <i class="far fa-star text-gray-300 mr-1 text-lg"></i>
                    </div>
                    <span class="text-sm text-gray-300">Tanggal</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Tentang Kami Section -->
@if($tentang)
<section class="py-16 bg-white" id="tentang">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Gambar atau Ikon -->
            <div class="relative" data-aos="fade-right">
                @if($tentang->gambar && file_exists(public_path('storage/tentang/' . $tentang->gambar)))
                <img src="{{ asset('storage/tentang/' . $tentang->gambar) }}"
                    alt="Tentang PetHouse"
                    class="rounded-2xl shadow-2xl w-full h-auto">
                @else
                <div class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl shadow-2xl p-12 text-center">
                    <i class="fas fa-paw text-white text-8xl mb-6"></i>
                    <h3 class="text-3xl font-bold text-white">PetHouse</h3>
                    <p class="text-teal-100 mt-4">Rumah kedua untuk hewan kesayangan Anda</p>
                </div>
                @endif
            </div>

            <!-- Konten -->
            <div data-aos="fade-left">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                    Tentang <span class="text-teal-600">PetHouse</span>
                </h2>

                <div class="prose prose-lg max-w-none text-gray-700 mb-8">
                    @if($tentang->isi)
                    {!! nl2br(e($tentang->isi)) !!}
                    @else
                    <p class="mb-4">PetHouse adalah tempat penitipan, grooming, dan perawatan hewan kesayangan yang mengutamakan kenyamanan dan kesehatan hewan peliharaan Anda.</p>
                    <p>Kami memiliki staf yang berpengalaman dan fasilitas lengkap untuk memastikan hewan kesayangan Anda mendapatkan perawatan terbaik.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Layanan Section -->
@if($layanans->count() > 0)
<section class="py-16 sm:py-20 bg-gray-50" id="layanan">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="text-center mb-12 sm:mb-16" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
                Layanan Kami
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base md:text-lg">
                Berbagai layanan terbaik untuk hewan kesayangan Anda dengan penuh kasih sayang
            </p>
        </div>

        {{-- GRID DIPERBESAR --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
            @foreach($layanans->take(6) as $index => $layanan)
            <div
                class="bg-white rounded-2xl shadow-lg overflow-hidden
                       transition-all duration-300 ease-out
                       hover:-translate-y-2 hover:scale-[1.03] hover:shadow-2xl"
                data-aos="fade-up"
                data-aos-delay="{{ ($index % 3) * 100 }}">

                {{-- GAMBAR DIPERBESAR --}}
                @php
                    $gambarPath = public_path('storage/layanan/' . $layanan->gambar);
                    $gambarUrl  = asset('storage/layanan/' . $layanan->gambar);
                @endphp

                @if($layanan->gambar && file_exists($gambarPath))
                <div class="h-64 sm:h-72 overflow-hidden">
                    <img src="{{ $gambarUrl }}"
                        alt="{{ $layanan->nama_layanan }}"
                        class="w-full h-full object-cover transition-transform duration-700 ease-out hover:scale-125"
                        loading="lazy">
                </div>
                @else
                <div class="h-64 sm:h-72 bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center">
                    <i class="fas fa-paw text-white text-6xl"></i>
                </div>
                @endif

                {{-- KONTEN DIPERBESAR --}}
                <div class="p-6 sm:p-8 md:p-10 flex flex-col flex-grow text-center">
                    <div class="mb-5">
                        <div class="inline-block bg-teal-100 text-teal-800 text-xs font-semibold px-4 py-1 rounded-full mb-4">
                            Layanan
                        </div>

                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                            {{ $layanan->nama_layanan }}
                        </h3>
                    </div>

                    <div class="text-gray-600 flex-grow">
                        <p class="leading-relaxed text-sm sm:text-base md:text-lg">
                            {{ $layanan->deskripsi }}
                        </p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
@endif


<!-- Galeri Preview -->
@if($galeris->count() > 0)
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h2 class="text-3xl sm:text-4xl font-bold text-center mb-12 sm:mb-16 text-gray-800"
            data-aos="fade-up">
            Galeri PetHouse
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @foreach($galeris->take(12) as $index => $galeri)
            <div class="relative overflow-hidden rounded-2xl group shadow-md"
                 data-aos="fade-up"
                 data-aos-delay="{{ $index * 50 }}">

                {{-- Gambar --}}
                @if($galeri->foto && Storage::disk('public')->exists($galeri->foto))
                <img src="{{ Storage::url($galeri->foto) }}"
                     alt="{{ $galeri->keterangan ?? 'Galeri PetHouse' }}"
                     class="w-full h-48 sm:h-64 md:h-72 object-cover
                            transition-transform duration-700
                            group-hover:scale-110"
                     loading="lazy">
                @else
                <div class="w-full h-48 sm:h-64 md:h-72 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                </div>
                @endif

                {{-- Overlay hover --}}
                @if(!empty($galeri->keterangan))
                <div class="absolute inset-0
                            bg-gradient-to-t from-black/70 to-transparent
                            opacity-0 group-hover:opacity-100
                            transition duration-300
                            flex items-end p-4 sm:p-6">
                    <p class="text-white text-sm sm:text-base md:text-lg font-semibold">
                        {{ $galeri->keterangan }}
                    </p>
                </div>
                @endif

            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Swiper
        const swiper = new Swiper('.mySwiper', {
            loop: true,
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
        });

        // Pause autoplay on hover
        const swiperContainer = document.querySelector('.mySwiper');
        swiperContainer.addEventListener('mouseenter', function() {
            swiper.autoplay.stop();
        });
        swiperContainer.addEventListener('mouseleave', function() {
            swiper.autoplay.start();
        });

        // Inisialisasi AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });
    });
</script>
@endpush