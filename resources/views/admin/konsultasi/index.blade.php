<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Konsultasi - PetHouse Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
        body {
            background: linear-gradient(135deg, #faf9f6 0%, #f5f3ef 100%);
            font-family: 'Poppins', sans-serif;
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(13, 148, 136, 0.3);
        }

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: rgba(13, 148, 136, 0.05);
            transform: translateX(8px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="min-h-screen">
    @include('admin.layouts.navbar')

    <div class="flex">
        @include('admin.layouts.navbar')
        
        <div class="flex-1 lg:ml-64">
            <div class="p-6 max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-10" data-aos="fade-down">
                    <h1 class="text-4xl font-bold text-gray-800">Data Konsultasi</h1>
                    <p class="text-lg text-gray-600 mt-2">Kelola semua permintaan konsultasi kesehatan hewan</p>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl" data-aos="fade-up">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl" data-aos="fade-up">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Tabel Konsultasi -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden card-hover" data-aos="fade-up">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-teal-500 to-teal-700 text-white">
                                <tr>
                                    <th class="px-6 py-5 text-left text-sm font-semibold">No</th>
                                    <th class="px-6 py-5 text-left text-sm font-semibold">Pemilik</th>
                                    <th class="px-6 py-5 text-left text-sm font-semibold">Topik & Hewan</th>
                                    <th class="px-6 py-5 text-left text-sm font-semibold">Jadwal</th>
                                    <th class="px-6 py-5 text-left text-sm font-semibold">WhatsApp</th>
                                    <th class="px-6 py-5 text-center text-sm font-semibold">Status</th>
                                    <th class="px-6 py-5 text-center text-sm font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($konsultasi as $item)
                                    @php
                                        $wa_number = preg_replace('/^0/', '62', $item->no_wa ?? '');
                                        $wa_greeting = urlencode("Halo {$item->nama_pemilik},\n\nTerima kasih telah mengajukan konsultasi dengan topik *{$item->topik}*.\nKami akan segera menghubungi Anda.\n\nPetHouse 🐾");
                                    @endphp
                                    <tr class="table-row">
                                        <td class="px-6 py-5 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-5 font-medium text-gray-900">
                                            {{ $item->nama_pemilik }}
                                            @if($item->user)
                                                <br>
                                                <span class="text-xs text-gray-500">User ID: {{ $item->user_id }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="font-medium text-gray-900">{{ $item->topik }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ $item->jenis_hewan }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-gray-700">
                                            {{ $item->tanggal_janji->format('d M Y') }}
                                            <br>
                                            <span class="text-sm text-gray-500">
                                                {{ date('H:i', strtotime($item->jam_janji)) }} WIB
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <a href="https://wa.me/{{ $wa_number }}?text={{ $wa_greeting }}" 
                                               target="_blank"
                                               class="text-green-600 hover:text-green-700 font-medium">
                                                <i class="fab fa-whatsapp mr-1"></i> {{ $item->no_wa }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="px-4 py-2 rounded-full text-xs font-bold {{ $item->status_class }}">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-center space-x-3">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.konsultasi.show', $item->id) }}"
                                               class="inline-block px-4 py-2 text-sm text-dark bg-blue-500 rounded-xl hover:bg-blue-600 transition shadow">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>

                                            <!-- Ubah Status -->
                                            <a href="{{ route('admin.konsultasi.edit', $item->id) }}"
                                               class="inline-block px-4 py-2 text-sm text-black bg-blue-500 rounded-xl hover:bg-amber-600 transition shadow">
                                                <i class="fas fa-edit mr-1"></i> Ubah
                                            </a>

                                            <!-- Hapus -->
                                            <form action="{{ route('admin.konsultasi.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Yakin hapus data konsultasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700 transition shadow">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-16 text-gray-500 text-lg">
                                            <i class="fas fa-inbox text-4xl mb-4"></i><br>
                                            Belum ada data konsultasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($konsultasi->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $konsultasi->links() }}
                        </div>
                    @endif
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