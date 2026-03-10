<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $booking->kode_booking }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
        }

        .header {
            border-bottom: 3px solid #0d9488;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0d9488;
        }

        .invoice-label {
            float: right;
            text-align: right;
        }

        .invoice-label h1 {
            margin: 0;
            color: #0d9488;
            font-size: 24px;
        }

        .section-title {
            background: #f0fdfa;
            padding: 8px 12px;
            font-weight: bold;
            color: #0d9488;
            margin: 20px 0 10px 0;
            border-left: 5px solid #0d9488;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 13px;
        }

        .item-table th {
            background: #f9fafb;
            padding: 12px 10px;
            text-align: left;
            border-bottom: 2px solid #edf2f7;
            font-size: 12px;
            color: #4a5568;
        }

        .item-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }

        .total-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }

        .total-box {
            background: #0d9488;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: right;
        }

        .total-amount {
            font-size: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #718096;
        }

        .qr-code {
            margin-top: 15px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #def7ec;
            color: #03543f;
        }

        .badge-warning {
            background-color: #fdf6b2;
            color: #723b13;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="invoice-label">
                <h1>INVOICE</h1>
                <p style="margin: 5px 0;">#{{ $booking->kode_booking }}</p>
                <p style="margin: 0; font-size: 12px;">Tgl: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="logo">LARAPetHouse</div>
            <p style="margin: 0; font-size: 12px; color: #666;">Solusi Penitipan Hewan Profesional</p>
        </div>

        <div class="section-title">INFORMASI BOOKING</div>
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong style="color: #666;">PEMILIK:</strong><br>
                    <span style="font-size: 15px; font-weight: bold;">{{ $booking->nama_pemilik }}</span><br>
                    WA: {{ $booking->nomor_wa }}<br>
                    Email: {{ $booking->email }}
                </td>
                <td width="50%">
                    <strong style="color: #666;">HEWAN (ANABUL):</strong><br>
                    <span style="font-size: 15px; font-weight: bold;">{{ $booking->nama_hewan }}</span><br>
                    Jenis: {{ $booking->jenis_hewan }}<br>
                    Ukuran: {{ $booking->ukuran_hewan }}
                </td>
            </tr>
        </table>

        <div class="section-title">RINCIAN LAYANAN</div>
        <table class="item-table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Durasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Harga Per Hari:</strong></td>
                    <td>Rp {{ number_format($totalBiaya / $durasi, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Durasi:</strong></td>
                    <td>{{ $durasi }} Hari</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-box">
                <div style="font-size: 12px; opacity: 0.8; margin-bottom: 5px;">ESTIMASI TOTAL BIAYA</div>
                <div class="total-amount">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
            </div>
            <p style="font-size: 11px; color: #718096; text-align: right; margin-top: 10px;">
                Status Pembayaran DP:
                <span class="badge {{ $booking->dp_dibayar == 'Ya' ? 'badge-success' : 'badge-warning' }}">
                    {{ $booking->dp_dibayar == 'Ya' ? 'Lunas' : 'Belum Dibayar' }}
                </span>
            </p>
        </div>

        <div style="clear: both;"></div>

        @if($booking->catatan)
        <div style="margin-top: 20px;">
            <strong style="font-size: 12px; color: #666;">Catatan:</strong>
            <p style="font-size: 12px; background: #f9fafb; padding: 10px; border-radius: 5px; border: 1px solid #edf2f7;">
                {{ $booking->catatan }}
            </p>
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih telah mempercayakan anabul Anda kepada <strong>LARAPetHouse</strong>.</p>
            <p>Harap tunjukkan QR Code ini kepada petugas saat melakukan Check-In.</p>
            <div class="qr-code">
                {{-- Menggunakan API QR gratis --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $booking->kode_booking }}" width="100">
                <div style="margin-top: 5px; font-weight: bold; color: #0d9488;">{{ $booking->kode_booking }}</div>
            </div>
        </div>
    </div>
</body>

</html>