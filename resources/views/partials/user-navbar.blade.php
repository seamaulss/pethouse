<!-- Header/Navbar -->
<nav class="bg-gradient-to-r from-teal-600 to-teal-700 text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-paw text-amber-400 text-2xl"></i>
                    <span class="text-xl font-bold">Pet<span class="font-normal">House</span></span>
                </div>
                <div class="hidden md:block ml-10">
                    <div class="flex items-baseline space-x-4">
                        <a href="{{ route('user.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-teal-700 transition duration-200">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('user.konsultasi.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-teal-700 text-white relative">
                            <i class="fas fa-comments mr-2"></i>Konsultasi Saya
                        </a>
                        <a href="{{ route('user.profil') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-teal-700 transition duration-200">
                            <i class="fas fa-user mr-2"></i>Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-teal-700 focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <span class="text-sm">Halo, {{ auth()->user()->username }}</span>
                <a href="{{ route('logout') }}" class="bg-pink-600 hover:bg-pink-700 px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center space-x-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-teal-700 px-2 pt-2 pb-3 space-y-1">
        <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-teal-800">
            <i class="fas fa-home mr-2"></i>Dashboard
        </a>
        <a href="{{ route('user.konsultasi.index') }}"
            class="px-3 py-2 rounded-md text-sm font-medium hover:bg-teal-700 text-white relative {{ request()->routeIs('user.konsultasi.*') ? 'bg-teal-700' : '' }}">
            <i class="fas fa-comments mr-2"></i>Konsultasi Saya
            @if(isset($jml_notif) && $jml_notif > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ $jml_notif > 9 ? '9+' : $jml_notif }}
            </span>
            @endif
        </a>
        <a href="{{ route('user.profil') }}" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-teal-800">
            <i class="fas fa-user mr-2"></i>Profil
        </a>
        <div class="pt-4 border-t border-teal-600">
            <span class="block px-3 py-2 text-sm">Halo, {{ auth()->user()->name }}</span>
            <a href="{{ route('logout') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-pink-600 hover:bg-pink-700" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</nav>

<!-- Script Mobile Menu -->
<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>