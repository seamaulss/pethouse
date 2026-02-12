@extends('admin.layout')

@section('title', 'Tambah Master Kegiatan')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-plus-circle text-teal"></i>
            Tambah Kegiatan
        </h1>
        <p class="text-gray-600 mt-2">
            Tambahkan jenis kegiatan layanan baru di PetHouse
        </p>
    </div>

    <!-- Notifikasi Error -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.master-kegiatan.store') }}" method="POST">
            @csrf

            <!-- Nama Kegiatan -->
            <div class="mb-4">
                <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Kegiatan <span class="text-red-600">*</span>
                </label>
                <input type="text"
                       name="nama_kegiatan"
                       id="nama_kegiatan"
                       value="{{ old('nama_kegiatan') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal focus:border-teal"
                       placeholder="Misal: Grooming, Penitipan, dll"
                       required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi"
                          id="deskripsi"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal focus:border-teal"
                          placeholder="Jelaskan kegiatan ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Icon & Warna (Grid 2 kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">
                        Icon (Font Awesome)
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text"
                               name="icon"
                               id="icon"
                               value="{{ old('icon') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal focus:border-teal"
                               placeholder="Contoh: dog, cat, scissors">
                        <span class="text-sm text-gray-500 whitespace-nowrap">
                            <i class="fas fa-dog"></i> dog
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Nama ikon Font Awesome tanpa awalan "fa-"
                    </p>
                </div>
                <div>
                    <label for="warna" class="block text-sm font-medium text-gray-700 mb-1">
                        Warna Badge
                    </label>
                    <select name="warna"
                            id="warna"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal focus:border-teal">
                        <option value="">-- Pilih Warna --</option>
                        @php
                            $warnaList = ['red', 'blue', 'green', 'yellow', 'purple', 'pink', 'orange', 'teal', 'gray', 'indigo'];
                        @endphp
                        @foreach($warnaList as $warna)
                            <option value="{{ $warna }}" {{ old('warna') == $warna ? 'selected' : '' }}>
                                {{ ucfirst($warna) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="mt-2 flex gap-2">
                        @foreach(['red', 'blue', 'green', 'yellow'] as $w)
                            <span class="px-3 py-1 rounded-full text-xs bg-{{ $w }}-100 text-{{ $w }}-800">{{ ucfirst($w) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Urutan & Aktif (Grid 2 kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="urutan" class="block text-sm font-medium text-gray-700 mb-1">
                        Urutan <span class="text-red-600">*</span>
                    </label>
                    <input type="number"
                           name="urutan"
                           id="urutan"
                           value="{{ old('urutan') }}"
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal focus:border-teal"
                           required>
                </div>
                <div>
                    <label for="aktif" class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center gap-6 mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio"
                                   name="aktif"
                                   value="ya"
                                   {{ old('aktif', 'ya') == 'ya' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-teal focus:ring-teal border-gray-300">
                            <span class="ml-2 text-gray-700">Aktif</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio"
                                   name="aktif"
                                   value="tidak"
                                   {{ old('aktif') == 'tidak' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <span class="ml-2 text-gray-700">Nonaktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.master-kegiatan.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-teal text-white rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>

    <!-- Back -->
    <div class="mt-6">
        <a href="{{ route('admin.master-kegiatan.index') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Kegiatan
        </a>
    </div>

</div>
@endsection