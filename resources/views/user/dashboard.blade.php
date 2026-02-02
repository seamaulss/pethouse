@extends('layouts.user')

@section('title', 'User - Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4 text-center sm:text-left">
            Selamat Datang Kembali, {{ Auth::user()->username }}! 🐾
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-10 text-center sm:text-left">
            Kelola hewan kesayangan dan layanan Anda dengan mudah di sini.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Hewan Saya -->
        <a href="{{ route('user.hewan-saya') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="100">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-teal-500">🐕</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Hewan Saya</h2>
                <p class="text-gray-600 leading-relaxed">
                    Lihat profil, riwayat penitipan, vaksin, dan status kesehatan semua hewan kesayangan Anda.
                </p>
            </div>
        </a>

        <!-- Booking Penitipan -->
        <a href="{{ route('user.booking.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="200">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-pink-500">🏠</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Booking Penitipan</h2>
                <p class="text-gray-600 leading-relaxed">
                    Booking jadwal penitipan baru, lihat status ongoing, dan dapatkan update foto harian.
                </p>
            </div>
        </a>

        <!-- Konsultasi Dokter -->
        <a href="{{ route('user.konsultasi.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="300">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-amber-500">🩺</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Konsultasi Dokter</h2>
                <p class="text-gray-600 leading-relaxed">
                    Chat langsung dengan dokter hewan, lihat riwayat konsultasi, dan resep obat.
                </p>
            </div>
        </a>
    </div>

    <!-- Info Bantuan -->
    <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="400">
        <p class="text-lg text-gray-600">
            Butuh bantuan cepat? Hubungi kami langsung via WhatsApp:
        </p>
        <a href="https://wa.me/6285942173668?text=Halo%20PetHouse,%20saya%20butuh%20bantuan%20di%20dashboard"
            class="inline-block mt-4 text-xl font-bold text-teal-600 hover:text-teal-700 underline">
            <i class="fab fa-whatsapp mr-2"></i> +62 859-4217-3668
        </a>
    </div>
</div>
@endsection