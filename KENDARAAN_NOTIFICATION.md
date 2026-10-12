# 🚗 Sistem Notifikasi Pajak Kendaraan

## 📋 Fitur yang Sudah Diimplementasikan

### 1. **Banner Alert Pajak Kendaraan**
- **Lokasi**: Muncul di halaman yang menggunakan component `<x-tax-alert-banner />`
- **Threshold**: Menampilkan alert untuk kendaraan yang masa berlaku pajaknya **≤ 60 hari**
- **Status Filter**: Hanya menampilkan pajak dengan status `belum_bayar`
- **Sorting**: Mengambil pajak paling dekat jatuh tempo
- **Tombol Tutup (X)**: User bisa menutup banner dengan klik tombol X
  - Banner akan disembunyikan selama session berlangsung
  - Banner akan muncul lagi setelah user login ulang atau refresh session

### 2. **Notifikasi Database (Automatis)**
- **Command**: `app:check-vehicle-tax-expiration`
- **Schedule**: Berjalan otomatis setiap hari jam **08:00 pagi**
- **Target**: Semua user dengan role `admin` dan status `aktif`
- **Deteksi Duplicate**: Sistem mencegah notifikasi duplikat untuk pajak yang sama di hari yang sama

### 3. **Level Urgency**
Notifikasi dibagi berdasarkan tingkat urgensi:

| Sisa Hari | Label Urgency | Warna Badge |
|-----------|--------------|-------------|
| < 0 (terlambat) | **TERLAMBAT** | 🔴 Merah |
| ≤ 7 hari | **MENDESAK** | 🔴 Merah |
| 8-30 hari | **PERHATIAN** | 🟡 Kuning |
| 31-60 hari | **INFO** | 🔵 Biru |

## 🛠️ Manual Testing

### Test Command Secara Manual
```bash
php artisan app:check-vehicle-tax-expiration
```

### Test Schedule
```bash
# Lihat daftar schedule
php artisan schedule:list

# Jalankan semua schedule yang jatuh tempo (untuk testing)
php artisan schedule:run

# Test schedule tertentu (jangan lupa set waktu sesuai schedule)
php artisan schedule:work
```

### Check Notifikasi yang Dibuat
```bash
php artisan tinker
>>> App\Models\Notification::where('type', 'vehicle_tax_expiring')->latest()->get()
```

## 📂 File yang Dimodifikasi/Dibuat

1. **Command Baru**:
   - `app/Console/Commands/CheckVehicleTaxExpiration.php`
   
2. **Schedule Update**:
   - `routes/console.php` (tambah schedule jam 08:00)
   
3. **Banner Component**:
   - `resources/views/components/tax-alert-banner.blade.php` (update threshold 30→60 hari, filter status belum_bayar)
   
4. **Routes**:
   - `routes/web.php` (pindah route dismiss-alert ke user section agar semua user bisa akses)

## 🔔 Format Notifikasi

### Title
```
[URGENCY] Pajak Kendaraan B1234XYZ
```

### Message
```
Pajak tahunan kendaraan Toyota Avanza (B1234XYZ) akan jatuh tempo 15 hari lagi.
```

### Data (JSON)
```json
{
    "kendaraan_id": 1,
    "pajak_id": 5,
    "plat_nomor": "B1234XYZ",
    "jenis_pajak": "tahunan",
    "sisa_hari": 15,
    "tanggal_jatuh_tempo": "2026-10-27"
}
```

### URL
Klik notifikasi akan membuka halaman detail kendaraan:
```
/admin/kendaraan/{id}
```

## ⚙️ Konfigurasi

### Ubah Threshold Banner
Edit `resources/views/components/tax-alert-banner.blade.php`:
```php
if ($pajakAktif && $pajakAktif->sisa_hari !== null && $pajakAktif->sisa_hari <= 60) {
    // Ubah 60 menjadi nilai yang diinginkan
}
```

### Ubah Threshold Command
Edit `app/Console/Commands/CheckVehicleTaxExpiration.php`:
```php
if ($pajakAktif && $pajakAktif->sisa_hari !== null && $pajakAktif->sisa_hari <= 60) {
    // Ubah 60 menjadi nilai yang diinginkan
}
```

### Ubah Waktu Schedule
Edit `routes/console.php`:
```php
Schedule::command('app:check-vehicle-tax-expiration')
    ->daily()
    ->at('08:00') // Ubah jam di sini
```

### Ubah Target Notifikasi
Saat ini notifikasi hanya dikirim ke **admin aktif**. Jika ingin kirim ke role lain:

Edit `app/Console/Commands/CheckVehicleTaxExpiration.php`:
```php
// Sekarang:
$admins = User::where('role', 'admin')->where('status', 'aktif')->get();

// Ubah menjadi (contoh: semua user aktif):
$users = User::where('status', 'aktif')->get();
```

## 🐛 Troubleshooting

### Banner tidak muncul?
1. Pastikan user sudah login
2. Cek apakah ada kendaraan dengan pajak ≤ 60 hari
3. Cek apakah session `tax_alert_dismissed` tidak aktif
4. Clear cache: `php artisan cache:clear`

### Notifikasi tidak dibuat?
1. Cek apakah ada admin aktif: `User::where('role', 'admin')->where('status', 'aktif')->count()`
2. Cek apakah ada kendaraan dengan pajak ≤ 60 hari
3. Jalankan command manual untuk cek error
4. Pastikan scheduler Laravel berjalan di server (cron job)

### Schedule tidak berjalan otomatis?
Di server production, pastikan sudah setup cron job:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📝 Notes

- Notifikasi **tidak akan duplikat** untuk pajak yang sama di hari yang sama
- Banner alert **bersifat temporary** (hilang setelah user klik X, muncul lagi setelah login ulang)
- Database notification **bersifat permanent** sampai user hapus/mark as read
- Command otomatis berjalan **setiap hari jam 08:00**
- Notifikasi lama (> 1 bulan) akan **otomatis terhapus** jam 02:00 setiap hari
