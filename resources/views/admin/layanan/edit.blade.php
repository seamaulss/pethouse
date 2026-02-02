<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Layanan - PetHouse Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #faf9f6 0%, #f5f3ef 100%);
        }
        
        .file-input-container {
            position: relative;
            display: flex;
            align-items: center;
            background: white;
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
            padding: 0.75rem;
            transition: all 0.3s;
        }
        
        .file-input-container:hover {
            border-color: #3b82f6;
            background: #f0f9ff;
        }
        
        .file-input-container input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #3b82f6;
            color: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-input-label:hover {
            background: #2563eb;
        }
        
        .file-name {
            margin-left: 1rem;
            color: #6b7280;
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Container -->
    <div class="flex">
        <!-- Sidebar -->
        @include('admin.layouts.navbar')
        
        <!-- Main Content Area -->
        <div class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
            <!-- Mobile Header -->
            <div class="lg:hidden fixed top-0 left-0 right-0 bg-white shadow-md z-30 p-4 flex items-center justify-between">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg bg-gray-100">
                    <i class="fas fa-bars text-gray-700"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-800">Edit Layanan</h1>
                <div class="w-10"></div> <!-- Spacer -->
            </div>
            
            <!-- Content -->
            <main class="p-4 lg:p-8 pt-16 lg:pt-8">
                <!-- Breadcrumb and Title -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                        <a href="{{ route('admin.layanan.index') }}" class="hover:text-blue-600 hover:underline">
                            <i class="fas fa-chevron-left mr-1"></i> Kembali ke Data Layanan
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="font-medium text-gray-800">Edit Layanan</span>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-800">Edit Layanan</h1>
                    <p class="text-gray-600 mt-1">Perbarui informasi layanan. Harga diatur per jenis hewan.</p>
                </div>

                <!-- Form Container -->
                <div class="max-w-4xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-red-800">Terjadi Kesalahan</h3>
                                    <p class="text-sm text-red-600 mt-1">Mohon periksa form di bawah</p>
                                </div>
                            </div>
                            <ul class="list-disc pl-5 text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h2 class="text-xl font-bold text-gray-800">Informasi Layanan</h2>
                            <p class="text-gray-600 text-sm mt-1">Lengkapi form untuk mengubah layanan</p>
                        </div>
                        
                        <form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                            @csrf
                            @method('PUT')
                            
                            <!-- Grid Layout -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Nama Layanan -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nama Layanan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nama_layanan" 
                                               value="{{ old('nama_layanan', $layanan->nama_layanan) }}" 
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                               placeholder="Contoh: Pet Hotel Premium">
                                    </div>

                                    <!-- Current Image -->
                                    @if($layanan->gambar)
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Gambar Saat Ini
                                            </label>
                                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 bg-gray-50">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative">
                                                        @php
                                                            // Cek gambar dari berbagai sumber
                                                            $imageUrl = null;
                                                            if (\Illuminate\Support\Facades\Storage::exists('public/layanan/' . $layanan->gambar)) {
                                                                $imageUrl = \Illuminate\Support\Facades\Storage::url('layanan/' . $layanan->gambar);
                                                            } elseif (file_exists(public_path('storage/layanan/' . $layanan->gambar))) {
                                                                $imageUrl = asset('storage/layanan/' . $layanan->gambar);
                                                            } elseif (file_exists(public_path('assets/img/layanan/' . $layanan->gambar))) {
                                                                $imageUrl = asset('assets/img/layanan/' . $layanan->gambar);
                                                            }
                                                        @endphp
                                                        
                                                        @if($imageUrl)
                                                            <img src="{{ $imageUrl }}" 
                                                                 alt="{{ $layanan->nama_layanan }}" 
                                                                 class="w-24 h-24 object-cover rounded-lg shadow-sm">
                                                        @else
                                                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                                            </div>
                                                        @endif
                                                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-medium text-gray-800">{{ $layanan->gambar }}</p>
                                                        <p class="text-sm text-gray-500 mt-1">Gambar saat ini</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Gambar Baru -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Ganti Gambar <span class="text-gray-500">(opsional)</span>
                                        </label>
                                        
                                        <div class="file-input-container">
                                            <input type="file" 
                                                   id="gambar" 
                                                   name="gambar" 
                                                   accept="image/*"
                                                   onchange="displayFileName(this)">
                                            <label for="gambar" class="file-input-label">
                                                <i class="fas fa-folder-open mr-2"></i>
                                                Pilih File Baru
                                            </label>
                                            <span id="fileName" class="file-name">Tidak ada file dipilih</span>
                                        </div>
                                        
                                        <p class="mt-2 text-sm text-gray-500">
                                            Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.
                                        </p>
                                        
                                        <!-- Image Preview -->
                                        <div id="imagePreview" class="mt-4 hidden">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Preview Gambar Baru
                                            </label>
                                            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                                                <img id="previewImage" 
                                                     class="w-32 h-32 object-cover rounded-lg mx-auto">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Deskripsi Layanan
                                        </label>
                                        <textarea name="deskripsi" 
                                                  rows="4"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                  placeholder="Deskripsi singkat tentang layanan...">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                                <button type="submit" 
                                        class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Simpan Perubahan
                                </button>
                                
                                <a href="{{ route('admin.layanan.atur-harga', $layanan->id) }}" 
                                   class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <i class="fas fa-coins"></i>
                                    Atur Harga
                                </a>
                                
                                <a href="{{ route('admin.layanan.index') }}" 
                                   class="flex-1 sm:flex-none px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-2">Informasi Penting</h4>
                                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                                    <li>Setelah menyimpan perubahan, Anda akan tetap berada di halaman ini.</li>
                                    <li>Gambar lama akan diganti dengan gambar baru jika Anda mengupload gambar baru.</li>
                                    <li>Harga layanan dapat diatur melalui tombol "Atur Harga".</li>
                                    <li>Pastikan nama layanan jelas dan mudah dipahami oleh pelanggan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" 
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
         onclick="toggleSidebar()"></div>

    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.querySelector('main').parentElement;
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                mainContent.classList.remove('ml-64');
            } else {
                mainContent.classList.add('ml-64');
            }
        }

        // File name display
        function displayFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'Tidak ada file dipilih';
            document.getElementById('fileName').textContent = fileName;
            
            // Show preview if image
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const preview = document.getElementById('previewImage');
                const previewContainer = document.getElementById('imagePreview');
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // File size validation (client side)
        document.getElementById('gambar')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (file && file.size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                this.value = '';
                document.getElementById('fileName').textContent = 'Tidak ada file dipilih';
                document.getElementById('imagePreview').classList.add('hidden');
            }
        });

        // Auto-hide sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');
            const isMobile = window.innerWidth < 1024;
            
            if (isMobile && !sidebar.contains(e.target) && !e.target.closest('button[onclick*="toggleSidebar"]') && !overlay.classList.contains('hidden')) {
                toggleSidebar();
            }
        });

        // Adjust layout on resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.querySelector('main').parentElement;
            
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                mainContent.classList.add('ml-64');
            } else {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 1024) {
                document.querySelector('main').parentElement.classList.add('ml-64');
            }
        });
    </script>
</body>
</html>