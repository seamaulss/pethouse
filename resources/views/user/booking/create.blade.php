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

    /* Sidebar Enhancement */
    .sidebar-aksi {
        position: sticky;
        top: 2rem;
        z-index: 10;
    }

    .sidebar-item {
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }

    .sidebar-item:hover,
    .sidebar-item.active {
        border-left-color: #0d9488;
        background-color: #f0fdfa;
    }

    /* Input Focus Effects */
    input:focus,
    select:focus,
    textarea:focus {
        transform: scale(1.01);
        transition: transform 0.2s ease;
    }

    .upload-area {
        transition: all 0.3s ease;
        border: 2px dashed #e5e7eb;
    }

    .upload-area:hover {
        border-color: #2dd4bf;
        background-color: #f9fafb;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
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
                                <div class="text-xs text-gray-500 italic">Form baru</div>
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
            </div>
        </div>

        <div class="lg:col-span-3 order-1 lg:order-2">
            <div class="card-form p-5 sm:p-8 lg:p-10">
                <div class="mb-10 text-center lg:text-left">
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-800 tracking-tight mb-3">
                        Booking <span class="text-teal-600">Penitipan</span>
                    </h1>
                    <p class="text-gray-500 text-sm">Lengkapi data penitipan hewan kesayangan Anda dengan aman dan nyaman.</p>
                </div>

                @if ($errors->any() || session('success'))
                <div class="mb-8">
                    @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                        <ul class="text-sm list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="bg-teal-50 border-l-4 border-teal-500 text-teal-700 p-4 rounded-r-xl shadow-sm italic animate-pulse">
                        <i class="fas fa-check-double mr-2"></i> {!! session('success') !!}
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
                                <label class="text-sm font-semibold text-gray-700">Nama Pemilik <span class="text-red-400">*</span></label>
                                <input type="text" name="nama_pemilik" required value="{{ old('nama_pemilik', $lastBooking->nama_pemilik ?? auth()->user()->username) }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Email <span class="text-red-400">*</span></label>
                                <input type="email" name="email" required value="{{ old('email', $lastBooking->email ?? auth()->user()->email) }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all">
                            </div>
                            <div class="mb-4">
                                <label for="nomor_wa" class="block text-gray-700 font-medium mb-2">Nomor WhatsApp</label>
                                <input type="text"
                                    name="nomor_wa"
                                    id="nomor_wa"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                    value="{{ auth()->user()->nomor_wa }}"
                                    placeholder="Contoh: 628123456789"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">*Pastikan nomor aktif untuk koordinasi penjemputan/update hewan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4">
                        <div class="flex items-center space-x-2 pb-2 border-b border-gray-100">
                            <i class="fas fa-paw text-teal-600"></i>
                            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-400">Detail Anabul</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Nama Hewan <span class="text-red-400">*</span></label>
                                <input type="text" name="nama_hewan" required value="{{ old('nama_hewan') }}" class="w-full px-4 py-3 bg-gray-50 border 
                                border-gray-200 rounded-xl outline-none focus:border-teal-500" autocomplete="off">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Jenis <span class="text-red-400">*</span></label>
                                <select name="jenis_hewan" required id="jenis_hewan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenisHewan as $jh)
                                    <option value="{{ $jh->nama }}" {{ old('jenis_hewan') == $jh->nama ? 'selected' : '' }}>{{ $jh->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Ukuran <span class="text-red-400">*</span></label>
                                <select name="ukuran_hewan" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="Kecil">Kecil (< 10kg)</option>
                                    <option value="Sedang">Sedang (10-25kg)</option>
                                    <option value="Besar">Besar (> 25kg)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-teal-800">Paket Layanan <span class="text-red-400">*</span></label>
                                <select name="layanan_id" required id="layanan_id" class="w-full px-4 py-3 bg-white border border-teal-200 rounded-xl focus:ring-4 focus:ring-teal-500/20 outline-none shadow-sm">
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($layanan as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama_layanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="harga-tampil" class="flex flex-col justify-center">
                                <div class="text-xs text-gray-400 italic bg-white/50 p-3 rounded-xl border border-dashed border-teal-200 text-center">
                                    Pilih jenis & paket untuk melihat estimasi biaya
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Tanggal Masuk <span class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Tanggal Keluar <span class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="fas fa-wallet mr-2 text-teal-600"></i> Konfirmasi Pembayaran
                            </label>
                            <div class="flex flex-col space-y-3">
                                <label class="relative flex items-center p-4 border border-gray-100 rounded-xl hover:bg-teal-50 cursor-pointer transition-colors">
                                    <input type="radio" name="dp_dibayar" value="Ya" class="w-5 h-5 text-teal-600">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Bayar Online</span>
                                </label>
                                <label class="relative flex items-center p-4 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" name="dp_dibayar" value="Tidak" checked class="w-5 h-5 text-teal-600">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Bayar di tempat</span>
                                </label>
                            </div>
                        </div>

                        <div id="bukti-dp-wrapper" class="hidden animate-fade-in space-y-4">
                            <div class="bg-gradient-to-r from-gray-800 to-gray-700 rounded-2xl p-4 text-white shadow-lg relative overflow-hidden">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest opacity-70">Transfer Tujuan (BCA)</p>
                                            <p class="text-lg font-mono tracking-widest">7825 0912 34</p>
                                        </div>
                                        <i class="fas fa-university text-xl opacity-50"></i>
                                    </div>

                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] font-bold">a.n. LARAPetHouse</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" onclick="copyAccount('7825091234')" class="text-[9px] bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition-all font-bold">SALIN</button>
                                            <button type="button" onclick="toggleQR()" class="text-[9px] bg-teal-500 px-3 py-1.5 rounded-lg hover:bg-teal-400 transition-all font-bold flex items-center">
                                                <i class="fas fa-qrcode mr-1"></i> QR CODE
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="qr-container" class="hidden bg-white p-4 rounded-2xl border-2 border-dashed border-teal-200 text-center animate-fade-in">
                                <p class="text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Scan QRIS LARAPetHouse</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=LaraPetHouse" alt="QR Code" class="mx-auto rounded-lg shadow-sm">
                                <p class="text-[9px] text-teal-600 mt-2 italic">* Pastikan nama merchant muncul sebagai LARAPetHouse</p>
                            </div>

                            <div class="upload-area relative rounded-2xl p-5 text-center cursor-pointer" onclick="document.getElementById('bukti-dp-input').click()">
                                <input type="file" name="bukti_dp" accept="image/*" id="bukti-dp-input" class="hidden" onchange="previewImage(this)">
                                <div id="image-preview" class="space-y-1">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-teal-500"></i>
                                    <p class="text-[10px] text-gray-500 font-medium">Klik untuk upload bukti bayar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4">
                        <label class="text-sm font-bold text-gray-800 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-amber-500"></i> Catatan Khusus <span class="text-red-400 ml-1">*</span>
                        </label>
                        <textarea name="catatan" rows="3" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none resize-none transition-all placeholder:text-sm"
                            placeholder="Sebutkan alergi, kebiasaan makan, atau instruksi khusus lainnya...">{{ old('catatan') }}</textarea>
                        <p class="text-[10px] text-gray-400 italic font-medium uppercase tracking-wider">* Isi "Tidak ada" jika anabul dalam kondisi normal.</p>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <button type="submit" class="btn-primary w-full sm:w-auto text-white font-black px-12 py-4 rounded-2xl shadow-lg flex items-center justify-center space-x-3">
                            <span>KONFIRMASI BOOKING</span>
                            <i class="fas fa-arrow-right text-xs"></i>
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
        const tIn = document.getElementById('tanggal_masuk');
        const tOut = document.getElementById('tanggal_keluar');
        const hargaDiv = document.getElementById('harga-tampil');

        // Setup Dates
        const today = new Date().toISOString().split('T')[0];
        tIn.min = today;
        tOut.min = today;

        async function hitungTotalHarga() {
            if (!layananSelect.value || !jenisSelect.value) return;
            if (!tIn.value || !tOut.value) {
                hargaDiv.innerHTML = `<div class="text-xs text-teal-600 font-bold bg-white p-3 rounded-xl border border-teal-100 animate-pulse text-center italic">Menunggu tanggal inap...</div>`;
                return;
            }

            const inDate = new Date(tIn.value);
            const outDate = new Date(tOut.value);
            const diffDays = Math.ceil(Math.abs(outDate - inDate) / (1000 * 60 * 60 * 24)) || 1;

            try {
                const response = await fetch(`{{ route('user.booking.get-harga') }}?layanan_id=${layananSelect.value}&jenis_hewan=${encodeURIComponent(jenisSelect.value)}`);
                const data = await response.json();
                const hargaDay = parseFloat(data.harga || 0);
                const total = diffDays * hargaDay;

                if (hargaDay > 0) {
                    hargaDiv.innerHTML = `
                        <div class="bg-gradient-to-br from-teal-50 to-white p-4 rounded-2xl border-2 border-teal-100 shadow-md animate-fade-in">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[9px] uppercase font-black text-teal-600">Ringkasan Biaya</span>
                                <span class="bg-teal-500 text-white text-[8px] px-2 py-0.5 rounded-full font-bold">${diffDays} Hari</span>
                            </div>
                            <div class="text-xl font-black text-gray-800 tracking-tight">
                                ${new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(total)}
                            </div>
                            <div class="mt-1 pt-1 border-t border-dashed border-teal-200 text-[9px] text-gray-500 italic">
                                * Biaya: ${new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(hargaDay)} / hari
                            </div>
                        </div>`;
                }
            } catch (e) {
                console.error("Error fetching price", e);
            }
        }

        [layananSelect, jenisSelect, tIn, tOut].forEach(el => el.addEventListener('change', hitungTotalHarga));

        // Payment Toggle
        const radios = document.querySelectorAll('input[name="dp_dibayar"]');
        const wrapper = document.getElementById('bukti-dp-wrapper');
        const input = document.getElementById('bukti-dp-input');

        radios.forEach(r => r.addEventListener('change', () => {
            if (r.value === 'Ya') {
                wrapper.classList.remove('hidden');
                input.required = true;
            } else {
                wrapper.classList.add('hidden');
                input.required = false;
            }
        }));
    });

    function copyAccount(num) {
        navigator.clipboard.writeText(num);
        alert("Nomor rekening disalin!");
    }

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files?.[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" class="w-12 h-12 mx-auto object-cover rounded-lg border-2 border-teal-500 shadow-sm">
                                     <p class="text-[9px] text-teal-600 font-bold mt-1">Selesai di-upload!</p>`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleQR() {
        const qrContainer = document.getElementById('qr-container');
        if (qrContainer.classList.contains('hidden')) {
            qrContainer.classList.remove('hidden');
        } else {
            qrContainer.classList.add('hidden');
        }
    }
</script>
@endpush