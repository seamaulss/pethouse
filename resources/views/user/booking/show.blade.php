<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - LARAPetHouse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-diterima {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-in_progress {
            background-color: #ccfbf1;
            color: #0d9488;
        }

        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-dibatalkan {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-perpanjangan {
            background-color: #f3e8ff;
            color: #7c3aed;
        }
    </style>
</head>

<body class="min-h-screen p-4">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('user.booking.riwayat') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
                    </a>
                    <h1 class="text-3xl font-bold text-teal-600 mb-2">Detail Booking</h1>
                    <p class="text-gray-600">Informasi lengkap booking Anda</p>
                </div>
                <div>
                    <span class="{{ $booking->status_class }} px-2 py-1 rounded border text-xs font-bold">
                        {{ $booking->status_text }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-teal-50 px-6 py-4 border-b border-teal-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-teal-800">Kode Booking: {{ $booking->kode_booking }}</h2>
                        <p class="text-sm text-teal-600">Dibuat: {{ $booking->created_at->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        @if($booking->dp_dibayar === 'Ya')
                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                            <i class="fas fa-check-circle mr-1"></i> DP Lunas
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                            <i class="fas fa-clock mr-1"></i> DP Belum Dibayar
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user text-teal-600 mr-2"></i> Informasi Pemilik
                        </h3>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="text-gray-600">Nama:</span> {{ $booking->nama_pemilik }}</p>
                            <p class="text-sm"><span class="text-gray-600">Email:</span> {{ $booking->email }}</p>
                            <p class="text-sm"><span class="text-gray-600">WhatsApp:</span> {{ $booking->nomor_wa ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-paw text-pink-500 mr-2"></i> Informasi Hewan
                        </h3>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="text-gray-600">Nama Hewan:</span> {{ $booking->nama_hewan }}</p>
                            <p class="text-sm"><span class="text-gray-600">Jenis:</span> {{ $booking->jenis_hewan }}</p>
                            <p class="text-sm"><span class="text-gray-600">Ukuran:</span> {{ $booking->ukuran_hewan }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i> Rincian Biaya
                    </h3>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-gray-600 text-sm">Durasi</div>
                                <div class="font-bold text-lg text-teal-600">{{ $durasi }} Hari</div>
                            </div>
                            <div>
                                <div class="text-gray-600 text-sm">Harga per Hari</div>
                                <div class="font-bold text-lg text-teal-600">Rp {{ number_format($hargaPerHari, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-gray-600 text-sm">Total Biaya</div>
                                <div class="font-bold text-lg text-green-600">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">

                    @if($booking->dp_dibayar !== 'Ya' && !in_array($booking->status, ['pembatalan', 'selesai']))
                    <button type="button" onclick="simulasiBayar('{{ $booking->id }}', '{{ $booking->kode_booking }}')"
                        class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold transition-all shadow-md hover:scale-105">
                        <i class="fas fa-hand-holding-usd mr-2"></i> Konfirmasi Sudah Bayar
                    </button>
                    @endif

                    <a href="{{ route('user.booking.pdf', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium">
                        <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
                    </a>

                    @if($booking->nomor_wa)
                    <a href="https://wa.me/{{ $booking->nomor_wa }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                        <i class="fab fa-whatsapp mr-2"></i> Chat Admin
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function simulasiBayar(id, kode) {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda sudah melakukan scan QRIS untuk booking " + kode + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                confirmButtonText: 'Ya, Sudah Bayar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memverifikasi...',
                        text: 'Sistem sedang memvalidasi pembayaran Anda.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // GUNAKAN HELPER ROUTE LARAVEL (Lebih Aman dari 404)
                    window.location.href = "{{ route('user.booking.bayar_simulasi', ':id') }}".replace(':id', id);
                }
            })
        }
    </script>
</body>

</html>