<?php

namespace App\Console\Commands;

use App\Http\Controllers\NotificationController;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:cleanup-old-notifications')]
#[Description('Hapus notifikasi yang lebih tua dari 1 bulan')]
class CleanupOldNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Starting cleanup old notifications...');

        $result = NotificationController::cleanupOldNotifications();

        $this->info("✅ {$result['message']}");

        return 0;
    }
}
