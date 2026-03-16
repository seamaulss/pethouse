@extends('admin.layouts.app')

@section('title', 'Admin - Dashboard')

@section('content')

{{-- Header Section --}}
<div class="mb-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
    <div class="space-y-1">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-800 tracking-tight">
            Selamat datang, <span class="gradient-text">{{ $adminName }}!</span> 🐾
        </h1>
        <p class="text-gray-500 text-base md:text-lg flex items-center gap-2 font-medium">
            <i class="far fa-calendar-alt text-teal-500"></i>
            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <div class="flex items-center gap-4 w-full lg:w-auto" x-data="{ showNotifications: false }">
        <div class="flex items-center justify-between bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 w-full lg:w-auto relative group transition-all hover:shadow-md">
            <span class="text-gray-600 font-semibold mr-4 hidden sm:inline">Notifikasi</span>
            <div class="relative" @click.away="showNotifications = false">
                <button @click="showNotifications = !showNotifications" class="relative p-2 rounded-xl hover:bg-gray-50 transition-colors focus:outline-none">
                    <i class="fas fa-bell text-2xl text-teal-600 cursor-pointer"></i>
                    @if($notifCount > 0)
                    <span id="notification-badge" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md ring-2 ring-white animate-bounce">
                        {{ $notifCount }}
                    </span>
                    @endif
                </button>

                {{-- Notification Dropdown --}}
                <div x-show="showNotifications"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-4 w-[calc(100vw-2rem)] sm:w-96 bg-white rounded-2xl shadow-2xl z-[60] border border-gray-100 overflow-hidden"
                    style="display: none;">
                    <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Notifikasi Terbaru</h3>
                        @if($notifCount > 0)
                        <button class="text-xs text-teal-600 hover:underline font-semibold flex items-center gap-1" onclick="markAllAsRead()">
                            <i class="fas fa-check-double text-[10px]"></i> Tandai semua
                        </button>
                        @endif
                    </div>
                    <div id="notification-list" class="max-h-[400px] overflow-y-auto">
                        @forelse($recentNotifications as $notification)
                        <div class="p-4 border-b border-gray-50 hover:bg-teal-50/30 transition-all cursor-pointer {{ !$notification->is_read ? 'bg-blue-50/40' : '' }}"
                            onclick="markSingleAsRead('{{ $notification->id }}', this)">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100">
                                    <i class="fas fa-bell text-teal-500"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm text-gray-800 truncate">{{ $notification->title }}</h4>
                                    <p class="text-xs text-gray-600 line-clamp-2 mt-0.5">{{ $notification->message }}</p>
                                    <p class="text-[10px] text-gray-400 mt-2"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center text-gray-400 text-sm">Belum ada notifikasi</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-10">
    @php
    $stats = [
    ['icon' => 'fa-calendar-check', 'title' => 'Booking Bln Ini', 'value' => $totalBookingBulanIni, 'bg' => 'from-teal-500 to-emerald-600'],
    ['icon' => 'fa-paw', 'title' => 'Hewan Inap', 'value' => $hewanDititipkanSekarang, 'bg' => 'from-rose-500 to-pink-600'],
    ['icon' => 'fa-stethoscope', 'title' => 'Konsultasi Selesai', 'value' => $selesaiKonsultasi, 'bg' => 'from-amber-400 to-orange-500'],
    ['icon' => 'fa-sack-dollar', 'title' => 'Pendapatan Bln Ini', 'value' => 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'), 'bg' => 'from-indigo-500 to-blue-600', 'is_price' => true],
    ['icon' => 'fa-chart-pie', 'title' => 'Occupancy Rate', 'value' => $occupancyRate . '%', 'bg' => 'from-fuchsia-500 to-purple-600'],
    ['icon' => 'fa-comment-dots', 'title' => 'Testimoni Baru', 'value' => $testimoniBaru, 'bg' => 'from-sky-400 to-cyan-500'],
    ];
    @endphp

    @foreach($stats as $stat)
    <div class="group bg-gradient-to-br {{ $stat['bg'] }} p-5 rounded-[2rem] shadow-lg transition-all duration-300 hover:-translate-y-2 relative overflow-hidden">
        <div class="relative z-10 flex flex-col h-full">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center mb-4 ring-1 ring-white/30">
                <i class="fas {{ $stat['icon'] }} text-white text-lg"></i>
            </div>
            <p class="text-white/80 text-[10px] font-bold uppercase tracking-wider mb-1">{{ $stat['title'] }}</p>
            <p class="text-white font-black {{ isset($stat['is_price']) ? 'text-base' : 'text-2xl' }} truncate">
                {{ $stat['value'] }}
            </p>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart Section --}}
