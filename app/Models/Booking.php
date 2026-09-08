<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aula_id',
        'tanggal_booking',
        'nama_penanggung_jawab',
        'keperluan',
        'jumlah_peserta',
        'sesi_waktu',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }
}