<?php
// app/Models/Testimoni.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';
    
    protected $fillable = [
        'nama_pemilik',
        'nama_hewan',
        'jenis_hewan',
        'isi_testimoni',
        'foto_hewan',
        'rating',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}