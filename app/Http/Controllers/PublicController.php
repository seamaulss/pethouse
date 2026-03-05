<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Galeri;
use App\Models\Testimoni;
use App\Models\Tentang;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        $layanans = Layanan::orderBy('id', 'desc')->get();
        $testimonis = Testimoni::where('status', 'aktif')->orderBy('created_at', 'desc')->limit(3)->get();
        $galeris = Galeri::orderBy('id', 'desc')->limit(12)->get();
        $tentang = Tentang::orderBy('id', 'desc')->first();
        $slides = HeroSlider::orderBy('urutan', 'asc')->get();

        // Default slide jika kosong
        if ($slides->isEmpty()) {
            $slides = collect([
                (object) [
                    'gambar' => null,
                    'judul' => 'Selamat Datang di LARAPetHouse',
                    'subjudul' => 'Penitipan, Grooming & Perawatan Hewan Kesayangan Anda dengan Cinta',
                    'tombol_text' => 'Booking Sekarang',
                    'tombol_link' => '#'
                ]
            ]);
        }

        // Logika Jam Operasional
        $now = now()->timezone('Asia/Jakarta');
        $time = $now->format('H:i');
        $day = $now->dayOfWeek;

        // Buka Senin-Sabtu, 08:00 - 18:00 (sesuai teks footer Anda)
        $isOpen = ($time >= '08:00' && $time <= '18:00') && ($day != 0);

        return view('public.index', compact('layanans', 'testimonis', 'galeris', 'tentang', 'slides', 'isOpen'));
    }

    /**
     * Menampilkan halaman layanan
     */
    public function layanan()
    {
        $layanans = Layanan::orderBy('id', 'desc')->get();
        return view('public.layanan', compact('layanans'));
    }

    /**
     * Menampilkan halaman galeri
     */
    public function galeri()
    {
        $galeris = Galeri::orderBy('id', 'desc')->get();
        return view('public.galeri', compact('galeris'));
    }

    /**
     * Menampilkan halaman kontak
     */
    public function kontak()
    {
        return view('public.kontak');
    }

    /**
     * Menampilkan halaman tentang kami
     */
    public function tentang()
    {
        $tentangItems = Tentang::orderBy('id', 'desc')->get();
        $testimonis = Testimoni::where('status', 'aktif')->orderBy('created_at', 'desc')->limit(3)->get();
        $layanans = Layanan::orderBy('id', 'desc')->limit(4)->get();

        return view('public.tentang', compact('tentangItems', 'testimonis', 'layanans'));
    }
}
