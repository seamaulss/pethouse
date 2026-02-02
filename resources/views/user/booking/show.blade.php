<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - PetHouse</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('user.booking.riwayat') }}"
                        class="inline-flex items-center text-teal-600 hover:text-teal-700 mb-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
                    </a>
                    <h1 class="text-3xl font-bold text-teal-600 mb-2">Detail Booking</h1>
                    <p class="text-gray-600">Informasi lengkap booking Anda</p>
                </div>
                <div>
                    @php
                    $statusClass = match($booking->status) {
                    'pending' => 'status-pending',
                    'diterima' => 'status-diterima',
                    'in_progress' => 'status-in_progress',
                    'selesai' => 'status-selesai',
                    'pembatalan' => 'status-dibatalkan',
                    'perpanjangan' => 'status-perpanjangan',
                    default => 'bg-gray-100 text-gray-800'
                    };

                    $statusText = match($booking->status) {
                    'pending' => 'Menunggu',
                    'diterima' => 'Diterima',
                    'in_progress' => 'Sedang Dititipkan',
                    'selesai' => 'Selesai',
                    'pembatalan' => 'Pembatalan Diajukan',
                    'perpanjangan' => 'Perpanjangan Diajukan',
                    default => ucfirst($booking->status)
                    };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Hitung durasi dan biaya di view -->
        @php
        // Hitung durasi
        $masuk = \Carbon\Carbon::parse($booking->tanggal_masuk);
        $keluar = \Carbon\Carbon::parse($booking->tanggal_keluar);
        $durasi = $masuk->diffInDays($keluar);

        // Ambil harga per hari dari controller atau hitung di sini
        $hargaPerHari = $hargaPerHari ?? 0;
        $totalBiaya = $durasi * $hargaPerHari;
        @endphp

        <!-- Card Detail -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Header Card -->
            <div class="bg-teal-50 px-6 py-4 border-b border-teal-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-teal-800">Kode Booking: {{ $booking->kode_booking }}</h2>
                        <p class="text-sm text-teal-600">Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</p>
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
                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Informasi Pemilik -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user text-teal-600 mr-2"></i>
                            Informasi Pemilik
                        </h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-600 text-sm">Nama:</span>
                                <p class="font-medium">{{ $booking->nama_pemilik }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Email:</span>
                                <p class="font-medium">{{ $booking->email }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">WhatsApp:</span>
                                <p class="font-medium">{{ $booking->nomor_wa ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Hewan -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-paw text-pink-500 mr-2"></i>
                            Informasi Hewan
                        </h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-600 text-sm">Nama Hewan:</span>
                                <p class="font-medium">{{ $booking->nama_hewan }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Jenis:</span>
                                <p class="font-medium">{{ $booking->jenis_hewan }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 text-sm">Ukuran:</span>
                                <p class="font-medium">{{ $booking->ukuran_hewan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Booking -->
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-teal-600 mr-2"></i>
                        Informasi Booking
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="text-gray-600 text-sm mb-1">Layanan</div>
                            <div class="font-bold text-lg">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                        </div>
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="text-gray-600 text-sm mb-1">Tanggal Masuk</div>
                            <div class="font-bold text-lg">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d-m-Y') }}</div>
                        </div>
                        <div class="bg-white border border-gray-200 p-4 rounded-lg">
                            <div class="text-gray-600 text-sm mb-1">Tanggal Keluar</div>
                            <div class="font-bold text-lg">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d-m-Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Harga -->
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                        Informasi Biaya
                    </h3>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-gray-600 text-sm mb-1">Durasi</div>
                                <div class="font-bold text-lg text-teal-600">{{ $durasi }} Hari</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-600 text-sm mb-1">Harga per Hari</div>
                                <div class="font-bold text-lg text-teal-600">
                                    @if($hargaPerHari > 0)
                                    Rp {{ number_format($hargaPerHari, 0, ',', '.') }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-600 text-sm mb-1">Total Biaya</div>
                                <div class="font-bold text-lg text-green-600">
                                    @if($totalBiaya > 0)
                                    Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                @if($booking->catatan)
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-sticky-note text-amber-500 mr-2"></i>
                        Catatan
                    </h3>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $booking->catatan }}</p>
                    </div>
                </div>
                @endif

                <!-- Bukti DP -->
                @if($booking->bukti_dp)
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-receipt text-teal-600 mr-2"></i>
                        Bukti Transfer
                    </h3>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <a href="{{ Storage::url($booking->bukti_dp) }}"
                            target="_blank"
                            class="inline-flex items-center text-teal-600 hover:text-teal-700">
                            <i class="fas fa-eye mr-2"></i> Lihat Bukti Transfer
                        </a>
                    </div>
                </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <!-- Tombol WhatsApp -->
                    @if($booking->nomor_wa)
                    @php
                    $wa_number = preg_replace('/^0/', '62', $booking->nomor_wa);
                    $status_wa_text = match($booking->status) {
                    'pending' => 'sedang menunggu konfirmasi',
                    'diterima' => 'telah DITERIMA',
                    'in_progress' => 'sedang dititipkan',
                    'selesai' => 'telah SELESAI',
                    'dibatalkan' => 'telah DIBATALKAN',
                    'perpanjangan' => 'sedang dalam proses perpanjangan',
                    default => 'dalam proses'
                    };
                    $wa_text = urlencode("Halo, saya ingin bertanya tentang booking dengan kode: {$booking->kode_booking} (Status: {$status_wa_text})");
                    @endphp
                    <a href="https://wa.me/{{ $wa_number }}?text={{ $wa_text }}"
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                        <i class="fab fa-whatsapp mr-2"></i> Chat via WhatsApp
                    </a>
                    @endif

                    <!-- Tombol Perpanjang -->
                    @if(in_array($booking->status, ['diterima', 'in_progress']) && \Carbon\Carbon::parse($booking->tanggal_keluar)->gte(\Carbon\Carbon::today()))
                    <a href="{{ route('user.booking.extend.form', $booking->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
                        <i class="fas fa-calendar-plus mr-2"></i> Perpanjang Booking
                    </a>
                    @endif

                    <!-- Tombol Batalkan -->
                    @if(in_array($booking->status, ['pending', 'diterima']))
                    <button type="button"
                        onclick="openCancelModal({{ $booking->id }}, '{{ $booking->kode_booking }}', '{{ $booking->nama_hewan }}')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                        <i class="fas fa-times mr-2"></i> Batalkan Booking
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Log Harian (jika ada) -->
        @if($booking->dailyLogs && $booking->dailyLogs->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list text-teal-600 mr-2"></i>
                Log Harian Perawatan
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-teal-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-center">Makan Pagi</th>
                            <th class="px-4 py-2 text-center">Makan Siang</th>
                            <th class="px-4 py-2 text-center">Makan Malam</th>
                            <th class="px-4 py-2 text-center">Minum</th>
                            <th class="px-4 py-2 text-center">Jalan-jalan</th>
                            <th class="px-4 py-2 text-left">Buang Air</th>
                            <th class="px-4 py-2 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->dailyLogs as $log)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center">
                                @if($log->makan_pagi)
                                <i class="fas fa-check text-green-500"></i>
                                @else
                                <i class="fas fa-times text-red-500"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($log->makan_siang)
                                <i class="fas fa-check text-green-500"></i>
                                @else
                                <i class="fas fa-times text-red-500"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($log->makan_malam)
                                <i class="fas fa-check text-green-500"></i>
                                @else
                                <i class="fas fa-times text-red-500"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($log->minum)
                                <i class="fas fa-check text-green-500"></i>
                                @else
                                <i class="fas fa-times text-red-500"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($log->jalan_jalan)
                                <i class="fas fa-check text-green-500"></i>
                                @else
                                <i class="fas fa-times text-red-500"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @switch($log->buang_air)
                                @case('normal')
                                <span class="text-green-600">Normal</span>
                                @break
                                @case('diare')
                                <span class="text-red-600">Diare</span>
                                @break
                                @case('sembelit')
                                <span class="text-yellow-600">Sembelit</span>
                                @break
                                @default
                                <span class="text-gray-500">Belum</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-2">{{ $log->catatan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Pembatalan -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-backdrop">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Batalkan Booking</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span id="cancel-booking-code" class="font-mono text-teal-600"></span> •
                                    <span id="cancel-pet-name" class="font-medium"></span>
                                </p>
                            </div>
                        </div>
                        <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="cancelForm" method="POST" class="p-6">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Alasan Pembatalan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_cancel"
                            rows="4"
                            required
                            placeholder="Mengapa Anda ingin membatalkan booking ini?"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                            maxlength="500"
                            id="cancel-reason-textarea"></textarea>
                        <div class="flex justify-between text-sm text-gray-500 mt-1">
                            <span id="char-count">0/500 karakter</span>
                            <span class="text-red-500">Wajib diisi</span>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                            <div>
                                <h4 class="font-medium text-red-800 mb-1">Perhatian!</h4>
                                <ul class="text-sm text-red-700 space-y-1">
                                    <li>• Pembatalan booking tidak dapat dibatalkan</li>
                                    <li>• Status booking akan berubah menjadi "Dibatalkan"</li>
                                    <li>• Jika sudah membayar, hubungi admin untuk refund</li>
                                    <li>• Konfirmasi akan dikirim ke WhatsApp Anda</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex space-x-3">
                        <button type="button"
                            onclick="closeCancelModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            id="cancel-submit-btn"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Script modal
        let currentBookingId = null;
        let currentBookingCode = null;

        function openCancelModal(bookingId, bookingCode, petName) {
            currentBookingId = bookingId;
            currentBookingCode = bookingCode;

            document.getElementById('cancel-booking-code').textContent = bookingCode;
            document.getElementById('cancel-pet-name').textContent = petName;
            document.getElementById('cancelForm').action = `/user/booking/${bookingId}/cancel`;

            document.getElementById('cancel-reason-textarea').value = '';
            document.getElementById('char-count').textContent = '0/500 karakter';

            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                document.getElementById('cancel-reason-textarea').focus();
            }, 100);
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentBookingId = null;
            currentBookingCode = null;

            document.getElementById('cancelForm').reset();
            document.getElementById('char-count').textContent = '0/500 karakter';
        }

        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });

        document.getElementById('cancel-reason-textarea').addEventListener('input', function(e) {
            const count = e.target.value.length;
            document.getElementById('char-count').textContent = `${count}/500 karakter`;

            if (count > 450) {
                document.getElementById('char-count').classList.add('text-red-600');
                document.getElementById('char-count').classList.remove('text-gray-500');
            } else {
                document.getElementById('char-count').classList.remove('text-red-600');
                document.getElementById('char-count').classList.add('text-gray-500');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCancelModal();
            }
        });

        document.getElementById('cancelForm').addEventListener('submit', function(e) {
            const reasonTextarea = document.getElementById('cancel-reason-textarea');
            const submitBtn = document.getElementById('cancel-submit-btn');

            if (!reasonTextarea.value.trim()) {
                e.preventDefault();
                alert('Harap isi alasan pembatalan.');
                reasonTextarea.focus();
                return;
            }

            if (reasonTextarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Alasan pembatalan minimal 10 karakter.');
                reasonTextarea.focus();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            if (!confirm(`Anda yakin ingin membatalkan booking ${currentBookingCode}?`)) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-times mr-2"></i> Ya, Batalkan';
            }
        });
    </script>
</body>

</html>