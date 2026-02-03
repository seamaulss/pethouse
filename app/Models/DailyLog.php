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
        'kegiatan_id',
        'tanggal',
        'waktu',
        'keterangan',
        'jumlah',
        'satuan',
        'status_pelaksanaan',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime:H:i',
        'status_pelaksanaan' => 'string'
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

    // Relasi dengan master kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kegiatan_id');
    }

    // Accessor untuk format jam yang lebih mudah
    public function getWaktuFormattedAttribute()
    {
        return $this->waktu ? date('H:i', strtotime($this->waktu)) : null;
    }

    // Scope untuk hari tertentu
    public function scopePadaTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
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

    // Scope untuk urut waktu
    public function scopeUrutWaktu($query)
    {
        return $query->orderBy('waktu', 'desc');
    }
}