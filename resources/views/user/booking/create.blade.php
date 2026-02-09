@extends('layouts.user')

@section('title', 'Booking Penitipan - PetHouse')

@push('styles')
<style>
    .card-form {
        border-radius: 2rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        background: white;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 20px 40px rgba(13, 148, 136, 0.4);
    }

    /* Sidebar Aksi */
    .sidebar-aksi {
        position: sticky;
        top: 2rem;
        transition: all 0.3s ease;
    }

    .sidebar-item {
        border-left: 4px solid transparent;
        transition: all 0.2s ease;
    }

    .sidebar-item:hover {
        border-left-color: #0d9488;
        background-color: #f0fdfa;
        transform: translateX(5px);
    }

    .sidebar-item.active {
        border-left-color: #0d9488;
        background-color: #f0fdfa;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar Aksi -->
        <div class="lg:col-span-1">
            <div class="sidebar-aksi bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-teal-600 mb-4 flex items-center">
                    <i class="fas fa-bars mr-2"></i> Menu Booking
                </h3>

                <div class="space-y-2">
                    <!-- Create Booking (Active) -->
                    <a href="{{ route('user.booking.create') }}"
                        class="sidebar-item active block p-3 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                                <i class="fas fa-plus text-teal-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Buat Booking Baru</div>
                                <div class="text-sm text-gray-500">Form booking baru</div>
                            </div>
                        </div>
                    </a>

                    <!-- Riwayat Booking -->
                    <a href="{{ route('user.booking.riwayat') }}"
                        class="sidebar-item block p-3 rounded-lg hover:bg-teal-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Riwayat Booking</div>
                                <div class="text-sm text-gray-500">Lihat semua booking</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Quick Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-bold text-gray-700 mb-3">Aksi Cepat</h4>
                    <div class="space-y-2">
                        @auth
                        @php
                        // Menggunakan model Booking langsung untuk menghindari error
                        $userId = auth()->id();
                        $latestBooking = \App\Models\Booking::where('user_id', $userId)->latest()->first();
                        $pendingBookings = \App\Models\Booking::where('user_id', $userId)->where('status', 'pending')->count();
                        $activeBookings = \App\Models\Booking::where('user_id', $userId)
                        ->whereIn('status', ['diterima', 'in_progress'])
                        ->count();
                        @endphp

                        @if($pendingBookings > 0)
                        <a href="{{ route('user.booking.riwayat') }}?status=pending"
                            class="flex items-center text-sm text-yellow-600 hover:text-yellow-700 p-2">
                            <i class="fas fa-clock mr-2"></i> {{ $pendingBookings }} Booking Pending
                        </a>
                        @endif

                        @if($activeBookings > 0)
                        <a href="{{ route('user.booking.riwayat') }}?status=active"
                            class="flex items-center text-sm text-blue-600 hover:text-blue-700 p-2">
                            <i class="fas fa-calendar-check mr-2"></i> {{ $activeBookings }} Booking Aktif
                        </a>
                        @endif

                        <!-- Link tetap untuk extend booking -->
                        @if($latestBooking && in_array($latestBooking->status, ['diterima', 'in_progress']) && \Carbon\Carbon::parse($latestBooking->tanggal_keluar)->gte(\Carbon\Carbon::today()))
                        <a href="{{ route('user.booking.extend.form', $latestBooking->id) }}"
                            class="flex items-center text-sm text-purple-600 hover:text-purple-700 p-2">
                            <i class="fas fa-calendar-plus mr-2"></i> Perpanjang Booking Terakhir
                        </a>
                        @endif
                        @endauth

                        <a href="{{ route('user.hewan-saya') }}"
                            class="flex items-center text-sm text-gray-600 hover:text-teal-700 p-2">
                            <i class="fas fa-paw mr-2"></i> Kelola Hewan Saya
                        </a>
                    </div>
                </div>

                <!-- Informasi -->
                <div class="mt-6 p-4 bg-teal-50 rounded-lg">
                    <h4 class="text-sm font-bold text-teal-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Informasi
                    </h4>
                    <ul class="text-xs text-teal-700 space-y-1">
                        <li>• Pastikan data hewan lengkap</li>
                        <li>• Pilih tanggal dengan benar</li>
                        <li>• Upload bukti DP jika sudah transfer</li>
                        <li>• Konfirmasi via WhatsApp akan dikirim</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Booking -->
        <div class="lg:col-span-3">
            <div class="card-form p-6 sm:p-8 lg:p-10" data-aos="fade-up">
                <div class="text-center mb-8">
                    <h1 class="text-3xl sm:text-4xl font-bold text-teal-600 mb-3">
                        <i class="fas fa-calendar-plus mr-2"></i> Booking Penitipan Hewan
                    </h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Isi form di bawah ini dengan lengkap. Kami akan segera konfirmasi via WhatsApp.
                    </p>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center" data-aos="fade-up">
                    <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center" data-aos="fade-up">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <div>
                        {!! nl2br(e(session('success'))) !!}
                    </div>
                </div>
                @endif

                    <form method="post" action="{{ route('user.booking.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Data Pemilik -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-teal-600"></i> Nama Pemilik <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_pemilik" required
                                    value="{{ old('nama_pemilik', $lastBooking->nama_pemilik ?? auth()->user()->username) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base"
                                    placeholder="Nama lengkap Anda">
                            </div>

                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-pink-500"></i> Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" required
                                    value="{{ old('email', $lastBooking->email ?? auth()->user()->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base"
                                    placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">
                                <i class="fab fa-whatsapp mr-2 text-green-500"></i> Nomor WhatsApp (opsional)
                            </label>
                            <input type="text" name="nomor_wa"
                                value="{{ old('nomor_wa', $lastBooking->nomor_wa ?? auth()->user()->nomor_wa) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base"
                                autocomplete="off"
                                placeholder="08xx-xxxx-xxxx">
                        </div>

                        <!-- Data Hewan -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    <i class="fas fa-paw mr-2 text-pink-500"></i> Nama Hewan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_hewan" required
                                    value="{{ old('nama_hewan') }}"
                                    autocomplete="off"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base"
                                    placeholder="Nama hewan kesayangan">
                            </div>

                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    Jenis Hewan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_hewan" required id="jenis_hewan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisHewan as $jh)
                                    <option value="{{ $jh->nama }}" {{ old('jenis_hewan') == $jh->nama ? 'selected' : '' }}>
                                        {{ $jh->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    Ukuran Hewan <span class="text-red-500">*</span>
                                </label>
                                <select name="ukuran_hewan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <option value="Kecil" {{ old('ukuran_hewan') == 'Kecil' ? 'selected' : '' }}>Kecil (&lt;10kg)</option>
                                    <option value="Sedang" {{ old('ukuran_hewan') == 'Sedang' ? 'selected' : '' }}>Sedang (10-25kg)</option>
                                    <option value="Besar" {{ old('ukuran_hewan') == 'Besar' ? 'selected' : '' }}>Besar (&gt;25kg)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Layanan & Harga -->
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">
                                <i class="fas fa-concierge-bell mr-2 text-amber-500"></i> Layanan <span class="text-red-500">*</span>
                            </label>
                            <select name="layanan_id" required id="layanan_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanan as $l)
                                <option value="{{ $l->id }}" data-harga="{{ $l->harga ?? 0 }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>
                                    {{ $l->nama_layanan }}
                                </option>
                                @endforeach
                            </select>
                            <div id="harga-tampil" class="mt-3 text-base min-h-[100px]"></div>
                        </div>

                        <!-- Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-check mr-2 text-teal-600"></i> Tanggal Masuk <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base">
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-times mr-2 text-pink-500"></i> Tanggal Keluar <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_keluar" id="tanggal_keluar" value="{{ old('tanggal_keluar') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base">
                            </div>
                        </div>

                        <!-- DP -->
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-4">
                                <i class="fas fa-money-bill-wave mr-2 text-green-600"></i> Sudah Transfer ? <span class="text-red-500">*</span>
                            </label>    
                            <div class="flex flex-wrap gap-6">
                                <label class="flex items-center text-base">
                                    <input type="radio" name="dp_dibayar" value="Ya" {{ old('dp_dibayar') == 'Ya' ? 'checked' : '' }} required class="w-5 h-5 text-teal-600">
                                    <span class="ml-3">Ya, sudah transfer</span>
                                </label>
                                <label class="flex items-center text-base">
                                    <input type="radio" name="dp_dibayar" value="Tidak" {{ old('dp_dibayar') == 'Tidak' ? 'checked' : '' }} required class="w-5 h-5 text-teal-600">
                                    <span class="ml-3">Belum, akan transfer nanti</span>
                                </label>
                            </div>
                        </div>

                        <!-- Bukti Transfer DP -->
                        <div id="bukti-dp-wrapper" class="{{ old('dp_dibayar') == 'Ya' ? '' : 'hidden' }}">
                            <label class="block text-lg font-medium text-gray-700 mb-2">
                                <i class="fas fa-receipt mr-2 text-teal-600"></i> Upload Bukti Transfer DP <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center">
                                <input type="file" name="bukti_dp" accept="image/*" id="bukti-dp-input"
                                    class="hidden"
                                    onchange="previewImage(this)">
                                <div id="image-preview" class="mb-3">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-600">Klik untuk upload bukti transfer</p>
                                </div>
                                <button type="button"
                                    onclick="document.getElementById('bukti-dp-input').click()"
                                    class="px-4 py-2 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200">
                                    <i class="fas fa-upload mr-2"></i> Pilih File
                                </button>
                                <p class="text-sm text-gray-500 mt-2">
                                    Format JPG / PNG, maksimal 2MB
                                </p>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note mr-2 text-amber-500"></i> Catatan Tambahan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="catatan" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base resize-none"
                                placeholder="Ceritakan kebiasaan, makanan favorit, alergi, atau hal penting lain tentang hewan Anda...">{{ old('catatan') }}</textarea>
                        </div>

                        <!-- Submit -->
                        <div class="text-center pt-4">
                            <button type="submit" class="btn-primary text-white font-bold px-10 py-4 rounded-full shadow-xl text-lg inline-flex items-center">
                                <i class="fas fa-paper-plane mr-3 text-xl"></i>
                                Kirim Booking Sekarang
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const layananSelect = document.querySelector('select[name="layanan_id"]');
        const jenisSelect = document.querySelector('select[name="jenis_hewan"]');
        const tanggalMasuk = document.getElementById('tanggal_masuk');
        const tanggalKeluar = document.getElementById('tanggal_keluar');
        const hargaDiv = document.getElementById('harga-tampil');

        // Format tanggal input minimal hari ini
        const today = new Date().toISOString().split('T')[0];
        tanggalMasuk.setAttribute('min', today);
        tanggalKeluar.setAttribute('min', today);

        // Update tanggal keluar minimal saat tanggal masuk berubah
        tanggalMasuk.addEventListener('change', function() {
            const tglMasuk = this.value;
            tanggalKeluar.setAttribute('min', tglMasuk);

            // Reset tanggal keluar jika kurang dari tanggal masuk baru
            const tglKeluar = tanggalKeluar.value;
            if (tglKeluar && tglKeluar < tglMasuk) {
                tanggalKeluar.value = tglMasuk;
            }
            hitungTotalHarga();
        });

        // Fungsi untuk mendapatkan harga per hari dari server
        async function getHargaPerHari(layananId, jenisHewan) {
            if (!layananId || !jenisHewan) return 0;

            try {
                const response = await fetch(`{{ route('user.booking.get-harga') }}?layanan_id=${layananId}&jenis_hewan=${encodeURIComponent(jenisHewan)}`);
                const data = await response.json();
                return data.harga ? parseFloat(data.harga) : 0;
            } catch (error) {
                console.error('Error fetching harga:', error);
                return 0;
            }
        }

        // Fungsi untuk memformat angka ke format Rupiah
        function formatRupiah(angka) {
            // Jika angka kurang dari 1000, anggap itu dalam ribuan
            if (angka < 1000 && angka > 0) {
                angka = angka * 1000;
            }

            return angka.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Fungsi untuk menghitung dan menampilkan total harga
        async function hitungTotalHarga() {
            const layananId = layananSelect.value;
            const jenisHewan = jenisSelect.value;
            const tglMasuk = tanggalMasuk.value;
            const tglKeluar = tanggalKeluar.value;

            // Reset tampilan
            hargaDiv.innerHTML = '';

            // Validasi data yang dibutuhkan
            if (!layananId || !jenisHewan) {
                return;
            }

            // Tampilkan loading jika belum ada tanggal
            if (!tglMasuk || !tglKeluar) {
                // Hanya tampilkan harga per hari jika tanggal belum lengkap
                hargaDiv.innerHTML = `
                    <div class="text-gray-500 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pilih tanggal masuk dan keluar untuk melihat total harga
                    </div>
                `;
                return;
            }

            // Validasi tanggal
            const masuk = new Date(tglMasuk);
            const keluar = new Date(tglKeluar);

            if (keluar < masuk) {
                hargaDiv.innerHTML = `
                    <div class="text-red-600 p-3 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Tanggal keluar tidak boleh kurang dari tanggal masuk
                    </div>
                `;
                return;
            }

            // Hitung durasi
            const durasi = Math.ceil((keluar - masuk) / (1000 * 60 * 60 * 24));
            if (durasi < 1) {
                hargaDiv.innerHTML = `
                    <div class="text-red-600 p-3 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Durasi penitipan minimal 1 hari
                    </div>
                `;
                return;
            }

            // Dapatkan harga per hari
            const hargaPerHari = await getHargaPerHari(layananId, jenisHewan);

            if (hargaPerHari > 0) {
                const totalHarga = durasi * hargaPerHari;
                const hargaPerHariFormatted = formatRupiah(hargaPerHari);
                const totalHargaFormatted = formatRupiah(totalHarga);

                hargaDiv.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center text-teal-600 p-3 bg-teal-50 rounded-lg">
                            <i class="fas fa-tag mr-2 text-pink-500"></i>
                            <span>Harga per hari: <strong>Rp ${hargaPerHariFormatted}</strong></span>
                        </div>
                        <div class="flex items-center text-green-600 bg-green-50 p-4 rounded-lg border border-green-200">
                            <i class="fas fa-calculator mr-3 text-green-500 text-xl"></i>
                            <div>
                                <div class="font-medium">Total untuk ${durasi} hari:</div>
                                <div class="text-2xl font-bold">Rp ${totalHargaFormatted}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    ${durasi} hari × Rp ${hargaPerHariFormatted}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                hargaDiv.innerHTML = `
                    <div class="text-gray-500 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-info-circle mr-2"></i>
                        Harga tidak tersedia untuk kombinasi ini
                    </div>
                `;
            }
        }

        // Event listeners untuk semua input yang mempengaruhi perhitungan harga
        layananSelect.addEventListener('change', hitungTotalHarga);
        jenisSelect.addEventListener('change', hitungTotalHarga);
        tanggalMasuk.addEventListener('change', hitungTotalHarga);
        tanggalKeluar.addEventListener('change', hitungTotalHarga);

        // Tampilkan harga awal jika ada data lama
        hitungTotalHarga();

        // Toggle Bukti DP
        const radios = document.querySelectorAll('input[name="dp_dibayar"]');
        const buktiWrapper = document.getElementById('bukti-dp-wrapper');

        function toggleBukti() {
            const selected = document.querySelector('input[name="dp_dibayar"]:checked');
            if (selected && selected.value === 'Ya') {
                buktiWrapper.classList.remove('hidden');
                // Tambahkan required attribute ke input file
                document.getElementById('bukti-dp-input').setAttribute('required', 'required');
            } else {
                buktiWrapper.classList.add('hidden');
                // Hapus required attribute
                document.getElementById('bukti-dp-input').removeAttribute('required');
            }
        }

        radios.forEach(r => r.addEventListener('change', toggleBukti));
        toggleBukti(); // trigger awal

        // Fungsi untuk preview image
        function previewImage(input) {
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="flex flex-col items-center">
                            <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg mb-2 border">
                            <p class="text-sm text-gray-600">${input.files[0].name}</p>
                        </div>
                    `;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Fungsi untuk menampilkan harga default dari data-harga attribute
        function tampilkanHargaDefault() {
            const selectedOption = layananSelect.options[layananSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const harga = selectedOption.getAttribute('data-harga');
                if (harga && parseFloat(harga) > 0) {
                    const hargaFormatted = formatRupiah(parseFloat(harga));
                    hargaDiv.innerHTML = `
                        <div class="text-gray-500 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pilih jenis hewan dan tanggal untuk melihat harga
                        </div>
                    `;
                }
            }
        }

        // Tampilkan harga default saat layanan dipilih
        layananSelect.addEventListener('change', function() {
            tampilkanHargaDefault();
            hitungTotalHarga();
        });

        // Tampilkan harga default saat halaman dimuat
        tampilkanHargaDefault();
    });
</script>
@endpush