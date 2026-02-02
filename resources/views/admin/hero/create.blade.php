@extends('admin.layout')

@section('title', 'Tambah Slide Hero')

@section('content')
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">Tambah Slide Hero</h2>
        <p class="text-sm text-gray-600 mt-1">Tambahkan slide baru untuk bagian hero di homepage</p>
    </header>

    <!-- Form Content -->
    <div class="p-6 max-w-md mx-auto w-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <a href="{{ route('admin.hero.index') }}" class="inline-flex items-center text-teal-600 hover:text-teal-800 mb-5 font-medium">
                ← Kembali ke Daftar Slide
            </a>

            @if ($errors->any())
                <div class="mb-5 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('admin.hero.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input
                        type="text"
                        name="judul"
                        required
                        value="{{ old('judul') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Subjudul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                    <input
                        type="text"
                        name="subjudul"
                        value="{{ old('subjudul') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Tombol Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tombol</label>
                    <input
                        type="text"
                        name="tombol_text"
                        value="{{ old('tombol_text', 'Booking Sekarang') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Tombol Link -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Tombol</label>
                    <input
                        type="text"
                        name="tombol_link"
                        value="{{ old('tombol_link', 'booking.php') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Urutan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                    <input
                        type="number"
                        name="urutan"
                        value="{{ old('urutan', 0) }}"
                        min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Gambar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (opsional)</label>
                    <input
                        type="file"
                        name="gambar"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-medium
                               file:bg-teal-50 file:text-teal-700
                               hover:file:bg-teal-100">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP (max: 2MB)</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full px-4 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                        Simpan Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection