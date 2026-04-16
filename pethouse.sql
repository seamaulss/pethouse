-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 09:20 AM
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
-- Database: `pethouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `petugas_id` int(11) DEFAULT NULL,
  `kode_booking` varchar(20) DEFAULT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_wa` varchar(20) DEFAULT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` enum('Kucing','Anjing') NOT NULL,
  `ukuran_hewan` varchar(20) DEFAULT NULL,
  `jenis_hewan_id` int(11) DEFAULT NULL,
  `layanan_id` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `dp_dibayar` enum('Ya','Tidak') DEFAULT 'Tidak',
  `bukti_dp` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','diterima','selesai','in_progress') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `petugas_id`, `kode_booking`, `nama_pemilik`, `email`, `nomor_wa`, `nama_hewan`, `jenis_hewan`, `ukuran_hewan`, `jenis_hewan_id`, `layanan_id`, `tanggal_masuk`, `tanggal_keluar`, `dp_dibayar`, `bukti_dp`, `catatan`, `status`) VALUES
(53, 17, NULL, 'PH-2026-0001', 'arkan', 'arkan@gmail.com', '6289506700308', 'exy', 'Kucing', '0', NULL, 6, '2026-01-19', '2026-01-20', 'Ya', 'dp_1768804472_865.png', 'cek', 'selesai'),
(54, 17, NULL, 'PH-2026-0002', 'arkan', 'arkan@gmail.com', '6289506700308', 'felix', 'Anjing', '0', NULL, 6, '2026-01-22', '2026-01-23', 'Tidak', NULL, 'cel', 'in_progress'),
(55, 17, NULL, 'PH-2026-0003', 'arkan', 'adb@gmail.com', '62843849389', 'xoxo', 'Anjing', '0', NULL, 6, '2026-01-22', '2026-01-24', 'Tidak', NULL, 'cek', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `catatan_medis`
--

CREATE TABLE `catatan_medis` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `konsultasi_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` varchar(50) NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `resep` text DEFAULT NULL,
  `vaksin` varchar(100) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `catatan_lain` text DEFAULT NULL,
  `dokter_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `makan_pagi` tinyint(1) DEFAULT 0,
  `makan_siang` tinyint(1) DEFAULT 0,
  `makan_malam` tinyint(1) DEFAULT 0,
  `minum` tinyint(1) DEFAULT 0,
  `jalan_jalan` tinyint(1) DEFAULT 0,
  `buang_air` enum('belum','normal','diare','sembelit') DEFAULT 'belum',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `jam_makan_pagi` time DEFAULT NULL,
  `jam_makan_siang` time DEFAULT NULL,
  `jam_makan_malam` time DEFAULT NULL,
  `jam_minum` time DEFAULT NULL,
  `jam_jalan_jalan` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_logs`
--

INSERT INTO `daily_logs` (`id`, `booking_id`, `petugas_id`, `tanggal`, `makan_pagi`, `makan_siang`, `makan_malam`, `minum`, `jalan_jalan`, `buang_air`, `catatan`, `created_at`, `jam_makan_pagi`, `jam_makan_siang`, `jam_makan_malam`, `jam_minum`, `jam_jalan_jalan`) VALUES
(8, 54, 14, '2026-01-22', 1, 1, 0, 0, 0, 'belum', 'cek', '2026-01-22 07:13:50', '10:00:14', '14:35:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `foto`, `keterangan`) VALUES
(15, 'galeri_695237ee29f47.jpg', 'Layanan konsultasi mendalam dengan dokter hewan berpengalaman untuk mendiagnosis dan memberikan solusi kesehatan terbaik bagi sahabat setia Anda.'),
(16, 'galeri_695237f8b88da.png', 'Terletak di lokasi yang mudah dijangkau dengan suasana klinik yang tenang dan nyaman, memberikan rasa aman bagi hewan peliharaan saat menunggu giliran periksa.'),
(17, 'galeri_695237fed7400.png', 'Penanganan medis dan vaksinasi dilakukan secara presisi oleh tim dokter hewan berlisensi untuk menjamin keamanan dan akurasi prosedur kesehatan.'),
(18, 'galeri_69523805b96c7.png', 'Layanan perawatan estetika yang dilakukan dengan peralatan lengkap dan teknik yang aman, memastikan hewan Anda tampil rapi dan menawan.'),
(20, 'galeri_695238157fe34.png', 'Perawatan kebersihan menyeluruh oleh staf profesional untuk menjaga penampilan dan kesehatan kulit serta bulu hewan kesayangan Anda tetap prima.');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slider`
--

