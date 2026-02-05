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
        'created_at' => 'datetime',
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

    // Method untuk mengirim notifikasi ke user
    public static function createForUser($userId, $title, $message, $bookingId = null, $type = 'info')
    {
        return self::create([
            'user_id' => $userId,
            'role_target' => 'user',
            'title' => $title,
            'message' => $message,
            'booking_id' => $bookingId,
            'type' => $type,
            'is_read' => false,
        ]);
    }

    // Method untuk mengirim notifikasi ke admin
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

    // Scope untuk notifikasi user yang belum dibaca
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->where('is_read', false);
    }

    // Scope untuk notifikasi berdasarkan role target
    public function scopeForRole($query, $role)
    {
        return $query->where('role_target', $role);
    }

    // Scope untuk notifikasi terbaru
    public function scopeLatestNotifications($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    // Method helper untuk mendapatkan status text
    public function getStatusTextAttribute()
    {
        switch ($this->type) {
            case 'success':
                return 'Berhasil';
            case 'warning':
                return 'Peringatan';
            case 'error':
                return 'Error';
            default:
                return 'Informasi';
        }
    }
}