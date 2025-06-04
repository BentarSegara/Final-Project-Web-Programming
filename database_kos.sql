-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 08:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_kos`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_kamar_kos`
--

CREATE TABLE `data_kamar_kos` (
  `id_dkk` int(11) NOT NULL,
  `id_pemilik` int(11) DEFAULT NULL,
  `nama_kos` varchar(100) NOT NULL,
  `alamat_kos` text NOT NULL,
  `nomor_kamar` varchar(20) NOT NULL,
  `tipe_kamar` varchar(50) DEFAULT NULL,
  `luas_kamar` varchar(20) DEFAULT NULL,
  `harga_sewa_bulanan_kamar` decimal(12,2) NOT NULL,
  `status_ketersediaan_kamar` enum('tersedia','terisi','perbaikan') NOT NULL DEFAULT 'tersedia',
  `deskripsi_kamar` text DEFAULT NULL,
  `kapasitas_maksimal_kamar` int(11) DEFAULT 1,
  `foto_kamar` text DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diperbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_sewa_penyewa`
--

CREATE TABLE `data_sewa_penyewa` (
  `id_dsp` int(11) NOT NULL,
  `id_dkk` int(11) NOT NULL,
  `id_pengguna_penyewa` int(11) NOT NULL,
  `tanggal_mulai_sewa` date NOT NULL,
  `tanggal_akhir_sewa` date DEFAULT NULL,
  `harga_sewa_disepakati` decimal(12,2) NOT NULL,
  `uang_jaminan_sewa` decimal(12,2) DEFAULT 0.00,
  `status_sewa` enum('aktif','berakhir','dibatalkan') NOT NULL DEFAULT 'aktif',
  `catatan_sewa` text DEFAULT NULL,
  `tanggal_transaksi_sewa_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_transaksi_sewa_diperbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id_fasilitas` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `deskripsi_fasilitas` text DEFAULT NULL,
  `ikon_fasilitas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kamar_memiliki_fasilitas`
--

CREATE TABLE `kamar_memiliki_fasilitas` (
  `id_dkk` int(11) NOT NULL,
  `id_fasilitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keluhan`
--

CREATE TABLE `keluhan` (
  `id_keluhan` int(11) NOT NULL,
  `id_dsp` int(11) NOT NULL,
  `judul_keluhan` varchar(255) NOT NULL,
  `deskripsi_keluhan` text NOT NULL,
  `foto_keluhan` varchar(255) DEFAULT NULL,
  `tanggal_laporan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_keluhan` enum('baru','diproses','selesai','ditolak') NOT NULL DEFAULT 'baru',
  `tanggapan_pemilik` text DEFAULT NULL,
  `ditanggapi_oleh` int(11) DEFAULT NULL,
  `tanggal_tanggapan` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_dsp` int(11) NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL,
  `jumlah_pembayaran` decimal(12,2) NOT NULL,
  `periode_pembayaran_mulai` date NOT NULL,
  `periode_pembayaran_akhir` date NOT NULL,
  `sisa_tagihan` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('transfer','tunai','gopay','ovo','lainnya') NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('lunas','belum_lunas','menunggu_verifikasi','ditolak') NOT NULL DEFAULT 'menunggu_verifikasi',
  `catatan_pembayaran` text DEFAULT NULL,
  `diverifikasi_oleh` int(11) DEFAULT NULL,
  `tanggal_verifikasi` timestamp NULL DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `id_pemilik_kos` int(11) NOT NULL,
  `nama_properti_pengeluaran` varchar(100) DEFAULT NULL,
  `alamat_properti_pengeluaran` text DEFAULT NULL,
  `tanggal_pengeluaran` date NOT NULL,
  `deskripsi_pengeluaran` varchar(255) NOT NULL,
  `jumlah_pengeluaran` decimal(12,2) NOT NULL,
  `kategori_pengeluaran` varchar(50) DEFAULT NULL,
  `bukti_pengeluaran` varchar(255) DEFAULT NULL,
  `dicatat_oleh` int(11) NOT NULL,
  `tanggal_dicatat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `peran` enum('pemilik','admin','penyewa') NOT NULL DEFAULT 'penyewa',
  `foto_profil` varchar(255) DEFAULT NULL,
  `nomor_ktp` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_asal` text DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `kontak_darurat_nama` varchar(100) DEFAULT NULL,
  `kontak_darurat_telepon` varchar(20) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `rekening_bank_pemilik` varchar(50) DEFAULT NULL,
  `nomor_rekening_pemilik` varchar(50) DEFAULT NULL,
  `atas_nama_rekening_pemilik` varchar(100) DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diperbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_kamar_kos`
--
ALTER TABLE `data_kamar_kos`
  ADD PRIMARY KEY (`id_dkk`),
  ADD KEY `id_pemilik` (`id_pemilik`);

--
-- Indexes for table `data_sewa_penyewa`
--
ALTER TABLE `data_sewa_penyewa`
  ADD PRIMARY KEY (`id_dsp`),
  ADD KEY `id_dkk` (`id_dkk`),
  ADD KEY `id_pengguna_penyewa` (`id_pengguna_penyewa`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id_fasilitas`),
  ADD UNIQUE KEY `nama_fasilitas` (`nama_fasilitas`);

