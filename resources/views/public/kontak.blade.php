@extends('layouts.public')

@section('title', 'Kontak - PetHouse')
@section('meta_description', 'Hubungi PetHouse untuk informasi layanan penitipan, grooming, dan perawatan hewan.')

@push('styles')
<style>
    .hero-overlay {
        background: linear-gradient(to bottom, rgba(13,148,136,0.6), rgba(244,63,94,0.5));
    }

    .card-modern {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: white;
    }
    .card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #2dd4bf);
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 30px rgba(13,148,136,0.4);
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
        .whatsapp-float {
            width: 55px;
            height: 55px;
            bottom: 15px;
            right: 15px;
        }
    }
</style>
@endpush

@section('content')

<!-- HERO -->
<section class="relative py-20 md:py-28 bg-cover bg-center"
    style="background-image: url('{{ asset('assets/img/hero-kontak.jpg') }}');">
    <div class="absolute inset-0 hero-overlay"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-teal-600/40 to-pink-600/40"></div>

    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <div data-aos="fade-up">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                Hubungi Kami
            </h1>
            <p class="text-lg sm:text-xl text-white max-w-3xl mx-auto">
                Kami siap membantu perawatan hewan kesayangan Anda kapan saja 🐾❤️
            </p>
        </div>
    </div>
</section>

<!-- KONTEN -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- INFO KONTAK -->
            <div data-aos="fade-right">
                <h2 class="text-3xl font-bold mb-8">Informasi Kontak</h2>

                <ul class="space-y-6 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt text-teal-600 text-xl mr-4 mt-1"></i>
                        <div>
                            <strong>Alamat</strong><br>
                            Jl. Mawar No. 10, Banjarnegara, Jawa Tengah
                        </div>
                    </li>

                    <li class="flex items-center">
                        <i class="fab fa-whatsapp text-green-500 text-2xl mr-4"></i>
                        <a href="https://wa.me/6285942173668" target="_blank"
                           class="text-teal-600 font-semibold hover:underline">
                            0859-4217-3668
                        </a>
                    </li>

                    <li class="flex items-center">
                        <i class="fas fa-envelope text-pink-500 text-xl mr-4"></i>
                        <a href="mailto:pethouse@gmail.com"
                           class="text-teal-600 hover:underline">
                            pethouse@gmail.com
                        </a>
                    </li>

                    <li class="flex items-center">
                        <i class="fas fa-clock text-amber-500 text-xl mr-4"></i>
                        <div>
                            <strong>Jam Operasional</strong><br>
                            Senin – Minggu: 08.00 – 15.00 WIB
                        </div>
                    </li>
                </ul>

                <!-- MAP -->
                <div class="mt-10 card-modern">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.852235712345!2d109.6522357!3d-7.4039689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aab250dbea06f%3A0x67886a3086ca184d!2sKambing%20Kita%20Banjarnegara!5e0!3m2!1sid!2sid!4v1712345678901"
                        class="w-full h-72 border-0 rounded-2xl"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <!-- FORM -->
            <div data-aos="fade-left">
                <h2 class="text-3xl font-bold mb-8">Kirim Pesan</h2>

                <div class="card-modern p-8">
                    <form method="POST" action="#">
                        @csrf

                        <div class="mb-5">
                            <label class="block mb-2 font-medium">Nama Lengkap</label>
                            <input type="text" name="nama" required
                                   class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="mb-5">
                            <label class="block mb-2 font-medium">Email</label>
                            <input type="email" name="email" required
                                   class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500">
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Pesan</label>
                            <textarea name="pesan" rows="5" required
                                      class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full btn-primary text-white font-bold py-4 rounded-xl flex justify-center items-center">
                            Kirim Pesan
                            <i class="fas fa-paper-plane ml-3"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FLOATING WHATSAPP -->
<a href="https://wa.me/6285942173668?text=Halo%20PetHouse"
   class="whatsapp-float text-4xl"
   target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

@endsection
