<nav class="bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">

            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="bg-white p-2 rounded-lg transform group-hover:scale-110 transition duration-300">
                        <i class="fas fa-paw text-teal-600 text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg lg:text-xl leading-tight">PetHouse</span>
                        <span class="text-xs text-teal-100 hidden sm:block">Petugas Panel</span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-2 lg:gap-4">

                <!-- Dashboard Link -->
                <a href="{{ route('petugas.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-teal-500 hover:shadow-md transition duration-200 {{ request()->routeIs('petugas.dashboard') ? 'bg-teal-500 shadow-md' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Notifications Bell -->
                <div class="relative">
                    <button id="notificationButton"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-teal-500 transition duration-200 relative">
                        <i class="fas fa-bell text-xl"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full animate-pulse">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                        <span class="hidden lg:inline">Notifikasi</span>
                    </button>

                    <!-- Dropdown Notifikasi (sembunyikan default) -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl text-gray-800 z-50 hidden">
                        <!-- Isi dropdown notifikasi -->
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-teal-500 transition duration-200">

                        <!-- Foto Profil / Avatar -->
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                            @php
                            $user = Auth::user();
                            @endphp
                            @if($user->foto_url)
                            <img src="{{ $user->foto_url }}" alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-white flex items-center justify-center">
                                <i class="fas fa-user text-teal-600"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Nama & Email (Desktop) -->
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-semibold">{{ $user->username ?? 'Petugas' }}</p>
                            <p class="text-xs text-teal-100">{{ $user->email ?? '' }}</p>
                        </div>

                        <i class="fas fa-chevron-down text-sm ml-1" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 z-50 hidden lg:block">

                        <a href="{{ route('petugas.profile.index') }}"
                            class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-circle text-teal-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">Profil Saya</p>
                                <p class="text-xs text-gray-500">Lihat dan edit profil</p>
                            </div>
                        </a>

                        <a href="{{ route('petugas.profile.edit') }}"
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

                        <!-- Logout Button dengan Konfirmasi -->
                        <button onclick="confirmLogout()"
                            class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-red-600"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-medium">Keluar</p>
                                <p class="text-xs text-gray-500">Akhiri sesi</p>
                            </div>
                        </button>

                        <!-- Hidden Logout Form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
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
        <a href="{{ route('petugas.dashboard') }}"
            class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-home w-6"></i>
            Dashboard
        </a>

        <a href="{{ route('petugas.profile.index') }}"
            class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-user w-6"></i>
            Profil
        </a>

        <a href="{{ route('petugas.profile.edit') }}"
            class="block px-4 py-3 rounded-lg hover:bg-teal-600 transition flex items-center gap-3">
            <i class="fas fa-cog w-6"></i>
            Pengaturan
        </a>

        <button onclick="confirmLogout()"
            class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-600 transition flex items-center gap-3">
            <i class="fas fa-sign-out-alt w-6"></i>
            Logout
        </button>
    </div>
</nav>

<!-- JavaScript untuk Logout Confirmation -->
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda akan diarahkan ke halaman login",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Toggle mobile menu
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

<!-- Required: Alpine.js untuk dropdown (jika belum ada) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Required: SweetAlert2 untuk konfirmasi logout (jika belum ada) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>