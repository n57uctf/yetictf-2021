-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Feb 01, 2021 at 05:17 PM
-- Server version: 8.0.23
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `merc`
--
CREATE DATABASE IF NOT EXISTS `merc`;
USE merc;
-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `id` int NOT NULL,
  `hash` varchar(255) NOT NULL,
  `coins` double NOT NULL DEFAULT '0',
  `links` double NOT NULL DEFAULT '0',
  `rocks` double NOT NULL DEFAULT '0',
  `bucks` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`id`, `hash`, `coins`, `links`, `rocks`, `bucks`) VALUES
(10, '1405b8a0d4c3080f4a67340fd3f2d6d3', 62.98, 411.16, 0.6, 2.4),
(104, '3c270131b260e7974c5dfcc5548c1624', 247.1, 109.68, 30, 25);

-- --------------------------------------------------------

--
-- Table structure for table `chart`
--

CREATE TABLE `chart` (
  `id` int NOT NULL,
  `coins` double NOT NULL,
  `links` double NOT NULL,
  `rocks` double NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chart`
--

INSERT INTO `chart` (`id`, `coins`, `links`, `rocks`, `date`) VALUES
(1, 2.76, 1.71, 0.59, '11.03'),
(2, 2.85, 1.93, 0.66, '12.03'),
(3, 2.83, 1.95, 0.73, '13.03'),
(4, 3.01, 2.4, 0.96, '14.03'),
(5, 2.73, 2.64, 1.03, '15.03'),
(6, 3.03, 2.22, 1.13, '16.03');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `value` double NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `name`, `amount`, `value`, `price`) VALUES
(1, 'coins', 815.06, 2712.0740035775, 3.3274531980192),
(2, 'links', 1216.9551164689, 3107.9533928978, 2.5538767624527),
(4, 'rocks', 2999.01, 4398.6211890012, 1.4666910710538),
(5, 'placeholder', 1000, 1000, 1),
(6, 'bucks', 1003.3296222365, 1003.3296222365, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `member` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `passwd`, `hash`, `member`) VALUES
(22, 'tellers2006', '7570AF18B3A50438E1E3F1257C6BFADD', '1405b8a0d4c3080f4a67340fd3f2d6d3', 1),
(25, 'fisher', '8364df803c3aae8923424a753b657e08', '3c270131b260e7974c5dfcc5548c1624', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart`
--
ALTER TABLE `chart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `chart`
--
ALTER TABLE `chart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
