@extends('petugas.layouts.app')

@section('title', 'Petugas - Update Harian')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header dengan Pilih Tanggal -->
    <div class="bg-white p-6 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-teal-600 mb-4 md:mb-0">
                🐾 Update Harian {{ $booking->nama_hewan }}
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
                    @if($log)
                        <span class="text-green-600">✓ Sudah diisi</span>
                    @else
                        <span class="text-red-600">✗ Belum diisi</span>
                    @endif
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

    <!-- Form Input Log -->
    <div class="bg-white p-8 rounded-2xl shadow-xl">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('petugas.input-log.store', $booking->id) }}" class="space-y-6">
            @csrf
            
            <!-- Hidden field untuk tanggal -->
            <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
            
            @php
                $activities = [
                    'makan_pagi'  => ['label' => 'Makan Pagi', 'jam' => 'jam_makan_pagi', 'icon' => '☀️'],
                    'makan_siang' => ['label' => 'Makan Siang', 'jam' => 'jam_makan_siang', 'icon' => '🕛'],
                    'makan_malam' => ['label' => 'Makan Malam', 'jam' => 'jam_makan_malam', 'icon' => '🌙'],
                    'minum'       => ['label' => 'Minum', 'jam' => 'jam_minum', 'icon' => '💧'],
                    'jalan_jalan' => ['label' => 'Jalan-jalan', 'jam' => 'jam_jalan_jalan', 'icon' => '🏃'],
                ];
            @endphp

            @foreach($activities as $name => $a)
                @php
                    // Checkbox logic dengan old input
                    $checked = '';
                    if (old($name) !== null) {
                        $checked = old($name) ? 'checked' : '';
                    } else if ($log && $log->$name) {
                        $checked = 'checked';
                    }
                    
                    // Time logic dengan old input
                    $jamVal = '';
                    if (old($a['jam']) !== null) {
                        $jamVal = old($a['jam']);
                    } else if ($log && $log->{$a['jam']}) {
                        $jamVal = \Carbon\Carbon::parse($log->{$a['jam']})->format('H:i');
                    }
                @endphp
                
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition">
                    <input type="checkbox" name="{{ $name }}" {{ $checked }} class="w-5 h-5 text-teal-600">
                    <span class="flex-1 text-lg">{{ $a['icon'] }} {{ $a['label'] }}</span>
                    <input type="time" name="{{ $a['jam'] }}" value="{{ $jamVal }}"
                           class="border rounded-lg px-3 py-2 w-32">
                </div>
            @endforeach

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-semibold block mb-2">Buang Air</label>
                    <select name="buang_air" class="w-full border rounded-lg px-4 py-3">
                        @php
                            $options = ['belum', 'normal', 'diare', 'sembelit'];
                            $selectedBuangAir = old('buang_air', $log->buang_air ?? 'belum');
                        @endphp
                        
                        @foreach($options as $option)
                            <option value="{{ $option }}" {{ $selectedBuangAir == $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-semibold block mb-2">Catatan Harian</label>
                    <textarea name="catatan" class="w-full border rounded-lg p-4 h-full" rows="3" 
                              placeholder="Catatan khusus untuk hari ini...">{{ old('catatan', $log->catatan ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 border-t">
                <div>
                    @if($log)
                        <p class="text-sm text-gray-600">
                            Terakhir diupdate: {{ $log->updated_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
                
                <button type="submit" 
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition">
                    💾 Simpan Update untuk {{ \Carbon\Carbon::parse($selectedDate)->format('d M') }}
                </button>
            </div>
        </form>
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