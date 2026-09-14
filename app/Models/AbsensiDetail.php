<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    use HasFactory;

    protected $table = 'absensi_detail';

    protected $fillable = [
        'absensi_sesi_id',
        'user_id',
        'status_kehadiran',
        'keterangan',
        'waktu_absen',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    // Relasi ke sesi
    public function sesi()
    {
        return $this->belongsTo(AbsensiSesi::class, 'absensi_sesi_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}