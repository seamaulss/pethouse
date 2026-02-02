<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users');
            $table->date('tanggal');
            $table->boolean('makan_pagi')->default(false);
            $table->boolean('makan_siang')->default(false);
            $table->boolean('makan_malam')->default(false);
            $table->boolean('minum')->default(false);
            $table->boolean('jalan_jalan')->default(false);
            $table->enum('buang_air', ['belum', 'normal', 'diare', 'sembelit'])->default('belum');
            $table->text('catatan')->nullable();
            $table->time('jam_makan_pagi')->nullable();
            $table->time('jam_makan_siang')->nullable();
            $table->time('jam_makan_malam')->nullable();
            $table->time('jam_minum')->nullable();
            $table->time('jam_jalan_jalan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};