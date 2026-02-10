@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-8 flex justify-between items-center" data-aos="fade-down">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Antrean #{{ $konsultasi->kode_konsultasi }}</h1>
            <p class="text-gray-500 mt-1">Status: <span class="font-bold {{ $konsultasi->status_class }} rounded-full px-2 py-0.5 text-xs">{{ $konsultasi->status_label }}</span></p>
        </div>
        <a href="{{ route('admin.konsultasi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-2xl font-bold transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="space-y-6" data-aos="fade-right">
            <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
                <h3 class="font-bold text-gray-400 text-xs uppercase tracking-widest mb-4">Informasi Klien</h3>
                <div class="space-y-3 text-sm">
                    <p><span class="text-gray-400">Pemilik:</span><br><span class="font-bold text-gray-800">{{ $konsultasi->nama_pemilik }}</span></p>
                    <p><span class="text-gray-400">No. WA:</span><br><a href="https://wa.me/{{ $konsultasi->wa_link }}" target="_blank" class="text-emerald-600 font-bold underline">{{ $konsultasi->no_wa }}</a></p>
                    <p><span class="text-gray-400">Spesies:</span><br><span class="font-bold text-gray-800">{{ $konsultasi->jenis_hewan }}</span></p>
                </div>
            </div>

            @if($konsultasi->dokter)
            <div class="bg-teal-600 p-6 rounded-3xl shadow-lg text-white">
                <h3 class="font-bold text-teal-200 text-xs uppercase tracking-widest mb-4">Dokter Penanggung Jawab</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <p class="font-bold text-lg">{{ $konsultasi->dokter->username }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="md:col-span-2 space-y-6" data-aos="fade-left">
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-notes-medical mr-3 text-teal-600"></i> Rekam Medis Digital
                </h2>

                @if($konsultasi->balasan_dokter)
                <div class="p-6 bg-teal-50 rounded-2xl border-2 border-teal-100 text-teal-900 leading-relaxed italic whitespace-pre-line">
                    "{{ $konsultasi->balasan_dokter }}"
                </div>
                <div class="mt-4 text-right text-[10px] text-gray-400 uppercase font-bold">
                    Terakhir diperbarui: {{ $konsultasi->updated_at->format('d/m/Y H:i') }}
                </div>
                @else
                <div class="p-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center">
                    <p class="text-gray-400 italic">Dokter belum mengisi hasil pemeriksaan untuk antrean ini.</p>
                </div>
                @endif
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center text-sm">
                    <i class="fas fa-comment-dots mr-3 text-gray-400"></i> Catatan Keluhan Awal
                </h2>
                <p class="text-gray-600 text-sm italic">{{ $konsultasi->catatan ?? 'Tidak ada catatan awal.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection