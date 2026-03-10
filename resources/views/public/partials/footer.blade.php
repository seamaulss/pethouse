<footer class="bg-gradient-to-br from-teal-700 to-teal-900 text-white mt-20 relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-5 transform translate-x-10 -translate-y-10">
        <i class="fas fa-paw text-[200px]"></i>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            <div data-aos="fade-up">
                <a href="{{ route('home') }}" class="flex items-center text-3xl font-bold mb-6">
                    <span class="mr-2">🐾</span> Pet<span class="text-teal-300">House</span>
                </a>
                <p class="text-teal-100 leading-relaxed mb-6 opacity-80">
                    Solusi terpercaya untuk penitipan, perawatan, dan kesehatan hewan kesayangan Anda di Banjarnegara. Kami merawat mereka seperti keluarga sendiri.
                </p>
                <div class="flex space-x-4">
                    <a href="https://instagram.com/arkanmaulidhana/" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center hover:bg-pink-500 transition-all duration-300 shadow-lg">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center hover:bg-blue-600 transition-all duration-300 shadow-lg">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center hover:bg-black transition-all duration-300 shadow-lg">
                        <i class="fab fa-tiktok text-lg"></i>
                    </a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl font-bold mb-6 pb-2 border-b-2 border-teal-500 w-16">Navigasi</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="text-teal-100 hover:text-white hover:translate-x-2 transition-all inline-block">Home</a></li>
                    <li><a href="{{ route('layanan') }}" class="text-teal-100 hover:text-white hover:translate-x-2 transition-all inline-block">Layanan Kami</a></li>
                    <li><a href="{{ route('galeri') }}" class="text-teal-100 hover:text-white hover:translate-x-2 transition-all inline-block">Galeri Foto</a></li>
                    <li><a href="{{ route('kontak') }}" class="text-teal-100 hover:text-white hover:translate-x-2 transition-all inline-block">Hubungi Kami</a></li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-xl font-bold mb-6 pb-2 border-b-2 border-teal-500 w-16">Kontak</h3>
                <ul class="space-y-5">
                    <li class="flex items-start gap-4">
                        <div class="bg-teal-600 p-3 rounded-xl"><i class="fas fa-map-marker-alt"></i></div>
                        <a href="https://maps.google.com" target="_blank" class="text-teal-100 hover:text-white transition">
                            Jl. Tampomas, Kec. Bawang,<br>Banjarnegara, Jawa Tengah
                        </a>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="bg-teal-600 p-3 rounded-xl"><i class="fas fa-phone-alt"></i></div>
                        <a href="https://wa.me/6285942173668" class="text-teal-100 hover:text-white transition">0859-4217-3668</a>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="bg-teal-600 p-3 rounded-xl"><i class="fas fa-envelope"></i></div>
                        <a href="mailto:larapethouse@gmail.com" class="text-teal-100 hover:text-white transition">larapethouse@gmail.com</a>
                    </li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-xl font-bold mb-6 pb-2 border-b-2 border-teal-500 w-16">Jadwal</h3>
                <div class="bg-teal-800/50 p-6 rounded-2xl border border-teal-600">
                    <ul class="space-y-3">
                        <li class="flex justify-between text-sm">
                            <span class="text-teal-300">Senin - Sabtu</span>
                            <span class="font-bold">08:00 - 18:00</span>
                        </li>
                        <li class="flex justify-between text-sm">
                            <span class="text-teal-300">Minggu</span>
                            <span class="text-pink-400 font-bold uppercase italic">Tutup</span>
                        </li>
                    </ul>
                    <hr class="my-4 border-teal-600">
                    <p class="text-xs text-teal-200 italic leading-relaxed">
                        *Layanan gawat darurat tersedia melalui WhatsApp di luar jam kantor.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-teal-800 bg-teal-950/50 py-8 text-center text-teal-400 text-sm px-6">
        <div class="flex flex-col md:flex-row justify-between items-center text-center max-w-7xl mx-auto gap-4">
            <p>© {{ date('Y') }} LARAPetHouse</p>
        </div>
    </div>
</footer>

<!-- AOS Init (jika belum ada di halaman utama) -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 50
    });
</script>