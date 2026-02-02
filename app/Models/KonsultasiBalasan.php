<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonsultasiBalasan extends Model
{
    use HasFactory;

    protected $table = 'konsultasi_balasan';

    protected $fillable = [
        'konsultasi_id',
        'pengirim',
        'isi',
        'dibaca_user',
        'dibaca_dokter'
    ];

    protected $casts = [
        'dibaca_user' => 'boolean',
        'dibaca_dokter' => 'boolean',
    ];

    public function konsultasi(): BelongsTo
    {
        return $this->belongsTo(Konsultasi::class);
    }
}