<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kapasitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan');
            $table->string('jenis_hewan', 50);
            $table->string('ukuran_hewan', 20);
            $table->integer('max_kapasitas')->default(10);
            $table->timestamps();
            
            $table->unique(['layanan_id', 'jenis_hewan', 'ukuran_hewan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kapasitas');
    }
};