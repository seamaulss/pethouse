@php
    use Illuminate\Support\Facades\Auth;
    $currentRoute = request()->route()->getName();
    $adminName = Auth::user()->username;
@endphp

<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden"></div>

<aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
    
    <div class="p-5 bg-teal-700 text-white">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-800 rounded flex items-center justify-center font-bold border border-teal-500">
                {{ strtoupper(substr($adminName, 0, 2)) }}
            </div>
            <div>
                <p class="font-semibold text-sm leading-tight">{{ $adminName }}</p>
                <p class="text-xs text-teal-200">Administrator</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto scrollbar-thin">
        
        <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Main Menu</p>

        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm rounded {{ $currentRoute === 'admin.dashboard' ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-tachometer-alt w-6 text-center"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.booking.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.booking') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-calendar-check w-6 text-center"></i>
            <span>Data Booking</span>
        </a>

        <a href="{{ route('admin.konsultasi.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.konsultasi') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-comments w-6 text-center"></i>
            <span>Konsultasi</span>
        </a>

        <div class="my-4 border-t border-gray-100"></div>
        <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Master Data</p>

        <a href="{{ route('admin.jenis-hewan.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.jenis-hewan') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-paw w-6 text-center"></i>
            <span>Jenis Hewan</span>
        </a>

        <a href="{{ route('admin.kapasitas.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.kapasitas') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-th-large w-6 text-center"></i>
            <span>Kapasitas Kandang</span>
        </a>

        <a href="{{ route('admin.master-kegiatan.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.master-kegiatan') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-tasks w-6 text-center"></i>
            <span>Master Kegiatan</span>
        </a>

        <a href="{{ route('admin.layanan.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.layanan') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-concierge-bell w-6 text-center"></i>
            <span>Data Layanan</span>
        </a>

        <div class="my-4 border-t border-gray-100"></div>
        <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Halaman Public</p>

        <a href="{{ route('admin.hero.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.hero') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-sliders-h w-6 text-center"></i>
            <span>Hero Slider</span>
        </a>

        <a href="{{ route('admin.testimoni.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.testimoni') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-star w-6 text-center"></i>
            <span>Testimoni</span>
        </a>

        <a href="{{ route('admin.galeri.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.galeri') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-images w-6 text-center"></i>
            <span>Galeri</span>
        </a>

        <a href="{{ route('admin.tentang.index') }}" class="flex items-center px-3 py-2 text-sm rounded {{ str_contains($currentRoute, 'admin.tentang') ? 'bg-gray-100 text-teal-700 font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-info-circle w-6 text-center"></i>
            <span>Tentang Kami</span>
        </a>

    </nav>

    <div class="p-2 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>