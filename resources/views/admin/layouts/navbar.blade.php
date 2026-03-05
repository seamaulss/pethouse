@php
use Illuminate\Support\Facades\Auth;
$currentRoute = request()->route()->getName();
$adminName = Auth::user()->username;
@endphp

<!-- Overlay untuk Mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300"></div>

<!-- Sidebar -->
<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-white to-gray-50 border-r border-gray-200 flex flex-col shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Header Sidebar -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-br from-teal-500 to-teal-600">
        <div class="flex items-center mb-5">
            <div class="relative">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-teal-600 font-bold text-xl shadow-lg ring-4 ring-teal-400 ring-opacity-30">
                    {{ strtoupper(substr($adminName, 0, 2)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <div class="ml-4 text-white">
                <p class="font-bold text-lg leading-tight">{{ $adminName }}</p>
                <p class="text-sm text-teal-100 flex items-center gap-1">
                    <i class="fas fa-shield-alt text-xs"></i>
                    Administrator
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-white">
            <i class="fas fa-home text-xl"></i>
            <h1 class="text-xl font-bold tracking-wide">LARAPetHouse Admin</h1>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ $currentRoute === 'admin.dashboard' ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ $currentRoute === 'admin.dashboard' ? 'bg-white bg-opacity-20' : 'bg-teal-50 group-hover:bg-teal-100' }} transition-all duration-300">
                <i class="fas fa-tachometer-alt text-lg {{ $currentRoute === 'admin.dashboard' ? 'text-white' : 'text-teal-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Dashboard</span>
            @if($currentRoute === 'admin.dashboard')
                <i class="fas fa-chevron-right ml-auto text-sm"></i>
            @endif
        </a>

        <!-- Booking -->
        <a href="{{ route('admin.booking.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.booking') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.booking') ? 'bg-white bg-opacity-20' : 'bg-pink-50 group-hover:bg-pink-100' }} transition-all duration-300">
                <i class="fas fa-calendar-check text-lg {{ str_contains($currentRoute, 'admin.booking') ? 'text-white' : 'text-pink-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Data Booking</span>
            @if(str_contains($currentRoute, 'admin.booking'))
                <i class="fas fa-chevron-right ml-auto text-sm"></i>
            @endif
        </a>

        <!-- Konsultasi -->
        <a href="{{ route('admin.konsultasi.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.konsultasi') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.konsultasi') ? 'bg-white bg-opacity-20' : 'bg-amber-50 group-hover:bg-amber-100' }} transition-all duration-300">
                <i class="fas fa-comments text-lg {{ str_contains($currentRoute, 'admin.konsultasi') ? 'text-white' : 'text-amber-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Konsultasi</span>
            @if(str_contains($currentRoute, 'admin.konsultasi'))
                <i class="fas fa-chevron-right ml-auto text-sm"></i>
            @endif
        </a>

        <!-- Divider -->
        <div class="pt-6 pb-3 px-2">
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Master Data</p>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>
        </div>

        <!-- Master Data Links -->
        <a href="{{ route('admin.jenis-hewan.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.jenis-hewan') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.jenis-hewan') ? 'bg-white bg-opacity-20' : 'bg-teal-50 group-hover:bg-teal-100' }} transition-all duration-300">
                <i class="fas fa-paw text-lg {{ str_contains($currentRoute, 'admin.jenis-hewan') ? 'text-white' : 'text-teal-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Jenis Hewan</span>
        </a>

        <a href="{{ route('admin.kapasitas.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.kapasitas') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.kapasitas') ? 'bg-white bg-opacity-20' : 'bg-orange-50 group-hover:bg-orange-100' }} transition-all duration-300">
                <i class="fas fa-th-large text-lg {{ str_contains($currentRoute, 'admin.kapasitas') ? 'text-white' : 'text-orange-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Kapasitas Kandang</span>
        </a>
        
        <a href="{{ route('admin.master-kegiatan.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.master-kegiatan') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.master-kegiatan') ? 'bg-white bg-opacity-20' : 'bg-purple-50 group-hover:bg-purple-100' }} transition-all duration-300">
                <i class="fas fa-tasks text-lg {{ str_contains($currentRoute, 'admin.master-kegiatan') ? 'text-white' : 'text-purple-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Master Kegiatan</span>
        </a>

        <a href="{{ route('admin.layanan.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.layanan') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.layanan') ? 'bg-white bg-opacity-20' : 'bg-pink-50 group-hover:bg-pink-100' }} transition-all duration-300">
                <i class="fas fa-concierge-bell text-lg {{ str_contains($currentRoute, 'admin.layanan') ? 'text-white' : 'text-pink-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Data Layanan</span>
        </a>


        <a href="{{ route('admin.hero.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.hero') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.hero') ? 'bg-white bg-opacity-20' : 'bg-indigo-50 group-hover:bg-indigo-100' }} transition-all duration-300">
                <i class="fas fa-sliders-h text-lg {{ str_contains($currentRoute, 'admin.hero') ? 'text-white' : 'text-indigo-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Hero Slider</span>
        </a>

        <a href="{{ route('admin.testimoni.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.testimoni') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.testimoni') ? 'bg-white bg-opacity-20' : 'bg-teal-50 group-hover:bg-teal-100' }} transition-all duration-300">
                <i class="fas fa-star text-lg {{ str_contains($currentRoute, 'admin.testimoni') ? 'text-white' : 'text-teal-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Testimoni</span>
        </a>

        <a href="{{ route('admin.galeri.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.galeri') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.galeri') ? 'bg-white bg-opacity-20' : 'bg-amber-50 group-hover:bg-amber-100' }} transition-all duration-300">
                <i class="fas fa-images text-lg {{ str_contains($currentRoute, 'admin.galeri') ? 'text-white' : 'text-amber-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Galeri</span>
        </a>

        <a href="{{ route('admin.tentang.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 {{ str_contains($currentRoute, 'admin.tentang') ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-700' }}">
            <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ str_contains($currentRoute, 'admin.tentang') ? 'bg-white bg-opacity-20' : 'bg-blue-50 group-hover:bg-blue-100' }} transition-all duration-300">
                <i class="fas fa-info-circle text-lg {{ str_contains($currentRoute, 'admin.tentang') ? 'text-white' : 'text-blue-600' }}"></i>
            </div>
            <span class="ml-3 font-medium">Tentang Kami</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-200 bg-white">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="group flex items-center w-full px-4 py-3.5 rounded-2xl transition-all duration-300 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold shadow-lg">
                <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-white bg-opacity-20 transition-all duration-300 group-hover:scale-110">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </div>
                <span class="ml-3">Logout</span>
                <i class="fas fa-arrow-right ml-auto text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
            </button>
        </form>
    </div>
</aside>