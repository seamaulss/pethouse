<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log; // Perbaikan typo Support

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
        'tanggal_janji' => 'datetime',
        'jam_janji' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    protected function waLink(): Attribute
    {
        return Attribute::make(
            get: function () {
                $no_wa = preg_replace('/[^0-9]/', '', $this->no_wa);
                // Pastikan format diawali 62
                if (str_starts_with($no_wa, '0')) {
                    $no_wa = '62' . substr($no_wa, 1);
                }
                return $no_wa;
            }
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'pending' => 'Menunggu',
                'diterima' => 'Diterima',
                'selesai' => 'Selesai',
                default => ucfirst($this->status)
            }
        );
    }

    protected function statusClass(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                'diterima' => 'bg-blue-100 text-blue-800 border-blue-200',
                'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200'
            }
        );
    }

    public function generateWaMessage(): string
    {
        $statusText = strtoupper($this->statusLabel);
        $pesan = "Halo {$this->nama_pemilik} 👋\n\n";
        $pesan .= "Reservasi Anda di *LARAPetHouse* telah diperbarui menjadi: *{$statusText}*\n\n";
        $pesan .= "📋 Detail Kunjungan:\n";
        $pesan .= "• Kode: {$this->kode_konsultasi}\n";
        $pesan .= "• Hewan: {$this->jenis_hewan}\n";
        $pesan .= "📅 Tanggal: " . $this->tanggal_janji->format('d F Y') . "\n";
        $pesan .= "⏰ Jam: " . date('H:i', strtotime($this->jam_janji)) . " WIB\n\n";
        
        if ($this->status === 'diterima') {
            $pesan .= "Silakan datang tepat waktu ya. Sampai jumpa! 🐾";
        } elseif ($this->status === 'selesai') {
            $pesan .= "Hasil pemeriksaan sudah bisa dilihat di website LARAPetHouse. Terima kasih ❤️";
        }

        return urlencode($pesan);
    }
}