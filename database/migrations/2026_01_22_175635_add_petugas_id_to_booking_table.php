<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Tambahkan kolom petugas_id sebagai foreign key ke users
            $table->foreignId('petugas_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            
            // Optional: tambahkan index untuk performa query
            $table->index('petugas_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
            $table->dropColumn('petugas_id');
        });
    }
};