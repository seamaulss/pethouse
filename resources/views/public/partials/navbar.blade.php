@php
    // Ambil path saat ini untuk logika Active Link
    $current_page = request()->path();
    $current_page = $current_page === '/' ? 'home' : $current_page;

    if (!function_exists('isActive')) {
        function isActive($page, $current_page) {
            $page = $page === 'home' ? '' : $page;
            // Gunakan str_contains agar sub-halaman juga tetap aktif
            return $current_page === $page || str_contains($current_page, $page) && $page !== '' 
                ? 'text-teal-500 font-semibold border-b-2 border-teal-500 md:border-none' 
                : 'text-gray-700';
        }
    }

    // Logika Status Operasional (Buka/Tutup)
    $now = now()->timezone('Asia/Jakarta');
    $time = $now->format('H:i');
    
    // Perbaikan: Hapus pengecekan hari agar Senin-Minggu tetap buka
    // Sesuai jadwal: 08:00 - 18:00 setiap hari
    $isOpen = ($time >= '08:00' && $time <= '18:00');
@endphp

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg shadow-lg transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <a href="{{ route('home') }}" class="flex items-center text-2xl font-bold text-gray-800 shrink-0">
                <span class="text-3xl mr-2">🐾</span>
                LARAPet<span class="font-normal text-teal-500">House</span>
            </a>

            <div class="hidden md:flex items-center space-x-4 lg:space-x-8 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-teal-500 {{ isActive('home', $current_page) }} transition-colors duration-200 whitespace-nowrap">Home</a>
                <a href="{{ route('layanan') }}" class="hover:text-teal-500 {{ isActive('layanan', $current_page) }} transition-colors duration-200 whitespace-nowrap">Layanan</a>
                <a href="{{ route('galeri') }}" class="hover:text-teal-500 {{ isActive('galeri', $current_page) }} transition-colors duration-200 whitespace-nowrap">Galeri</a>
                <a href="{{ route('kontak') }}" class="hover:text-teal-500 {{ isActive('kontak', $current_page) }} transition-colors duration-200 whitespace-nowrap">Kontak</a>

                <div class="flex items-center ml-4 px-3 py-1.5 rounded-full {{ $isOpen ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} border {{ $isOpen ? 'border-green-200' : 'border-red-200' }}">
                    <span class="relative flex h-2 w-2 mr-2">
                        @if($isOpen)
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        @endif
                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $isOpen ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest">
                        {{ $isOpen ? 'Buka Sekarang' : 'Tutup' }}
                    </span>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 ml-4">
                    @auth
                        @php $dashboardRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard'; @endphp
                        <a href="{{ route($dashboardRoute) }}"
                            class="inline-flex items-center justify-center h-11 px-6 bg-teal-500 hover:bg-teal-600 text-white rounded-full font-medium transition shadow-md text-sm lg:text-base whitespace-nowrap">
                            Dashboard {{ auth()->user()->role === 'admin' ? 'Admin' : '' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center h-11 px-6 border-2 border-teal-500 text-teal-500 hover:bg-teal-500 hover:text-white rounded-full font-medium transition text-sm lg:text-base whitespace-nowrap">
                            Login
                        </a>
                    @endauth

                    <a href="https://wa.me/6285942173668" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center h-11 px-4 bg-green-500 hover:bg-green-600 text-white rounded-full font-medium transition shadow-md">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>

            <button id="mobileMenuButton" class="md:hidden text-gray-700 focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Toggle menu">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="menuIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path id="closeIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-2xl overflow-y-auto max-h-screen">
        <div class="px-6 py-8 space-y-6">
            <div class="flex items-center justify-center p-3 rounded-xl {{ $isOpen ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} mb-4">
                <span class="h-2 w-2 rounded-full {{ $isOpen ? 'bg-green-500' : 'bg-red-500' }} mr-3"></span>
                <span class="font-bold text-sm uppercase">Klinik sedang {{ $isOpen ? 'Buka' : 'Tutup' }}</span>
            </div>

            <a href="{{ route('home') }}" class="block text-xl {{ isActive('home', $current_page) }} hover:text-teal-500 transition-colors">Home</a>
            <a href="{{ route('layanan') }}" class="block text-xl {{ isActive('layanan', $current_page) }} hover:text-teal-500 transition-colors">Layanan</a>
            <a href="{{ route('galeri') }}" class="block text-xl {{ isActive('galeri', $current_page) }} hover:text-teal-500 transition-colors">Galeri</a>
            <a href="{{ route('kontak') }}" class="block text-xl {{ isActive('kontak', $current_page) }} hover:text-teal-500 transition-colors">Kontak</a>

            <div class="pt-6 border-t border-gray-100 space-y-4">
                @auth
                    @php $dashboardRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard'; @endphp
                    <a href="{{ route($dashboardRoute) }}" class="block w-full py-4 bg-teal-500 text-white text-center rounded-2xl font-bold shadow-lg shadow-teal-200">
                        Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full py-4 border-2 border-teal-500 text-teal-500 text-center rounded-2xl font-bold">
                        Masuk / Login
                    </a>
                @endauth

                <a href="https://wa.me/6285942173668" target="_blank" rel="noopener noreferrer"
                    class="block w-full py-4 bg-green-500 text-white text-center rounded-2xl font-bold flex items-center justify-center gap-3">
                    <i class="fab fa-whatsapp text-2xl"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');
        const navbar = document.getElementById('navbar');

        // Toggle Navbar on Scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('py-1', 'shadow-xl');
            } else {
                navbar.classList.remove('py-1', 'shadow-xl');
            }
        });

        function toggleMenu() {
            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        mobileMenuButton.addEventListener('click', toggleMenu);

        // Close menu on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
                toggleMenu();
            }
        });
    });
</script>