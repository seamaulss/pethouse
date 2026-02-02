<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananHarga extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'layanan_harga';
    
    protected $fillable = ['layanan_id', 'jenis_hewan_id', 'harga_per_hari'];

    // Relationship ke layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    // Relationship ke jenis hewan
    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'jenis_hewan_id');
    }
}