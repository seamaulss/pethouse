<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik', 100);
            $table->string('nama_hewan', 50)->nullable();
            $table->string('jenis_hewan', 30)->nullable();
            $table->text('isi_testimoni');
            $table->string('foto_hewan')->nullable();
            $table->tinyInteger('rating')->default(5)->check('rating >= 1 AND rating <= 5');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};