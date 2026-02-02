<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_target',
        'title',
        'message',
        'booking_id',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Method helper untuk membuat notifikasi
    public static function createForAdmin($title, $message, $bookingId = null, $type = 'info')
    {
        return self::create([
            'role_target' => 'admin', 
            'title' => $title,
            'message' => $message,
            'booking_id' => $bookingId,
            'type' => $type,
            'is_read' => false,
        ]);
    }

    // Scope untuk notifikasi admin yang belum dibaca
    public function scopeUnreadForAdmin($query)
    {
        return $query->where('role_target', 'admin')
                    ->where('is_read', false);
    }
}