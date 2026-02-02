@extends('admin.layout')

@section('title', 'Tambah Layanan')

@section('styles')
<style>
    .file-input-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .file-input-container input[type="file"] {
        display: none;
    }
    
    .file-input-label {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #e8f4f3;
        color: #0d9488;
        border: 1px solid #0d9488;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .file-input-label:hover {
        background-color: #d1e7e5;
    }
    
    .file-name {
        font-size: 0.875rem;
        color: #6b7280;
    }
</style>
@endsection

@section('content')
<div class="flex min-h-screen">
    
    @include('admin.layouts.navbar')

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-6">
            <h2 class="text-2xl font-semibold text-gray-800">Tambah Layanan Baru</h2>
            <p class="text-gray-600 mt-2">Isi form untuk menambahkan layanan. Harga akan diatur per jenis hewan.</p>
        </header>

        <!-- Form Area -->
        <div class="p-8">
            <!-- Back Link -->
            <a href="{{ route('admin.layanan.index') }}" 
               class="inline-flex items-center text-teal-600 hover:text-teal-800 mb-6 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> 
                Kembali ke Data Layanan
            </a>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm border border-red-200">
                    <strong class="font-semibold">Perhatian:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow border border-gray-200 p-8 max-w-2xl">
                <form method="post" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Nama Layanan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Layanan
                        </label>
                        <input type="text" 
                               name="nama_layanan" 
                               value="{{ old('nama_layanan') }}"
                               required
                               placeholder="Masukkan nama layanan"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Gambar Layanan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Gambar Layanan <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="file-input-container">
                            <input type="file" 
                                   id="gambar" 
                                   name="gambar" 
                                   accept="image/*" 
                                   required
                                   onchange="displayFileName(this)">
                            <label for="gambar" class="file-input-label">
                                <i class="fas fa-folder-open mr-2"></i>
                                Choose File
                            </label>
                            <span id="fileName" class="file-name">No file chosen</span>
                        </div>
                        
                        <p class="mt-2 text-sm text-gray-500">
                            Format: JPG, JPEG, PNG, WEBP
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" 
                                  rows="4"
                                  placeholder="Masukkan deskripsi layanan (opsional)"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 focus:outline-none transition-colors">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            Simpan Layanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    function displayFileName(input) {
        const fileName = input.files[0]?.name || 'No file chosen';
        document.getElementById('fileName').textContent = fileName;
    }
</script>
@endsection