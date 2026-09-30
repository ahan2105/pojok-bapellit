<?php

namespace Database\Seeders;

use App\Models\ChatbotTemplate;
use Illuminate\Database\Seeder;

class ChatbotTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // BOOKING AULA
            [
                'label' => ' Cara Booking Aula',
                'keywords' => json_encode([
                    'cara booking', 'booking aula', 'pesan aula', 'sewa aula',
                    'gimana booking', 'bagaimana booking', 'mau booking',
                    'cara pesan aula', 'cara sewa aula', 'cara booking aula',
                ]),
                'reply' => "📅 *Cara Booking Aula:*\n\n" .
                           "1. Buka menu *Booking Aula* di navbar\n" .
                           "2. Pilih aula yang ingin dipesan\n" .
                           "3. Isi form: tanggal, sesi (pagi/siang/seharian), keperluan, jumlah peserta\n" .
                           "4. Klik *Simpan* — status awal: Pending\n" .
                           "5. Tunggu admin approve\n" .
                           "6. Cek status di menu *Riwayat Booking*\n\n" .
                           "⏰ Booking minimal H-1 sebelum pemakaian.",
                'is_active' => true,
            ],

            // RIWAYAT BOOKING
            [
                'label' => '📋 Cek Riwayat Booking',
                'keywords' => json_encode([
                    'riwayat booking', 'cek booking', 'status booking',
                    'lihat booking', 'daftar booking', 'riwayat aula',
                ]),
                'reply' => "📋 *Cek Riwayat Booking:*\n\n" .
                           "1. Buka menu *Riwayat Booking Aula* di navbar\n" .
                           "2. Semua booking lu bakal tampil di situ\n" .
                           "3. Status booking:\n" .
                           "   • 🟡 *Pending* — belum di-approve admin\n" .
                           "   • 🟢 *Approved* — disetujui\n" .
                           "   • 🔴 *Rejected* — ditolak\n" .
                           "   • ⚪ *Canceled* — dibatalkan\n" .
                           "   • ✅ *Completed* — selesai",
                'is_active' => true,
            ],

            // AMBIL NOMOR SURAT
            [
                'label' => '✉️ Cara Ambil Nomor Surat',
                'keywords' => json_encode([
                    'cara ambil surat', 'ambil nomor surat', 'nomor surat',
                    'cara surat', 'buat surat', 'minta nomor surat',
                    'cara ambil nomor', 'cara minta surat',
                ]),
                'reply' => "✉️ *Cara Ambil Nomor Surat:*\n\n" .
                           "1. Buka menu *Ambil Surat* di navbar\n" .
                           "2. Isi form:\n" .
                           "   • Jenis surat\n" .
                           "   • Sifat surat\n" .
                           "   • Asal surat & alamat tujuan\n" .
                           "   • Isi ringkas surat\n" .
                           "   • Banyak lampiran\n" .
                           "3. Nomor surat otomatis muncul\n" .
                           "4. Klik *Simpan*\n" .
                           "5. Surat bisa di-download dari riwayat",
                'is_active' => true,
            ],

            // PRESENSI / ABSENSI
            [
                'label' => '📱 Cara Presensi / Absen',
                'keywords' => json_encode([
                    'cara absen', 'cara presensi', 'scan absen', 'absen qr',
                    'cara scan qr', 'presensi pegawai', 'cara absensi',
                    'cara scan', 'absen gimana', 'cara absen qr',
                ]),
                'reply' => "📱 *Cara Presensi / Absen:*\n\n" .
                           "1. Buka menu *Scan Absensi* di navbar\n" .
                           "2. Arahkan kamera ke QR code yang ditampilkan admin\n" .
                           "3. Sistem otomatis catat kehadiran lu\n" .
                           "4. Cek riwayat di menu *Presensi Saya*\n\n" .
                           "️ QR code cuma berlaku untuk sesi yang sedang aktif.",
                'is_active' => true,
            ],

            // LUPA PASSWORD
            [
                'label' => '🔐 Lupa / Ganti Password',
                'keywords' => json_encode([
                    'lupa password', 'reset password', 'ganti password',
                    'ubah password', 'ganti akun', 'password lupa',
                ]),
                'reply' => "🔐 *Lupa / Ganti Password:*\n\n" .
                           "1. Buka menu *Akun Profil* (dropdown kanan atas)\n" .
                           "2. Pilih *Ganti Password*\n" .
                           "3. Masukkan password lama + password baru\n" .
                           "4. Simpan\n\n" .
                           "Kalau beneran lupa password, hubungi admin Bappeda untuk reset.",
                'is_active' => true,
            ],

            // JAM OPERASIONAL
            [
                'label' => '⏰ Jam Operasional',
                'keywords' => json_encode([
                    'jam kerja', 'jam operasional', 'jam buka', 'jam layanan',
                    'kapan buka', 'jam berapa',
                ]),
                'reply' => "⏰ *Jam Operasional Bappeda:*\n\n" .
                           "• Senin–Kamis: 08.00 – 16.00 WIB\n" .
                           "• Jumat: 08.00 – 14.30 WIB\n" .
                           "• Sabtu–Minggu & hari libur: Tutup\n\n" .
                           "Sistem Pojok Bapelit bisa diakses 24/7, tapi approval admin cuma di jam kerja.",
                'is_active' => true,
            ],

            // KONTAK / BANTUAN
            [
                'label' => '☎️ Kontak & Bantuan',
                'keywords' => json_encode([
                    'kontak', 'hubungi', 'bantuan', 'admin bappeda', 'cs',
                    'customer service', 'no telp', 'nomor telp', 'email bappeda',
                ]),
                'reply' => "☎️ *Kontak & Bantuan:*\n\n" .
                           "• Email: bappeda@example.go.id\n" .
                           "• Telp: (0265) 123-456\n" .
                           "• Alamat: Jl. Contoh No. 123\n\n" .
                           "Untuk bantuan teknis sistem, hubungi admin IT Bappeda.",
                'is_active' => true,
            ],

            // TENTANG BAPPEDA
            [
                'label' => '🏛️ Tentang Bappeda',
                'keywords' => json_encode([
                    'apa itu bappeda', 'bappeda adalah', 'tugas bappeda',
                    'fungsi bappeda', 'bappeda itu',
                ]),
                'reply' => "🏛️ *Bappeda* adalah Badan Perencanaan Pembangunan Daerah — instansi pemerintah daerah yang bertugas:\n\n" .
                           "• Menyusun rencana pembangunan daerah\n" .
                           "• Koordinasi perencanaan lintas SKPD\n" .
                           "• Penelitian & pengembangan daerah\n" .
                           "• Monitoring & evaluasi pembangunan\n\n" .
                           "Pojok Bapelit adalah sistem internal untuk booking aula, pengambilan nomor surat, dan presensi pegawai.",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            ChatbotTemplate::updateOrCreate(
                ['label' => $template['label']], // Cek berdasarkan label agar tidak duplikat saat re-seed
                $template
            );
        }

        $this->command->info('✅ ' . count($templates) . ' template chatbot berhasil di-seed!');
    }
}