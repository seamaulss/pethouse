-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lara_pethouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `petugas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_hewan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_wa` varchar(20) DEFAULT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` enum('Kucing','Anjing') NOT NULL,
  `ukuran_hewan` varchar(20) DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `dp_dibayar` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `bukti_dp` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `alasan_perpanjangan` text DEFAULT NULL,
  `alasan_cancel` text DEFAULT NULL,
  `tanggal_perpanjangan` date DEFAULT NULL,
  `status` enum('pending','diterima','selesai','in_progress','perpanjangan','pembatalan') NOT NULL DEFAULT 'pending',
  `total_harga` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `petugas_id`, `jenis_hewan_id`, `layanan_id`, `kode_booking`, `nama_pemilik`, `email`, `nomor_wa`, `nama_hewan`, `jenis_hewan`, `ukuran_hewan`, `tanggal_masuk`, `tanggal_keluar`, `dp_dibayar`, `bukti_dp`, `catatan`, `alasan_perpanjangan`, `alasan_cancel`, `tanggal_perpanjangan`, `status`, `total_harga`, `created_at`, `updated_at`) VALUES
(8, 3, NULL, NULL, 1, 'BOOK-2026-0003', 'Felix', 'felix@gmail.com', '62895413890707', 'Muso', 'Anjing', 'Sedang', '2026-01-30', '2026-02-02', 'Ya', 'bukti_dp/dTy2aG6USnSB9x5ncog9aSnbPEhxYzTcTbBPEHGb.png', 'cek', NULL, '\n\n[PEMBATALAN DIAJUKAN]\nAlasan: Maaf min, kepencet\nDibatalkan pada: 30/01/2026 00:20', NULL, 'pembatalan', 510000.00, '2026-01-29 17:17:08', '2026-01-29 18:02:05'),
(12, 6, NULL, NULL, 1, 'BOOK-2026-0004', 'Arkan', 'arkan@gmail.com', '6289506700308', 'NginX', 'Anjing', 'Kecil', '2026-02-02', '2026-02-03', 'Ya', 'bukti_dp/svzZxKnsiovRHJxBaD6867cIsj0NooqSfPEwSGau.png', 'min', NULL, '\n\n[PEMBATALAN DIAJUKAN]\nAlasan: min aku gajadi berubah pikiran\nDibatalkan pada: 02/02/2026 13:30\nTotal yang sudah dibayar: Rp 170.000', NULL, 'pembatalan', 170000.00, '2026-02-02 06:29:54', '2026-02-02 06:30:49'),
(13, 7, 2, NULL, 1, 'BOOK-2026-0005', 'NginX', 'nginx@gmail.com', '6285942173668', 'Black', 'Kucing', 'Kecil', '2026-02-02', '2026-02-04', 'Ya', 'bukti_dp/LHxwWu7Yb8gDfIRMUZX4DdbcPkEYBSPfrgB2EhJE.png', 'Min aku mau liburan', NULL, NULL, NULL, 'in_progress', 170000.00, '2026-02-02 06:40:01', '2026-02-02 06:45:30'),
(14, 7, 2, NULL, 1, 'BOOK-2026-0006', 'NginX', 'nginx@gmail.com', '6289506700308', 'b', 'Anjing', 'Sedang', '2026-02-02', '2026-02-06', 'Ya', 'bukti_dp/7Ze1fIqbjONaeE0xi5w52QzMDejnRGwjYJWwKWfD.png', 'cek', NULL, NULL, NULL, 'in_progress', 170000.00, '2026-02-02 07:40:04', '2026-02-02 07:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `catatan_medis`
--

CREATE TABLE `catatan_medis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `konsultasi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dokter_id` bigint(20) UNSIGNED NOT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` varchar(50) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `resep` text DEFAULT NULL,
  `vaksin` varchar(100) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `catatan_lain` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `petugas_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_kegiatan` time NOT NULL,
  `makan_pagi` tinyint(1) NOT NULL DEFAULT 0,
  `makan_siang` tinyint(1) NOT NULL DEFAULT 0,
  `makan_malam` tinyint(1) NOT NULL DEFAULT 0,
  `minum` tinyint(1) NOT NULL DEFAULT 0,
  `jalan_jalan` tinyint(1) NOT NULL DEFAULT 0,
  `buang_air` enum('belum','normal','diare','sembelit') NOT NULL DEFAULT 'belum',
  `catatan` text DEFAULT NULL,
  `jam_makan_pagi` time DEFAULT NULL,
  `jam_makan_siang` time DEFAULT NULL,
  `jam_makan_malam` time DEFAULT NULL,
  `jam_minum` time DEFAULT NULL,
  `jam_jalan_jalan` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `foto`, `keterangan`, `created_at`, `updated_at`) VALUES
(6, 'galeri/1769966114_697f8a22c80d4.jpg', 'Kesehatan anabul Anda adalah prioritas utama kami. Kenali para dokter hewan berdedikasi yang siap memberikan perawatan terbaik dengan sepenuh hati.', '2026-02-01 17:15:14', '2026-02-01 17:15:14'),
(7, 'galeri/1769966169_697f8a596136d.jpg', 'Kesehatan anabul Anda adalah prioritas utama kami. Kenali para dokter hewan berdedikasi yang siap memberikan perawatan terbaik dengan sepenuh hati.', '2026-02-01 17:16:09', '2026-02-01 17:16:09'),
(8, 'galeri/1769966587_697f8bfb14249.jpg', 'Kesehatan anabul Anda adalah prioritas utama kami. Kenali para dokter hewan berdedikasi yang siap memberikan perawatan terbaik dengan sepenuh hati.', '2026-02-01 17:23:07', '2026-02-01 17:23:07'),
(9, 'galeri/1769967169_697f8e416909f. Justine Lee', 'Kesehatan anabul Anda adalah prioritas utama kami. Kenali para dokter hewan berdedikasi yang siap memberikan perawatan terbaik dengan sepenuh hati.', '2026-02-01 17:32:49', '2026-02-01 17:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slider`
--

