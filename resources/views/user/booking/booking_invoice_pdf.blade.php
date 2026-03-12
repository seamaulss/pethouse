<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; font-size: 14px; }
        .header { border-bottom: 2px solid #0d9488; padding-bottom: 10px; margin-bottom: 20px; }
        .section-title { background: #f0fdfa; padding: 5px 10px; font-weight: bold; color: #0d9488; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; }
        .item-table th { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        .item-table td { padding: 8px; border-bottom: 1px solid #eee; }
        
        /* Box Pembayaran */
        .payment-box { margin-top: 20px; }
        .qris-area { float: left; width: 45%; border: 1px solid #eee; padding: 10px; text-align: center; border-radius: 10px; }
        .summary-area { float: right; width: 45%; text-align: right; }
        .total-price { font-size: 20px; font-weight: bold; color: #0d9488; }
        
        .footer { clear: both; margin-top: 40px; text-align: center; border-top: 1px dashed #ccc; padding-top: 20px; }
        .qr-checkin { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #0d9488; margin: 0;">LARAPetHouse Invoice</h2>
        <p style="margin: 0; font-size: 12px;">Kode Booking: {{ $booking->kode_booking }}</p>
    </div>

    <table>
        <tr>
            <td>
                <strong>Pemilik:</strong><br>
                {{ $booking->nama_pemilik }}<br>
                {{ $booking->nomor_wa }}
            </td>
            <td style="text-align: right;">
                <strong>Anabul:</strong><br>
                {{ $booking->nama_hewan }} ({{ $booking->jenis_hewan }})<br>
                {{ $booking->ukuran_hewan }}
            </td>
        </tr>
    </table>

    <div class="section-title">RINCIAN LAYANAN</div>
    <table class="item-table">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th style="text-align: right;">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $booking->layanan->nama_layanan }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}</td>
                <td style="text-align: right;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="payment-box">
        @if($booking->dp_dibayar == 'Tidak')
        <div class="qris-area">
            <p style="font-size: 11px; font-weight: bold; margin: 0 0 5px 0;">SCAN QRIS PEMBAYARAN</p>
            {{-- Pastikan file ini ada di public/images/qris.png --}}
            <img src="{{ public_path('images/qris.png') }}" width="120">
            <p style="font-size: 9px; color: #666; margin-top: 5px;">Silahkan selesaikan pembayaran DP</p>
        </div>
        @else
        <div class="qris-area" style="background: #f0fdfa;">
            <p style="color: #0d9488; font-weight: bold; margin-top: 30px;">PEMBAYARAN DP LUNAS</p>
            <p style="font-size: 10px;">Terima kasih telah membayar secara online.</p>
        </div>
        @endif

        <div class="summary-area">
            <p style="font-size: 12px; margin: 0;">TOTAL TAGIHAN</p>
            <p class="total-price">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</p>
            <p style="font-size: 11px;">Status: <strong>{{ $booking->dp_dibayar == 'Ya' ? 'Lunas/Sudah DP' : 'Menunggu DP' }}</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <p style="font-size: 12px; font-weight: bold;">TUNJUKKAN KODE INI SAAT TIBA DI LOKASI (CHECK-IN)</p>
        <div class="qr-checkin">
            {{-- Ukuran diperbesar ke 150 agar mudah di-scan petugas --}}
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($booking->kode_booking) }}" width="100">
            <p style="letter-spacing: 5px; font-weight: bold; color: #0d9488; margin-top: 5px;">{{ $booking->kode_booking }}</p>
        </div>
    </div>
</body>
</html>