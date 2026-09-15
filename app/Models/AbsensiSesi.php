<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_sesi
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $lokasi
 * @property string|null $catatan
 * @property bool $is_default
 * @property bool $is_locked
 * @property string|null $token_qr
 * @property \Illuminate\Support\Carbon|null $token_generated_at
 * @property int $qr_lifetime_seconds
 * @property bool $qr_auto_refresh
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AbsensiSesi extends Model
{
    use HasFactory;

    protected $table = 'absensi_sesi';

    protected $fillable = [
        'nama_sesi',
        'tanggal',
        'lokasi',
        'catatan',
        'is_default',
        'is_locked',
        'token_qr',
        'token_generated_at',
        'qr_lifetime_seconds',
        'qr_auto_refresh',
        'created_by',
    ];

    protected $casts = [
        'tanggal'            => 'date',
        'is_default'         => 'boolean',
        'is_locked'          => 'boolean',
        'token_generated_at' => 'datetime',
        'qr_auto_refresh'    => 'boolean',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class, 'absensi_sesi_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============================================================
    // ACCESSOR
    // ============================================================

    public function getJumlahHadirAttribute()
    {
        return $this->details()->where('status_kehadiran', 'hadir')->count();
    }

    public function getJumlahTidakAttribute()
    {
        return $this->details()->where('status_kehadiran', 'tidak')->count();
    }

    // ============================================================
    // ⭐ QR CODE HELPERS
    // ============================================================

    /**
     * ⭐ Cek apakah QR masih valid
     * 
     * QR valid HANYA kalau:
     * - Sesi belum dikunci
     * - Token QR sudah di-generate
     * 
     * ⚠️ TIDAK cek expired waktu (auto-refresh dimatikan)
     * 
     * @return bool
     */
    public function isQrValid(): bool
    {
        // QR tidak valid kalau sesi sudah dikunci
        if ($this->is_locked) return false;

        // QR tidak valid kalau belum di-generate
        if (empty($this->token_qr)) return false;

        // Kalau dua-duanya lolos → valid
        return true;
    }

    /**
     * ⭐ Hitung sisa detik QR berlaku
     * 
     * Karena auto-refresh dimatikan, method ini hanya untuk backward
     * compatibility. Return value akan tetap.
     * 
     * @return int
     */
    public function qrSecondsRemaining(): int
    {
        // Kalau sesi dikunci atau token kosong → 0
        if ($this->is_locked || empty($this->token_qr)) return 0;

        // Karena tidak auto-refresh, sisa waktu tidak terbatas
        // Kita bisa return int besar (misal 999999) untuk indikasi "abadi"
        return 999999;
    }
}