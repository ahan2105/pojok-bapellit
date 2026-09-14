<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_surat',
        'tanggal',
        'no_indek',
        'alamat_tujuan',
        'isi_surat',           // ← ganti dari 'keperluan'
        'banyak_lampiran',
        'sifat_surat',
        'keterangan',
        'jenis_surat',         // ← TAMBAH
        'asal_surat',          // ← TAMBAH
        'file_surat',
            'file_surat_original_name',   // ← TAMBAH
    ];

    protected $casts = [
        'tanggal' => 'date',
        'banyak_lampiran' => 'integer',
    ];

    /**
     * Relasi ke user yang mengajukan
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor URL file surat
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_surat) {
            return null;
        }
        return asset('storage/' . $this->file_surat);
    }

    /**
     * Cek apakah file PDF
     */
    public function isFilePdf(): bool
    {
        if (!$this->file_surat) return false;
        return strtolower(pathinfo($this->file_surat, PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * Cek apakah file gambar (JPG/PNG)
     */
    public function isFileImage(): bool
    {
        if (!$this->file_surat) return false;
        $ext = strtolower(pathinfo($this->file_surat, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }
}