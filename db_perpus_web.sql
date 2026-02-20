-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 07:13 PM
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
-- Database: `db_perpus_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok_total` int(11) NOT NULL DEFAULT 0,
  `stok_tersedia` int(11) NOT NULL DEFAULT 0,
  `status_buku` enum('tersedia','rusak','hilang') DEFAULT 'tersedia',
  `gambar` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `kode_buku`, `judul_buku`, `penulis`, `penerbit`, `id_kategori`, `tahun_terbit`, `stok_total`, `stok_tersedia`, `status_buku`, `gambar`, `created_at`) VALUES
(3, '', 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 'Warner Books', 4, '1997', 0, 10, 'tersedia', '1770392470_Rich dad poor dad.jpg', '2026-02-06 15:41:10'),
(6, 'BK-62987', 'The Lean Startup', 'Eric Ries', 'Crown Business', 4, '2011', 0, 10, 'tersedia', '1866667759_The Lean Startup.jpg', '2026-02-06 15:49:28'),
(7, 'BK-17859', 'Zero to One', 'Peter Thiel', 'Crown Business', 4, '2014', 0, 10, 'tersedia', '1204362245_Zero to One.jpg', '2026-02-06 15:50:49'),
(8, 'BK-86127', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 1, '2008', 0, 10, 'tersedia', '198009108_Clean Code.jpg', '2026-02-06 15:53:14'),
(9, 'BK-17733', 'You Don’t Know JS (Yet)', 'Kyle Simpson', 'O’Reilly Media', 1, '2015', 0, 10, 'tersedia', '1371104016_You Don’t Know JS (Yet).jpg', '2026-02-06 15:54:18'),
(10, 'BK-49555', 'The Pragmatic Programmer', 'Andrew Hunt & David Thomas', 'Addison-Wesley', 1, '1999', 0, 10, 'tersedia', '1476135590_The Pragmatic Programmer.jpg', '2026-02-06 15:55:07'),
(11, 'BK-44450', 'A Brief History of Time', 'Stephen Hawking', 'Bantam Books', 2, '1988', 0, 10, 'tersedia', '1025166163_A Brief History of Time.jpg', '2026-02-06 16:02:28'),
(12, 'BK-91759', 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', 2, '2011', 0, 10, 'tersedia', '633117079_Sapiens  A Brief History of Humankind.jpg', '2026-02-06 16:03:14'),
(13, 'BK-16606', 'Cosmos', 'Carl Sagan', 'Random House', 2, '1980', 0, 10, 'tersedia', '829640973_Cosmos.jpg', '2026-02-06 16:04:13'),
(14, 'BK-90246', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 3, '2005', 0, 10, 'tersedia', '1646217688_Laskar Pelangi.jpg', '2026-02-06 16:06:19'),
(15, 'BK-98467', '1984', 'George Orwell', 'Secker & Warburg', 3, '1949', 0, 10, 'tersedia', '1334951800_1984.jpg', '2026-02-06 16:07:05'),
(16, 'BK-97622', 'The Alchemist', 'Paulo Coelho', 'HarperCollins', 3, '1988', 0, 10, 'tersedia', '1783104375_The Alchemist.jpeg', '2026-02-06 16:07:46'),
(17, 'BK-16186', 'One Piece', 'Eiichiro Oda', 'Shueisha', 6, '1997', 0, 10, 'tersedia', '1728381052_one piece.jpg', '2026-02-06 16:09:59'),
(18, 'BK-98772', 'Naruto', 'Masashi Kishimoto', 'Shueisha', 6, '1999', 0, 10, 'tersedia', '782443477_Naruto.jpg', '2026-02-06 16:10:30'),
(19, 'BK-59585', 'Akira Toriyama', 'Akira Toriyama', 'Shueisha', 6, '1984', 0, 10, 'tersedia', '215593915_Dragon Ball.jpg', '2026-02-06 16:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Pemrograman'),
(2, 'Sains'),
(3, 'Novel'),
(4, 'Bisnis'),
(6, 'Komik');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `kode_transaksi` varchar(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status_peminjaman` enum('diajukan','dipinjam','kembali','ditolak') DEFAULT 'diajukan',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `kode_transaksi`, `id_user`, `id_buku`, `tgl_pengajuan`, `tgl_pinjam`, `tgl_jatuh_tempo`, `tgl_kembali`, `denda`, `status_peminjaman`, `keterangan`) VALUES
(4, 'TRX-1771180335', 5, 16, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(5, 'TRX-1771180348', 5, 19, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(6, 'TRX-1771182734', 5, 17, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(7, 'TRX-1771182928', 5, 13, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(8, 'TRX-1771257119', 5, 11, '0000-00-00', '2026-02-16', '2026-02-10', '2026-02-16', 6000, 'kembali', NULL),
(9, 'TRX-1771257957', 5, 11, '0000-00-00', '2026-02-16', '2026-02-09', '2026-02-16', 7000, 'kembali', NULL),
(10, 'TRX-1771258253', 5, 8, '0000-00-00', '2026-02-16', '2026-02-08', '2026-02-16', 8000, 'kembali', NULL),
(11, 'TRX-1771258265', 5, 9, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(12, 'TRX-1771258719', 5, 16, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(13, 'TRX-1771258753', 5, 18, '0000-00-00', '2026-02-16', '2026-02-23', '2026-02-16', 0, 'kembali', NULL),
(14, 'TRX-1771259091', 5, 16, '0000-00-00', '2026-02-16', '2026-02-10', '2026-02-16', 6000, 'kembali', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nomor_identitas` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','anggota') NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nomor_identitas`, `nama_lengkap`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(5, '312410376', 'Abiln', NULL, '$2y$10$WGr2YKZ.mPRO85ieVIpcH.zlMOfGb7mXKxwXdTEUE4GyDaarpYhBm', 'anggota', 'aktif', '2026-02-10 14:42:02'),
(7, '99999999', 'Super Admin', NULL, '$2y$10$dQeGZISoGn5hp/THExjgd.4mEH5sdI0kXnmhwYEqy9WbKuUFAeUNy', 'admin', 'aktif', '2026-02-10 15:20:41');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id_wishlist`, `id_user`, `id_buku`, `created_at`) VALUES
(8, 5, 19, '2026-02-15 19:02:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nomor_identitas` (`nomor_identitas`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
