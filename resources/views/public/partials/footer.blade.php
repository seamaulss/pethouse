<footer class="bg-gradient-to-br from-teal-600 to-teal-700 text-white mt-16 sm:mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-16 text-center md:text-left">

            <!-- Kontak & Lokasi -->
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl sm:text-2xl font-bold mb-6 flex items-center justify-center md:justify-start">
                    <i class="fas fa-map-marker-alt mr-3 text-pink-300"></i>
                    Kontak & Lokasi
                </h3>
                <ul class="space-y-4 text-sm sm:text-base">
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-location-dot text-pink-300 mr-3 text-lg"></i>
                        <span>Jl. Tampomas,<br>Kota Banjarnegara</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fab fa-whatsapp text-green-300 mr-3 text-xl"></i>
                        <a href="https://wa.me/6289506700308" target="_blank" class="hover:underline">
                            0895-0670-0308
                        </a>
                    </li>
                    <li class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-envelope text-amber-300 mr-3 text-lg"></i>
                        <a href="mailto:pethouse@gmail.com" class="hover:underline">
                            pethouse@gmail.com
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Media Sosial -->
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-xl sm:text-2xl font-bold mb-6 flex items-center justify-center md:justify-start">
                    <i class="fas fa-heart text-pink-300 mr-3"></i>
                    Ikuti Kami
                </h3>
                <div class="flex justify-center md:justify-start space-x-6 text-3xl">
                    <a href="https://www.instagram.com/arkanmaulidhana/" class="hover:text-pink-300 transition transform hover:scale-110" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-pink-300 transition transform hover:scale-110">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://wa.me/6285942173668" target="_blank" class="hover:text-green-300 transition transform hover:scale-110">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="hover:text-pink-300 transition transform hover:scale-110" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
                <p class="mt-6 text-sm sm:text-base text-gray-200">
                    Dapatkan update foto hewan lucu & promo menarik!
                </p>
            </div>

            <!-- Jam Operasional -->
            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-xl sm:text-2xl font-bold mb-6 flex items-center justify-center md:justify-start">
                    <i class="fas fa-clock text-amber-300 mr-3"></i>
                    Jam Operasional
                </h3>
                <ul class="space-y-3 text-sm sm:text-base">
                    <li class="flex items-center justify-center md:justify-start">
                        <span class="font-medium">Senin - Sabtu:</span>
                        <span class="ml-3">08.00 - 18.00 WIB</span>
                    </li>

                    <li class="text-pink-300 mt-4 flex items-center justify-center md:justify-start">
                        <i class="fas fa-phone-alt mr-3"></i>
                        Layanan darurat 24 jam via WhatsApp
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Copyright -->
    <div class="bg-teal-800/70 text-center py-5 text-sm sm:text-base border-t border-teal-500/30">
        <p class="flex items-center justify-center">
            © {{ date('Y') }} <span class="font-bold mx-2">PetHouse</span> 
        </p>
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