@extends('admin.layout')

@section('title', 'Admin - Data Booking')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-10" data-aos="fade-down">
        <h1 class="text-4xl font-bold text-gray-800">Data Booking</h1>
        <p class="text-lg text-gray-600 mt-2">Kelola semua pemesanan penitipan hewan kesayangan</p>
    </div>

    <!-- Search Bar -->
    <div class="max-w-lg mb-8" data-aos="fade-up">
        <form method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode booking, nama pemilik, atau nama hewan..."
                class="w-full pl-12 pr-6 py-4 bg-white rounded-3xl shadow-lg focus:outline-none focus:ring-4 focus:ring-teal-200 text-gray-700">
            <i class="fas fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-teal text-xl"></i>
            @if(request('search'))
            <a href="{{ route('admin.booking.index') }}"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </a>
            @endif
        </form>
    </div>

    <!-- Filter Status -->
    <div class="mb-6" data-aos="fade-up" data-aos-delay="50">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.booking.index') }}"
                class="px-4 py-2 rounded-full text-sm {{ !request('status') ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Pending
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'diterima']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'diterima' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Diterima
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'in_progress']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'in_progress' ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Dititipkan
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'selesai']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'selesai' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Selesai
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'pembatalan']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'pembatalan' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                pembatalan
            </a>
            <a href="{{ route('admin.booking.index', ['status' => 'perpanjangan']) }}"
                class="px-4 py-2 rounded-full text-sm {{ request('status') == 'perpanjangan' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Perpanjangan
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Booking</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-calendar text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Dititipkan</p>
                    <p class="text-2xl font-bold">{{ $stats['in_progress'] ?? 0 }}</p>
                </div>
                <div class="bg-teal-100 p-3 rounded-full">
                    <i class="fas fa-paw text-teal-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Perpanjangan</p>
                    <p class="text-2xl font-bold">{{ $stats['perpanjangan'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-clock text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Pembatalan</p>
                    <p class="text-2xl font-bold">{{ $stats['pembatalan'] ?? 0 }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times text-red-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Booking -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden card-hover" data-aos="fade-up" data-aos-delay="150">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal to-teal-700 text-white">
                    <tr>
                        <th class="px-6 py-5 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-5 text-left text-sm font-semibold">Kode Booking</th>
                        <th class="px-6 py-5 text-left text-sm font-semibold">Pemilik & Hewan</th>
                        <th class="px-6 py-5 text-left text-sm font-semibold">Layanan</th>
                        <th class="px-6 py-5 text-left text-sm font-semibold">Tanggal Menginap</th>
                        <th class="px-6 py-5 text-left text-sm font-semibold">Total Harga</th>
                        <th class="px-6 py-5 text-center text-sm font-semibold">Bukti Bayar</th>
                        <th class="px-6 py-5 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-5 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    @php
                    $wa_number = preg_replace('/^0/', '62', $booking->nomor_wa ?? '');
                    $status_text = match($booking->status) {
                    'pending' => 'sedang menunggu konfirmasi',
                    'diterima' => 'telah DITERIMA',
                    'in_progress' => 'Dititipkan',
                    'selesai' => 'telah SELESAI',
                    'pembatalan' => 'pembatalan',
                    'perpanjangan' => 'PERPANJANGAN DIAJUKAN',
                    default => 'diperbarui'
                    };
                    $wa_text = urlencode("Halo {$booking->nama_pemilik},\n\nBooking Anda dengan kode *{$booking->kode_booking}* {$status_text}.\n\nSilakan cek status terbaru di website kami.\nTerima kasih! 🐾");

                    // Hitung durasi
                    $tanggal_masuk = \Carbon\Carbon::parse($booking->tanggal_masuk);
                    $tanggal_keluar = \Carbon\Carbon::parse($booking->tanggal_keluar);
                    $durasi = $tanggal_masuk->diffInDays($tanggal_keluar);
                    $durasi = max(1, $durasi); // Minimal 1 hari

                    // Get harga per hari from pivot table
                    $harga_per_hari = 0;
                    $total_harga = 0;

                    if($booking->layanan) {
                    // Get jenis hewan ID based on nama jenis hewan
                    $jenisHewan = \App\Models\JenisHewan::where('nama', $booking->jenis_hewan)->first();

                    if($jenisHewan) {
                    // Get harga from pivot table
                    $layananHarga = \App\Models\LayananHarga::where('layanan_id', $booking->layanan_id)
                    ->where('jenis_hewan_id', $jenisHewan->id)
                    ->first();

                    if($layananHarga) {
                    $harga_per_hari = $layananHarga->harga_per_hari;
                    $total_harga = $durasi * $harga_per_hari;
                    }
                    }
                    }

                    // If no harga found from pivot, check if booking has total_harga
                    if($total_harga == 0 && isset($booking->total_harga) && $booking->total_harga > 0) {
                    $total_harga = $booking->total_harga;
                    $harga_per_hari = $total_harga / $durasi;
                    }

                    // Clean alasan text for display
                    $alasanPerpanjangan = $booking->alasan_perpanjangan ? str_replace(["[PERPANJANGAN DIAJUKAN]\n", "Diajukan pada:"], "", $booking->alasan_perpanjangan) : null;
                    $alasanCancel = $booking->alasan_cancel ? str_replace(["[PEMBATALAN DIAJUKAN]\n", "Dibatalkan pada:"], "", $booking->alasan_cancel) : null;
                    @endphp

                    <tr class="table-row hover:bg-gray-50 transition">
                        <td class="px-6 py-5 text-sm text-gray-600">{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-5">
                            <span class="font-mono font-bold text-teal">{{ $booking->kode_booking }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-medium text-gray-900">{{ $booking->nama_pemilik }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $booking->nama_hewan }}
                                <span class="text-gray-400">• {{ $booking->jenis_hewan }}</span>
                            </div>

                            <!-- Tampilkan alasan perpanjangan -->
                            @if($alasanPerpanjangan)
                            <div class="mt-2">
                                <div class="text-xs text-purple-700 bg-purple-50 p-2 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-clock mr-2 mt-0.5"></i>
                                        <div>
                                            <div class="font-semibold mb-1">Alasan Perpanjangan:</div>
                                            <div class="text-purple-800">{{ Str::limit(trim($alasanPerpanjangan), 100) }}</div>
                                            @if(strlen(trim($alasanPerpanjangan)) > 100)
                                            <button type="button" onclick="showReason('perpanjangan', '{{ addslashes($booking->alasan_perpanjangan) }}')"
                                                class="text-purple-600 hover:text-purple-800 text-xs mt-1">
                                                Baca selengkapnya
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Tampilkan alasan cancel -->
                            @if($alasanCancel)
                            <div class="mt-2">
                                <div class="text-xs text-red-700 bg-red-50 p-2 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-times mr-2 mt-0.5"></i>
                                        <div>
                                            <div class="font-semibold mb-1">Alasan Pembatalan:</div>
                                            <div class="text-red-800">{{ Str::limit(trim($alasanCancel), 100) }}</div>
                                            @if(strlen(trim($alasanCancel)) > 100)
                                            <button type="button" onclick="showReason('pembatalan', '{{ addslashes($booking->alasan_cancel) }}')"
                                                class="text-red-600 hover:text-red-800 text-xs mt-1">
                                                Baca selengkapnya
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-gray-700">{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                        <td class="px-6 py-5 text-gray-700">
                            {{ $tanggal_masuk->format('d M Y') }}
                            <span class="text-gray-500">→</span>
                            {{ $tanggal_keluar->format('d M Y') }}
                            <div class="text-xs text-gray-400 mt-1">{{ $durasi }} hari</div>
                            @if($booking->status == 'perpanjangan' && $booking->tanggal_perpanjangan)
                            <div class="text-xs text-purple-600 font-semibold mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                Ajukan perpanjangan hingga: {{ \Carbon\Carbon::parse($booking->tanggal_perpanjangan)->format('d M Y') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($booking->total_harga)
                            <div class="font-semibold text-green-600">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                @php
                                $tanggal_masuk = \Carbon\Carbon::parse($booking->tanggal_masuk);
                                $tanggal_keluar = \Carbon\Carbon::parse($booking->tanggal_keluar);
                                $durasi = $tanggal_masuk->diffInDays($tanggal_keluar);
                                $durasi = max(1, $durasi);
                                @endphp
                                @if($durasi > 0)
                                Rp {{ number_format($booking->total_harga / $durasi, 0, ',', '.') }}/hari
                                @endif
                            </div>
                            @else
                            <span class="text-gray-400 text-sm italic">Belum ada harga</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($booking->bukti_dp)
                            @php
                            $file = $booking->bukti_dp;
                            // Jika tidak mengandung slash, tambahkan folder bukti_dp
                            if (!str_contains($file, '/')) {
                            $file = 'bukti_dp/' . $file;
                            }
                            $url = asset('storage/' . $file);
                            @endphp
                            <a href="{{ $url }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-teal text-white rounded-xl hover:bg-teal-700 transition shadow">
                                <i class="fas fa-image"></i> Lihat
                            </a>
                            @else
                            <span class="text-gray-400 italic text-sm">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-4 py-2 rounded-full text-xs font-bold {{ $booking->status_class }}">
                                {{ $booking->status_text }}
                            </span>
                            @if($booking->petugas_id && $booking->petugas)
                            <div class="text-xs text-gray-500 mt-1">
                                Petugas: {{ $booking->petugas->username }}
                            </div>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-center space-x-2">

                            <!-- Untuk konfirmasi (pending → diterima) -->
                            @if($booking->status == 'pending')
                            <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="diterima">
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi booking ini?')"
                                    class="inline-block px-4 py-2 text-sm text-white bg-teal rounded-xl hover:bg-teal-700 transition shadow">
                                    <i class="fas fa-check mr-1"></i> Konfirmasi
                                </button>
                            </form>
                            @elseif($booking->status == 'diterima')
                            <div class="flex flex-col space-y-2">
                                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="in_progress">
                                    <select name="petugas_id" required
                                        class="px-3 py-2 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-300">
                                        <option value="">Pilih Petugas</option>
                                        @foreach($petugas as $p)
                                        <option value="{{ $p->id }}">{{ $p->username }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                        onclick="return confirm('Mulai penitipan & tetapkan petugas?')"
                                        class="inline-block px-4 py-2 text-sm text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow">
                                        <i class="fas fa-play mr-1"></i> Mulai
                                    </button>
                                </form>

                                <!-- Tombol untuk reject booking -->
                                <button type="button" onclick="showRejectForm({{ $booking->id }})"
                                    class="inline-block px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700 transition shadow">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                            @elseif($booking->status == 'in_progress')
                            <div class="flex flex-col space-y-2">
                                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit"
                                        onclick="return confirm('Selesaikan penitipan ini?')"
                                        class="inline-block px-4 py-2 text-sm text-white bg-green-600 rounded-xl hover:bg-green-700 transition shadow">
                                        <i class="fas fa-flag-checkered mr-1"></i> Selesai
                                    </button>
                                </form>
                            </div>
                            @elseif($booking->status == 'perpanjangan')
                            <div class="flex flex-col space-y-2">
                                <form action="{{ route('admin.booking.handle-extension', $booking->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="action" value="terima">
                                    <input type="date" name="tanggal_perpanjangan"
                                        value="{{ $booking->tanggal_perpanjangan ?? '' }}"
                                        min="{{ $booking->tanggal_keluar }}"
                                        class="px-3 py-1 text-sm rounded-lg border border-gray-300">
                                    <button type="submit"
                                        onclick="return confirm('Terima perpanjangan?')"
                                        class="inline-block px-4 py-2 text-sm text-white bg-purple-600 rounded-xl hover:bg-purple-700 transition shadow">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.booking.handle-extension', $booking->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="tolak">
                                    <button type="submit"
                                        onclick="return confirm('Tolak perpanjangan?')"
                                        class="inline-block px-4 py-2 text-sm text-white bg-gray-600 rounded-xl hover:bg-gray-700 transition shadow">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                            @endif

                            <!-- WA Notif -->
                            @if($wa_number)
                            <a href="https://wa.me/{{ $wa_number }}?text={{ $wa_text }}" target="_blank"
                                class="inline-block px-4 py-2 text-sm text-white bg-[#25D366] rounded-xl hover:bg-[#128C7E] transition shadow mt-2">
                                <i class="fab fa-whatsapp mr-1"></i> WA
                            </a>
                            @endif

                            <!-- Hapus -->
                            <form action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST" class="inline mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Yakin hapus booking ini? Data akan hilang permanen!')"
                                    class="inline-block px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700 transition shadow">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-16 text-gray-500 text-lg">
                            @if(request('search') || request('status'))
                            Tidak ditemukan booking
                            @else
                            Belum ada data booking.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal untuk menampilkan alasan lengkap -->
<div id="reasonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800" id="modalTitle"></h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <p id="reasonText" class="text-gray-700 whitespace-pre-wrap"></p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk reject booking -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="pembatalan">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Tolak Booking</h3>
                        <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan (Opsional)
                        </label>
                        <textarea name="alasan_cancel"
                            rows="4"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-teal-300 focus:border-teal-500"
                            placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Tolak Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fungsi untuk menampilkan modal alasan lengkap
    function showReason(type, reason) {
        const modal = document.getElementById('reasonModal');
        const title = document.getElementById('modalTitle');
        const text = document.getElementById('reasonText');

        if (type === 'perpanjangan') {
            title.textContent = 'Alasan Perpanjangan Lengkap';
        } else if (type === 'pembatalan') {
            title.textContent = 'Alasan Pembatalan Lengkap';
        }

        // Replace newline characters with <br> for HTML display
        text.innerHTML = reason.replace(/\n/g, '<br>');
        modal.classList.remove('hidden');
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        document.getElementById('reasonModal').classList.add('hidden');
    }

    // Fungsi untuk menampilkan form reject
    function showRejectForm(bookingId) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/booking/${bookingId}`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal reject
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Event listener untuk menutup modal saat klik di luar
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('reasonModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    });
</script>
@endsection