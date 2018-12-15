-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2018 at 08:56 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gosalon`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_cust` int(10) NOT NULL,
  `nama_cust` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `password` varchar(10) NOT NULL,
  `level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_cust`, `nama_cust`, `alamat`, `jenis_kelamin`, `photo`, `password`, `level`) VALUES
(3, 'Ricky Octavian Wahyudi', 'Nganjuk', 'Laki Laki', 'upload/.png', '11', 0),
(4, 'Ilbad', 'Bangil', 'laki', 'upload/ilbad.png', '123', 0),
(5, 'o', 'oo\n', 'o', 'upload/o.png', 'o', 0),
(6, 'aku', 'ke', 'oke', 'upload/aku.png', 'oke', 0),
(7, 'admin', 'baru', 'laki', 'upload/baru.png', '123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(10) NOT NULL,
  `id_salon` int(10) NOT NULL,
  `nama_layanan` varchar(150) NOT NULL,
  `deskripsi` varchar(250) NOT NULL,
  `harga` int(10) NOT NULL,
  `photo` varchar(150) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_peg` int(10) NOT NULL,
  `nama_peg` varchar(20) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `alamat` varchar(250) NOT NULL,
  `password` varchar(10) NOT NULL,
  `level` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `salon`
--

CREATE TABLE `salon` (
  `id_salon` int(10) NOT NULL,
  `nama_salon` varchar(50) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `koordinat_x` varchar(150) NOT NULL,
  `koordinat_y` varchar(50) NOT NULL,
  `photo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salon`
--

INSERT INTO `salon` (`id_salon`, `nama_salon`, `alamat`, `koordinat_x`, `koordinat_y`, `photo`) VALUES
(11, 'Larissa', 'malang', '78', '98', 'Larissa.jpg'),
(12, 'Natasha', 'malang', '78', '98', 'Natasha.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tranksaksi`
--

CREATE TABLE `tranksaksi` (
  `id_tranksaksi` int(11) NOT NULL,
  `id_cust` varchar(50) NOT NULL,
  `no_antrian` int(3) NOT NULL,
  `nama_salon` varchar(50) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `total` int(11) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_cust`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_peg`);

--
-- Indexes for table `salon`
--
ALTER TABLE `salon`
  ADD PRIMARY KEY (`id_salon`);

--
-- Indexes for table `tranksaksi`
--
ALTER TABLE `tranksaksi`
  ADD PRIMARY KEY (`id_tranksaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_cust` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_peg` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salon`
--
ALTER TABLE `salon`
  MODIFY `id_salon` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tranksaksi`
--
ALTER TABLE `tranksaksi`
  MODIFY `id_tranksaksi` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
