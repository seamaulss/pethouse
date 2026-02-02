<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konsultasi - PetHouse Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
        body {
            background: linear-gradient(135deg, #faf9f6 0%, #f5f3ef 100%);
            font-family: 'Poppins', sans-serif;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .message-bubble {
            max-width: 70%;
            border-radius: 1.25rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }

        .message-user {
            background-color: #0d9488;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0.5rem;
        }

        .message-dokter {
            background-color: #e5e7eb;
            color: #374151;
            margin-right: auto;
            border-bottom-left-radius: 0.5rem;
        }
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
            <div class="p-6 max-w-6xl mx-auto">
                <!-- Header -->
                <div class="mb-8" data-aos="fade-down">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Detail Konsultasi</h1>
                            <p class="text-gray-600 mt-2">Kode: <span class="font-semibold">{{ $konsultasi->kode_konsultasi }}</span></p>
                        </div>
                        <a href="{{ route('admin.konsultasi.index') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 transition font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl" data-aos="fade-up">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
                @endif

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Informasi Utama -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Data Konsultasi -->
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-right">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-file-medical text-teal-600 mr-3"></i> Informasi Konsultasi
                            </h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Nama Pemilik</p>
                                    <p class="text-lg font-medium">{{ $konsultasi->nama_pemilik }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">No. WhatsApp</p>
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $konsultasi->no_wa) }}"
                                        target="_blank"
                                        class="text-lg font-medium text-green-600 hover:text-green-700">
                                        <i class="fab fa-whatsapp mr-1"></i> {{ $konsultasi->no_wa }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jenis Hewan</p>
                                    <p class="text-lg font-medium">{{ $konsultasi->jenis_hewan }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Topik</p>
                                    <p class="text-lg font-medium">{{ $konsultasi->topik }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Konsultasi</p>
                                    <p class="text-lg font-medium">{{ $konsultasi->tanggal_janji->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jam Konsultasi</p>
                                    <p class="text-lg font-medium">{{ date('H:i', strtotime($konsultasi->jam_janji)) }} WIB</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Catatan Pemilik</p>
                                    <p class="mt-2 p-4 bg-gray-50 rounded-2xl">{{ $konsultasi->catatan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Balasan Dokter -->
                        @if($konsultasi->balasan_dokter)
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-right">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-stethoscope text-amber-500 mr-3"></i> Balasan Dokter
                            </h2>
                            <div class="p-6 bg-amber-50 rounded-2xl border border-amber-200">
                                <p class="text-gray-800 whitespace-pre-line">{{ $konsultasi->balasan_dokter }}</p>
                                @if($konsultasi->dokter)
                                <p class="mt-4 text-sm text-gray-600">Oleh: <span class="font-medium">{{ $konsultasi->dokter->username }}</span></p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Riwayat Chat/Conversation -->
                        @if($konsultasi->balasan->count() > 0)
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-right">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-comments text-blue-500 mr-3"></i> Riwayat Percakapan
                            </h2>
                            <div class="space-y-4 max-h-96 overflow-y-auto p-4">
                                @foreach($konsultasi->balasan->sortBy('created_at') as $balasan)
                                <div class="flex {{ $balasan->pengirim === 'dokter' ? 'justify-start' : 'justify-end' }}">
                                    <div class="message-bubble {{ $balasan->pengirim === 'dokter' ? 'message-dokter' : 'message-user' }}">
                                        <p class="text-sm">{{ $balasan->isi }}</p>
                                        <p class="text-xs mt-2 opacity-75">
                                            {{ $balasan->created_at->format('d M H:i') }}
                                            @if($balasan->pengirim === 'dokter')
                                            <i class="fas fa-user-md ml-1"></i>
                                            @else
                                            <i class="fas fa-user ml-1"></i>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar - Status & Aksi -->
                    <div class="space-y-8">
                        <!-- Status -->
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-left">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-info-circle text-pink-500 mr-3"></i> Status
                            </h2>
                            <div class="text-center">
                                <span class="inline-block px-6 py-3 rounded-full text-lg font-bold {{ $konsultasi->status_class }}">
                                    {{ $konsultasi->status_label }}
                                </span>
                                <p class="text-gray-600 mt-4 text-sm">
                                    @if($konsultasi->status === 'pending')
                                    Menunggu konfirmasi dokter
                                    @elseif($konsultasi->status === 'diterima')
                                    Sudah dijadwalkan
                                    @else
                                    Konsultasi telah selesai
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Dokter yang Menangani -->
                        @if($konsultasi->dokter)
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-left">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-user-md text-teal-600 mr-3"></i> Dokter
                            </h2>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-user-md text-teal-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-lg">{{ $konsultasi->dokter->username }}</p>
                                    <p class="text-gray-600 text-sm">{{ $konsultasi->dokter->email }}</p>
                                    <p class="text-gray-600 text-sm">{{ $konsultasi->dokter->nomor_wa ?? 'No. WA tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Aksi -->
                        <div class="bg-white rounded-3xl shadow-xl p-8 card-hover" data-aos="fade-left">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-cogs text-gray-600 mr-3"></i> Aksi
                            </h2>
                            <div class="space-y-4">
                                <a href="{{ route('admin.konsultasi.edit', $konsultasi->id) }}"
                                    class="block w-full py-4 px-6 bg-teal-600 text-dark text-center rounded-2xl hover:bg-teal-700 transition font-medium">
                                    <i class="fas fa-edit mr-2"></i> Ubah Status
                                </a>

                                <!-- Form Tambah Balasan -->
                                <form action="{{ route('admin.konsultasi.add-balasan', $konsultasi->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Balasan</label>
                                        <textarea name="isi" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                            placeholder="Tulis balasan..." required></textarea>
                                    </div>
                                    <input type="hidden" name="pengirim" value="dokter">
                                    <button type="submit"
                                        class="w-full py-4 px-6 bg-blue-600 text-white text-center rounded-2xl hover:bg-blue-700 transition font-medium">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim Balasan
                                    </button>
                                </form>

                                <!-- Hapus -->
                                <form action="{{ route('admin.konsultasi.destroy', $konsultasi->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus konsultasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full py-4 px-6 bg-red-600 text-white text-center rounded-2xl hover:bg-red-700 transition font-medium">
                                        <i class="fas fa-trash mr-2"></i> Hapus Konsultasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

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

        // Auto scroll ke bottom untuk chat
        const chatContainer = document.querySelector('.overflow-y-auto');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    </script>
</body>

</html>