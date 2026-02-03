<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('master_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan', 100);
            $table->text('deskripsi')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('warna', 20)->nullable();
            $table->integer('urutan')->default(0);
            $table->enum('aktif', ['ya', 'tidak'])->default('ya');
            $table->timestamps();
        });

        // Insert data default
        DB::table('master_kegiatan')->insert([
            [
                'nama_kegiatan' => 'Makan',
                'deskripsi' => 'Pemberian makanan',
                'icon' => 'utensils',
                'warna' => 'success',
                'urutan' => 1,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Minum',
                'deskripsi' => 'Pemberian minum',
                'icon' => 'glass-water',
                'warna' => 'primary',
                'urutan' => 2,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Jalan-jalan',
                'deskripsi' => 'Jalan-jalan di area sekitar',
                'icon' => 'person-walking',
                'warna' => 'warning',
                'urutan' => 3,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Pemberian Obat',
                'deskripsi' => 'Pemberian obat/vitamin',
                'icon' => 'pills',
                'warna' => 'danger',
                'urutan' => 4,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Grooming',
                'deskripsi' => 'Perawatan tubuh (mandi, sikat bulu)',
                'icon' => 'spray-can-sparkles',
                'warna' => 'info',
                'urutan' => 5,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Bermain',
                'deskripsi' => 'Waktu bermain dengan mainan',
                'icon' => 'dice',
                'warna' => 'secondary',
                'urutan' => 6,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Istirahat',
                'deskripsi' => 'Waktu tidur/istirahat',
                'icon' => 'bed',
                'warna' => 'dark',
                'urutan' => 7,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Buang Air',
                'deskripsi' => 'Catatan buang air',
                'icon' => 'toilet',
                'warna' => 'warning',
                'urutan' => 8,
                'aktif' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('master_kegiatan');
    }
};