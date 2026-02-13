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
        if (empty($this->foto)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->username) . '&background=0D9488&color=fff';
        }

        $folder = match ($this->role) {
            'petugas' => 'foto_petugas',
            'dokter'  => 'foto_dokter',
            default   => 'foto_user',
        };

        $path = $folder . '/' . $this->foto;

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->username) . '&background=0D9488&color=fff';
    }
}