CREATE TABLE `hero_slider` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `subjudul` varchar(255) DEFAULT NULL,
  `tombol_text` varchar(50) NOT NULL DEFAULT 'Booking Sekarang',
  `tombol_link` varchar(255) NOT NULL DEFAULT 'booking.php',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slider`
--

INSERT INTO `hero_slider` (`id`, `gambar`, `judul`, `subjudul`, `tombol_text`, `tombol_link`, `urutan`, `created_at`, `updated_at`) VALUES
(1, '1769409553_1767149802_Gemini_Generated_Image_g1p3t8g1p3t8g1p3.png', 'Hubungi Kami', 'Punya pertanyaan medis? Chat dokter kami sekarang melalui layanan WhatsApp responsif.', 'Darurat', 'https://wa.me/6285942173668', 1, '2026-01-25 23:39:15', '2026-01-25 23:39:15'),
(2, '1769409635_1767668362_A man with a stethoscope around his neck is petting a dog _ Premium AI-generated image.jpg', 'Dokter Berlisensi', 'Kami menghadirkan kasih sayang yang dipadukan dengan keahlian medis dari dokter hewan berlisensi.', 'Check', 'layanan', 2, '2026-01-25 23:40:35', '2026-01-25 23:40:35'),
(3, '1769409706_1767150059_Animal Care Center of Downers Grove _ RWE Design Build.jpg', 'Lokasi Strategis', 'Berada di lokasi yang mudah ditemukan, memastikan hewan kesayangan Anda mendapatkan bantuan medis secepat mungkin saat dibutuhkan.', 'Lokasi', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.852235712345!2d109.6522357!3d-7.4039689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aab250dbea06f%3A0x67886a3086ca184d!2sKambing%20Kita%20Banjarnegara!5e0!3m2!1sen!2sid!4v1712345678901!', 3, '2026-01-25 23:41:46', '2026-01-25 23:41:46');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_hewan`
--

CREATE TABLE `jenis_hewan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `aktif` enum('ya','tidak') NOT NULL DEFAULT 'ya',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_hewan`
--

