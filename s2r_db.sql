-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 08:31 AM
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
-- Database: `s2r_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kendaraan`
--

CREATE TABLE `tbl_kendaraan` (
  `id_kendaraan` int(11) NOT NULL,
  `nama_kendaraan` varchar(255) NOT NULL,
  `harga_kendaraan` int(255) NOT NULL,
  `img_kendaraan` varchar(255) NOT NULL,
  `stok_kendaraan` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kendaraan`
--

INSERT INTO `tbl_kendaraan` (`id_kendaraan`, `nama_kendaraan`, `harga_kendaraan`, `img_kendaraan`, `stok_kendaraan`) VALUES
(1, 'beat dealux', 150000, 'assets/img/beat dealux.png', 1),
(2, 'beat street', 180000, 'assets/img/beat street.png', 3),
(3, 'scoopy', 150000, 'assets/img/scoopy.png', 3),
(4, 'vario 150', 100000, 'assets/img/vario 150.jpg', 2),
(5, 'aerox', 150000, 'assets/img/aerox.webp', 6),
(6, 'nmax', 150000, 'assets/img/nmax.png', 4),
(7, 'mio', 100000, 'assets/img/mio.jpg', 4),
(8, 'pcx', 180000, 'assets/img/pcx.png', 4),
(9, 'vario 160', 180000, 'assets/img/vario 160.png', 2),
(15, 'avanza', 350000, 'assets/img/avanza.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pembayaran`
--

CREATE TABLE `tbl_pembayaran` (
  `id_jenis_bayar` int(11) NOT NULL,
  `nama_pembayaran` varchar(50) NOT NULL,
  `no_rek` varchar(20) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pembayaran`
--

INSERT INTO `tbl_pembayaran` (`id_jenis_bayar`, `nama_pembayaran`, `no_rek`, `nama_penerima`) VALUES
(1, 'Dana', '089876584532', 'REZA SOFT SPOKEN'),
(2, 'BCA', '12352647895', 'AKHYANA MANGKUALAM');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pesanan`
--

CREATE TABLE `tbl_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `nama_penyewa` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `id_kendaraan` int(11) NOT NULL,
  `id_pembayaran` int(11) DEFAULT NULL,
  `total_pesanan` decimal(10,2) DEFAULT NULL,
  `status_pesanan` enum('pending','dibayar','batal') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pesanan`
--

INSERT INTO `tbl_pesanan` (`id_pesanan`, `nama_penyewa`, `nomor_telepon`, `tgl_pinjam`, `tgl_kembali`, `id_kendaraan`, `id_pembayaran`, `total_pesanan`, `status_pesanan`, `created_at`, `id_user`) VALUES
(7, 'dimas skena', '08888008800', '2024-12-09', '2024-12-11', 2, 2, 360000.00, 'pending', '2024-12-09 11:44:41', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username_user` varchar(255) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `password_user` varchar(255) NOT NULL,
  `role_user` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username_user`, `email_user`, `password_user`, `role_user`) VALUES
(1, 'admin', 'admin123@gmail.com', '$2y$10$bLqK3GJXS93s8456cj3FSeBWIeY7qyGA/v3BSavz9nmxtz.qoWxXu', 'admin'),
(2, 'yash12', 'yash@gmail.com', '$2y$10$Ncwjq38AQlU013GNe2kGUuc13WL1EXqiHP1o.Ai8AgQMHRVtVVhLW', 'user'),
(3, 'tomy11', 'tomy@gmail.com', '$2y$10$WSRZXwy3aZuQ0Wk5xjcpl.EGwwN.NBMFSqgdw15u5NmWKHLA.Ca8m', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_kendaraan`
--
ALTER TABLE `tbl_kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`);

--
-- Indexes for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD PRIMARY KEY (`id_jenis_bayar`);

--
-- Indexes for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_id_kendaraan` (`id_kendaraan`),
  ADD KEY `id_jenis_bayar` (`id_pembayaran`),
  ADD KEY `fk_user` (`id_user`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_kendaraan`
--
ALTER TABLE `tbl_kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  MODIFY `id_jenis_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_pesanan`
--
ALTER TABLE `tbl_pesanan`
  ADD CONSTRAINT `fk_id_kendaraan` FOREIGN KEY (`id_kendaraan`) REFERENCES `tbl_kendaraan` (`id_kendaraan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
