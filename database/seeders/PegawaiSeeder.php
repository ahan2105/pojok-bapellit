<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Data pegawai Bappelitbangda Kab. Tasikmalaya
     * Sumber: DAFTAR HADIR per 7 Juli 2026.xlsx
     *
     * Format: [nama, golongan, nip, jabatan, bidang]
     *
     * - Bidang pakai HURUF BESAR biar gampang di-grouping
     * - Password default: "bapelit1" (sama semua)
     * - Username & email auto-generate dari nama depan
     */
    private array $pegawai = [
        // ============================================================
        // KEPALA BADAN
        // ============================================================
        ['Drs. Rahayu Jamiat Abdullah, S.Sos., M.Si.', 'IV/c', '19690718 198903 1 005', 'Kepala', 'KEPALA BADAN'],

        // ============================================================
        // SEKRETARIAT
        // ============================================================
        ['Teguh Nugraha, S.T., M.M.', 'IV/b', '19771130 200501 1 010', 'Sekretaris', 'SEKRETARIAT'],
        ['Yuliani, S.IP.', 'III/d', '19780712 200701 2 016', 'Kasubag Umum dan Kepegawaian', 'SEKRETARIAT'],
        ['Ratna Sari Aisyah, S.E.', 'III/d', '19870302 200901 2 001', 'Pengolah Data dan Informasi', 'SEKRETARIAT'],
        ['Rika Rakanita, S.AP.', 'III/b', '19910801 201903 2 006', 'Penelaah Teknis Kebijakan', 'SEKRETARIAT'],
        ['Hj. Puji Priyanti Utami Dewi', 'III/a', '19750324 200701 2 004', 'Pengadministrasi Perkantoran', 'SEKRETARIAT'],
        ['Ade Hendra Suhendar', null, '19790820 202521 1 068', 'Operator Layanan Operasional', 'SEKRETARIAT'],
        ['Mohamad Yusup', null, '19701208 202521 1 024', 'Operator Layanan Operasional', 'SEKRETARIAT'],
        ['Anita Rohmat, S.P., M.Si.', 'III/d', '19760502 201410 2 001', 'Perencana Ahli Muda', 'SEKRETARIAT'],
        ['Andri Herdiana, S.IP.', 'III/d', '19810525 201001 1 005', 'Penelaah Teknis Kebijakan', 'SEKRETARIAT'],
        ['Anton Watoni, S.AP', 'III/b', '19930521 201903 1 005', 'Perencana Ahli Pertama', 'SEKRETARIAT'],
        ['Fachrul Septian Dwiputra, S.M.', 'IX', '19930921202421 1 018', 'Perencana Ahli Pertama', 'SEKRETARIAT'],
        ['Irfan Faizal', null, '19890610 202521 1 151', 'Operator Layanan Operasional', 'SEKRETARIAT'],
        ['Rohimah, S.Si.', 'III/d', '19870216 201001 2 008', 'Kasubag Keuangan', 'SEKRETARIAT'],
        ['Adam Nugraha, S.IP.', 'III/c', '19841014 201001 1 002', 'Penelaah Teknis Kebijakan', 'SEKRETARIAT'],
        ['Dede Sutedi, S.IP.', 'III/d', '19690425 200701 1 007', 'Pengolah Data dan Informasi', 'SEKRETARIAT'],
        ['Ade Supriatna, S.Sos.', 'III/d', '19700829 200701 1 007', 'Pengolah Data dan Informasi', 'SEKRETARIAT'],
        ['Rony Setiawan, S.H.', null, '19760524 202521 1 044', 'Penata Layanan Operasional', 'SEKRETARIAT'],

        // ============================================================
        // BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM
        // ============================================================
        ['Resna Nika Febriyanty, S.Kom., M.Si.', 'III/d', '19800227 200801 2 002', 'Kepala Bidang Perekonomian dan SDA', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Dzikri Miftahul Huda, S.AP', 'III/b', '19960309 201903 1 001', 'Penelaah Teknis Kebijakan', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Dani Nurdiana, S.E.', 'III/c', '19840108 201503 1 002', 'Penelaah Teknis Kebijakan', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Eka Surtika, S.IP', 'III/c', '19780105 200701 2 007', 'Pengolah Data dan Informasi', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Satya Laksana, S.P., M.E., M.P.P.', 'III/d', '19790530 200604 1 004', 'Perencana Ahli Muda', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Rina Ridiawati, S.Sos., M.Si.', 'III/b', '19840328 201410 2 001', 'Penelaah Teknis Kebijakan', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Hapidulloh Zen, S.T.', 'IX', '19920903 202421 1 023', 'Perencana Ahli Pertama', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],
        ['Erwin Faizal Lesmana, S.Pd.', null, '19910812 202521 1 111', 'Penata Layanan Operasional', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM'],

        // ============================================================
        // BIDANG INFRASTRUKTUR DAN KEWILAYAHAN
        // ============================================================
        ['Jani Maulana, S.Sos., M.Si.', 'IV/a', '19800125 200901 1 005', 'Kepala Bidang Infrastruktur dan Kewilayahan', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Haries Nursyamsu, S.H., M.H.', 'III/d', '19790101 201001 1 002', 'Perencana Ahli Muda', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Windi Rahmikawati, S.Sos.', 'III/c', '19820930 201410 2 001', 'Penelaah Teknis Kebijakan', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Yeko Anugrah Januar, S.T.,M.T', 'III/b', '19980122 202203 1 001', 'Penelaah Teknis Kebijakan', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Irma Nurhalimah Rizqi, S.T.', 'III/a', '19910806 202505 2 001', 'Perencana Ahli Pertama', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Ardita Puja Rahmani, S.P.W.K.', 'III/a', '20020314 202505 2 003', 'Perencana Ahli Pertama', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Euis Yunan, S.T.', 'IX', '19950522 202421 2 029', 'Perencana Ahli Pertama', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],
        ['Rizki Muhibudin, A.Md.T.', null, '19910820 202521 1 132', 'Pengelola Layanan Operasional', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN'],

        // ============================================================
        // BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA
        // ============================================================
        ['Sena Adikrisna, S.H., M.H.', 'III/d', '19860718 201101 2 006', 'Kabid Pemerintahan dan Pembangunan Manusia', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Kania Dewi Utami, S.I.Kom.', 'III/d', '19900909 201503 2 001', 'Perencana Ahli Muda', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Gian Jatnika Munggaran, S.Kom', 'III/d', '19830605 200501 1 003', 'Perencana Ahli Pertama', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Intan Ayu Hardiyanti, S.E.', 'III/b', '19970507 202203 2 001', 'Perencana Ahli Pertama', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Shinta Lestari, S.Tr.IP.', 'III/b', '19991212 202208 2 002', 'Penelaah Teknis Kebijakan', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Dewi Shintya A', 'III/a', '19760202 200701 2 011', 'Pengolah Data dan Informasi', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Nida Fauziyyah Sambas, S.Mat.', 'IX', '19960330 202421 2 033', 'Perencana Ahli Pertama', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],
        ['Mohammad Fazar Ruslan Setia', null, '19950419 202521 1 091', 'Operator Layanan Operasional', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA'],

        // ============================================================
        // BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI
        // ============================================================
        ['Ine Susyane, S.T.', 'IV/a', '19781103 200604 2 001', 'Kabid Perencanaan Pengendalian dan Evaluasi', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Sri Yulia, S.Sos., M.M.', 'IV/a', '19690902 199403 2 002', 'Perencana Ahli Muda', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Kania Dewi Kinasih, S.E.', 'III/b', '19980618 202203 2 001', 'Perencana Ahli Pertama', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Asep Wahyudin, S.IP', 'III/c', '19730409 200501 1 004', 'Pengolah Data dan Informasi', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Mona Febriyanti, S.Ak.', 'III/b', '19980225 202203 2 001', 'Perencana Ahli Pertama', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Dena Tri Lestary, S.M.', 'III/b', '19940621 201503 2 001', 'Perencana Ahli Pertama', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Rizqa Fauziyyah Nabila, S.Kom.', 'III/a', '20000227 202505 2 002', 'Penata Kelola Sistem dan Teknologi Informasi', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Yadi Mulyadi, S.Sos.', 'IX', '19820116 202421 1 009', 'Perencana Ahli Pertama', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],
        ['Rifqi Widyan Ramadhan, S.T', null, '19940218 202521 1 083', 'Penata Layanan Operasional', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI'],

        // ============================================================
        // BIDANG PENELITIAN DAN PENGEMBANGAN
        // ============================================================
        ['Bayu Wijaksana, S.T., M.Si.', 'IV/a', '19820805 200902 1 004', 'Kabid Penelitian dan Pengembangan', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],
        ['Agi Nurhidayah, S.Si., M.T.', 'III/d', '19820807 200701 1 007', 'Peneliti Ahli Muda', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],
        ['Akbar Bhahesti, S.E., M.A.B.', 'III/b', '19910926 201903 1 003', 'Penelaah Teknis Kebijakan', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],
        ['Imat Rohimat, S.IP', 'III/d', '19810503 200901 1 004', 'Penelaah Teknis Kebijakan', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],
        ['Dadan Sunandar, S.Si.', 'III/d', '19810416 200902 1 002', 'Kasubid Statistik', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],
        ['Nurul Hikmah, S.Stat., M.I.L.', 'III/b', '19960408 201903 2 006', 'Penelaah Teknis Kebijakan', 'BIDANG PENELITIAN DAN PENGEMBANGAN'],

        // ============================================================
        // TENAGA IT (dari sheet "IT")
        // ============================================================
        ['Wildan Nugraha, S.T', null, null, 'Tenaga IT', 'IT'],
        ['Muhammad Ridwan, S.Kom', null, null, 'Tenaga IT', 'IT'],
        ['Febi Robiana Suherman, S.Kom', null, null, 'Tenaga IT', 'IT'],
        ['Sandria Anggra Sutardi, S.T', null, null, 'Tenaga IT', 'IT'],
    ];

    private string $defaultPassword = 'bapelit1';

    public function run(): void
    {
        $this->command->info('🌱 Mulai import data pegawai...');
        $this->command->newLine();

        $inserted = 0;
        $skipped = 0;

        foreach ($this->pegawai as $data) {
            [$nama, $golongan, $nip, $jabatan, $bidang] = $data;

            $exists = User::where(function ($q) use ($nip, $nama) {
                if ($nip) {
                    $q->where('nip', $nip);
                } else {
                    $q->where('name', $nama);
                }
            })->exists();

            if ($exists) {
                $this->command->warn("⏭  Skip (sudah ada): {$nama}");
                $skipped++;
                continue;
            }

            $username = $this->generateUsername($nama);
            $email = $this->generateEmail($nama);

            User::create([
                'name'     => $nama,
                'nip'      => $nip,
                'golongan' => $golongan,
                'jabatan'  => $jabatan,
                'bidang'   => $bidang,
                'role'     => 'user',
                'is_admin' => false,
                'status'   => 'aktif',
                'username' => $username,
                'email'    => $email,
                'password' => $this->defaultPassword,
            ]);

            $this->command->info("✅ {$nama} → @{$username}");
            $inserted++;
        }

        $this->command->newLine();
        $this->command->info("📊 Selesai!");
        $this->command->info("   Berhasil insert: {$inserted}");
        $this->command->info("   Skip          : {$skipped}");
        $this->command->info("   Total data    : " . count($this->pegawai));
        $this->command->newLine();
        $this->command->info("🔑 Password default: {$this->defaultPassword}");
    }

    private function generateUsername(string $nama): string
    {
        $cleanName = explode(',', $nama)[0];
        $cleanName = preg_replace('/^(Hj\.|H\.|Drs\.|Dra\.|Ir\.)\s*/i', '', $cleanName);
        $cleanName = trim($cleanName);
        $firstWord = explode(' ', $cleanName)[0];
        $username = strtolower(preg_replace('/[^a-z0-9]/', '', $firstWord));

        $base = $username;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $i++;
            $username = $base . $i;
        }

        return $username;
    }

    private function generateEmail(string $nama): string
    {
        $cleanName = explode(',', $nama)[0];
        $cleanName = preg_replace('/^(Hj\.|H\.|Drs\.|Dra\.|Ir\.)\s*/i', '', $cleanName);
        $cleanName = trim($cleanName);
        $firstWord = explode(' ', $cleanName)[0];
        $baseEmail = strtolower(preg_replace('/[^a-z0-9]/', '', $firstWord));

        $email = $baseEmail . '@gmail.com';
        $base = $baseEmail;
        $i = 1;
        while (User::where('email', $email)->exists()) {
            $i++;
            $email = $base . $i . '@gmail.com';
        }

        return $email;
    }
}