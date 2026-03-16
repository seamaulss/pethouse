<nav class="bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg sticky top-0 z-[100] backdrop-blur-sm bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <div class="flex items-center">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="bg-white p-2 rounded-xl transform group-hover:scale-110 group-hover:rotate-3 transition duration-300 shadow-md">
                        <i class="fas fa-paw text-teal-600 text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg lg:text-xl leading-tight tracking-tight">LARAPetHouse</span>
                        <span class="text-[10px] uppercase tracking-widest text-teal-100 opacity-80 hidden sm:block font-semibold">User Experience</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-2 lg:gap-4">
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl transition duration-300 {{ request()->routeIs('user.dashboard') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/10' }}">
                    <i class="fas fa-th-large text-sm"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('user.booking.create') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl transition duration-300 {{ request()->routeIs('user.booking.*') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/10' }}">
                    <i class="fas fa-calendar-check text-sm"></i>
                    <span class="font-medium">Booking</span>
                </a>

                <a href="{{ route('user.konsultasi.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl transition duration-300 {{ request()->routeIs('user.konsultasi.*') ? 'bg-white/20 shadow-inner' : 'hover:bg-white/10' }}">
                    <i class="fas fa-comment-medical text-sm"></i>
                    <span class="font-medium">Konsultasi</span>
                </a>

                <div class="h-8 w-[1px] bg-white/20 mx-2"></div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-3 pl-2 pr-3 py-1.5 rounded-2xl hover:bg-white/10 transition duration-300 group">
                        <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-full overflow-hidden border-2 border-white/50 shadow-sm flex-shrink-0 bg-teal-800">
                            @if(Auth::user()->foto)
                            <img src="{{ asset('storage/foto_user/' . Auth::user()->foto) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-teal-500 text-white">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            @endif
                        </div>

                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-bold leading-none">{{ Auth::user()->username }}</p>
                            <p class="text-[10px] text-teal-100 mt-1 opacity-70 italic">Online</p>
                        </div>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        style="display: none;"
                        class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-2xl py-2 z-[110] border border-gray-100">

                        <a href="{{ route('user.profil') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-teal-50 group transition">
                            <i class="fas fa-user-circle text-teal-500 group-hover:scale-110 transition"></i>
                            <span class="font-medium text-sm">Profil Saya</span>
                        </a>

                        <div class="border-t border-gray-50 my-1"></div>

                        <button type="button" onclick="confirmLogout()" class="w-full flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-red-50 group transition">
                            <i class="fas fa-power-off text-red-500 group-hover:rotate-12 transition"></i>
                            <span class="font-medium text-sm text-red-600">Keluar Sesi</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobileMenuButton" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 transition">
                    <i class="fas fa-bars text-xl text-white"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-teal-800 border-t border-white/10 overflow-hidden transition-all duration-300">
        <div class="px-4 py-6 space-y-2">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl hover:bg-white/10 transition">
                <i class="fas fa-home w-5 text-teal-300"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('user.booking.create') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl hover:bg-white/10 transition">
                <i class="fas fa-calendar-check w-5 text-teal-300"></i>
                <span class="font-medium">Booking Penitipan</span>
            </a>
            <a href="{{ route('user.konsultasi.index') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl hover:bg-white/10 transition">
                <i class="fas fa-comments w-5 text-teal-300"></i>
                <span class="font-medium">Konsultasi</span>
            </a>
            <a href="{{ route('user.profil') }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl hover:bg-white/10 transition">
                <i class="fas fa-user-edit w-5 text-teal-300"></i>
                <span class="font-medium">Profil</span>
            </a>
            <div class="pt-4 border-t border-white/10">
                <button type="button" onclick="confirmLogout()" class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl bg-red-500/20 text-red-200 hover:bg-red-500/30 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="font-bold">Logout</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function confirmLogout() {
        // Cek apakah SweetAlert terpasang
        if (typeof Swal !== 'undefined') {
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
                    const form = document.getElementById('logout-form');
                    if (form) {
                        form.submit();
                    }
                }
            });
        } else {
            // Fallback jika SweetAlert gagal load
            if (confirm('Yakin ingin keluar?')) {
                document.getElementById('logout-form').submit();
            }
        }
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

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>