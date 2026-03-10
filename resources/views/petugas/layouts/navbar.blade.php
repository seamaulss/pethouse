<nav class="bg-teal-600/95 backdrop-blur-md text-white shadow-xl sticky top-0 z-[100] border-b border-teal-500/30"
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
                                @if($user->foto)
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
                        class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] py-2 z-[110] overflow-hidden border border-gray-100 text-gray-700">

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

                        <button type="button"
                            onclick="confirmLogout(event)"
                            class="w-full group flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 transition-colors cursor-pointer relative z-[120]">
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
</nav>

<form id="logout-form-petugas" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function confirmLogout(event) {
        // Mencegah aksi default jika ada
        if(event) event.preventDefault();

        Swal.fire({
            title: 'Selesai Bertugas?',
            text: "Pastikan semua laporan monitoring sudah tersimpan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488', // Warna Teal-600
            cancelButtonColor: '#ef4444', // Warna Red-500
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            borderRadius: '1.25rem',
            customClass: {
                popup: 'rounded-[2rem] shadow-2xl border border-gray-100',
                title: 'font-black text-gray-800',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Eksekusi form logout
                document.getElementById('logout-form-petugas').submit();
            }
        });
    }
</script>