<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking LARAPetHouse</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #0d9488;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0d9488;
            text-transform: uppercase;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table th {
            background-color: #0d9488;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #0d9488;
        }
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Warna Status */
        .status-selesai { background: #dcfce7; color: #166534; }
        .status-in_progress { background: #dcf0fa; color: #1e40af; }
        .status-pembatalan { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef3c7; color: #92400e; }

        .footer-total {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0fdfa;
            border: 1px solid #0d9488;
            text-align: right;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LARAPetHouse</h1>
        <p>Layanan Penitipan & Perawatan Hewan Profesional</p>
        <p>Banjarnegara, Jawa Tengah | Email: admin@LARAPetHouse.com | Telp: 0812-3456-7890</p>
    </div>

    <div class="info">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; padding: 0;">
                    <strong>LAPORAN TRANSAKSI BOOKING</strong><br>
                    Tanggal Filter: {{ $date ? \Carbon\Carbon::parse($date)->translatedFormat('d F Y') : 'Semua Tanggal' }}
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    Dicetak Oleh: Admin LARAPetHouse<br>
                    Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="20%">Pemilik & Hewan</th>
                <th width="12%">Layanan</th>
                <th width="12%">Tgl Masuk</th>
                <th width="12%">Tgl Keluar</th>
                <th width="10%">Status</th>
                <th width="17%" class="text-right">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td><strong>#{{ $booking->kode_booking }}</strong></td>
                <td>
                    {{ $booking->nama_pemilik }}<br>
                    <small style="color: #666;">({{ $booking->nama_hewan }} - {{ $booking->jenis_hewan }})</small>
                </td>
                <td>{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="badge status-{{ $booking->status }}">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </td>
                <td class="text-right">Rp {{ number_format((float)$booking->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-total">
        <span style="font-size: 13px;">Total Pendapatan (Status Selesai): </span>
        <strong style="font-size: 18px; color: #0d9488;">
            Rp {{ number_format((float)$total_pendapatan, 0, ',', '.') }}
        </strong>
    </div>

    <div style="margin-top: 30px; font-size: 10px; color: #999; text-align: center;">
        Laporan ini dibuat otomatis oleh sistem manajemen LARAPetHouse.
    </div>
</body>
</html>