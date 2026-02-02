<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->text('alasan_perpanjangan')->nullable()->after('catatan');
            $table->text('alasan_cancel')->nullable()->after('alasan_perpanjangan');
            $table->date('tanggal_perpanjangan')->nullable()->after('alasan_cancel');
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn([
                'alasan_perpanjangan',
                'alasan_cancel',
                'tanggal_perpanjangan'
            ]);
        });
    }
};