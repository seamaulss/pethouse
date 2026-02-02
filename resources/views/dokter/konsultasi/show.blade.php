@extends('layouts.dokter')

@section('title', "Konsultasi #{$konsultasi->kode_konsultasi}")

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-6">
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded mb-6 animate-pulse">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Card Utama -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 border border-gray-200">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-6 text-white">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Konsultasi #{{ $konsultasi->kode_konsultasi }}</h1>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="
                            @if($konsultasi->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($konsultasi->status === 'diterima') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800
                            @endif 
                            px-3 py-1 rounded-full text-sm font-medium">
                            {{ ucfirst($konsultasi->status) }}
                        </span>
                        <span class="text-teal-100">Dibuat: {{ $konsultasi->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', $konsultasi->no_wa), '0') }}" 
                   target="_blank"
                   class="bg-white text-teal-700 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition duration-200 shadow-md">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.76.982.998-3.675-.236-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.9 6.994c-.004 5.45-4.438 9.88-9.888 9.88m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.333.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.304-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893 0-3.18-1.24-6.162-3.495-8.411"/>
                    </svg>
                    Chat via WhatsApp
                </a>
            </div>
        </div>

        <!-- Body Card -->
        <div class="p-6">
            <!-- Informasi Pasien -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg border-b pb-2">Informasi Pemilik</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Nama Lengkap</p>
                            <p class="font-medium">{{ $konsultasi->nama_pemilik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor WhatsApp</p>
                            <p class="font-medium">{{ $konsultasi->no_wa }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg border-b pb-2">Informasi Hewan & Janji</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Jenis Hewan</p>
                            <p class="font-medium">{{ $konsultasi->jenis_hewan }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Janji</p>
                                <p class="font-medium">{{ $konsultasi->tanggal_janji ? \Carbon\Carbon::parse($konsultasi->tanggal_janji)->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jam Janji</p>
                                <p class="font-medium">{{ $konsultasi->jam_janji ? date('H:i', strtotime($konsultasi->jam_janji)) : '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Topik Konsultasi</p>
                            <p class="font-medium text-teal-700">{{ $konsultasi->topik }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan dari Pasien -->
            @if($konsultasi->catatan)
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-bold text-gray-800 text-lg mb-2">Catatan dari Pasien</h3>
                <p class="text-gray-700">{{ $konsultasi->catatan }}</p>
            </div>
            @endif

            <!-- Aksi Berdasarkan Status -->
            <div class="flex flex-wrap gap-3 mb-8 p-4 bg-gray-50 rounded-lg">
                @if($konsultasi->status === 'pending')
                    <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="aksi" value="terima">
                        <button type="submit" 
                                onclick="return confirm('Yakin menerima konsultasi ini?')"
                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terima Konsultasi
                        </button>
                    </form>
                @elseif($konsultasi->status === 'diterima')
                    <form method="POST" action="{{ route('dokter.konsultasi.update-status', $konsultasi->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="aksi" value="selesai">
                        <button type="submit" 
                                onclick="return confirm('Tandai konsultasi ini sebagai selesai?')"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tandai Selesai
                        </button>
                    </form>
                @endif
                <a href="{{ route('dokter.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Riwayat Percakapan -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Riwayat Percakapan
                </h3>
                
                <div class="space-y-4 max-h-96 overflow-y-auto p-4 border border-gray-200 rounded-lg bg-gray-50">
                    @forelse($konsultasi->balasan as $msg)
                        <div class="flex {{ $msg->pengirim === 'dokter' ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-xs md:max-w-md px-4 py-3 {{ $msg->pengirim === 'dokter' ? 'chat-bubble-doctor' : 'chat-bubble-client' }}">
                                <p class="text-sm">{!! nl2br(e($msg->isi)) !!}</p>
                                <p class="text-xs opacity-70 mt-2 text-right">
                                    {{ $msg->created_at->format('d/m/Y H:i') }}
                                    {{ $msg->pengirim === 'dokter' ? ' (Dokter)' : ' (Klien)' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p>Belum ada percakapan.</p>
                            <p class="text-sm mt-1">Mulai percakapan dengan mengirim balasan di bawah.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Form Balasan (hanya jika status diterima/selesai) -->
            @if($konsultasi->status !== 'pending')
                <form method="POST" action="{{ route('dokter.konsultasi.balas', $konsultasi->id) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Balasan / Catatan Medis
                            </label>
                            <textarea name="balasan" rows="5" 
                                class="w-full border border-gray-300 rounded-lg p-4 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200"
                                placeholder="Tulis diagnosa, saran perawatan, resep obat, atau rekomendasi tindakan medis..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition duration-200 shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Balasan & Simpan Catatan
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                    <p class="text-yellow-800">Konsultasi masih dalam status <strong>pending</strong>. Silakan terima terlebih dahulu untuk dapat mengirim balasan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection