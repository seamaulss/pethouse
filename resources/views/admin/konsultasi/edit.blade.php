<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status Konsultasi - PetHouse Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
        body { background-color: #faf9f6; font-family: 'Poppins', sans-serif; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="min-h-screen">
    @include('admin.layouts.navbar')

    <!-- Hamburger Mobile -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobileMenuBtn" class="bg-white rounded-full p-3 shadow-lg text-teal-600 text-2xl hover:bg-gray-100 transition">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="flex">
        
        <div class="flex-1 lg:ml-64">
            <div class="p-6 max-w-5xl mx-auto">
                <!-- Header -->
                <div class="mb-10 text-center lg:text-left" data-aos="fade-down">
                    <h1 class="text-4xl font-bold text-gray-800">Ubah Status Konsultasi</h1>
                    <p class="text-lg text-gray-600 mt-3">Perbarui status dan kirim notifikasi otomatis ke WhatsApp pelanggan</p>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl" data-aos="fade-up">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        
                        @if(session('wa_link'))
                            <div class="mt-3">
                                <a href="{{ session('wa_link') }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fab fa-whatsapp mr-2"></i> Kirim Notifikasi WhatsApp
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl" data-aos="fade-up">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Detail Konsultasi -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-right">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-stethoscope text-teal-600 mr-3"></i> Detail Permintaan
                        </h2>
                        <div class="space-y-5 text-gray-700">
                            <div>
                                <span class="font-medium">Nama Pemilik:</span><br>
                                <span class="text-lg">{{ $konsultasi->nama_pemilik }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Nomor WhatsApp:</span><br>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $konsultasi->no_wa) }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-700 font-medium">
                                    <i class="fab fa-whatsapp mr-1"></i> {{ $konsultasi->no_wa }}
                                </a>
                            </div>
                            <div>
                                <span class="font-medium">Topik Konsultasi:</span><br>
                                <span class="text-lg">{{ $konsultasi->topik }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Jenis Hewan:</span><br>
                                <span class="text-lg">{{ $konsultasi->jenis_hewan }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Jadwal Janji:</span><br>
                                <span class="text-lg">
                                    {{ $konsultasi->tanggal_janji->format('l, d F Y') }}<br>
                                    <span class="text-teal-600 font-medium">
                                        {{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB
                                    </span>
                                </span>
                            </div>
                            <div>
                                <span class="font-medium">Catatan Tambahan:</span><br>
                                <p class="mt-2 bg-gray-50 p-4 rounded-xl">
                                    {{ $konsultasi->catatan ? nl2br(e($konsultasi->catatan)) : '-' }}
                                </p>
                            </div>
                            <div>
                                <span class="font-medium">Status Saat Ini:</span><br>
                                <span class="inline-block mt-2 px-5 py-3 rounded-full text-sm font-bold {{ $konsultasi->status_class }}">
                                    {{ $konsultasi->status_label }}
                                </span>
                            </div>
                            
                            <!-- Balasan Dokter (jika ada) -->
                            @if($konsultasi->balasan_dokter)
                                <div>
                                    <span class="font-medium">Balasan Dokter:</span><br>
                                    <p class="mt-2 bg-teal-50 p-4 rounded-xl border border-teal-200">
                                        {{ (e($konsultasi->balasan_dokter)) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Form Ubah Status -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-left">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-8 flex items-center">
                            <i class="fas fa-edit text-amber-500 mr-3"></i> Ubah Status
                        </h2>

                        <form method="POST" action="{{ route('admin.konsultasi.update', $konsultasi->id) }}" class="space-y-8">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-4">Pilih Status Baru</label>
                                <select name="status" 
                                        class="w-full px-6 py-4 text-lg bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100">
                                    <option value="pending" {{ $konsultasi->status === 'pending' ? 'selected' : '' }}>
                                        📌 Menunggu Konfirmasi
                                    </option>
                                    <option value="diterima" {{ $konsultasi->status === 'diterima' ? 'selected' : '' }}>
                                        ✅ Diterima & Dijadwalkan
                                    </option>
                                    <option value="selesai" {{ $konsultasi->status === 'selesai' ? 'selected' : '' }}>
                                        🎉 Selesai
                                    </option>
                                </select>
                                @error('status')
                                    <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Balasan Dokter -->
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-4">Balasan Dokter (Opsional)</label>
                                <textarea name="balasan_dokter" 
                                          rows="4"
                                          class="w-full px-6 py-4 text-lg bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                                          placeholder="Tulis balasan dokter di sini...">{{ old('balasan_dokter', $konsultasi->balasan_dokter) }}</textarea>
                                @error('balasan_dokter')
                                    <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="pt-6">
                                <button type="submit"
                                        class="w-full py-5 px-8 bg-gradient-to-r from-teal-500 to-teal-700 text-white text-xl font-bold rounded-3xl shadow-lg hover:from-teal-600 hover:to-teal-800 transform hover:scale-105 transition duration-300 flex items-center justify-center">
                                    <i class="fab fa-whatsapp mr-3 text-2xl"></i>
                                    Simpan & Kirim Notifikasi WhatsApp
                                </button>
                            </div>

                            <p class="text-center text-sm text-gray-500 mt-6">
                                Setelah disimpan, link WhatsApp akan muncul di atas untuk mengirim notifikasi otomatis.
                            </p>
                        </form>

                        <!-- Tombol Kembali -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.konsultasi.index') }}" 
                               class="text-teal-600 hover:text-teal-700 font-medium flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Konsultasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // Mobile sidebar toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (mobileMenuBtn && sidebar && overlay) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // Auto-dismiss flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[data-dismiss="flash"]');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>