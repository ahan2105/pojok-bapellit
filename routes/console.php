<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule cleanup old notifications daily at 2 AM
Schedule::command('app:cleanup-old-notifications')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping()
    ->name('cleanup-old-notifications');
