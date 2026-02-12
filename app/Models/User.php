<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // ← WAJIB DITAMBAHKAN

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'nomor_wa',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor untuk URL foto profil.
     * Mendukung role 'petugas' (folder foto_petugas) dan 'user' (folder foto_user).
     */
    public function getFotoUrlAttribute()
    {
        // Jika tidak ada foto, kembalikan avatar default
        if (empty($this->foto)) {
            return asset('assets/img/default-avatar.png');
        }

        // Tentukan folder berdasarkan role
        $folder = ($this->role === 'petugas') ? 'foto_petugas' : 'foto_user';
        $path = $folder . '/' . $this->foto;

        // Cek keberadaan file di storage/public
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        // Fallback jika file tidak ditemukan
        return asset('assets/img/default-avatar.png');
    }
}