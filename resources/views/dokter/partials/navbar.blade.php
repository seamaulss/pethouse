<!-- Navbar Dokter -->
<nav class="bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dokter.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="bg-white p-2 rounded-lg transform group-hover:scale-110 transition duration-300">
                        <span class="text-2xl">🩺</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg lg:text-xl leading-tight">PetHouse</span>
                        <span class="text-xs text-teal-100 hidden sm:block">Dokter Panel</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-2 lg:gap-4">
                
                <!-- Catatan Medis Link -->
                <a href="{{ route('dokter.catatan-medis.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-teal-500 hover:shadow-md transition duration-200 {{ request()->routeIs('dokter.catatan-medis.*') ? 'bg-teal-500 shadow-md' : '' }}">
                    <i class="fas fa-notes-medical"></i>
                    <span>Catatan Medis</span>
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-teal-500 transition duration-200">
                        
                        <!-- Avatar -->
                        <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                            @php $user = Auth::user(); @endphp
                            @if($user->foto_url)
                                <img src="{{ $user->foto_url }}" alt="Foto" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-white flex items-center justify-center">
                                    <i class="fas fa-user-md text-teal-600"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Nama & Role -->
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-semibold">Dr. {{ $user->username }}</p>
                            <p class="text-xs text-teal-100">Dokter</p>
                        </div>
                        
                        <i class="fas fa-chevron-down text-sm ml-1" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 z-50 hidden lg:block">
                        
                        <a href="{{ route('dokter.profile.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-circle text-teal-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Profil Saya</p>
                                <p class="text-xs text-gray-500">Lihat profil</p>
                            </div>
                        </a>

                        <a href="{{ route('dokter.profile.edit') }}" 
                           class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-cog text-teal-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Pengaturan</p>
                                <p class="text-xs text-gray-500">Ubah profil & foto</p>
                            </div>
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-sign-out-alt text-red-600"></i>
                                </div>
                                <div class="text-left">
                                    <p class="font-medium">Keluar</p>
                                    <p class="text-xs text-gray-500">Akhiri sesi</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton" class="p-2 rounded-lg hover:bg-teal-500 transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobileMenu" class="hidden md:hidden bg-teal-700 px-4 py-4 space-y-3">
        <a href="{{ route('dokter.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-home w-6"></i> Dashboard
        </a>
        <a href="{{ route('dokter.catatan-medis.index') }}" class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-notes-medical w-6"></i> Catatan Medis
        </a>
        <a href="{{ route('dokter.profile.index') }}" class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-user-md w-6"></i> Profil Saya
        </a>
        <a href="{{ route('dokter.profile.edit') }}" class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-cog w-6"></i> Pengaturan
        </a>
        <div class="border-t border-teal-600 my-2"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-600 transition flex items-center gap-3">
                <i class="fas fa-sign-out-alt w-6"></i> Logout
            </button>
        </form>
    </div>
</nav>

<!-- Script untuk mobile menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobileMenuButton');
        const menu = document.getElementById('mobileMenu');
        if (btn && menu) {
            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>

<!-- Alpine.js untuk dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>