INSERT INTO `jenis_hewan` (`id`, `nama`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kucing', 'ya', '2026-01-25 23:43:32', '2026-01-25 23:43:32'),
(2, 'Anjing', 'ya', '2026-01-25 23:43:41', '2026-01-25 23:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `kapasitas`
--

CREATE TABLE `kapasitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_hewan` varchar(50) NOT NULL,
  `ukuran_hewan` varchar(20) NOT NULL,
  `max_kapasitas` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dokter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_konsultasi` varchar(20) NOT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `jenis_hewan` varchar(50) DEFAULT NULL,
  `topik` varchar(100) DEFAULT NULL,
  `tanggal_janji` date DEFAULT NULL,
  `jam_janji` time DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `balasan_dokter` text DEFAULT NULL,
  `status` enum('pending','diterima','selesai') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `user_id`, `dokter_id`, `kode_konsultasi`, `nama_pemilik`, `no_wa`, `jenis_hewan`, `topik`, `tanggal_janji`, `jam_janji`, `catatan`, `balasan_dokter`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 'KONS-2026-0001', 'Felix', '6289506700308', 'Anjing', 'demam', '2026-01-28', '12:00:00', 'cek', NULL, 'diterima', '2026-01-27 19:45:12', '2026-01-27 19:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi_balasan`
--

CREATE TABLE `konsultasi_balasan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `konsultasi_id` bigint(20) UNSIGNED NOT NULL,
  `pengirim` enum('user','dokter') NOT NULL,
  `isi` text NOT NULL,
  `dibaca_user` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `gambar`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Pet Hotel', '1769410813_697710fdda412.jpg', 'Berikan pengalaman menginap yang menyenangkan bagi hewan peliharaan Anda saat Anda sedang bepergian. Pet Hotel kami menawarkan suasana yang hangat dan eksklusif, di mana setiap tamu mendapatkan perhatian personal. Bukan sekadar tempat penitipan, kami menghadirkan rumah kedua yang mengutamakan keamanan dan kebahagiaan mereka.', '2026-01-26 00:00:14', '2026-01-26 00:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_harga`
--

CREATE TABLE `layanan_harga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_hewan_id` bigint(20) UNSIGNED NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layanan_harga`
--

INSERT INTO `layanan_harga` (`id`, `layanan_id`, `jenis_hewan_id`, `harga_per_hari`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 170000.00, '2026-01-26 00:00:39', '2026-01-26 00:00:39'),
(2, 1, 1, 85000.00, '2026-01-26 00:00:39', '2026-01-26 00:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_01_15_151505_create_users_table', 1),
(2, '2026_01_15_151549_create_jenis_hewan_table', 1),
(3, '2026_01_15_151602_create_layanan_table', 1),
(4, '2026_01_15_151616_create_layanan_harga_table', 1),
(5, '2026_01_15_151636_create_booking_table', 1),
(6, '2026_01_15_151654_create_kapasitas_table', 1),
(7, '2026_01_15_151751_create_daily_logs_table', 1),
(8, '2026_01_15_151804_create_galeri_table', 1),
(9, '2026_01_15_151818_create_hero_slider_table', 1),
(10, '2026_01_15_151839_create_tentang_table', 1),
(11, '2026_01_15_151857_create_testimoni_table', 1),
(12, '2026_01_15_151907_create_notifications_table', 1),
(13, '2026_01_15_161753_create_cache_table', 1),
(14, '2026_01_16_001603_create_konsultasi_table', 1),
(15, '2026_01_16_001604_create_konsultasi_balasan_table', 1),
(16, '2026_01_16_151733_create_catatan_medis_table', 1),
(17, '2026_01_22_172555_create_riwayat_petugas_table', 1),
(18, '2026_01_22_175635_add_petugas_id_to_booking_table', 1),
(19, '2026_01_22_185707_add_extension_cancel_fields_to_booking_table', 1),
(20, '2026_01_26_163420_add_type_to_notifications_table', 2),
(21, '2026_01_30_010143_add_total_harga_to_booking_table', 3),
(22, '2026_02_02_144858_create_kegiatan_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_target` enum('user','petugas','dokter','admin') NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `role_target`, `title`, `message`, `booking_id`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(46, NULL, 'admin', 'Booking Baru', 'Booking baru dibuat oleh Felix untuk Muso (Kode: BOOK-2026-0003)', 8, 'booking', 1, '2026-01-29 17:17:08', '2026-01-29 17:37:16'),
(47, NULL, 'admin', 'Booking Dibatalkan', 'Booking BOOK-2026-0003 dibatalkan oleh Felix', 8, 'cancel', 1, '2026-01-29 17:20:14', '2026-01-29 17:37:16'),
(69, NULL, 'admin', 'Booking Baru', 'Booking baru dibuat oleh Arkan untuk NginX (Kode: BOOK-2026-0004)', 12, 'booking', 1, '2026-02-02 06:29:54', '2026-02-02 06:34:16'),
(70, NULL, 'admin', 'Booking Dibatalkan', 'Booking BOOK-2026-0004 dibatalkan oleh Arkan', 12, 'cancel', 1, '2026-02-02 06:30:49', '2026-02-02 06:34:16'),
(71, NULL, 'admin', 'Booking Baru', 'Booking baru dibuat oleh NginX untuk Black (Kode: BOOK-2026-0005)', 13, 'booking', 0, '2026-02-02 06:40:01', '2026-02-02 06:40:01'),
(72, NULL, 'admin', 'Status Booking Berubah', 'Status booking BOOK-2026-0005 berubah dari pending menjadi diterima', 13, 'status', 0, '2026-02-02 06:45:21', '2026-02-02 06:45:21'),
(73, 7, 'user', 'Status Booking Diperbarui', 'Booking BOOK-2026-0005 statusnya diubah menjadi Diterima', 13, 'info', 0, '2026-02-02 06:45:21', '2026-02-02 06:45:21'),
(74, NULL, 'admin', 'Status Booking Berubah', 'Status booking BOOK-2026-0005 berubah dari diterima menjadi in_progress', 13, 'status', 0, '2026-02-02 06:45:30', '2026-02-02 06:45:30'),
(75, 7, 'user', 'Status Booking Diperbarui', 'Booking BOOK-2026-0005 statusnya diubah menjadi Dititipkan', 13, 'info', 0, '2026-02-02 06:45:30', '2026-02-02 06:45:30'),
(76, NULL, 'admin', 'Booking Baru', 'Booking baru dibuat oleh NginX untuk b (Kode: BOOK-2026-0006)', 14, 'booking', 0, '2026-02-02 07:40:04', '2026-02-02 07:40:04'),
(77, NULL, 'admin', 'Status Booking Berubah', 'Status booking BOOK-2026-0006 berubah dari pending menjadi diterima', 14, 'status', 0, '2026-02-02 07:40:24', '2026-02-02 07:40:24'),
(78, 7, 'user', 'Status Booking Diperbarui', 'Booking BOOK-2026-0006 statusnya diubah menjadi Diterima', 14, 'info', 0, '2026-02-02 07:40:24', '2026-02-02 07:40:24'),
(79, NULL, 'admin', 'Status Booking Berubah', 'Status booking BOOK-2026-0006 berubah dari diterima menjadi in_progress', 14, 'status', 0, '2026-02-02 07:40:41', '2026-02-02 07:40:41'),
(80, 7, 'user', 'Status Booking Diperbarui', 'Booking BOOK-2026-0006 statusnya diubah menjadi Dititipkan', 14, 'info', 0, '2026-02-02 07:40:41', '2026-02-02 07:40:41'),
(81, NULL, 'admin', 'Booking Diperpanjang', 'Booking BOOK-2026-0006 diperpanjang oleh NginX', 14, 'extend', 0, '2026-02-02 07:41:20', '2026-02-02 07:41:20'),
(82, NULL, 'admin', 'Status Booking Berubah', 'Status booking BOOK-2026-0006 berubah dari in_progress menjadi perpanjangan', 14, 'status', 0, '2026-02-02 07:41:20', '2026-02-02 07:41:20'),
(83, NULL, 'admin', 'Status Booking Berubah1', 'Status booking BOOK-2026-0006 berubah dari perpanjangan menjadi in_progress', 14, 'status', 0, '2026-02-02 07:41:39', '2026-02-02 07:41:39'),
(84, 7, 'user', 'Perpanjangan Diterima', 'Perpanjangan booking BOOK-2026-0006 telah diterima hingga 06 Feb 2026 dengan biaya tambahan Rp 0. Total harga baru: Rp 170.000', 14, 'info', 0, '2026-02-02 07:41:39', '2026-02-02 07:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_petugas`
--

CREATE TABLE `riwayat_petugas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `petugas_id` bigint(20) UNSIGNED NOT NULL,
  `status_akhir` enum('selesai') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tentang`
--

CREATE TABLE `tentang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tentang`
--

INSERT INTO `tentang` (`id`, `judul`, `isi`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Tentang Kami', 'Selamat datang di Pethouse, tempat terbaik untuk merawat dan memanjakan hewan peliharaan Anda! Kami percaya bahwa setiap hewan peliharaan layak mendapatkan perhatian, kasih sayang, dan perawatan terbaik.\r\n\r\nDi Pethouse, kami menyediakan berbagai layanan mulai dari grooming, konsultasi kesehatan, hingga penjualan produk berkualitas untuk hewan kesayangan Anda. Tim kami terdiri dari para profesional yang berpengalaman dan mencintai hewan, sehingga setiap hewan yang datang ke Pethouse akan mendapatkan layanan dengan penuh perhatian dan kenyamanan.\r\n\r\nKami berkomitmen untuk menciptakan lingkungan yang ramah hewan dan keluarga, di mana kebahagiaan hewan peliharaan adalah prioritas utama kami. Dengan Pethouse, Anda tidak hanya memberikan perawatan terbaik, tetapi juga pengalaman yang menyenangkan bagi hewan kesayangan Anda.', '1769410975_tentang-kami.png', '2026-01-26 00:02:55', '2026-01-26 00:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `nama_hewan` varchar(50) DEFAULT NULL,
  `jenis_hewan` varchar(30) DEFAULT NULL,
  `isi_testimoni` text NOT NULL,
  `foto_hewan` varchar(255) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `nama_pemilik`, `nama_hewan`, `jenis_hewan`, `isi_testimoni`, `foto_hewan`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Jane Smith', 'Wilson', 'Anjing', 'Sangat Baik', '69770f335d8c8_t6.jpg', 5, 'aktif', '2026-01-25 23:52:35', '2026-01-25 23:54:04'),
(2, 'Felix', 'Heru', 'Anjing', 'Good Job!', '69770fc976e38_t6.jpg', 5, 'aktif', '2026-01-25 23:55:05', '2026-01-25 23:55:05'),
(3, 'Lanna', 'Muso', 'Anjing', 'Nice Banget', '69770ffbc2519_t6.jpg', 5, 'aktif', '2026-01-25 23:55:55', '2026-01-25 23:55:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','petugas','dokter') NOT NULL DEFAULT 'user',
  `nomor_wa` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nomor_wa`, `email`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '$2y$12$IVVHiXL6Blrk.IoWRXhBHeShzfhX3Fq2X2dSBko0RwOxFDh2lSzlC', 'admin', '085942173668', 'admin@pethouse.com', NULL, NULL, NULL),
(2, 'Petugas1', '$2y$12$IVVHiXL6Blrk.IoWRXhBHeShzfhX3Fq2X2dSBko0RwOxFDh2lSzlC', 'petugas', '089506700308', 'petugas1@gmail.com', NULL, NULL, NULL),
(3, 'felix', '$2y$12$IVVHiXL6Blrk.IoWRXhBHeShzfhX3Fq2X2dSBko0RwOxFDh2lSzlC', 'user', NULL, 'felix@gmail.com', NULL, '2026-01-25 23:35:55', '2026-01-25 23:35:55'),
(4, 'dokter ', '$2y$12$IVVHiXL6Blrk.IoWRXhBHeShzfhX3Fq2X2dSBko0RwOxFDh2lSzlC', 'dokter', '085942173668', 'dokter@gmail.com', NULL, NULL, NULL),
(6, 'Arkan', '$2y$12$tryDCXjI9Hsbq4ZoY8eo7.R8fIKN5js9k4iN1hdlkT0O8opYo36fi', 'user', NULL, 'arkan@gmail.com', NULL, '2026-01-29 18:33:41', '2026-01-29 18:33:41'),
(7, 'NginX', '$2y$12$QLUQzV4SYEiulZi5/LYB1OBukoFb.yUbpUjCErDjoldJkQP2J74wO', 'user', NULL, 'nginx@gmail.com', NULL, '2026-02-02 06:38:39', '2026-02-02 06:38:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_kode_booking_unique` (`kode_booking`),
  ADD KEY `booking_user_id_foreign` (`user_id`),
  ADD KEY `booking_jenis_hewan_id_foreign` (`jenis_hewan_id`),
  ADD KEY `booking_layanan_id_foreign` (`layanan_id`),
  ADD KEY `booking_petugas_id_index` (`petugas_id`);

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
-- Indexes for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catatan_medis_booking_id_foreign` (`booking_id`),
  ADD KEY `catatan_medis_konsultasi_id_foreign` (`konsultasi_id`),
  ADD KEY `catatan_medis_user_id_foreign` (`user_id`),
  ADD KEY `catatan_medis_dokter_id_foreign` (`dokter_id`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_logs_booking_id_foreign` (`booking_id`),
  ADD KEY `daily_logs_petugas_id_foreign` (`petugas_id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slider`
--
ALTER TABLE `hero_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kapasitas`
--
ALTER TABLE `kapasitas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kapasitas_layanan_id_jenis_hewan_ukuran_hewan_unique` (`layanan_id`,`jenis_hewan`,`ukuran_hewan`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konsultasi_kode_konsultasi_unique` (`kode_konsultasi`),
  ADD KEY `konsultasi_status_tanggal_janji_index` (`status`,`tanggal_janji`),
  ADD KEY `konsultasi_user_id_index` (`user_id`),
  ADD KEY `konsultasi_dokter_id_index` (`dokter_id`);

--
-- Indexes for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_balasan_konsultasi_id_foreign` (`konsultasi_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan_harga`
--
ALTER TABLE `layanan_harga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `layanan_harga_layanan_id_jenis_hewan_id_unique` (`layanan_id`,`jenis_hewan_id`),
  ADD KEY `layanan_harga_jenis_hewan_id_foreign` (`jenis_hewan_id`);

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
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `riwayat_petugas`
--
ALTER TABLE `riwayat_petugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_petugas_booking_id_foreign` (`booking_id`),
  ADD KEY `riwayat_petugas_petugas_id_foreign` (`petugas_id`);

--
-- Indexes for table `tentang`
--
ALTER TABLE `tentang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hero_slider`
--
ALTER TABLE `hero_slider`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kapasitas`
--
ALTER TABLE `kapasitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `layanan_harga`
--
ALTER TABLE `layanan_harga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `riwayat_petugas`
--
ALTER TABLE `riwayat_petugas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tentang`
--
ALTER TABLE `tentang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_jenis_hewan_id_foreign` FOREIGN KEY (`jenis_hewan_id`) REFERENCES `jenis_hewan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`),
  ADD CONSTRAINT `booking_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  ADD CONSTRAINT `catatan_medis_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `catatan_medis_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `catatan_medis_konsultasi_id_foreign` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `catatan_medis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD CONSTRAINT `daily_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_logs_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kapasitas`
--
ALTER TABLE `kapasitas`
  ADD CONSTRAINT `kapasitas_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`);

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `konsultasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  ADD CONSTRAINT `konsultasi_balasan_konsultasi_id_foreign` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `layanan_harga`
--
ALTER TABLE `layanan_harga`
  ADD CONSTRAINT `layanan_harga_jenis_hewan_id_foreign` FOREIGN KEY (`jenis_hewan_id`) REFERENCES `jenis_hewan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `layanan_harga_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_petugas`
--
ALTER TABLE `riwayat_petugas`
  ADD CONSTRAINT `riwayat_petugas_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_petugas_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
