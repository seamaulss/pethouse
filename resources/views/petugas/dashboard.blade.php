@extends('petugas.layouts.app')

@section('title', 'Petugas - Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    {{-- HEADER SECTION: Z-Index ditingkatkan agar dropdown melayang di atas fitur lain --}}
    <div class="relative z-[100] flex flex-row items-center justify-between mb-8 gap-4" data-aos="fade-down">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                <i class="fas fa-dog mr-2 text-teal-600 hidden sm:inline-block"></i>
                Dashboard <span class="text-teal-600">Petugas</span>
            </h1>
            <p class="text-[10px] sm:text-sm text-gray-500 mt-1 font-medium flex items-center">
                <span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></span>
                Sistem Monitoring & Daily Log PetHouse
            </p>
        </div>

        {{-- Dropdown Notifikasi --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="relative p-3 sm:p-4 bg-white rounded-2xl shadow-sm border border-gray-200 text-gray-600 hover:text-teal-600 hover:border-teal-300 transition-all focus:outline-none">
                <i class="fas fa-bell text-xl sm:text-2xl"></i>
                @if(isset($unreadCount) && $unreadCount > 0)
                <span class="absolute top-2 right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white border-2 border-white animate-bounce">
                    {{ $unreadCount }}
                </span>
                @endif
            </button>

            {{-- Dropdown Panel: Menggunakan origin-top-right dan z-index maksimal --}}
            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-3 w-[85vw] sm:w-96 bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden z-[110] origin-top-right"
                style="display: none;">

                <div class="bg-teal-600 px-5 py-4 flex justify-between items-center">
                    <h3 class="text-white font-bold text-xs uppercase tracking-widest flex items-center">
                        <i class="fas fa-bullhorn mr-2"></i> Notifikasi
                    </h3>
                    <a href="{{ route('petugas.notifications.index') }}" class="text-[10px] bg-white/20 text-white px-3 py-1 rounded-full hover:bg-white/40 transition font-bold">
                        Lihat Semua
                    </a>
                </div>

                <div class="max-h-[30rem] overflow-y-auto divide-y divide-gray-50">
                    @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                    @foreach($recentNotifications as $notif)
                    @php
                    $config = match($notif->type) {
                    'assignment' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'fa' => 'fa-user-plus'],
                    'status' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'fa' => 'fa-sync-alt'],
                    'completed' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'fa' => 'fa-check-double'],
                    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'fa' => 'fa-bell'],
                    };
                    @endphp
                    <div class="p-4 hover:bg-gray-50 transition flex items-start {{ !$notif->is_read ? 'bg-teal-50/40 border-l-4 border-teal-500' : '' }}">
                        <div class="flex-shrink-0 mr-3">
                            <span class="w-10 h-10 rounded-xl flex items-center justify-center {{ $config['bg'] }} {{ $config['text'] }}">
                                <i class="fas {{ $config['fa'] }}"></i>
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="text-sm font-bold text-gray-800 truncate">{{ $notif->title }}</h4>
                                <span class="text-[9px] text-gray-400 font-medium whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                            @if(!$notif->is_read)
                            <a href="{{ route('petugas.notifications.markAsRead', $notif->id) }}" class="inline-block mt-2 text-[10px] font-bold text-teal-600 hover:text-teal-800 underline decoration-teal-200">
                                Tandai dibaca
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="py-16 text-center">
                        <i class="fas fa-bell-slash text-gray-200 text-5xl mb-3"></i>
                        <p class="text-gray-400 text-sm italic">Belum ada tugas baru.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BOX SECTION: Menggunakan z-10 agar tetap di bawah dropdown --}}
    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12" data-aos="fade-up">
        <div class="bg-gradient-to-br from-teal-500 to-teal-700 p-8 rounded-3xl shadow-xl shadow-teal-100 text-white flex items-center justify-between group cursor-pointer relative overflow-hidden">
            <div class="relative z-20">
                <h3 class="text-xl font-bold mb-1 uppercase tracking-tight">Check-in Tamu</h3>
                <p class="text-sm text-teal-100 opacity-90 font-medium">Scan QR Code Invoice untuk verifikasi</p>
                <button onclick="openScanner()" class="mt-5 bg-white text-teal-600 px-8 py-3 rounded-2xl text-xs font-black uppercase hover:shadow-lg transition-all active:scale-95 flex items-center">
                    <i class="fas fa-qrcode mr-2 text-sm"></i> Mulai Scan
                </button>
            </div>
            <i class="fas fa-camera text-8xl opacity-10 absolute -right-4 -bottom-4 transform group-hover:scale-110 group-hover:-rotate-12 transition-all duration-700"></i>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Manual Search</label>
            <form action="{{ route('petugas.booking.search') }}" method="GET" class="relative group">
                <input type="text" name="kode_booking" placeholder="Masukkan Kode Booking..."
                    class="w-full bg-gray-50 border-2 border-transparent rounded-2xl py-4 pl-6 pr-14 focus:ring-0 focus:border-teal-500 focus:bg-white transition-all font-bold text-gray-700 placeholder-gray-300 shadow-inner">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-12 h-12 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-all flex items-center justify-center">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- SECTION DAFTAR HEWAN --}}
    <div class="flex items-center mb-8" data-aos="fade-right">
        <div class="w-2 h-8 bg-teal-500 rounded-full mr-4"></div>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Antrean Monitoring</h2>
    </div>

    @if($bookings->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($bookings as $index => $booking)
        <div class="group bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500"
            data-aos="fade-up"
            data-aos-delay="{{ 50 * ($index + 1) }}">

            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center group-hover:bg-teal-600 transition-colors duration-500">
                        <i class="fas fa-paw text-2xl text-teal-600 group-hover:text-white transition-all"></i>
                    </div>
                    <div class="flex flex-col items-end gap-2 text-right">
                        <span class="bg-gray-50 text-gray-400 px-3 py-1 rounded-lg text-[10px] font-black border border-gray-100 uppercase tracking-tighter">
                            {{ $booking->kode_booking }}
                        </span>
                        @if($booking->dp_dibayar == 'Ya')
                        <span class="bg-green-100 text-green-600 text-[9px] px-2 py-0.5 rounded font-black italic">LUNAS</span>
                        @else
                        <span class="bg-red-50 text-red-500 text-[9px] px-2 py-0.5 rounded font-black italic">BELUM BAYAR</span>
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-2xl font-black text-gray-800 mb-1 capitalize tracking-tight">{{ $booking->nama_hewan }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] px-2.5 py-1 bg-pink-100 text-pink-600 rounded-md font-bold uppercase tracking-wide">{{ $booking->jenis_hewan }}</span>
                        <span class="text-gray-300 text-xs">•</span>
                        <span class="text-xs text-gray-400 font-medium italic">PetHouse Guest</span>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gray-50 rounded-2xl mb-8">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-amber-500 shadow-sm mr-4 flex-shrink-0">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="min-w-0 overflow-hidden">
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Pemilik</p>
                        <p class="text-sm font-bold text-gray-700 truncate">{{ $booking->nama_pemilik }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @php
                    $wa_clean = preg_replace('/[^\d]/', '', $booking->nomor_wa);
                    if (str_starts_with($wa_clean, '0')) {
                    $wa_clean = '62' . substr($wa_clean, 1);
                    } elseif (!str_starts_with($wa_clean, '62')) {
                    $wa_clean = '62' . $wa_clean;
                    }
                    @endphp
                    <a href="https://wa.me/{{ $wa_clean }}?text=Halo%20{{ urlencode($booking->nama_pemilik) }},%20ini%20update%20dari%20PetHouse"
                        target="_blank"
                        class="flex items-center justify-center gap-2 py-4 bg-[#25D366] text-white rounded-2xl hover:brightness-110 transition-all font-black text-xs uppercase shadow-lg shadow-green-100 active:scale-95">
                        <i class="fab fa-whatsapp text-lg"></i> Chat
                    </a>

                    <a href="{{ route('petugas.input-log.show', $booking->id) }}"
                        class="flex items-center justify-center gap-2 py-4 bg-teal-600 text-white rounded-2xl hover:bg-teal-700 transition-all font-black text-xs uppercase shadow-lg shadow-teal-100 active:scale-95">
                        <i class="fas fa-clipboard-check text-lg"></i> Log
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-[3rem] p-16 sm:p-24 text-center shadow-sm border-2 border-dashed border-gray-100" data-aos="zoom-in">
        <div class="relative inline-block mb-10">
            <span class="text-8xl block transform hover:rotate-12 transition-transform cursor-default">🏘️</span>
            <span class="absolute -bottom-2 -right-2 text-4xl animate-bounce">🐾</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-3 uppercase tracking-tight">Belum Ada Hewan</h3>
        <p class="text-gray-400 max-w-sm mx-auto font-medium">Saat ini antrean kosong. Pastikan kembali setelah ada reservasi baru.</p>
    </div>
    @endif

</div>

{{-- MODAL SCANNER --}}
<div id="scannerModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-gray-900/80 backdrop-blur-md p-4 shadow-2xl">
    <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden transition-all duration-300">
        <div class="p-8 border-b flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">QR Scanner</h3>
                <p class="text-xs text-gray-500 font-medium">Scan barcode invoice pelanggan</p>
            </div>
            <button onclick="closeScanner()" class="w-10 h-10 flex items-center justify-center bg-white rounded-full text-gray-400 hover:text-red-500 transition shadow-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 bg-white">
            <div id="reader" class="overflow-hidden rounded-3xl bg-gray-50 border-2 border-gray-100"></div>
        </div>
        <div class="px-8 pb-8 pt-2 text-center">
            <div class="bg-teal-50 text-teal-700 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center">
                <i class="fas fa-lightbulb mr-2"></i> Pastikan QR Code berada di tengah kotak
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // Polling setiap 30 detik
    setInterval(checkPetugasNotifications, 30000);

    async function checkPetugasNotifications() {
        try {
            // Menggunakan name route yang sesuai dengan struktur grup di web.php
            const response = await fetch('{{ route("petugas.notifications.get-new") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            if (data.success && data.unreadCount !== undefined) {
                const badge = document.querySelector('.absolute.top-2');
                if (badge) {
                    if (data.unreadCount > 0) {
                        badge.textContent = data.unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
        } catch (error) {
            console.warn('Notification update failed (Silent)');
        }
    }

    let html5QrCode;

    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        document.getElementById('scannerModal').classList.add('flex');
        html5QrCode = new Html5Qrcode("reader");
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            },
            aspectRatio: 1.0
        };
        html5QrCode.start({
                facingMode: "environment"
            }, config,
            (decodedText) => {
                stopScanner();
                const targetUrl = "{{ route('petugas.booking.search') }}";
                window.location.href = `${targetUrl}?kode_booking=${encodeURIComponent(decodedText)}`;
            },
            (errorMessage) => {}
        ).catch((err) => {
            alert("Gagal mengakses kamera: " + err);
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                closeScanner();
            }).catch((err) => console.error(err));
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

<style>
    /* Custom Scrollbar for Notifications */
    .max-h-\[30rem\]::-webkit-scrollbar {
        width: 4px;
    }

    .max-h-\[30rem\]::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .max-h-\[30rem\]::-webkit-scrollbar-thumb:hover {
        background: #0d9488;
    }

    /* Z-Index Fix: Menjaga Header tetap paling atas secara visual */
    header,
    .relative.z-\[100\] {
        z-index: 100 !important;
    }
</style>
@endsection