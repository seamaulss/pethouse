<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Suppport\Facades\log;

class Konsultasi extends Model
{
    protected $table = 'konsultasi';

    protected $fillable = [
        'user_id',
        'dokter_id',
        'kode_konsultasi',
        'nama_pemilik',
        'no_wa',
        'jenis_hewan',
        'topik',
        'tanggal_janji',
        'jam_janji',
        'catatan',
        'balasan_dokter',
        'status'
    ];

    protected $casts = [
        'tanggal_janji' => 'date',
        'jam_janji' => 'datetime:H:i',
    ];

    // Relationship dengan user (pemilik)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan dokter
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    // Relationship dengan balasan
    public function balasan(): HasMany
    {
        return $this->hasMany(KonsultasiBalasan::class, 'konsultasi_id');
    }

    // Relationship dengan catatan medis
    public function catatanMedis(): HasMany
    {
        return $this->hasMany(CatatanMedis::class, 'konsultasi_id');
    }

    // Accessor untuk format nomor WA
    protected function waLink(): Attribute
    {
        return Attribute::make(
            get: function () {
                $no_wa = preg_replace('/[^0-9]/', '', $this->no_wa);
                $no_wa = ltrim($no_wa, '0');
                return '62' . $no_wa;
            }
        );
    }

    // Accessor untuk status label
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'pending' => 'Menunggu',
                    'diterima' => 'Diterima',
                    'selesai' => 'Selesai',
                    default => ucfirst($this->status)
                };
            }
        );
    }

    // Accessor untuk status class
    protected function statusClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    'pending' => 'bg-amber-100 text-amber-800',
                    'diterima' => 'bg-teal-100 text-teal-800',
                    'selesai' => 'bg-pink-100 text-pink-800',
                    default => 'bg-gray-100 text-gray-800'
                };
            }
        );
    }

    // Method untuk generate pesan WA
    public function generateWaMessage(): string
    {
        $statusText = match($this->status) {
            'pending' => 'MENUNGGU',
            'diterima' => 'DITERIMA',
            'selesai' => 'SELESAI',
            default => strtoupper($this->status)
        };

        $pesan = "Halo {$this->nama_pemilik} 👋\n\n";
        $pesan .= "Status konsultasi Anda di PetHouse telah diupdate menjadi:\n\n";
        $pesan .= "*{$statusText}* 🐾\n\n";
        $pesan .= "📋 Detail:\n";
        $pesan .= "• Topik: {$this->topik}\n";
        $pesan .= "• Hewan: {$this->jenis_hewan}\n";
        $pesan .= "📅 Tanggal: " . ($this->tanggal_janji ? $this->tanggal_janji->format('d F Y') : 'Belum diatur') . "\n";
        
        if ($this->jam_janji) {
            $pesan .= "⏰ Jam: " . date('H:i', strtotime($this->jam_janji)) . " WIB\n\n";
        }
        
        $pesan .= "Terima kasih atas kepercayaannya ❤️\nPetHouse";

        return urlencode($pesan);
    }
}