-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2019 at 10:42 PM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `golball`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `pass` text NOT NULL,
  `phone` text NOT NULL,
  `lvl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pass`, `phone`, `lvl`) VALUES
(1, 'segev', 'segev', '', 0),
(4, ':username', 'segev1', '', 0),
(5, ':username', 'segev1', '', 0),
(6, ':username', 'segev1', '', 0),
(7, 'segev2', 'segev2', '00', 0),
(8, 'segev3', 'segev3', '00', 0),
(9, 'segev4', 'segev4', '00', 0),
(10, 'segev5', 'segev5', '00', 0),
(11, 'segev6', 'segev5', '00', 0),
(12, 'segev7', 'segev5', '00', 0),
(13, 'segev8', 'segev5', '00', 0),
(14, 'segev9', 'segev5', '00', 0),
(15, 'segev10', 'segev5', '00', 0),
(16, 'segev11', 'segev5', '00', 0),
(17, 'segev12', 'segev5', '00', 0),
(18, 'segev111', 'segev111', '00', 0),
(20, 'segev21', 'segev21', '00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
