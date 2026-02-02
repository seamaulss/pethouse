@extends('layouts.user')

@section('title', 'Perpanjang Booking - PetHouse')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('user.booking.riwayat') }}" 
                   class="mr-4 text-gray-500 hover:text-teal-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Perpanjang Booking</h1>
            </div>
            
            <!-- Informasi Booking -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Kode Booking:</span>
                        <div class="font-bold text-teal-600">{{ $booking->kode_booking }}</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Hewan:</span>
                        <div class="font-bold">{{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal Masuk:</span>
                        <div>{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal Keluar Saat Ini:</span>
                        <div class="font-bold">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Harga -->
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                <h3 class="font-medium text-teal-800 mb-2">Informasi Harga:</h3>
                @php
                    // Cari harga per hari untuk jenis hewan ini
                    $jenisHewan = \App\Models\JenisHewan::where('nama', $booking->jenis_hewan)->first();
                    $hargaPerHari = 0;
                    
                    if ($jenisHewan) {
                        $harga = \Illuminate\Support\Facades\DB::table('layanan_harga')
                            ->where('layanan_id', $booking->layanan_id)
                            ->where('jenis_hewan_id', $jenisHewan->id)
                            ->value('harga_per_hari');
                        $hargaPerHari = $harga ?? 0;
                    }
                @endphp
                <div class="flex justify-between items-center">
                    <span>Harga per hari:</span>
                    <span class="font-bold text-teal-600" id="harga-per-hari">Rp {{ number_format($hargaPerHari, 0, ',', '.') }}</span>
                </div>
                <div class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Biaya perpanjangan akan dihitung berdasarkan jumlah hari tambahan.
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Perpanjangan -->
        <form method="POST" action="{{ route('user.booking.extend', $booking->id) }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Tanggal Keluar Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Keluar Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal_keluar_baru" 
                           id="tanggal_keluar_baru"
                           min="{{ $minDate ?? \Carbon\Carbon::parse($booking->tanggal_keluar)->addDay()->format('Y-m-d') }}"
                           max="{{ $maxDate ?? \Carbon\Carbon::parse($booking->tanggal_keluar)->addDays(30)->format('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           required>
                    <div class="text-sm text-gray-500 mt-2 space-y-1">
                        <p>Tanggal minimal: {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->addDay()->format('d/m/Y') }}</p>
                        <p>Tanggal maksimal: {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->addDays(30)->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Alasan Perpanjangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Perpanjangan (Opsional)
                    </label>
                    <textarea name="alasan_perpanjangan" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Berikan alasan perpanjangan jika diperlukan...">{{ old('alasan_perpanjangan') }}</textarea>
                </div>

                <!-- Durasi dan Perkiraan Biaya -->
                <div id="harga-info" class="bg-teal-50 p-4 rounded-lg hidden">
                    <h4 class="font-medium text-teal-800 mb-2">Rincian Perpanjangan:</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Tanggal keluar lama:</span>
                            <span id="tanggal-lama" class="font-bold">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tanggal keluar baru:</span>
                            <span id="tanggal-baru" class="font-bold"></span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between">
                                <span>Durasi Perpanjangan:</span>
                                <span id="durasi" class="font-bold text-teal-600"></span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span>Harga per hari:</span>
                                <span id="harga-hari">Rp {{ number_format($hargaPerHari, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Biaya Perpanjangan:</span>
                                <span id="biaya" class="text-green-600"></span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 mt-2 bg-white p-2 rounded">
                            <i class="fas fa-info-circle mr-1"></i>
                            Biaya ini akan ditagihkan setelah perpanjangan disetujui oleh admin.
                        </div>
                    </div>
                </div>

                <!-- Informasi Penting -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-yellow-800 mb-1">Perhatian!</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Perpanjangan membutuhkan persetujuan admin terlebih dahulu</li>
                                <li>• Status booking akan berubah menjadi "Perpanjangan Diajukan"</li>
                                <li>• Anda akan mendapatkan notifikasi setelah admin menyetujui/menolak</li>
                                <li>• Maksimal perpanjangan adalah 30 hari</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-4 pt-4">
                    <a href="{{ route('user.booking.riwayat') }}"
                       class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit"
                            id="submit-btn"
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i> Ajukan Perpanjangan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    #submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalKeluarBaru = document.getElementById('tanggal_keluar_baru');
    const hargaInfo = document.getElementById('harga-info');
    const durasiSpan = document.getElementById('durasi');
    const biayaSpan = document.getElementById('biaya');
    const tanggalBaruSpan = document.getElementById('tanggal-baru');
    const submitBtn = document.getElementById('submit-btn');
    
    // Format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }
    
    // Harga per hari dari PHP variable
    const hargaPerHari = {{ $hargaPerHari }};
    
    function hitungPerpanjangan() {
        const tanggalKeluarLama = new Date('{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format("Y-m-d") }}');
        const tanggalKeluarBaruValue = tanggalKeluarBaru.value;
        
        if (!tanggalKeluarBaruValue) {
            hargaInfo.classList.add('hidden');
            submitBtn.disabled = true;
            return;
        }
        
        const tanggalBaru = new Date(tanggalKeluarBaruValue);
        
        // Hitung durasi (dalam hari)
        const timeDiff = tanggalBaru.getTime() - tanggalKeluarLama.getTime();
        const durasi = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (durasi > 0 && durasi <= 30) {
            // Hitung biaya
            const biaya = durasi * hargaPerHari;
            
            // Format tanggal
            const formatTanggal = (date) => {
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };
            
            // Tampilkan informasi
            tanggalBaruSpan.textContent = formatTanggal(tanggalBaru);
            durasiSpan.textContent = durasi + ' hari';
            biayaSpan.textContent = formatRupiah(biaya);
            
            // Show info section
            hargaInfo.classList.remove('hidden');
            submitBtn.disabled = false;
        } else {
            hargaInfo.classList.add('hidden');
            submitBtn.disabled = true;
            
            if (durasi > 30) {
                alert('Maksimal perpanjangan adalah 30 hari');
            }
        }
    }
    
    // Event listeners
    tanggalKeluarBaru.addEventListener('change', hitungPerpanjangan);
    tanggalKeluarBaru.addEventListener('input', hitungPerpanjangan);
    
    // Set tanggal minimal
    const today = new Date().toISOString().split('T')[0];
    const minDate = '{{ $minDate ?? \Carbon\Carbon::parse($booking->tanggal_keluar)->addDay()->format("Y-m-d") }}';
    tanggalKeluarBaru.min = minDate;
    
    // Set max date (30 hari dari tanggal keluar)
    const maxDate = '{{ $maxDate ?? \Carbon\Carbon::parse($booking->tanggal_keluar)->addDays(30)->format("Y-m-d") }}';
    tanggalKeluarBaru.max = maxDate;
    
    // Hitung otomatis jika ada value sebelumnya
    if (tanggalKeluarBaru.value) {
        hitungPerpanjangan();
    }
    
    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const tanggalKeluarLama = new Date('{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format("Y-m-d") }}');
        const tanggalBaruValue = tanggalKeluarBaru.value;
        
        if (!tanggalBaruValue) {
            e.preventDefault();
            alert('Silakan pilih tanggal keluar baru');
            return;
        }
        
        const tanggalBaru = new Date(tanggalBaruValue);
        const timeDiff = tanggalBaru.getTime() - tanggalKeluarLama.getTime();
        const durasi = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (durasi <= 0) {
            e.preventDefault();
            alert('Tanggal keluar baru harus setelah tanggal keluar saat ini');
            return;
        }
        
        if (durasi > 30) {
            e.preventDefault();
            alert('Maksimal perpanjangan adalah 30 hari');
            return;
        }
        
        // Konfirmasi
        if (!confirm('Anda yakin ingin mengajukan perpanjangan booking?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection