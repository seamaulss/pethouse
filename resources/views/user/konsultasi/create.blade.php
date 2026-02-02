@extends('layouts.user')

@section('title', 'Konsultasi Dokter - PetHouse')

@push('styles')
<style>
    .card-form {
        border-radius: 2rem;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        background: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 20px 40px rgba(13,148,136,0.4);
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="card-form p-8 sm:p-10 lg:p-12" data-aos="fade-up">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Konsultasi dengan Dokter Hewan 🩺
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                Jadwalkan konsultasi kesehatan hewan kesayangan Anda (jam 08.00 – 18.00 WIB)
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-5 rounded-xl mb-8 flex items-center" data-aos="fade-up">
                <i class="fas fa-check-circle mr-4 text-2xl"></i>
                <div>
                    <p class="font-semibold">{!! session('success') !!}</p>
                    <p class="text-sm mt-2">Anda akan dialihkan ke daftar konsultasi dalam 3 detik...</p>
                </div>
            </div>
            <script>
                setTimeout(() => window.location.href = "{{ route('user.konsultasi.index') }}", 3000);
            </script>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-5 rounded-xl mb-8 flex items-center" data-aos="fade-up">
                <i class="fas fa-exclamation-triangle mr-4 text-2xl"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('user.konsultasi.store') }}" class="space-y-8">
            @csrf
            
            <!-- Nama & WhatsApp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-teal-600"></i> Nama Pemilik <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_pemilik" required 
                           value="{{ old('nama_pemilik', auth()->user()->name) }}"
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base"
                           placeholder="Nama lengkap Anda">
                </div>

                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fab fa-whatsapp mr-2 text-green-500"></i> Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_wa" required 
                           value="{{ old('no_wa', auth()->user()->no_wa) }}"
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base"
                           placeholder="081234567890">
                </div>
            </div>

            <!-- Jenis Hewan & Topik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-paw mr-2 text-pink-500"></i> Jenis Hewan <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_hewan" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                        <option value="">-- Pilih Jenis Hewan --</option>
                        @foreach($jenisHewan as $jh)
                            <option value="{{ $jh->nama }}" {{ old('jenis_hewan') == $jh->nama ? 'selected' : '' }}>
                                {{ $jh->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-stethoscope mr-2 text-amber-500"></i> Topik Konsultasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="topik" required 
                           value="{{ old('topik') }}"
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base"
                           placeholder="Misal: Vaksin, demam, perilaku aneh">
                </div>
            </div>

            <!-- Tanggal & Jam -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-teal-600"></i> Tanggal Janji <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal" name="tanggal_janji" required
                           value="{{ old('tanggal_janji') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                </div>

                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-pink-500"></i> Jam Janji <span class="text-red-500">*</span>
                    </label>
                    <select id="jam" name="jam_janji" required class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                        <option value="">Pilih tanggal dulu</option>
                    </select>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-amber-500"></i> Catatan Tambahan
                </label>
                <textarea name="catatan" rows="5"
                          class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base resize-none"
                          placeholder="Ceritakan gejala, riwayat penyakit, atau hal penting lainnya...">{{ old('catatan') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" name="kirim" class="btn-primary text-white font-bold px-12 py-5 rounded-full shadow-2xl text-lg inline-flex items-center">
                    <i class="fas fa-paper-plane mr-3 text-xl"></i>
                    Kirim Permintaan Konsultasi
                </button>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('user.konsultasi.index') }}" class="text-teal-600 hover:text-teal-700 underline text-base">
                    <i class="fas fa-list mr-2"></i> Lihat Daftar Konsultasi Saya
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const slotJam = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
    const tanggalInput = document.getElementById('tanggal');
    const jamSelect = document.getElementById('jam');

    tanggalInput.addEventListener('change', async () => {
        jamSelect.innerHTML = '<option>Loading jam tersedia...</option>';
        
        try {
            const res = await fetch("{{ route('user.konsultasi.get-jam') }}?tanggal=" + tanggalInput.value);
            const jamTerpakai = await res.json();

            jamSelect.innerHTML = '<option value="">-- Pilih Jam (08.00 - 18.00) --</option>';

            const today = new Date().toISOString().split('T')[0];
            const nowHour = new Date().getHours();

            slotJam.forEach(jam => {
                const jamInt = parseInt(jam.split(':')[0]);
                const opt = document.createElement('option');
                opt.value = jam;
                opt.textContent = jam;

                if (jamTerpakai.includes(jam)) {
                    opt.disabled = true;
                    opt.textContent += ' (Sudah dibooking)';
                }
                if (tanggalInput.value === today && jamInt <= nowHour) {
                    opt.disabled = true;
                    opt.textContent += ' (Sudah lewat)';
                }
                jamSelect.appendChild(opt);
            });
        } catch (error) {
            jamSelect.innerHTML = '<option value="">Gagal memuat jam</option>';
            console.error('Error loading jam:', error);
        }
    });

    // Jika ada data lama, reload jam
    @if(!empty(old('tanggal_janji')))
        tanggalInput.value = '{{ old('tanggal_janji') }}';
        tanggalInput.dispatchEvent(new Event('change'));
        setTimeout(() => {
            jamSelect.value = '{{ old('jam_janji') ?? '' }}';
        }, 500);
    @endif
</script>
@endpush