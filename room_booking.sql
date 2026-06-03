-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 11:40 PM
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
-- Database: `atslegas_sis`
--

-- --------------------------------------------------------

--
-- Table structure for table `raksti`
--

CREATE TABLE `raksti` (
  `id` int(11) NOT NULL,
  `lietotajs_id` int(11) DEFAULT NULL,
  `atslega_id` int(11) DEFAULT NULL,
  `start_laiks` datetime DEFAULT NULL,
  `beigu_laiks` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raksti`
--

INSERT INTO `raksti` (`id`, `lietotajs_id`, `atslega_id`, `start_laiks`, `beigu_laiks`) VALUES
(1, 1, 1, '2026-05-23 12:00:00', '2026-06-06 00:59:00'),
(2, 1, 1, '2026-05-23 01:08:00', '2026-05-23 01:08:00'),
(4, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(5, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(6, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(7, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(8, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(9, 1, 1, '2026-06-06 01:22:00', '2026-06-05 01:22:00'),
(12, 1, 19, '2026-05-27 14:30:00', '2026-05-27 19:00:00'),
(13, 1, 19, '2026-05-27 06:00:00', '2026-05-27 06:30:00'),
(14, 1, 10, '2026-05-27 06:00:00', '2026-05-27 06:30:00'),
(15, 1, 27, '2026-05-27 06:00:00', '2026-05-27 06:30:00'),
(18, 1, 2, '2026-05-27 06:00:00', '2026-05-27 06:30:00'),
(19, 1, 3, '2026-05-27 06:00:00', '2026-05-27 06:30:00'),
(20, 1, 27, '2026-05-27 07:00:00', '2026-05-27 07:30:00'),
(21, 1, 2, '2026-05-27 10:00:00', '2026-05-27 11:00:00'),
(22, 1, 2, '2026-05-27 11:00:00', '2026-05-27 11:30:00'),
(23, 1, 2, '2026-05-27 11:30:00', '2026-05-27 14:00:00'),
(24, 1, 27, '2026-05-27 10:30:00', '2026-05-27 12:30:00'),
(25, 2, 28, '2026-05-28 06:00:00', '2026-05-28 06:30:00'),
(26, 9, 27, '2026-05-28 13:00:00', '2026-05-28 13:30:00'),
(27, 2, 27, '2026-05-28 21:30:00', '2026-05-28 22:00:00'),
(28, 2, 27, '2026-05-28 22:00:00', '2026-05-28 22:30:00'),
(31, 2, 34, '2026-05-28 06:00:00', '2026-05-28 08:00:00'),
(37, 5, 41, '2026-05-31 06:00:00', '2026-05-31 06:30:00'),
(38, 5, 41, '2026-05-31 06:30:00', '2026-05-31 09:00:00'),
(40, 5, 27, '2026-05-31 06:00:00', '2026-05-31 06:30:00'),
(41, 5, 44, '2026-05-31 06:00:00', '2026-05-31 06:30:00'),
(42, 5, 42, '2026-05-31 06:00:00', '2026-05-31 06:30:00'),
(44, 5, 27, '2026-05-31 06:30:00', '2026-05-31 07:00:00'),
(45, 2, 38, '2026-05-31 06:00:00', '2026-05-31 07:00:00'),
(47, 5, 38, '2026-05-31 03:00:00', '2026-05-31 03:30:00'),
(48, 5, 10, '2026-05-31 03:00:00', '2026-05-31 04:00:00'),
(49, 5, 37, '2026-05-31 03:00:00', '2026-06-01 00:00:00'),
(50, 5, 23, '2026-05-31 03:00:00', '2026-05-31 04:00:00'),
(53, 5, 43, '2026-05-31 03:00:00', '2026-05-31 03:30:00'),
(54, 5, 2, '2026-05-31 03:00:00', '2026-05-31 04:30:00'),
(55, 5, 31, '2026-05-31 03:00:00', '2026-05-31 04:30:00'),
(56, 5, 34, '2026-05-31 03:00:00', '2026-05-31 03:30:00'),
(57, 5, 39, '2026-05-31 02:30:00', '2026-05-31 03:30:00'),
(58, 5, 28, '2026-05-31 02:30:00', '2026-05-31 03:00:00'),
(59, 5, 2, '2026-05-31 05:00:00', '2026-05-31 07:00:00'),
(60, 5, 2, '2026-05-31 20:30:00', '2026-05-31 21:00:00'),
(62, 5, 45, '2026-05-31 02:30:00', '2026-05-31 23:30:00'),
(63, 5, 45, '2026-06-01 00:00:00', '2026-06-02 00:30:00'),
(64, 10, 45, '2026-06-03 01:00:00', '2026-06-04 02:00:00'),
(65, 5, 3, '2026-05-31 04:30:00', '2026-05-31 05:30:00'),
(67, 5, 34, '2026-06-01 00:00:00', '2026-06-02 03:00:00'),
(68, 13, 31, '2026-06-05 00:00:00', '2026-06-06 02:00:00'),
(69, 5, 31, '2026-06-06 02:00:00', '2026-06-07 02:30:00'),
(70, 13, 10, '2026-05-31 17:00:00', '2026-05-31 18:30:00'),
(75, 13, 2, '2026-05-31 18:00:00', '2026-05-31 18:30:00'),
(76, 9, 49, '2026-05-31 18:00:00', '2026-05-31 18:30:00'),
(77, 9, 44, '2026-05-31 18:00:00', '2026-05-31 18:30:00'),
(78, 9, 34, '2026-05-31 20:00:00', '2026-05-31 22:00:00'),
(79, 13, 28, '2026-05-31 19:00:00', '2026-05-31 19:30:00'),
(80, 5, 28, '2026-05-31 19:30:00', '2026-05-31 20:00:00'),
(81, 13, 49, '2026-06-01 00:00:00', '2026-06-01 03:00:00'),
(82, 5, 49, '2026-06-01 04:30:00', '2026-06-01 05:30:00'),
(86, 19, 49, '2026-05-31 21:00:00', '2026-05-31 22:30:00'),
(87, 22, 27, '2026-05-31 23:00:00', '2026-05-31 23:30:00'),
(88, 21, 39, '2026-05-31 23:00:00', '2026-05-31 23:30:00'),
(90, 2, 10, '2026-06-01 00:30:00', '2026-06-01 02:00:00'),
(91, 15, 31, '2026-05-31 22:30:00', '2026-05-31 23:00:00'),
(92, 15, 27, '2026-05-31 22:30:00', '2026-05-31 23:00:00'),
(93, 13, 39, '2026-05-31 22:30:00', '2026-05-31 23:00:00'),
(94, 6, 43, '2026-05-31 22:30:00', '2026-05-31 23:00:00'),
(95, 21, 2, '2026-05-31 22:30:00', '2026-05-31 23:30:00'),
(96, 16, 29, '2026-06-02 06:00:00', '2026-06-03 06:30:00'),
(97, 2, 27, '2026-06-01 06:00:00', '2026-06-01 06:30:00'),
(98, 2, 27, '2026-06-01 06:30:00', '2026-06-01 07:00:00'),
(99, 2, 38, '2026-06-01 06:00:00', '2026-06-01 06:30:00'),
(100, 2, 38, '2026-06-01 06:30:00', '2026-06-01 22:30:00'),
(101, 16, 38, '2026-06-02 08:00:00', '2026-06-02 09:30:00'),
(102, 16, 38, '2026-06-02 06:00:00', '2026-06-02 06:30:00'),
(103, 16, 38, '2026-06-04 09:00:00', '2026-06-07 11:30:00'),
(104, 16, 38, '2026-06-07 11:30:00', '2026-06-07 12:00:00'),
(105, 16, 38, '2026-06-04 06:00:00', '2026-06-04 08:00:00'),
(106, 7, 23, '2026-06-01 06:00:00', '2026-06-01 10:30:00'),
(107, 16, 43, '2026-06-01 06:00:00', '2026-06-01 09:00:00'),
(108, 16, 43, '2026-06-01 09:00:00', '2026-06-01 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `zurnali`
--

CREATE TABLE `zurnali` (
  `id` int(11) NOT NULL,
  `darbiba` text DEFAULT NULL,
  `veidota` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zurnali`
--

INSERT INTO `zurnali` (`id`, `darbiba`, `veidota`) VALUES
(40, 'John added atslega: yo', '2026-05-28 01:38:44'),
(42, 'John added atslega: TTT', '2026-05-28 01:54:29'),
(43, 'John added atslega: a', '2026-05-28 01:54:41'),
(44, 'Admin created lietotajs: gg@gg.com', '2026-05-28 01:55:26'),
(45, 'Johncreated lietotajs: ll@ll.com', '2026-05-28 01:56:50'),
(46, 'that guy booked 123 from 2026-05-28 21:30:00 - 2026-05-28 22:00:00', '2026-05-28 02:38:47'),
(47, 'that guy booked 123 from 2026-05-28 22:00:00 - 2026-05-28 22:30:00', '2026-05-28 02:38:55'),
(48, '  booked 123 from 2026-05-28 06:00:00 - 2026-05-28 08:30:00', '2026-05-28 03:07:28'),
(49, 'Admin removed atslega: ', '2026-05-28 03:16:04'),
(50, 'John Doe booked yo for lietotajs   from 2026-05-28 06:00:00 - 2026-05-30 07:30:00', '2026-05-28 03:17:21'),
(51, 'John Doe booked TTT for lietotajs that guy from 2026-05-28 06:00:00 - 2026-05-28 08:00:00', '2026-05-28 03:18:50'),
(52, ' added atslega: ii', '2026-05-28 03:21:19'),
(55, 'John Doe added atslega: 666', '2026-05-28 03:22:49'),
(56, 'John Doe removed atslega: ', '2026-05-28 03:24:14'),
(57, 'John Doe added atslega: 4', '2026-05-28 03:26:31'),
(58, 'John Doe added atslega: gg', '2026-05-28 03:26:33'),
(59, 'John Doe added atslega: hello there', '2026-05-28 03:26:37'),
(60, 'John Doe added atslega: atslega 106', '2026-05-28 03:26:52'),
(61, 'John Doe added atslega: Hello', '2026-05-28 03:31:54'),
(62, 'John Doe added atslega: What\'s Good', '2026-05-28 03:32:25'),
(63, 'John Doe added atslega: Hippity Bopity', '2026-05-28 03:32:35'),
(64, 'John Doe added atslega: atslega 103', '2026-05-28 03:32:44'),
(65, 'John Doe booked atslega 202 for lietotajs   from 2026-05-28 06:00:00 - 2026-05-29 06:00:00', '2026-05-28 03:33:23'),
(66, 'John Doe booked sdfsdf for lietotajs   from 2026-05-28 06:00:00 - 2026-05-29 06:00:00', '2026-05-28 03:40:56'),
(67, 'John Doe booked atslega 103 for lietotajs   from 2026-05-28 06:00:00 - 2026-05-28 06:30:00', '2026-05-28 03:56:06'),
(68, 'John Doe booked atslega 103 for lietotajs   from 2026-05-28 06:30:00 - 2026-05-28 07:00:00', '2026-05-28 03:56:19'),
(69, 'John Doe booked 123 for lietotajs   from 2026-05-30 06:00:00 - 2026-05-31 06:00:00', '2026-05-30 05:45:15'),
(70, 'John Doe booked GG for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 06:30:00', '2026-05-31 02:18:56'),
(71, 'John Doe booked GG for lietotajs abe lincon from 2026-05-31 06:30:00 - 2026-05-31 09:00:00', '2026-05-31 02:19:09'),
(72, 'John Doe booked yo for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 06:30:00', '2026-05-31 02:19:29'),
(73, 'John Doe booked 123 for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 06:30:00', '2026-05-31 02:27:47'),
(74, 'John Doe booked HELLO for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 06:30:00', '2026-05-31 02:31:51'),
(75, 'John Doe booked HELLO THERE for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 06:30:00', '2026-05-31 02:32:03'),
(76, 'John Doe booked 4 for lietotajs abe lincon from 2026-05-31 06:00:00 - 2026-05-31 11:00:00', '2026-05-31 02:32:18'),
(77, 'John Doe booked 123 for lietotajs abe lincon from 2026-05-31 06:30:00 - 2026-05-31 07:00:00', '2026-05-31 02:32:59'),
(78, 'that guy booked 78 from 2026-05-31 06:00:00 - 2026-05-31 07:00:00', '2026-05-31 02:33:20'),
(79, 'John Doe booked 4 for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:30:00', '2026-05-31 02:59:27'),
(80, 'John Doe booked 78 for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 03:30:00', '2026-05-31 02:59:35'),
(81, 'John Doe booked aber for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:00:00', '2026-05-31 02:59:48'),
(82, 'John Doe booked abc for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-06-01 00:00:00', '2026-05-31 02:59:59'),
(83, 'John Doe booked asdasd for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:00:00', '2026-05-31 03:00:10'),
(84, 'John Doe booked ii for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:00:00', '2026-05-31 03:00:23'),
(85, 'John Doe booked yo for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:30:00', '2026-05-31 03:00:30'),
(86, 'John Doe booked atslega 106 for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 03:30:00', '2026-05-31 03:00:48'),
(87, 'John Doe booked atslega 2 for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:30:00', '2026-05-31 03:00:54'),
(88, 'John Doe booked sdfsdf for lietotajs abe lincon from 2026-05-31 03:00:00 - 2026-05-31 04:30:00', '2026-05-31 03:01:02'),
(90, 'John Doe booked 666 for lietotajs abe lincon from 2026-05-31 02:30:00 - 2026-05-31 03:30:00', '2026-05-31 03:05:43'),
(91, 'John Doe booked atslega 505 for lietotajs abe lincon from 2026-05-31 02:30:00 - 2026-05-31 03:00:00', '2026-05-31 03:06:29'),
(92, 'John Doe booked atslega 2 for lietotajs abe lincon from 2026-05-31 05:00:00 - 2026-05-31 07:00:00', '2026-05-31 03:10:29'),
(93, 'John Doe booked atslega 2 for lietotajs abe lincon from 2026-05-31 20:30:00 - 2026-05-31 21:00:00', '2026-05-31 03:13:00'),
(94, 'John Doe booked 4 for lietotajs abe lincon from 2026-05-31 11:00:00 - 2026-06-03 00:30:00', '2026-05-31 03:16:49'),
(95, 'John Doe booked What\'s Good for lietotajs abe lincon from 2026-05-31 02:30:00 - 2026-05-31 23:30:00', '2026-05-31 03:21:55'),
(96, 'John Doe booked What\'s Good for lietotajs abe lincon from 2026-06-01 00:00:00 - 2026-06-02 00:30:00', '2026-05-31 03:22:19'),
(97, 'John Doe booked What\'s Good for lietotajs vards uzvards from 2026-06-03 01:00:00 - 2026-06-04 02:00:00', '2026-05-31 03:24:31'),
(98, 'John Doe booked atslega 105 for lietotajs abe lincon from 2026-05-31 04:30:00 - 2026-05-31 05:30:00', '2026-05-31 05:26:18'),
(99, 'John Doe booked atslega 103 for lietotajs abe lincon from 2026-05-31 04:30:00 - 2026-05-31 09:00:00', '2026-05-31 05:26:39'),
(100, 'John Doe added atslega: atslega 3', '2026-05-31 05:33:16'),
(101, 'John Doe added atslega: atslega 4', '2026-05-31 05:42:31'),
(102, 'John created lietotajs: wow@wow.com', '2026-05-31 05:53:42'),
(103, 'wow man has joined the system', '2026-05-31 06:13:17'),
(104, 'John Doe removed atslega: ', '2026-05-31 17:07:50'),
(105, 'John Doe removed atslega: ii', '2026-05-31 17:30:04'),
(106, 'John Doe booked TTT for lietotajs abe lincon from 2026-06-01 00:00:00 - 2026-06-02 03:00:00', '2026-05-31 17:30:35'),
(107, 'John Doe booked sdfsdf for lietotajs wow man from 2026-06-05 00:00:00 - 2026-06-06 02:00:00', '2026-05-31 17:31:31'),
(108, 'John Doe booked sdfsdf for lietotajs abe lincon from 2026-06-06 02:00:00 - 2026-06-07 02:30:00', '2026-05-31 17:32:27'),
(109, 'John Doe booked aber for lietotajs wow man from 2026-05-31 17:00:00 - 2026-05-31 18:30:00', '2026-05-31 17:33:05'),
(110, 'John Doe booked yo for lietotajs abe lincon from 2026-05-31 18:00:00 - 2026-05-31 18:30:00', '2026-05-31 17:55:43'),
(111, 'John Doe booked yo for lietotajs abe lincon from 2026-05-31 19:30:00 - 2026-05-31 20:00:00', '2026-05-31 17:56:03'),
(112, 'John Doe booked yo for lietotajs abe lincon from 2026-05-31 19:00:00 - 2026-05-31 19:30:00', '2026-05-31 17:56:23'),
(113, 'John Doe booked yo for lietotajs wow man from 2026-06-01 00:30:00 - 2026-06-02 01:00:00', '2026-05-31 17:58:20'),
(114, 'John Doe booked atslega 2 for lietotajs wow man from 2026-05-31 18:00:00 - 2026-05-31 18:30:00', '2026-05-31 18:11:34'),
(115, 'John Doe booked atslega 4 for lietotajs Yee Man from 2026-05-31 18:00:00 - 2026-05-31 18:30:00', '2026-05-31 18:15:18'),
(116, 'John Doe booked HELLO for lietotajs Yee Man from 2026-05-31 18:00:00 - 2026-05-31 18:30:00', '2026-05-31 18:16:24'),
(117, 'John Doe booked TTT for lietotajs Yee Man from 2026-05-31 20:00:00 - 2026-05-31 22:00:00', '2026-05-31 18:18:18'),
(118, 'John Doe booked atslega 505 for lietotajs wow man from 2026-05-31 19:00:00 - 2026-05-31 19:30:00', '2026-05-31 18:54:37'),
(119, 'John Doe booked atslega 505 for lietotajs abe lincon from 2026-05-31 19:30:00 - 2026-05-31 20:00:00', '2026-05-31 18:54:50'),
(120, 'John Doe booked atslega 4 for lietotajs wow man from 2026-06-01 00:00:00 - 2026-06-01 03:00:00', '2026-05-31 18:55:14'),
(121, 'John Doe booked atslega 4 for lietotajs abe lincon from 2026-06-01 04:30:00 - 2026-06-01 05:30:00', '2026-05-31 18:55:29'),
(122, 'John Doe added atslega: atslega 6', '2026-05-31 20:01:39'),
(123, 'John Doe removed atslega: atslega 6', '2026-05-31 20:02:00'),
(124, 'John created lietotajs: yippie@yippie.com', '2026-05-31 20:03:56'),
(125, 'John created lietotajs: coolman@com.com', '2026-05-31 20:21:25'),
(126, 'John created lietotajs: lol@lol.com', '2026-05-31 20:23:11'),
(127, 'John created lietotajs: why@why.com', '2026-05-31 20:26:43'),
(128, 'John created lietotajs: fixed@fixed.com', '2026-05-31 20:32:58'),
(129, 'John created lietotajs: jk@jk.com', '2026-05-31 20:34:27'),
(131, 'big boom has joined the system', '2026-05-31 20:36:30'),
(135, 'John Doe booked atslega 4 for lietotajs   from 2026-05-31 21:00:00 - 2026-05-31 22:30:00', '2026-05-31 20:47:58'),
(136, 'hihi man has joined the system', '2026-05-31 21:16:06'),
(137, 'hihi man booked 123 from 2026-05-31 23:00:00 - 2026-05-31 23:30:00', '2026-05-31 21:16:50'),
(138, 'okayimhere lol has joined the system', '2026-05-31 21:19:19'),
(139, 'okayimhere lol booked 666 from 2026-05-31 23:00:00 - 2026-05-31 23:30:00', '2026-05-31 21:19:26'),
(140, '  is no longer amongus', '2026-05-31 21:19:31'),
(141, 'mm mm has joined the system', '2026-05-31 21:21:45'),
(142, 'mm mm is no longer amongus', '2026-05-31 21:21:48'),
(143, 'John Doe deactivated lietotajs: Yee Man', '2026-05-31 21:30:54'),
(144, 'that guy booked yo from 2026-05-31 22:00:00 - 2026-05-31 23:00:00', '2026-05-31 21:46:50'),
(145, 'John Doe booked aber for lietotajs that guy from 2026-06-01 00:30:00 - 2026-06-01 02:00:00', '2026-05-31 21:49:14'),
(146, 'John Doe booked sdfsdf for lietotajs   from 2026-05-31 22:30:00 - 2026-05-31 23:00:00', '2026-05-31 22:08:42'),
(147, 'John Doe booked 123 for lietotajs   from 2026-05-31 22:30:00 - 2026-05-31 23:00:00', '2026-05-31 22:35:43'),
(148, 'John Doe booked 666 for lietotajs wow man from 2026-05-31 22:30:00 - 2026-05-31 23:00:00', '2026-05-31 22:35:58'),
(149, 'John Doe booked atslega 106 for lietotajs that guy from 2026-05-31 22:30:00 - 2026-05-31 23:00:00', '2026-05-31 22:39:51'),
(150, 'John Doe booked atslega 2 for lietotajs okayimhere lol from 2026-05-31 22:30:00 - 2026-05-31 23:30:00', '2026-05-31 22:40:06'),
(151, 'John Doe booked atslega 202 for lietotajs   from 2026-06-02 06:00:00 - 2026-06-03 06:30:00', '2026-05-31 22:40:30'),
(152, 'John created lietotajs: temp@temp.com', '2026-05-31 22:42:00'),
(153, 'testingfields in testing has joined the system', '2026-05-31 23:04:27'),
(154, 'testingfields in has joined the system', '2026-05-31 23:05:50'),
(155, 'testingfields in has joined the system', '2026-05-31 23:06:19'),
(156, 'testingfields in the field has joined the system', '2026-05-31 23:06:51'),
(157, 'John Doe added atslega: atslega 89', '2026-05-31 23:07:33'),
(158, 'John Doe removed atslega: yo', '2026-05-31 23:08:07'),
(159, 'testingfields in the field is no longer amongus', '2026-05-31 23:08:25'),
(163, 'testingman himself has joined the system', '2026-05-31 23:15:23'),
(164, 'John Doe deactivated lietotajs: that guy', '2026-05-31 23:28:00'),
(165, 'that guy booked 123 from 2026-06-01 06:00:00 - 2026-06-01 06:30:00', '2026-06-01 00:01:22'),
(166, 'that guy booked 123 from 2026-06-01 06:30:00 - 2026-06-01 07:00:00', '2026-06-01 00:01:23'),
(167, 'that guy booked 78 from 2026-06-01 06:00:00 - 2026-06-01 06:30:00', '2026-06-01 00:01:37'),
(168, 'that guy booked 78 from 2026-06-01 06:30:00 - 2026-06-01 22:30:00', '2026-06-01 00:01:52'),
(169, 'John Doe booked 78 for lietotajs   from 2026-06-02 08:00:00 - 2026-06-02 09:30:00', '2026-06-01 00:03:12'),
(170, 'John Doe booked 78 for lietotajs   from 2026-06-02 06:00:00 - 2026-06-02 06:30:00', '2026-06-01 00:03:21'),
(171, 'John Doe booked 78 for lietotajs   from 2026-06-04 09:00:00 - 2026-06-07 11:30:00', '2026-06-01 00:03:34'),
(172, 'John Doe booked 78 for lietotajs   from 2026-06-07 11:30:00 - 2026-06-07 12:00:00', '2026-06-01 00:03:49'),
(173, 'John Doe booked 78 for lietotajs   from 2026-06-04 06:00:00 - 2026-06-04 08:00:00', '2026-06-01 00:16:57'),
(174, 'John Doe booked asdasd for lietotajs hi world from 2026-06-01 06:00:00 - 2026-06-01 10:30:00', '2026-06-01 00:24:30'),
(175, 'John Doe booked atslega 106 for lietotajs   from 2026-06-01 06:00:00 - 2026-06-01 09:00:00', '2026-06-01 00:34:28'),
(176, 'John Doe booked atslega 106 for lietotajs   from 2026-06-01 09:00:00 - 2026-06-01 11:30:00', '2026-06-01 00:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `atslegas`
--

CREATE TABLE `atslegas` (
  `id` int(11) NOT NULL,
  `nosaukums` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atslegas`
--

INSERT INTO `atslegas` (`id`, `nosaukums`) VALUES
(27, '123'),
(39, '666'),
(38, '78'),
(19, 'aaaa'),
(37, 'abc'),
(10, 'aber'),
(23, 'asdasd'),
(33, 'cool atslega'),
(30, 'ergnioergi'),
(41, 'GG'),
(44, 'HELLO'),
(42, 'HELLO THERE'),
(46, 'Hippity Bopity'),
(1, 'atslega 1'),
(47, 'atslega 103'),
(3, 'atslega 105'),
(43, 'atslega 106'),
(2, 'atslega 2'),
(29, 'atslega 202'),
(48, 'atslega 3'),
(49, 'atslega 4'),
(28, 'atslega 505'),
(51, 'atslega 89'),
(31, 'sdfsdf'),
(34, 'TTT'),
(45, 'What\'s Good');

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE `lietotaji` (
  `id` int(11) NOT NULL,
  `epasts` varchar(100) DEFAULT NULL,
  `parole` varchar(255) DEFAULT NULL,
  `loma` varchar(10) DEFAULT NULL,
  `nosaukums` varchar(50) DEFAULT NULL,
  `uzvards` varchar(50) DEFAULT NULL,
  `beigu_term` datetime DEFAULT NULL,
  `aktivs` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`id`, `epasts`, `parole`, `loma`, `nosaukums`, `uzvards`, `beigu_term`, `aktivs`) VALUES
(1, 'admin', '$2y$10$ZvfZ1PmVTOaX1l82T8n8COUoBvTkUbLlqiGorSlcl8qe8YywIF/FS', 'admin', 'John', 'Doe', NULL, 1),
(2, 'lietotajs', '$2y$10$2/.R8tcCzbK2dWuJxxAwxe7i/mUcfVCg2W4xZobCawkze3UAccpIW', 'lietotajs', 'that', 'guy', NULL, 1),
(5, 'ab@ab.com', '$2y$10$.lSi9XpcCqSh8KhTtHM5wu3Bs5KXilx0/TYXoCMpUdNKGyz/yXAPq', 'lietotajs', 'abe', 'lincon', NULL, 1),
(6, 'abc@abc.com', '$2y$10$uEJ5Lvku6hqUdxkhu6Cjs.5lhpl91dvf5RGOPf0z2jszhHD2WnQ0m', 'lietotajs', 'that', 'guy', NULL, 1),
(7, 'abcd@abcd.com', '$2y$10$kTkC12OLXBm8dbe8zCiM8en8.1to9HXMCs5RgmSulYZSzcOKigDca', 'lietotajs', 'hi', 'world', NULL, 1),
(9, 'abcde@abcde.com', '$2y$10$9psnSzg3ePchxiKBOn0ENOA.tLy3eoeO4VZgkOCcwqC/3WZz0ZduS', 'lietotajs', 'Yee', 'Man', NULL, 0),
(10, 'epasts@epasts.com', '$2y$10$c8jNShJhry3Og0hoVAFpZ.S71ve2hkh3n0zMYUqpYwi7MZznBv9GC', 'lietotajs', 'Vards', 'Uzvards', NULL, 0),
(13, 'wow@wow.com', '$2y$10$/OSyknnpLiYN5wwnb2kixu0BjZu9WzmpNDdpmvbAAXXzl8fR90HuK', 'temp', 'wow', 'man', '2026-05-31 03:53:42', 0),
(15, 'coolman@com.com', '$2y$10$0NaNLm5J7N2pfUugm20GUeGA9Nq.bw31EHf3bqo0STZl.BOPq7aX.', 'temp', NULL, NULL, '1970-01-01 01:00:00', 1),
(16, 'lol@lol.com', '$2y$10$UKN5RUK5dIFuncKvED/Rv.n4wS.UZeH5cg4ab7WdnyBWv0qDOv3qK', 'temp', NULL, NULL, '1970-01-01 01:00:00', 1),
(17, 'why@why.com', '$2y$10$Luftu4Isje80ehUmsbLoj.ldmHAo6tr61SwmdaE.t3vfSG.ji8x16', 'temp', NULL, NULL, '1970-01-01 01:00:00', 1),
(19, 'jk@jk.com', '$2y$10$KIn6zNOu9oMEeR.3aqDR8ei0I1DdmCSM12p.js/lB1CyHUDYnvRwq', 'temp', NULL, NULL, '2026-06-07 19:34:27', 1),
(20, 'boom@boom.com', '$2y$10$9y3FMHxuAvBf9hGBEkvCAubaUL3m.8bmTXZojS3CtqFlwuwmaPxMW', 'temp', 'big', 'boom', '2026-06-01 19:34:51', 1),
(21, 'hihi@hihi.com', '$2y$10$EYHvFmo2Tv.aUpA2SzSrBOVsSxt4WQb/fhQ/3av94udWdKD48miJy', 'temp', 'okayimhere', 'lol', NULL, 0),
(22, 'hihihi@hihihi.com', '$2y$10$4bAz2WTH2hcR9M6A2mSfO.UlcLhkbaFvGcFwujbFGbOWAoU5tS5N6', 'temp', 'hihi', 'man', NULL, 0),
(23, 'mm@mm.com', '$2y$10$stEOJ1W4mWdgp/ZfK4vFPOzb6CDOtePrPVW8Yw5v2GiLMGh5QIjw2', 'temp', 'mm', 'mm', NULL, 0),
(24, 'temp@temp.com', '$2y$10$mde/KtZV/O2fVMxrUQvs0OsvUvjbSvyodIwerE6BCfnzs5GxXuToy', 'temp', NULL, NULL, '2026-06-12 22:42:00', 1),
(25, 'testing@com.com', '$2y$10$dEVjvTa39VKvNteNgTavEelfTr6iHrNXF3hq0OZ5jJRXTwTH4xEcu', 'temp', 'testingfields', 'in the field', NULL, 0),
(26, 'testing@testing.com', '$2y$10$YB1WNoKNs544.vl4sq8lMu7ESUdqO0eln8Ta3Mklx.txv/DQ.ymqq', 'temp', 'testingman', 'himself', '2026-05-30 23:14:05', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `raksti`
--
ALTER TABLE `raksti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_raksti_lietotajs` (`lietotajs_id`),
  ADD KEY `fk_raksti_atslega` (`atslega_id`);

--
-- Indexes for table `zurnali`
--
ALTER TABLE `zurnali`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atslegas`
--
ALTER TABLE `atslegas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nosaukums` (`nosaukums`);

--
-- Indexes for table `lietotaji`
--
ALTER TABLE `lietotaji`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `epasts` (`epasts`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `raksti`
--
ALTER TABLE `raksti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `zurnali`
--
ALTER TABLE `zurnali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `atslegas`
--
ALTER TABLE `atslegas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `raksti`
--
ALTER TABLE `raksti`
  ADD CONSTRAINT `fk_raksti_atslega` FOREIGN KEY (`atslega_id`) REFERENCES `atslegas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_raksti_lietotajs` FOREIGN KEY (`lietotajs_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
