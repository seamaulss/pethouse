@extends('layouts.user')

@section('title', 'User - Riwayat Booking')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header -->
    <div class="mb-8" data-aos="fade-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-teal-600 mb-2">
                    Riwayat Booking
                </h1>
                <p class="text-gray-600">
                    Semua booking yang telah Anda buat
                </p>
            </div>

            <a href="{{ route('user.booking.create') }}"
               class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Booking Baru
            </a>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Table -->
    @if($bookings->count())
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-teal-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Kode</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Hewan</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Layanan</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-teal-800">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            @php
                                $statusMap = [
                                    'pending' => ['Menunggu', 'bg-yellow-100 text-yellow-800'],
                                    'diterima' => ['Diterima', 'bg-blue-100 text-blue-800'],
                                    'in_progress' => ['Sedang Dititipkan', 'bg-teal-100 text-teal-800'],
                                    'selesai' => ['Selesai', 'bg-green-100 text-green-800'],
                                    'pembatalan' => ['Dibatalkan', 'bg-red-100 text-red-800'],
                                    'perpanjangan' => ['Perpanjangan', 'bg-purple-100 text-purple-800'],
                                ];
                                [$statusText, $statusClass] = $statusMap[$booking->status] ?? ['Unknown', 'bg-gray-100'];
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-mono font-bold text-teal-600">
                                        {{ $booking->kode_booking }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $booking->nama_hewan }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->jenis_hewan }} • {{ $booking->ukuran_hewan }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-medium">
                                        {{ $booking->layanan->nama_layanan ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/Y') }}
                                    →
                                    {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('user.booking.show', $booking->id) }}"
                                           class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <h3 class="text-xl font-bold text-gray-700 mb-2">
                Belum ada booking
            </h3>
            <p class="text-gray-500 mb-6">
                Anda belum membuat booking penitipan hewan.
            </p>
            <a href="{{ route('user.booking.create') }}"
               class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-medium">
                <i class="fas fa-plus mr-2"></i> Buat Booking
            </a>
        </div>
    @endif

    <!-- Back -->
    <div class="mt-8 text-center">
        <a href="{{ route('user.booking.create') }}"
           class="text-gray-600 hover:text-teal-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Booking
        </a>
    </div>

</div>
@endsection
