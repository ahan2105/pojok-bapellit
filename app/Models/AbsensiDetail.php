<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $absensi_sesi_id
 * @property int $user_id
 * @property string|null $status_kehadiran
 * @property string|null $keterangan
 * @property Carbon|null $waktu_absen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
     * @return BelongsTo
     */
    public function sesi()
    {
        return $this->belongsTo(AbsensiSesi::class, 'absensi_sesi_id');
    }

    /**
     * Relasi ke user (peserta)
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
