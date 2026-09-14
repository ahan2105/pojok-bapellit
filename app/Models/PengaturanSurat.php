<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $kode_surat
 * @property int $nomor_terakhir
 * @property string $format
 */
class PengaturanSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_surat',
        'nomor_terakhir',
        'format',
    ];

    protected $casts = [
        'nomor_terakhir' => 'integer',
    ];

    /**
     * Ambil pengaturan (selalu 1 row)
     */
    public static function getSetting(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'kode_surat' => 'BAPELIT',
                'nomor_terakhir' => 0,
                'format' => '{nomor}',
            ]
        );
    }

    /**
     * Ambil counter (aman untuk IDE)
     */
    public function getCounter(): int
    {
        return (int) $this->getAttribute('nomor_terakhir');
    }

    /**
     * Set counter
     */
    public function setCounter(int $value): void
    {
        $this->setAttribute('nomor_terakhir', $value);
    }

    /**
     * Ambil kode surat
     */
    public function getKodeSurat(): string
    {
        return (string) $this->getAttribute('kode_surat');
    }

    /**
     * Ambil format
     */
    public function getFormat(): string
    {
        return (string) $this->getAttribute('format');
    }

    /**
     * Generate nomor surat berikutnya
     */
    public function generateNomor(int $increment = 1): string
    {
        $nextNumber = $this->getCounter() + $increment;
        $nomor = (string) $nextNumber;

        $romawi = $this->toRomawi((int) date('n'));
        $tahun = date('Y');
        $bulan = date('m');

        return str_replace(
            ['{nomor}', '{kode}', '{romawi}', '{tahun}', '{bulan}'],
            [$nomor, $this->getKodeSurat(), $romawi, $tahun, $bulan],
            $this->getFormat()
        );
    }

    /**
     * Konversi bulan ke angka romawi
     */
    public function toRomawi(int $bulan): string
    {
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[$bulan - 1] ?? '';
    }

    /**
     * Increment counter
     */
    public function incrementCounter(int $by = 1): void
    {
        $this->setCounter($this->getCounter() + $by);
        $this->save();
    }
}