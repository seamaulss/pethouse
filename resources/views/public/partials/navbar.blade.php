@php
$current_page = request()->path();
$current_page = $current_page === '/' ? 'home' : $current_page;

function isActive($page, $current_page) {
$page = $page === 'home' ? '' : $page;
return $current_page === $page ? 'text-teal-500 font-semibold' : '';
}
@endphp

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg shadow-lg transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">

      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex items-center text-2xl font-bold text-gray-800 shrink-0">
        <span class="text-3xl mr-2">🐾</span>
        Pet<span class="font-normal text-teal-500">House</span>
      </a>

      <!-- Desktop Menu - Responsif lebih baik -->
      <div class="hidden md:flex items-center space-x-4 lg:space-x-8 flex-wrap gap-y-2">
        <a href="{{ route('home') }}" class="text-gray-700 hover:text-teal-500 {{ isActive('home', $current_page) }} transition-colors duration-200 whitespace-nowrap">Home</a>
        <a href="{{ route('layanan') }}" class="text-gray-700 hover:text-teal-500 {{ isActive('layanan', $current_page) }} transition-colors duration-200 whitespace-nowrap">Layanan</a>
        <a href="{{ route('galeri') }}" class="text-gray-700 hover:text-teal-500 {{ isActive('galeri', $current_page) }} transition-colors duration-200 whitespace-nowrap">Galeri</a>
        <a href="{{ route('kontak') }}" class="text-gray-700 hover:text-teal-500 {{ isActive('kontak', $current_page) }} transition-colors duration-200 whitespace-nowrap">Kontak</a>

        <!-- Tombol Aksi - Menyesuaikan ukuran secara bertahap -->
        <!-- Tombol Aksi -->
        <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4 ml-2 lg:ml-6">

          @auth
          @if(auth()->user()->role === 'user')
          <a href="{{ route('user.dashboard') }}"
            class="inline-flex items-center justify-center
                h-11 px-6
                bg-teal-500 hover:bg-teal-600 text-white
                rounded-full font-medium transition shadow-md
                text-sm lg:text-base whitespace-nowrap">
            Dashboard
          </a>

          @elseif(auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center justify-center
                h-11 px-6
                bg-teal-500 hover:bg-teal-600 text-white
                rounded-full font-medium transition shadow-md
                text-sm lg:text-base whitespace-nowrap">
            Dashboard Admin
          </a>
          @endif

          @else
          <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center
              h-11 px-6
              border-2 border-teal-500 text-teal-500
              hover:bg-teal-500 hover:text-white
              rounded-full font-medium transition
              text-sm lg:text-base whitespace-nowrap">
            Login
          </a>
          @endauth

          <a href="https://wa.me/6285942173668" target="_blank" rel="noopener"
            class="inline-flex items-center justify-center
          h-11 px-6
          bg-green-500 hover:bg-green-600 text-white
          rounded-full font-medium transition shadow-md
          text-sm lg:text-base whitespace-nowrap">
            <svg class="w-5 h-5 mr-2 flex-shrink-0"
              fill="currentColor"
              viewBox="0 0 24 24"
              aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
            </svg>
            WA
          </a>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <button id="mobileMenuButton" class="md:hidden text-gray-700 focus:outline-none p-2" aria-label="Toggle menu" aria-expanded="false">
        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path id="menuIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          <path id="closeIcon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Menu Dropdown -->
  <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-xl">
    <div class="px-4 py-5 space-y-4">
      <a href="{{ route('home') }}" class="block py-3 text-lg text-gray-700 hover:text-teal-500 {{ isActive('home', $current_page) }} font-medium transition-colors">Home</a>
      <a href="{{ route('layanan') }}" class="block py-3 text-lg text-gray-700 hover:text-teal-500 {{ isActive('layanan', $current_page) }} font-medium transition-colors">Layanan</a>
      <a href="{{ route('galeri') }}" class="block py-3 text-lg text-gray-700 hover:text-teal-500 {{ isActive('galeri', $current_page) }} font-medium transition-colors">Galeri</a>
      <a href="{{ route('kontak') }}" class="block py-3 text-lg text-gray-700 hover:text-teal-500 {{ isActive('kontak', $current_page) }} font-medium transition-colors">Kontak</a>

      <div class="pt-4 border-t border-gray-200 space-y-4">
        @auth
        @if(auth()->user()->role === 'user')
        <a href="{{ route('user.dashboard') }}" class="block w-full py-3.5 bg-teal-500 hover:bg-teal-600 text-white text-center rounded-full font-semibold transition shadow-md">
          Dashboard
        </a>
        @elseif(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="block w-full py-3.5 bg-teal-500 hover:bg-teal-600 text-white text-center rounded-full font-semibold transition shadow-md">
          Dashboard Admin
        </a>
        @endif
        @else
        <a href="{{ route('login') }}" class="block w-full py-3.5 border-2 border-teal-500 text-teal-500 hover:bg-teal-500 hover:text-white text-center rounded-full font-semibold transition">
          Login
        </a>
        @endauth

        <a href="https://wa.me/6285942173668" target="_blank" rel="noopener"
          class="block w-full py-3.5 bg-green-500 hover:bg-green-600 text-white text-center rounded-full font-semibold transition shadow-md flex items-center justify-center gap-3">
          <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
          </svg>
          WhatsApp
        </a>
      </div>
    </div>
  </div>
</nav>

<script>
  const mobileMenuButton = document.getElementById('mobileMenuButton');
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = document.getElementById('menuIcon');
  const closeIcon = document.getElementById('closeIcon');

  function openMenu() {
    mobileMenu.classList.remove('hidden');
    menuIcon.classList.add('hidden');
    closeIcon.classList.remove('hidden');
    mobileMenuButton.setAttribute('aria-expanded', 'true');
    document.body.classList.add('overflow-hidden');
  }

  function closeMenu() {
    mobileMenu.classList.add('hidden');
    menuIcon.classList.remove('hidden');
    closeIcon.classList.add('hidden');
    mobileMenuButton.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('overflow-hidden');
  }

  mobileMenuButton.addEventListener('click', (e) => {
    e.stopPropagation();
    mobileMenu.classList.contains('hidden') ? openMenu() : closeMenu();
  });

  document.addEventListener('click', (e) => {
    if (!mobileMenu.classList.contains('hidden') &&
      !mobileMenu.contains(e.target) &&
      !mobileMenuButton.contains(e.target)) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
      closeMenu();
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
      closeMenu();
    }
  });
</script>