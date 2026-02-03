<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKegiatan extends Model
{
    use HasFactory;

    protected $table = 'master_kegiatan';
    
    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'icon',
        'warna',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'string'
    ];

    // Relasi ke daily_logs
    public function logs()
    {
        return $this->hasMany(DailyLog::class, 'kegiatan_id');
    }

    // Scope untuk yang aktif saja
    public function scopeAktif($query)
    {
        return $query->where('aktif', 'ya');
    }

    // Scope untuk urutan
    public function scopeUrut($query)
    {
        return $query->orderBy('urutan');
    }
}