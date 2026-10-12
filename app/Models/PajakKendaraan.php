<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PajakKendaraan extends Model
{
    use HasFactory;

    protected $table = 'pajak_kendaraan';

    protected $fillable = [
        'kendaraan_id', 'jenis_pajak', 'tanggal_jatuh_tempo', 'tanggal_bayar', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    /** Kendaraan yang memiliki catatan pajak ini. */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    /** Jumlah hari dari hari ini sampai jatuh tempo. */
    public function getSisaHariAttribute(): int
    {
        return Carbon::today()->diffInDays($this->tanggal_jatuh_tempo, false);
    }

    /** Warna indikator status pajak. */
    public function getWarnaAttribute(): string
    {
        if ($this->status === 'sudah_bayar') {
            return 'green';
        }

        if ($this->sisa_hari <= 7) {
            return 'red';
        }

        if ($this->sisa_hari <= 30) {
            return 'yellow';
        }

        if ($this->sisa_hari <= 60) {
            return 'blue';
        }

        return 'gray';
    }

    /** Label sisa waktu yang mudah dibaca. */
    public function getLabelSisaAttribute(): string
    {
        if ($this->status === 'sudah_bayar') {
            return 'Sudah bayar';
        }

        if ($this->sisa_hari < 0) {
            return 'Terlambat '.abs($this->sisa_hari).' hari';
        }

        return $this->sisa_hari === 0 ? 'Hari ini' : 'H-'.$this->sisa_hari;
    }

    /** Filter pajak yang belum dibayar. */
    public function scopeBelumBayar(Builder $query): Builder
    {
        return $query->where('status', 'belum_bayar');
    }

    /** Filter pajak yang sudah dibayar. */
    public function scopeSudahBayar(Builder $query): Builder
    {
        return $query->where('status', 'sudah_bayar');
    }

    /** Urutkan pajak belum bayar terlebih dahulu dan berdasarkan jatuh tempo. */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->orderBy('status')->orderBy('tanggal_jatuh_tempo');
    }
}
