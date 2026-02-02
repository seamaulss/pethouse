<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->foreignId('jenis_hewan_id')->constrained('jenis_hewan')->onDelete('cascade');
            $table->decimal('harga_per_hari', 10, 2);
            $table->timestamps();
            
            $table->unique(['layanan_id', 'jenis_hewan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_harga');
    }
};