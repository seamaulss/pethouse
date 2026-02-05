@extends('layouts.user')

@section('title', 'User - Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <!-- Header dengan Notifikasi -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div data-aos="fade-up">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Selamat Datang Kembali, {{ Auth::user()->username }}! 🐾
            </h1>
            <p class="text-lg sm:text-xl text-gray-600">
                Kelola hewan kesayangan dan layanan Anda dengan mudah di sini.
            </p>
        </div>
        
        <!-- Bell Notifikasi -->
        <div class="flex items-center gap-6 bg-white px-8 py-4 rounded-3xl shadow-lg relative" id="notification-container">
            <span class="text-gray-600 font-medium">Notifikasi</span>
            <div class="relative">
                <button id="notification-button" class="relative focus:outline-none">
                    <i class="fas fa-bell text-4xl text-teal cursor-pointer hover:text-teal-600 transition-colors"></i>
                    @if($unreadCount > 0)
                    <span id="notification-badge" class="absolute -top-2 -right-2 bg-pink text-white text-xs font-bold rounded-full w-7 h-7 flex items-center justify-center shadow-lg animate-pulse">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi -->
                <div id="notification-dropdown" class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl z-50 border border-gray-200 max-h-96 overflow-y-auto hidden">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                        @if($unreadCount > 0)
                        <button id="mark-all-read-btn" 
                                class="text-xs text-teal hover:text-teal-600 font-medium transition-colors duration-200"
                                onclick="markAllAsRead()">
                            <i class="fas fa-check-double mr-1"></i>Tandai semua terbaca
                        </button>
                        @endif
                    </div>
                    <div id="notification-list">
                        @forelse($notifications as $notification)
                        <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 
                                {{ !$notification->is_read ? 'bg-blue-50' : '' }}"
                            data-id="{{ $notification->id }}"
                            onclick="markSingleAsRead({{ $notification->id }}, this, '{{ $notification->booking_id ? route('user.booking.show', $notification->booking_id) : '#' }}')">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-1">
                                    @php
                                    $icon = match(true) {
                                        str_contains($notification->title, 'Booking Baru') => 'fa-calendar-plus text-teal',
                                        str_contains($notification->title, 'Dibatalkan') => 'fa-calendar-times text-pink',
                                        str_contains($notification->title, 'Diperpanjang') => 'fa-calendar-plus text-teal',
                                        str_contains($notification->title, 'Konsultasi') => 'fa-comments text-blue-500',
                                        str_contains($notification->title, 'Status') => 'fa-info-circle text-teal',
                                        default => 'fa-bell text-gray-500'
                                    };
                                    @endphp
                                    <i class="fas {{ $icon }} text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $notification->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    @if($notification->booking_id)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Kode: #{{ $notification->booking_id }}
                                    </p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">
                                        <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @if(!$notification->is_read)
                                <span class="unread-dot w-2 h-2 bg-pink rounded-full flex-shrink-0 mt-2"></span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada notifikasi</p>
                        </div>
                        @endforelse
                    </div>
                    @if($notifications->count() > 0)
                    <div class="p-4 border-t border-gray-200 text-center">
                        <a href="{{ route('user.notifikasi.index') }}" class="text-teal-600 hover:text-teal-700 font-medium">
                            Lihat semua notifikasi
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layanan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Hewan Saya -->
        <a href="{{ route('user.hewan-saya') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="100">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-teal-500">🐕</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Hewan Saya</h2>
                <p class="text-gray-600 leading-relaxed">
                    Lihat profil, riwayat penitipan, vaksin, dan status kesehatan semua hewan kesayangan Anda.
                </p>
            </div>
        </a>

        <!-- Booking Penitipan -->
        <a href="{{ route('user.booking.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="200">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-pink-500">🏠</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Booking Penitipan</h2>
                <p class="text-gray-600 leading-relaxed">
                    Booking jadwal penitipan baru, lihat status ongoing, dan dapatkan update foto harian.
                </p>
            </div>
        </a>

        <!-- Konsultasi Dokter -->
        <a href="{{ route('user.konsultasi.create') }}" class="card-dashboard" data-aos="fade-up" data-aos-delay="300">
            <div class="p-8 text-center">
                <div class="text-6xl mb-6 text-amber-500">🩺</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Konsultasi Dokter</h2>
                <p class="text-gray-600 leading-relaxed">
                    Chat langsung dengan dokter hewan, lihat riwayat konsultasi, dan resep obat.
                </p>
            </div>
        </a>
    </div>

    <!-- Info Bantuan -->
    <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="400">
        <p class="text-lg text-gray-600">
            Butuh bantuan cepat? Hubungi kami langsung via WhatsApp:
        </p>
        <a href="https://wa.me/6285942173668?text=Halo%20PetHouse,%20saya%20butuh%20bantuan%20di%20dashboard"
            class="inline-block mt-4 text-xl font-bold text-teal-600 hover:text-teal-700 underline">
            <i class="fab fa-whatsapp mr-2"></i> +62 859-4217-3668
        </a>
    </div>
</div>

<!-- JavaScript untuk Notifikasi -->
<script>
    // Global variables
    let notificationDropdownOpen = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Fungsi untuk toggle dropdown
    document.addEventListener('DOMContentLoaded', function() {
        // Button click handler
        const notificationButton = document.getElementById('notification-button');
        if (notificationButton) {
            notificationButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleNotificationDropdown();
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.getElementById('notification-container');
            const dropdown = document.getElementById('notification-dropdown');
            
            if (container && dropdown && !container.contains(e.target) && notificationDropdownOpen) {
                dropdown.classList.add('hidden');
                notificationDropdownOpen = false;
            }
        });
        
        // Prevent dropdown close when clicking inside dropdown
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Polling untuk notifikasi baru setiap 30 detik
        setInterval(checkNewNotifications, 30000);
    });

    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        
        if (!dropdown) return;
        
        if (notificationDropdownOpen) {
            dropdown.classList.add('hidden');
            notificationDropdownOpen = false;
        } else {
            dropdown.classList.remove('hidden');
            notificationDropdownOpen = true;
        }
    }

    // Fungsi untuk menandai satu notifikasi sebagai terbaca
    async function markSingleAsRead(notificationId, element, redirectUrl = '#') {
        try {
            const url = `/user/notifikasi/${notificationId}/read`;
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Hapus background biru
                element.classList.remove('bg-blue-50');
                
                // Hapus titik merah
                const dot = element.querySelector('.unread-dot');
                if (dot) {
                    dot.remove();
                }
                
                // Update badge count
                updateBadgeCount();
                
                // Jika ada redirect URL dan bukan #, redirect setelah delay
                if (redirectUrl && redirectUrl !== '#') {
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 300);
                }
            }
            
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    // Fungsi untuk menandai semua notifikasi sebagai terbaca
    async function markAllAsRead() {
        const button = document.getElementById('mark-all-read-btn');
        if (!button) return;
        
        const originalText = button.innerHTML;
        
        // Tampilkan loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
        button.disabled = true;
        
        try {
            const response = await fetch('{{ route("user.notifikasi.read-all") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // 1. Hilangkan badge notifikasi
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    badge.remove();
                }
                
                // 2. Hapus background biru dari semua notifikasi
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-blue-50');
                });
                
                // 3. Hapus titik merah dari semua notifikasi
                document.querySelectorAll('.unread-dot').forEach(dot => {
                    dot.remove();
                });
                
                // 4. Sembunyikan tombol "Tandai semua terbaca"
                button.style.display = 'none';
                
            } else {
                console.error('Failed to mark all as read:', data.message);
            }
            
        } catch (error) {
            console.error('Error:', error);
        } finally {
            // Kembalikan teks tombol
            button.innerHTML = originalText;
            button.disabled = false;
        }
    }

    // Fungsi untuk update badge count (mengambil dari server)
    async function updateBadgeCount() {
        try {
            const response = await fetch('{{ route("user.notifikasi.get-new") }}');
            const data = await response.json();
            
            if (data.success) {
                const unreadCount = data.unreadCount;
                const badge = document.getElementById('notification-badge');
                
                if (unreadCount > 0) {
                    if (badge) {
                        badge.textContent = unreadCount;
                    } else {
                        // Buat badge baru jika belum ada
                        const button = document.getElementById('notification-button');
                        if (button) {
                            const newBadge = document.createElement('span');
                            newBadge.id = 'notification-badge';
                            newBadge.className = 'absolute -top-2 -right-2 bg-pink text-white text-xs font-bold rounded-full w-7 h-7 flex items-center justify-center shadow-lg animate-pulse';
                            newBadge.textContent = unreadCount;
                            button.appendChild(newBadge);
                        }
                    }
                } else {
                    // Hapus badge jika tidak ada notifikasi yang belum dibaca
                    if (badge) {
                        badge.remove();
                    }
                    // Sembunyikan tombol "Tandai semua terbaca"
                    const markAllBtn = document.getElementById('mark-all-read-btn');
                    if (markAllBtn) {
                        markAllBtn.style.display = 'none';
                    }
                }
            }
        } catch (error) {
            console.error('Error updating badge count:', error);
        }
    }

    // Fungsi untuk memeriksa notifikasi baru (polling)
    async function checkNewNotifications() {
        try {
            const response = await fetch('{{ route("user.notifikasi.get-new") }}');
            const data = await response.json();
            
            if (data.success && data.unreadCount > 0) {
                // Update badge count
                updateBadgeCount();
            }
        } catch (error) {
            console.error('Error checking new notifications:', error);
        }
    }
</script>

<style>
.notification-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.notification-item {
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-item:hover {
    transform: translateX(5px);
}

/* Style untuk dropdown */
#notification-dropdown {
    scrollbar-width: thin;
    scrollbar-color: #0d9488 #f0fdfa;
}

#notification-dropdown::-webkit-scrollbar {
    width: 6px;
}

#notification-dropdown::-webkit-scrollbar-track {
    background: #f0fdfa;
    border-radius: 10px;
}

#notification-dropdown::-webkit-scrollbar-thumb {
    background: #0d9488;
    border-radius: 10px;
}

/* Animation for card hover */
.card-dashboard {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    text-decoration: none;
    display: block;
    overflow: hidden;
}

.card-dashboard:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: #5eead4;
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #0d9488, #2dd4bf);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endsection