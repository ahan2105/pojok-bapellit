<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $absensi_sesi_id
 * @property int $user_id
 * @property string|null $status_kehadiran
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $waktu_absen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read AbsensiSesi $sesi
 * @property-read User $user
 */
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

    // ============================================================
    // RELASI
    // ============================================================

    /**
     * Relasi ke sesi absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sesi()
    {
        return $this->belongsTo(AbsensiSesi::class, 'absensi_sesi_id');
    }

    /**
     * Relasi ke user (peserta)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}