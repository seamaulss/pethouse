@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    {{-- HEADER SECTION: Judul di Kiri, Notifikasi di Kanan --}}
    <div class="relative z-50 flex flex-row justify-between items-center mb-10 gap-4" data-aos="fade-down">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
                <i class="fas fa-dog mr-2 text-teal-600 hidden md:inline-block"></i>
                Dashboard <span class="text-teal-600">Petugas</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 font-medium italic">
                Sistem Monitoring & Daily Log PetHouse
            </p>
        </div>

        {{-- PETUGAS ACTION BOX: Untuk Check-in User Baru --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8" data-aos="fade-up">
            <div class="bg-gradient-to-br from-teal-500 to-teal-700 p-6 rounded-[2rem] shadow-lg shadow-teal-200 text-white flex items-center justify-between group cursor-pointer overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold">Check-in Tamu</h3>
                    <p class="text-xs text-teal-100 opacity-80">Scan QR Code Invoice dari pelanggan</p>
                    <button onclick="openScanner()" class="mt-3 bg-white text-teal-600 px-6 py-2 rounded-xl text-xs font-black uppercase hover:bg-teal-50 transition-all shadow-md">
                        <i class="fas fa-qrcode mr-2"></i> Mulai Scan
                    </button>
                </div>
                <i class="fas fa-camera text-6xl opacity-20 absolute -right-4 -bottom-4 transform group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Kode Booking</label>
                <form action="{{ route('petugas.booking.search') }}" method="GET" class="relative">
                    <input type="text" name="kode_booking" placeholder="Contoh: BK-2024..."
                        class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-6 pr-12 focus:ring-2 focus:ring-teal-500 font-bold text-gray-700">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition shadow-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div id="scannerModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden shadow-2xl">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Scan QR Code Invoice</h3>
                    <button onclick="closeScanner()" class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div id="reader" class="overflow-hidden rounded-2xl bg-gray-100"></div>
                </div>
                <div class="p-6 bg-gray-50 text-center">
                    <p class="text-xs text-gray-500 font-medium">Arahkan kamera ke QR Code yang ada di Invoice pelanggan</p>
                </div>
            </div>
        </div>

        {{-- Dropdown Notifikasi --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="relative p-3 sm:p-4 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 text-gray-600 hover:text-teal-600 hover:border-teal-300 hover:shadow-md transition-all focus:outline-none">
                <i class="fas fa-bell text-xl sm:text-2xl"></i>

                @if(isset($unreadCount) && $unreadCount > 0)
                <span class="absolute -top-1 -right-1 sm:top-2 sm:right-2 flex h-5 w-5 sm:h-6 sm:w-6 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white border-2 border-white animate-bounce">
                    {{ $unreadCount }}
                </span>
                @endif
            </button>

            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-3 w-[85vw] sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-[100]"
                style="display: none;">

                <div class="bg-teal-600 px-5 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold flex items-center text-sm uppercase tracking-wider">
                        <i class="fas fa-bullhorn mr-2 text-teal-200"></i> Pemberitahuan
                    </h3>
                    <a href="{{ route('petugas.notifications.index') }}" class="text-[10px] bg-teal-700 text-white px-2 py-1 rounded hover:bg-teal-800 transition">
                        Lihat Semua
                    </a>
                </div>

                <div class="max-h-96 overflow-y-auto divide-y divide-gray-50 bg-white">
                    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                    @foreach($recentNotifications as $notif)
                    @php
                    $config = match($notif->type) {
                    'assignment' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'fa' => 'fa-user-plus'],
                    'status' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'fa' => 'fa-sync-alt'],
                    'extend' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'fa' => 'fa-clock'],
                    'completed' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'fa' => 'fa-check-double'],
                    'cancel' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'fa' => 'fa-calendar-times'],
                    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'fa' => 'fa-bell'],
                    };
                    @endphp
                    <div class="p-4 hover:bg-gray-50 transition flex items-start {{ !$notif->is_read ? 'bg-blue-50/50 border-l-4 border-teal-500' : '' }}">
                        <div class="flex-shrink-0 mr-3">
                            <span class="w-10 h-10 rounded-xl flex items-center justify-center {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                <i class="fas {{ $config['fa'] }}"></i>
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h4 class="text-xs sm:text-sm font-bold text-gray-800 truncate">{{ $notif->title }}</h4>
                                <span class="text-[9px] text-gray-400 font-medium italic">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[11px] sm:text-[12px] text-gray-600 mt-1 line-clamp-2">{{ $notif->message }}</p>
                            @if(!$notif->is_read)
                            <a href="{{ route('petugas.notifications.markAsRead', $notif->id) }}" class="inline-block mt-2 text-[10px] font-bold text-teal-600 hover:underline">
                                Tandai telah dibaca
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="py-12 text-center">
                        <i class="fas fa-bell-slash text-gray-200 text-5xl mb-3"></i>
                        <p class="text-gray-400 text-sm italic font-medium">Belum ada tugas baru.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION DAFTAR HEWAN --}}
    <div class="mb-8 border-b border-gray-100 pb-4" data-aos="fade-right">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center">
            <span class="w-2 h-8 bg-teal-500 rounded-full mr-3"></span>
            Antrean Monitoring Hewan
        </h2>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($bookings as $index => $booking)
        <div class="group bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
            data-aos="fade-up"
            data-aos-delay="{{ 100 * ($index + 1) }}">

            <div class="p-6 sm:p-8">
                {{-- GANTI BAGIAN ATAS CARD (Di dalam foreach bookings) --}}
                <div class="flex justify-between items-start mb-6">
                    <div class="p-4 bg-teal-50 rounded-2xl group-hover:bg-teal-600 transition-colors">
                        <i class="fas fa-paw text-2xl text-teal-600 group-hover:text-white"></i>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="bg-gray-50 text-gray-500 px-3 py-1 rounded-full text-[10px] font-bold border border-gray-100 tracking-widest uppercase">
                            {{ $booking->kode_booking }}
                        </span>
                        {{-- Status DP --}}
                        @if($booking->dp_dibayar == 'Ya')
                        <span class="bg-green-100 text-green-600 text-[9px] px-2 py-0.5 rounded-md font-black italic">LUNAS</span>
                        @else
                        <span class="bg-red-100 text-red-600 text-[9px] px-2 py-0.5 rounded-md font-black italic">BELUM BAYAR</span>
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-2xl font-black text-gray-800 mb-1 capitalize group-hover:text-teal-600 transition-colors">
                        {{ $booking->nama_hewan }}
                    </h3>
                    <div class="flex items-center text-gray-500 font-medium">
                        <span class="text-sm px-2 py-0.5 bg-pink-50 text-pink-600 rounded-md mr-2">{{ $booking->jenis_hewan }}</span>
                        <span class="text-xs">• PetHouse Guest</span>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-8">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-amber-500 shadow-sm mr-3">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Pemilik</p>
                        <p class="text-sm font-bold text-gray-700 line-clamp-1">{{ $booking->nama_pemilik }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @php
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (str_starts_with($wa_clean, '0')) {
                    $wa_clean = '62' . substr($wa_clean, 1);
                    } elseif (!str_starts_with($wa_clean, '62')) {
                    $wa_clean = '62' . $wa_clean;
                    }
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20Bapak/Ibu%20{{ urlencode($booking->nama_pemilik) }},%20ini%20update%20dari%20PetHouse%20untuk%20{{ urlencode($booking->nama_hewan) }}"
                        target="_blank"
                        class="flex flex-col items-center justify-center py-4 bg-green-500 text-white rounded-2xl hover:bg-green-600 transition shadow-lg shadow-green-100">
                        <i class="fab fa-whatsapp text-xl mb-1"></i>
                        <span class="text-[10px] font-black uppercase">Chat</span>
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="flex flex-col items-center justify-center py-4 bg-teal-600 text-white rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-100">
                        <i class="fas fa-clipboard-check text-xl mb-1"></i>
                        <span class="text-[10px] font-black uppercase">Input Log</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-[3rem] p-12 sm:p-20 text-center shadow-sm border-2 border-dashed border-gray-100" data-aos="zoom-in">
        <div class="relative inline-block mb-8">
            <span class="text-8xl">🏘️</span>
            <span class="absolute -bottom-2 -right-2 text-4xl animate-bounce">🐾</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Semua Kandang Terisi?</h3>
        <p class="text-gray-400 max-w-sm mx-auto font-medium">
            Saat ini tidak ada hewan yang membutuhkan monitoring. Silakan istirahat atau hubungi admin jika terjadi kendala.
        </p>
    </div>
    @endif

</div>

{{-- SCRIPT: Polling Notifikasi & Web Interactivity --}}
<script>
    // Polling setiap 30 detik
    setInterval(checkPetugasNotifications, 30000);

    async function checkPetugasNotifications() {
        try {
            const response = await fetch('{{ route("petugas.notifications.get-new") }}');
            const data = await response.json();

            if (data.success && data.notifications.length > 0) {
                const badge = document.querySelector('.absolute.-top-1');
                if (badge) {
                    badge.textContent = data.unreadCount;
                    badge.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Notification Error:', error);
        }
    }

    let html5QrCode;

    function openScanner() {
        // Tampilkan Modal
        document.getElementById('scannerModal').classList.remove('hidden');
        document.getElementById('scannerModal').classList.add('flex');

        // Inisialisasi Scanner
        html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            },
            aspectRatio: 1.0
        };

        // Mulai Kamera (Facing Mode Environment = Kamera Belakang)
        html5QrCode.start({
                facingMode: "environment"
            },
            config,
            // ... di dalam callback html5QrCode.start ...
            (decodedText) => {
                stopScanner();

                // Gunakan helper route() agar Laravel memberikan URL: /petugas/verifikasi-checkin
                const targetUrl = "{{ route('petugas.booking.search') }}";

                // Redirect ke URL verifikasi
                window.location.href = `${targetUrl}?kode_booking=${encodeURIComponent(decodedText)}`;
            },
            (errorMessage) => {
                // Abaikan error saat mencari QR
            }
        ).catch((err) => {
            alert("Gagal mengakses kamera: " + err);
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                closeScanner();
            }).catch((err) => console.error("Gagal stop scanner", err));
        }
    }

    function closeScanner() {
        document.getElementById('scannerModal').classList.add('hidden');
        document.getElementById('scannerModal').classList.remove('flex');
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    }
</script>
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
    /* Custom Scrollbar for Dropdown */
    .max-h-96::-webkit-scrollbar {
        width: 5px;
    }

    .max-h-96::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .max-h-96::-webkit-scrollbar-thumb {
        background: #0d9488;
        border-radius: 10px;
    }

    .card-hewan:hover .fa-paw {
        transform: rotate(20deg) scale(1.2);
        transition: all 0.3s ease;
    }
</style>
@endsection