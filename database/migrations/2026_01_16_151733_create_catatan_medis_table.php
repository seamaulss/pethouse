<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('booking')->onDelete('set null');
            $table->foreignId('konsultasi_id')->nullable()->constrained('konsultasi')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('dokter_id')->constrained('users');
            $table->string('nama_hewan', 100);
            $table->string('jenis_hewan', 50);
            $table->text('diagnosis')->nullable();
            $table->text('resep')->nullable();
            $table->string('vaksin', 100)->nullable();
            $table->date('tanggal');
            $table->text('catatan_lain')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_medis');
    }
};