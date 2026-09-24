-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2026 at 04:16 PM
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
(1536, 34, 123, 'hadir', NULL, '2026-09-24 14:01:49', '2026-09-24 14:01:40', '2026-09-24 14:01:49'),
(1537, 34, 124, 'hadir', NULL, '2026-09-24 14:01:49', '2026-09-24 14:01:40', '2026-09-24 14:01:49'),
(1538, 35, 92, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1539, 35, 93, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1540, 35, 94, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1541, 35, 95, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1542, 35, 96, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1543, 35, 97, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1544, 35, 98, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1545, 35, 99, NULL, NULL, NULL, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(1546, 36, 1, NULL, NULL, NULL, '2026-09-24 14:08:33', '2026-09-24 14:08:33'),
(1547, 36, 127, NULL, NULL, NULL, '2026-09-24 14:08:33', '2026-09-24 14:08:33'),
(1548, 36, 128, 'hadir', NULL, '2026-09-24 14:08:39', '2026-09-24 14:08:33', '2026-09-24 14:08:39');

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
  `token_qr` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_generated_at` timestamp NULL DEFAULT NULL,
  `qr_lifetime_seconds` int NOT NULL DEFAULT '60',
  `qr_auto_refresh` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_sesi`
--

INSERT INTO `absensi_sesi` (`id`, `nama_sesi`, `tanggal`, `lokasi`, `catatan`, `is_default`, `is_locked`, `token_qr`, `token_generated_at`, `qr_lifetime_seconds`, `qr_auto_refresh`, `created_by`, `created_at`, `updated_at`) VALUES
(34, 'sasqewq', '2026-09-24', NULL, NULL, 0, 1, NULL, NULL, 0, 0, 1, '2026-09-24 14:01:40', '2026-09-24 14:01:53'),
(35, 'waw', '2026-09-24', NULL, NULL, 0, 0, '7GTNUEJmFwEnBOsuSxmz3wrkziYqX3U4dcSeqfjjGwQrTK4w', '2026-09-24 14:07:16', 0, 0, 1, '2026-09-24 14:07:16', '2026-09-24 14:07:16'),
(36, 'wew', '2026-09-24', NULL, NULL, 0, 0, '4ZpWLrnkUme1uvTH0Jk9vxws6PBMdUA7tONX8myWX0TvkFs8', '2026-09-24 14:08:33', 0, 0, 1, '2026-09-24 14:08:33', '2026-09-24 14:08:33');

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
(1, 'Aula Wiradadahaa', 100, 'Aula utama Bappetibangda, cocok untuk rapat koordinasi besar, sosialisasi program, dan acara seremonial.', 'jaga kebersihan', '📍Dibawah', '[\"uploads/aulas/39b0adcf-a853-4890-8a4a-41f177983bb0.jpeg\", \"uploads/aulas/f24d782b-77c2-4cf3-bd45-4d05fd755ce9.jpeg\"]', '[\"ac\", \"kursi\", \"tv\"]', 1, '2026-09-07 21:20:21', '2026-09-24 09:10:52'),
(2, 'Aula Wiratanuningrat', 80, 'Ruang serbaguna untuk rapat lintas bidang, FGD, dan pelatihan internal.', NULL, NULL, '[]', '[\"Proyektor\", \"AC\", \"Whiteboard\"]', 1, '2026-09-07 21:20:21', '2026-09-15 03:35:46'),
(3, 'Aula Wirahadinigrat', 60, 'Ruang lebih kecil dan intim, ideal untuk rapat internal bidang atau diskusi terbatas.', NULL, NULL, '[]', '[\"AC\", \"TV\", \"Meja Konferensi\"]', 1, '2026-09-07 21:20:21', '2026-09-15 06:29:03');

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
(1, 1, 1, '2026-09-25', 'Admin Bapelit', 'd', 1, 'siang', 'pending', '2026-09-24 14:33:20', '2026-09-24 14:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin_user_ids', 'a:3:{i:0;i:1;i:1;i:127;i:2;i:128;}', 1790264000),
('laravel-cache-booking_statistics_2026-09-24', 'a:7:{s:5:\"total\";i:1;s:7:\"pending\";i:1;s:8:\"approved\";i:0;s:8:\"rejected\";i:0;s:9:\"completed\";i:0;s:8:\"canceled\";i:0;s:5:\"today\";i:0;}', 1790260789),
('laravel-cache-surat_statistics_2026-09-24', 'a:3:{s:5:\"total\";i:0;s:9:\"bulan_ini\";i:0;s:8:\"hari_ini\";i:0;}', 1790266854),
('laravel-cache-user_1_unread_count', 'i:9;', 1790266565),
('laravel-cache-user_128_unread_count', 'i:8;', 1790263870);

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
(21, '2026_09_14_092020_create_absensi_detail_table', 11),
(22, '2026_09_14_140959_add_jabatan_golongan_to_users_table', 12),
(23, '2026_09_15_083404_add_qr_columns_to_absensi_sesi_table', 13),
(24, '2026_09_15_085816_change_qr_auto_refresh_default_on_absensi_sesi_table', 14),
(25, '2026_09_15_144224_create_notifications_table', 15),
(26, '2026_09_17_093925_create_push_subscriptions_table', 16),
(27, '2026_09_17_093925_increase_push_subscriptions_endpoint_length', 16),
(28, '2026_09_23_144427_add_indexes_to_notifications_table', 17),
(29, '2026_09_23_145509_add_indexes_to_surats_table', 18),
(30, '2026_09_23_145649_add_indexes_to_bookings_table', 19),
(31, '2026_09_21_142525_add_profile_photo_to_users_table', 20);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `url`, `read_at`, `created_at`, `updated_at`) VALUES
(35, 1, 'booking', 'Booking Baru', 'leo booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-18\", \"booking_id\": 24}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-18 02:16:22', '2026-09-18 01:47:44', '2026-09-18 02:16:22'),
(36, 127, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-18 telah disetujui.', '{\"status\": \"approved\", \"booking_id\": 24}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat?24', '2026-09-18 01:48:27', '2026-09-18 01:48:13', '2026-09-18 01:48:27'),
(37, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-21\", \"booking_id\": 25}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-18 02:16:18', '2026-09-18 01:54:22', '2026-09-18 02:16:18'),
(38, 1, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"Ikbal Maulana\", \"tanggal\": \"28 Sep 2026\", \"keperluan\": \"pemilihan calon presiden 2040\", \"booking_id\": 26}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking/26', '2026-09-18 02:32:29', '2026-09-18 02:29:27', '2026-09-18 02:32:29'),
(39, 127, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"Ikbal Maulana\", \"tanggal\": \"28 Sep 2026\", \"keperluan\": \"pemilihan calon presiden 2040\", \"booking_id\": 26}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking/26', NULL, '2026-09-18 02:29:27', '2026-09-18 02:29:27'),
(40, 4, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-28 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-28\", \"booking_id\": 26}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/26', '2026-09-24 07:58:44', '2026-09-18 02:32:19', '2026-09-24 07:58:44'),
(41, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"ss\", \"booking_id\": 27}', 'http://pojok-bapelit.test/admin/kelolabooking/27', '2026-09-18 03:24:56', '2026-09-18 03:15:06', '2026-09-18 03:24:56'),
(42, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"ss\", \"booking_id\": 27}', 'http://pojok-bapelit.test/admin/kelolabooking/27', NULL, '2026-09-18 03:15:06', '2026-09-18 03:15:06'),
(43, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 28}', 'http://pojok-bapelit.test/admin/kelolabooking/28', '2026-09-18 03:24:56', '2026-09-18 03:15:28', '2026-09-18 03:24:56'),
(44, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 28}', 'http://pojok-bapelit.test/admin/kelolabooking/28', NULL, '2026-09-18 03:15:28', '2026-09-18 03:15:28'),
(45, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 29}', 'http://pojok-bapelit.test/admin/kelolabooking/29', '2026-09-18 03:24:56', '2026-09-18 03:20:35', '2026-09-18 03:24:56'),
(46, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 29}', 'http://pojok-bapelit.test/admin/kelolabooking/29', NULL, '2026-09-18 03:20:35', '2026-09-18 03:20:35'),
(47, 1, 'surat', 'Pengajuan Surat Baru', '3 dari test 2', '{\"jenis\": null, \"pengaju\": \"test 2\", \"no_surat\": \"3\", \"surat_id\": 10}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-18 03:24:56', '2026-09-18 03:23:35', '2026-09-18 03:24:56'),
(48, 127, 'surat', 'Pengajuan Surat Baru', '3 dari test 2', '{\"jenis\": null, \"pengaju\": \"test 2\", \"no_surat\": \"3\", \"surat_id\": 10}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-18 03:23:35', '2026-09-18 03:23:35'),
(49, 1, 'surat', 'Pengajuan Surat Baru', '4 dari test 2', '{\"jenis\": \"ass\", \"pengaju\": \"test 2\", \"no_surat\": \"4\", \"surat_id\": 11}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-18 03:24:56', '2026-09-18 03:24:08', '2026-09-18 03:24:56'),
(50, 127, 'surat', 'Pengajuan Surat Baru', '4 dari test 2', '{\"jenis\": \"ass\", \"pengaju\": \"test 2\", \"no_surat\": \"4\", \"surat_id\": 11}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-18 03:24:08', '2026-09-18 03:24:08'),
(51, 1, 'surat', 'Pengajuan Surat Baru', '5 dari test 2', '{\"jenis\": \"f5t\", \"pengaju\": \"test 2\", \"no_surat\": \"5\", \"surat_id\": 12}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-18 03:24:56', '2026-09-18 03:24:44', '2026-09-18 03:24:56'),
(52, 127, 'surat', 'Pengajuan Surat Baru', '5 dari test 2', '{\"jenis\": \"f5t\", \"pengaju\": \"test 2\", \"no_surat\": \"5\", \"surat_id\": 12}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-18 03:24:44', '2026-09-18 03:24:44'),
(53, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-25 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-25\", \"booking_id\": 29}', 'http://pojok-bapelit.test/riwayat/29', '2026-09-21 03:21:11', '2026-09-18 03:25:09', '2026-09-21 03:21:11'),
(54, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-23 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-23\", \"booking_id\": 28}', 'http://pojok-bapelit.test/riwayat/28', '2026-09-21 03:21:11', '2026-09-18 03:25:22', '2026-09-21 03:21:11'),
(55, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-23 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-23\", \"booking_id\": 27}', 'http://pojok-bapelit.test/riwayat/27', '2026-09-21 03:21:11', '2026-09-18 03:29:28', '2026-09-21 03:21:11'),
(56, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"19 Sep 2026\", \"keperluan\": \"er\", \"booking_id\": 30}', 'http://pojok-bapelit.test/admin/kelolabooking/30', '2026-09-18 04:11:08', '2026-09-18 03:29:52', '2026-09-18 04:11:08'),
(57, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"19 Sep 2026\", \"keperluan\": \"er\", \"booking_id\": 30}', 'http://pojok-bapelit.test/admin/kelolabooking/30', NULL, '2026-09-18 03:29:52', '2026-09-18 03:29:52'),
(58, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-19 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-19\", \"booking_id\": 30}', 'http://pojok-bapelit.test/riwayat/30', '2026-09-21 03:21:11', '2026-09-18 03:30:00', '2026-09-21 03:21:11'),
(59, 1, 'surat', 'Pengajuan Surat Baru', '6 dari Ikbal Maulana', '{\"jenis\": \"surat magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"6\", \"surat_id\": 13}', 'https://longitude-heights-reputation-establishing.trycloudflare.com/admin/kelolasurat', '2026-09-18 04:11:08', '2026-09-18 03:54:55', '2026-09-18 04:11:08'),
(60, 127, 'surat', 'Pengajuan Surat Baru', '6 dari Ikbal Maulana', '{\"jenis\": \"surat magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"6\", \"surat_id\": 13}', 'https://longitude-heights-reputation-establishing.trycloudflare.com/admin/kelolasurat', NULL, '2026-09-18 03:54:55', '2026-09-18 03:54:55'),
(61, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"i\", \"booking_id\": 31}', 'http://pojok-bapelit.test/admin/kelolabooking/31', '2026-09-18 06:57:52', '2026-09-18 06:45:54', '2026-09-18 06:57:52'),
(62, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"i\", \"booking_id\": 31}', 'http://pojok-bapelit.test/admin/kelolabooking/31', NULL, '2026-09-18 06:45:54', '2026-09-18 06:45:54'),
(63, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 32}', 'http://pojok-bapelit.test/admin/kelolabooking/32', '2026-09-18 06:57:50', '2026-09-18 06:46:58', '2026-09-18 06:57:50'),
(64, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 32}', 'http://pojok-bapelit.test/admin/kelolabooking/32', NULL, '2026-09-18 06:46:58', '2026-09-18 06:46:58'),
(65, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 33}', 'http://pojok-bapelit.test/admin/kelolabooking/33', '2026-09-18 06:48:08', '2026-09-18 06:47:25', '2026-09-18 06:48:08'),
(66, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 33}', 'http://pojok-bapelit.test/admin/kelolabooking/33', NULL, '2026-09-18 06:47:25', '2026-09-18 06:47:25'),
(67, 1, 'surat', 'Pengajuan Surat Baru', '7 dari test 2', '{\"jenis\": \"ha\", \"pengaju\": \"test 2\", \"no_surat\": \"7\", \"surat_id\": 14}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-18 07:21:38', '2026-09-18 06:58:17', '2026-09-18 07:21:38'),
(68, 127, 'surat', 'Pengajuan Surat Baru', '7 dari test 2', '{\"jenis\": \"ha\", \"pengaju\": \"test 2\", \"no_surat\": \"7\", \"surat_id\": 14}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-18 06:58:17', '2026-09-18 06:58:17'),
(69, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"ss\", \"booking_id\": 34}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 03:12:33', '2026-09-21 03:04:48', '2026-09-21 03:12:33'),
(70, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"ss\", \"booking_id\": 34}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:04:48', '2026-09-21 03:04:48'),
(71, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"26 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 35}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 03:12:33', '2026-09-21 03:05:19', '2026-09-21 03:12:33'),
(72, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"26 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 35}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:05:19', '2026-09-21 03:05:19'),
(73, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"30 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 36}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 03:12:33', '2026-09-21 03:06:06', '2026-09-21 03:12:33'),
(74, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"30 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 36}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:06:06', '2026-09-21 03:06:06'),
(75, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 37}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 03:12:33', '2026-09-21 03:06:36', '2026-09-21 03:12:33'),
(76, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 37}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:06:36', '2026-09-21 03:06:36'),
(77, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 38}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 03:12:33', '2026-09-21 03:10:53', '2026-09-21 03:12:33'),
(78, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 38}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:10:53', '2026-09-21 03:10:53'),
(79, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"2\", \"booking_id\": 39}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 04:02:28', '2026-09-21 03:14:53', '2026-09-21 04:02:28'),
(80, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"2\", \"booking_id\": 39}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 03:14:53', '2026-09-21 03:14:53'),
(81, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 40}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 04:24:22', '2026-09-21 04:13:52', '2026-09-21 04:24:22'),
(82, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 40}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:13:52', '2026-09-21 04:13:52'),
(83, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"r\", \"booking_id\": 41}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 04:18:38', '2026-09-21 04:15:56', '2026-09-21 04:18:38'),
(84, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"r\", \"booking_id\": 41}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:15:56', '2026-09-21 04:15:56'),
(85, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-23 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-23\", \"booking_id\": 40}', 'http://pojok-bapelit.test/riwayat/40', '2026-09-21 07:05:21', '2026-09-21 04:16:58', '2026-09-21 07:05:21'),
(86, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 42}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 04:24:22', '2026-09-21 04:19:25', '2026-09-21 04:24:22'),
(87, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 42}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:19:25', '2026-09-21 04:19:25'),
(88, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 43}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 07:05:38', '2026-09-21 04:24:36', '2026-09-21 07:05:38'),
(89, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 43}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:24:36', '2026-09-21 04:24:36'),
(90, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-22 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-22\", \"booking_id\": 43}', 'http://pojok-bapelit.test/riwayat/43', '2026-09-21 07:05:21', '2026-09-21 04:25:42', '2026-09-21 07:05:21'),
(91, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 44}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 07:05:38', '2026-09-21 04:26:28', '2026-09-21 07:05:38'),
(92, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 44}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:26:28', '2026-09-21 04:26:28'),
(93, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 45}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 04:32:08', '2026-09-21 04:31:55', '2026-09-21 04:32:08'),
(94, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"25 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 45}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 04:31:55', '2026-09-21 04:31:55'),
(95, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-25 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-25\", \"booking_id\": 45}', 'http://pojok-bapelit.test/riwayat/45', '2026-09-21 07:05:21', '2026-09-21 04:32:11', '2026-09-21 07:05:21'),
(96, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 46}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 07:05:38', '2026-09-21 06:49:51', '2026-09-21 07:05:38'),
(97, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 46}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 06:49:51', '2026-09-21 06:49:51'),
(98, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"w\", \"booking_id\": 47}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 06:54:38', '2026-09-21 06:54:30', '2026-09-21 06:54:38'),
(99, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"w\", \"booking_id\": 47}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 06:54:30', '2026-09-21 06:54:30'),
(100, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-22 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-22\", \"booking_id\": 47}', 'http://pojok-bapelit.test/riwayat/47', '2026-09-21 07:05:21', '2026-09-21 06:54:40', '2026-09-21 07:05:21'),
(101, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-24 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-24\", \"booking_id\": 46}', 'http://pojok-bapelit.test/riwayat/46', '2026-09-21 07:05:21', '2026-09-21 06:54:46', '2026-09-21 07:05:21'),
(102, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 48}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 08:19:31', '2026-09-21 08:13:26', '2026-09-21 08:19:31'),
(103, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"a\", \"booking_id\": 48}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:13:26', '2026-09-21 08:13:26'),
(104, 1, 'surat', 'Pengajuan Surat Baru', '8 dari test 2', '{\"jenis\": \"s\", \"pengaju\": \"test 2\", \"no_surat\": \"8\", \"surat_id\": 15}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-21 08:19:31', '2026-09-21 08:15:12', '2026-09-21 08:19:31'),
(105, 127, 'surat', 'Pengajuan Surat Baru', '8 dari test 2', '{\"jenis\": \"s\", \"pengaju\": \"test 2\", \"no_surat\": \"8\", \"surat_id\": 15}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-21 08:15:12', '2026-09-21 08:15:12'),
(106, 1, 'surat', 'Pengajuan Surat Baru', '9 dari test 2', '{\"jenis\": \"a\", \"pengaju\": \"test 2\", \"no_surat\": \"9\", \"surat_id\": 16}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-21 08:19:31', '2026-09-21 08:15:46', '2026-09-21 08:19:31'),
(107, 127, 'surat', 'Pengajuan Surat Baru', '9 dari test 2', '{\"jenis\": \"a\", \"pengaju\": \"test 2\", \"no_surat\": \"9\", \"surat_id\": 16}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-21 08:15:46', '2026-09-21 08:15:46'),
(108, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"26 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 49}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 08:19:31', '2026-09-21 08:16:00', '2026-09-21 08:19:31'),
(109, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"seharian\", \"pengaju\": \"test 2\", \"tanggal\": \"26 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 49}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:16:00', '2026-09-21 08:16:00'),
(110, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"28 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 50}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 01:29:51', '2026-09-21 08:20:21', '2026-09-22 01:29:51'),
(111, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"28 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 50}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:20:21', '2026-09-21 08:20:21'),
(112, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"27 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 51}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 08:21:00', '2026-09-21 08:20:51', '2026-09-21 08:21:00'),
(113, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"27 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 51}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:20:51', '2026-09-21 08:20:51'),
(114, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-27 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-27\", \"booking_id\": 51}', 'http://pojok-bapelit.test/riwayat/51', '2026-09-22 02:46:28', '2026-09-21 08:21:03', '2026-09-22 02:46:28'),
(115, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 52}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-21 08:22:16', '2026-09-21 08:22:07', '2026-09-21 08:22:16'),
(116, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 52}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:22:07', '2026-09-21 08:22:07'),
(117, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-22 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-22\", \"booking_id\": 52}', 'http://pojok-bapelit.test/riwayat/52', '2026-09-22 02:46:28', '2026-09-21 08:22:19', '2026-09-22 02:46:28'),
(118, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 53}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 01:29:51', '2026-09-21 08:29:19', '2026-09-22 01:29:51'),
(119, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"23 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 53}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:29:19', '2026-09-21 08:29:19'),
(120, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 54}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 01:29:51', '2026-09-21 08:29:35', '2026-09-22 01:29:51'),
(121, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 54}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:29:35', '2026-09-21 08:29:35'),
(122, 1, 'surat', 'Pengajuan Surat Baru', '10 dari test 2', '{\"jenis\": \"s\", \"pengaju\": \"test 2\", \"no_surat\": \"10\", \"surat_id\": 17}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-21 08:30:58', '2026-09-21 08:29:54', '2026-09-21 08:30:58'),
(123, 127, 'surat', 'Pengajuan Surat Baru', '10 dari test 2', '{\"jenis\": \"s\", \"pengaju\": \"test 2\", \"no_surat\": \"10\", \"surat_id\": 17}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-21 08:29:54', '2026-09-21 08:29:54'),
(124, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 55}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 01:29:51', '2026-09-21 08:38:21', '2026-09-22 01:29:51'),
(125, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"22 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 55}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-21 08:38:21', '2026-09-21 08:38:21'),
(126, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"d\", \"booking_id\": 56}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 02:31:00', '2026-09-22 02:30:23', '2026-09-22 02:31:00'),
(127, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"24 Sep 2026\", \"keperluan\": \"d\", \"booking_id\": 56}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-22 02:30:23', '2026-09-22 02:30:23'),
(128, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"d\", \"booking_id\": 57}', 'http://pojok-bapelit.test/admin/kelolabooking', '2026-09-22 02:30:57', '2026-09-22 02:30:45', '2026-09-22 02:30:57'),
(129, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"d\", \"booking_id\": 57}', 'http://pojok-bapelit.test/admin/kelolabooking', NULL, '2026-09-22 02:30:45', '2026-09-22 02:30:45'),
(130, 128, 'booking', 'Booking Ditolak', 'Booking aula Aula Wiradadahaa tanggal 2026-09-23 ditolak.', '{\"alasan\": null, \"status\": \"rejected\", \"booking_id\": 53}', 'http://pojok-bapelit.test/riwayat?53', '2026-09-23 08:46:38', '2026-09-22 02:57:52', '2026-09-23 08:46:38'),
(131, 1, 'surat', 'Pengajuan Surat Baru', '11 dari test 2', '{\"jenis\": \"Surat anu\", \"pengaju\": \"test 2\", \"no_surat\": \"11\", \"surat_id\": 18}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', '2026-09-23 08:46:13', '2026-09-23 08:37:03', '2026-09-23 08:46:13'),
(132, 127, 'surat', 'Pengajuan Surat Baru', '11 dari test 2', '{\"jenis\": \"Surat anu\", \"pengaju\": \"test 2\", \"no_surat\": \"11\", \"surat_id\": 18}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-23 08:37:03', '2026-09-23 08:37:03'),
(133, 1, 'surat', 'Pengajuan Surat Baru', '12 dari test 2', '{\"jenis\": \"Surat cinta\", \"pengaju\": \"test 2\", \"no_surat\": \"12\", \"surat_id\": 19}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', '2026-09-23 08:46:13', '2026-09-23 08:38:12', '2026-09-23 08:46:13'),
(134, 127, 'surat', 'Pengajuan Surat Baru', '12 dari test 2', '{\"jenis\": \"Surat cinta\", \"pengaju\": \"test 2\", \"no_surat\": \"12\", \"surat_id\": 19}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-23 08:38:12', '2026-09-23 08:38:12'),
(135, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-24\", \"booking_id\": 58}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-23 08:46:13', '2026-09-23 08:44:14', '2026-09-23 08:46:13'),
(136, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-24\", \"booking_id\": 58}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-23 08:44:14', '2026-09-23 08:44:14'),
(137, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-30\", \"booking_id\": 59}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-23 08:46:13', '2026-09-23 08:45:10', '2026-09-23 08:46:13'),
(138, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-30\", \"booking_id\": 59}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-23 08:45:10', '2026-09-23 08:45:10'),
(139, 1, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-28\", \"booking_id\": 60}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-23 08:46:13', '2026-09-23 08:45:36', '2026-09-23 08:46:13'),
(140, 127, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-28\", \"booking_id\": 60}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-23 08:45:36', '2026-09-23 08:45:36'),
(141, 1, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-28 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-28\", \"booking_id\": 60}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/60', '2026-09-23 08:48:11', '2026-09-23 08:46:23', '2026-09-23 08:48:11'),
(142, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-30 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-30\", \"booking_id\": 59}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/59', '2026-09-23 08:47:35', '2026-09-23 08:46:42', '2026-09-23 08:47:35'),
(143, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-24\", \"booking_id\": 61}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-23 08:48:11', '2026-09-23 08:47:46', '2026-09-23 08:48:11'),
(144, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-24\", \"booking_id\": 61}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-23 08:47:46', '2026-09-23 08:47:46'),
(145, 1, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-25\", \"booking_id\": 62}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-24 06:35:26', '2026-09-23 08:49:00', '2026-09-24 06:35:26'),
(146, 127, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-25\", \"booking_id\": 62}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-23 08:49:00', '2026-09-23 08:49:00'),
(147, 1, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-28\", \"booking_id\": 63}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-24 07:39:47', '2026-09-24 07:39:33', '2026-09-24 07:39:47'),
(148, 127, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-28\", \"booking_id\": 63}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 07:39:33', '2026-09-24 07:39:33'),
(149, 4, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-28 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-28\", \"booking_id\": 63}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/63', '2026-09-24 07:58:44', '2026-09-24 07:39:52', '2026-09-24 07:58:44'),
(150, 1, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-25\", \"booking_id\": 64}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-24 09:51:01', '2026-09-24 07:40:47', '2026-09-24 09:51:01'),
(151, 127, 'booking', 'Booking Baru', 'Ikbal Maulana booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"tanggal\": \"2026-09-25\", \"booking_id\": 64}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 07:40:47', '2026-09-24 07:40:47'),
(152, 4, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-25 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-25\", \"booking_id\": 64}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/64', '2026-09-24 07:57:42', '2026-09-24 07:41:01', '2026-09-24 07:57:42'),
(153, 1, 'surat', 'Pengajuan Surat Baru', '13 dari Ikbal Maulana', '{\"jenis\": \"Surat izin healing\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"13\", \"surat_id\": 20}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', '2026-09-24 09:51:01', '2026-09-24 07:42:02', '2026-09-24 09:51:01'),
(154, 127, 'surat', 'Pengajuan Surat Baru', '13 dari Ikbal Maulana', '{\"jenis\": \"Surat izin healing\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"13\", \"surat_id\": 20}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 07:42:02', '2026-09-24 07:42:02'),
(155, 1, 'surat', 'Pengajuan Surat Baru', '14 dari Ikbal Maulana', '{\"jenis\": \"Laporan kegiatan selesai magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"14\", \"surat_id\": 21}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', '2026-09-24 09:51:01', '2026-09-24 07:56:31', '2026-09-24 09:51:01'),
(156, 127, 'surat', 'Pengajuan Surat Baru', '14 dari Ikbal Maulana', '{\"jenis\": \"Laporan kegiatan selesai magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"14\", \"surat_id\": 21}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 07:56:31', '2026-09-24 07:56:31'),
(157, 1, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-26\", \"booking_id\": 65}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-24 09:51:01', '2026-09-24 09:09:56', '2026-09-24 09:51:01'),
(158, 127, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-26\", \"booking_id\": 65}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 09:09:56', '2026-09-24 09:09:56'),
(159, 1, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-28\", \"booking_id\": 66}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:11:11', '2026-09-24 14:11:11'),
(160, 127, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-28\", \"booking_id\": 66}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:11:11', '2026-09-24 14:11:11'),
(161, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-29\", \"booking_id\": 67}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:12:34', '2026-09-24 14:12:34'),
(162, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-29\", \"booking_id\": 67}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:12:34', '2026-09-24 14:12:34'),
(163, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-25\", \"booking_id\": 68}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', '2026-09-24 14:15:55', '2026-09-24 14:15:47', '2026-09-24 14:15:55'),
(164, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-25\", \"booking_id\": 68}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:15:47', '2026-09-24 14:15:47'),
(165, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-25 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-25\", \"booking_id\": 68}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/68', NULL, '2026-09-24 14:15:58', '2026-09-24 14:15:58'),
(166, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-29 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-29\", \"booking_id\": 67}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/67', NULL, '2026-09-24 14:16:03', '2026-09-24 14:16:03'),
(167, 1, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-28 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-28\", \"booking_id\": 66}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/66', NULL, '2026-09-24 14:17:24', '2026-09-24 14:17:24'),
(168, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-30\", \"booking_id\": 69}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:17:44', '2026-09-24 14:17:44'),
(169, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"tanggal\": \"2026-09-30\", \"booking_id\": 69}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:17:44', '2026-09-24 14:17:44'),
(170, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-30 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"status\": \"approved\", \"tanggal\": \"2026-09-30\", \"booking_id\": 69}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/69', NULL, '2026-09-24 14:18:12', '2026-09-24 14:18:12'),
(171, 1, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-25\", \"booking_id\": 1}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:33:20', '2026-09-24 14:33:20'),
(172, 127, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-25\", \"booking_id\": 1}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:33:20', '2026-09-24 14:33:20'),
(173, 128, 'booking', 'Booking Baru', 'Admin Bapelit booking Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"siang\", \"tanggal\": \"2026-09-25\", \"booking_id\": 1}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolabooking', NULL, '2026-09-24 14:33:20', '2026-09-24 14:33:20'),
(174, 1, 'surat', 'Pengajuan Surat Baru', '15 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"15\", \"surat_id\": 22}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:38', '2026-09-24 14:40:38'),
(175, 127, 'surat', 'Pengajuan Surat Baru', '15 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"15\", \"surat_id\": 22}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:38', '2026-09-24 14:40:38'),
(176, 128, 'surat', 'Pengajuan Surat Baru', '15 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"15\", \"surat_id\": 22}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:38', '2026-09-24 14:40:38'),
(177, 1, 'surat', 'Pengajuan Surat Baru', '16 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"16\", \"surat_id\": 23}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:57', '2026-09-24 14:40:57'),
(178, 127, 'surat', 'Pengajuan Surat Baru', '16 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"16\", \"surat_id\": 23}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:57', '2026-09-24 14:40:57'),
(179, 128, 'surat', 'Pengajuan Surat Baru', '16 dari Admin Bapelit', '{\"jenis\": \"tugas\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"16\", \"surat_id\": 23}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:40:57', '2026-09-24 14:40:57'),
(180, 1, 'surat', 'Pengajuan Surat Baru', '17 dari test 2', '{\"jenis\": \"iahah\", \"pengaju\": \"test 2\", \"no_surat\": \"17\", \"surat_id\": 24}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:02', '2026-09-24 14:45:02'),
(181, 127, 'surat', 'Pengajuan Surat Baru', '17 dari test 2', '{\"jenis\": \"iahah\", \"pengaju\": \"test 2\", \"no_surat\": \"17\", \"surat_id\": 24}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:02', '2026-09-24 14:45:02'),
(182, 128, 'surat', 'Pengajuan Surat Baru', '17 dari test 2', '{\"jenis\": \"iahah\", \"pengaju\": \"test 2\", \"no_surat\": \"17\", \"surat_id\": 24}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:02', '2026-09-24 14:45:02'),
(183, 1, 'surat', 'Pengajuan Surat Baru', '18 dari Admin Bapelit', '{\"jenis\": \"asaerwer\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"18\", \"surat_id\": 25}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:39', '2026-09-24 14:45:39'),
(184, 127, 'surat', 'Pengajuan Surat Baru', '18 dari Admin Bapelit', '{\"jenis\": \"asaerwer\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"18\", \"surat_id\": 25}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:39', '2026-09-24 14:45:39'),
(185, 128, 'surat', 'Pengajuan Surat Baru', '18 dari Admin Bapelit', '{\"jenis\": \"asaerwer\", \"pengaju\": \"Admin Bapelit\", \"no_surat\": \"18\", \"surat_id\": 25}', 'https://spendable-portal-overexert.ngrok-free.dev/admin/kelolasurat', NULL, '2026-09-24 14:45:39', '2026-09-24 14:45:39');

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
(1, 'BAPELIT', 18, '{nomor}', '2026-09-09 23:37:51', '2026-09-24 14:45:39');

-- --------------------------------------------------------

--
-- Table structure for table `push_subscriptions`
--

CREATE TABLE `push_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `subscribable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribable_id` bigint UNSIGNED NOT NULL,
  `endpoint` varchar(1024) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_encoding` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `push_subscriptions`
--

INSERT INTO `push_subscriptions` (`id`, `subscribable_type`, `subscribable_id`, `endpoint`, `public_key`, `auth_token`, `content_encoding`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 128, 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAABqq1p6fDazt0AHn7twlGL1SmghF25b83BaMh1-H-7IMMwm0qPT5MweWUlREyZ7bj84S3sEua5SG5pAHQk55GZaO8b_i7Olla8w9dIq_u3UyJgwhCAPyAvApsxxSXndPW9E9j4WYarKwpuKkth8BWCwBeRZm9lYlJoA8kFJdsUEaWK2LXc', 'BPjtGxzOETTDVPJFjNOpZy8lO60ZS6o7pPZTSZ_pUyOk8eBsPGuzasO3xkQWNd2bxy_XlpD00w42dXxKk0RW0V4', 'aTbo13Pf4U4J1-slX4oXJw', NULL, '2026-09-17 03:11:54', '2026-09-17 03:11:54'),
(7, 'App\\Models\\User', 1, 'https://fcm.googleapis.com/fcm/send/eWOBnoyNIdw:APA91bHQgMXU-Cx-vzL0wxF9VrhOOhB9JZ61MhPksOfTNm5SUhSa8d5HHmuYBcBSyRTe4dE1lr-8hL4RUzp7jpd8nH_FGeOozVDrmm8Fy-k3WR1D0RymgCuMHf-k7sZtKNTvM7iH7SLs', 'BGcOvrr0T4vaOPt_80XvF4OTvdz1llI9dhu8KzfPHuPGzaAuvNUkYiQ2PM1LE7mF4htlaZxTkvLcfa-pg8z2sPk', 'Sryur3Wuvu6P5hdnHYq7JQ', NULL, '2026-09-17 03:57:37', '2026-09-17 03:57:37');

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
('aud9nvmTLMAY2a0RKqx6JwBfAO0TPZ7kuRS6PjX9', 128, '114.79.49.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJ2QmNYQXJsd3VlR1labTJhUWMzYjVNQU5PNTRaSng0S01yTUsxVVNUIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NwZW5kYWJsZS1wb3J0YWwtb3ZlcmV4ZXJ0Lm5ncm9rLWZyZWUuZGV2XC9ib29raW5nIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvc3BlbmRhYmxlLXBvcnRhbC1vdmVyZXhlcnQubmdyb2stZnJlZS5kZXZcL25vdGlmaWNhdGlvbnNcL3N0cmVhbSIsInJvdXRlIjoibm90aWZpY2F0aW9ucy5zdHJlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTI4fQ==', 1790264083),
('NOkWZUwHZsDJUrYehEJaQyZsCbwTSluOYrrniacU', 128, '114.79.49.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/153.0.8010.24 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJGd0tqOWRUVmVIblE4ZldzTEhWeXM0MUxXR05OQkVWTmZ1OXZFbVlZIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3NwZW5kYWJsZS1wb3J0YWwtb3ZlcmV4ZXJ0Lm5ncm9rLWZyZWUuZGV2XC9ib29raW5nIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwczpcL1wvc3BlbmRhYmxlLXBvcnRhbC1vdmVyZXhlcnQubmdyb2stZnJlZS5kZXZcL25vdGlmaWNhdGlvbnMiLCJyb3V0ZSI6Im5vdGlmaWNhdGlvbnMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTI4fQ==', 1790259358),
('pZgSI1bjxvWaUwN8aYca4c1hbDaljjYoMDNjQsKa', 1, '114.79.49.33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJybmJveENwU0RnZ3NQd0JPNm96NERONXFTVVhHNlZPbkJ6cXlFUll0IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3NwZW5kYWJsZS1wb3J0YWwtb3ZlcmV4ZXJ0Lm5ncm9rLWZyZWUuZGV2XC9ub3RpZmljYXRpb25zXC9zdHJlYW0iLCJyb3V0ZSI6Im5vdGlmaWNhdGlvbnMuc3RyZWFtIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1790266555),
('YGlxgrOvrquiMEMrL49KzvZTHyTXvQdgaccdsvsw', 128, '114.79.49.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJ3Y1I1TFM1Q1h6ZWgxZ2c1bGd6blNjcWVVR0dES0dLY0N4TlhhaWNMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9zcGVuZGFibGUtcG9ydGFsLW92ZXJleGVydC5uZ3Jvay1mcmVlLmRldlwvbm90aWZpY2F0aW9uc1wvc3RyZWFtIiwicm91dGUiOiJub3RpZmljYXRpb25zLnN0cmVhbSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sInVybCI6eyJpbnRlbmRlZCI6Imh0dHBzOlwvXC9zcGVuZGFibGUtcG9ydGFsLW92ZXJleGVydC5uZ3Jvay1mcmVlLmRldlwvYm9va2luZyJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTI4fQ==', 1790259465);

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `bidang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `profile_photo`, `nip`, `whatsapp`, `email_verified_at`, `password`, `role`, `bidang`, `jabatan`, `golongan`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `status`) VALUES
(1, 'Admin Bapelit', 'admin', 'admiiiiiiiin@gmail.com', 'profile-photos/vwNsm6mrLLpm20DYn9eyQNQ9ByhuKuSNOCgZOEjW.jpg', NULL, NULL, NULL, '$2y$12$NBPZiUguvnMzP7xXpD9mPOVjXLpaMGlIdkAIiXp9LH8dbMBmZ1AV2', 'admin', NULL, 'hengker', NULL, NULL, '2026-09-04 13:59:54', '2026-09-24 07:14:44', 1, 'aktif'),
(4, 'Ikbal Maulana', 'ikbal', NULL, 'profile-photos/IwtOwet9ke68DfZvoqAefNTJP0M1a2IZfeZLvhlr.jpg', NULL, NULL, NULL, '$2y$12$RwFlRrVNZm/LlhqArozrsudx4ytpwBHfBwb6hnvvYLA8uTytaZwqm', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-10 07:59:30', '2026-09-24 08:07:00', 0, 'aktif'),
(66, 'Drs. Rahayu Jamiat Abdullah, S.Sos., M.Si.', 'rahayu', 'rahayu@gmail.com', NULL, '19690718 198903 1 005', NULL, NULL, '$2y$12$hZFkwILlH4I2avbDFVK43.azandL5Sx3cxpaaabCxkIOpkWyzZlvq', 'user', 'KEPALA BADAN', 'Kepala Badan', 'IV/c', NULL, '2026-09-14 07:45:18', '2026-09-24 12:52:38', 0, 'aktif'),
(67, 'Teguh Nugraha, S.T., M.M.', 'eguh', 'eguh@gmail.com', NULL, '19771130 200501 1 010', NULL, NULL, '$2y$12$bn3dzjXtkgVxF39E/yvaG.h4p2rQDo5wvrx74VX3kdoM2hno.zLKW', 'user', 'SEKRETARIAT', 'Sekretaris', 'IV/b', NULL, '2026-09-14 07:45:18', '2026-09-14 07:45:18', 0, 'aktif'),
(68, 'Yuliani, S.IP.', 'uliani', 'uliani@gmail.com', NULL, '19780712 200701 2 016', NULL, NULL, '$2y$12$EPfUZTURPy2k4ZiPXMZmj.oYsbh8NayLPcLRwQFSlkjo07AY66rYa', 'user', 'SEKRETARIAT', 'Kasubag Umum dan Kepegawaian', 'III/d', NULL, '2026-09-14 07:45:19', '2026-09-14 07:45:19', 0, 'aktif'),
(69, 'Ratna Sari Aisyah, S.E.', 'atna', 'atna@gmail.com', NULL, '19870302 200901 2 001', NULL, NULL, '$2y$12$9mhr.y6hfPI.eFP.h5ZeUOfb3NID1WJh7/TUro8iUSoJCl7LJcWKG', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:19', '2026-09-14 07:45:19', 0, 'aktif'),
(70, 'Rika Rakanita, S.AP.', 'ika', 'ika@gmail.com', NULL, '19910801 201903 2 006', NULL, NULL, '$2y$12$oiYXhfr3XoQFMd2AyIoG9.bkUKBcWVgk1bcredGPtAFW87dC33zn2', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:20', '2026-09-14 12:50:16', 0, 'aktif'),
(71, 'Hj. Puji Priyanti Utami Dewi', 'uji', 'uji@gmail.com', NULL, '19750324 200701 2 004', NULL, NULL, '$2y$12$VHiMb1Cpl4Iobdr8CZ1NWeUjUM51QBZ8ENP8uYOBOjy87lJq.tA9W', 'user', 'SEKRETARIAT', 'Pengadministrasi Perkantoran', 'III/a', NULL, '2026-09-14 07:45:20', '2026-09-14 07:45:20', 0, 'aktif'),
(72, 'Ade Hendra Suhendar', 'de', 'de@gmail.com', NULL, '19790820 202521 1 068', NULL, NULL, '$2y$12$snT.cAgu48cDRHzf/0L6neHfEAbZCnf097SUjMk.r2sK/M8fPVHbe', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:20', '2026-09-14 07:45:20', 0, 'aktif'),
(73, 'Mohamad Yusup', 'ohamad', 'ohamad@gmail.com', NULL, '19701208 202521 1 024', NULL, NULL, '$2y$12$M.9yk87BnnMdYKFVITWlBuPvSpVomOTlthBc1zdbI6bTWhja.EQbC', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(74, 'Anita Rohmat, S.P., M.Si.', 'nita', 'nita@gmail.com', NULL, '19760502 201410 2 001', NULL, NULL, '$2y$12$UxwVuOgAsmhJCcaufEbKeumbvFUye.1z4VpjYwpmWQtTOoiCXe0.S', 'user', 'SEKRETARIAT', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(75, 'Andri Herdiana, S.IP.', 'ndri', 'ndri@gmail.com', NULL, '19810525 201001 1 005', NULL, NULL, '$2y$12$rTUCGWXm2ay/X6eEJ8Uk9eRroSzs3kB1shiHQrNgqrxxEfqrihCxG', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/d', NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(76, 'Anton Watoni, S.AP', 'nton', 'nton@gmail.com', NULL, '19930521 201903 1 005', NULL, NULL, '$2y$12$5.L5lbzAWQLG0GD0VPSwmulheMyfxv/kdnhzQSrnlRBdmVQj779SS', 'user', 'SEKRETARIAT', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(77, 'Fachrul Septian Dwiputra, S.M.', 'achrul', 'achrul@gmail.com', NULL, '19930921202421 1 018', NULL, NULL, '$2y$12$FAg95476AMMdruUAP3dcxetvMJGRJiMERLwJ38C60I4y3KZCi4eyS', 'user', 'SEKRETARIAT', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(78, 'Irfan Faizal', 'rfan', 'rfan@gmail.com', NULL, '19890610 202521 1 151', NULL, NULL, '$2y$12$WQzwfJz9Aj2YqhgDQ33pcuT9SANnouw6.fUeHOJEKG/oGjTHnRM3G', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(79, 'Rohimah, S.Si.', 'ohimah', 'ohimah@gmail.com', NULL, '19870216 201001 2 008', NULL, NULL, '$2y$12$bg9B7k7dxSfc2xSiKWCy1OGSP1LBbpePInr.EVlQVMFYJ7dye/VdO', 'user', 'SEKRETARIAT', 'Kasubag Keuangan', 'III/d', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(80, 'Adam Nugraha, S.IP.', 'dam', 'dam@gmail.com', NULL, '19841014 201001 1 002', NULL, NULL, '$2y$12$6TRzepQnXbvcV9xYDD5XzuO4w/6jSET0qqKmy7ESID9LZNhbRfDVy', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(81, 'Dede Sutedi, S.IP.', 'ede', 'ede@gmail.com', NULL, '19690425 200701 1 007', NULL, NULL, '$2y$12$yowc6hPHn1YX4OWw8cQX/uzsh8UQ3f5CQRzFYBtGsHAlavAQCGafS', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(82, 'Ade Supriatna, S.Sos.', 'de2', 'de2@gmail.com', NULL, '19700829 200701 1 007', NULL, NULL, '$2y$12$KtA7vzYD7XFUF2KM8.ViHe7h.KUlOXaynJF7Mj9z7NyliYiPl79si', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(83, 'Rony Setiawan, S.H.', 'ony', 'ony@gmail.com', NULL, '19760524 202521 1 044', NULL, NULL, '$2y$12$tnFikWFFMwS2.zrIjAB2ve2o1P8eoHxC4qHUvCc.g5qkgvEOLd/FG', 'user', 'SEKRETARIAT', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(84, 'Resna Nika Febriyanty, S.Kom., M.Si.', 'esna', 'esna@gmail.com', NULL, '19800227 200801 2 002', NULL, NULL, '$2y$12$hXqHrKVC/aYBaHTBwI5hYeNa0dJ44d8OXR3RBBIlDxFqFiVS7z.3a', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Kepala Bidang Perekonomian dan SDA', 'III/d', NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(85, 'Dzikri Miftahul Huda, S.AP', 'zikri', 'zikri@gmail.com', NULL, '19960309 201903 1 001', NULL, NULL, '$2y$12$qzebBDnVNhpMZHcyA7sLM.YJzG2BqCIGxrouGOeuxjefCEKoba1bW', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:25', '2026-09-14 07:45:25', 0, 'aktif'),
(86, 'Dani Nurdiana, S.E.', 'ani', 'ani@gmail.com', NULL, '19840108 201503 1 002', NULL, NULL, '$2y$12$6WyDMK3f8cHiYoNMgzU.JOziuMeDMjorbB2RhfGQis6MFGyGPUr0m', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:25', '2026-09-14 07:45:25', 0, 'aktif'),
(87, 'Eka Surtika, S.IP', 'ka', 'ka@gmail.com', NULL, '19780105 200701 2 007', NULL, NULL, '$2y$12$WJiv8EbJDHix8O1hQhNBV.X6wE1llqn.8WPZH3dMST00XxtrgIctO', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Pengolah Data dan Informasi', 'III/c', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(88, 'Satya Laksana, S.P., M.E., M.P.P.', 'atya', 'atya@gmail.com', NULL, '19790530 200604 1 004', NULL, NULL, '$2y$12$yvtpOJ7aaumF3omTqWp8ZepmVY0XJoUJBpcaYk8w9soaR9yebHvzm', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(89, 'Rina Ridiawati, S.Sos., M.Si.', 'ina', 'ina@gmail.com', NULL, '19840328 201410 2 001', NULL, NULL, '$2y$12$5LMixRyDr68q/B5ntdihgeIi6OCzzAEhuiPmdOoxrdA1LnKGdI/Ti', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(90, 'Hapidulloh Zen, S.T.', 'apidulloh', 'apidulloh@gmail.com', NULL, '19920903 202421 1 023', NULL, NULL, '$2y$12$hqk0dNJsGRbAtMkGClAL2ObXM5GGDkGdRkfolSAaiT1vvZrFNwNv.', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(91, 'Erwin Faizal Lesmana, S.Pd.', 'rwin', 'rwin@gmail.com', NULL, '19910812 202521 1 111', NULL, NULL, '$2y$12$PDPrT/6.lsZC.yfor8YQZuhfZWxN0Is9RJtLbG.TS7JsfBBSYjrf6', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(92, 'Jani Maulana, S.Sos., M.Si.', 'ani2', 'ani2@gmail.com', NULL, '19800125 200901 1 005', NULL, NULL, '$2y$12$SJcAuQt2jSNvu99uVJ9UTumWbI58AyuES/YcuVPBgvezGn0s74pTa', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Kepala Bidang Infrastruktur dan Kewilayahan', 'IV/a', NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(93, 'Haries Nursyamsu, S.H., M.H.', 'aries', 'aries@gmail.com', NULL, '19790101 201001 1 002', NULL, NULL, '$2y$12$IKGPvw5ALlH27Gg7PloUT.z4.RPLANAdrEd78szcobVAYVjNUPjMC', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(94, 'Windi Rahmikawati, S.Sos.', 'indi', 'indi@gmail.com', NULL, '19820930 201410 2 001', NULL, NULL, '$2y$12$We3I83v/uZZI.DvV56tZEujB8tutMp.WPYrtnWxxn4YiEyrfN24gK', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(95, 'Yeko Anugrah Januar, S.T.,M.T', 'eko', 'eko@gmail.com', NULL, '19980122 202203 1 001', NULL, NULL, '$2y$12$2kByro02ybLG57vLX0WwDOBO37euc7fNZ4/fYMcsRF4Gk41L1qc7m', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(96, 'Irma Nurhalimah Rizqi, S.T.', 'rma', 'rma@gmail.com', NULL, '19910806 202505 2 001', NULL, NULL, '$2y$12$OAiXnLhzkQZFgxbyf7Y24e5hI6BBzXfdwJS.OS.yluOf/8E8e0pj2', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'III/a', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(97, 'Ardita Puja Rahmani, S.P.W.K.', 'rdita', 'rdita@gmail.com', NULL, '20020314 202505 2 003', NULL, NULL, '$2y$12$kWCUwiKaVkBiBmj5RKNhbeogn4hK9IvXtN3qpBGV2cVeO68iBFYay', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'III/a', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(98, 'Euis Yunan, S.T.', 'uis', 'uis@gmail.com', NULL, '19950522 202421 2 029', NULL, NULL, '$2y$12$/FAPKuPRAkjwPcz6eFnlLuighxaZjuwqgNWk1qEQKf0SgqKBp4jhC', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(99, 'Rizki Muhibudin, A.Md.T.', 'izki', 'izki@gmail.com', NULL, '19910820 202521 1 132', NULL, NULL, '$2y$12$gQKB.dzSQEmRU7.9C5Q6meEPLE3ePbB0PFpxcD/xc.s7V2RN6wzFi', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Pengelola Layanan Operasional', NULL, NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(100, 'Sena Adikrisna, S.H., M.H.', 'ena', 'ena@gmail.com', NULL, '19860718 201101 2 006', NULL, NULL, '$2y$12$5dJM.YqaHqhvGU7wchTJn.4psMlykSSnDr4LCPmKGR6zCr9gxVAYW', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Kabid Pemerintahan dan Pembangunan Manusia', 'III/d', NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(101, 'Kania Dewi Utami, S.I.Kom.', 'ania', 'ania@gmail.com', NULL, '19900909 201503 2 001', NULL, NULL, '$2y$12$aCHWRnC8tV5Erhzh8/S4geWfcdZOkvsnthDh4UEcoZUtlof/HZBti', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(102, 'Gian Jatnika Munggaran, S.Kom', 'ian', 'ian@gmail.com', NULL, '19830605 200501 1 003', NULL, NULL, '$2y$12$f5LZce5.D12.8IM7Yy1D.eoD9JRo.JgfV54dNH2Is35xynzoNRmJu', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'III/d', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(103, 'Intan Ayu Hardiyanti, S.E.', 'ntan', 'ntan@gmail.com', NULL, '19970507 202203 2 001', NULL, NULL, '$2y$12$jCE91aPrqPdtqMag8Ow.T./HRObwrhM4ramA4IdZhGyorJy8eXtMm', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(104, 'Shinta Lestari, S.Tr.IP.', 'hinta', 'hinta@gmail.com', NULL, '19991212 202208 2 002', NULL, NULL, '$2y$12$cP7lXZOBQzBDEbJ.ZP40Q.BirCrEmI/GnffRyHdjn5iSOp9bLJA5K', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(105, 'Dewi Shintya A', 'ewi', 'ewi@gmail.com', NULL, '19760202 200701 2 011', NULL, NULL, '$2y$12$L/LR9m0/l47.lWDQU0m.eenhN1FFA4sfv8BNuZ/DbOKndXh2JtPae', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Pengolah Data dan Informasi', 'III/a', NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(106, 'Nida Fauziyyah Sambas, S.Mat.', 'ida', 'ida@gmail.com', NULL, '19960330 202421 2 033', NULL, NULL, '$2y$12$8iSAwtUugLwVrvq.yQjSMusDpytJe7aEGyOgJvfw8n33RB78fVsw6', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(107, 'Mohammad Fazar Ruslan Setia', 'ohammad', 'ohammad@gmail.com', NULL, '19950419 202521 1 091', NULL, NULL, '$2y$12$HQ3QBTfGxOJpbqBAQlCRROIfEBz5QkINFL/BOBWX2WXW/OvZ7NG4q', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(108, 'Ine Susyane, S.T.', 'ne', 'ne@gmail.com', NULL, '19781103 200604 2 001', NULL, NULL, '$2y$12$Rsie7ruP2EhwL2niT4UPD.PZfFUg/OWJOs.nlnHtv.QFiQb2seS9G', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Kabid Perencanaan Pengendalian dan Evaluasi', 'IV/a', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(109, 'Sri Yulia, S.Sos., M.M.', 'ri', 'ri@gmail.com', NULL, '19690902 199403 2 002', NULL, NULL, '$2y$12$iwp1rB70JsvR.xmaXyuis.7N5M180cLTM1V74DGTybqgH.r9ZBI6W', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Muda', 'IV/a', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(110, 'Kania Dewi Kinasih, S.E.', 'ania2', 'ania2@gmail.com', NULL, '19980618 202203 2 001', NULL, NULL, '$2y$12$rLl3i04Xz1bqWJgYsJjW4OAJRDBFZHIYVaAGiAZeWZQFK7Ikh1nUG', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(111, 'Asep Wahyudin, S.IP', 'sep', 'sep@gmail.com', NULL, '19730409 200501 1 004', NULL, NULL, '$2y$12$JLBz1.uUIBxTIZzDKu48AO26xME5yweuGMoppn5ZmA3pLvTTHaaiq', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Pengolah Data dan Informasi', 'III/c', NULL, '2026-09-14 07:45:34', '2026-09-14 07:45:34', 0, 'aktif'),
(112, 'Mona Febriyanti, S.Ak.', 'ona', 'ona@gmail.com', NULL, '19980225 202203 2 001', NULL, NULL, '$2y$12$ECiBE.N7wLCChPbiVbH83.AI0AnHdU4Rg5DO90Yokzo7LiSeQpnTm', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:34', '2026-09-14 07:45:34', 0, 'aktif'),
(113, 'Dena Tri Lestary, S.M.', 'ena2', 'ena2@gmail.com', NULL, '19940621 201503 2 001', NULL, NULL, '$2y$12$HHzmcXGMf7bWLHc0PnjoZeOx/5G8WW7yisE9yehoYcqJV60zEwvwm', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(114, 'Rizqa Fauziyyah Nabila, S.Kom.', 'izqa', 'izqa@gmail.com', NULL, '20000227 202505 2 002', NULL, NULL, '$2y$12$0TlqvsmZzO7AksMGHx/uVOlwZPxF5q6ld3E08xVGk9GfhMhWFxeRy', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Penata Kelola Sistem dan Teknologi Informasi', 'III/a', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(115, 'Yadi Mulyadi, S.Sos.', 'adi', 'adi@gmail.com', NULL, '19820116 202421 1 009', NULL, NULL, '$2y$12$DrRHPgxT0jdNIdEV4qVNYufSKlQkrQxG0t7sZJqC3KrhqvRH9hCO.', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(116, 'Rifqi Widyan Ramadhan, S.T', 'ifqi', 'ifqi@gmail.com', NULL, '19940218 202521 1 083', NULL, NULL, '$2y$12$ON8RccJ8tMCuM.kBioTuy.BDS1qSI99WZjnrIsRlryf22Opgk0tn6', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(117, 'Bayu Wijaksana, S.T., M.Si.', 'ayu', 'ayu@gmail.com', NULL, '19820805 200902 1 004', NULL, NULL, '$2y$12$MAAmPSBSZWpHcKE7NC/gP.DKAnghFQeTBGxwr4Jv4JJA2kEfFC3R2', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Kabid Penelitian dan Pengembangan', 'IV/a', NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(118, 'Agi Nurhidayah, S.Si., M.T.', 'gi', 'gi@gmail.com', NULL, '19820807 200701 1 007', NULL, NULL, '$2y$12$f3jNJ65EOs4qXPeGRNnLLelgjOs6RqJBhmYWXC47mxwch44OQ8zrO', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Peneliti Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(119, 'Akbar Bhahesti, S.E., M.A.B.', 'kbar', 'kbar@gmail.com', NULL, '19910926 201903 1 003', NULL, NULL, '$2y$12$SBCpDeZN8DV7N91gq7IinO84u5qdiY69cVQpho1ScKGawkq/fpSGy', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(120, 'Imat Rohimat, S.IP', 'mat', 'mat@gmail.com', NULL, '19810503 200901 1 004', NULL, NULL, '$2y$12$uAxeFaA152vm9B9SB8OH3ONf5MNehTmZlkSvnSlzwuQSn1c4fGfha', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/d', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(121, 'Dadan Sunandar, S.Si.', 'adan', 'adan@gmail.com', NULL, '19810416 200902 1 002', NULL, NULL, '$2y$12$T..YbvbF/h5.mmWHCkv0SOy5Gh9I40mBFatk25CM6O6J.bvo0aFde', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Kasubid Statistik', 'III/d', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(122, 'Nurul Hikmah, S.Stat., M.I.L.', 'urul', 'urul@gmail.com', NULL, '19960408 201903 2 006', NULL, NULL, '$2y$12$mgSymM3cuEDatjp4A/mDXeWXVBsHLAPLo64o1pnGtT117rDx25OXS', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(123, 'Wildan Nugraha, S.T', 'ildan', 'ildan@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$A32EgWsFBxQ05BxFd9uW7.y9/gvMEjlhbnL894JgAySkaDyzsB.4a', 'user', 'IT', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(124, 'Muhammad Ridwan, S.Kom', 'uhammad', 'uhammad@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$JRCFYPEtNkmrQfrAreXmVO6MF.uTWlvfimmdowR9Hb5QwDmy3vCzW', 'user', 'IT', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(125, 'Febi Robiana Suherman, S.Kom', 'ebi', 'ebi@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$YXd90JI1Ws94BmKmmKJnNOxnIHOi/jIPt1inAuk/8ItNVPh/PoASq', 'user', 'Bidang PSDA', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:39', '2026-09-14 07:52:33', 0, 'aktif'),
(126, 'Sandria Anggra Sutardi, S.T', 'andria', 'andria@gmail.com', NULL, NULL, NULL, NULL, '$2y$12$ESaCVrJnIO9LxvC7Nb1A9uiuUlEa5VcNUod/iNIGrcC1vng19t64K', 'user', 'Bidang Litbang', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:39', '2026-09-14 07:52:49', 0, 'aktif'),
(127, 'leo', 'leo', NULL, NULL, NULL, NULL, NULL, '$2y$12$RKOXNMWJNMEEchWsyzw7Ke0TsXhsHQ3moYNAqIFlUcDQc4LhZfgRm', 'admin', NULL, NULL, NULL, NULL, '2026-09-15 02:32:53', '2026-09-18 01:49:52', 1, 'aktif'),
(128, 'test 2', 'test2', NULL, 'profile-photos/qZzU3KFl82Lh4NaBFa2wc63Zpzu4hXgg4GWSXSoS.png', NULL, NULL, NULL, '$2y$12$qONEG5D0nJ84AFUSBzuEl.pX.54JmPpPlbnJojV/ICtcBxf/v/NJy', 'admin', NULL, NULL, NULL, '320wzF1mUDx8FJ6yP5WX8wtj7FWiPWhHgFa3wGMgTD4gPBdc38F1oNJIDdr5', '2026-09-17 01:47:56', '2026-09-24 15:31:08', 1, 'aktif'),
(129, 'Risa Fahmawati', 'risa', NULL, NULL, NULL, '0822-1086-6182', NULL, '$2y$12$vC67dF/UJEb49Hxd0B0nmer.lCoZqu2VVl7QUOnmeLEjBp.ZH8jJq', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-18 01:56:23', '2026-09-18 01:56:23', 0, 'aktif'),
(130, 'Farhan Fauzy', 'farhan', NULL, NULL, NULL, NULL, NULL, '$2y$12$jszoNPcvbnugVN7ji9EIwuEV/jRh2csdmLP6VKXDom5miTGO50ipC', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-18 01:57:54', '2026-09-18 01:57:54', 0, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `users_backup`
--

CREATE TABLE `users_backup` (
  `id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `bidang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `golongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_backup`
--

INSERT INTO `users_backup` (`id`, `name`, `username`, `email`, `nip`, `whatsapp`, `email_verified_at`, `password`, `role`, `bidang`, `jabatan`, `golongan`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `status`) VALUES
(1, 'Admin Bapelit', 'admin', 'admin@gmail.com', NULL, NULL, NULL, '$2y$12$nEEIVdLDsixbYYDA.vbI1u4aD4xBlQd3noEq/Fsox/GN8PZ81kAuK', 'admin', NULL, NULL, NULL, NULL, '2026-09-04 13:59:54', '2026-09-04 13:59:54', 0, 'aktif'),
(4, 'ikbal maulana', 'ikbal', 'anjay@gmail.com', NULL, NULL, NULL, '$2y$12$qlzVWBFzJ15PFIV53SEW3eEu.LfFo3dR7BvNK1hHvZVlsXdFerKQW', 'admin', 'Umpeg', NULL, NULL, NULL, '2026-09-10 07:59:30', '2026-09-14 04:44:58', 1, 'aktif');

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
  ADD UNIQUE KEY `absensi_sesi_token_qr_unique` (`token_qr`),
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
  ADD KEY `bookings_aula_id_foreign` (`aula_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_tanggal_booking` (`tanggal_booking`),
  ADD KEY `idx_bookings_created_at` (`created_at`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `idx_notifications_user_id_id` (`user_id`,`id`),
  ADD KEY `idx_notifications_read_at` (`read_at`);

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
-- Indexes for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `push_subscriptions_endpoint_unique` (`endpoint`),
  ADD KEY `push_subscriptions_subscribable_morph_idx` (`subscribable_type`,`subscribable_id`);

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
  ADD KEY `surats_user_id_foreign` (`user_id`),
  ADD KEY `idx_surats_no_surat` (`no_surat`),
  ADD KEY `idx_surats_no_indek` (`no_indek`),
  ADD KEY `idx_surats_created_at` (`created_at`),
  ADD KEY `idx_surats_created_month_year` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_detail`
--
ALTER TABLE `absensi_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1549;

--
-- AUTO_INCREMENT for table `absensi_sesi`
--
ALTER TABLE `absensi_sesi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `pengaturan_surats`
--
ALTER TABLE `pengaturan_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `push_subscriptions`
--
ALTER TABLE `push_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `surats`
--
ALTER TABLE `surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surats`
--
ALTER TABLE `surats`
  ADD CONSTRAINT `surats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
