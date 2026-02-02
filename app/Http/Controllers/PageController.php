<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Testimoni;
use App\Models\Tentang;
use App\Models\HeroSlider;

class PageController extends Controller
{
    public function home()
    {
        $slides = HeroSlider::orderBy('urutan', 'asc')->get();
        $layanan = Layanan::orderBy('id', 'desc')->get();
        $testimoni = Testimoni::where('status', 'aktif')->orderBy('created_at', 'desc')->limit(3)->get();
        $galeri = Galeri::orderBy('id', 'desc')->limit(12)->get();
        $tentang = Tentang::orderBy('id', 'desc')->first();

        // Jika tidak ada slide, gunakan default
        if ($slides->isEmpty()) {
            $slides = collect([
                (object) [
                    'gambar' => 'default-hero.jpg',
                    'judul' => 'Selamat Datang di PetHouse',
                    'subjudul' => 'Penitipan, Grooming & Perawatan Hewan Kesayangan Anda dengan Cinta',
                    'tombol_text' => 'Booking Sekarang',
                    'tombol_link' => ''
                ]
            ]);
        }

        return view('home', compact('slides', 'layanan', 'testimoni', 'galeri', 'tentang'));
    }

    public function galeri()
    {
        $galeri = Galeri::orderBy('id', 'desc')->limit(20)->get();
        return view('galeri', compact('galeri'));
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function layanan()
    {
        $layanan = Layanan::orderBy('id', 'desc')->get();
        return view('layanan', compact('layanan'));
    }
}