--
-- Indexes for table `kamar_memiliki_fasilitas`
--
ALTER TABLE `kamar_memiliki_fasilitas`
  ADD PRIMARY KEY (`id_dkk`,`id_fasilitas`),
  ADD KEY `id_fasilitas` (`id_fasilitas`);

--
-- Indexes for table `keluhan`
--
ALTER TABLE `keluhan`
  ADD PRIMARY KEY (`id_keluhan`),
  ADD KEY `id_dsp` (`id_dsp`),
  ADD KEY `ditanggapi_oleh` (`ditanggapi_oleh`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_dsp` (`id_dsp`),
  ADD KEY `diverifikasi_oleh` (`diverifikasi_oleh`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `id_pemilik_kos` (`id_pemilik_kos`),
  ADD KEY `dicatat_oleh` (`dicatat_oleh`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_kamar_kos`
--
ALTER TABLE `data_kamar_kos`
  MODIFY `id_dkk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_sewa_penyewa`
--
ALTER TABLE `data_sewa_penyewa`
  MODIFY `id_dsp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id_fasilitas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keluhan`
--
ALTER TABLE `keluhan`
  MODIFY `id_keluhan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_kamar_kos`
--
ALTER TABLE `data_kamar_kos`
  ADD CONSTRAINT `data_kamar_kos_ibfk_1` FOREIGN KEY (`id_pemilik`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `data_sewa_penyewa`
--
ALTER TABLE `data_sewa_penyewa`
  ADD CONSTRAINT `data_sewa_penyewa_ibfk_1` FOREIGN KEY (`id_dkk`) REFERENCES `data_kamar_kos` (`id_dkk`) ON UPDATE CASCADE,
  ADD CONSTRAINT `data_sewa_penyewa_ibfk_2` FOREIGN KEY (`id_pengguna_penyewa`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE;

--
-- Constraints for table `kamar_memiliki_fasilitas`
--
ALTER TABLE `kamar_memiliki_fasilitas`
  ADD CONSTRAINT `kamar_memiliki_fasilitas_ibfk_1` FOREIGN KEY (`id_dkk`) REFERENCES `data_kamar_kos` (`id_dkk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kamar_memiliki_fasilitas_ibfk_2` FOREIGN KEY (`id_fasilitas`) REFERENCES `fasilitas` (`id_fasilitas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keluhan`
--
ALTER TABLE `keluhan`
  ADD CONSTRAINT `keluhan_ibfk_1` FOREIGN KEY (`id_dsp`) REFERENCES `data_sewa_penyewa` (`id_dsp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keluhan_ibfk_2` FOREIGN KEY (`ditanggapi_oleh`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_dsp`) REFERENCES `data_sewa_penyewa` (`id_dsp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`id_pemilik_kos`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`dicatat_oleh`) REFERENCES `pengguna` (`id_pengguna`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
