@extends('admin.layout')

@section('title', 'Edit Jenis Hewan - PetHouse Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-8" data-aos="fade-down">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('admin.jenis-hewan.index') }}" 
               class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <div class="w-px h-6 bg-gray-300"></div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Edit Jenis Hewan</h1>
                <p class="text-gray-600 mt-1">Ubah data jenis hewan</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl" data-aos="fade-up">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Form Edit Jenis Hewan</h2>
            </div>
            
            <!-- Form -->
            <form action="{{ route('admin.jenis-hewan.update', $jenisHewan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Nama Jenis Hewan -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jenis Hewan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $jenisHewan->nama) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:outline-none transition"
                               placeholder="Contoh: Kucing, Anjing, Kelinci" 
                               required>
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
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                <input type="radio" 
                                       name="aktif" 
                                       value="ya" 
                                       {{ old('aktif', $jenisHewan->aktif) == 'ya' ? 'checked' : '' }}
                                       class="h-5 w-5 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">Aktif</span>
                                    <span class="block text-sm text-gray-500">Jenis hewan akan ditampilkan</span>
                                </span>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                <input type="radio" 
                                       name="aktif" 
                                       value="tidak" 
                                       {{ old('aktif', $jenisHewan->aktif) == 'tidak' ? 'checked' : '' }}
                                       class="h-5 w-5 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">Nonaktif</span>
                                    <span class="block text-sm text-gray-500">Jenis hewan tidak ditampilkan</span>
                                </span>
                            </label>
                        </div>
                        @error('aktif')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Card Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.jenis-hewan.index') }}" 
                           class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 font-medium">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-teal-500 to-teal-600 text-white hover:from-teal-600 hover:to-teal-700 transition-all duration-200 font-medium shadow-sm hover:shadow">
                            Update Jenis Hewan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection