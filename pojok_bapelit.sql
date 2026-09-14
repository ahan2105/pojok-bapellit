-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 03:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pojok_bapelit`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_detail`
--

CREATE TABLE `absensi_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `absensi_sesi_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_kehadiran` enum('hadir','tidak') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `waktu_absen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_detail`
--

INSERT INTO `absensi_detail` (`id`, `absensi_sesi_id`, `user_id`, `status_kehadiran`, `keterangan`, `waktu_absen`, `created_at`, `updated_at`) VALUES
(5, 2, 1, NULL, NULL, NULL, '2026-09-14 02:35:19', '2026-09-14 02:35:19'),
(6, 2, 2, NULL, NULL, NULL, '2026-09-14 02:35:19', '2026-09-14 02:35:19'),
(7, 2, 3, NULL, NULL, NULL, '2026-09-14 02:35:19', '2026-09-14 02:35:19'),
(8, 2, 4, NULL, NULL, NULL, '2026-09-14 02:35:19', '2026-09-14 02:35:19'),
(16, 5, 1, NULL, NULL, NULL, '2026-09-14 03:24:09', '2026-09-14 03:24:09'),
(17, 5, 2, NULL, NULL, NULL, '2026-09-14 03:24:09', '2026-09-14 03:24:09'),
(18, 5, 3, NULL, NULL, NULL, '2026-09-14 03:24:09', '2026-09-14 03:24:09'),
(19, 6, 1, 'hadir', NULL, '2026-09-14 03:25:11', '2026-09-14 03:25:03', '2026-09-14 03:25:11'),
(20, 6, 2, 'hadir', NULL, '2026-09-14 03:25:11', '2026-09-14 03:25:03', '2026-09-14 03:25:11'),
(21, 6, 3, 'hadir', NULL, '2026-09-14 03:25:11', '2026-09-14 03:25:03', '2026-09-14 03:25:11'),
(22, 6, 4, 'hadir', NULL, '2026-09-14 03:25:11', '2026-09-14 03:25:03', '2026-09-14 03:25:11'),
(23, 7, 1, NULL, NULL, NULL, '2026-09-14 03:25:23', '2026-09-14 03:25:23'),
(24, 7, 2, NULL, NULL, NULL, '2026-09-14 03:25:23', '2026-09-14 03:25:23'),
(25, 7, 3, NULL, NULL, NULL, '2026-09-14 03:25:23', '2026-09-14 03:25:23'),
(26, 7, 4, NULL, NULL, NULL, '2026-09-14 03:25:23', '2026-09-14 03:25:23'),
(27, 8, 1, 'hadir', NULL, '2026-09-14 03:26:23', '2026-09-14 03:26:15', '2026-09-14 03:26:23'),
(28, 8, 2, 'hadir', NULL, '2026-09-14 03:26:23', '2026-09-14 03:26:15', '2026-09-14 03:26:23'),
(29, 8, 3, 'hadir', NULL, '2026-09-14 03:26:23', '2026-09-14 03:26:15', '2026-09-14 03:26:23');

-- --------------------------------------------------------

--
-- Table structure for table `absensi_sesi`
--

CREATE TABLE `absensi_sesi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_sesi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_sesi`
--

INSERT INTO `absensi_sesi` (`id`, `nama_sesi`, `tanggal`, `lokasi`, `catatan`, `is_default`, `is_locked`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Sesi Ice Breaking', '2026-09-14', 'Aula Utama', 'Sesi perkenalan dan ice breaking harian.', 1, 0, NULL, '2026-09-14 02:35:19', '2026-09-14 02:35:19'),
(5, 'ice breking', '2026-09-14', NULL, NULL, 0, 1, 1, '2026-09-14 03:24:09', '2026-09-14 03:24:22'),
(6, 'ice breaking', '2026-09-14', NULL, NULL, 0, 1, 1, '2026-09-14 03:25:03', '2026-09-14 03:25:16'),
(7, 'tes', '2026-09-14', NULL, NULL, 0, 1, 1, '2026-09-14 03:25:23', '2026-09-14 03:25:29'),
(8, 'tes2', '2026-09-14', NULL, NULL, 0, 1, 1, '2026-09-14 03:26:15', '2026-09-14 03:26:27');

-- --------------------------------------------------------

--
-- Table structure for table `aulas`
--

CREATE TABLE `aulas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `informasi_tambahan` text COLLATE utf8mb4_unicode_ci,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` json DEFAULT NULL,
  `fasilitas` json DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aulas`
--

