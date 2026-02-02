<!-- Navbar Dokter -->
<nav class="navbar-dokter text-dark shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex justify-between items-center">
        <a href="{{ route('dokter.dashboard') }}" class="flex items-center text-2xl sm:text-3xl font-bold">
            <span class="text-4xl mr-3">🩺</span>
            Pet<span class="font-normal">House</span> Dokter
        </a>

        <div class="flex items-center space-x-6">
            <!-- Greeting -->
            <span class="hidden sm:block text-lg">
                Halo, <strong>Dokter {{ auth()->user()->username }}</strong>
            </span>

            <!-- Dropdown Menu (klik untuk buka) -->
            <div class="relative">
                <button id="menu-btn" class="flex items-center text-lg font-medium hover:text-teal-200 transition focus:outline-none">
                    <i class="fas fa-bars mr-2"></i>
                    Menu
                    <i class="fas fa-chevron-down ml-2 text-sm transition-transform" id="chevron"></i>
                </button>

                <!-- Dropdown Content -->
                <div id="menu-dropdown" class="absolute right-0 mt-4 w-64 bg-white rounded-2xl shadow-2xl opacity-0 invisible transition-all duration-300 transform scale-95 origin-top-right">
                    <div class="py-3">
                        <a href="{{ route('dokter.dashboard') }}" class="flex items-center px-6 py-3 text-gray-800 hover:bg-teal-50 hover:text-teal-600 transition">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('dokter.cek-status') }}" class="flex items-center px-6 py-3 text-gray-800 hover:bg-teal-50 hover:text-teal-600 transition">
                            <i class="fas fa-search mr-3"></i>
                            Cek Status Konsultasi
                        </a>
                        <a href="{{ route('dokter.catatan-medis.index') }}" class="flex items-center px-6 py-3 text-gray-800 hover:bg-teal-50 hover:text-teal-600 transition">
                            <i class="fas fa-notes-medical mr-3"></i>
                            Catatan Medis
                        </a>
                        <hr class="mx-4 my-2 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50 transition w-full text-left">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Logout untuk mobile -->
            <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-full font-medium shadow-md transition flex items-center text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    Out
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Script untuk toggle dropdown -->
<script>
    const menuBtn = document.getElementById('menu-btn');
    const dropdown = document.getElementById('menu-dropdown');
    const chevron = document.getElementById('chevron');

    menuBtn.addEventListener('click', () => {
        const isHidden = dropdown.classList.contains('opacity-0');

        if (isHidden) {
            dropdown.classList.remove('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.add('opacity-100', 'visible', 'scale-100');
            chevron.classList.add('rotate-180');
        } else {
            dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            chevron.classList.remove('rotate-180');
        }
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', (e) => {
        if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('opacity-0', 'invisible', 'scale-95');
            dropdown.classList.remove('opacity-100', 'visible', 'scale-100');
            chevron.classList.remove('rotate-180');
        }
    });
</script>