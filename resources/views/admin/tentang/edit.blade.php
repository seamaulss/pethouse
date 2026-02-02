@extends('admin.layout')

@section('title', 'Edit Konten Tentang - PetHouse Admin')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Konten Tentang Kami</h1>
                <p class="text-gray-600 mt-2">Perbarui konten untuk halaman "Tentang Kami"</p>
            </div>
            <a href="{{ route('admin.tentang.index') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <h4 class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Terjadi Kesalahan:</h4>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Form Edit -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.tentang.update', $tentang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        name="judul"
                        id="judul"
                        value="{{ old('judul', $tentang->judul) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-300"
                        placeholder="Contoh: Visi & Misi PetHouse">
                    @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi Konten -->
                <div>
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi"
                        id="isi"
                        rows="10"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-300"
                        placeholder="Tulis konten tentang kami di sini...">{{ old('isi', $tentang->isi) }}</textarea>
                    @error('isi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Gunakan Enter untuk membuat paragraf baru.
                    </p>
                </div>

                <!-- Di bagian gambar saat ini, perbaiki URL -->
                @if($tentang->gambar)
                <img src="{{ url('storage/tentang/' . $tentang->gambar) }}"
                    alt="Gambar saat ini"
                    class="w-48 h-32 object-cover rounded-lg border shadow-sm"
                    onerror="this.src='https://via.placeholder.com/300x200?text=Gambar+Tidak+Ditemukan'">
                @endif

                <!-- Di bagian lihat full size -->
                <a href="{{ url('storage/tentang/' . $tentang->gambar) }}"
                    target="_blank"
                    class="px-3 py-1 bg-blue-100 text-blue-700 text-sm rounded hover:bg-blue-200 transition duration-300 flex items-center">
                    <i class="fas fa-external-link-alt mr-1"></i> Lihat Full Size
                </a>

                <!-- Upload Gambar Baru -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
                        @if($tentang->gambar)
                        Ganti Gambar (Opsional)
                        @else
                        Tambah Gambar (Opsional)
                        @endif
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-teal-500 transition duration-300">
                        <div class="mx-auto w-16 h-16 mb-4 text-gray-400">
                            <i class="fas fa-cloud-upload-alt text-4xl"></i>
                        </div>
                        <p class="text-gray-600 mb-2">Klik untuk upload gambar baru atau drag & drop</p>
                        <p class="text-sm text-gray-500 mb-4">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>

                        <input type="file"
                            name="gambar"
                            id="gambar"
                            accept="image/*"
                            class="hidden"
                            onchange="previewImage(event)">

                        <label for="gambar"
                            class="inline-block px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 cursor-pointer transition duration-300">
                            <i class="fas fa-upload mr-2"></i>
                            @if($tentang->gambar)
                            Pilih Gambar Baru
                            @else
                            Pilih Gambar
                            @endif
                        </label>

                        <!-- Preview Image Baru -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm text-gray-700 mb-2">Preview Gambar Baru:</p>
                            <img id="preview" class="mx-auto max-w-full h-48 object-cover rounded-lg border">
                        </div>
                    </div>
                    @error('gambar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Metadata -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Informasi Konten
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Dibuat:</p>
                            <p class="font-medium text-gray-800">
                                {{ $tentang->created_at->translatedFormat('l, d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">Terakhir Diperbarui:</p>
                            <p class="font-medium text-gray-800">
                                {{ $tentang->updated_at->translatedFormat('l, d F Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">ID Konten:</p>
                            <p class="font-medium text-gray-800">{{ $tentang->id }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status:</p>
                            <p class="font-medium text-green-600">
                                <i class="fas fa-circle text-xs mr-1"></i> Aktif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <button type="button"
                                onclick="if(confirm('Yakin ingin menghapus konten ini?')) { document.getElementById('delete-form').submit(); }"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 flex items-center">
                                <i class="fas fa-trash mr-2"></i> Hapus Konten
                            </button>
                        </div>

                        <div class="flex space-x-4">
                            <a href="{{ route('admin.tentang.index') }}"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-300 flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Form untuk Hapus (hidden) -->
        <form id="delete-form" action="{{ route('admin.tentang.destroy', $tentang->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<!-- Script untuk Preview Image -->
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            imagePreview.classList.add('hidden');
        }
    }

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const judul = document.getElementById('judul').value.trim();
        const isi = document.getElementById('isi').value.trim();

        if (!judul || !isi) {
            e.preventDefault();
            alert('Judul dan Isi konten wajib diisi!');
            return false;
        }

        return true;
    });

    // Konfirmasi sebelum hapus gambar
    const hapusGambarCheckbox = document.querySelector('input[name="hapus_gambar"]');
    if (hapusGambarCheckbox) {
        hapusGambarCheckbox.addEventListener('change', function() {
            if (this.checked) {
                if (!confirm('Yakin ingin menghapus gambar ini? Gambar akan dihapus permanen.')) {
                    this.checked = false;
                }
            }
        });
    }
</script>

<!-- Styles -->
<style>
    textarea {
        resize: vertical;
        min-height: 200px;
    }

    #preview {
        max-width: 100%;
        max-height: 300px;
    }

    .border-dashed:hover {
        border-color: #0d9488;
    }
</style>
@endsection