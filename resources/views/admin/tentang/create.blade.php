@extends('admin.layout')

@section('title', 'Tambah Konten - Tentang Kami')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Konten Tentang Kami</h1>
        <p class="text-gray-600 mt-2">Tambah konten baru untuk halaman "Tentang Kami" di website publik</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.tentang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                    <input type="text" name="judul" id="judul" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           value="{{ old('judul') }}">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi -->
                <div>
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi Konten</label>
                    <textarea name="isi" id="isi" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('isi') }}</textarea>
                    @error('isi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar (Opsional)</label>
                    <input type="file" name="gambar" id="gambar"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           accept="image/*">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.tentang.index') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-300">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection