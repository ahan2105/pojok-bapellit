<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\BuatAbsensiIceBreaking;

// Setiap hari jam 00:01
Schedule::command(BuatAbsensiIceBreaking::class)
    ->dailyAt('00:01')
    ->timezone('Asia/Jakarta');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
