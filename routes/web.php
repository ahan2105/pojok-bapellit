<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AulaController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RiwayatController;
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

// ========== ROUTE USER (BOOKING & RIWAYAT) ==========
Route::middleware(['auth'])->group(function () {
    
    // ===== BOOKING USER =====
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::post('/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
    Route::get('/booking/{id}', [BookingController::class, 'detail'])->name('booking.detail');
    
    // ===== RIWAYAT BOOKING (USER & ADMIN) =====
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::post('/riwayat/{id}/cancel', [RiwayatController::class, 'cancel'])->name('riwayat.cancel');
    
    // ===== ROUTE ADMIN =====
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
        
        // ===== AKSI BOOKING (ADMIN) =====
        Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
        Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
        Route::post('/booking/{id}/complete', [BookingController::class, 'complete'])->name('booking.complete');
        Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
        Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    });
});