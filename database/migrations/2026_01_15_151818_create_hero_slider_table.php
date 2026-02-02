<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slider', function (Blueprint $table) {
            $table->id();
            $table->string('gambar');
            $table->string('judul')->nullable();
            $table->string('subjudul')->nullable();
            $table->string('tombol_text', 50)->default('Booking Sekarang');
            $table->string('tombol_link')->default('booking.php');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slider');
    }
};