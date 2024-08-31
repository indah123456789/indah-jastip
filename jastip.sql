-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2024 pada 04.27
-- Versi server: 10.4.16-MariaDB
-- Versi PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jastip`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `notlp` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `nama`, `alamat`, `notlp`, `email`, `password`, `profil`) VALUES
(1, 'admin_ambon', 'Admin Ambon', 'Jl. Ambon No. 1', '081234567890', 'admin_ambon@example.com', '$2y$10$Pk0G5.wDz90U7/Syzbp5he51DP4wJZ/62YvjFZGyDyA9jciuaDF3.', 'admin_ambon_profil.png'),
(2, 'admin_jakarta', 'Admin Jakarta', 'Jl. Jakarta No. 1', '081234567891', 'admin_jakarta@example.com', '$2y$10$aLHI4uAgsaO.0m6ZHlo9D.LJm1UWVzohTsjJcwOHaO8hU413KNNqS', 'admin_jakarta_profil.png'),
(3, 'admin_bandung', 'Admin Bandung', 'Jl. Bandung No. 2', '081234567892', 'admin_bandung@example.com', '$2y$10$j.jk6fqbrujAGfd8IObapuXPrK3N.8dKAPjFi8iwb9JmtANk0Eb7a', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `bdgtujuan`
--

CREATE TABLE `bdgtujuan` (
  `id_tujuan` int(11) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bdgtujuan`
--

