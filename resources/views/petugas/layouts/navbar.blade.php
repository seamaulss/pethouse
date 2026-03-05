<nav class="bg-teal-600/95 backdrop-blur-md text-white shadow-xl sticky top-0 z-50 border-b border-teal-500/30" 
     x-data="{ mobileMenuOpen: false }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            
            <div class="flex items-center">
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-4 group">
                    <div class="bg-white p-2 rounded-xl shadow-inner transform group-hover:rotate-6 transition duration-300">
                        <i class="fas fa-paw text-teal-600 text-xl lg:text-2xl"></i>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-extrabold text-xl lg:text-2xl tracking-tight italic">
                            Pet<span class="text-teal-200">House</span>
                        </span>
                        <span class="text-[10px] uppercase tracking-[0.2em] text-teal-100 font-medium">Petugas Panel</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                
                <div class="flex items-center gap-2 border-r border-teal-500/50 pr-6 mr-2">
                    <a href="{{ route('petugas.dashboard') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('petugas.dashboard') ? 'bg-white/20 shadow-inner' : '' }}">
                        <i class="fas fa-home opacity-70"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('petugas.kapasitas.index') }}" 
                       class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('petugas.kapasitas.*') ? 'bg-white/20 shadow-inner' : '' }}">
                        <i class="fas fa-warehouse opacity-70"></i>
                        <span>Status Kandang</span>
                    </a>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.outside="open = false"
                            class="flex items-center gap-3 p-1 pr-3 rounded-full bg-teal-700/50 hover:bg-teal-500 transition-all duration-300 border border-transparent hover:border-teal-400 focus:outline-none">
                        
                        <div class="relative">
                            <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-full overflow-hidden border-2 border-white/80 shadow-md flex-shrink-0">
                                @php $user = Auth::user(); @endphp
                                @if($user->foto_url)
                                    <img src="{{ asset('storage/foto_petugas/' . $user->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-teal-100 to-teal-300 flex items-center justify-center">
                                        <i class="fas fa-user text-teal-700"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-teal-600 rounded-full"></span>
                        </div>
                        
                        <div class="hidden xl:block text-left">
                            <p class="text-[10px] font-light text-teal-100 leading-none mb-1">Shift Aktif</p>
                            <p class="text-sm font-bold leading-none">{{ Str::limit($user->username ?? 'Petugas', 12) }}</p>
                        </div>
                        
                        <i class="fas fa-chevron-down text-[10px] opacity-70 transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] py-2 z-[60] overflow-hidden border border-gray-100 text-gray-700">
                        
                        <div class="px-4 py-3 bg-gray-50/50 border-b border-gray-100 mb-2 font-bold text-[10px] uppercase tracking-widest text-gray-400">
                            Manajemen Petugas
                        </div>

                        <a href="{{ route('petugas.profile.index') }}" class="group flex items-center gap-3 px-4 py-3 hover:bg-teal-50 transition-colors">
                            <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-all">
                                <i class="fas fa-id-card-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Profil Saya</p>
                                <p class="text-[11px] text-gray-400 leading-none">Biodata & Jabatan</p>
                            </div>
                        </a>

                        <a href="{{ route('petugas.profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 hover:bg-teal-50 transition-colors">
                            <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-all">
                                <i class="fas fa-user-cog text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Pengaturan</p>
                                <p class="text-[11px] text-gray-400 leading-none">Ubah password & foto</p>
                            </div>
                        </a>

                        <div class="border-t border-gray-100 my-2"></div>

                        <button onclick="confirmLogout()" class="w-full group flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 transition-colors">
                            <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                                <i class="fas fa-power-off text-sm"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-sm">Keluar</p>
                                <p class="text-[11px] text-red-300 leading-none">Akhiri sesi kerja</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-teal-500/50 border border-teal-400/30 active:scale-90 transition-all">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times text-xl' : 'fa-bars text-xl'"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="max-h-0 opacity-0"
         x-transition:enter-end="max-h-screen opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="max-h-screen opacity-100"
         x-transition:leave-end="max-h-0 opacity-0"
         class="md:hidden bg-teal-800 border-t border-teal-600 overflow-hidden text-sm">
        <div class="px-4 py-6 space-y-2">
            <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('petugas.dashboard') ? 'bg-white/10' : '' }}">
                <i class="fas fa-home w-5 text-teal-300"></i> Dashboard
            </a>
            <a href="{{ route('petugas.kapasitas.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('petugas.kapasitas.*') ? 'bg-white/10' : '' }}">
                <i class="fas fa-warehouse w-5 text-teal-300"></i> Status Kandang
            </a>
            <div class="border-t border-teal-700 my-4"></div>
            <a href="{{ route('petugas.profile.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-white/10 transition">
                <i class="fas fa-user-circle w-5 text-teal-300"></i> Profil Saya
            </a>
            <button onclick="confirmLogout()" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl bg-red-500/20 text-red-200 mt-4 font-bold">
                <i class="fas fa-sign-out-alt w-5"></i> Logout
            </button>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Selesai Bertugas?',
            text: "Pastikan semua laporan kandang sudah tersimpan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488', // teal-600
            cancelButtonColor: '#ef4444', // red-500
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            borderRadius: '1.25rem',
            customClass: {
                title: 'font-bold text-gray-800',
                popup: 'rounded-3xl shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>