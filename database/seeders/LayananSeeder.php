<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\LayananHarga;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // Pet Hotel
        $petHotel = Layanan::create([
            'nama_layanan' => 'Pet Hotel',
            'gambar' => 'layanan_69522dbe478dc.jpg',
            'deskripsi' => 'Berikan pengalaman menginap yang menyenangkan bagi hewan peliharaan Anda saat Anda sedang bepergian...',
        ]);

        // Harga untuk jenis hewan
        LayananHarga::create([
            'layanan_id' => $petHotel->id,
            'jenis_hewan_id' => 1, // Kucing
            'harga_per_hari' => 100000.00,
        ]);

        LayananHarga::create([
            'layanan_id' => $petHotel->id,
            'jenis_hewan_id' => 2, // Anjing
            'harga_per_hari' => 150000.00,
        ]);
    }
}