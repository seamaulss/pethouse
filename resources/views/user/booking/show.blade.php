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
    </style>
</head>

<body class="min-h-screen p-4">
    <div class="max-w-4xl mx-auto py-8">
        
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('user.booking.riwayat') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 mb-4 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
                    </a>
                    <h1 class="text-3xl font-bold text-teal-600">Detail Booking</h1>
                    <p class="text-gray-600">ID Transaksi: <span class="font-mono font-bold">{{ $booking->kode_booking }}</span></p>
                </div>
                <div>
                    <span class="{{ $booking->status_class }} px-4 py-2 rounded-full border text-sm font-bold shadow-sm">
                        <i class="fas fa-info-circle mr-1"></i> {{ $booking->status_text }}
                    </span>
                </div>
            </div>
        </div>

        @if($booking->status === 'perpanjangan')
        <div class="mb-6 bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <i class="fas fa-history text-purple-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-purple-800 font-bold">Menunggu Persetujuan Perpanjangan</h4>
                    <p class="text-purple-700 text-sm">Anda mengajukan perpanjangan hingga <strong>{{ \Carbon\Carbon::parse($booking->tanggal_perpanjangan)->translatedFormat('d F Y') }}</strong>. Mohon tunggu verifikasi admin.</p>
                </div>
            </div>
        </div>
        @endif

        @if($booking->status === 'pembatalan' && !str_contains($booking->alasan_cancel, '[DISETUJUI]'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-red-800 font-bold">Permintaan Pembatalan Diproses</h4>
                    <p class="text-red-700 text-sm">Alasan: "{{ $booking->alasan_cancel }}"</p>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-teal-50">
            <div class="bg-teal-600 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center text-white">
                    <span class="text-teal-100 text-sm">Dibuat pada: {{ $booking->created_at->translatedFormat('d M Y, H:i') }}</span>
                    <div class="mt-2 sm:mt-0 uppercase tracking-widest font-bold text-xs bg-white/20 px-3 py-1 rounded">
                        {{ $booking->layanan->nama ?? 'Penitipan' }}
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="space-y-6">
                        <section>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Pemilik</h3>
                            <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 mr-4">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $booking->nama_pemilik }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->email }}</p>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Jadwal Penitipan</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 bg-teal-50 rounded-xl border border-teal-100">
                                    <p class="text-[10px] text-teal-600 font-bold uppercase">Check-In</p>
                                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="p-3 bg-orange-50 rounded-xl border border-orange-100">
                                    <p class="text-[10px] text-orange-600 font-bold uppercase">Check-Out</p>
                                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <section>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Anabul</h3>
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 relative overflow-hidden">
                            <i class="fas fa-paw absolute -right-4 -bottom-4 text-gray-100 text-8xl"></i>
                            <div class="relative z-10">
                                <div class="flex justify-between mb-4">
                                    <span class="text-gray-500 text-sm">Nama Hewan</span>
                                    <span class="font-bold text-gray-900">{{ $booking->nama_hewan }}</span>
                                </div>
                                <div class="flex justify-between mb-4">
                                    <span class="text-gray-500 text-sm">Jenis</span>
                                    <span class="px-2 py-0.5 bg-teal-100 text-teal-700 rounded text-xs font-bold">{{ $booking->jenis_hewan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 text-sm">Ukuran</span>
                                    <span class="font-medium text-gray-800">{{ $booking->ukuran_hewan }}</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="mb-10">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Rincian Pembayaran</h3>
                    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-4">
                            <span class="text-gray-400 text-sm">Tarif Harian ({{ $booking->jenis_hewan }})</span>
                            <span class="font-bold">Rp {{ number_format($hargaPerHari, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-400 text-sm">Durasi x {{ $durasi }} Hari</span>
                            <span class="text-2xl font-black text-teal-400">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between bg-white/10 p-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2 text-teal-300"></i>
                                <span class="text-xs">Status Pembayaran</span>
                            </div>
                            <span class="text-xs font-bold uppercase {{ $booking->dp_dibayar === 'Ya' ? 'text-green-400' : 'text-yellow-400' }}">
                                {{ $booking->dp_dibayar === 'Ya' ? 'Sudah Terverifikasi' : 'Menunggu Pembayaran' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 pt-6 border-t border-gray-100">
                    
                    @if(!$booking->dp_paid && !$booking->is_cancelled && !$booking->is_completed)
                    <button type="button" onclick="simulasiBayar('{{ $booking->id }}', '{{ $booking->kode_booking }}')"
                        class="flex-1 min-w-[200px] inline-flex items-center justify-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-amber-200 hover:-translate-y-1">
                        <i class="fas fa-qrcode mr-2"></i> Konfirmasi Bayar DP
                    </button>
                    @endif

                    @if($booking->canExtend() && $booking->status !== 'perpanjangan')
                    <a href="{{ route('user.booking.extend.form', $booking->id) }}" 
                       class="flex-1 min-w-[200px] inline-flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-purple-200 hover:-translate-y-1">
                        <i class="fas fa-calendar-plus mr-2"></i> Perpanjang Penitipan
                    </a>
                    @endif

                    <a href="https://wa.me/{{ $booking->nomor_wa ?? '628123456789' }}" target="_blank" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-green-200 hover:-translate-y-1">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>

                    <a href="{{ route('user.booking.pdf', $booking->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all">
                        <i class="fas fa-file-pdf mr-2"></i> PDF
                    </a>

                    @if($booking->canCancel() && $booking->status !== 'pembatalan')
                    <button type="button" onclick="handlePembatalan('{{ $booking->id }}', '{{ $booking->kode_booking }}')"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-red-500 hover:text-red-700 font-semibold transition-all italic text-sm underline decoration-dotted">
                        Batalkan Pesanan
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
        <p class="text-center mt-8 text-gray-400 text-xs italic">
            &copy; 2026 LARAPetHouse - Professional Pet Care Services
        </p>
    </div>

    <script>
        // Fungsi Simulasi Pembayaran
        function simulasiBayar(id, kode) {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda sudah melakukan transfer/scan QRIS untuk booking " + kode + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                confirmButtonText: 'Ya, Sudah Bayar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memverifikasi...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                    window.location.href = "{{ route('user.booking.bayar_simulasi', ':id') }}".replace(':id', id);
                }
            })
        }

        // Fungsi Pembatalan dengan Input Alasan
        function handlePembatalan(id, kode) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: "Berikan alasan pembatalan (minimal 10 karakter) untuk booking " + kode + ":",
                input: 'textarea',
                inputPlaceholder: 'Contoh: Rencana perjalanan saya berubah...',
                inputAttributes: { 'aria-label': 'Masukkan alasan pembatalan' },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ajukan Pembatalan',
                cancelButtonText: 'Tutup',
                preConfirm: (value) => {
                    if (!value || value.length < 10) {
                        Swal.showValidationMessage('Mohon berikan alasan yang jelas (min. 10 karakter)!');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = "{{ route('user.booking.cancel', ':id') }}".replace(':id', id);
                    form.method = 'POST';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="alasan_cancel" value="${result.value}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>