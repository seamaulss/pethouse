@extends('petugas.layouts.app')

@section('title', 'Detail Booking - ' . $booking->kode_booking)

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <a href="{{ route('petugas.booking.index') }}" class="text-teal-600 hover:underline text-sm flex items-center mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Booking #{{ $booking->kode_booking }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-xl text-sm font-bold uppercase {{ $booking->status == 'in_progress' ? 'bg-teal-100 text-teal-700' : 'bg-blue-100 text-blue-700' }}">
                Status: {{ $booking->status == 'in_progress' ? 'Dititipkan' : 'Diterima' }}
            </span>
            <a href="{{ route('petugas.input-log.show', $booking->id) }}" class="bg-teal-600 text-white px-5 py-2 rounded-xl hover:bg-teal-700 transition shadow-md flex items-center">
                <i class="fas fa-plus mr-2"></i> Update Kegiatan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-paw mr-2 text-teal-600"></i> Informasi Hewan
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Hewan</span>
                        <span class="font-semibold text-gray-800">{{ $booking->nama_hewan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jenis</span>
                        <span class="font-semibold text-gray-800">{{ $booking->jenis_hewan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ukuran/Kategori</span>
                        <span class="font-semibold text-teal-600">{{ $booking->ukuran_hewan }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-teal-600"></i> Informasi Pemilik
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama</span>
                        <span class="font-semibold text-gray-800">{{ $booking->nama_pemilik }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->nomor_wa) }}" target="_blank" class="font-semibold text-blue-600 hover:underline">
                            {{ $booking->nomor_wa }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Penitipan</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Tanggal Masuk</p>
                        <p class="text-gray-800 font-semibold italic">
                            <i class="far fa-calendar-check mr-2 text-teal-500"></i>
                            {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Tanggal Keluar</p>
                        <p class="text-gray-800 font-semibold italic">
                            <i class="far fa-calendar-times mr-2 text-pink-500"></i>
                            {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="mt-6 p-4 border border-dashed border-gray-200 rounded-2xl">
                    <p class="text-xs text-gray-500 uppercase font-bold mb-2">Catatan Kebutuhan Khusus</p>
                    <p class="text-sm text-gray-700 leading-relaxed italic">
                        "{{ $booking->catatan ?? 'Tidak ada catatan khusus.' }}"
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Log Kegiatan Harian</h3>
                </div>
                <div class="p-6">
                    @if(isset($booking->dailyLogs) && $booking->dailyLogs->count() > 0)
                        <div class="relative border-l-2 border-teal-100 ml-3 space-y-8">
                            @foreach($booking->dailyLogs->sortByDesc('created_at') as $log)
                            <div class="relative pl-8">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 bg-teal-500 rounded-full border-4 border-white shadow-sm"></div>
                                <div class="text-xs text-gray-400 mb-1 font-mono uppercase">
                                    {{ $log->created_at->format('d M Y - H:i') }}
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl text-sm text-gray-700 border border-gray-100">
                                    {{ $log->keterangan }}
                                    @if($log->foto_kegiatan)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $log->foto_kegiatan) }}" class="w-32 h-32 object-cover rounded-xl shadow-sm border">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400 italic">
                            <i class="fas fa-stream text-3xl mb-3 opacity-20"></i>
                            <p>Belum ada log kegiatan harian.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection