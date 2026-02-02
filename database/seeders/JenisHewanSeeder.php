<?php

namespace Database\Seeders;

use App\Models\JenisHewan;
use Illuminate\Database\Seeder;

class JenisHewanSeeder extends Seeder
{
    public function run(): void
    {
        JenisHewan::create([
            'nama' => 'Kucing',
            'aktif' => 'ya',
        ]);

        JenisHewan::create([
            'nama' => 'Anjing',
            'aktif' => 'ya',
        ]);
    }
}