<div class="grid grid-cols-1 gap-8 mb-10">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 md:p-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Revenue Analytics</h2>
                <p class="text-gray-400 text-sm mt-1">Monitoring performa berdasarkan periode</p>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-2 p-1.5 bg-gray-50 rounded-2xl border border-gray-100 w-full md:w-auto">
                <select name="type" class="text-xs font-bold border-none bg-white rounded-xl py-2.5 px-4 shadow-sm cursor-pointer">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Semua Layanan (Rp)</option>
                    <option value="booking" {{ request('type') == 'booking' ? 'selected' : '' }}>Penitipan (Rp)</option>
                    <option value="konsultasi" {{ request('type') == 'konsultasi' ? 'selected' : '' }}>Konsultasi (Data)</option>
                </select>

                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-gray-100 shadow-sm">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="text-[10px] font-bold border-none p-0 focus:ring-0 text-gray-600">
                    <span class="text-gray-300">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="text-[10px] font-bold border-none p-0 focus:ring-0 text-gray-600">
                </div>

                <button type="submit" class="bg-teal-600 text-white w-10 h-10 rounded-xl hover:bg-teal-700 flex items-center justify-center shadow-md shadow-teal-200">
                    <i class="fas fa-filter text-xs"></i>
                </button>
            </form>
        </div>

        <div class="relative w-full overflow-hidden" style="height: 400px;">
            {{-- DATA SINKRONISASI DENGAN CONTROLLER --}}
            <canvas id="revenueChart"
                data-unit="{{ $chartUnit }}"
                data-labels='@json($revenueLabels)'
                data-values='@json($revenueValues)'>
            </canvas>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50">
        <h2 class="text-2xl font-bold text-gray-800">Aktivitas Terbaru</h2>
    </div>
    <div class="overflow-x-auto p-6">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-[11px] font-extrabold uppercase tracking-widest border-b border-gray-100">
                    <th class="pb-6 px-4">Tipe</th>
                    <th class="pb-6 px-4">Pelanggan</th>
                    <th class="pb-6 px-4">Waktu</th>
                    <th class="pb-6 px-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentActivity as $row)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-6 px-4">
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-gray-100 text-gray-600 uppercase">{{ $row['tipe'] }}</span>
                    </td>
                    <td class="py-6 px-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-800">{{ $row['pelanggan'] }}</span>
                            <span class="text-xs text-gray-400">{{ $row['kode'] }}</span>
                        </div>
                    </td>
                    <td class="py-6 px-4 text-xs font-semibold text-gray-500">
                        {{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}
                    </td>
                    <td class="py-6 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase bg-teal-50 text-teal-600 ring-1 ring-teal-500/20">
                            {{ $row['status'] }}
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartEl = document.getElementById('revenueChart');
        if (!chartEl) return;

        // Ambil data dari atribut data HTML yang diparsing oleh Laravel
        const unit = chartEl.getAttribute('data-unit'); // 'Rupiah' atau 'Jumlah'
        const labelsData = JSON.parse(chartEl.getAttribute('data-labels') || '[]');
        const valuesData = JSON.parse(chartEl.getAttribute('data-values') || '[]');

        const ctx = chartEl.getContext('2d');
        const gradientFill = ctx.createLinearGradient(0, 0, 0, 400);
        gradientFill.addColorStop(0, 'rgba(13, 148, 136, 0.25)');
        gradientFill.addColorStop(1, 'rgba(13, 148, 136, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: unit === 'Rupiah' ? 'Total Pendapatan' : 'Jumlah Konsultasi',
                    data: valuesData,
                    borderColor: '#0d9488',
                    borderWidth: 3,
                    backgroundColor: gradientFill,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (unit === 'Rupiah') {
                                    return label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label + ': ' + context.parsed.y + ' data';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (unit === 'Rupiah') {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                    return 'Rp ' + value;
                                }
                                return value + ' data';
                            }
                        }
                    }
                }
            }
        });
    });

    function markAllAsRead() {
        fetch('{{ route("admin.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }

    function markSingleAsRead(id, el) {
        fetch(`/admin/notifications/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
</script>

<style>
    .gradient-text {
        background: linear-gradient(90deg, #0d9488, #2dd4bf);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush