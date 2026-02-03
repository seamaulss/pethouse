<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom baru dulu
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id')->nullable()->after('petugas_id');
            $table->time('waktu')->after('tanggal');
            $table->dropColumn('jam_kegiatan'); // Hapus jam_kegiatan karena akan diganti waktu
            $table->text('keterangan')->nullable()->after('catatan');
            $table->string('jumlah', 50)->nullable()->after('keterangan');
            $table->string('satuan', 20)->nullable()->after('jumlah');
            $table->enum('status_pelaksanaan', ['selesai', 'terlewat', 'ditunda'])->default('selesai')->after('satuan');
            
            // Foreign key ke master_kegiatan
            $table->foreign('kegiatan_id')->references('id')->on('master_kegiatan')->onDelete('set null');
        });

        // Ubah constraint agar bisa menerima null untuk kegiatan_id
        Schema::table('daily_logs', function (Blueprint $table) {
            $table->foreign('kegiatan_id')->references('id')->on('master_kegiatan')->onDelete('set null')->change();
        });
    }

    public function down()
    {
        Schema::table('daily_logs', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['kegiatan_id']);
            
            // Hapus kolom baru
            $table->dropColumn([
                'kegiatan_id',
                'waktu',
                'keterangan',
                'jumlah',
                'satuan',
                'status_pelaksanaan'
            ]);
            
            // Tambah kembali kolom lama
            $table->time('jam_kegiatan')->after('tanggal');
        });
    }
};