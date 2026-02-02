<?php
// app/Models/JenisHewan.php

// app/Models/JenisHewan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisHewan extends Model
{
    use HasFactory;

    protected $table = 'jenis_hewan';
    
    protected $fillable = [
        'nama',
        'aktif'
    ];

    protected $casts = [
        // 'aktif' => 'boolean', // karena di database enum('ya','tidak') maka kita tidak perlu casting ke boolean
    ];

    // Hapus SoftDeletes

    // Scope untuk data aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', 'ya');
    }

    // Relasi ke booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'jenis_hewan_id');
    }

    // Relasi ke layanan_harga
    public function layananHarga()
    {
        return $this->hasMany(LayananHarga::class, 'jenis_hewan_id');
    }
}