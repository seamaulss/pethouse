@extends('layouts.user')

@section('title', 'Booking Penitipan - LARAPetHouse')

@push('styles')
<style>
    /* Glassmorphism & Modern Shadows */
    .card-form {
        border-radius: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .card-form:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d9488, #14b8a6);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        filter: brightness(1.05);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Sidebar Enhancement */
    .sidebar-aksi {
        position: sticky;
        top: 2rem;
        z-index: 10;
    }

    .sidebar-item {
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
        background: transparent;
    }

    .sidebar-item:hover {
        border-left-color: #0d9488;
        background-color: #f0fdfa;
        padding-left: 1rem;
    }

    .sidebar-item.active {
        border-left-color: #0d9488;
        background-color: #f0fdfa;
        font-weight: 600;
    }

    /* Input Focus Effects */
    input:focus, select:focus, textarea:focus {
        transform: scale(1.01);
        transition: transform 0.2s ease;
    }

    /* Custom File Upload Styling */
    .upload-area {
        transition: all 0.3s ease;
        border: 2px dashed #e5e7eb;
    }

    .upload-area:hover {
        border-color: #2dd4bf;
        background-color: #f9fafb;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">

        <div class="lg:col-span-1 order-2 lg:order-1">
            <div class="sidebar-aksi bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <h3 class="text-lg font-bold text-teal-700 mb-5 flex items-center">
                    <span class="p-2 bg-teal-50 rounded-lg mr-3">
                        <i class="fas fa-th-large text-teal-600"></i>
                    </span>
                    Menu Booking
                </h3>

                <div class="space-y-3">
                    <a href="{{ route('user.booking.create') }}" class="sidebar-item active block p-3 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center mr-3 shadow-sm">
                                <i class="fas fa-plus text-teal-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Buat Booking</div>
                                <div class="text-xs text-gray-500 italic">Form penitipan baru</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.booking.riwayat') }}" class="sidebar-item block p-3 rounded-xl hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mr-3 shadow-sm">
                                <i class="fas fa-history text-blue-500"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Riwayat</div>
                                <div class="text-xs text-gray-500 italic">Daftar transaksi</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4 px-2">Status Terkini</h4>
                    <div class="space-y-1">
                        @auth
                            @php
                                $userId = auth()->id();
                                $latestBooking = \App\Models\Booking::where('user_id', $userId)->latest()->first();
                                $pendingBookings = \App\Models\Booking::where('user_id', $userId)->where('status', 'pending')->count();
                                $activeBookings = \App\Models\Booking::where('user_id', $userId)->whereIn('status', ['diterima', 'in_progress'])->count();
                            @endphp

                            @if($pendingBookings > 0)
                            <a href="{{ route('user.booking.riwayat') }}?status=pending" class="flex items-center justify-between text-sm text-amber-600 hover:bg-amber-50 p-2 rounded-lg transition-colors">
                                <span class="flex items-center"><i class="fas fa-clock mr-2"></i> Pending</span>
                                <span class="bg-amber-100 px-2 py-0.5 rounded-full text-xs font-bold">{{ $pendingBookings }}</span>
                            </a>
                            @endif

                            @if($activeBookings > 0)
                            <a href="{{ route('user.booking.riwayat') }}?status=active" class="flex items-center justify-between text-sm text-teal-600 hover:bg-teal-50 p-2 rounded-lg transition-colors">
                                <span class="flex items-center"><i class="fas fa-check-circle mr-2"></i> Aktif</span>
                                <span class="bg-teal-100 px-2 py-0.5 rounded-full text-xs font-bold">{{ $activeBookings }}</span>
                            </a>
                            @endif
                        @endauth

                        <a href="{{ route('user.hewan-saya') }}" class="flex items-center text-sm text-gray-600 hover:text-teal-600 p-2 rounded-lg transition-colors">
                            <i class="fas fa-paw mr-2"></i> Hewan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 order-1 lg:order-2">
            <div class="card-form p-5 sm:p-8 lg:p-10">
                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-800 tracking-tight mb-3">
                        Booking <span class="text-teal-600">Penitipan</span>
                    </h1>
                    <p class="text-gray-500 text-sm sm:text-base">
                        Lengkapi data penitipan hewan kesayangan Anda. Keamanan dan kenyamanan adalah prioritas kami.
                    </p>
                </div>

                @if ($errors->any() || session('success'))
                    <div class="mb-8 transform transition-all animate-pulse">
                        @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-times-circle mr-3"></i>
                                <span class="font-bold">Terjadi Kesalahan!</span>
                            </div>
                            <ul class="mt-2 text-sm list-disc list-inside opacity-80">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="bg-teal-50 border-l-4 border-teal-500 text-teal-700 p-4 rounded-r-xl shadow-sm">
                            <div class="flex items-center italic">
                                <i class="fas fa-check-double mr-3"></i>
                                {!! session('success') !!}
                            </div>
                        </div>
                        @endif
                    </div>
                @endif

                <form method="post" action="{{ route('user.booking.store') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-user-circle text-teal-600"></i>
                            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-400">Informasi Pemilik</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Nama Pemilik <span class="text-red-400">*</span></label>
                                <input type="text" name="nama_pemilik" required value="{{ old('nama_pemilik', $lastBooking->nama_pemilik ?? auth()->user()->username) }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none" placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Email Aktif <span class="text-red-400">*</span></label>
                                <input type="email" name="email" required value="{{ old('email', $lastBooking->email ?? auth()->user()->email) }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none" placeholder="Alamat email anda">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 ml-1">Nomor WhatsApp <span class="text-xs text-gray-400">(Disarankan)</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="fab fa-whatsapp"></i>
                                </span>
                                <input type="text" name="nomor_wa" value="{{ old('nomor_wa', $lastBooking->nomor_wa ?? auth()->user()->nomor_wa) }}"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none" placeholder="08xxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4">
                        <div class="flex items-center space-x-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-paw text-teal-600"></i>
                            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-400">Detail Hewan</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Nama Hewan <span class="text-red-400">*</span></label>
                                <input type="text" name="nama_hewan" required value="{{ old('nama_hewan') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none" placeholder="Nama anabul">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Jenis <span class="text-red-400">*</span></label>
                                <select name="jenis_hewan" required id="jenis_hewan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none appearance-none">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenisHewan as $jh)
                                    <option value="{{ $jh->nama }}" {{ old('jenis_hewan') == $jh->nama ? 'selected' : '' }}>{{ $jh->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Ukuran <span class="text-red-400">*</span></label>
                                <select name="ukuran_hewan" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 focus:bg-white transition-all outline-none appearance-none">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="Kecil" {{ old('ukuran_hewan') == 'Kecil' ? 'selected' : '' }}>Kecil (&lt;10kg)</option>
                                    <option value="Sedang" {{ old('ukuran_hewan') == 'Sedang' ? 'selected' : '' }}>Sedang (10-25kg)</option>
                                    <option value="Besar" {{ old('ukuran_hewan') == 'Besar' ? 'selected' : '' }}>Besar (&gt;25kg)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-teal-50/50 p-5 rounded-2xl border border-teal-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-teal-800 ml-1">Layanan Penitipan <span class="text-red-400">*</span></label>
                                <select name="layanan_id" required id="layanan_id" class="w-full px-4 py-3 bg-white border border-teal-200 rounded-xl focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none shadow-sm">
                                    <option value="">-- Pilih Paket Layanan --</option>
                                    @foreach($layanan as $l)
                                    <option value="{{ $l->id }}" data-harga="{{ $l->harga ?? 0 }}" {{ old('layanan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="harga-tampil" class="flex flex-col justify-center">
                                </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Check-in <span class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700 ml-1">Check-out <span class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_keluar" id="tanggal_keluar" value="{{ old('tanggal_keluar') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="fas fa-wallet mr-2 text-teal-600"></i> Konfirmasi Pembayaran
                            </label>
                            <div class="flex flex-col space-y-3">
                                <label class="relative flex items-center p-3 border border-gray-100 rounded-xl hover:bg-teal-50 cursor-pointer transition-colors">
                                    <input type="radio" name="dp_dibayar" value="Ya" {{ old('dp_dibayar') == 'Ya' ? 'checked' : '' }} required class="w-5 h-5 text-teal-600 border-gray-300">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Ya, Saya sudah transfer</span>
                                </label>
                                <label class="relative flex items-center p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="dp_dibayar" value="Tidak" {{ old('dp_dibayar') == 'Tidak' ? 'checked' : '' }} required class="w-5 h-5 text-teal-600 border-gray-300">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Belum, transfer nanti atau bayar di tempat</span>
                                </label>
                            </div>
                        </div>

                        <div id="bukti-dp-wrapper" class="space-y-2 {{ old('dp_dibayar') == 'Ya' ? '' : 'hidden' }}">
                            <label class="text-sm font-bold text-gray-800">Bukti Transfer</label>
                            <div class="upload-area relative rounded-2xl p-6 text-center cursor-pointer" onclick="document.getElementById('bukti-dp-input').click()">
                                <input type="file" name="bukti_dp" accept="image/*" id="bukti-dp-input" class="hidden" onchange="previewImage(this)">
                                <div id="image-preview" class="space-y-2">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-teal-500"></i>
                                    <p class="text-xs text-gray-500 font-medium">Klik untuk upload (JPG/PNG, Max 2MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-4">
                        <label class="text-sm font-bold text-gray-800 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-amber-500"></i> Catatan Khusus
                        </label>
                        <textarea name="catatan" rows="3" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none resize-none transition-all placeholder:text-sm"
                            placeholder="Alergi, kebiasaan makan, atau pesan khusus lainnya untuk anabul Anda...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="btn-primary w-full sm:w-auto text-white font-black px-12 py-4 rounded-2xl shadow-lg flex items-center justify-center space-x-3 text-base">
                            <span>KONFIRMASI BOOKING</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                        <p class="text-center sm:text-left text-[10px] text-gray-400 mt-4 italic uppercase tracking-widest">
                            * Dengan mengklik tombol, Anda menyetujui syarat & ketentuan penitipan.
                        </p>
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

        // Setup Dates
        const today = new Date().toISOString().split('T')[0];
        tanggalMasuk.setAttribute('min', today);
        tanggalKeluar.setAttribute('min', today);

        tanggalMasuk.addEventListener('change', function() {
            tanggalKeluar.setAttribute('min', this.value);
            if (tanggalKeluar.value && tanggalKeluar.value < this.value) {
                tanggalKeluar.value = this.value;
            }
            hitungTotalHarga();
        });

        async function getHargaPerHari(layananId, jenisHewan) {
            if (!layananId || !jenisHewan) return 0;
            try {
                const response = await fetch(`{{ route('user.booking.get-harga') }}?layanan_id=${layananId}&jenis_hewan=${encodeURIComponent(jenisHewan)}`);
                const data = await response.json();
                return data.harga ? parseFloat(data.harga) : 0;
            } catch (error) {
                return 0;
            }
        }

        function formatRupiah(angka) {
            if (angka < 1000 && angka > 0) angka *= 1000;
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        async function hitungTotalHarga() {
            const lId = layananSelect.value;
            const jH = jenisSelect.value;
            const tIn = tanggalMasuk.value;
            const tOut = tanggalKeluar.value;

            if (!lId || !jH) {
                hargaDiv.innerHTML = `<div class="text-xs text-gray-400 italic bg-white/50 p-3 rounded-xl border border-dashed border-teal-200">Pilih jenis hewan & paket layanan...</div>`;
                return;
            }

            if (!tIn || !tOut) {
                hargaDiv.innerHTML = `<div class="text-xs text-teal-600 font-bold bg-white p-3 rounded-xl shadow-sm border border-teal-100 animate-pulse">Menunggu penentuan tanggal...</div>`;
                return;
            }

            const inDate = new Date(tIn);
            const outDate = new Date(tOut);
            const diffTime = Math.abs(outDate - inDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;

            const hargaDay = await getHargaPerHari(lId, jH);

            if (hargaDay > 0) {
                const total = diffDays * hargaDay;
                hargaDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-teal-100 transform scale-100 hover:scale-105 transition-transform">
                        <div class="text-[10px] uppercase font-black text-gray-400 tracking-tighter">Estimasi Biaya (${diffDays} Hari)</div>
                        <div class="text-xl font-black text-teal-600">${formatRupiah(total)}</div>
                        <div class="text-[10px] text-gray-500 mt-1 italic">* ${formatRupiah(hargaDay)} / hari</div>
                    </div>
                `;
            } else {
                hargaDiv.innerHTML = `<div class="text-xs text-red-400 italic p-3">Harga tidak ditemukan.</div>`;
            }
        }

        [layananSelect, jenisSelect, tanggalMasuk, tanggalKeluar].forEach(el => el.addEventListener('change', hitungTotalHarga));

        // Toggle Proof of Payment
        const radios = document.querySelectorAll('input[name="dp_dibayar"]');
        const buktiWrapper = document.getElementById('bukti-dp-wrapper');
        const buktiInput = document.getElementById('bukti-dp-input');

        function toggleBukti() {
            const selected = document.querySelector('input[name="dp_dibayar"]:checked');
            if (selected?.value === 'Ya') {
                buktiWrapper.classList.remove('hidden');
                buktiInput.setAttribute('required', 'required');
            } else {
                buktiWrapper.classList.add('hidden');
                buktiInput.removeAttribute('required');
            }
        }

        radios.forEach(r => r.addEventListener('change', toggleBukti));
        toggleBukti();

        window.previewImage = function(input) {
            const preview = document.getElementById('image-preview');
            if (input.files?.[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `
                        <div class="relative inline-block">
                            <img src="${e.target.result}" class="w-20 h-20 object-cover rounded-lg border-2 border-teal-500 shadow-md">
                            <div class="absolute -top-2 -right-2 bg-teal-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px]">
                                <i class="fas fa-check"></i>
                            </div>
                            <p class="text-[10px] mt-1 text-teal-600 font-bold truncate w-20">${input.files[0].name}</p>
                        </div>`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        hitungTotalHarga();
    });
</script>
@endpush