<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $no_surat
 * @property \Illuminate\Support\Carbon|null $tanggal
 * @property string|null $no_indek
 * @property string|null $alamat_tujuan
 * @property string|null $isi_surat
 * @property int|null $banyak_lampiran
 * @property string|null $sifat_surat
 * @property string|null $keterangan
 * @property string|null $jenis_surat
 * @property string|null $asal_surat
 * @property string|null $file_surat
 * @property string|null $file_surat_original_name
 * @property-read string|null $file_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User|null $user
 */
class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_surat',
        'tanggal',
        'no_indek',
        'alamat_tujuan',
        'isi_surat',
        'banyak_lampiran',
        'sifat_surat',
        'keterangan',
        'jenis_surat',
        'asal_surat',
        'file_surat',
        'file_surat_original_name',
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