<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AulaController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminSuratController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\NotificationStreamController;
use App\Http\Controllers\AbsensiScanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// ========== HALAMAN UTAMA ==========
Route::get('/', function () {
    return redirect()->route('booking.index');
});

// ========== HALAMAN AKUN NONAKTIF ==========
Route::get('/akun-nonaktif', function () {
    return view('auth.akun-nonaktif');
})->name('akun.nonaktif');

// ========== AUTH ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ========== ROUTE USER ==========
Route::middleware(['auth', 'cek.status'])->group(function () {

    // ===== ⭐ SCAN ABSENSI (USER) =====
    Route::get('/absensi/scan', [AbsensiScanController::class, 'showScan'])->name('absensi.scan-page');
    Route::get('/absensi/scan/{token}', [AbsensiScanController::class, 'scanViaToken'])->name('absensi.scan');
    Route::post('/absensi/scan/process', [AbsensiScanController::class, 'processScan'])->name('absensi.scan-process');

    // ===== BOOKING USER =====
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
    Route::get('/booking/{id}', [BookingController::class, 'detail'])->name('booking.detail');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // ===== RIWAYAT BOOKING =====
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::post('/riwayat/{id}/cancel', [RiwayatController::class, 'cancel'])->name('riwayat.cancel');

    // ===== AMBIL SURAT (USER) =====
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{id}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{id}/download', [SuratController::class, 'download'])->name('surat.download');

    // ===== ⭐ PRESENSI SAYA (USER) =====
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');

    // ===== ⭐ NOTIFIKASI (USER & ADMIN) =====
    // SSE stream — netral, dipakai bareng admin & user
    Route::get('/notifications/stream', [NotificationStreamController::class, 'stream'])
        ->name('notifications.stream');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');

    // ========== ROUTE ADMIN ==========
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

        // ===== KELOLA AULA =====
        Route::resource('aula', AulaController::class);
        Route::post('/aula/{id}/toggle-status', [AulaController::class, 'toggleStatus'])->name('aula.toggle-status');
        Route::get('/aula/{id}/detail', [AulaController::class, 'show'])->name('aula.show');
        Route::post('/aula/{id}/delete-photos', [AulaController::class, 'deletePhotos'])->name('aula.delete-photos');
        Route::delete('/aula/{id}/delete-photo', [AulaController::class, 'deletePhoto'])->name('aula.delete-photo');

        // ===== KELOLA BOOKING (ADMIN) =====
        Route::get('/kelolabooking', [AdminBookingController::class, 'index'])->name('kelolabooking.index');
        Route::get('/kelolabooking/{id}', [AdminBookingController::class, 'detail'])->name('kelolabooking.detail');
        Route::post('/kelolabooking/{id}/approve', [AdminBookingController::class, 'approve'])->name('kelolabooking.approve');
        Route::post('/kelolabooking/{id}/reject', [AdminBookingController::class, 'reject'])->name('kelolabooking.reject');
        Route::post('/kelolabooking/{id}/complete', [AdminBookingController::class, 'complete'])->name('kelolabooking.complete');
        Route::put('/kelolabooking/{id}', [AdminBookingController::class, 'update'])->name('kelolabooking.update');
        Route::delete('/kelolabooking/{id}', [AdminBookingController::class, 'destroy'])->name('kelolabooking.destroy');

        // ===== KELOLA SURAT (ADMIN) =====
        Route::get('/kelolasurat', [AdminSuratController::class, 'index'])->name('kelolasurat.index');
        Route::get('/kelolasurat/pengaturan', [AdminSuratController::class, 'pengaturan'])->name('kelolasurat.pengaturan');
        Route::put('/kelolasurat/pengaturan', [AdminSuratController::class, 'updatePengaturan'])->name('kelolasurat.pengaturan.update');
        Route::get('/kelolasurat/{id}/download', [AdminSuratController::class, 'downloadFile'])->name('kelolasurat.download-file');
        Route::get('/kelolasurat/{id}/edit', [AdminSuratController::class, 'edit'])->name('kelolasurat.edit');
        Route::put('/kelolasurat/{id}', [AdminSuratController::class, 'update'])->name('kelolasurat.update');
        Route::delete('/kelolasurat/{id}', [AdminSuratController::class, 'destroy'])->name('kelolasurat.destroy');

        // ===== KELOLA AKUN (ADMIN) =====
        Route::get('/kelolaakun', [UserController::class, 'index'])->name('kelolaakun.index');
        Route::get('/kelolaakun/create', [UserController::class, 'create'])->name('kelolaakun.create');
        Route::post('/kelolaakun', [UserController::class, 'store'])->name('kelolaakun.store');
        Route::get('/kelolaakun/{id}/edit', [UserController::class, 'edit'])->name('kelolaakun.edit');
        Route::put('/kelolaakun/{id}', [UserController::class, 'update'])->name('kelolaakun.update');
        Route::delete('/kelolaakun/{id}', [UserController::class, 'destroy'])->name('kelolaakun.destroy');

        // ===== KELOLA PEGAWAI (ADMIN) =====
        Route::get('/pegawai', [AbsensiController::class, 'pegawai'])->name('pegawai.index');

        // ===== KELOLA ABSENSI (ADMIN) =====
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/export-rekap', [AbsensiController::class, 'exportRekap'])->name('absensi.export-rekap');
        Route::get('/absensi/{id}/qr', [AbsensiController::class, 'qrCode'])->name('absensi.qr');
        Route::post('/absensi/{id}/qr/regenerate', [AbsensiController::class, 'regenerateQr'])->name('absensi.qr-regenerate');
        Route::get('/absensi/{id}/export', [AbsensiController::class, 'exportSesi'])->name('absensi.export');
        Route::get('/absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/{id}/kehadiran', [AbsensiController::class, 'updateKehadiran'])->name('absensi.update-kehadiran');
        Route::post('/absensi/{id}/lock', [AbsensiController::class, 'lock'])->name('absensi.lock');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });
});