CREATE TABLE `hero_slider` (
  `id` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `subjudul` varchar(255) DEFAULT NULL,
  `tombol_text` varchar(50) DEFAULT 'Booking Sekarang',
  `tombol_link` varchar(255) DEFAULT 'booking.php',
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_slider`
--

INSERT INTO `hero_slider` (`id`, `gambar`, `judul`, `subjudul`, `tombol_text`, `tombol_link`, `urutan`, `created_at`) VALUES
(6, '1767149802_Gemini_Generated_Image_g1p3t8g1p3t8g1p3.png', 'Hubungi Kami', 'Punya pertanyaan medis? Chat dokter kami sekarang melalui layanan WhatsApp responsif.', 'Darurat', 'https://wa.me/6285942173668', 1, '2025-12-31 02:56:42'),
(7, '1767668362_A man with a stethoscope around his neck is petting a dog _ Premium AI-generated image.jpg', 'Dokter Berlisensi', 'Kami menghadirkan kasih sayang yang dipadukan dengan keahlian medis dari dokter hewan berlisensi.', 'Check', 'layanan.php', 2, '2025-12-31 02:57:59'),
(8, '1767150059_Animal Care Center of Downers Grove _ RWE Design Build.jpg', 'Lokasi Strategis', 'Berada di lokasi yang mudah ditemukan, memastikan hewan kesayangan Anda mendapatkan bantuan medis secepat mungkin saat dibutuhkan.', 'Lokasi', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.852235712345!2d109.6522357!3d-7.4039689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aab250dbea06f%3A0x67886a3086ca184d!2sKambing%20Kita%20Banjarnegara!5e0!3m2!1sen!2sid!4v1712345678901!', 3, '2025-12-31 03:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_hewan`
--

CREATE TABLE `jenis_hewan` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `aktif` enum('ya','tidak') DEFAULT 'ya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_hewan`
--

INSERT INTO `jenis_hewan` (`id`, `nama`, `aktif`) VALUES
(1, 'Kucing', 'ya'),
(2, 'Anjing', 'ya'),
(7, 'Monyet', 'ya');

-- --------------------------------------------------------

--
-- Table structure for table `kapasitas`
--

CREATE TABLE `kapasitas` (
  `id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `jenis_hewan` varchar(50) NOT NULL,
  `ukuran_hewan` varchar(20) NOT NULL,
  `max_kapasitas` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kapasitas`
--

INSERT INTO `kapasitas` (`id`, `layanan_id`, `jenis_hewan`, `ukuran_hewan`, `max_kapasitas`) VALUES
(1, 1, 'Anjing', 'Kecil', 15),
(2, 1, 'Anjing', 'Sedang', 10),
(3, 1, 'Anjing', 'Besar', 5),
(4, 1, 'Kucing', 'Kecil', 20),
(5, 1, 'Kucing', 'Sedang', 10),
(6, 1, 'Kucing', 'Besar', 5);

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kode_konsultasi` varchar(20) DEFAULT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `jenis_hewan` varchar(50) DEFAULT NULL,
  `topik` varchar(100) DEFAULT NULL,
  `tanggal_janji` date DEFAULT NULL,
  `jam_janji` time DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `balasan_dokter` text DEFAULT NULL,
  `status` enum('pending','diterima','selesai') DEFAULT 'pending',
  `dokter_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id`, `user_id`, `kode_konsultasi`, `nama_pemilik`, `no_wa`, `jenis_hewan`, `topik`, `tanggal_janji`, `jam_janji`, `catatan`, `balasan_dokter`, `status`, `dokter_id`, `created_at`) VALUES
(42, 15, 'KONS-2026-0001', 'Pelanggan', '6289506700308', 'Anjing', 'Demam', '2026-01-14', '15:00:00', 'cek', NULL, 'selesai', NULL, '2026-01-14 07:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi_balasan`
--

CREATE TABLE `konsultasi_balasan` (
  `id` int(11) NOT NULL,
  `konsultasi_id` int(11) NOT NULL,
  `pengirim` enum('user','dokter') NOT NULL,
  `isi` text NOT NULL,
  `dibaca_user` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi_balasan`
--

INSERT INTO `konsultasi_balasan` (`id`, `konsultasi_id`, `pengirim`, `isi`, `dibaca_user`, `created_at`) VALUES
(48, 42, 'dokter', 'hai arkan', 0, '2026-01-14 07:23:22'),
(49, 42, 'user', 'hai dok, hewan ku demam. apa ya obatnya?', 0, '2026-01-14 07:23:51'),
(50, 42, 'dokter', 'di rondap aja', 0, '2026-01-14 07:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `gambar`, `deskripsi`) VALUES
(6, 'Pet Hotel', 'layanan_69522dbe478dc.jpg', 'Berikan pengalaman menginap yang menyenangkan bagi hewan peliharaan Anda saat Anda sedang bepergian. Pet Hotel kami menawarkan suasana yang hangat dan eksklusif, di mana setiap tamu mendapatkan perhatian personal. Bukan sekadar tempat penitipan, kami menghadirkan rumah kedua yang mengutamakan keamanan dan kebahagiaan mereka.'),
(11, 'Grooming', 'layanan_6971d6f5a2775.png', 'Grooming');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_harga`
--

CREATE TABLE `layanan_harga` (
  `id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `jenis_hewan_id` int(11) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan_harga`
--

INSERT INTO `layanan_harga` (`id`, `layanan_id`, `jenis_hewan_id`, `harga_per_hari`) VALUES
(9, 6, 2, 150000.00),
(10, 6, 1, 100000.00),
(13, 11, 2, 120000.00),
(14, 11, 1, 60000.00),
(15, 6, 7, 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_target` enum('petugas','dokter','admin') NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_perawatan`
--

CREATE TABLE `riwayat_perawatan` (
  `id` int(11) NOT NULL,
  `hewan_id` int(11) NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `tanggal_rawat` datetime NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_petugas`
--

CREATE TABLE `riwayat_petugas` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `status_akhir` enum('selesai') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_petugas`
--

INSERT INTO `riwayat_petugas` (`id`, `booking_id`, `petugas_id`, `status_akhir`, `created_at`) VALUES
(1, 50, 14, 'selesai', '2026-01-14 13:14:55'),
(2, 51, 14, 'selesai', '2026-01-14 13:19:06'),
(3, 52, 14, 'selesai', '2026-01-19 13:14:16'),
(4, 53, 14, 'selesai', '2026-01-19 13:49:38'),
(5, 55, 14, 'selesai', '2026-01-22 14:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `tentang`
--

CREATE TABLE `tentang` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tentang`
--

INSERT INTO `tentang` (`id`, `judul`, `isi`, `gambar`, `created_at`) VALUES
(2, 'Tentang Kami', 'Selamat datang di Pethouse, tempat terbaik untuk merawat dan memanjakan hewan peliharaan Anda! Kami percaya bahwa setiap hewan peliharaan layak mendapatkan perhatian, kasih sayang, dan perawatan terbaik.\r\n\r\nDi Pethouse, kami menyediakan berbagai layanan mulai dari grooming, konsultasi kesehatan, hingga penjualan produk berkualitas untuk hewan kesayangan Anda. Tim kami terdiri dari para profesional yang berpengalaman dan mencintai hewan, sehingga setiap hewan yang datang ke Pethouse akan mendapatkan layanan dengan penuh perhatian dan kenyamanan.\r\n\r\nKami berkomitmen untuk menciptakan lingkungan yang ramah hewan dan keluarga, di mana kebahagiaan hewan peliharaan adalah prioritas utama kami. Dengan Pethouse, Anda tidak hanya memberikan perawatan terbaik, tetapi juga pengalaman yang menyenangkan bagi hewan kesayangan Anda.', '1766972972_about.png', '2025-12-23 02:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `nama_hewan` varchar(50) DEFAULT NULL,
  `jenis_hewan` varchar(30) DEFAULT NULL,
  `isi_testimoni` text NOT NULL,
  `foto_hewan` varchar(255) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT 5 CHECK (`rating` between 1 and 5),
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `nama_pemilik`, `nama_hewan`, `jenis_hewan`, `isi_testimoni`, `foto_hewan`, `rating`, `status`, `created_at`) VALUES
(1, 'Lawuk', 'Weli', 'Anjing', 'Bagus Sekali', '6954ce50b9c46_Gemini_Generated_Image_an5tzqan5tzqan5t.png', 5, 'aktif', '2025-12-31 10:59:06'),
(2, 'Agus', 'Joni', 'Kucing', 'Kucingku Sehat!', '6954d0a035902_t1.jpg', 5, 'aktif', '2025-12-31 13:17:35'),
(4, 'Wili', 'Heru', 'Anjing', 'Kurang memuaskan', '6954d0f4c0d42_Gemini_Generated_Image_an5tzqan5tzqan5t.png', 4, 'aktif', '2025-12-31 14:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','petugas','dokter') NOT NULL,
  `nomor_wa` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nomor_wa`, `email`, `created_at`) VALUES
(13, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, '2026-01-06 02:08:24'),
(14, 'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', '6289506700208', NULL, '2026-01-06 04:13:47'),
(15, 'Pelanggan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, NULL, '2026-01-07 06:41:57'),
(16, 'dokter', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dokter', NULL, NULL, '2026-01-07 14:55:18'),
(17, 'arkan', '$2y$10$PgdGEq5hWHVHszCUgVysyeu1YCljizsrXuRkK/QjjUpShKYYdP0pO', 'user', NULL, 'arkanmaulidhananurfalah@gmail.com', '2026-01-07 18:28:13'),
(18, 'Adam', '$2y$10$6XQS.l.YUb8q7zQen6lpW.qdUCGg7yzqstoLvDeRZZB92Oeqz5/IG', 'user', NULL, 'adam@gmail.com', '2026-01-08 14:24:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `layanan_id` (`layanan_id`),
  ADD KEY `jenis_hewan_id` (`jenis_hewan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `konsultasi_id` (`konsultasi_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dokter_id` (`dokter_id`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `petugas_id` (`petugas_id`);

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
  ADD UNIQUE KEY `unique_kombinasi` (`layanan_id`,`jenis_hewan`,`ukuran_hewan`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_konsultasi` (`kode_konsultasi`),
  ADD KEY `dokter_id` (`dokter_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_id` (`konsultasi_id`);

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
  ADD UNIQUE KEY `layanan_id` (`layanan_id`,`jenis_hewan_id`),
  ADD KEY `jenis_hewan_id` (`jenis_hewan_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `riwayat_perawatan`
--
ALTER TABLE `riwayat_perawatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hewan_id` (`hewan_id`),
  ADD KEY `petugas_id` (`petugas_id`);

--
-- Indexes for table `riwayat_petugas`
--
ALTER TABLE `riwayat_petugas`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hero_slider`
--
ALTER TABLE `hero_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jenis_hewan`
--
ALTER TABLE `jenis_hewan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kapasitas`
--
ALTER TABLE `kapasitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `layanan_harga`
--
ALTER TABLE `layanan_harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `riwayat_perawatan`
--
ALTER TABLE `riwayat_perawatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_petugas`
--
ALTER TABLE `riwayat_petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tentang`
--
ALTER TABLE `tentang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`jenis_hewan_id`) REFERENCES `jenis_hewan` (`id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `catatan_medis`
--
ALTER TABLE `catatan_medis`
  ADD CONSTRAINT `catatan_medis_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `catatan_medis_ibfk_2` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `catatan_medis_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `catatan_medis_ibfk_4` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD CONSTRAINT `daily_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_logs_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `konsultasi_balasan`
--
ALTER TABLE `konsultasi_balasan`
  ADD CONSTRAINT `konsultasi_balasan_ibfk_1` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `layanan_harga`
--
ALTER TABLE `layanan_harga`
  ADD CONSTRAINT `layanan_harga_ibfk_1` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `layanan_harga_ibfk_2` FOREIGN KEY (`jenis_hewan_id`) REFERENCES `jenis_hewan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_perawatan`
--
ALTER TABLE `riwayat_perawatan`
  ADD CONSTRAINT `riwayat_perawatan_ibfk_1` FOREIGN KEY (`hewan_id`) REFERENCES `hewan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_perawatan_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
