<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory;

    protected $table = 'hero_slider';
    
    protected $fillable = [
        'gambar',
        'judul',
        'subjudul',
        'tombol_text',
        'tombol_link',
        'urutan',
    ];
}