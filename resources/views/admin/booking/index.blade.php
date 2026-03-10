@extends('admin.layout')

@section('title', 'Admin - Data Booking')

@section('content')
<div class="p-8 max-w-7xl mx-auto bg-gray-50/50 min-h-screen">
    <div class="mb-8 border-b border-gray-200 pb-6">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Booking</h1>
        <p class="text-sm text-gray-500 mt-1 uppercase tracking-wide font-medium">Manajemen Penitipan Hewan LARAPetHouse</p>
    </div>

    <div class="mb-8 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.booking.index') }}" class="flex flex-wrap lg:flex-nowrap gap-4 items-center">
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode, nama, atau hewan..."
                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-sm transition-all">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                @if(request('search'))
                <a href="{{ route('admin.booking.index', request()->except('search', 'page')) }}"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                    <i class="fas fa-times-circle"></i>
                </a>
                @endif
            </div>

            <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="bg-transparent border-none focus:ring-0 text-xs text-gray-600 font-medium p-0">
                @if(request('date'))
                <a href="{{ route('admin.booking.index', request()->except('date', 'page')) }}" class="text-gray-400 hover:text-red-500">
                    <i class="fas fa-times-circle text-xs"></i>
                </a>
                @endif
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-teal-600 text-white px-5 py-2.5 rounded-lg hover:bg-teal-700 text-sm font-bold transition shadow-sm active:scale-95">
                    Filter
                </button>
                <a href="{{ route('admin.booking.export-pdf', request()->all()) }}"
                    class="bg-white text-red-600 border border-red-200 px-5 py-2.5 rounded-lg hover:bg-red-50 text-sm font-bold transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.booking.index', request()->except('status', 'page')) }}"
            class="px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider transition {{ !request('status') ? 'bg-gray-800 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>

        @php
        $statuses = [
        'pending' => ['label' => 'Pending', 'color' => 'amber'],
        'diterima' => ['label' => 'Diterima', 'color' => 'blue'],
        'in_progress' => ['label' => 'Dititipkan', 'color' => 'teal'],
        'selesai' => ['label' => 'Selesai', 'color' => 'emerald'],
        'pembatalan' => ['label' => 'Batal', 'color' => 'red'],
        'perpanjangan' => ['label' => 'Perpanjangan', 'color' => 'purple'],
        ];
        @endphp

        @foreach($statuses as $key => $val)
        <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}"
            class="px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider transition {{ request('status') == $key ? "bg-{$val['color']}-600 text-white shadow-md" : "bg-white text-gray-500 border border-gray-200 hover:border-{$val['color']}-300 hover:text-{$val['color']}-600" }}">
            {{ $val['label'] }}
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto text-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px]">No</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px]">Kode</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px]">Pemilik & Hewan</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px]">Layanan & Harga</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px]">Durasi Menginap</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px] text-center">Bukti DP</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px] text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-gray-600 uppercase tracking-wider text-[11px] text-right px-10">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 text-gray-400 text-xs font-medium">
                            {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-teal-50 text-teal-700 rounded font-mono text-xs font-bold border border-teal-100 uppercase">
                                {{ $booking->kode_booking }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $booking->nama_pemilik }}</div>
                            <div class="text-[11px] text-gray-400 mt-0.5 flex items-center italic capitalize">
                                <i class="fas fa-paw mr-1 text-[10px]"></i> {{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})
                            </div>

                            @if($booking->alasan_perpanjangan)
                            <div class="mt-2 text-[10px] bg-purple-50 text-purple-700 p-1.5 rounded border border-purple-100 inline-block max-w-[200px] truncate cursor-pointer" onclick="showReason('perpanjangan', '{{ addslashes($booking->alasan_perpanjangan) }}')">
                                <i class="fas fa-clock mr-1"></i> Perpanjangan...
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-700 font-medium">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                            <div class="text-emerald-600 font-bold text-xs mt-0.5">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600 leading-tight">
                                <span class="text-gray-400">In:</span> {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/y') }}<br>
                                <span class="text-gray-400">Out:</span> {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/y') }}
                            </div>
                            <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded mt-1 inline-block">
                                {{ max(1, \Carbon\Carbon::parse($booking->tanggal_masuk)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_keluar))) }} Hari
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($booking->bukti_dp)
                            <a href="{{ asset('storage/' . (str_contains($booking->bukti_dp, '/') ? $booking->bukti_dp : 'bukti_dp/'.$booking->bukti_dp)) }}"
                                target="_blank" class="inline-flex items-center text-teal-600 hover:text-teal-800 font-bold text-[11px] bg-teal-50 px-2 py-1 rounded border border-teal-100 transition-all">
                                <i class="fas fa-eye mr-1.5"></i> Lihat
                            </a>
                            @else
                            <span class="text-gray-300 italic text-[11px]">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold tracking-tight uppercase border shadow-sm {{ $booking->status_class }}">
                                {{ $booking->status_text }}
                            </span>
                            @if($booking->petugas)
                            <div class="text-[9px] text-gray-400 mt-1 uppercase font-bold">{{ $booking->petugas->username }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-2">

                                {{-- CASE: PELANGGAN AJUKAN PEMBATALAN (STATUS: PEMBATALAN) --}}
                                {{-- Tombol hanya muncul jika status pembatalan DAN BELUM ada tulisan [DISETUJUI] --}}
                                @if($booking->status == 'pembatalan' && !str_contains($booking->alasan_cancel, '[DISETUJUI]'))
                                <div class="flex flex-col gap-2">
                                    <div class="text-[10px] bg-red-50 text-red-700 p-2 rounded border border-red-100 mb-1 max-w-[180px]">
                                        <span class="font-bold uppercase block mb-1 text-left">Alasan User:</span>
                                        <p class="text-left italic">"{{ $booking->alasan_cancel }}"</p>
                                    </div>

                                    <div class="flex gap-2 justify-end">
                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="pembatalan">
                                            {{-- Kirim alasan saat ini agar bisa diproses Controller --}}
                                            <input type="hidden" name="alasan_cancel" value="{{ $booking->alasan_cancel }}">
                                            <button type="submit" onclick="return confirm('Setujui pembatalan?')"
                                                class="bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 text-[10px] font-bold transition shadow-sm">
                                                SETUJUI
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="diterima">
                                            <button type="submit" class="bg-gray-800 text-white px-3 py-1.5 rounded-lg hover:bg-gray-900 text-[10px] font-bold transition shadow-sm">
                                                TOLAK
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @elseif($booking->status == 'pembatalan' && str_contains($booking->alasan_cancel, '[DISETUJUI]'))
                                {{-- Tampilan saat sudah disetujui: Tombol Hilang, muncul teks saja --}}
                                <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded font-bold uppercase italic">
                                    <i class="fas fa-check-circle mr-1"></i> Telah Dibatalkan
                                </span>
                                @endif

                                {{-- CASE: PENDING -> TERIMA --}}
                                @if($booking->status == 'pending')
                                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" onclick="return confirm('Terima booking ini?')"
                                        class="bg-teal-600 text-white px-3 py-1.5 rounded-lg hover:bg-teal-700 text-[11px] font-bold transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-check"></i> KONFIRMASI
                                    </button>
                                </form>
                                @endif

                                {{-- CASE: DITERIMA -> MULAI (PILIH PETUGAS) --}}
                                @if($booking->status == 'diterima')
                                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="flex items-center gap-1.5">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="in_progress">

                                    <select name="petugas_id" required
                                        class="text-[10px] py-1 pl-2 pr-6 border-gray-200 rounded-lg focus:ring-teal-500/20 focus:border-teal-500 bg-gray-50 font-bold text-gray-600 uppercase">
                                        <option value="">PETUGAS</option>
                                        @foreach($petugas as $p)
                                        <option value="{{ $p->id }}">{{ $p->username }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" onclick="return confirm('Mulai penitipan?')"
                                        class="bg-blue-600 text-white p-1.5 rounded-lg hover:bg-blue-700 transition shadow-sm" title="Mulai Penitipan">
                                        <i class="fas fa-play text-[10px]"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- CASE: IN PROGRESS -> SELESAI --}}
                                @if($booking->status == 'in_progress')
                                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit" onclick="return confirm('Selesaikan penitipan?')"
                                        class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-700 text-[11px] font-bold transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-flag-checkered"></i> SELESAIKAN
                                    </button>
                                </form>
                                @endif

                                {{-- CASE: PERPANJANGAN --}}
                                @if($booking->status == 'perpanjangan')
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.booking.handle-extension', $booking->id) }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="action" value="terima">
                                        <input type="date" name="tanggal_perpanjangan" value="{{ $booking->tanggal_perpanjangan }}"
                                            class="text-[10px] py-1 border-gray-200 rounded-lg bg-purple-50 text-purple-700 font-bold">
                                        <button type="submit" class="bg-purple-600 text-white p-1.5 rounded-lg hover:bg-purple-700 transition shadow-sm">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif

                                {{-- BARIS TOMBOL TAMBAHAN (WA, REJECT, DELETE) --}}
                                <div class="flex gap-1.5 mt-1">
                                    @if($booking->status == 'diterima' || $booking->status == 'pending')
                                    <button type="button" onclick="showRejectForm('{{ $booking->id }}')"
                                        class="text-gray-400 hover:text-red-600 p-1.5 transition" title="Tolak">
                                        <i class="fas fa-times-circle text-xs"></i>
                                    </button>
                                    @endif

                                    @if($booking->nomor_wa)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $booking->nomor_wa) }}" target="_blank"
                                        class="text-gray-400 hover:text-emerald-600 p-1.5 transition" title="WhatsApp">
                                        <i class="fab fa-whatsapp text-xs"></i>
                                    </a>
                                    @endif

                                    <form action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-700 p-1.5 transition" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-20 text-center">
                            <i class="fas fa-inbox text-gray-200 text-4xl mb-3"></i>
                            <p class="text-gray-400 font-medium italic text-sm">Data booking tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $bookings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modals tetap sama logikanya, hanya disesuaikan stylenya agar rounded-xl dan font-bold --}}
<div id="reasonModal" class="fixed inset-0 bg-gray-900/60 hidden z-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider" id="modalTitle">Detail Alasan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 leading-relaxed italic border border-gray-100 shadow-inner" id="reasonText"></div>
        </div>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 bg-gray-900/60 hidden z-50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
        <form id="rejectForm" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="pembatalan">
            <div class="p-5 border-b border-gray-100 bg-gray-50 font-bold text-gray-800 text-sm">KONFIRMASI PENOLAKAN</div>
            <div class="p-6">
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Alasan Pembatalan</label>
                <textarea name="alasan_cancel" rows="3" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" placeholder="Tuliskan alasan singkat..."></textarea>
            </div>
            <div class="p-4 bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">BATAL</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 shadow-sm transition">TOLAK BOOKING</button>
            </div>
        </form>
    </div>
</div>
@endsection