<?php

namespace Database\Seeders;

use App\Models\Kendaraan;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['plat_nomor' => 'B 1234 KDP', 'merk' => 'Toyota Innova Reborn', 'unit_kerja' => 'Sekretariat Daerah', 'pemakai' => 'Budi Santoso, Andi Wijaya', 'status' => 'aktif', 'catatan' => 'Kendaraan operasional pimpinan', 'tahunan' => '2027-01-15', 'lima_tahunan' => '2030-01-15'],
            ['plat_nomor' => 'B 5678 KDP', 'merk' => 'Toyota Avanza', 'unit_kerja' => 'Bidang Pemerintahan', 'pemakai' => 'Siti Rahma', 'status' => 'aktif', 'catatan' => null, 'tahunan' => '2026-10-14', 'lima_tahunan' => '2030-10-14'],
            ['plat_nomor' => 'B 9012 KDP', 'merk' => 'Mitsubishi Xpander', 'unit_kerja' => 'Bidang Perekonomian', 'pemakai' => 'Rudi Hartono', 'status' => 'aktif', 'catatan' => 'Perlu pemeriksaan rutin', 'tahunan' => '2026-11-20', 'lima_tahunan' => '2030-11-20'],
            ['plat_nomor' => 'B 3456 KDP', 'merk' => 'Daihatsu Gran Max', 'unit_kerja' => 'Bidang Infrastruktur', 'pemakai' => 'Tim Lapangan', 'status' => 'rusak', 'catatan' => 'Menunggu perbaikan bengkel', 'tahunan' => '2026-12-10', 'lima_tahunan' => '2030-12-10'],
            ['plat_nomor' => 'B 7890 KDP', 'merk' => 'Honda CR-V', 'unit_kerja' => 'Badan Perencanaan', 'pemakai' => 'Dewi Lestari', 'status' => 'dijual', 'catatan' => 'Dalam proses penghapusan aset', 'tahunan' => '2027-03-05', 'lima_tahunan' => '2031-03-05'],
        ];

        foreach ($vehicles as $vehicleData) {
            $kendaraan = Kendaraan::updateOrCreate(
                ['plat_nomor' => $vehicleData['plat_nomor']],
                collect($vehicleData)->except(['tahunan', 'lima_tahunan'])->all()
            );

            $kendaraan->pajakKendaraan()->updateOrCreate(
                ['jenis_pajak' => 'tahunan', 'status' => 'belum_bayar'],
                ['tanggal_jatuh_tempo' => $vehicleData['tahunan']]
            );
            $kendaraan->pajakKendaraan()->updateOrCreate(
                ['jenis_pajak' => '5_tahunan', 'status' => 'belum_bayar'],
                ['tanggal_jatuh_tempo' => $vehicleData['lima_tahunan']]
            );
        }
    }
}
