@extends('petugas.layouts.app')

@section('title', 'Petugas - Daftar Hewan Menginap')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Hewan Menginap</h1>
            <p class="text-gray-500">Memantau hewan yang sedang menempati kapasitas kandang saat ini.</p>
        </div>
        <div class="bg-teal-50 px-4 py-2 rounded-xl border border-teal-100">
            <span class="text-teal-600 font-bold">{{ $bookings->total() }} Ekor</span>
            <span class="text-teal-500 text-sm"> di lokasi</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Kode & Hewan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Kategori Kandang</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Pemilik</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Durasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-teal-600">#{{ $booking->kode_booking }}</div>
                            <div class="text-gray-800 font-medium">{{ $booking->nama_hewan }}</div>
                            <div class="text-xs text-gray-400">{{ $booking->jenis_hewan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-semibold">
                                {{ $booking->ukuran_hewan }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $booking->nama_pemilik }}</div>
                            <div class="text-xs text-blue-500 italic">{{ $booking->nomor_wa }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600">
                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M') }} 
                                - {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d M Y') }}
                            </div>
                            @php
                                $sisaHari = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($booking->tanggal_keluar), false);
                            @endphp
                            <div class="text-xs mt-1 {{ $sisaHari <= 1 ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                {{ $sisaHari < 0 ? 'Melewati batas' : 'Sisa ' . $sisaHari . ' hari' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $booking->status == 'in_progress' ? 'bg-teal-100 text-teal-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $booking->status == 'in_progress' ? 'DITITIPKAN' : 'DITERIMA' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('petugas.input-log.show', $booking->id) }}" 
                                   class="p-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-600 hover:text-white transition shadow-sm"
                                   title="Update Kegiatan Harian">
                                    <i class="fas fa-notes-medical"></i>
                                </a>
                                <a href="{{ route('petugas.booking.show', $booking->id) }}" 
                                   class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm"
                                   title="Detail Booking">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl mb-3 opacity-20"></i>
                                <p>Tidak ada hewan yang sedang menginap saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection