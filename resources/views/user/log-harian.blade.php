@extends('layouts.user')

@section('title', 'Log Harian ' . $booking->nama_hewan . ' - PetHouse')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ route('user.hewan-saya') }}" class="text-teal-600 hover:underline flex items-center gap-1">
            ← Kembali ke Hewan Saya
        </a>
        <h1 class="text-2xl font-bold mt-2">Log Harian: {{ $booking->nama_hewan }}</h1>
        <p class="text-gray-600">
            {{ $booking->jenis_hewan }} • 
            {{ $booking->tanggal_masuk->format('d/m/Y') }} s/d 
            {{ $booking->tanggal_keluar->format('d/m/Y') }}
        </p>
    </div>

    @if($logs->count() > 0)
        <div class="space-y-5">
            @foreach($logs as $log)
                <div class="bg-white p-5 rounded-lg shadow border border-gray-200">
                    <div class="flex justify-between">
                        <h3 class="font-bold text-lg text-gray-800">
                            {{ $log->tanggal->format('d F Y') }}
                        </h3>
                        <span class="text-xs text-gray-500">{{ $log->created_at->format('H:i') }}</span>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($log->makan_pagi)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">✅ Makan Pagi</span>
                        @endif
                        @if($log->makan_siang)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">✅ Makan Siang</span>
                        @endif
                        @if($log->makan_malam)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">✅ Makan Malam</span>
                        @endif
                        @if($log->minum)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">💧 Minum</span>
                        @endif
                        @if($log->jalan_jalan)
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">🚶‍♂️ Jalan-jalan</span>
                        @endif
                        @if($log->buang_air && $log->buang_air !== 'belum')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                                💩 {{ ucfirst($log->buang_air) }}
                            </span>
                        @endif
                    </div>

                    @if($log->catatan)
                        <div class="mt-3 p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-700 italic">"{{ $log->catatan }}"</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
            Belum ada update harian untuk hewan ini.
        </div>
    @endif
</div>
@endsection