@extends('layouts.dokter')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-10" data-aos="fade-down">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-id-badge mr-3 text-teal-600"></i>
                    Panel Antrean Dokter
                </h1>
                <p class="text-gray-600 mt-2 italic">Pantau kunjungan fisik dan catat rekam medis anabul secara digital.</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative" id="notification-container">
                    <button id="notification-button" class="relative bg-white p-3 rounded-2xl shadow-sm border border-gray-100 focus:outline-none hover:bg-gray-50 transition-all">
                        <i class="fas fa-bell text-2xl text-teal-600"></i>
                        @if($unreadCount > 0)
                        <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md animate-bounce">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notification-dropdown"
                        class="fixed right-8 top-24 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl z-[9999] border border-gray-100 max-h-[32rem] overflow-y-auto hidden">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-teal-50 rounded-t-2xl">
                            <h3 class="text-sm font-bold text-teal-800">Pemberitahuan Konsultasi</h3>
                            @if($unreadCount > 0)
                            <button onclick="markAllAsRead()" class="text-[10px] text-teal-600 hover:underline font-bold uppercase">Tandai Semua</button>
                            @endif
                        </div>
                        <div id="notification-list" class="divide-y divide-gray-50">
                            @forelse($notifications as $notif)
                            <div class="p-4 hover:bg-gray-50 transition cursor-pointer {{ !$notif->is_read ? 'bg-teal-50/30' : '' }}"
                                onclick="markAsRead({{ $notif->id }})">
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
                                    <div class="w-2 h-2 bg-teal-500 rounded-full mt-2"></div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-400">
                                <i class="fas fa-bell-slash text-3xl mb-2 text-gray-200"></i>
                                <p class="text-xs">Belum ada aktivitas konsultasi</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
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

    <section class="mb-12">
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
                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $konsul->nama_pemilik }}</h3>
                    <p class="text-sm text-teal-600 font-semibold mb-3">🐾 {{ $konsul->jenis_hewan }} - <span class="text-gray-600 font-normal">{{ $konsul->topik }}</span></p>

                    <div class="bg-gray-50 p-3 rounded-xl mb-6 text-xs space-y-2">
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

    <section>
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
                    <h3 class="text-lg font-bold text-gray-800">{{ $konsul->nama_pemilik }}</h3>
                    <p class="text-sm text-pink-600 mb-4 font-medium"><i class="fas fa-paw mr-1"></i> {{ $konsul->jenis_hewan }}</p>

                    <div class="border-t border-gray-100 pt-4 mt-2">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notification-button');
        const notifDropdown = document.getElementById('notification-dropdown');

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('notification-container').contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });

        setInterval(checkDoctorNotifications, 30000);
    });

    async function checkDoctorNotifications() {
        try {
            const response = await fetch('{{ route("dokter.notifikasi.get-new") }}');
            const data = await response.json();
            const badge = document.getElementById('notification-badge');

            if (data.success) {
                if (data.unreadCount > 0) {
                    if (badge) {
                        badge.innerText = data.unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        // Jika badge belum ada di DOM (tadinya 0), reload untuk memunculkan element
                        location.reload();
                    }
                } else if (badge) {
                    badge.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    async function markAsRead(id) {
        try {
            const response = await fetch(`/dokter/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                // Refresh ringan atau manipulasi DOM per item bisa dilakukan di sini
                location.reload();
            }
        } catch (error) {
            console.error(error);
        }
    }

    // FUNGSI TANDAI SEMUA (HANYA SATU DEFINISI)
    async function markAllAsRead(event) {
        if (event) event.preventDefault(); // Mencegah reload halaman otomatis dari link

        const btnMarkAll = document.getElementById('btn-mark-all') || (event ? event.currentTarget : null);

        try {
            const response = await fetch('{{ route("dokter.notifikasi.read-all") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // 1. Sembunyikan badge angka merah secara instan
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    badge.classList.add('hidden');
                    badge.innerText = '0';
                }

                // 2. Hilangkan styling background biru (unread)
                document.querySelectorAll('#notification-list > div').forEach(item => {
                    item.classList.remove('bg-teal-50/30');
                });

                // 3. Hapus titik indikator
                document.querySelectorAll('.unread-dot, .w-2.h-2.bg-teal-500').forEach(dot => {
                    dot.remove();
                });

                // 4. Sembunyikan tombol "Tandai Semua"
                if (btnMarkAll) btnMarkAll.classList.add('hidden');

                console.log("Dashboard: Notifikasi konsultasi dibersihkan.");
            }
        } catch (error) {
            console.error("Gagal menandai semua:", error);
        }
    }
</script>

<style>
    #notification-dropdown::-webkit-scrollbar {
        width: 4px;
    }

    #notification-dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #notification-dropdown::-webkit-scrollbar-thumb {
        background: #0d9488;
        border-radius: 10px;
    }
</style>
@endsection