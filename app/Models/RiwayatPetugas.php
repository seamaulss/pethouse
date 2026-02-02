<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPetugas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_petugas';

    protected $fillable = [
        'booking_id',
        'petugas_id',
        'status_akhir',
    ];

    // Relasi dengan booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi dengan petugas (user)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}