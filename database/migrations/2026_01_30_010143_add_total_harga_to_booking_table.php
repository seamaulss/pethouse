<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Booking;
use App\Models\LayananHarga;
use App\Models\JenisHewan;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->nullable()->after('status');
        });

        // Update data yang sudah ada dengan total_harga
        $this->updateExistingBookings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn('total_harga');
        });
    }

    /**
     * Update existing bookings with total_harga.
     */
    private function updateExistingBookings(): void
    {
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            // Hitung durasi
            $tanggal_masuk = Carbon::parse($booking->tanggal_masuk);
            $tanggal_keluar = Carbon::parse($booking->tanggal_keluar);
            $durasi = $tanggal_masuk->diffInDays($tanggal_keluar);
            $durasi = max(1, $durasi);

            // Dapatkan harga per hari
            $harga_per_hari = 0;
            $jenisHewan = JenisHewan::where('nama', $booking->jenis_hewan)->first();
            
            if ($jenisHewan) {
                $layananHarga = LayananHarga::where('layanan_id', $booking->layanan_id)
                    ->where('jenis_hewan_id', $jenisHewan->id)
                    ->first();
                
                if ($layananHarga) {
                    $harga_per_hari = $layananHarga->harga_per_hari;
                }
            }

            $booking->total_harga = $durasi * $harga_per_hari;
            $booking->save();
        }
    }
};