@extends('layouts.frontend')

@section('title', 'Galeri - PetHouse')

@push('styles')
<style>
    :root {
        --primary: #0d9488;
        --secondary: #f43f5e;
    }

    /* Grid Layout */
    .card-modern {
        border-radius: 1rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%; /* Memastikan semua card sama tinggi dalam satu baris */
        outline: none;
    }

    /* Efek Gambar */
    .img-container {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1; /* Gambar kotak sempurna */
        overflow: hidden;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .card-modern:hover .img-container img {
        transform: scale(1.1);
    }

    /* Caption Mobile (Solusi teks terpotong) */
    .mobile-caption {
        padding: 0.75rem;
        background: white;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .mobile-caption h4 {
        color: #1f2937;
        font-weight: 700;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        line-height: 1.25;
    }

    .description-text {
        font-size: 0.75rem;
        color: #4b5563;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Default tampil 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    /* Saat di-tap/klik, tampilkan semua teks */
    .card-modern.is-active .description-text {
        -webkit-line-clamp: unset;
        overflow: visible;
    }

    .tap-hint {
        font-size: 10px;
        color: var(--primary);
        font-weight: 600;
        margin-top: 0.5rem;
        display: block;
    }

    /* Overlay Desktop (Hanya muncul di layar besar) */
    @media (min-width: 1024px) {
        .overlay-desktop {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 70%);
            display: flex;
            align-items: flex-end;
            padding: 1.25rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: white;
        }
        .card-modern:hover .overlay-desktop {
            opacity: 1;
        }
        .mobile-caption {
            display: none; /* Sembunyikan caption bawah di desktop */
        }
        .tap-hint {
            display: none;
        }
    }

    /* WhatsApp Floating Button */
    .whatsapp-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        background: #25d366;
        color: white !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 15px rgba(37,211,102,0.4);
        z-index: 999;
        transition: transform 0.3s ease;
    }
    .whatsapp-float:active { transform: scale(0.9); }
</style>
@endpush

@section('content')
<section class="py-12 bg-teal-50">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-800 mb-2" data-aos="fade-down">Galeri Kita</h1>
        <p class="text-gray-600" data-aos="fade-up">Intip keseruan teman bulu favoritmu di PetHouse! 🐾</p>
    </div>
</section>

<section class="py-10 bg-white">
    <div class="container mx-auto px-4">
        @if($galeris->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                @foreach($galeris as $g)
                    <div class="card-modern group" 
                         data-aos="fade-up" 
                         onclick="this.classList.toggle('is-active')">
                        
                        <div class="img-container">
                            @if($g->foto)
                                <img src="{{ Storage::url($g->foto) }}" 
                                     alt="{{ $g->judul }}" 
                                     loading="lazy">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-paw text-gray-300 text-3xl"></i>
                                </div>
                            @endif

                            <div class="overlay-desktop">
                                <div>
                                    <h4 class="font-bold text-lg">{{ $g->judul }}</h4>
                                    <p class="text-sm opacity-90">{{ $g->keterangan }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mobile-caption">
                            <h4>{{ $g->judul }}</h4>
                            <p class="description-text">
                                {{ $g->keterangan }}
                            </p>
                            @if(strlen($g->keterangan) > 40)
                                <span class="tap-hint">Tap untuk baca selengkapnya...</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 text-gray-400">
                <i class="fas fa-camera-retro text-5xl mb-4"></i>
                <p>Belum ada foto momen yang dibagikan.</p>
            </div>
        @endif
    </div>
</section>

<a href="https://wa.me/6285942173668" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });
    });
</script>
@endpush