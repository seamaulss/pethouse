<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email' => 'admin@pethouse.com',
        ]);

        // Petugas
        User::create([
            'username' => 'petugas1',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'nomor_wa' => '6289506700208',
        ]);

        // Dokter
        User::create([
            'username' => 'dokter',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'email' => 'dokter@pethouse.com',
        ]);

        // Users
        User::create([
            'username' => 'arkan',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email' => 'arkanmaulidhananurfalah@gmail.com',
        ]);

        User::create([
            'username' => 'Adam',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email' => 'adam@gmail.com',
        ]);
    }
}