<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Jika tidak ditemukan, kembalikan default value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Menggunakan first() lebih aman daripada value() jika nanti butuh ekspansi
        $setting = self::where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Simpan atau update nilai setting.
     * Value akan dikonversi ke string agar kompatibel dengan kolom TEXT/VARCHAR.
     */
    public static function set(string $key, mixed $value): void
    {
        // Konversi array/object ke JSON string jika diperlukan
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        self::updateOrCreate(
            ['key' => $key], 
            ['value' => (string) $value]
        );
    }
}