
@extends('layouts.frontend')

@section('title', 'Galeri - PetHouse')

@push('styles')
<style>
    :root {
        --primary: #0d9488;   /* teal utama */
        --secondary: #f43f5e; /* pink cute */
        --accent: #fbbf24;    /* amber ceria */
    }

    .card-modern {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: white;
        position: relative;
    }
    .card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    /* Overlay untuk hover (desktop & mobile saat tap) */
    .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent 70%);
        display: flex;
        align-items: end;
        padding: 1.5rem;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .card-modern:hover .overlay,
    .card-modern:focus-within .overlay { /* support tap di mobile */
        opacity: 1;
    }

    /* Caption bawah - SELALU terlihat di mobile */
    .mobile-caption {
        display: block;
    }
    @media (min-width: 768px) {
        .mobile-caption {
            display: none; /* sembunyi di desktop karena sudah ada overlay */
        }
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
        box-shadow: 0 8px 25px rgba(37,211,102,0.5);
        z-index: 1000;
        transition: all 0.3s ease;
    }
    .whatsapp-float:hover {
        transform: scale(1.15);
    }

    @media (max-width: 640px) {
        .whatsapp-float { width: 55px; height: 55px; bottom: 15px; right: 15px; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section Galeri -->
<section class="relative py-16 sm:py-20 md:py-24 bg-gradient-to-br from-teal-50 via-pink-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-gray-800 mb-4 sm:mb-6" data-aos="fade-up">
            Galeri PetHouse
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl text-gray-700 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Momen bahagia hewan kesayangan Anda bersama kami 🐾❤️
        </p>
    </div>
</section>

<!-- Grid Galeri -->
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($galeris->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($galeris as $g)
                    <div class="card-modern group" data-aos="fade-up" tabindex="0">
                        
                        <!-- PERUBAHAN DI SINI: $g->gambar MENJADI $g->foto -->
                        @if($g->foto)
                            <div class="relative overflow-hidden">
                                <!-- Gunakan Storage::url karena path lengkap sudah ada di database -->
                                <img src="{{ Storage::url($g->foto) }}" 
                                     alt="{{ $g->keterangan ?? 'Foto hewan di PetHouse' }}"
                                     class="w-full h-48 sm:h-64 md:h-72 object-cover transition transform group-hover:scale-110 duration-700"
                                     loading="lazy" decoding="async"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                
                                <!-- Fallback jika gambar error -->
                                <div class="w-full h-48 sm:h-64 md:h-72 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center hidden">
                                    <div class="text-center">
                                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                        <p class="text-xs text-gray-500">{{ basename($g->foto) }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-48 sm:h-64 md:h-72 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                            </div>
                        @endif

                        <!-- Overlay Hover -->
                        @if(!empty($g->judul) || !empty($g->keterangan))
                            <div class="overlay">
                                <div class="w-full">
                                    @if(!empty($g->judul))
                                        <h4 class="text-white text-lg sm:text-xl md:text-2xl font-bold mb-2">
                                            {{ $g->judul }}
                                        </h4>
                                    @endif
                                    @if(!empty($g->keterangan))
                                        <p class="text-white text-sm sm:text-base md:text-lg">
                                            {{ $g->keterangan }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Caption Bawah -->
                        @if(!empty($g->judul) || !empty($g->keterangan))
                            <div class="mobile-caption p-3 sm:p-4 text-center bg-gray-50">
                                @if(!empty($g->judul))
                                    <h4 class="text-gray-800 font-bold text-sm mb-1">
                                        {{ $g->judul }}
                                    </h4>
                                @endif
                                @if(!empty($g->keterangan))
                                    <p class="text-gray-700 text-xs">
                                        {{ Str::limit($g->keterangan, 50) }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            
        @endif
    </div>
</section>

<!-- Floating WhatsApp -->
<a href="https://wa.me/6285942173668?text=Halo%20PetHouse,%20saya%20ingin%20informasi%20layanan"
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Hubungi via WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>
@endsection

@push('scripts')
<script>
    // Inisialisasi AOS untuk animasi
    AOS.init({
        duration: 800,
        once: true,
        offset: 80,
        easing: 'ease-out-quart'
    });

    // Untuk mobile: saat tap card, toggle overlay
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-modern');
        
        cards.forEach(card => {
            card.addEventListener('touchstart', function() {
                this.classList.add('touched');
            }, {passive: true});
            
            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touched');
                }, 300);
            }, {passive: true});
        });
    });
</script>
@endpush