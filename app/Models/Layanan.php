<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'layanan';

    protected $fillable = ['nama_layanan', 'gambar', 'deskripsi'];

    // Relationship ke harga per jenis
    public function hargas()
    {
        return $this->hasMany(LayananHarga::class, 'layanan_id');
    }

    // Alias untuk konsistensi (jika dibutuhkan)
    public function hargaPerJenis()
    {
        return $this->hasMany(LayananHarga::class, 'layanan_id');
    }

    // Relationship ke booking (jika ada)
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'layanan_id');
    }

    // Helper method untuk mendapatkan harga berdasarkan jenis hewan
    public function getHargaByJenis($jenisHewanId)
    {
        $harga = $this->hargas()->where('jenis_hewan_id', $jenisHewanId)->first();
        return $harga ? $harga->harga_per_hari : 0;
    }
}