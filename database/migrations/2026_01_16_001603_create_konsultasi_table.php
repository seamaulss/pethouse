<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('dokter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_konsultasi', 20)->unique();
            $table->string('nama_pemilik', 100)->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->string('jenis_hewan', 50)->nullable();
            $table->string('topik', 100)->nullable();
            $table->date('tanggal_janji')->nullable();
            $table->time('jam_janji')->nullable();
            $table->text('catatan')->nullable();
            $table->text('balasan_dokter')->nullable();
            $table->enum('status', ['pending', 'diterima', 'selesai'])->default('pending');
            $table->timestamps();
            
            $table->index(['status', 'tanggal_janji']);
            $table->index('user_id');
            $table->index('dokter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};