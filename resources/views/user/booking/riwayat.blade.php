@extends('layouts.user')

@section('title', 'User - Riwayat Booking')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10" data-aos="fade-down">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="relative">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
                    Riwayat <span class="text-teal-600">Booking</span>
                </h1>
                <p class="text-gray-500 text-lg flex items-center">
                    <i class="fas fa-history mr-2 text-teal-500"></i> Pantau status penitipan anabul kesayangan Anda
                </p>
                <div class="absolute -left-4 top-0 h-full w-1 bg-teal-500 rounded-full opacity-50"></div>
            </div>

            <a href="{{ route('user.booking.create') }}"
               class="group relative inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl overflow-hidden">
                <div class="absolute inset-0 w-full h-full bg-white opacity-10 group-hover:scale-150 transition-transform duration-700 rounded-full"></div>
                <i class="fas fa-plus-circle mr-3 text-xl group-hover:rotate-90 transition-transform duration-300"></i> 
                <span>Booking Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-8 border-emerald-500 p-5 mb-8 rounded-2xl shadow-sm flex items-center animate-bounce-short">
            <div class="bg-emerald-500 p-2 rounded-lg mr-4">
                <i class="fas fa-check text-white"></i>
            </div>
            <p class="text-emerald-800 font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-8 border-rose-500 p-5 mb-8 rounded-2xl shadow-sm flex items-center animate-pulse-slow">
            <div class="bg-rose-500 p-2 rounded-lg mr-4">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <p class="text-rose-800 font-bold">{{ session('error') }}</p>
        </div>
    @endif

    @if($bookings->count())
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden" data-aos="fade-up">
            
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-teal-50 to-white">
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800">Detail Transaksi</th>
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800">Identitas Hewan</th>
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800">Layanan</th>
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800">Jadwal</th>
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800">Status</th>
                            <th class="px-8 py-6 text-xs uppercase tracking-widest font-black text-teal-800 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @foreach($bookings as $booking)
                            @php
                                $statusMap = [
                                    'pending' => ['Menunggu', 'bg-amber-100 text-amber-700 border-amber-200', 'fa-clock'],
                                    'diterima' => ['Diterima', 'bg-blue-100 text-blue-700 border-blue-200', 'fa-check-double'],
                                    'in_progress' => ['Dititipkan', 'bg-teal-100 text-teal-700 border-teal-200', 'fa-paw'],
                                    'selesai' => ['Selesai', 'bg-emerald-100 text-emerald-700 border-emerald-200', 'fa-flag-checkered'],
                                    'pembatalan' => ['Dibatalkan', 'bg-rose-100 text-rose-700 border-rose-200', 'fa-times-circle'],
                                    'perpanjangan' => ['Perpanjangan', 'bg-violet-100 text-violet-700 border-violet-200', 'fa-calendar-plus'],
                                ];
                                [$statusText, $statusClass, $statusIcon] = $statusMap[$booking->status] ?? ['Unknown', 'bg-gray-100', 'fa-question'];
                            @endphp

                            <tr class="group hover:bg-teal-50/30 transition-colors duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-mono font-black text-teal-700 text-lg mb-1 group-hover:scale-105 transition-transform origin-left">
                                            #{{ $booking->kode_booking }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium italic">
                                            {{ $booking->created_at->translatedFormat('d F Y, H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 mr-3 shadow-inner">
                                            <i class="fas fa-dog text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-800">{{ $booking->nama_hewan }}</div>
                                            <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">
                                                {{ $booking->jenis_hewan }} • {{ $booking->ukuran_hewan }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold uppercase">
                                        {{ $booking->layanan->nama_layanan ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                        <span class="text-teal-600">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y') }}</span>
                                        <i class="fas fa-long-arrow-alt-right text-gray-300"></i>
                                        <span class="text-rose-500">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black border {{ $statusClass }} shadow-sm">
                                        <i class="fas {{ $statusIcon }} mr-2 animate-pulse"></i>
                                        {{ strtoupper($statusText) }}
                                    </span>
                                </td>

                                <td class="px-8 py-6 text-center">
                                    <a href="{{ route('user.booking.show', $booking->id) }}"
                                       class="inline-flex items-center justify-center w-12 h-12 bg-white text-teal-600 rounded-2xl shadow-md hover:bg-teal-600 hover:text-white transition-all duration-300 border border-gray-100 group">
                                        <i class="fas fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y divide-gray-100">
                @foreach($bookings as $booking)
                    <div class="p-6 hover:bg-teal-50/20 active:bg-teal-50 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="font-mono font-black text-teal-600 text-lg">#{{ $booking->kode_booking }}</span>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                                    Dibuat: {{ $booking->created_at->format('d F Y') }}
                                </p>
                            </div>
                            @php
                                [$statusText, $statusClass, $statusIcon] = $statusMap[$booking->status] ?? ['Unknown', 'bg-gray-100', 'fa-question'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black border {{ $statusClass }}">
                                {{ strtoupper($statusText) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-5 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase">Hewan</p>
                                <p class="text-sm font-bold text-gray-800">{{ $booking->nama_hewan }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase">Jadwal</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('user.booking.show', $booking->id) }}"
                           class="w-full flex items-center justify-center gap-2 bg-white border-2 border-teal-600 text-teal-600 py-3 rounded-xl font-black text-sm hover:bg-teal-600 hover:text-white transition-all">
                            LIHAT DETAIL <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            @if($bookings->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-[3rem] shadow-2xl p-16 text-center border-2 border-dashed border-gray-200" data-aos="zoom-in">
            <div class="w-32 h-32 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i class="fas fa-folder-open text-5xl text-teal-200"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-800 mb-4">
                Belum Ada Riwayat
            </h3>
            <p class="text-gray-500 max-w-sm mx-auto mb-10 leading-relaxed font-medium">
                Sepertinya Anda belum pernah menitipkan hewan di klinik kami. Mari berikan perawatan terbaik untuk anabul Anda!
            </p>
            <a href="{{ route('user.booking.create') }}"
               class="inline-flex items-center bg-gray-900 hover:bg-teal-600 text-white px-10 py-4 rounded-2xl font-black tracking-widest transition-all duration-300 transform hover:scale-110 shadow-xl">
                MULAI BOOKING <i class="fas fa-rocket ml-3"></i>
            </a>
        </div>
    @endif

    <div class="mt-12 flex justify-center">
        <a href="{{ route('user.booking.create') }}"
           class="inline-flex items-center text-gray-400 hover:text-teal-600 font-bold transition-colors group">
            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2 group-hover:bg-teal-50 transition-colors">
                <i class="fas fa-chevron-left text-sm"></i>
            </div>
            KEMBALI KE HALAMAN BOOKING
        </a>
    </div>

</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-bounce-short {
        animation: bounce-short 1s ease-in-out infinite;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection