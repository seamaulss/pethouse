<nav class="navbar-petugas text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3">
                    <i class="fas fa-paw text-2xl"></i>
                    <span class="font-bold text-xl hidden sm:block">PetHouse - Petugas</span>
                    <span class="font-bold text-xl block sm:hidden">PetHouse</span>
                </a>
            </div>

            <!-- Menu -->
            <div class="flex items-center gap-6">
                <!-- Dashboard -->
                <a href="{{ route('petugas.dashboard') }}"
                    class="hover:bg-teal-600 px-3 py-2 rounded-md flex items-center gap-2 transition">
                    <i class="fas fa-home"></i>
                    <span class="hidden sm:inline">Dashboard</span>
                </a>

                <!-- Notifications -->
                <a href="{{ route('petugas.notifications.index') }}"
                    class="hover:bg-teal-600 px-3 py-2 rounded-md flex items-center gap-2 relative transition">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="hidden sm:inline">Notifikasi</span>
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- Profile -->
                <a href="{{ route('petugas.profile.index') }}"
                    class="hover:bg-teal-600 px-3 py-2 rounded-md flex items-center gap-2 transition">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="hidden sm:inline">Profile</span>
                </a>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="hover:bg-red-600 px-4 py-2 rounded-md flex items-center gap-2 transition bg-red-600">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>