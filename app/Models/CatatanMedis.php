<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanMedis extends Model
{
    use HasFactory;

    protected $table = 'catatan_medis';
    
    protected $fillable = [
        'booking_id',
        'konsultasi_id',
        'user_id',
        'dokter_id',
        'nama_hewan',
        'jenis_hewan',
        'diagnosis',
        'resep',
        'vaksin',
        'tanggal',
        'catatan_lain',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
}