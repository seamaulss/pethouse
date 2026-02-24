<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapasitas extends Model
{
    use HasFactory;

    protected $table = 'kapasitas';

    protected $fillable = [
        'layanan_id',
        'jenis_hewan',
        'ukuran_hewan',
        'max_kapasitas',
    ];

    // Relasi ke tabel Layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
}