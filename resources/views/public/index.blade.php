@extends('layouts.frontend')

@section('title', 'Home')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Responsive Hero Height */
    .hero-slide {
        position: relative;
        height: 450px; /* Tinggi untuk Mobile */
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .hero-slide {
            height: 600px; /* Tinggi untuk Desktop */
        }
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
        width: 10px;
        height: 10px;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #0d9488;
        width: 25px; /* Efek memanjang saat aktif */
        border-radius: 5px;
    }

    /* Sembunyikan Navigasi Panah di Mobile agar tidak ganggu teks */
    @media (max-width: 768px) {
        .swiper-button-next,
        .swiper-button-prev {
            display: none !important;
        }
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: white;
        background: rgba(0, 0, 0, 0.3);
        width: 45px;
        height: 45px;
        border-radius: 50%;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
    }
</style>
@endpush

@section('content')
<section class="relative">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($slides as $slide)
            <div class="swiper-slide">
                <div class="hero-slide">
                    @if($slide->gambar && file_exists(public_path('storage/hero/' . $slide->gambar)))
                    <img src="{{ asset('storage/hero/' . $slide->gambar) }}" 
                         alt="{{ $slide->judul }}"
                         loading="eager"> 
                    @else
                    <div class="no-image w-full h-full"></div>
                    @endif
                    
                    <div class="hero-content">
                        <div class="container mx-auto px-6">
                            <div class="max-w-3xl mx-auto text-center text-white">
                                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                                    {{ $slide->judul }}
                                </h1>
                                @if($slide->subjudul)
                                <p class="text-base md:text-xl mb-8 opacity-90 line-clamp-3 md:line-clamp-none">
                                    {{ $slide->subjudul }}
                                </p>
                                @endif
                                @if($slide->tombol_text)
                                <a href="{{ $slide->tombol_link ?? '#' }}"
                                    class="inline-block bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 md:px-8 md:py-4 rounded-lg font-semibold text-base md:text-lg transition duration-300 transform hover:scale-105 shadow-lg" target="_blank">
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

        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

@if($testimonis->count() > 0)
<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 md:mb-16" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-3">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto">
                Testimoni dari pelanggan yang puas dengan layanan PetHouse
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($testimonis as $index => $testimoni)
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100 flex flex-col justify-between"
                data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 mr-3">
                            @if($testimoni->foto_hewan && file_exists(public_path('storage/testimoni/' . $testimoni->foto_hewan)))
                            <img src="{{ asset('storage/testimoni/' . $testimoni->foto_hewan) }}"
                                alt="{{ $testimoni->nama_hewan }}"
                                class="w-14 h-14 rounded-full object-cover border-2 border-teal-100" loading="lazy">
                            @else
                            <div class="w-14 h-14 bg-teal-50 rounded-full flex items-center justify-center border border-teal-100">
                                <i class="fas fa-paw text-teal-600"></i>
                            </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $testimoni->nama_pemilik }}</h4>
                            <p class="text-teal-600 text-xs font-medium">{{ $testimoni->nama_hewan }} ({{ $testimoni->jenis_hewan }})</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic text-sm md:text-base leading-relaxed mb-4">
                        "{{ Str::limit($testimoni->isi_testimoni, 150) }}"
                    </p>
                </div>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= ($testimoni->rating ?? 5) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">{{ $testimoni->created_at->format('d M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12 md:py-20 bg-gray-50" id="layanan">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-4">Layanan Kami</h2>
            <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-10">
            @foreach($layanans as $index => $layanan)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up">
                <div class="h-52 overflow-hidden">
                    <img src="{{ asset('storage/layanan/' . $layanan->gambar) }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         loading="lazy">
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $layanan->nama_layanan }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $layanan->deskripsi }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-4xl font-bold text-center mb-10 text-gray-800">Galeri PetHouse</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            @foreach($galeris->take(8) as $galeri)
            <div class="aspect-square rounded-xl overflow-hidden shadow-sm group relative">
                <img src="{{ Storage::url($galeri->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-duration-500" loading="lazy">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 text-center">
                    <p class="text-white text-xs md:text-sm font-medium">{{ $galeri->keterangan }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.mySwiper', {
            loop: true,
            speed: 1000,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            effect: 'fade',
            fadeEffect: { crossFade: true },
        });

        AOS.init({ duration: 800, once: true });
    });
</script>
@endpush