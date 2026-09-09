<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kapasitas',
        'deskripsi',
        'informasi_tambahan',
        'lokasi',
        'foto',
        'fasilitas',
        'status_aktif'
    ];

    protected $casts = [
        'foto' => 'array',
        'fasilitas' => 'array',
        'status_aktif' => 'boolean'
    ];

    /**
     * Accessor untuk mendapatkan URL foto lengkap
     */
    public function getFotoUrlsAttribute()
    {
        if (!$this->foto) {
            return [];
        }
        
        return array_map(function($path) {
            return asset('storage/' . $path);
        }, $this->foto);
    }

    /**
     * Relasi ke booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}