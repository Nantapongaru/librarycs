-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 11:46 AM
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
-- Database: `bookshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id_b` varchar(4) NOT NULL,
  `name_d` varchar(30) NOT NULL,
  `detail_b` varchar(50) NOT NULL,
  `type_b` varchar(1) NOT NULL,
  `pic_b` varchar(100) NOT NULL,
  `status_b` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id_b`, `name_d`, `detail_b`, `type_b`, `pic_b`, `status_b`) VALUES
('A001', 'ภาษาซี', 'ปิยะ', 'A', 'a1.jpg', 0),
('A002', 'ภาษาจาวา', 'ปิยะ', 'A', 'a2.jpg', 1),
('B001', 'การซ่อมบำรุงคอม', 'ดาดา', 'B', 'b1.jpg', 1),
('B002', 'Java', 'ตาตา', 'B', 'b2.jpg', 1),
('C001', 'chatGPT', 'ประวิทย์', 'C', 'c1.jpg', 1),
('C002', 'ซ่อม1', 'พาพา', 'C', 'c2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id_m` int(9) NOT NULL,
  `id_b` varchar(20) NOT NULL,
  `date_bor` date NOT NULL,
  `date_re` date NOT NULL,
  `money` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_depart`
--

CREATE TABLE `data_depart` (
  `id_data` int(2) NOT NULL,
  `id_depart` int(9) NOT NULL,
  `status_data` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `data_depart`
--

INSERT INTO `data_depart` (`id_data`, `id_depart`, `status_data`) VALUES
(1, 664245024, 'นักศึกษา'),
(2, 20302, 'อาจารย์');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_m` int(9) NOT NULL,
  `name_m` varchar(30) NOT NULL,
  `address_m` varchar(50) NOT NULL,
  `tel_m` varchar(10) NOT NULL,
  `status_m` varchar(10) NOT NULL,
  `user_m` varchar(20) NOT NULL,
  `pass_m` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_m`, `name_m`, `address_m`, `tel_m`, `status_m`, `user_m`, `pass_m`) VALUES
(20302, 'pp', '', '09', 'อาจารย์', 'oo', '1234'),
(664245024, 'pp', '', '09', 'นักศึกษา', 'oo', '3333');

-- --------------------------------------------------------

--
-- Table structure for table `type_book`
--

CREATE TABLE `type_book` (
  `type_b` varchar(1) NOT NULL,
  `name_b` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `type_book`
--

INSERT INTO `type_book` (`type_b`, `name_b`) VALUES
('A', 'การเขียนโปรแกรม'),
('B', 'การซ่อมบำรุงและเครือข่าย'),
('C', 'ปัญญาประดิษฐ์');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id_b`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id_m`,`id_b`);

--
-- Indexes for table `data_depart`
--
ALTER TABLE `data_depart`
  ADD PRIMARY KEY (`id_data`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_m`);

--
-- Indexes for table `type_book`
--
ALTER TABLE `type_book`
  ADD PRIMARY KEY (`type_b`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_depart`
--
ALTER TABLE `data_depart`
  MODIFY `id_data` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