INSERT INTO `bdgtujuan` (`id_tujuan`, `tujuan`, `harga`) VALUES
(1, 'Ambon', '13000.00'),
(2, 'Tulehu, Tial', '16000.00'),
(3, 'Gemba, Kairatu', '18000.00'),
(4, 'Waihatu, Waisarisa', '18000.00'),
(5, 'Piru', '18000.00'),
(6, 'Kawa', '18000.00'),
(7, 'Masohi', '18000.00'),
(8, 'Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa', '21000.00'),
(9, 'Luhu, Iha', '20000.00'),
(10, 'Sirisori', '22000.00'),
(11, 'Katapang', '19000.00'),
(12, 'Olas', '20000.00'),
(13, 'Bula', '18000.00'),
(14, 'Malaku', '18000.00'),
(15, 'Wahai', '18000.00'),
(16, 'Hualoi', '18000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jastipbdg`
--

CREATE TABLE `jastipbdg` (
  `id` varchar(20) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `jumlah_paket` int(11) NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `biaya_paket` decimal(15,2) NOT NULL,
  `total_biaya` decimal(15,2) NOT NULL,
  `tgl_pengirim` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jastipbdg`
--

INSERT INTO `jastipbdg` (`id`, `tujuan`, `nama_pengirim`, `no_hp`, `jumlah_paket`, `berat`, `biaya_paket`, `total_biaya`, `tgl_pengirim`) VALUES
('BDG2024081401', 'Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa', 'John Doe', '081234567890', 2, '5.50', '21000.00', '115500.00', '2024-08-14 11:37:34'),
('BDG2024081802', 'Malaku', 'yanto', '121', 21, '21.00', '18000.00', '378000.00', '2024-08-18 15:38:00'),
('BDG2024082603', 'Ambon', 'rifal', '08121415111', 1, '10.00', '13000.00', '130000.00', '2024-08-26 11:07:00');

--
-- Trigger `jastipbdg`
--
DELIMITER $$
CREATE TRIGGER `before_insert_jastipbdg` BEFORE INSERT ON `jastipbdg` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    SET last_id = (SELECT COUNT(*) FROM jastipbdg) + 1;
    SET NEW.id = CONCAT('BDG', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(last_id, 2, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jastipjkt`
--

CREATE TABLE `jastipjkt` (
  `id` varchar(20) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `jumlah_paket` int(11) NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `biaya_paket` decimal(15,2) NOT NULL,
  `total_biaya` decimal(15,2) NOT NULL,
  `tgl_pengirim` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jastipjkt`
--

INSERT INTO `jastipjkt` (`id`, `tujuan`, `nama_pengirim`, `no_hp`, `jumlah_paket`, `berat`, `biaya_paket`, `total_biaya`, `tgl_pengirim`) VALUES
('JKT2024082601', 'Ambon', 'rifal', '08121415111', 1, '12.00', '13000.00', '156000.00', '2024-08-26 10:14:00');

--
-- Trigger `jastipjkt`
--
DELIMITER $$
CREATE TRIGGER `before_insert_jastipjkt` BEFORE INSERT ON `jastipjkt` FOR EACH ROW BEGIN
    DECLARE last_id INT;
    SET last_id = (SELECT COUNT(*) FROM jastipjkt) + 1;
    SET NEW.id = CONCAT('JKT', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(last_id, 2, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jkttujuan`
--

CREATE TABLE `jkttujuan` (
  `id_tujuan` int(11) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jkttujuan`
--

INSERT INTO `jkttujuan` (`id_tujuan`, `tujuan`, `harga`) VALUES
(1, 'Ambon', '13000.00'),
(2, 'Tulehu, Tial', '16000.00'),
(3, 'Gemba, Kairatu', '18000.00'),
(4, 'Waihatu, Waisarisa', '18000.00'),
(5, 'Piru', '18000.00'),
(6, 'Kawa', '18000.00'),
(7, 'Masohi', '16000.00'),
(8, 'Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa', '19000.00'),
(9, 'Luhu, Iha', '20000.00'),
(10, 'Sirisori', '20000.00'),
(11, 'Katapang', '19000.00'),
(12, 'Olas', '18000.00'),
(13, 'Bula', '18000.00'),
(14, 'Malaku', '16000.00'),
(15, 'Wahai', '18000.00'),
(16, 'Hualoi', '18000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sortirambon`
--

CREATE TABLE `sortirambon` (
  `id_sortir` int(11) NOT NULL,
  `id` varchar(20) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `jumlah_paket` int(11) NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `biaya_paket` decimal(15,2) NOT NULL,
  `total_biaya` decimal(15,2) NOT NULL,
  `tgl_pengirim` datetime NOT NULL,
  `sttsbrg` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sortirambon`
--

INSERT INTO `sortirambon` (`id_sortir`, `id`, `tujuan`, `nama_pengirim`, `no_hp`, `jumlah_paket`, `berat`, `biaya_paket`, `total_biaya`, `tgl_pengirim`, `sttsbrg`, `status`) VALUES
(18, 'BDG2024082603', 'Ambon', 'rifal', '08121415111', 1, '10.00', '13000.00', '130000.00', '2024-08-26 11:07:00', 'Pending..', 'Pending..'),
(19, 'JKT2024082601', 'Ambon', 'rifal', '08121415111', 1, '12.00', '13000.00', '156000.00', '2024-08-26 10:14:00', 'Pending..', 'Pending..');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `bdgtujuan`
--
ALTER TABLE `bdgtujuan`
  ADD PRIMARY KEY (`id_tujuan`);

--
-- Indeks untuk tabel `jastipbdg`
--
ALTER TABLE `jastipbdg`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jastipjkt`
--
ALTER TABLE `jastipjkt`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jkttujuan`
--
ALTER TABLE `jkttujuan`
  ADD PRIMARY KEY (`id_tujuan`);

--
-- Indeks untuk tabel `sortirambon`
--
ALTER TABLE `sortirambon`
  ADD PRIMARY KEY (`id_sortir`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `bdgtujuan`
--
ALTER TABLE `bdgtujuan`
  MODIFY `id_tujuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `jkttujuan`
--
ALTER TABLE `jkttujuan`
  MODIFY `id_tujuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `sortirambon`
--
ALTER TABLE `sortirambon`
  MODIFY `id_sortir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
