@extends('petugas.layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Status Ketersediaan Kandang</h1>
        <p class="text-gray-500">Data real-time kondisi kandang hari ini ({{ date('d F Y') }})</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($dataStatus as $item)
        <div class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-1 rounded">
                        {{ $item->layanan->nama_layanan }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-800 mt-2">
                        {{ $item->jenis_hewan }} ({{ $item->ukuran_hewan }})
                    </h3>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black {{ $item->sisa <= 0 ? 'text-red-500' : 'text-gray-800' }}">
                        {{ $item->sisa }}
                    </span>
                    <p class="text-xs text-gray-400 uppercase">Sisa Slot</p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between text-xs font-medium text-gray-500">
                    <span>Terisi: {{ $item->terisi }}</span>
                    <span>Kapasitas: {{ $item->max_kapasitas }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $item->persentase >= 90 ? 'bg-red-500' : ($item->persentase >= 70 ? 'bg-orange-400' : 'bg-teal-500') }}"
                        style="--progress: {{ $item->persentase }}%; width: var(--progress);">
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                @if($item->sisa > 0)
                <span class="flex items-center text-xs text-green-600 font-bold">
                    <i class="fas fa-check-circle mr-1"></i> TERSEDIA
                </span>
                @else
                <span class="flex items-center text-xs text-red-600 font-bold">
                    <i class="fas fa-times-circle mr-1"></i> PENUH
                </span>
                @endif
                <a href="{{ route('petugas.booking.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Detail Hewan &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection