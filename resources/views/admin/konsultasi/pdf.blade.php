<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Antrean Konsultasi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d9488; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0d9488; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #0d9488; color: white; padding: 10px; text-align: left; text-transform: uppercase; }
        td { border: 1px solid #eee; padding: 8px; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; font-size: 9px; }
        .status { font-weight: bold; text-transform: uppercase; color: #0d9488; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Antrean Klinik LARAPetHouse</h2>
        <p>Data Kunjungan Pasien & Konsultasi Dokter</p>
        <span>Dicetak pada: {{ date('d/m/Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="20%">Pemilik</th>
                <th width="20%">Hewan</th>
                <th width="25%">Jadwal Janji</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="font-family: monospace;">#{{ $item->kode_konsultasi }}</td>
                <td>{{ $item->nama_pemilik }}</td>
                <td>
                    <strong>{{ $item->nama_hewan }}</strong><br>
                    <small>{{ $item->jenis_hewan }}</small>
                </td>
                <td>
                    {{ $item->tanggal_janji->format('d/m/Y') }}<br>
                    {{ date('H:i', strtotime($item->jam_janji)) }} WIB
                </td>
                <td class="status">{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Manajemen LARAPetHouse - Dokumen Sah Hasil Generate Sistem
    </div>
</body>
</html>