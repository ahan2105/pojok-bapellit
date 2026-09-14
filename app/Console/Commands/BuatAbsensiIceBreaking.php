<?php

namespace App\Console\Commands;

use App\Models\AbsensiSesi;
use App\Models\AbsensiDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BuatAbsensiIceBreaking extends Command
{
    /**
     * Signature command
     * --force : paksa buat walau weekend (buat testing)
     */
    protected $signature = 'absensi:buat-ice-breaking {--force : Paksa buat walau Sabtu/Minggu}';

    protected $description = 'Buat sesi absensi Ice Breaking otomatis untuk hari ini (kecuali Sabtu & Minggu)';

    public function handle(): int
    {
        $hariIni = Carbon::today();

        // Cek weekend (kecuali pakai --force)
        // Pakai isWeekend() — lebih clean, warning PHP6606 hilang
        if (!$this->option('force') && $hariIni->isWeekend()) {
            $this->warn("⏭  Hari ini {$hariIni->translatedFormat('l')} — weekend, skip pembuatan sesi Ice Breaking.");
            return self::SUCCESS;
        }

        // Cek: apakah sudah ada sesi ice breaking untuk hari ini?
        $sudahAda = AbsensiSesi::where('is_default', true)
            ->whereDate('tanggal', $hariIni)
            ->exists();

        if ($sudahAda) {
            $this->info("✅ Sesi Ice Breaking untuk {$hariIni->format('d M Y')} sudah ada. Skip.");
            return self::SUCCESS;
        }

        // Buat sesi baru
        $sesi = AbsensiSesi::create([
            'nama_sesi'  => 'Sesi Ice Breaking',
            'tanggal'    => $hariIni,
            'lokasi'     => 'Aula Utama',
            'catatan'    => 'Sesi perkenalan dan ice breaking harian.',
            'is_default' => true,
            'is_locked'  => false,
            'created_by' => null, // dibuat otomatis oleh sistem
        ]);

        // Generate detail absensi untuk semua user aktif
        $users = User::where('status', 'aktif')->get();
        foreach ($users as $u) {
            AbsensiDetail::create([
                'absensi_sesi_id'  => $sesi->id,
                'user_id'          => $u->id,
                'status_kehadiran' => null,
            ]);
        }

        $this->info("🎉 Sesi Ice Breaking untuk {$hariIni->translatedFormat('l, d F Y')} berhasil dibuat dengan {$users->count()} peserta.");

        return self::SUCCESS;
    }
}