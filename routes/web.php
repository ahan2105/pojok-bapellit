<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AulaController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminSuratController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

// ========== HALAMAN UTAMA ==========
Route::get('/', function () {
    return redirect()->route('booking.index');
});

// ========== AUTH ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ========== ROUTE USER (BOOKING, RIWAYAT, SURAT) ==========
Route::middleware(['auth'])->group(function () {
    
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
        // ⚠️ PENTING: Route 'pengaturan' & 'download' harus DI ATAS '{id}/edit'
        Route::get('/kelolasurat', [AdminSuratController::class, 'index'])->name('kelolasurat.index');
        Route::get('/kelolasurat/pengaturan', [AdminSuratController::class, 'pengaturan'])->name('kelolasurat.pengaturan');
        Route::put('/kelolasurat/pengaturan', [AdminSuratController::class, 'updatePengaturan'])->name('kelolasurat.pengaturan.update');
        Route::get('/kelolasurat/{id}/download', [AdminSuratController::class, 'downloadFile'])->name('kelolasurat.download-file');
        Route::get('/kelolasurat/{id}/edit', [AdminSuratController::class, 'edit'])->name('kelolasurat.edit');
        Route::put('/kelolasurat/{id}', [AdminSuratController::class, 'update'])->name('kelolasurat.update');
        Route::delete('/kelolasurat/{id}', [AdminSuratController::class, 'destroy'])->name('kelolasurat.destroy');

        // ===== KELOLA AKUN (ADMIN) =====
        // View: resources/views/admin/kelolaakun/
        //   - index.blade.php  → tabel daftar akun
        //   - create.blade.php → form tambah & edit
        // Controller: app/Http/Controllers/Admin/UserController.php
        Route::get('/kelolaakun', [UserController::class, 'index'])->name('kelolaakun.index');
        Route::get('/kelolaakun/create', [UserController::class, 'create'])->name('kelolaakun.create');
        Route::post('/kelolaakun', [UserController::class, 'store'])->name('kelolaakun.store');
        Route::get('/kelolaakun/{id}/edit', [UserController::class, 'edit'])->name('kelolaakun.edit');
        Route::put('/kelolaakun/{id}', [UserController::class, 'update'])->name('kelolaakun.update');
        Route::delete('/kelolaakun/{id}', [UserController::class, 'destroy'])->name('kelolaakun.destroy');

        // ===== KELOLA PEGAWAI (ADMIN) =====
        // View: resources/views/admin/absensi/pegawai.blade.php
        // Controller: app/Http/Controllers/Admin/AbsensiController.php
        Route::get('/pegawai', [AbsensiController::class, 'pegawai'])->name('pegawai.index');

        // ===== KELOLA ABSENSI (ADMIN) =====
        // View: resources/views/admin/absensi/
        //   - index.blade.php  → daftar sesi absensi
        //   - create.blade.php → form buat sesi baru
        //   - show.blade.php   → form isi kehadiran peserta
        // Controller: app/Http/Controllers/Admin/AbsensiController.php
        //
        // ⚠️ PENTING: Route statis (create, export, export-rekap) harus DI ATAS '{id}'
        // biar tidak dibaca sebagai ID.
        
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        
        // ⭐ Export Rekap per periode (mingguan/bulanan/tahunan) — HARUS DI ATAS '{id}'
        Route::get('/absensi/export-rekap', [AbsensiController::class, 'exportRekap'])->name('absensi.export-rekap');
        
        // Export per sesi (ada '{id}/export' — ini aman karena ada segmen '/export' di belakang)
        Route::get('/absensi/{id}/export', [AbsensiController::class, 'exportSesi'])->name('absensi.export');
        
        // Route dinamis (taruh paling bawah biar tidak bentrok dengan route statis di atas)
        Route::get('/absensi/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::post('/absensi/{id}/kehadiran', [AbsensiController::class, 'updateKehadiran'])->name('absensi.update-kehadiran');
        Route::post('/absensi/{id}/lock', [AbsensiController::class, 'lock'])->name('absensi.lock');
        Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });
});