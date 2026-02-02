<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisHewanSeeder::class,
            LayananSeeder::class,
            // Tambahkan seeder lain sesuai kebutuhan
        ]);
    }
}