@extends('layouts.user')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifikasi Saya</h1>
        <p class="text-gray-600">Semua notifikasi yang Anda terima</p>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <button id="mark-all-read-btn" 
                    onclick="markAllAsRead()"
                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
            </button>
            
            <button id="refresh-btn"
                    onclick="refreshNotifications()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
        
        <div class="text-sm text-gray-500">
            <span id="unread-count">{{ $notifications->where('is_read', false)->count() }}</span> notifikasi belum dibaca
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($notifications->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($notifications as $notification)
            <div class="notification-item p-6 hover:bg-gray-50 transition-colors duration-200 
                    {{ !$notification->is_read ? 'bg-blue-50 border-l-4 border-teal-600' : '' }}"
                 data-id="{{ $notification->id }}"
                 onclick="markSingleAsRead({{ $notification->id }}, this, '{{ $notification->booking_id ? route('user.booking.show', $notification->booking_id) : '#' }}')">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 mt-1">
                            @php
                            $icon = match(true) {
                                str_contains($notification->title, 'Booking') => 'fa-calendar text-teal-600',
                                str_contains($notification->title, 'Dibatalkan') => 'fa-calendar-times text-red-500',
                                str_contains($notification->title, 'Diperpanjang') => 'fa-calendar-plus text-teal-600',
                                str_contains($notification->title, 'Konsultasi') => 'fa-comments text-blue-500',
                                str_contains($notification->title, 'Status') => 'fa-info-circle text-teal-600',
                                default => 'fa-bell text-gray-500'
                            };
                            @endphp
                            <i class="fas {{ $icon }} text-2xl"></i>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $notification->title }}</h3>
                                <span class="text-xs text-gray-500 ml-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ $notification->message }}</p>
                            
                            @if($notification->booking_id)
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">
                                <i class="fas fa-hashtag"></i>
                                <span>Booking #{{ $notification->booking_id }}</span>
                            </div>
                            @endif
                            
                            <div class="mt-3 flex items-center gap-4 text-sm">
                                <span class="text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notification->created_at->format('d M Y, H:i') }}
                                </span>
                                
                                @if(!$notification->is_read)
                                <span class="inline-flex items-center gap-1 text-teal-600 font-medium">
                                    <i class="fas fa-circle text-xs"></i>
                                    Baru
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Button -->
                    <button onclick="deleteNotification(event, {{ $notification->id }})" 
                            class="ml-4 text-gray-400 hover:text-red-500 transition-colors"
                            title="Hapus notifikasi">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
        
        @else
        <div class="py-16 text-center">
            <i class="fas fa-bell-slash text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Tidak ada notifikasi</h3>
            <p class="text-gray-400">Anda belum memiliki notifikasi</p>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript -->
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

// Fungsi untuk menandai satu notifikasi sebagai terbaca
async function markSingleAsRead(notificationId, element, redirectUrl = '#') {
    try {
        const response = await fetch(`/user/notifikasi/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update UI
            element.classList.remove('bg-blue-50', 'border-l-4', 'border-teal-600');
            
            // Update unread count
            updateUnreadCount(data.unread_count);
            
            // Jika ada redirect URL dan bukan #, redirect
            if (redirectUrl && redirectUrl !== '#') {
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 300);
            }
            
            showToast('success', 'Notifikasi ditandai sebagai dibaca');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Gagal menandai notifikasi');
    }
}

// Fungsi untuk menandai semua notifikasi sebagai terbaca
async function markAllAsRead() {
    const button = document.getElementById('mark-all-read-btn');
    const originalText = button.innerHTML;
    
    // Tampilkan loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
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
            // Hapus semua styling notifikasi baru
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('bg-blue-50', 'border-l-4', 'border-teal-600');
            });
            
            // Update unread count
            updateUnreadCount(0);
            
            showToast('success', 'Semua notifikasi telah ditandai sebagai dibaca');
        } else {
            showToast('error', data.message || 'Terjadi kesalahan');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Gagal menghubungi server');
    } finally {
        // Kembalikan teks tombol
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Fungsi untuk menghapus notifikasi
async function deleteNotification(event, notificationId) {
    event.stopPropagation(); // Mencegah trigger click pada parent
    
    if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/user/notifikasi/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Hapus elemen dari DOM
            const element = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (element) {
                element.style.opacity = '0';
                element.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    element.remove();
                    
                    // Jika tidak ada notifikasi lagi, reload halaman
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            showToast('success', 'Notifikasi berhasil dihapus');
        } else {
            showToast('error', data.message || 'Gagal menghapus notifikasi');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'Gagal menghapus notifikasi');
    }
}

// Fungsi untuk refresh halaman
function refreshNotifications() {
    const button = document.getElementById('refresh-btn');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
    
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Fungsi untuk update unread count
function updateUnreadCount(count) {
    const unreadElement = document.getElementById('unread-count');
    if (unreadElement) {
        unreadElement.textContent = count;
        
        if (count === 0) {
            unreadElement.classList.add('text-green-600');
            unreadElement.classList.remove('text-red-600');
        } else {
            unreadElement.classList.add('text-red-600');
            unreadElement.classList.remove('text-green-600');
        }
    }
}

// Fungsi untuk menampilkan toast notification
function showToast(type, message) {
    // Hapus toast sebelumnya jika ada
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-y-0 opacity-100 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-xl"></i>
            <div>
                <p class="font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove setelah 3 detik
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    // Update unread count awal
    const unreadCount = {{ $notifications->where('is_read', false)->count() }};
    updateUnreadCount(unreadCount);
});
</script>

<style>
.notification-item {
    cursor: pointer;
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: #f9fafb;
    transform: translateX(5px);
}

/* Animasi untuk pagination */
.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 2px;
}

.pagination li a,
.pagination li span {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination li a {
    color: #4b5563;
    background-color: #f3f4f6;
}

.pagination li a:hover {
    background-color: #0d9488;
    color: white;
}

.pagination li.active span {
    background-color: #0d9488;
    color: white;
    font-weight: 600;
}

.pagination li.disabled span {
    color: #9ca3af;
    cursor: not-allowed;
}
</style>
@endsection