@extends('layouts.public')

@section('title', 'Kontak - LARAPetHouse')
@section('meta_description', 'Hubungi LARAPetHouse untuk informasi layanan penitipan, grooming, dan perawatan hewan.')

@push('styles')
<style>
    /* Pindahkan background image ke sini agar VS Code tidak error */
    .hero-section-custom {
        background-image: url('{{ asset("assets/img/hero-kontak.jpg") }}');
    }

    .hero-overlay {
        background: linear-gradient(to bottom, rgba(13, 148, 136, 0.6), rgba(244, 63, 94, 0.5));
    }

    .card-modern {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 30px rgba(13, 148, 136, 0.4);
        background: linear-gradient(135deg, #0f766e, #2dd4bf);
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

<section class="py-12 md:py-20 bg-teal-50 relative overflow-hidden">
    <div class="absolute top-0 left-0 -translate-y-1/2 -translate-x-1/4 w-72 h-72 bg-pink-100 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute bottom-0 right-0 translate-y-1/2 translate-x-1/4 w-72 h-72 bg-teal-100 rounded-full blur-3xl opacity-60"></div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-800 mb-4" data-aos="fade-down">
            Hubungi Kami
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Kami siap membantu perawatan hewan kesayangan Anda kapan saja 🐾❤️
        </p>
        <div class="mt-6 flex justify-center" data-aos="zoom-in" data-aos-delay="200">
            <div class="h-1.5 w-16 bg-pink-500 rounded-full"></div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

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
                        <a href="mailto:LARAPetHouse@gmail.com"
                            class="text-teal-600 hover:underline">
                            LARAPetHouse@gmail.com
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

                <div class="mt-10 card-modern">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126615.158226065!2d109.6053!3d-7.3995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aaec573f55057%3A0xc023807204739567!2sBanjarnegara!5e0!3m2!1sid!2sid!4v1700000000000"
                        class="w-full h-72 border-0 rounded-2xl"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <div data-aos="fade-left">
                <h2 class="text-3xl font-bold mb-8">Kirim Pesan</h2>

                <div class="card-modern p-8">
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-5">
                            <label class="block mb-2 font-medium">Nama Lengkap</label>
                            <input type="text" name="nama" required
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <div class="mb-5">
                            <label class="block mb-2 font-medium">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Pesan</label>
                            <textarea name="pesan" rows="5" required
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-teal-500 outline-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full btn-primary text-white font-bold py-4 rounded-xl">
                            Kirim Pesan
                            <i class="fas fa-paper-plane ml-3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<a href="https://wa.me/6285942173668?text=Halo%20LARAPetHouse"
    class="whatsapp-float text-4xl"
    target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

@endsection