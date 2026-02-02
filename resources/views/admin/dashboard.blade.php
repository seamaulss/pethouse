@extends('admin.layouts.app')

@section('title', 'Admin - Dashboard')

@section('content')

<!-- Header dengan Welcome Message -->
<div class="mb-12 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6" data-aos="fade-down" data-aos-duration="1000">
    <div>
        <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-2">
            Selamat datang, <span class="gradient-text">{{ $adminName }}!</span> 🐾
        </h1>
        <p class="text-gray-500 text-lg flex items-center gap-2">
            <i class="far fa-calendar-alt text-teal"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>
    <div class="flex items-center gap-6 bg-white px-8 py-4 rounded-3xl shadow-lg relative" x-data="{ showNotifications: false }">
        <span class="text-gray-600 font-medium">Notifikasi</span>
        <div class="relative" @click.away="showNotifications = false">
            <button @click="showNotifications = !showNotifications" class="relative focus:outline-none">
                <i class="fas fa-bell text-4xl text-teal cursor-pointer hover:text-teal-600 transition-colors"></i>
                @if($notifCount > 0)
                <span id="notification-badge" class="notification-badge absolute -top-2 -right-2 bg-pink text-white text-xs font-bold rounded-full w-7 h-7 flex items-center justify-center shadow-lg animate-pulse">
                    {{ $notifCount }}
                </span>
                @endif
            </button>

            <!-- Dropdown Notifikasi -->
            <div x-show="showNotifications" x-transition
                class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl z-50 border border-gray-200 max-h-96 overflow-y-auto"
                style="display: none;">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Notifikasi Terbaru</h3>
                    @if($notifCount > 0)
                    <button id="mark-all-read-btn" 
                            class="text-xs text-teal hover:text-teal-600 font-medium transition-colors duration-200"
                            onclick="markAllAsRead()">
                        <i class="fas fa-check-double mr-1"></i>Tandai semua terbaca
                    </button>
                    @endif
                </div>
                <div id="notification-list">
                    @forelse($recentNotifications as $notification)
                    <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200 
                            {{ !$notification->is_read ? 'bg-blue-50' : '' }}"
                        data-id="{{ $notification->id }}"
                        onclick="markSingleAsRead({{ $notification->id }}, this)">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                @php
                                $icon = match(true) {
                                str_contains($notification->title, 'Baru') => 'fa-calendar-plus text-teal',
                                str_contains($notification->title, 'Dibatalkan') => 'fa-calendar-times text-pink',
                                str_contains($notification->title, 'Diperpanjang') => 'fa-calendar-plus text-teal',
                                str_contains($notification->title, 'Konsultasi') => 'fa-comments text-blue-500',
                                default => 'fa-bell text-gray-500'
                                };
                                @endphp
                                <i class="fas {{ $icon }} text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $notification->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                @if($notification->booking)
                                <p class="text-xs text-gray-500 mt-1">
                                    Kode: {{ $notification->booking->kode_booking }}
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
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards dengan 6 Kolom -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-12">
    @php
    $stats = [
    [
    'icon' => 'fa-calendar-check',
    'title' => 'Booking Bulan Ini',
    'value' => $totalBookingBulanIni,
    'color' => 'teal',
    'bg' => 'from-teal-500 to-teal-600'
    ],
    [
    'icon' => 'fa-paw',
    'title' => 'Hewan Dititipkan',
    'value' => $hewanDititipkanSekarang,
    'color' => 'pink',
    'bg' => 'from-pink-500 to-pink-600'
    ],
    [
    'icon' => 'fa-stethoscope',
    'title' => 'Konsultasi Pending',
    'value' => $pendingKonsultasi,
    'color' => 'amber',
    'bg' => 'from-amber-400 to-amber-500'
    ],
    [
    'icon' => 'fa-sack-dollar',
    'title' => 'Pendapatan Bulan Ini',
    'value' => 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'),
    'color' => 'teal',
    'bg' => 'from-teal-600 to-teal-700',
    'large' => true
    ],
    [
    'icon' => 'fa-chart-pie',
    'title' => 'Occupancy Rate',
    'value' => $occupancyRate . '%',
    'color' => 'pink',
    'bg' => 'from-pink-600 to-pink-700'
    ],
    [
    'icon' => 'fa-comment-dots',
    'title' => 'Testimoni Baru',
    'value' => $testimoniBaru,
    'color' => 'amber',
    'bg' => 'from-amber-500 to-amber-600'
    ],
    ];
    @endphp

    @foreach($stats as $i => $stat)
    <div class="stat-card bg-gradient-to-br {{ $stat['bg'] }} text-white rounded-3xl shadow-xl p-6 card-hover" data-aos="zoom-in" data-aos-delay="{{ $i * 80 }}" data-aos-duration="800">
        <div class="flex flex-col h-full justify-between">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-3">
                    <i class="fas {{ $stat['icon'] }} text-3xl"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium opacity-90 mb-2">{{ $stat['title'] }}</p>
                <p class="text-3xl font-bold tracking-tight {{ isset($stat['large']) ? 'text-2xl' : '' }}">
                    {{ $stat['value'] }}
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Chart Pendapatan 7 Hari -->
<div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 mb-12 card-hover" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Pendapatan 7 Hari Terakhir</h2>
            <p class="text-gray-500">Grafik performa pendapatan minggu ini</p>
        </div>
        <div class="bg-teal bg-opacity-10 rounded-2xl p-4">
            <i class="fas fa-chart-line text-4xl text-teal"></i>
        </div>
    </div>
    <div class="relative">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 mb-12 card-hover" data-aos="fade-right" data-aos-duration="1000">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Aktivitas Terbaru</h2>
            <p class="text-gray-500">10 transaksi terakhir dalam sistem</p>
        </div>
        <div class="bg-pink bg-opacity-10 rounded-2xl p-4">
            <i class="fas fa-history text-4xl text-pink"></i>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b-2 border-gray-200">
                <tr class="text-gray-600 text-sm font-semibold uppercase tracking-wider">
                    <th class="py-5 px-4">Tipe</th>
                    <th class="py-5 px-4">Kode</th>
                    <th class="py-5 px-4">Pelanggan</th>
                    <th class="py-5 px-4">Detail</th>
                    <th class="py-5 px-4">Tanggal</th>
                    <th class="py-5 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivity as $row)
                @php
                $badgeClass = match ($row['status']) {
                'selesai', 'diterima' => 'bg-teal-100 text-teal-700 border-teal-200',
                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                default => 'bg-pink-100 text-pink-700 border-pink-200'
                };
                $statusText = ucwords(str_replace('_', ' ', $row['status']));
                @endphp
                <tr class="table-row border-b border-gray-100">
                    <td class="py-5 px-4">
                        <span class="px-4 py-2 rounded-full text-xs font-semibold border-2 {{ $row['tipe'] == 'booking' ? 'bg-teal-50 text-teal-600 border-teal-200' : 'bg-pink-50 text-pink-600 border-pink-200' }}">
                            <i class="fas {{ $row['tipe'] == 'booking' ? 'fa-calendar' : 'fa-comments' }} mr-1"></i>
                            {{ ucfirst($row['tipe']) }}
                        </span>
                    </td>
                    <td class="py-5 px-4 font-semibold text-gray-700">{{ $row['kode'] }}</td>
                    <td class="py-5 px-4 text-gray-600">{{ $row['pelanggan'] }}</td>
                    <td class="py-5 px-4 text-gray-600">{{ $row['detail'] }}</td>
                    <td class="py-5 px-4 text-gray-500">
                        <i class="far fa-clock mr-2"></i>{{ date('d M Y', strtotime($row['tanggal'])) }}
                    </td>
                    <td class="py-5 px-4">
                        <span class="px-4 py-2 rounded-full text-xs font-bold border-2 {{ $badgeClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(13, 148, 136, 0.3)');
    gradient.addColorStop(1, 'rgba(13, 148, 136, 0.01)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($revenueValues),
                borderColor: '#0d9488',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0d9488',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#0d9488',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#0d9488',
                    borderWidth: 2,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            family: 'Poppins'
                        },
                        callback: value => 'Rp ' + (value / 1000) + 'k'
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            family: 'Poppins'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // CSRF Token untuk AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Fungsi untuk menandai semua notifikasi sebagai terbaca
    async function markAllAsRead() {
        const button = document.getElementById('mark-all-read-btn');
        if (!button) return;
        
        const originalText = button.innerHTML;
        
        // Tampilkan loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
        button.disabled = true;
        
        try {
            const response = await fetch('{{ route("admin.notifications.mark-all-read") }}', {
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
                
                // 4. Tampilkan pesan sukses
                showToast('success', data.message || 'Semua notifikasi telah ditandai sebagai terbaca');
                
                // 5. Update tombol "Tandai semua terbaca" hilang karena tidak ada notif
                button.style.display = 'none';
                
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

    // Fungsi untuk menandai satu notifikasi sebagai terbaca
    async function markSingleAsRead(notificationId, element) {
        try {
            const response = await fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
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
                updateBadgeCount(data.unread_count);
            }
            
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    // Fungsi untuk update badge count
    function updateBadgeCount(count) {
        const badge = document.getElementById('notification-badge');
        const markAllBtn = document.getElementById('mark-all-read-btn');
        
        if (count > 0) {
            if (badge) {
                badge.textContent = count;
                // Perbarui animasi
                badge.classList.remove('animate-pulse');
                void badge.offsetWidth; // Trigger reflow
                badge.classList.add('animate-pulse');
            } else {
                // Buat badge baru jika belum ada
                const bellIcon = document.querySelector('.fa-bell')?.parentElement;
                if (bellIcon) {
                    const newBadge = document.createElement('span');
                    newBadge.id = 'notification-badge';
                    newBadge.className = 'notification-badge absolute -top-2 -right-2 bg-pink text-white text-xs font-bold rounded-full w-7 h-7 flex items-center justify-center shadow-lg animate-pulse';
                    newBadge.textContent = count;
                    bellIcon.appendChild(newBadge);
                }
            }
            
            // Tampilkan tombol "Tandai semua terbaca"
            if (markAllBtn) {
                markAllBtn.style.display = 'block';
            }
            
        } else {
            // Hapus badge jika count = 0
            if (badge) {
                badge.remove();
            }
            
            // Sembunyikan tombol "Tandai semua terbaca"
            if (markAllBtn) {
                markAllBtn.style.display = 'none';
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

    // Event listener untuk dropdown notifikasi
    document.addEventListener('DOMContentLoaded', function() {
        
        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('[x-show="showNotifications"]');
            const bellButton = document.querySelector('.fa-bell')?.closest('button');
            
            if (dropdown && bellButton && 
                !dropdown.contains(event.target) && 
                !bellButton.contains(event.target) &&
                dropdown.style.display !== 'none') {
                
                // Gunakan Alpine.js untuk menutup dropdown
                if (typeof Alpine !== 'undefined') {
                    const alpineComponent = Alpine.$data(dropdown);
                    if (alpineComponent && alpineComponent.showNotifications !== undefined) {
                        alpineComponent.showNotifications = false;
                    }
                }
            }
        });
    });
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
[x-show="showNotifications"] {
    scrollbar-width: thin;
    scrollbar-color: #0d9488 #f0fdfa;
}

[x-show="showNotifications"]::-webkit-scrollbar {
    width: 6px;
}

[x-show="showNotifications"]::-webkit-scrollbar-track {
    background: #f0fdfa;
    border-radius: 10px;
}

[x-show="showNotifications"]::-webkit-scrollbar-thumb {
    background: #0d9488;
    border-radius: 10px;
}

/* Animation for card hover */
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #0d9488, #2dd4bf);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Table row hover */
.table-row:hover {
    background-color: #f9fafb;
}
</style>

@endpush