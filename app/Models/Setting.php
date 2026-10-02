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

    /**
     * Dapatkan path suara notifikasi yang valid dan siap digunakan.
     * Jika path custom tidak ada fisiknya, otomatis fallback ke /sounds/notif.mp3
     */
    public static function getNotificationSound(): string
    {
        $file = self::get('notification_sound_file', '/sounds/notif.mp3');

        if (empty($file) || $file === '/sounds/default-bell.mp3') {
            return '/sounds/notif.mp3';
        }

        // Jika path lokal, pastikan file ada secara fisik
        if (! str_starts_with($file, 'http')) {
            $relativePath = ltrim($file, '/');
            if (! file_exists(public_path($relativePath))) {
                return '/sounds/notif.mp3';
            }
        }

        return $file;
    }
}
