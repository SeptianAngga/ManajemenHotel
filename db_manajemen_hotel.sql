-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 11:22 AM
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
-- Database: `db_manajemen_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id` int(11) NOT NULL,
  `nomorKamar` varchar(10) NOT NULL,
  `tipe` varchar(10) NOT NULL,
  `hargaDasar` double NOT NULL,
  `status` varchar(10) NOT NULL,
  `ukuranKamar` varchar(20) DEFAULT NULL,
  `fasilitasTambahan` varchar(255) DEFAULT NULL,
  `tambahanHarga` double DEFAULT NULL,
  `durasiMenginap` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id`, `nomorKamar`, `tipe`, `hargaDasar`, `status`, `ukuranKamar`, `fasilitasTambahan`, `tambahanHarga`, `durasiMenginap`) VALUES
(5, '1', 'standar', 100000, 'Tersedia', NULL, NULL, 0, 4),
(6, '2', 'deluxe', 900000, 'Terisi', NULL, 'Akses Lounge', 100000, 5),
(7, '3', 'deluxe', 500000, 'Tersedia', NULL, 'Akses Lounge', 200000, 1),
(8, '4', 'deluxe', 700000, 'Tersedia', NULL, 'Jacuzzi', 300000, 2),
(9, '5', 'standar', 1000000, 'Tersedia', NULL, NULL, 0, 4),
(11, '7', 'deluxe', 800000, 'Terisi', NULL, 'Akses Lounge', 200000, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