INSERT INTO `aulas` (`id`, `nama`, `kapasitas`, `deskripsi`, `informasi_tambahan`, `lokasi`, `foto`, `fasilitas`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Aula Wiradadahaa', 100, 'Aula utama Bappetibangda, cocok untuk rapat koordinasi besar, sosialisasi program, dan acara seremonial.', 'jaga kebersihan', '📍Dibawah', '[\"uploads/aulas/d0d55d35-a327-451a-a40c-4e0bd178a670.JPG\"]', '[\"ac\", \"kursi\", \"tv\"]', 1, '2026-09-07 21:20:21', '2026-09-09 18:56:10'),
(2, 'Aula Wiratanuningrat', 80, 'Ruang serbaguna untuk rapat lintas bidang, FGD, dan pelatihan internal.', NULL, NULL, '[\"uploads/aulas/5cc2ff86-7d02-4fc1-9f68-f5265ab94244.jpg\"]', '[\"Proyektor\", \"AC\", \"Whiteboard\"]', 1, '2026-09-07 21:20:21', '2026-09-08 21:29:35'),
(3, 'Aula Wirahadinigrat', 60, 'Ruang lebih kecil dan intim, ideal untuk rapat internal bidang atau diskusi terbatas.', NULL, NULL, '[\"uploads/aulas/e674358d-b799-438e-b363-ff913fe49528.jpg\"]', '[\"AC\", \"TV\", \"Meja Konferensi\"]', 1, '2026-09-07 21:20:21', '2026-09-08 20:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `aula_id` bigint UNSIGNED NOT NULL,
  `tanggal_booking` date NOT NULL,
  `nama_penanggung_jawab` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_peserta` int NOT NULL,
  `sesi_waktu` enum('pagi','siang','seharian') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','canceled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `aula_id`, `tanggal_booking`, `nama_penanggung_jawab`, `keperluan`, `jumlah_peserta`, `sesi_waktu`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-09-17', 'Admin Bapelit', 'rapat', 100, 'pagi', 'canceled', '2026-09-08 21:12:20', '2026-09-09 18:53:19'),
(3, 1, 1, '2026-09-10', 'Admin Bapelit', 'as', 1, 'seharian', 'completed', '2026-09-09 18:40:22', '2026-09-09 18:47:28'),
(4, 2, 1, '2026-09-18', 'User Bapelit', 'p', 1, 'siang', 'completed', '2026-09-09 18:49:57', '2026-09-14 01:41:31'),
(6, 2, 1, '2026-09-24', 'User Bapelit', 'a', 1, 'pagi', 'approved', '2026-09-14 01:42:49', '2026-09-14 01:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_04_204219_add_username_and_role_to_users_table', 1),
(5, '2026_09_08_024058_create_aulas_table', 2),
(6, '2026_09_08_025208_create_bookings_table', 3),
(7, '2026_09_09_042305_add_informasi_tambahan_and_lokasi_to_aulas_table', 4),
(8, '2026_09_10_014702_fix_status_column_on_bookings_table', 5),
(14, '2026_09_10_044444_create_surat_tables', 6),
(15, '2026_09_10_065733_add_file_original_name_to_surats_table', 7),
(16, '2026_09_10_143510_add_admin_fields_to_users_table', 8),
(17, '2026_09_10_144257_add_is_admin_and_status_to_users_table', 9),
(18, '2026_09_14_090940_create_absensi_sesi_table', 10),
(19, '2026_09_14_091008_create_absensi_detail_table', 10),
(20, '2026_09_14_091410_create_absensi_sesi_table', 11),
(21, '2026_09_14_092020_create_absensi_detail_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_surats`
--

CREATE TABLE `pengaturan_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BAPELIT',
  `nomor_terakhir` int NOT NULL DEFAULT '0',
  `format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{nomor}',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_surats`
--

INSERT INTO `pengaturan_surats` (`id`, `kode_surat`, `nomor_terakhir`, `format`, `created_at`, `updated_at`) VALUES
(1, 'BAPELIT', 2362397, '{nomor}', '2026-09-09 23:37:51', '2026-09-10 07:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3k419Y6OhjgdTP0QzqFwID0Rkbw4yPRIRpvvVexU', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 'eyJfdG9rZW4iOiJlZk5Hdmt6QWRYUjM3R01JTktCdGNkdmVHeWdwdnFuMjBvMVBRSnZKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9yaXdheWF0Iiwicm91dGUiOiJyaXdheWF0LmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1789357703),
('rB94h2y3gux7NV959qol8AmAxNtQScLpnJwrHhgP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.137.0 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36', 'eyJfdG9rZW4iOiI1OWEzZjFJQ1ZEYWN3ckxwU0U2aU9wOUNNTlFvc09TcXp1Z2ZhY2JsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYm9va2luZyJ9fQ==', 1789349371),
('yjWyx476kK6jUKG8nIJV2Fxm3GPRVQ3zPOV1ONHp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJYb2JFV2RFcHAzb0pzdFZaeEFEcnBDNFloQzkxYU56aDZ2M2pZWHBVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdXJhdCIsInJvdXRlIjoic3VyYXQuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1789357751);

-- --------------------------------------------------------

--
-- Table structure for table `surats`
--

CREATE TABLE `surats` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `no_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `no_indek` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_tujuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isi_surat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `banyak_lampiran` int NOT NULL DEFAULT '0',
  `sifat_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `jenis_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asal_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_surat_original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surats`
--

INSERT INTO `surats` (`id`, `user_id`, `no_surat`, `tanggal`, `no_indek`, `alamat_tujuan`, `isi_surat`, `banyak_lampiran`, `sifat_surat`, `keterangan`, `jenis_surat`, `asal_surat`, `file_surat`, `file_surat_original_name`, `created_at`, `updated_at`) VALUES
(4, 2, '2362397', '2026-09-18', '828641/34284', 'uncip', 'assa', 0, 'ssss', 'ada', 'hhass', NULL, 'uploads/surat/a8200864-d395-4b8b-9f48-fb74b93e6b41.pdf', 'Pertemuan 7 - Tugas Mandiri.pdf', '2026-09-10 07:21:44', '2026-09-10 07:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `bidang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `nip`, `whatsapp`, `email_verified_at`, `password`, `role`, `bidang`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `status`) VALUES
(1, 'Admin Bapelit', 'admin', 'admin@gmail.com', NULL, NULL, NULL, '$2y$12$nEEIVdLDsixbYYDA.vbI1u4aD4xBlQd3noEq/Fsox/GN8PZ81kAuK', 'admin', NULL, NULL, '2026-09-04 13:59:54', '2026-09-04 13:59:54', 0, 'aktif'),
(2, 'User Bapelit', 'user', 'user@gmail.com', NULL, NULL, NULL, '$2y$12$hq0OU.YmYxfeGwppdJIUqu9MwNrB5VsSZEYJADcyzD28BXU.LdeIu', 'user', NULL, NULL, '2026-09-04 13:59:54', '2026-09-04 13:59:54', 0, 'aktif'),
(3, 'Staff Bapelit', 'user1', 'staff@gmail.com', NULL, NULL, NULL, '$2y$12$UZP2Evu/Hw/3STJcrjt4kOW7yOqEnS9.EZhG/Q2N4TU5IEzYd3ElO', 'user', NULL, NULL, '2026-09-04 13:59:55', '2026-09-04 13:59:55', 0, 'aktif'),
(4, 'ikball', 'bal', 'anjay@gmail.com', NULL, NULL, NULL, '$2y$12$egcqKX44n4GaFH0pcFTkb.3KlMROwBixlUXo1AMeQ2taX/h3ShTfy', 'admin', 'it', NULL, '2026-09-10 07:59:30', '2026-09-14 03:26:44', 1, 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi_detail`
--
ALTER TABLE `absensi_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_absensi_per_sesi` (`absensi_sesi_id`,`user_id`),
  ADD KEY `absensi_detail_user_id_foreign` (`user_id`);

--
-- Indexes for table `absensi_sesi`
--
ALTER TABLE `absensi_sesi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_sesi_created_by_foreign` (`created_by`);

--
-- Indexes for table `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_aula_id_foreign` (`aula_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengaturan_surats`
--
ALTER TABLE `pengaturan_surats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surats`
--
ALTER TABLE `surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surats_no_surat_unique` (`no_surat`),
  ADD KEY `surats_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_detail`
--
ALTER TABLE `absensi_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `absensi_sesi`
--
ALTER TABLE `absensi_sesi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pengaturan_surats`
--
ALTER TABLE `pengaturan_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surats`
--
ALTER TABLE `surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi_detail`
--
ALTER TABLE `absensi_detail`
  ADD CONSTRAINT `absensi_detail_absensi_sesi_id_foreign` FOREIGN KEY (`absensi_sesi_id`) REFERENCES `absensi_sesi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_detail_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `absensi_sesi`
--
ALTER TABLE `absensi_sesi`
  ADD CONSTRAINT `absensi_sesi_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_aula_id_foreign` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surats`
--
ALTER TABLE `surats`
  ADD CONSTRAINT `surats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
