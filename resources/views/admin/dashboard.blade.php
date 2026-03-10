@extends('admin.layouts.app')

@section('title', 'Admin - Dashboard')

@section('content')

<div class="mb-12 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6" data-aos="fade-down" data-aos-duration="1000">
    <div>
        <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-2">
            Selamat datang, <span class="gradient-text">{{ $adminName }}!</span> 🐾
        </h1>
        <p class="text-gray-500 text-lg flex items-center gap-2">
            <i class="far fa-calendar-alt text-teal"></i>
            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
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
                        onclick="markSingleAsRead('{{ $notification->id }}', this)">
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
    'title' => 'Konsultasi Selesai',
    'value' => $selesaiKonsultasi,
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

<div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 mb-12 card-hover" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Revenue Analytics</h2>
            <p class="text-gray-500 text-sm">Grafik performa pendapatan berdasarkan filter</p>
        </div>

        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 bg-gray-50 p-2 rounded-2xl border border-gray-100">
            <div class="flex items-center bg-white rounded-xl px-3 border border-gray-100 shadow-sm">
                <i class="fas fa-tag text-teal text-xs"></i>
                <select name="type" class="text-[12px] font-bold border-none bg-transparent focus:ring-0 text-gray-600 cursor-pointer py-2">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Semua Pendapatan</option>
                    <option value="booking" {{ request('type') == 'booking' ? 'selected' : '' }}>Penitipan</option>
                    <option value="konsultasi" {{ request('type') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar-alt text-teal text-xs"></i>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0 text-gray-600 uppercase cursor-pointer">
                </div>
                <span class="text-gray-300">|</span>
                <div class="flex items-center gap-2">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="text-[11px] font-bold bg-transparent border-none p-0 focus:ring-0 text-gray-600 uppercase cursor-pointer">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-teal text-white w-10 h-10 rounded-xl hover:bg-teal-600 transition-all flex items-center justify-center shadow-lg shadow-teal-100 active:scale-90">
                    <i class="fas fa-filter text-sm"></i>
                </button>

                @if(request('start_date') || request('type'))
                <a href="{{ url()->current() }}" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                    <i class="fas fa-sync-alt text-sm"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="relative">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

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
                        <i class="far fa-clock mr-2"></i>{{ \Carbon\Carbon::parse($row['tanggal'])->locale('id')->translatedFormat('d F Y') }}
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
            locale: 'id-ID',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
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
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Notifications Functions
    async function markAllAsRead() {
        const button = document.getElementById('mark-all-read-btn');
        if (!button) return;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>';
        try {
            const response = await fetch('{{ route("admin.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                location.reload(); // Simple reload to refresh all states
            }
        } catch (error) { console.error('Error:', error); }
    }

    async function markSingleAsRead(notificationId, element) {
        try {
            const response = await fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                element.classList.remove('bg-blue-50');
                element.querySelector('.unread-dot')?.remove();
                if(document.getElementById('notification-badge')) {
                    const currentCount = parseInt(document.getElementById('notification-badge').textContent);
                    if(currentCount <= 1) document.getElementById('notification-badge').remove();
                    else document.getElementById('notification-badge').textContent = currentCount - 1;
                }
            }
        } catch (error) { console.error('Error:', error); }
    }
</script>

<style>
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .notification-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .table-row:hover { background-color: #f9fafb; }
</style>
@endpush