-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 18, 2026 at 07:33 AM
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
(5, 2, 1, 'tidak', NULL, '2026-09-14 04:29:40', '2026-09-14 02:35:19', '2026-09-14 04:29:40'),
(8, 2, 4, 'tidak', NULL, '2026-09-14 04:29:40', '2026-09-14 02:35:19', '2026-09-14 04:29:40'),
(856, 22, 1, NULL, NULL, NULL, '2026-09-18 01:51:46', '2026-09-18 01:51:46'),
(857, 22, 4, 'hadir', NULL, '2026-09-18 02:01:31', '2026-09-18 01:51:46', '2026-09-18 02:01:31'),
(858, 22, 66, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(859, 22, 67, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(860, 22, 68, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(861, 22, 69, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(862, 22, 70, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(863, 22, 71, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(864, 22, 72, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(865, 22, 73, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(866, 22, 74, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(867, 22, 75, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(868, 22, 76, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(869, 22, 77, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(870, 22, 78, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(871, 22, 79, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(872, 22, 80, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(873, 22, 81, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(874, 22, 82, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(875, 22, 83, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(876, 22, 84, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(877, 22, 85, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(878, 22, 86, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(879, 22, 87, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(880, 22, 88, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(881, 22, 89, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(882, 22, 90, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(883, 22, 91, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(884, 22, 92, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(885, 22, 93, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(886, 22, 94, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(887, 22, 95, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(888, 22, 96, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(889, 22, 97, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(890, 22, 98, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(891, 22, 99, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(892, 22, 100, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(893, 22, 101, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(894, 22, 102, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(895, 22, 103, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(896, 22, 104, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(897, 22, 105, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(898, 22, 106, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(899, 22, 107, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(900, 22, 108, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(901, 22, 109, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(902, 22, 110, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(903, 22, 111, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(904, 22, 112, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(905, 22, 113, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(906, 22, 114, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(907, 22, 115, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(908, 22, 116, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(909, 22, 117, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(910, 22, 118, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(911, 22, 119, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(912, 22, 120, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(913, 22, 121, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(914, 22, 122, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(915, 22, 123, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(916, 22, 124, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(917, 22, 125, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(918, 22, 126, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(919, 22, 127, NULL, NULL, NULL, '2026-09-18 01:51:47', '2026-09-18 01:51:47'),
(920, 22, 128, 'hadir', NULL, '2026-09-18 01:55:34', '2026-09-18 01:51:47', '2026-09-18 01:55:34'),
(921, 22, 129, 'hadir', NULL, '2026-09-18 02:08:17', '2026-09-18 02:08:17', '2026-09-18 02:08:17'),
(922, 22, 130, 'hadir', NULL, '2026-09-18 02:09:55', '2026-09-18 02:09:55', '2026-09-18 02:09:55');

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
(2, 'Sesi Ice Breaking', '2026-09-14', 'Aula Utama', 'Sesi perkenalan dan ice breaking harian.', 1, 1, NULL, NULL, 60, 0, NULL, '2026-09-14 02:35:19', '2026-09-14 04:29:44'),
(22, 'Kehadiran Umpeg tgl 18/09/2026', '2026-09-18', '📍Dibawah', NULL, 0, 0, 'iFan6vJNZ8QjcEBpp6usAkbVfDCyZEIZQIw5XKE5t4toyJzV', '2026-09-18 01:51:46', 0, 0, 127, '2026-09-18 01:51:46', '2026-09-18 01:51:46');

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
(1, 'Aula Wiradadahaa', 100, 'Aula utama Bappetibangda, cocok untuk rapat koordinasi besar, sosialisasi program, dan acara seremonial.', 'jaga kebersihan', '📍Dibawah', '[\"uploads/aulas/39b0adcf-a853-4890-8a4a-41f177983bb0.jpeg\"]', '[\"ac\", \"kursi\", \"tv\"]', 1, '2026-09-07 21:20:21', '2026-09-18 01:45:52'),
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
(23, 1, 1, '2026-09-25', 'Admin Bapelit', 'a', 1, 'siang', 'approved', '2026-09-17 07:09:15', '2026-09-17 07:09:25'),
(24, 127, 1, '2026-09-18', 'leo', 'pemilihan calon presiden 2030', 5, 'seharian', 'approved', '2026-09-18 01:47:44', '2026-09-18 01:48:13'),
(25, 128, 1, '2026-09-21', 'test 2', 'bh', 1, 'siang', 'approved', '2026-09-18 01:54:22', '2026-09-18 02:29:54'),
(26, 4, 1, '2026-09-28', 'Ikbal Maulana', 'pemilihan calon presiden 2040', 60, 'seharian', 'approved', '2026-09-18 02:29:27', '2026-09-18 02:32:19'),
(27, 128, 2, '2026-09-23', 'test 2', 'ss', 1, 'siang', 'approved', '2026-09-18 03:15:06', '2026-09-18 03:29:28'),
(28, 128, 3, '2026-09-23', 'test 2', 's', 1, 'seharian', 'approved', '2026-09-18 03:15:28', '2026-09-18 03:25:22'),
(29, 128, 3, '2026-09-25', 'test 2', 'a', 1, 'siang', 'completed', '2026-09-18 03:20:35', '2026-09-18 03:25:18'),
(30, 128, 2, '2026-09-19', 'test 2', 'er', 1, 'siang', 'approved', '2026-09-18 03:29:52', '2026-09-18 03:30:00'),
(31, 128, 2, '2026-09-21', 'test 2', 'i', 1, 'pagi', 'pending', '2026-09-18 06:45:54', '2026-09-18 06:45:54'),
(32, 128, 3, '2026-09-21', 'test 2', 's', 1, 'siang', 'pending', '2026-09-18 06:46:58', '2026-09-18 06:46:58'),
(33, 128, 1, '2026-09-29', 'test 2', 's', 1, 'pagi', 'pending', '2026-09-18 06:47:25', '2026-09-18 06:47:25');

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
(21, '2026_09_14_092020_create_absensi_detail_table', 11),
(22, '2026_09_14_140959_add_jabatan_golongan_to_users_table', 12),
(23, '2026_09_15_083404_add_qr_columns_to_absensi_sesi_table', 13),
(24, '2026_09_15_085816_change_qr_auto_refresh_default_on_absensi_sesi_table', 14),
(25, '2026_09_15_144224_create_notifications_table', 15),
(26, '2026_09_17_093925_create_push_subscriptions_table', 16),
(27, '2026_09_17_093925_increase_push_subscriptions_endpoint_length', 16);

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
(40, 4, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiradadahaa tanggal 2026-09-28 telah disetujui.', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-28\", \"booking_id\": 26}', 'https://spendable-portal-overexert.ngrok-free.dev/riwayat/26', NULL, '2026-09-18 02:32:19', '2026-09-18 02:32:19'),
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
(53, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-25 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-25\", \"booking_id\": 29}', 'http://pojok-bapelit.test/riwayat/29', NULL, '2026-09-18 03:25:09', '2026-09-18 03:25:09'),
(54, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wirahadinigrat tanggal 2026-09-23 telah disetujui.', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"seharian\", \"status\": \"approved\", \"tanggal\": \"2026-09-23\", \"booking_id\": 28}', 'http://pojok-bapelit.test/riwayat/28', NULL, '2026-09-18 03:25:22', '2026-09-18 03:25:22'),
(55, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-23 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-23\", \"booking_id\": 27}', 'http://pojok-bapelit.test/riwayat/27', NULL, '2026-09-18 03:29:28', '2026-09-18 03:29:28'),
(56, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"19 Sep 2026\", \"keperluan\": \"er\", \"booking_id\": 30}', 'http://pojok-bapelit.test/admin/kelolabooking/30', '2026-09-18 04:11:08', '2026-09-18 03:29:52', '2026-09-18 04:11:08'),
(57, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"19 Sep 2026\", \"keperluan\": \"er\", \"booking_id\": 30}', 'http://pojok-bapelit.test/admin/kelolabooking/30', NULL, '2026-09-18 03:29:52', '2026-09-18 03:29:52'),
(58, 128, 'booking', 'Booking Disetujui!', 'Booking aula Aula Wiratanuningrat tanggal 2026-09-19 telah disetujui.', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"siang\", \"status\": \"approved\", \"tanggal\": \"2026-09-19\", \"booking_id\": 30}', 'http://pojok-bapelit.test/riwayat/30', NULL, '2026-09-18 03:30:00', '2026-09-18 03:30:00'),
(59, 1, 'surat', 'Pengajuan Surat Baru', '6 dari Ikbal Maulana', '{\"jenis\": \"surat magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"6\", \"surat_id\": 13}', 'https://longitude-heights-reputation-establishing.trycloudflare.com/admin/kelolasurat', '2026-09-18 04:11:08', '2026-09-18 03:54:55', '2026-09-18 04:11:08'),
(60, 127, 'surat', 'Pengajuan Surat Baru', '6 dari Ikbal Maulana', '{\"jenis\": \"surat magang\", \"pengaju\": \"Ikbal Maulana\", \"no_surat\": \"6\", \"surat_id\": 13}', 'https://longitude-heights-reputation-establishing.trycloudflare.com/admin/kelolasurat', NULL, '2026-09-18 03:54:55', '2026-09-18 03:54:55'),
(61, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"i\", \"booking_id\": 31}', 'http://pojok-bapelit.test/admin/kelolabooking/31', '2026-09-18 06:57:52', '2026-09-18 06:45:54', '2026-09-18 06:57:52'),
(62, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiratanuningrat', '{\"aula\": \"Aula Wiratanuningrat\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"i\", \"booking_id\": 31}', 'http://pojok-bapelit.test/admin/kelolabooking/31', NULL, '2026-09-18 06:45:54', '2026-09-18 06:45:54'),
(63, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 32}', 'http://pojok-bapelit.test/admin/kelolabooking/32', '2026-09-18 06:57:50', '2026-09-18 06:46:58', '2026-09-18 06:57:50'),
(64, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wirahadinigrat', '{\"aula\": \"Aula Wirahadinigrat\", \"sesi\": \"siang\", \"pengaju\": \"test 2\", \"tanggal\": \"21 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 32}', 'http://pojok-bapelit.test/admin/kelolabooking/32', NULL, '2026-09-18 06:46:58', '2026-09-18 06:46:58'),
(65, 1, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 33}', 'http://pojok-bapelit.test/admin/kelolabooking/33', '2026-09-18 06:48:08', '2026-09-18 06:47:25', '2026-09-18 06:48:08'),
(66, 127, 'booking', 'Booking Baru', 'test 2 booking Aula Aula Wiradadahaa', '{\"aula\": \"Aula Wiradadahaa\", \"sesi\": \"pagi\", \"pengaju\": \"test 2\", \"tanggal\": \"29 Sep 2026\", \"keperluan\": \"s\", \"booking_id\": 33}', 'http://pojok-bapelit.test/admin/kelolabooking/33', NULL, '2026-09-18 06:47:25', '2026-09-18 06:47:25'),
(67, 1, 'surat', 'Pengajuan Surat Baru', '7 dari test 2', '{\"jenis\": \"ha\", \"pengaju\": \"test 2\", \"no_surat\": \"7\", \"surat_id\": 14}', 'http://pojok-bapelit.test/admin/kelolasurat', '2026-09-18 07:21:38', '2026-09-18 06:58:17', '2026-09-18 07:21:38'),
(68, 127, 'surat', 'Pengajuan Surat Baru', '7 dari test 2', '{\"jenis\": \"ha\", \"pengaju\": \"test 2\", \"no_surat\": \"7\", \"surat_id\": 14}', 'http://pojok-bapelit.test/admin/kelolasurat', NULL, '2026-09-18 06:58:17', '2026-09-18 06:58:17');

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
(1, 'BAPELIT', 7, '{nomor}', '2026-09-09 23:37:51', '2026-09-18 06:58:17');

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
('dbKEIvdTq3Xf7dwkF4tlOph0xUmstTB3Mr8uLU9h', 128, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:156.0) Gecko/20100101 Firefox/156.0', 'eyJfdG9rZW4iOiJEdzZCbDJXWG5JaHVpdGczNXRBWHJKdVIxVEZJUWh0d0xwY290ckJjIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvcG9qb2stYmFwZWxpdC50ZXN0XC9zdXJhdCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvcG9qb2stYmFwZWxpdC50ZXN0XC9ub3RpZmljYXRpb25zXC9zdHJlYW0/bmdyb2stc2tpcC1icm93c2VyLXdhcm5pbmc9dHJ1ZSIsInJvdXRlIjoibm90aWZpY2F0aW9ucy5zdHJlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTI4fQ==', 1789714698),
('g0ZR2NFDFSzvNXDSxocyPsGcJ4n0uonluUGT0GUN', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXRmM5U1lOeGdkZHhJVkNhbG5LdVBWeFE4UWRwYzBRUXJpYzVuOURoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ib29raW5nIiwicm91dGUiOiJib29raW5nLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1789713805),
('U94zDEucjR9kBRwdTKUTm0T7RLy3Yl4M4haeORVG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.138.0 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36', 'eyJfdG9rZW4iOiJuZm1MbWdxcEVKSUJLc3VWRk5hVTZ1RXcwTndCUG1jOGNmYW94azNUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYm9va2luZyJ9fQ==', 1789712953),
('XkDWFGLXpKeyRr1yWcvqDuYT5siz7XCCA61wrNog', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3dWFLbUREendJMmpoejdZaEJCa1plSXhDTldJelBNanJmQVJrRTdoIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvcG9qb2stYmFwZWxpdC50ZXN0XC9zdXJhdCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvcG9qb2stYmFwZWxpdC50ZXN0XC9ub3RpZmljYXRpb25zXC9zdHJlYW0/bmdyb2stc2tpcC1icm93c2VyLXdhcm5pbmc9dHJ1ZSIsInJvdXRlIjoibm90aWZpY2F0aW9ucy5zdHJlYW0ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1789716577);

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
(5, 4, '2362398', '2026-09-15', NULL, NULL, 'Izin konser', 0, 'Pribadi', NULL, 'Surat izin karaoke', NULL, 'uploads/surat/f105c3c8-7693-45c9-8fa5-3cbb001ad98c.jpg', 'IMG_20250513_064136_077.jpg', '2026-09-15 02:43:47', '2026-09-15 02:43:47'),
(6, 4, '2362399', '2026-09-15', NULL, 'Abi', 'Bismillahirrahmanirrahim', 0, 'Pribadi', NULL, 'Sertifikat', NULL, 'uploads/surat/ba1d080c-5209-4d22-839a-f7e59db7bcaf.pdf', '__Sertifikat_ADSE x UTEM_Ikbal Maulana__.pdf', '2026-09-15 02:45:50', '2026-09-15 02:45:50'),
(7, 4, '2362400', '2026-09-15', 'hsu', 'aj', 'jdj', 0, 'te', NULL, 'tes', NULL, 'uploads/surat/10599f62-5293-4c63-9e33-ca168be6bb11.pdf', 'projek uas_psi_farhan_fauzy_.pdf', '2026-09-15 02:48:14', '2026-09-15 02:48:14'),
(8, 4, '1', '2026-09-17', '2', '2d', 'd', 0, NULL, NULL, NULL, NULL, 'uploads/surat/5d490ff5-e005-43b8-9696-0a73cf8a5d0b.pdf', 'hasbipbi.pdf', '2026-09-17 01:10:36', '2026-09-17 01:10:36'),
(9, 128, '2', '2026-09-18', 'ssss', 's', 'ss', 0, NULL, NULL, NULL, NULL, 'uploads/surat/d2890fea-d04e-4b4f-ab11-be73f4b377dc.pdf', '__Sertifikat_ADSE x UTEM_Ikbal Maulana__.pdf', '2026-09-18 03:21:46', '2026-09-18 03:21:46'),
(10, 128, '3', '2026-09-18', 'ssss', 's', 'ss', 0, NULL, NULL, NULL, NULL, 'uploads/surat/cd11e1bd-17d9-4c66-844b-5ee0c4e1191d.pdf', '__Sertifikat_ADSE x UTEM_Ikbal Maulana__.pdf', '2026-09-18 03:23:35', '2026-09-18 03:23:35'),
(11, 128, '4', '2026-09-18', 'ee', '2e2', 'sse', 0, 's', NULL, 'ass', NULL, 'uploads/surat/a3350291-a368-44a1-9cc6-d6f1b4b6b700.pdf', 'projek uas_psi_farhan_fauzy_.pdf', '2026-09-18 03:24:08', '2026-09-18 03:24:08'),
(12, 128, '5', '2026-09-18', 'r3r3', 'r4334rr', '3r', 0, '4r4', NULL, 'f5t', NULL, 'uploads/surat/83cc8d3f-13ba-4b8d-b020-d27e51d3a6d4.pdf', 'projek uas_psi_farhan_fauzy_.pdf', '2026-09-18 03:24:44', '2026-09-18 03:24:44'),
(13, 4, '6', '2026-09-18', NULL, NULL, 'kumaha damang', 0, 'pribadi', NULL, 'surat magang', NULL, 'uploads/surat/97f19882-38b2-4f99-a69b-8367fccf7a19.jpeg', '01KRZ31T5VXYKZ43H3QCQQWM7Q.jpeg', '2026-09-18 03:54:55', '2026-09-18 03:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `users` (`id`, `name`, `username`, `email`, `nip`, `whatsapp`, `email_verified_at`, `password`, `role`, `bidang`, `jabatan`, `golongan`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `status`) VALUES
(1, 'Admin Bapelit', 'admin', 'admin@gmail.com', NULL, NULL, NULL, '$2y$12$nEEIVdLDsixbYYDA.vbI1u4aD4xBlQd3noEq/Fsox/GN8PZ81kAuK', 'admin', NULL, 'hengker', NULL, NULL, '2026-09-04 13:59:54', '2026-09-14 13:14:52', 1, 'aktif'),
(4, 'Ikbal Maulana', 'ikbal', NULL, NULL, NULL, NULL, '$2y$12$/CsixFN3XsOvJzit3wlO/e7wmJ6xqag33LKYtJVaaxM1GM6DNCuPq', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-10 07:59:30', '2026-09-18 01:59:16', 0, 'aktif'),
(66, 'Drs. Rahayu Jamiat Abdullah, S.Sos., M.Si.', 'rahayu', 'rahayu@gmail.com', '19690718 198903 1 005', NULL, NULL, '$2y$12$hZFkwILlH4I2avbDFVK43.azandL5Sx3cxpaaabCxkIOpkWyzZlvq', 'user', 'KEPALA BADAN', 'Kepala', 'IV/c', NULL, '2026-09-14 07:45:18', '2026-09-14 13:15:10', 0, 'aktif'),
(67, 'Teguh Nugraha, S.T., M.M.', 'eguh', 'eguh@gmail.com', '19771130 200501 1 010', NULL, NULL, '$2y$12$bn3dzjXtkgVxF39E/yvaG.h4p2rQDo5wvrx74VX3kdoM2hno.zLKW', 'user', 'SEKRETARIAT', 'Sekretaris', 'IV/b', NULL, '2026-09-14 07:45:18', '2026-09-14 07:45:18', 0, 'aktif'),
(68, 'Yuliani, S.IP.', 'uliani', 'uliani@gmail.com', '19780712 200701 2 016', NULL, NULL, '$2y$12$EPfUZTURPy2k4ZiPXMZmj.oYsbh8NayLPcLRwQFSlkjo07AY66rYa', 'user', 'SEKRETARIAT', 'Kasubag Umum dan Kepegawaian', 'III/d', NULL, '2026-09-14 07:45:19', '2026-09-14 07:45:19', 0, 'aktif'),
(69, 'Ratna Sari Aisyah, S.E.', 'atna', 'atna@gmail.com', '19870302 200901 2 001', NULL, NULL, '$2y$12$9mhr.y6hfPI.eFP.h5ZeUOfb3NID1WJh7/TUro8iUSoJCl7LJcWKG', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:19', '2026-09-14 07:45:19', 0, 'aktif'),
(70, 'Rika Rakanita, S.AP.', 'ika', 'ika@gmail.com', '19910801 201903 2 006', NULL, NULL, '$2y$12$oiYXhfr3XoQFMd2AyIoG9.bkUKBcWVgk1bcredGPtAFW87dC33zn2', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:20', '2026-09-14 12:50:16', 0, 'aktif'),
(71, 'Hj. Puji Priyanti Utami Dewi', 'uji', 'uji@gmail.com', '19750324 200701 2 004', NULL, NULL, '$2y$12$VHiMb1Cpl4Iobdr8CZ1NWeUjUM51QBZ8ENP8uYOBOjy87lJq.tA9W', 'user', 'SEKRETARIAT', 'Pengadministrasi Perkantoran', 'III/a', NULL, '2026-09-14 07:45:20', '2026-09-14 07:45:20', 0, 'aktif'),
(72, 'Ade Hendra Suhendar', 'de', 'de@gmail.com', '19790820 202521 1 068', NULL, NULL, '$2y$12$snT.cAgu48cDRHzf/0L6neHfEAbZCnf097SUjMk.r2sK/M8fPVHbe', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:20', '2026-09-14 07:45:20', 0, 'aktif'),
(73, 'Mohamad Yusup', 'ohamad', 'ohamad@gmail.com', '19701208 202521 1 024', NULL, NULL, '$2y$12$M.9yk87BnnMdYKFVITWlBuPvSpVomOTlthBc1zdbI6bTWhja.EQbC', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(74, 'Anita Rohmat, S.P., M.Si.', 'nita', 'nita@gmail.com', '19760502 201410 2 001', NULL, NULL, '$2y$12$UxwVuOgAsmhJCcaufEbKeumbvFUye.1z4VpjYwpmWQtTOoiCXe0.S', 'user', 'SEKRETARIAT', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(75, 'Andri Herdiana, S.IP.', 'ndri', 'ndri@gmail.com', '19810525 201001 1 005', NULL, NULL, '$2y$12$rTUCGWXm2ay/X6eEJ8Uk9eRroSzs3kB1shiHQrNgqrxxEfqrihCxG', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/d', NULL, '2026-09-14 07:45:21', '2026-09-14 07:45:21', 0, 'aktif'),
(76, 'Anton Watoni, S.AP', 'nton', 'nton@gmail.com', '19930521 201903 1 005', NULL, NULL, '$2y$12$5.L5lbzAWQLG0GD0VPSwmulheMyfxv/kdnhzQSrnlRBdmVQj779SS', 'user', 'SEKRETARIAT', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(77, 'Fachrul Septian Dwiputra, S.M.', 'achrul', 'achrul@gmail.com', '19930921202421 1 018', NULL, NULL, '$2y$12$FAg95476AMMdruUAP3dcxetvMJGRJiMERLwJ38C60I4y3KZCi4eyS', 'user', 'SEKRETARIAT', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(78, 'Irfan Faizal', 'rfan', 'rfan@gmail.com', '19890610 202521 1 151', NULL, NULL, '$2y$12$WQzwfJz9Aj2YqhgDQ33pcuT9SANnouw6.fUeHOJEKG/oGjTHnRM3G', 'user', 'SEKRETARIAT', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:22', '2026-09-14 07:45:22', 0, 'aktif'),
(79, 'Rohimah, S.Si.', 'ohimah', 'ohimah@gmail.com', '19870216 201001 2 008', NULL, NULL, '$2y$12$bg9B7k7dxSfc2xSiKWCy1OGSP1LBbpePInr.EVlQVMFYJ7dye/VdO', 'user', 'SEKRETARIAT', 'Kasubag Keuangan', 'III/d', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(80, 'Adam Nugraha, S.IP.', 'dam', 'dam@gmail.com', '19841014 201001 1 002', NULL, NULL, '$2y$12$6TRzepQnXbvcV9xYDD5XzuO4w/6jSET0qqKmy7ESID9LZNhbRfDVy', 'user', 'SEKRETARIAT', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(81, 'Dede Sutedi, S.IP.', 'ede', 'ede@gmail.com', '19690425 200701 1 007', NULL, NULL, '$2y$12$yowc6hPHn1YX4OWw8cQX/uzsh8UQ3f5CQRzFYBtGsHAlavAQCGafS', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:23', '2026-09-14 07:45:23', 0, 'aktif'),
(82, 'Ade Supriatna, S.Sos.', 'de2', 'de2@gmail.com', '19700829 200701 1 007', NULL, NULL, '$2y$12$KtA7vzYD7XFUF2KM8.ViHe7h.KUlOXaynJF7Mj9z7NyliYiPl79si', 'user', 'SEKRETARIAT', 'Pengolah Data dan Informasi', 'III/d', NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(83, 'Rony Setiawan, S.H.', 'ony', 'ony@gmail.com', '19760524 202521 1 044', NULL, NULL, '$2y$12$tnFikWFFMwS2.zrIjAB2ve2o1P8eoHxC4qHUvCc.g5qkgvEOLd/FG', 'user', 'SEKRETARIAT', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(84, 'Resna Nika Febriyanty, S.Kom., M.Si.', 'esna', 'esna@gmail.com', '19800227 200801 2 002', NULL, NULL, '$2y$12$hXqHrKVC/aYBaHTBwI5hYeNa0dJ44d8OXR3RBBIlDxFqFiVS7z.3a', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Kepala Bidang Perekonomian dan SDA', 'III/d', NULL, '2026-09-14 07:45:24', '2026-09-14 07:45:24', 0, 'aktif'),
(85, 'Dzikri Miftahul Huda, S.AP', 'zikri', 'zikri@gmail.com', '19960309 201903 1 001', NULL, NULL, '$2y$12$qzebBDnVNhpMZHcyA7sLM.YJzG2BqCIGxrouGOeuxjefCEKoba1bW', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:25', '2026-09-14 07:45:25', 0, 'aktif'),
(86, 'Dani Nurdiana, S.E.', 'ani', 'ani@gmail.com', '19840108 201503 1 002', NULL, NULL, '$2y$12$6WyDMK3f8cHiYoNMgzU.JOziuMeDMjorbB2RhfGQis6MFGyGPUr0m', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:25', '2026-09-14 07:45:25', 0, 'aktif'),
(87, 'Eka Surtika, S.IP', 'ka', 'ka@gmail.com', '19780105 200701 2 007', NULL, NULL, '$2y$12$WJiv8EbJDHix8O1hQhNBV.X6wE1llqn.8WPZH3dMST00XxtrgIctO', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Pengolah Data dan Informasi', 'III/c', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(88, 'Satya Laksana, S.P., M.E., M.P.P.', 'atya', 'atya@gmail.com', '19790530 200604 1 004', NULL, NULL, '$2y$12$yvtpOJ7aaumF3omTqWp8ZepmVY0XJoUJBpcaYk8w9soaR9yebHvzm', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(89, 'Rina Ridiawati, S.Sos., M.Si.', 'ina', 'ina@gmail.com', '19840328 201410 2 001', NULL, NULL, '$2y$12$5LMixRyDr68q/B5ntdihgeIi6OCzzAEhuiPmdOoxrdA1LnKGdI/Ti', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:26', '2026-09-14 07:45:26', 0, 'aktif'),
(90, 'Hapidulloh Zen, S.T.', 'apidulloh', 'apidulloh@gmail.com', '19920903 202421 1 023', NULL, NULL, '$2y$12$hqk0dNJsGRbAtMkGClAL2ObXM5GGDkGdRkfolSAaiT1vvZrFNwNv.', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(91, 'Erwin Faizal Lesmana, S.Pd.', 'rwin', 'rwin@gmail.com', '19910812 202521 1 111', NULL, NULL, '$2y$12$PDPrT/6.lsZC.yfor8YQZuhfZWxN0Is9RJtLbG.TS7JsfBBSYjrf6', 'user', 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(92, 'Jani Maulana, S.Sos., M.Si.', 'ani2', 'ani2@gmail.com', '19800125 200901 1 005', NULL, NULL, '$2y$12$SJcAuQt2jSNvu99uVJ9UTumWbI58AyuES/YcuVPBgvezGn0s74pTa', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Kepala Bidang Infrastruktur dan Kewilayahan', 'IV/a', NULL, '2026-09-14 07:45:27', '2026-09-14 07:45:27', 0, 'aktif'),
(93, 'Haries Nursyamsu, S.H., M.H.', 'aries', 'aries@gmail.com', '19790101 201001 1 002', NULL, NULL, '$2y$12$IKGPvw5ALlH27Gg7PloUT.z4.RPLANAdrEd78szcobVAYVjNUPjMC', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(94, 'Windi Rahmikawati, S.Sos.', 'indi', 'indi@gmail.com', '19820930 201410 2 001', NULL, NULL, '$2y$12$We3I83v/uZZI.DvV56tZEujB8tutMp.WPYrtnWxxn4YiEyrfN24gK', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Penelaah Teknis Kebijakan', 'III/c', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(95, 'Yeko Anugrah Januar, S.T.,M.T', 'eko', 'eko@gmail.com', '19980122 202203 1 001', NULL, NULL, '$2y$12$2kByro02ybLG57vLX0WwDOBO37euc7fNZ4/fYMcsRF4Gk41L1qc7m', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:28', '2026-09-14 07:45:28', 0, 'aktif'),
(96, 'Irma Nurhalimah Rizqi, S.T.', 'rma', 'rma@gmail.com', '19910806 202505 2 001', NULL, NULL, '$2y$12$OAiXnLhzkQZFgxbyf7Y24e5hI6BBzXfdwJS.OS.yluOf/8E8e0pj2', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'III/a', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(97, 'Ardita Puja Rahmani, S.P.W.K.', 'rdita', 'rdita@gmail.com', '20020314 202505 2 003', NULL, NULL, '$2y$12$kWCUwiKaVkBiBmj5RKNhbeogn4hK9IvXtN3qpBGV2cVeO68iBFYay', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'III/a', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(98, 'Euis Yunan, S.T.', 'uis', 'uis@gmail.com', '19950522 202421 2 029', NULL, NULL, '$2y$12$/FAPKuPRAkjwPcz6eFnlLuighxaZjuwqgNWk1qEQKf0SgqKBp4jhC', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:29', '2026-09-14 07:45:29', 0, 'aktif'),
(99, 'Rizki Muhibudin, A.Md.T.', 'izki', 'izki@gmail.com', '19910820 202521 1 132', NULL, NULL, '$2y$12$gQKB.dzSQEmRU7.9C5Q6meEPLE3ePbB0PFpxcD/xc.s7V2RN6wzFi', 'user', 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN', 'Pengelola Layanan Operasional', NULL, NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(100, 'Sena Adikrisna, S.H., M.H.', 'ena', 'ena@gmail.com', '19860718 201101 2 006', NULL, NULL, '$2y$12$5dJM.YqaHqhvGU7wchTJn.4psMlykSSnDr4LCPmKGR6zCr9gxVAYW', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Kabid Pemerintahan dan Pembangunan Manusia', 'III/d', NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(101, 'Kania Dewi Utami, S.I.Kom.', 'ania', 'ania@gmail.com', '19900909 201503 2 001', NULL, NULL, '$2y$12$aCHWRnC8tV5Erhzh8/S4geWfcdZOkvsnthDh4UEcoZUtlof/HZBti', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:30', '2026-09-14 07:45:30', 0, 'aktif'),
(102, 'Gian Jatnika Munggaran, S.Kom', 'ian', 'ian@gmail.com', '19830605 200501 1 003', NULL, NULL, '$2y$12$f5LZce5.D12.8IM7Yy1D.eoD9JRo.JgfV54dNH2Is35xynzoNRmJu', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'III/d', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(103, 'Intan Ayu Hardiyanti, S.E.', 'ntan', 'ntan@gmail.com', '19970507 202203 2 001', NULL, NULL, '$2y$12$jCE91aPrqPdtqMag8Ow.T./HRObwrhM4ramA4IdZhGyorJy8eXtMm', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(104, 'Shinta Lestari, S.Tr.IP.', 'hinta', 'hinta@gmail.com', '19991212 202208 2 002', NULL, NULL, '$2y$12$cP7lXZOBQzBDEbJ.ZP40Q.BirCrEmI/GnffRyHdjn5iSOp9bLJA5K', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:31', '2026-09-14 07:45:31', 0, 'aktif'),
(105, 'Dewi Shintya A', 'ewi', 'ewi@gmail.com', '19760202 200701 2 011', NULL, NULL, '$2y$12$L/LR9m0/l47.lWDQU0m.eenhN1FFA4sfv8BNuZ/DbOKndXh2JtPae', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Pengolah Data dan Informasi', 'III/a', NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(106, 'Nida Fauziyyah Sambas, S.Mat.', 'ida', 'ida@gmail.com', '19960330 202421 2 033', NULL, NULL, '$2y$12$8iSAwtUugLwVrvq.yQjSMusDpytJe7aEGyOgJvfw8n33RB78fVsw6', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(107, 'Mohammad Fazar Ruslan Setia', 'ohammad', 'ohammad@gmail.com', '19950419 202521 1 091', NULL, NULL, '$2y$12$HQ3QBTfGxOJpbqBAQlCRROIfEBz5QkINFL/BOBWX2WXW/OvZ7NG4q', 'user', 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA', 'Operator Layanan Operasional', NULL, NULL, '2026-09-14 07:45:32', '2026-09-14 07:45:32', 0, 'aktif'),
(108, 'Ine Susyane, S.T.', 'ne', 'ne@gmail.com', '19781103 200604 2 001', NULL, NULL, '$2y$12$Rsie7ruP2EhwL2niT4UPD.PZfFUg/OWJOs.nlnHtv.QFiQb2seS9G', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Kabid Perencanaan Pengendalian dan Evaluasi', 'IV/a', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(109, 'Sri Yulia, S.Sos., M.M.', 'ri', 'ri@gmail.com', '19690902 199403 2 002', NULL, NULL, '$2y$12$iwp1rB70JsvR.xmaXyuis.7N5M180cLTM1V74DGTybqgH.r9ZBI6W', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Muda', 'IV/a', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(110, 'Kania Dewi Kinasih, S.E.', 'ania2', 'ania2@gmail.com', '19980618 202203 2 001', NULL, NULL, '$2y$12$rLl3i04Xz1bqWJgYsJjW4OAJRDBFZHIYVaAGiAZeWZQFK7Ikh1nUG', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:33', '2026-09-14 07:45:33', 0, 'aktif'),
(111, 'Asep Wahyudin, S.IP', 'sep', 'sep@gmail.com', '19730409 200501 1 004', NULL, NULL, '$2y$12$JLBz1.uUIBxTIZzDKu48AO26xME5yweuGMoppn5ZmA3pLvTTHaaiq', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Pengolah Data dan Informasi', 'III/c', NULL, '2026-09-14 07:45:34', '2026-09-14 07:45:34', 0, 'aktif'),
(112, 'Mona Febriyanti, S.Ak.', 'ona', 'ona@gmail.com', '19980225 202203 2 001', NULL, NULL, '$2y$12$ECiBE.N7wLCChPbiVbH83.AI0AnHdU4Rg5DO90Yokzo7LiSeQpnTm', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:34', '2026-09-14 07:45:34', 0, 'aktif'),
(113, 'Dena Tri Lestary, S.M.', 'ena2', 'ena2@gmail.com', '19940621 201503 2 001', NULL, NULL, '$2y$12$HHzmcXGMf7bWLHc0PnjoZeOx/5G8WW7yisE9yehoYcqJV60zEwvwm', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'III/b', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(114, 'Rizqa Fauziyyah Nabila, S.Kom.', 'izqa', 'izqa@gmail.com', '20000227 202505 2 002', NULL, NULL, '$2y$12$0TlqvsmZzO7AksMGHx/uVOlwZPxF5q6ld3E08xVGk9GfhMhWFxeRy', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Penata Kelola Sistem dan Teknologi Informasi', 'III/a', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(115, 'Yadi Mulyadi, S.Sos.', 'adi', 'adi@gmail.com', '19820116 202421 1 009', NULL, NULL, '$2y$12$DrRHPgxT0jdNIdEV4qVNYufSKlQkrQxG0t7sZJqC3KrhqvRH9hCO.', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Perencana Ahli Pertama', 'IX', NULL, '2026-09-14 07:45:35', '2026-09-14 07:45:35', 0, 'aktif'),
(116, 'Rifqi Widyan Ramadhan, S.T', 'ifqi', 'ifqi@gmail.com', '19940218 202521 1 083', NULL, NULL, '$2y$12$ON8RccJ8tMCuM.kBioTuy.BDS1qSI99WZjnrIsRlryf22Opgk0tn6', 'user', 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI', 'Penata Layanan Operasional', NULL, NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(117, 'Bayu Wijaksana, S.T., M.Si.', 'ayu', 'ayu@gmail.com', '19820805 200902 1 004', NULL, NULL, '$2y$12$MAAmPSBSZWpHcKE7NC/gP.DKAnghFQeTBGxwr4Jv4JJA2kEfFC3R2', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Kabid Penelitian dan Pengembangan', 'IV/a', NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(118, 'Agi Nurhidayah, S.Si., M.T.', 'gi', 'gi@gmail.com', '19820807 200701 1 007', NULL, NULL, '$2y$12$f3jNJ65EOs4qXPeGRNnLLelgjOs6RqJBhmYWXC47mxwch44OQ8zrO', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Peneliti Ahli Muda', 'III/d', NULL, '2026-09-14 07:45:36', '2026-09-14 07:45:36', 0, 'aktif'),
(119, 'Akbar Bhahesti, S.E., M.A.B.', 'kbar', 'kbar@gmail.com', '19910926 201903 1 003', NULL, NULL, '$2y$12$SBCpDeZN8DV7N91gq7IinO84u5qdiY69cVQpho1ScKGawkq/fpSGy', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(120, 'Imat Rohimat, S.IP', 'mat', 'mat@gmail.com', '19810503 200901 1 004', NULL, NULL, '$2y$12$uAxeFaA152vm9B9SB8OH3ONf5MNehTmZlkSvnSlzwuQSn1c4fGfha', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/d', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(121, 'Dadan Sunandar, S.Si.', 'adan', 'adan@gmail.com', '19810416 200902 1 002', NULL, NULL, '$2y$12$T..YbvbF/h5.mmWHCkv0SOy5Gh9I40mBFatk25CM6O6J.bvo0aFde', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Kasubid Statistik', 'III/d', NULL, '2026-09-14 07:45:37', '2026-09-14 07:45:37', 0, 'aktif'),
(122, 'Nurul Hikmah, S.Stat., M.I.L.', 'urul', 'urul@gmail.com', '19960408 201903 2 006', NULL, NULL, '$2y$12$mgSymM3cuEDatjp4A/mDXeWXVBsHLAPLo64o1pnGtT117rDx25OXS', 'user', 'BIDANG PENELITIAN DAN PENGEMBANGAN', 'Penelaah Teknis Kebijakan', 'III/b', NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(123, 'Wildan Nugraha, S.T', 'ildan', 'ildan@gmail.com', NULL, NULL, NULL, '$2y$12$A32EgWsFBxQ05BxFd9uW7.y9/gvMEjlhbnL894JgAySkaDyzsB.4a', 'user', 'IT', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(124, 'Muhammad Ridwan, S.Kom', 'uhammad', 'uhammad@gmail.com', NULL, NULL, NULL, '$2y$12$JRCFYPEtNkmrQfrAreXmVO6MF.uTWlvfimmdowR9Hb5QwDmy3vCzW', 'user', 'IT', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:38', '2026-09-14 07:45:38', 0, 'aktif'),
(125, 'Febi Robiana Suherman, S.Kom', 'ebi', 'ebi@gmail.com', NULL, NULL, NULL, '$2y$12$YXd90JI1Ws94BmKmmKJnNOxnIHOi/jIPt1inAuk/8ItNVPh/PoASq', 'user', 'Bidang PSDA', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:39', '2026-09-14 07:52:33', 0, 'aktif'),
(126, 'Sandria Anggra Sutardi, S.T', 'andria', 'andria@gmail.com', NULL, NULL, NULL, '$2y$12$ESaCVrJnIO9LxvC7Nb1A9uiuUlEa5VcNUod/iNIGrcC1vng19t64K', 'user', 'Bidang Litbang', 'Tenaga IT', NULL, NULL, '2026-09-14 07:45:39', '2026-09-14 07:52:49', 0, 'aktif'),
(127, 'leo', 'leo', NULL, NULL, NULL, NULL, '$2y$12$RKOXNMWJNMEEchWsyzw7Ke0TsXhsHQ3moYNAqIFlUcDQc4LhZfgRm', 'admin', NULL, NULL, NULL, NULL, '2026-09-15 02:32:53', '2026-09-18 01:49:52', 1, 'aktif'),
(128, 'test 2', 'test2', NULL, NULL, NULL, NULL, '$2y$12$qONEG5D0nJ84AFUSBzuEl.pX.54JmPpPlbnJojV/ICtcBxf/v/NJy', 'user', NULL, NULL, NULL, NULL, '2026-09-17 01:47:56', '2026-09-17 01:47:56', 0, 'aktif'),
(129, 'Risa Fahmawati', 'risa', NULL, NULL, '0822-1086-6182', NULL, '$2y$12$vC67dF/UJEb49Hxd0B0nmer.lCoZqu2VVl7QUOnmeLEjBp.ZH8jJq', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-18 01:56:23', '2026-09-18 01:56:23', 0, 'aktif'),
(130, 'Farhan Fauzy', 'farhan', NULL, NULL, NULL, NULL, '$2y$12$jszoNPcvbnugVN7ji9EIwuEV/jRh2csdmLP6VKXDom5miTGO50ipC', 'user', 'Umum dan kepegawaian', NULL, NULL, NULL, '2026-09-18 01:57:54', '2026-09-18 01:57:54', 0, 'aktif');

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`);

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
  ADD KEY `surats_user_id_foreign` (`user_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=923;

--
-- AUTO_INCREMENT for table `absensi_sesi`
--
ALTER TABLE `absensi_sesi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
