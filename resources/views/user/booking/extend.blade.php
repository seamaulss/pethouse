@extends('layouts.user')

@section('title', 'Perpanjang Booking - LARAPetHouse')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="h-2 bg-teal-600"></div>

        <div class="p-6 sm:p-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div class="flex items-center">
                    <a href="{{ route('user.booking.riwayat') }}" 
                       class="group mr-4 bg-gray-50 p-3 rounded-full text-gray-500 hover:text-teal-600 hover:bg-teal-50 transition-all duration-300">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Perpanjang Booking</h1>
                        <p class="text-sm text-gray-500">Ajukan tambahan durasi penitipan hewan Anda</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Detail Booking</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Kode Booking</span>
                            <span class="font-bold text-teal-600">{{ $booking->kode_booking }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Nama Hewan</span>
                            <span class="font-bold text-gray-800">{{ $booking->nama_hewan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Jenis</span>
                            <span class="px-2 py-0.5 bg-gray-200 rounded text-xs font-medium">{{ $booking->jenis_hewan }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Jadwal Saat Ini</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Check-in</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Check-out</span>
                            <span class="font-bold text-orange-600">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $jenisHewan = \App\Models\JenisHewan::where('nama', $booking->jenis_hewan)->first();
                $hargaPerHari = 0;
                if ($jenisHewan) {
                    $hargaPerHari = \Illuminate\Support\Facades\DB::table('layanan_harga')
                        ->where('layanan_id', $booking->layanan_id)
                        ->where('jenis_hewan_id', $jenisHewan->id)
                        ->value('harga_per_hari') ?? 0;
                }
            @endphp

            @if(session('error') || $errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span class="font-medium">{{ session('error') ?? 'Mohon periksa kembali form Anda.' }}</span>
                    </div>
                    @if($errors->any())
                    <ul class="mt-2 text-sm ml-7 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('user.booking.extend', $booking->id) }}" class="space-y-8">
                @csrf
                
                {{-- Data Hidden untuk JavaScript agar editor tidak error --}}
                <input type="hidden" id="harga_per_hari_val" value="{{ $hargaPerHari }}">
                <input type="hidden" id="tgl_keluar_lama_val" value="{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('Y-m-d') }}">

                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-teal-600">
                            Tanggal Keluar Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="date" 
                                   name="tanggal_keluar_baru" 
                                   id="tanggal_keluar_baru"
                                   {{-- Diperbaiki ke format Y-m-d agar kalender muncul --}}
                                   min="{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->addDay()->format('Y-m-d') }}"
                                   max="{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->addDays(30)->format('Y-m-d') }}"
                                   class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all"
                                   required>
                        </div>
                        <div class="flex justify-between mt-2 px-1">
                            <p class="text-[11px] text-gray-400 italic">Min: {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->addDay()->translatedFormat('d/m/Y') }}</p>
                            <p class="text-[11px] text-gray-400 italic">Max: +30 hari</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alasan Perpanjangan <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="alasan_perpanjangan" 
                                  rows="3"
                                  class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-gray-400"
                                  placeholder="Contoh: Masih ada urusan mendadak di luar kota...">{{ old('alasan_perpanjangan') }}</textarea>
                    </div>

                    <div id="harga-info" class="hidden transform transition-all duration-500 opacity-0">
                        <div class="bg-gradient-to-br from-teal-50 to-white border border-teal-100 rounded-2xl p-6 shadow-sm">
                            <h4 class="text-teal-800 font-bold flex items-center mb-4">
                                <i class="fas fa-file-invoice-dollar mr-2"></i> Estimasi Biaya Tambahan
                            </h4>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Durasi Tambahan</span>
                                    <span id="durasi" class="font-bold text-gray-900 bg-teal-100 px-2 rounded"></span>
                                </div>
                                <div class="flex justify-between text-gray-600 border-b border-dashed border-teal-200 pb-3">
                                    <span>Tarif Harian</span>
                                    <span class="font-medium text-gray-900">Rp {{ number_format($hargaPerHari, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-gray-800 font-semibold uppercase tracking-tighter">Total Tagihan Baru</span>
                                    <span id="biaya" class="text-2xl font-black text-teal-600 tracking-tight"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-xl p-4 flex items-start border border-amber-100">
                        <div class="bg-amber-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-shield-alt text-amber-600"></i>
                        </div>
                        <div class="text-xs text-amber-800 leading-relaxed">
                            <strong>Konfirmasi:</strong> Dengan menekan tombol kirim, Anda setuju bahwa perpanjangan ini memerlukan 
                            persetujuan admin dan akan ada biaya tambahan sesuai durasi yang dipilih.
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                            id="submit-btn"
                            disabled
                            class="flex-[2] order-1 sm:order-2 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-teal-200 transition-all flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane mr-2"></i> Ajukan Sekarang
                    </button>
                    <a href="{{ route('user.booking.riwayat') }}"
                       class="flex-1 order-2 sm:order-1 bg-white border border-gray-200 text-gray-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    #submit-btn:disabled {
        opacity: 0.6;
        filter: grayscale(1);
        cursor: not-allowed;
        transform: none !important;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Element
    const tanggalKeluarBaru = document.getElementById('tanggal_keluar_baru');
    const hargaInfo = document.getElementById('harga-info');
    const durasiSpan = document.getElementById('durasi');
    const biayaSpan = document.getElementById('biaya');
    const submitBtn = document.getElementById('submit-btn');
    
    // Mengambil data dari input hidden (Menghindari error Decorators)
    const hargaPerHari = parseInt(document.getElementById('harga_per_hari_val').value) || 0;
    const tglLamaStr = document.getElementById('tgl_keluar_lama_val').value;
    
    function hitungPerpanjangan() {
        const tanggalKeluarLama = new Date(tglLamaStr);
        const tanggalKeluarBaruValue = tanggalKeluarBaru.value;
        
        if (!tanggalKeluarBaruValue) {
            hargaInfo.classList.add('hidden', 'opacity-0');
            submitBtn.disabled = true;
            return;
        }
        
        const tanggalBaru = new Date(tanggalKeluarBaruValue);
        const timeDiff = tanggalBaru.getTime() - tanggalKeluarLama.getTime();
        const durasi = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (durasi > 0 && durasi <= 30) {
            const biaya = durasi * hargaPerHari;
            
            durasiSpan.textContent = `+${durasi} Hari`;
            biayaSpan.textContent = 'Rp ' + biaya.toLocaleString('id-ID');
            
            hargaInfo.classList.remove('hidden');
            // Sedikit delay agar transisi Tailwind berjalan
            setTimeout(() => hargaInfo.classList.remove('opacity-0'), 10);
            submitBtn.disabled = false;
        } else {
            hargaInfo.classList.add('hidden', 'opacity-0');
            submitBtn.disabled = true;
            if (durasi > 30) {
                alert('Maksimal perpanjangan adalah 30 hari');
                tanggalKeluarBaru.value = '';
            } else if (durasi <= 0 && tanggalKeluarBaruValue !== "") {
                alert('Tanggal baru harus setelah tanggal keluar saat ini');
                tanggalKeluarBaru.value = '';
            }
        }
    }
    
    // Event Listener
    tanggalKeluarBaru.addEventListener('change', hitungPerpanjangan);
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin data perpanjangan sudah benar?')) {
                e.preventDefault();
            }
        });
    }

    // Jalankan pengecekan awal jika sudah ada value (misal saat redirect back)
    if (tanggalKeluarBaru.value) hitungPerpanjangan();
});
</script>
@endpush
@endsection