<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'plat_nomor', 'merk', 'nomor_rangka', 'unit_kerja', 'pemakai', 'foto', 'status', 'catatan',
    ];

    /** Pajak kendaraan yang pernah tercatat. */
    public function pajakKendaraan(): HasMany
    {
        return $this->hasMany(PajakKendaraan::class, 'kendaraan_id');
    }

    /** Pajak tahunan yang belum dibayar dan paling baru. */
    public function pajakTahunanAktif(): HasOne
    {
        return $this->hasOne(PajakKendaraan::class, 'kendaraan_id')
            ->where('jenis_pajak', 'tahunan')
            ->where('status', 'belum_bayar')
            ->latest('tanggal_jatuh_tempo');
    }

    /** Pajak lima tahunan yang belum dibayar dan paling baru. */
    public function pajakLimaTahunanAktif(): HasOne
    {
        return $this->hasOne(PajakKendaraan::class, 'kendaraan_id')
            ->where('jenis_pajak', '5_tahunan')
            ->where('status', 'belum_bayar')
            ->latest('tanggal_jatuh_tempo');
    }

    /** URL foto kendaraan, atau null jika belum ada foto. */
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }
}
