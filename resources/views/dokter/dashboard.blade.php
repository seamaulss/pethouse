@extends('layouts.dokter')

@section('title', 'Dashboard Dokter')

@push('styles')
<style>
    .glass-nav {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    .card-zoom {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-zoom:hover {
        transform: translateY(-5px) scale(1.01);
    }

    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- 1. BENTO STATS HEADER --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10" data-aos="fade-down">
        <div class="md:col-span-2 bg-gradient-to-br from-teal-600 to-teal-700 rounded-3xl p-6 text-white shadow-lg shadow-teal-200 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-2xl font-bold">Halo, Dok! 👋</h1>
                <p class="text-teal-100 mt-1">Siap membantu anabul hari ini?</p>
                <div class="mt-6 flex items-center gap-2">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
            <i class="fas fa-stethoscope absolute -right-4 -bottom-4 text-9xl text-white/10 rotate-12"></i>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-sm font-bold uppercase">Antrean Baru</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-amber-500">{{ $pending->count() }}</h3>
                <div class="w-10 h-10 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-sm font-bold uppercase">Sedang Diperiksa</p>
            <div class="flex items-end justify-between">
                <h3 class="text-4xl font-black text-blue-500">{{ $diterima->count() }}</h3>
                <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                    <i class="fas fa-notes-medical"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. NOTIFIKASI FLOATING BAR (Optional, atau tetap di Navbar) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen <span class="text-teal-600">Pasien</span></h2>

        {{-- Notification Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-3 bg-white px-5 py-2.5 rounded-2xl border border-gray-200 shadow-sm hover:bg-gray-50 transition-all">
                <div class="relative">
                    <i class="fas fa-bell text-teal-600"></i>
                    @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center animate-bounce">{{ $unreadCount }}</span>
                    @endif
                </div>
                <span class="text-sm font-bold text-gray-700">Pemberitahuan</span>
                <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
            </button>

            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl z-[100] border border-gray-100 overflow-hidden">
                {{-- GANTI BAGIAN TOMBOL TANDAI BACA MENJADI SEPERTI INI --}}
                <div class="p-4 bg-teal-50 border-b border-teal-100 flex justify-between items-center">
                    <span class="text-sm font-black text-teal-800 uppercase">Aktivitas Terbaru</span>
                    @if($unreadCount > 0)
                    {{-- Gunakan BUTTON biasa saja karena prosesnya akan ditangani JavaScript Fetch --}}
                    <button type="button" onclick="markAllAsRead(event)" id="mark-all-read-btn" class="text-[10px] bg-teal-600 text-white px-2 py-1 rounded-lg font-bold hover:bg-teal-700 transition-colors">
                        Tandai Baca
                    </button>
                    @endif
                </div>
                <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                    @forelse($notifications as $notif)
                    <div class="p-4 hover:bg-teal-50/30 transition cursor-pointer {{ !$notif->is_read ? 'bg-teal-50/50' : '' }}" onclick="markSingleAsRead({{ $notif->id }}, this)">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0">
                                <i class="fas fa-file-alt text-teal-500 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800 leading-tight">{{ $notif->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notif->message }}</p>
                                <p class="text-[10px] text-teal-600 font-medium mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2 text-gray-100"></i>
                        <p class="text-xs">Tidak ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 3. GRID KONTEN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- KOLOM ANTREAN --}}
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-2 h-8 bg-amber-500 rounded-full"></div>
                <h2 class="text-xl font-black text-gray-800">Menunggu <span class="text-amber-500">Konfirmasi</span></h2>
            </div>

            @forelse($pending as $konsul)
            <div class="card-zoom bg-white rounded-3xl border border-gray-100 p-6 shadow-sm mb-4 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-full w-1.5 bg-amber-500 opacity-20 group-hover:opacity-100 transition-opacity"></div>

                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 font-black text-xl">
                            {{ substr($konsul->nama_pemilik, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 leading-tight text-lg">{{ $konsul->nama_pemilik }}</h3>
                            <span class="text-xs font-mono text-gray-400 uppercase tracking-tighter">#{{ $konsul->kode_konsultasi }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg inline-block italic">New Request</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-6 text-sm">
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium"><i class="fas fa-paw mr-1 text-teal-500"></i>{{ $konsul->jenis_hewan }}</span>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium"><i class="fas fa-clock mr-1 text-pink-500"></i>{{ date('H:i', strtotime($konsul->jam_janji)) }}</span>
                </div>

                <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsul->id) }}">
                    @csrf
                    <input type="hidden" name="aksi" value="terima">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-teal-600 text-white font-bold py-3.5 rounded-2xl shadow-lg transition-all flex items-center justify-center group/btn">
                        Konfirmasi & Panggil <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>
            @empty
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-12 text-center">
                <p class="text-gray-400 italic">Antrean sedang kosong.</p>
            </div>
            @endforelse
        </section>

        {{-- KOLOM SEDANG DIPERIKSA --}}
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-xl font-black text-gray-800">Ruang <span class="text-blue-600">Pemeriksaan</span></h2>
            </div>

            @forelse($diterima as $konsul)
            <div class="card-zoom bg-white rounded-3xl border-2 border-blue-50 p-6 shadow-xl mb-6 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center opacity-50">
                    <i class="fas fa-notes-medical text-blue-200 text-3xl"></i>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                        <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Sesi Aktif</span>
                    </div>

                    <h3 class="text-2xl font-black text-gray-800 mb-1">{{ $konsul->nama_pemilik }}</h3>
                    <p class="text-teal-600 font-bold mb-6 italic">🐾 {{ $konsul->jenis_hewan }} - <span class="text-gray-500 font-medium">{{ $konsul->topik }}</span></p>

                    <a href="{{ route('dokter.konsultasi.show', $konsul->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
                        <i class="fas fa-file-signature mr-2"></i> Tulis Rekam Medis Digital
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-blue-50/50 border-2 border-dashed border-blue-100 rounded-3xl p-12 text-center">
                <i class="fas fa-stethoscope text-4xl text-blue-100 mb-4"></i>
                <p class="text-blue-400 font-medium italic">Belum ada pasien di ruang periksa.</p>
            </div>
            @endforelse
        </section>

    </div>
</div>

<script>
    function updateNotificationDropdown() {
        // Ganti pemanggilan routenya
        fetch("{{ route('dokter.notifikasi.get-new') }}")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 1. Update Badge Angka (Merah)
                    const badge = document.querySelector('.animate-bounce');
                    if (data.unreadCount > 0) {
                        if (badge) {
                            badge.innerText = data.unreadCount;
                        } else {
                            // Jika badge belum ada, buat elemennya secara dinamis
                            const bellIcon = document.querySelector('.fa-bell').parentElement;
                            const newBadge = `<span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] h-4 w-4 rounded-full flex items-center justify-center animate-bounce">${data.unreadCount}</span>`;
                            bellIcon.insertAdjacentHTML('beforeend', newBadge);
                        }
                    } else if (badge) {
                        badge.remove();
                    }

                    // 2. Update List Notifikasi di dalam Dropdown
                    const container = document.querySelector('.max-h-96.overflow-y-auto');
                    if (data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(notif => {
                            const isUnread = notif.is_read == 0 ? 'bg-teal-50/50' : '';
                            html += `
                                <div class="p-4 hover:bg-teal-50/30 transition cursor-pointer ${isUnread}" onclick="markSingleAsRead(${notif.id}, this)">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm flex-shrink-0">
                                            <i class="fas fa-file-alt text-teal-500 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-800 leading-tight">${notif.title}</p>
                                            <p class="text-xs text-gray-500 mt-1">${notif.message}</p>
                                            <p class="text-[10px] text-teal-600 font-medium mt-1">Baru saja</p>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        container.innerHTML = html;
                    }
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Jalankan polling setiap 30 detik
    setInterval(updateNotificationDropdown, 30000);

    // 1. Fungsi Tandai Satu Notifikasi Sebagai Baca
    // Fungsi untuk satu notifikasi
    function markSingleAsRead(id, element) {
        fetch(`/dokter/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Fungsi untuk semua notifikasi
    function markAllAsRead(event) {
        if (event) {
            event.preventDefault(); // Mencegah form submit tradisional
            event.stopPropagation(); // Mencegah dropdown tertutup seketika
        }

        fetch("{{ route('dokter.notifikasi.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json', // Tambahkan ini
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // REFRESH HALAMAN agar angka notif di badge merah hilang
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Tetap refresh jika terjadi error untuk sinkronisasi ulang data
                window.location.reload();
            });
    }
</script>
@endsection