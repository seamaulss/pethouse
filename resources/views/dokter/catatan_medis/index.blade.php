@extends('layouts.dokter')

@section('title', 'Catatan Medis Hewan')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="card-form p-8 sm:p-10 lg:p-12" data-aos="fade-up">
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-800 mb-4">
                📋 Catatan Medis Hewan
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Simpan rekam medis lengkap untuk membantu perawatan jangka panjang hewan kesayangan.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-5 rounded-xl mb-10 flex items-center" data-aos="fade-up">
                <i class="fas fa-check-circle mr-4 text-2xl"></i>
                <span class="font-medium">{!! session('success') !!}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-5 rounded-xl mb-10 flex items-center" data-aos="fade-up">
                <i class="fas fa-exclamation-triangle mr-4 text-2xl"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('dokter.catatan-medis.store') }}" class="space-y-8">
            @csrf
            
            <!-- Pilih Pemilik dari Riwayat -->
            <div>
                <label class="block text-xl font-medium text-gray-700 mb-4">
                    <i class="fas fa-user mr-3 text-teal-600"></i>
                    Pilih Pemilik Hewan dari Riwayat <span class="text-red-500">*</span>
                </label>
                <select name="user_id" required class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach($pemilikList as $pemilik)
                        <option value="{{ $pemilik->id }}" {{ old('user_id') == $pemilik->id ? 'selected' : '' }}>
                            {{ $pemilik->nama_pemilik }}
                            @if($pemilik->jenis_hewan)
                                 - {{ $pemilik->jenis_hewan }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Pilih pemilik dari riwayat konsultasi atau booking. Nama & jenis hewan bisa diisi manual di bawah.
                </p>
            </div>

            <!-- Nama & Jenis Hewan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-dog mr-3 text-teal-600"></i> Nama Hewan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_hewan" required placeholder="Contoh: Milo, Kitty, Blacky"
                           value="{{ old('nama_hewan') }}"
                           class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                </div>
                <div>
                    <label class="block text-lg font-medium text-gray-700 mb-2">
                        <i class="fas fa-cat mr-3 text-pink-500"></i> Jenis Hewan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="jenis_hewan" required placeholder="Contoh: Kucing Persia, Anjing Golden"
                           value="{{ old('jenis_hewan') }}"
                           class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
                </div>
            </div>

            <!-- Tanggal Kunjungan -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-3 text-teal-600"></i> Tanggal Kunjungan / Catatan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" required 
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-500 text-base">
            </div>

            <!-- Diagnosis -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-stethoscope mr-3 text-amber-500"></i> Diagnosis
                </label>
                <textarea name="diagnosis" rows="4" placeholder="Tulis hasil pemeriksaan dan diagnosis..."
                          class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-amber-200 focus:border-amber-500 text-base resize-none">{{ old('diagnosis') }}</textarea>
            </div>

            <!-- Resep Obat -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-pills mr-3 text-pink-500"></i> Resep Obat
                </label>
                <textarea name="resep" rows="4" placeholder="Tulis nama obat, dosis, dan aturan pakai..."
                          class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-500 text-base resize-none">{{ old('resep') }}</textarea>
            </div>

            <!-- Vaksin -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-syringe mr-3 text-green-600"></i> Vaksin (opsional)
                </label>
                <input type="text" name="vaksin" placeholder="Contoh: Vaksin Rabies, Booster FVRCP"
                       value="{{ old('vaksin') }}"
                       class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500 text-base">
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-3 text-amber-500"></i> Catatan Tambahan
                </label>
                <textarea name="catatan_lain" rows="5" placeholder="Saran perawatan, diet, jadwal kontrol berikutnya..."
                          class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-amber-200 focus:border-amber-500 text-base resize-none">{{ old('catatan_lain') }}</textarea>
            </div>

            <!-- Tombol Simpan -->
            <div class="text-center">
                <button type="submit" class="btn-primary text-white font-bold px-12 py-5 rounded-full shadow-2xl text-lg inline-flex items-center">
                    <i class="fas fa-save mr-3 text-xl"></i> Simpan Catatan Medis
                </button>
            </div>
        </form>

        <!-- Kembali -->
        <div class="text-center mt-12">
            <a href="{{ route('dokter.dashboard') }}" class="text-teal-600 hover:text-teal-700 font-medium text-lg inline-flex items-center">
                <i class="fas fa-arrow-left mr-3"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection