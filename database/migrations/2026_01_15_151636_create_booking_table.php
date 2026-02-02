<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('jenis_hewan_id')->nullable()->constrained('jenis_hewan')->onDelete('set null');
            $table->foreignId('layanan_id')->constrained('layanan');
            $table->string('kode_booking', 20)->unique();
            $table->string('nama_pemilik', 100);
            $table->string('email', 100)->nullable();
            $table->string('nomor_wa', 20)->nullable();
            $table->string('nama_hewan', 100);
            $table->enum('jenis_hewan', ['Kucing', 'Anjing']);
            $table->string('ukuran_hewan', 20)->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar');
            $table->enum('dp_dibayar', ['Ya', 'Tidak'])->default('Tidak');
            $table->string('bukti_dp')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'diterima', 'selesai', 'in_progress'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};