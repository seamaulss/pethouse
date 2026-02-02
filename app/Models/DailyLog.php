<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'petugas_id',
        'tanggal',
        'makan_pagi',
        'jam_makan_pagi',
        'makan_siang',
        'jam_makan_siang',
        'makan_malam',
        'jam_makan_malam',
        'minum',
        'jam_minum',
        'jalan_jalan',
        'jam_jalan_jalan',
        'buang_air',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'makan_pagi' => 'boolean',
        'makan_siang' => 'boolean',
        'makan_malam' => 'boolean',
        'minum' => 'boolean',
        'jalan_jalan' => 'boolean',
        'jam_makan_pagi' => 'datetime:H:i',
        'jam_makan_siang' => 'datetime:H:i',
        'jam_makan_malam' => 'datetime:H:i',
        'jam_minum' => 'datetime:H:i',
        'jam_jalan_jalan' => 'datetime:H:i',
    ];

    // Relasi dengan booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi dengan petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // Accessor untuk format jam yang lebih mudah
    public function getJamMakanPagiFormattedAttribute()
    {
        return $this->jam_makan_pagi ? date('H:i', strtotime($this->jam_makan_pagi)) : null;
    }

    public function getJamMakanSiangFormattedAttribute()
    {
        return $this->jam_makan_siang ? date('H:i', strtotime($this->jam_makan_siang)) : null;
    }

    public function getJamMakanMalamFormattedAttribute()
    {
        return $this->jam_makan_malam ? date('H:i', strtotime($this->jam_makan_malam)) : null;
    }

    public function getJamMinumFormattedAttribute()
    {
        return $this->jam_minum ? date('H:i', strtotime($this->jam_minum)) : null;
    }

    public function getJamJalanJalanFormattedAttribute()
    {
        return $this->jam_jalan_jalan ? date('H:i', strtotime($this->jam_jalan_jalan)) : null;
    }

    // Scope untuk hari ini
    public function scopeHariIni($query)
    {
        return $query->where('tanggal', today()->toDateString());
    }

    // Scope untuk petugas tertentu
    public function scopeByPetugas($query, $petugasId)
    {
        return $query->where('petugas_id', $petugasId);
    }

    // Scope untuk booking tertentu
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }
}