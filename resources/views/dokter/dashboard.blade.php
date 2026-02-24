@extends('layouts.dokter')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 relative z-20">

    {{-- HEADER UTAMA --}}
    <div class="mb-10 relative z-50" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-id-badge mr-3 text-teal-600"></i>
                    Panel Antrean Dokter
                </h1>
                <p class="text-gray-600 mt-2 italic">Pantau kunjungan fisik dan catat rekam medis anabul secara digital.</p>
            </div>

            <div class="flex items-center gap-4">
                {{-- NOTIFIKASI CONTAINER (Logika Admin) --}}
                <div class="relative" id="notification-container" x-data="{ open: false }">
                    <button @click="open = !open" id="notification-button" class="relative bg-white p-3 rounded-2xl shadow-sm border border-gray-100 focus:outline-none hover:bg-gray-50 transition-all">
                        <i class="fas fa-bell text-2xl text-teal-600"></i>
                        @if($unreadCount > 0)
                        <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md animate-pulse">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    {{-- DROPDOWN --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl z-[100] border border-gray-100 max-h-[32rem] overflow-y-auto hidden"
                        :class="{'hidden': !open}">
                        
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-teal-50 rounded-t-2xl sticky top-0 z-10">
                            <h3 class="text-sm font-bold text-teal-800">Pemberitahuan Konsultasi</h3>
                            @if($unreadCount > 0)
                            <button onclick="markAllAsRead(event)" id="mark-all-read-btn" class="text-[10px] text-teal-600 hover:underline font-bold uppercase transition-colors">
                                <i class="fas fa-check-double mr-1"></i>Tandai Semua
                            </button>
                            @endif
                        </div>

                        <div id="notification-list" class="divide-y divide-gray-50 bg-white">
                            @forelse($notifications as $notif)
                            <div class="notification-item p-4 hover:bg-gray-50 transition cursor-pointer {{ !$notif->is_read ? 'bg-teal-50/30' : '' }}"
                                data-id="{{ $notif->id }}"
                                onclick="markSingleAsRead({{ $notif->id }}, this)">
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-notes-medical text-teal-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $notif->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-2">
                                            <i class="far fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notif->is_read)
                                    <div class="unread-dot w-2 h-2 bg-teal-500 rounded-full mt-2"></div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-400 bg-white">
                                <i class="fas fa-bell-slash text-3xl mb-2 text-gray-200"></i>
                                <p class="text-xs">Belum ada aktivitas konsultasi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- TANGGAL --}}
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tanggal Hari Ini</p>
                        <p class="font-bold text-gray-800">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                    <i class="fas fa-calendar-check text-teal-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN ANTREAN --}}
    <section class="mb-12 relative z-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-clock mr-3 text-amber-500"></i>
                Menunggu Konfirmasi Datang
            </h2>
            <span class="px-4 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold shadow-sm">
                {{ $pending->count() }} Antrean Baru
            </span>
        </div>

        @if($pending->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pending as $konsul)
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300" data-aos="zoom-in">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-500">#{{ $konsul->kode_konsultasi }}</span>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg italic">Booking Online</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1 text-left">{{ $konsul->nama_pemilik }}</h3>
                    <p class="text-sm text-teal-600 font-semibold mb-3 text-left">🐾 {{ $konsul->jenis_hewan }} - <span class="text-gray-600 font-normal">{{ $konsul->topik }}</span></p>

                    <div class="bg-gray-50 p-3 rounded-xl mb-6 text-xs space-y-2 text-left">
                        <p><i class="far fa-calendar-alt mr-2"></i>{{ $konsul->tanggal_janji->translatedFormat('d M Y') }}</p>
                        <p><i class="far fa-clock mr-2 text-pink-500"></i>Jam {{ date('H:i', strtotime($konsul->jam_janji)) }} WIB</p>
                    </div>

                    <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsul->id) }}">
                        @csrf
                        <input type="hidden" name="aksi" value="terima">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i> Konfirmasi Kedatangan
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
            <p class="text-gray-400 italic">Belum ada antrean baru yang perlu dikonfirmasi.</p>
        </div>
        @endif
    </section>

    {{-- KONTEN SEDANG DIPERIKSA --}}
    <section class="relative z-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-stethoscope mr-3 text-blue-600"></i>
                Pasien di Klinik (Sedang Diperiksa)
            </h2>
            <span class="px-4 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold shadow-sm">
                {{ $diterima->count() }} Sedang Proses
            </span>
        </div>

        @if($diterima->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($diterima as $konsul)
            <div class="bg-white rounded-2xl shadow-md border-2 border-blue-100 overflow-hidden hover:border-blue-300 transition duration-300" data-aos="zoom-in">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-mono bg-blue-50 px-2 py-1 rounded text-blue-500 font-bold">#{{ $konsul->kode_konsultasi }}</span>
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-ping"></span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 text-left">{{ $konsul->nama_pemilik }}</h3>
                    <p class="text-sm text-pink-600 mb-4 font-medium text-left"><i class="fas fa-paw mr-1"></i> {{ $konsul->jenis_hewan }}</p>

                    <div class="border-t border-gray-100 pt-4 mt-2 text-left">
                        <p class="text-xs text-gray-400 mb-3">Silakan periksa anabul, lalu klik tombol di bawah untuk mengisi rekam medis digital.</p>
                        <a href="{{ route('dokter.konsultasi.show', $konsul->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-md transition flex items-center justify-center">
                            <i class="fas fa-file-signature mr-2"></i> Input Rekam Medis
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
            <p class="text-gray-400 italic">Tidak ada pasien yang sedang diperiksa saat ini.</p>
        </div>
        @endif
    </section>
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    // FUNGSI TANDAI SEMUA (Identik Admin)
    async function markAllAsRead(event) {
        if (event) event.preventDefault();
        const button = document.getElementById('mark-all-read-btn');
        if (!button) return;

        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
        button.disabled = true;

        try {
            const response = await fetch('{{ route("dokter.notifikasi.read-all") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Hapus UI secara instan (State Management)
                const badge = document.getElementById('notification-badge');
                if (badge) badge.remove();
                
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('bg-teal-50/30');
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();
                });

                button.style.display = 'none';
                showToast('success', 'Semua notifikasi ditandai terbaca');
            }
        } catch (error) {
            console.error("Error:", error);
            showToast('error', 'Gagal menghubungi server');
        } finally {
            button.innerHTML = '<i class="fas fa-check-double mr-1"></i>Tandai Semua';
            button.disabled = false;
        }
    }

    // FUNGSI TANDAI SATU (Identik Admin)
    async function markSingleAsRead(id, element) {
        try {
            const response = await fetch(`/dokter/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                element.classList.remove('bg-teal-50/30');
                const dot = element.querySelector('.unread-dot');
                if (dot) dot.remove();
                
                // Update badge count
                updateBadgeCount(data.unread_count);
            }
        } catch (error) {
            console.error(error);
        }
    }

    function updateBadgeCount(count) {
        const badge = document.getElementById('notification-badge');
        if (count > 0) {
            if (badge) badge.innerText = count;
        } else {
            if (badge) badge.remove();
            const btn = document.getElementById('mark-all-read-btn');
            if (btn) btn.style.display = 'none';
        }
    }

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-6 right-6 z-[1000] px-6 py-4 rounded-xl shadow-2xl text-white transition-all duration-300 ${type === 'success' ? 'bg-teal-600' : 'bg-red-600'}`;
        toast.innerHTML = `<div class="flex items-center gap-3"><i class="fas fa-check-circle"></i><p>${message}</p></div>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }

    // Polling Interval
    setInterval(async () => {
        try {
            const response = await fetch('{{ route("dokter.notifikasi.get-new") }}');
            const data = await response.json();
            if (data.success) updateBadgeCount(data.unreadCount);
        } catch (e) {}
    }, 30000);
</script>
@endpush