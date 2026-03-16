@extends('petugas.layouts.app')

@section('title', 'Verifikasi Check-in - ' . $booking->kode_booking)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 sm:py-12">
    {{-- Tombol Kembali --}}
    <a href="{{ route('petugas.dashboard') }}" class="inline-flex items-center text-sm font-bold text-teal-600 mb-6 hover:text-teal-700 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>

    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100" data-aos="zoom-in">
        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-8 sm:p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest border border-white/30">
                    Konfirmasi Kedatangan
                </span>
                <h1 class="text-3xl font-black mt-3">Verifikasi Check-in</h1>
                <p class="text-teal-100 text-sm font-medium opacity-80 italic">ID Booking: {{ $booking->kode_booking }}</p>
            </div>
            <i class="fas fa-clipboard-check text-9xl absolute -right-5 -bottom-5 opacity-10 transform rotate-12"></i>
        </div>

        <div class="p-8 sm:p-10">
            {{-- Info Pemilik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Informasi Pemilik</label>
                        <p class="text-lg font-extrabold text-gray-800 leading-tight">{{ $booking->nama_pemilik }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ $booking->nomor_wa }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl {{ $booking->dp_dibayar == 'Ya' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }} flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $booking->dp_dibayar == 'Ya' ? 'fa-check-circle' : 'fa-exclamation-circle' }} text-xl"></i>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Status Pembayaran</label>
                        @if($booking->dp_dibayar == 'Ya')
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-500 text-white text-[10px] font-black uppercase tracking-tighter">Lunas (DP)</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-500 text-white text-[10px] font-black uppercase tracking-tighter">Belum Bayar</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Info Hewan --}}
            <div class="bg-gray-50 rounded-[2rem] p-6 sm:p-8 mb-10 border border-gray-100 relative group transition-all hover:bg-teal-50/30">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Detail Anabul</h3>
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-teal-600 shadow-sm text-3xl group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-teal-900 capitalize leading-tight">{{ $booking->nama_hewan }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="text-[10px] font-bold px-3 py-1 bg-white text-gray-500 rounded-md border border-gray-200 uppercase">{{ $booking->jenis_hewan }}</span>
                            <span class="text-[10px] font-bold px-3 py-1 bg-white text-gray-500 rounded-md border border-gray-200 uppercase">{{ $booking->ukuran_hewan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('petugas.dashboard') }}"
                    class="flex-1 text-center py-4 px-6 bg-white border-2 border-gray-100 text-gray-400 rounded-2xl font-bold hover:bg-gray-50 hover:text-gray-600 transition-all active:scale-95">
                    Batalkan
                </a>

                @if($booking->dp_dibayar == 'Ya')
                {{-- Pastikan Parameter ID Terkirim dengan Benar --}}
                <a href="{{ route('petugas.input-log.show', ['booking' => $booking->id]) }}"
                    class="flex-[2] text-center py-4 px-6 bg-teal-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-teal-700 transition-all shadow-xl shadow-teal-200 active:scale-95">
                    <i class="fas fa-sign-in-alt mr-2"></i> Proses Check-in Sekarang
                </a>
                @else
                <button onclick="Swal.fire('Perhatian!', 'Pelanggan wajib melunasi DP terlebih dahulu sebelum diproses.', 'warning')"
                    class="flex-[2] text-center py-4 px-6 bg-amber-500 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-amber-100 transition-all active:scale-95">
                    <i class="fas fa-hourglass-half mr-2"></i> Menunggu Pembayaran
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan SweetAlert2 untuk UX yang lebih profesional --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection