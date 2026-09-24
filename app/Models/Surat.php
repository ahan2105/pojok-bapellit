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
 * @property-read string|null $file_extension
 * @property-read string|null $file_label
 * @property-read string|null $file_color
 * @property-read bool $is_pdf
 * @property-read bool $is_word
 * @property-read bool $is_excel
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
     * Daftar ekstensi yang diizinkan (sinkron dengan validasi controller)
     * JPG/PNG dihapus dari daftar ini.
     */
    public const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

    /**
     * Mapping ekstensi → label + warna badge
     * Hanya untuk file yang diizinkan (PDF, Word, Excel)
     */
    public const FILE_STYLES = [
        'pdf'  => ['label' => 'PDF',  'color' => 'red'],
        'doc'  => ['label' => 'DOC',  'color' => 'blue'],
        'docx' => ['label' => 'DOCX', 'color' => 'blue'],
        'xls'  => ['label' => 'XLS',  'color' => 'emerald'],
        'xlsx' => ['label' => 'XLSX', 'color' => 'emerald'],
    ];

    /**
     * Relasi ke user yang mengajukan
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================
    // ACCESSOR
    // =========================================================

    /**
     * URL file surat
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_surat) {
            return null;
        }
        return asset('storage/' . $this->file_surat);
    }

    /**
     * Ekstensi file (lowercase)
     */
    public function getFileExtensionAttribute(): ?string
    {
        if (!$this->file_surat) return null;
        return strtolower(pathinfo($this->file_surat, PATHINFO_EXTENSION));
    }

    /**
     * Label file (PDF / DOC / DOCX / XLS / XLSX)
     */
    public function getFileLabelAttribute(): ?string
    {
        $ext = $this->file_extension;
        if (!$ext) return null;
        return self::FILE_STYLES[$ext]['label'] ?? strtoupper($ext);
    }

    /**
     * Warna badge file (red / blue / emerald / gray)
     */
    public function getFileColorAttribute(): string
    {
        $ext = $this->file_extension;
        if (!$ext) return 'gray';
        return self::FILE_STYLES[$ext]['color'] ?? 'gray';
    }

    // =========================================================
    // HELPER: Cek tipe file
    // =========================================================

    /**
     * Cek apakah file PDF
     */
    public function isFilePdf(): bool
    {
        return $this->file_extension === 'pdf';
    }

    /**
     * Cek apakah file Word (DOC/DOCX)
     */
    public function isFileWord(): bool
    {
        return in_array($this->file_extension, ['doc', 'docx'], true);
    }

    /**
     * Cek apakah file Excel (XLS/XLSX)
     */
    public function isFileExcel(): bool
    {
        return in_array($this->file_extension, ['xls', 'xlsx'], true);
    }

    /**
     * Cek apakah file boleh di-preview langsung di browser
     * Sekarang hanya PDF yang bisa di-preview
     */
    public function isPreviewable(): bool
    {
        return $this->isFilePdf();
    }

    // =========================================================
    // ACCESSOR BOOLEAN (biar bisa dipanggil $surat->is_pdf, $surat->is_word, $surat->is_excel)
    // =========================================================

    public function getIsPdfAttribute(): bool
    {
        return $this->isFilePdf();
    }

    public function getIsWordAttribute(): bool
    {
        return $this->isFileWord();
    }

    public function getIsExcelAttribute(): bool
    {
        return $this->isFileExcel();
    }
}