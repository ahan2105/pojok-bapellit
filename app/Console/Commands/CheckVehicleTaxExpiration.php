<?php

namespace App\Console\Commands;

use App\Models\Kendaraan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class CheckVehicleTaxExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-vehicle-tax-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check vehicle tax expiration and create notifications for admin users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking vehicle tax expiration...');

        // Ambil semua kendaraan dengan pajak aktif
        $kendaraan = Kendaraan::with('pajakKendaraan')->get();
        
        $expiringVehicles = collect();
        
        foreach ($kendaraan as $k) {
            // Ambil pajak yang belum bayar dan paling dekat jatuh tempo
            $pajakAktif = $k->pajakKendaraan
                ->where('status', 'belum_bayar')
                ->sortBy('tanggal_jatuh_tempo')
                ->first();
            
            if ($pajakAktif && $pajakAktif->sisa_hari !== null && $pajakAktif->sisa_hari <= 60) {
                $expiringVehicles->push([
                    'kendaraan' => $k,
                    'pajak' => $pajakAktif
                ]);
            }
        }

        if ($expiringVehicles->isEmpty()) {
            $this->info('No vehicles with expiring tax found.');
            return self::SUCCESS;
        }

        // Ambil semua admin
        $admins = User::where('role', 'admin')->where('status', 'aktif')->get();

        if ($admins->isEmpty()) {
            $this->warn('No active admin users found to notify.');
            return self::SUCCESS;
        }

        $notificationCount = 0;

        foreach ($expiringVehicles as $item) {
            $kendaraan = $item['kendaraan'];
            $pajak = $item['pajak'];
            
            // Tentukan urgency berdasarkan sisa hari
            $urgency = match(true) {
                $pajak->sisa_hari < 0 => 'TERLAMBAT',
                $pajak->sisa_hari <= 7 => 'MENDESAK',
                $pajak->sisa_hari <= 30 => 'PERHATIAN',
                default => 'INFO'
            };

            $sisaHariText = $pajak->sisa_hari < 0 
                ? 'terlambat ' . abs($pajak->sisa_hari) . ' hari'
                : ($pajak->sisa_hari == 0 ? 'hari ini' : $pajak->sisa_hari . ' hari lagi');

            $title = "[$urgency] Pajak Kendaraan {$kendaraan->plat_nomor}";
            $message = "Pajak {$pajak->jenis_pajak} kendaraan {$kendaraan->merk} ({$kendaraan->plat_nomor}) akan jatuh tempo {$sisaHariText}.";
            
            foreach ($admins as $admin) {
                // Cek apakah notifikasi serupa sudah dibuat hari ini
                $existingNotification = Notification::where('user_id', $admin->id)
                    ->where('type', 'vehicle_tax_expiring')
                    ->whereJsonContains('data->kendaraan_id', $kendaraan->id)
                    ->whereJsonContains('data->pajak_id', $pajak->id)
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$existingNotification) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'vehicle_tax_expiring',
                        'title' => $title,
                        'message' => $message,
                        'url' => route('admin.kendaraan.show', $kendaraan->id),
                        'data' => [
                            'kendaraan_id' => $kendaraan->id,
                            'pajak_id' => $pajak->id,
                            'plat_nomor' => $kendaraan->plat_nomor,
                            'jenis_pajak' => $pajak->jenis_pajak,
                            'sisa_hari' => $pajak->sisa_hari,
                            'tanggal_jatuh_tempo' => $pajak->tanggal_jatuh_tempo->format('Y-m-d'),
                        ],
                    ]);
                    
                    $notificationCount++;
                }
            }
        }

        $this->info("Created {$notificationCount} notifications for {$expiringVehicles->count()} expiring vehicles.");

        return self::SUCCESS;
    }
}
