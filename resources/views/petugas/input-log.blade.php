@extends('petugas.layouts.app')

@section('title', 'Petugas - Input Log Kegiatan')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header dengan Pilih Tanggal -->
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-teal-600 mb-4 md:mb-0">
                📝 Log Kegiatan {{ $booking->nama_hewan }}
            </h1>
            
            <!-- Form Pilih Tanggal -->
            <form method="GET" action="{{ route('petugas.input-log.show', $booking->id) }}" 
                  class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="font-semibold">Pilih Tanggal:</span>
                    <input type="date" name="tanggal" value="{{ $selectedDate }}" 
                           min="{{ $booking->tanggal_masuk }}"
                           max="{{ $booking->tanggal_keluar }}"
                           class="border rounded-lg px-4 py-2">
                </div>
                <button type="submit" 
                        class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg">
                    Lihat
                </button>
            </form>
        </div>
        
        <!-- Info Booking -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-teal-50 p-4 rounded-lg">
                <p class="text-sm text-teal-600">Periode Booking</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }} 
                    - 
                    {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d M Y') }}
                </p>
                <p class="text-sm text-gray-600">
                    ({{ \Carbon\Carbon::parse($booking->tanggal_masuk)->diffInDays($booking->tanggal_keluar) }} hari)
                </p>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-blue-600">Tanggal Dipilih</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                    @if($selectedDate == date('Y-m-d'))
                        <span class="text-green-600 text-sm">(Hari Ini)</span>
                    @endif
                </p>
                <p class="text-sm text-gray-600">
                    @php
                        $dayNumber = \Carbon\Carbon::parse($booking->tanggal_masuk)->diffInDays($selectedDate) + 1;
                    @endphp
                    Hari ke-{{ $dayNumber }} dari {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->diffInDays($booking->tanggal_keluar) + 1 }} hari
                </p>
            </div>
            
            <div class="bg-amber-50 p-4 rounded-lg">
                <p class="text-sm text-amber-600">Status Log</p>
                <p class="font-semibold">
                    {{ $logs->count() }} kegiatan tercatat
                </p>
                <p class="text-sm text-gray-600">
                    {{ count($filledDates) }} dari {{ count($dates) }} hari sudah diisi
                </p>
            </div>
        </div>
        
        <!-- Tampilkan semua tanggal -->
        <div class="mb-6">
            <p class="font-semibold mb-2">Semua Tanggal:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($dates as $date)
                    @php
                        $isSelected = $date == $selectedDate;
                        $isFilled = in_array($date, $filledDates);
                        $isToday = $date == date('Y-m-d');
                        $dateObj = \Carbon\Carbon::parse($date);
                    @endphp
                    <a href="{{ route('petugas.input-log.show', ['booking' => $booking->id, 'tanggal' => $date]) }}"
                       class="px-3 py-2 rounded-lg border text-sm 
                              {{ $isSelected ? 'bg-teal-500 text-white border-teal-500' : 'bg-white border-gray-300' }}
                              {{ $isFilled ? 'border-green-500' : '' }}
                              {{ $isToday ? 'font-bold' : '' }}">
                        {{ $dateObj->format('d M') }}
                        @if($isFilled)
                            <span class="ml-1">✓</span>
                        @endif
                        @if($isToday)
                            <span class="ml-1">⭐</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Form Input Log Baru -->
    <div class="bg-white p-8 rounded-2xl shadow-xl mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Tambah Kegiatan Baru</h2>
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('petugas.input-log.store', $booking->id) }}" class="space-y-6">
            @csrf
            
            <!-- Hidden field untuk tanggal -->
            <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-semibold block mb-2">Jenis Kegiatan *</label>
                    <select name="kegiatan_id" class="w-full border rounded-lg px-4 py-3" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($masterKegiatan as $kegiatan)
                            <option value="{{ $kegiatan->id }}">
                                {{ $kegiatan->nama_kegiatan }}
                                @if($kegiatan->deskripsi)
                                    - {{ $kegiatan->deskripsi }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="font-semibold block mb-2">Waktu Kegiatan *</label>
                    <input type="time" name="waktu" value="{{ date('H:i') }}" 
                           class="w-full border rounded-lg px-4 py-3" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-semibold block mb-2">Jumlah</label>
                    <input type="text" name="jumlah" 
                           class="w-full border rounded-lg px-4 py-3" 
                           placeholder="Contoh: 1 mangkuk, 2 tablet">
                </div>
                
                <div>
                    <label class="font-semibold block mb-2">Satuan</label>
                    <input type="text" name="satuan" 
                           class="w-full border rounded-lg px-4 py-3" 
                           placeholder="Contoh: mangkuk, tablet, ml">
                </div>
            </div>
            
            <div>
                <label class="font-semibold block mb-2">Keterangan</label>
                <textarea name="keterangan" class="w-full border rounded-lg p-4" rows="2" 
                          placeholder="Contoh: Makan lahap, habis 1 mangkuk"></textarea>
            </div>
            
            <div>
                <label class="font-semibold block mb-2">Catatan Tambahan</label>
                <textarea name="catatan" class="w-full border rounded-lg p-4" rows="3" 
                          placeholder="Catatan khusus..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition">
                    💾 Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>
    
    <!-- Daftar Log Hari Ini -->
    <div class="bg-white p-8 rounded-2xl shadow-xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            📋 Kegiatan {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
            <span class="text-sm font-normal text-gray-600">({{ $logs->count() }} kegiatan)</span>
        </h2>
        
        @if($logs->count() > 0)
            <div class="space-y-4">
                @foreach($logs as $log)
                    <div class="border-l-4 border-teal-500 bg-gray-50 p-4 rounded-r-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg flex items-center">
                                    @if($log->kegiatan->icon)
                                        <i class="fas fa-{{ $log->kegiatan->icon }} mr-2"></i>
                                    @endif
                                    {{ $log->kegiatan->nama_kegiatan }}
                                    <span class="ml-2 text-sm bg-{{ $log->kegiatan->warna }}-100 text-{{ $log->kegiatan->warna }}-800 px-2 py-1 rounded">
                                        {{ \Carbon\Carbon::parse($log->waktu)->format('H:i') }}
                                    </span>
                                </h3>
                                
                                @if($log->keterangan)
                                    <p class="text-gray-700 mt-1">{{ $log->keterangan }}</p>
                                @endif
                                
                                @if($log->jumlah)
                                    <p class="text-gray-600 text-sm mt-1">
                                        Jumlah: <span class="font-semibold">{{ $log->jumlah }}</span>
                                        @if($log->satuan)
                                            {{ $log->satuan }}
                                        @endif
                                    </p>
                                @endif
                                
                                @if($log->catatan)
                                    <p class="text-gray-600 text-sm mt-2 border-t pt-2">
                                        <strong>Catatan:</strong> {{ $log->catatan }}
                                    </p>
                                @endif
                                
                                <p class="text-gray-500 text-xs mt-2">
                                    Dicatat oleh: {{ $log->petugas->username ?? 'Petugas' }}
                                </p>
                            </div>
                            
                            <form action="{{ route('petugas.input-log.destroy-log', $log->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin hapus log ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <p class="text-xl text-gray-600">Belum ada kegiatan yang dicatat untuk hari ini.</p>
                <p class="text-gray-500">Mulai dengan menambahkan kegiatan pertama di atas.</p>
            </div>
        @endif
    </div>
    
    <!-- Navigasi ke hari lain -->
    <div class="mt-6 flex justify-between">
        @php
            $prevDate = \Carbon\Carbon::parse($selectedDate)->subDay()->toDateString();
            $nextDate = \Carbon\Carbon::parse($selectedDate)->addDay()->toDateString();
            $canPrev = $prevDate >= $booking->tanggal_masuk;
            $canNext = $nextDate <= $booking->tanggal_keluar;
        @endphp
        
        @if($canPrev)
            <a href="{{ route('petugas.input-log.show', ['booking' => $booking->id, 'tanggal' => $prevDate]) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg">
                ← Sebelumnya ({{ \Carbon\Carbon::parse($prevDate)->format('d M') }})
            </a>
        @else
            <div></div>
        @endif
        
        @if($canNext)
            <a href="{{ route('petugas.input-log.show', ['booking' => $booking->id, 'tanggal' => $nextDate]) }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg">
                Berikutnya ({{ \Carbon\Carbon::parse($nextDate)->format('d M') }}) →
            </a>
        @endif
    </div>
</div>
@endsection