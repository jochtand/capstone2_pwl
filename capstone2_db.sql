-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 05:37 AM
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
-- Database: `capstone2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bhp`
--

CREATE TABLE `bhp` (
  `id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bhp`
--

INSERT INTO `bhp` (`id`, `ruangan_id`, `nama_barang`, `stok`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tinta Printer Epson Hitam', 9, 'Botol', '2026-05-25 13:08:38', '2026-06-02 05:55:03'),
(2, 2, 'Kabel UTP Cat6', 150, 'Meter', '2026-05-25 13:08:38', '2026-05-25 13:08:38'),
(3, 1, 'Kabel UTP (Meter)', 150, '', '2026-06-01 15:16:38', '2026-06-01 15:16:38'),
(4, 1, 'Konektor RJ45 (Pieces)', 46, '', '2026-06-01 15:16:38', '2026-06-02 06:45:13'),
(5, 2, 'Tinta Printer Hitam', 10, '', '2026-06-01 15:16:38', '2026-06-01 15:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengadaan`
--

CREATE TABLE `detail_pengadaan` (
  `id` int(11) NOT NULL,
  `draft_id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `link_pembelian` varchar(255) DEFAULT NULL,
  `inventaris_diganti_id` int(11) DEFAULT NULL,
  `status_approval` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `tgl_diterima` date DEFAULT NULL,
  `status_kedatangan` enum('Belum Datang','Diterima') DEFAULT 'Belum Datang',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Pending','Disetujui','Ditolak') DEFAULT 'Pending',
  `tanggal_terima` date DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pengadaan`
--

INSERT INTO `detail_pengadaan` (`id`, `draft_id`, `nama_barang`, `harga`, `jumlah`, `link_pembelian`, `inventaris_diganti_id`, `status_approval`, `tgl_diterima`, `status_kedatangan`, `created_at`, `updated_at`, `status`, `tanggal_terima`, `label`) VALUES
(1, 2, 'Keyboard', 150000.00, 5, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 05:49:24', '2026-06-02 05:54:05', 'Disetujui', '2026-06-24', 'INV-2026-E448-1'),
(2, 3, 'Keyboard', 150000.00, 5, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 06:34:11', '2026-06-02 06:41:00', 'Disetujui', '2026-06-04', 'INV-2026-1OAQ-2'),
(3, 3, 'Mouse', 100000.00, 5, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 06:34:11', '2026-06-02 06:37:49', 'Ditolak', NULL, NULL),
(5, 4, 'Kabel HDMI', 60000.00, 12, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 16:07:20', '2026-06-02 16:07:20', 'Pending', NULL, NULL),
(6, 5, 'Speaker', 150000.00, 12, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 16:14:35', '2026-06-02 16:14:35', 'Pending', NULL, NULL),
(7, 5, 'Kabel Speaker', 20000.00, 12, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 16:14:35', '2026-06-02 16:14:35', 'Pending', NULL, NULL),
(13, 11, 'sd', 1111.00, 4, NULL, NULL, 'Pending', NULL, 'Belum Datang', '2026-06-02 17:04:51', '2026-06-02 17:04:51', 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `draft_pengadaan`
--

CREATE TABLE `draft_pengadaan` (
  `id` int(11) NOT NULL,
  `kepala_lab_id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `status` enum('Draft','Locked','Finalized') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `draft_pengadaan`
--

INSERT INTO `draft_pengadaan` (`id`, `kepala_lab_id`, `tahun`, `tgl_pengajuan`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2026', '2026-05-26', 'Draft', '2026-05-26 02:59:02', '2026-05-26 02:59:02'),
(2, 2, '2026', '2026-06-02', 'Finalized', '2026-06-02 05:49:24', '2026-06-02 05:50:27'),
(3, 2, '2026', '2026-06-02', 'Finalized', '2026-06-02 06:34:11', '2026-06-02 06:37:49'),
(4, 2, '2026', '2026-06-02', 'Draft', '2026-06-02 16:07:20', '2026-06-02 16:07:20'),
(5, 2, '2026', '2026-06-02', 'Draft', '2026-06-02 16:14:35', '2026-06-02 16:14:35'),
(11, 2, '2026', '2026-06-03', 'Draft', '2026-06-02 17:04:51', '2026-06-02 17:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `inventaris`
--

CREATE TABLE `inventaris` (
  `id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kode_label` varchar(100) DEFAULT NULL,
  `tgl_penerimaan` date DEFAULT NULL,
  `kondisi` enum('Baik','Rusak','Maintenance') DEFAULT 'Baik',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventaris`
--

INSERT INTO `inventaris` (`id`, `ruangan_id`, `nama_barang`, `kode_label`, `tgl_penerimaan`, `kondisi`, `created_at`, `updated_at`) VALUES
(1, 1, 'PC Desktop Dell Optiplex', NULL, NULL, 'Baik', '2026-05-25 13:08:38', '2026-05-25 13:08:38'),
(2, 1, 'Monitor Samsung 24 Inch', NULL, NULL, 'Rusak', '2026-05-25 13:08:38', '2026-05-25 13:08:38'),
(3, 2, 'Router Mikrotik RB750Gr3', NULL, NULL, 'Baik', '2026-05-25 13:08:38', '2026-05-25 13:08:38'),
(4, 1, 'PC Server Dell', NULL, NULL, 'Baik', '2026-06-01 15:16:38', '2026-06-01 15:16:38'),
(5, 1, 'Proyektor Epson', NULL, NULL, 'Baik', '2026-06-01 15:16:38', '2026-06-02 06:45:13'),
(6, 2, 'Router Mikrotik', NULL, NULL, 'Baik', '2026-06-01 15:16:38', '2026-06-01 15:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `log_maintenance`
--

CREATE TABLE `log_maintenance` (
  `id` int(11) NOT NULL,
  `inventaris_id` int(11) NOT NULL,
  `staf_lab_id` int(11) NOT NULL,
  `tgl_maintenance` date NOT NULL,
  `deskripsi` text NOT NULL,
  `bhp_id` int(11) DEFAULT NULL,
  `jumlah_bhp_dipakai` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_log`
--

CREATE TABLE `maintenance_log` (
  `id` int(11) NOT NULL,
  `inventaris_id` int(11) NOT NULL,
  `tanggal_maintenance` date NOT NULL,
  `deskripsi` text NOT NULL,
  `kondisi_sesudah` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `bhp_id` int(11) DEFAULT NULL,
  `jumlah_bhp` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_log`
--

INSERT INTO `maintenance_log` (`id`, `inventaris_id`, `tanggal_maintenance`, `deskripsi`, `kondisi_sesudah`, `bhp_id`, `jumlah_bhp`, `created_at`) VALUES
(1, 5, '2026-06-01', 'Ganti konektor RJ45', 'Baik', 4, 2, '2026-06-01 15:23:32'),
(2, 5, '2026-06-02', 'perbaikan', 'Rusak Ringan', 1, 1, '2026-06-02 05:55:03'),
(3, 5, '2026-06-02', 'perbaikan', 'Baik', 4, 2, '2026-06-02 06:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `nama_ruangan`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, 'Laboratorium Komputer 2', 'Gedung A Lantai 2', '2026-05-25 12:38:39', '2026-06-02 15:21:20'),
(2, 'Laboratorium Jaringan', 'Gedung B Lantai 1', '2026-05-25 12:38:39', '2026-05-25 12:38:39'),
(3, 'Lab Komputer 1', '', '2026-06-01 15:16:38', '2026-06-01 15:16:38'),
(4, 'Lab Komputer 2', '', '2026-06-01 15:16:38', '2026-06-01 15:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Administrator','Kepala Laboratorium','Kaprodi','Staf Administrasi','Staf Laboratorium') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', 'admin@lab.com', 'password123', 'Administrator', '2026-05-25 12:38:39', '2026-06-02 15:21:33'),
(2, 'Bapak Kepala Lab', 'kepalalab@lab.com', 'password123', 'Kepala Laboratorium', '2026-05-31 18:12:28', '2026-05-31 18:12:28'),
(3, 'Bapak Kaprodi', 'kaprodi@lab.com', 'password123', 'Kaprodi', '2026-06-01 04:34:04', '2026-06-01 04:34:04'),
(4, 'Bapak Staf Admin', 'adminstaf@lab.com', 'password123', 'Staf Administrasi', '2026-06-01 14:34:58', '2026-06-01 14:34:58'),
(5, 'Ibu Staf Lab', 'staflab@lab.com', 'password123', 'Staf Laboratorium', '2026-06-01 14:35:23', '2026-06-01 14:35:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bhp`
--
ALTER TABLE `bhp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `draft_id` (`draft_id`),
  ADD KEY `inventaris_diganti_id` (`inventaris_diganti_id`);

--
-- Indexes for table `draft_pengadaan`
--
ALTER TABLE `draft_pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kepala_lab_id` (`kepala_lab_id`);

--
-- Indexes for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `log_maintenance`
--
ALTER TABLE `log_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventaris_id` (`inventaris_id`),
  ADD KEY `staf_lab_id` (`staf_lab_id`),
  ADD KEY `bhp_id` (`bhp_id`);

--
-- Indexes for table `maintenance_log`
--
ALTER TABLE `maintenance_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bhp`
--
ALTER TABLE `bhp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `draft_pengadaan`
--
ALTER TABLE `draft_pengadaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventaris`
--
ALTER TABLE `inventaris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_maintenance`
--
ALTER TABLE `log_maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_log`
--
ALTER TABLE `maintenance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bhp`
--
ALTER TABLE `bhp`
  ADD CONSTRAINT `bhp_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pengadaan`
--
ALTER TABLE `detail_pengadaan`
  ADD CONSTRAINT `detail_pengadaan_ibfk_1` FOREIGN KEY (`draft_id`) REFERENCES `draft_pengadaan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pengadaan_ibfk_2` FOREIGN KEY (`inventaris_diganti_id`) REFERENCES `inventaris` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `draft_pengadaan`
--
ALTER TABLE `draft_pengadaan`
  ADD CONSTRAINT `draft_pengadaan_ibfk_1` FOREIGN KEY (`kepala_lab_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD CONSTRAINT `inventaris_ibfk_1` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_maintenance`
--
ALTER TABLE `log_maintenance`
  ADD CONSTRAINT `log_maintenance_ibfk_1` FOREIGN KEY (`inventaris_id`) REFERENCES `inventaris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_maintenance_ibfk_2` FOREIGN KEY (`staf_lab_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_maintenance_ibfk_3` FOREIGN KEY (`bhp_id`) REFERENCES `bhp` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
