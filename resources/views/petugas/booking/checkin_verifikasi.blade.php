@extends('petugas.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-teal-600 p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Verifikasi Check-in</h1>
                <p class="text-teal-100 text-sm">Kode: {{ $booking->kode_booking }}</p>
            </div>
            <i class="fas fa-file-invoice text-4xl opacity-50"></i>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Pemilik</label>
                    <p class="text-lg font-bold text-gray-800">{{ $booking->nama_pemilik }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->nomor_wa }}</p>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase">Status DP</label>
                    <div>
                        @if($booking->dp_dibayar == 'Ya')
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-bold">LUNAS</span>
                        @else
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">BELUM BAYAR</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-6 mb-8 border border-dashed border-gray-200">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Detail Anabul</h3>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-teal-600 shadow-sm text-2xl">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $booking->nama_hewan }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->jenis_hewan }} ({{ $booking->ukuran_hewan }})</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('petugas.dashboard') }}" class="flex-1 text-center py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition">
                    Batal
                </a>
                
                @if($booking->dp_dibayar == 'Ya')
                    <a href="{{ route('petugas.input-log.show', $booking->id) }}" 
                       class="flex-[2] text-center py-4 bg-teal-600 text-white rounded-2xl font-bold hover:bg-teal-700 transition shadow-lg shadow-teal-100">
                        Proses Check-in Sekarang
                    </a>
                @else
                    <button onclick="alert('Minta pelanggan bayar DP terlebih dahulu!')" 
                       class="flex-[2] text-center py-4 bg-amber-500 text-white rounded-2xl font-bold opacity-50 cursor-not-allowed">
                        Menunggu Pembayaran
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection