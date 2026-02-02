@extends('admin.layouts.app')

@section('title', 'Tambah Jenis Hewan - PetHouse Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('admin.jenis-hewan.index') }}" 
               class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <div class="w-px h-6 bg-gray-300"></div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">
                    Tambah Jenis Hewan Baru
                </h1>
                <p class="text-gray-600 mt-1">
                    Tambahkan jenis hewan baru ke dalam sistem
                </p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">
                    Form Tambah Jenis Hewan
                </h2>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.jenis-hewan.store') }}" method="POST">
                @csrf

                <div class="p-6 space-y-6">
                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jenis Hewan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nama"
                               name="nama"
                               value="{{ old('nama') }}"
                               required
                               placeholder="Contoh: Kucing, Anjing"
                               class="w-full px-4 py-3 rounded-lg border 
                               @error('nama') border-red-500 @else border-gray-300 @enderror
                               focus:ring-2 focus:ring-teal-500 focus:outline-none transition">
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Aktif -->
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio"
                                       name="aktif"
                                       value="ya"
                                       required
                                       {{ old('aktif', 'ya') === 'ya' ? 'checked' : '' }}
                                       class="h-5 w-5 text-teal-600 focus:ring-teal-500">
                                <span class="ml-3">
                                    <span class="block font-medium text-gray-700">Aktif</span>
                                    <span class="text-sm text-gray-500">Ditampilkan di sistem</span>
                                </span>
                            </label>

                            <!-- Nonaktif -->
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio"
                                       name="aktif"
                                       value="tidak"
                                       {{ old('aktif') === 'tidak' ? 'checked' : '' }}
                                       class="h-5 w-5 text-teal-600 focus:ring-teal-500">
                                <span class="ml-3">
                                    <span class="block font-medium text-gray-700">Nonaktif</span>
                                    <span class="text-sm text-gray-500">Tidak ditampilkan</span>
                                </span>
                            </label>
                        </div>

                        @error('aktif')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-3">
                    <a href="{{ route('admin.jenis-hewan.index') }}"
                       class="px-5 py-2.5 border rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Batal
                    </a>
                    <button type="submit" 
                                class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-teal-500 to-teal-600 text-white hover:from-teal-600 hover:to-teal-700 transition-all duration-200 font-medium shadow-sm hover:shadow">
                            Update Jenis Hewan
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
