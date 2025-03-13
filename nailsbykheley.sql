-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 01:44 PM
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
-- Database: `nailsbykheley`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `referenceNum` varchar(100) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `conditionId` bigint(20) NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `service` varchar(100) DEFAULT NULL,
  `submission_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending',
  `end_time` time NOT NULL,
  `verification_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`referenceNum`, `userId`, `conditionId`, `appointment_date`, `start_time`, `service`, `submission_timestamp`, `status`, `end_time`, `verification_code`) VALUES
('APPT-20240616-000156', 69, 69, '2024-06-18', '19:09:00', 'Soft-gel Extension', '2024-06-16 12:47:06', 'cancelled', '20:09:00', 'cfa8956a8f9c0fe5c60fca00ab7b4539'),
('APPT-20240617-000159', 72, 72, '2024-06-19', '09:00:00', 'Soft-gel Extension', '2024-06-17 07:05:10', 'confirmed', '10:00:00', '9402fe8b98383f06d18423404eaa0c0b'),
('APPT-20240617-000160', 73, 73, '2024-06-28', '09:00:00', 'Soft-gel Extension', '2024-06-17 07:19:21', 'cancelled', '10:00:00', 'b717a2dc6a607675adce8afe6d6d1247'),
('APPT-20240617-000162', 75, 75, '2024-06-28', '13:00:00', 'Soft-gel Extension', '2024-06-17 11:50:05', 'cancelled', '15:00:00', '19a29a4b79ccc83bdbe6d8d735d83be1'),
('APPT-20240617-000164', 76, 76, '2024-06-19', '08:00:00', 'Soft-gel Extension', '2024-06-17 11:54:40', 'confirmed', '10:00:00', '0b8694e6b9d51e9f0c9b015b8b08f1bc'),
('APPT-20240617-000165', 77, 77, '2024-06-24', '13:00:00', 'Soft-gel Extension', '2024-06-17 12:34:00', 'confirmed', '15:00:00', 'b96c9a2df95363d51ce20ac0348f1061'),
('APPT-20240617-000166', 78, 78, '2024-06-24', '10:00:00', 'Soft-gel Extension', '2024-06-17 17:27:23', 'completed', '12:00:00', '2b97fa6b4238e49a2aea3ff5c9daf45d'),
('APPT-20240618-000174', 86, 86, '2024-06-24', '08:00:00', 'Soft-gel Extension', '2024-06-18 00:52:31', 'confirmed', '10:00:00', 'a0fc5e92e874e469f5f814b7a02dd522'),
('APPT-20240618-000175', 87, 87, '2024-06-24', '10:00:00', 'Soft-gel Extension', '2024-06-18 00:53:24', 'confirmed', '12:00:00', 'ca39d9a3756651ee8adfc81fa94de6e6'),
('APPT-20240618-000176', 88, 88, '2024-06-24', '15:00:00', 'Soft-gel Extension', '2024-06-18 01:03:48', 'confirmed', '17:00:00', '84c192442f6bd49fd1d7ef7fdf460465'),
('APPT-20240618-000178', 90, 90, '2024-06-20', '10:00:00', 'Soft-gel Extension', '2024-06-18 01:59:45', 'confirmed', '12:00:00', '44ff39313f13c41d48b9ddc6bf9c0dfd'),
('APPT-20240713-000179', 91, 91, '2024-07-17', '10:00:00', 'Soft-gel Extension', '2024-07-13 08:26:51', 'cancelled', '12:00:00', '82295a128dd9ae9df77e834a14cd39c6');

-- --------------------------------------------------------

--
-- Table structure for table `available_time`
--

CREATE TABLE `available_time` (
  `id` int(11) NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `available_time`
--

INSERT INTO `available_time` (`id`, `from_time`, `to_time`) VALUES
(28, '08:00:00', '10:00:00'),
(29, '10:00:00', '12:00:00'),
(30, '13:00:00', '15:00:00'),
(31, '15:00:00', '17:00:00'),
(35, '17:00:00', '19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` bigint(20) NOT NULL,
  `image_path` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `_type` varchar(50) NOT NULL DEFAULT 'normal',
  `theme_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image_path`, `title`, `_type`, `theme_id`) VALUES
(125, '66616136352af1717657910.jpg', 'Nail 21', 'normal', 84),
(126, '666161429cbf61717657922.jpg', 'Nail 22', 'normal', 99),
(127, '6661614d52e0f1717657933.jpg', 'Nail 17', 'featured', 84),
(128, '6661615faf4311717657951.jpg', 'Nail 18', 'featured', 84),
(129, '6661617e991121717657982.jpg', 'Nail 19', 'featured', 84),
(130, '6661afbd3e7901717678013.jpg', 'Nail 20', 'featured', 90),
(137, '6670417d7312c1718632829.jpg', 'Nail 1', 'normal', 84),
(138, '667041e8aa0d31718632936.jpg', 'Nail 2', 'normal', 93),
(139, '667042013fce51718632961.jpg', 'Nail 3', 'normal', NULL),
(140, '66704213301691718632979.jpg', 'Nail 4', 'normal', 20),
(141, '6670422396f5e1718632995.jpg', 'Nail 5', 'normal', 99),
(142, '66704235c17a81718633013.jpg', 'Nail 6', 'normal', 93),
(143, '6670786337f941718646883.jpg', 'Nail 16', 'featured', 84),
(144, '6670768c99aac1718646412.jpg', 'Nail 7', 'normal', 93),
(145, '667076b77ae5d1718646455.jpg', 'Nail 8', 'normal', 20),
(146, '667076c8167d71718646472.jpg', 'Nail 9', 'normal', 90),
(147, '66707707e31051718646535.jpg', 'Nail 10', 'normal', NULL),
(148, '66707726a0b1b1718646566.jpg', 'Nail 11', 'normal', 102),
(150, '667077cde36a31718646733.jpg', 'Nail 13', 'normal', 90),
(151, '667077f99e0c91718646777.jpg', 'Nail 14', 'normal', 20),
(152, '66707849c178d1718646857.jpg', 'Nail 15', 'normal', 99),
(153, '6670be9d274dc1718664861.jpg', 'Example 1', 'normal', 84),
(154, '6670beb0402771718664880.jpg', 'Example 2', 'normal', 102),
(155, '6670bebbd1ca41718664891.jpg', 'Example 3', 'normal', 102),
(156, '6670ee95efa1d1718677141.jpg', 'hall', 'featured', 90);

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `image_path` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`id`, `image_path`) VALUES
(60, '667024cf1b9f01718625487.jpg'),
(61, '667024e372d291718625507.jpg'),
(62, '667024ee138771718625518.jpg'),
(63, '667024f746cdb1718625527.jpg'),
(64, '667025008fad11718625536.jpg'),
(65, '66702510cdaed1718625552.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL,
  `read_status` varchar(50) NOT NULL DEFAULT 'unread',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `read_status`, `timestamp`) VALUES
(89, 'New Appointment', 'You have new pending appointment (APPT-20240617-000162).', 'unread', '2024-06-17 11:50:05'),
(90, 'New Appointment', 'You have new pending appointment (APPT-20240617-000164).', 'unread', '2024-06-17 11:54:40'),
(91, 'New Appointment', 'You have new pending appointment (APPT-20240617-000165).', 'unread', '2024-06-17 12:34:00'),
(92, 'New Appointment', 'You have new pending appointment (APPT-20240617-000166).', 'unread', '2024-06-17 17:27:23'),
(93, 'New Appointment', 'You have new pending appointment (APPT-20240617-000167).', 'unread', '2024-06-17 17:30:58'),
(94, 'New Appointment', 'You have new pending appointment (APPT-20240617-000168).', 'unread', '2024-06-17 17:32:53'),
(95, 'New Appointment', 'You have new pending appointment (APPT-20240618-000169).', 'unread', '2024-06-17 22:46:35'),
(96, 'New Appointment', 'You have new pending appointment (APPT-20240618-000170).', 'unread', '2024-06-17 22:48:41'),
(97, 'New Appointment', 'You have new pending appointment (APPT-20240618-000171).', 'unread', '2024-06-17 22:49:48'),
(98, 'New Appointment', 'You have new pending appointment (APPT-20240618-000172).', 'unread', '2024-06-17 23:02:30'),
(99, 'New Appointment', 'You have new pending appointment (APPT-20240618-000173).', 'unread', '2024-06-18 00:48:06'),
(100, 'New Appointment', 'You have new pending appointment (APPT-20240618-000174).', 'unread', '2024-06-18 00:52:31'),
(101, 'New Appointment', 'You have new pending appointment (APPT-20240618-000175).', 'unread', '2024-06-18 00:53:24'),
(102, 'New Appointment', 'You have new pending appointment (APPT-20240618-000176).', 'unread', '2024-06-18 01:03:48'),
(103, 'New Appointment', 'You have new pending appointment (APPT-20240618-000177).', 'unread', '2024-06-18 01:56:14'),
(104, 'New Appointment', 'You have new pending appointment (APPT-20240618-000178).', 'unread', '2024-06-18 01:59:45'),
(105, 'New Appointment', 'You have new pending appointment (APPT-20240713-000179).', 'unread', '2024-07-13 08:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `off_days`
--

CREATE TABLE `off_days` (
  `id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `theme_id` int(11) NOT NULL,
  `theme` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`theme_id`, `theme`) VALUES
(84, 'All'),
(93, 'Birthdays'),
(102, 'Christmas'),
(99, 'Flowers'),
(90, 'Halloween'),
(20, 'Wedding');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(11, 'admin', '$2y$10$VFzzFYaAVYf94NbxAlLD5.e/n3mJuQvg5J6duiz0DrwNksIFeJQA6');

-- --------------------------------------------------------

--
-- Table structure for table `user_conditions`
--

CREATE TABLE `user_conditions` (
  `conditionId` bigint(20) NOT NULL,
  `first_time` varchar(50) NOT NULL,
  `hasAllergic` varchar(50) NOT NULL,
  `allergicReaction` varchar(100) DEFAULT NULL,
  `isParticipatedSport` varchar(50) NOT NULL,
  `sportName` varchar(100) DEFAULT NULL,
  `medicalCondition` varchar(100) DEFAULT NULL,
  `nailCondition` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_conditions`
--

INSERT INTO `user_conditions` (`conditionId`, `first_time`, `hasAllergic`, `allergicReaction`, `isParticipatedSport`, `sportName`, `medicalCondition`, `nailCondition`) VALUES
(1, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(2, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(3, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(4, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(5, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(6, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(7, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(8, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(9, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(10, '1', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(11, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(12, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(13, '1', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(14, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(15, '1', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(16, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(17, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(18, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(19, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(20, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(21, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(22, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(23, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(24, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(25, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(26, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(27, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(28, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(29, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(30, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(31, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(32, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(33, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(34, '1', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(35, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(36, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(37, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(38, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(39, '1', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(40, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(41, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(42, '1', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(43, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(44, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(45, '0', '1', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(46, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(47, '0', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(48, '1', '0', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(49, '0', '1', 'Allergic Reaction', '1', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(50, '0', '0', 'Allergic Reaction', '0', 'Sport Name', 'Medical Condition', 'Nail Condition'),
(64, 'no', 'no', '', 'no', '', '', ''),
(65, 'no', 'no', '', 'no', '', '', ''),
(66, 'no', 'no', '', 'no', '', 'Diabetes', ''),
(67, 'yes', 'no', '', 'yes', 'Gymmers', 'Calluses', 'Soft'),
(69, 'no', 'no', '', 'no', '', 'Skin Inflammation', 'Normal'),
(72, 'yes', 'no', '', 'no', '', 'Athletes Foot', 'Hard'),
(73, 'yes', 'yes', 'masakit', 'yes', 'basket ball', 'Broken Skin, Swelling', 'Hard, Bendy'),
(75, 'no', 'no', '', 'no', '', '', ''),
(76, 'no', 'no', '', 'no', '', '', 'Soft'),
(77, 'no', 'no', '', 'no', '', 'Diabetes', ''),
(78, 'no', 'no', '', 'no', '', '', ''),
(81, 'no', 'no', '', 'no', '', '', ''),
(82, 'no', 'no', '', 'no', '', '', ''),
(83, 'no', 'no', '', 'no', '', '', ''),
(84, 'no', 'no', '', 'no', '', '', ''),
(86, 'no', 'no', '', 'no', '', '', ''),
(87, 'no', 'no', '', 'no', '', '', ''),
(88, 'no', 'no', '', 'no', '', '', ''),
(89, 'yes', 'no', '', 'no', '', '', 'Soft'),
(90, 'no', 'no', '', 'no', '', '', ''),
(91, 'no', 'no', '', 'no', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `userId` bigint(20) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mnumber` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`userId`, `fullName`, `address`, `email`, `mnumber`) VALUES
(1, 'Filipino Name 1', 'Philippine Address 1', 'email1@example.com', '+639851081227'),
(2, 'Filipino Name 2', 'Philippine Address 2', 'email2@example.com', '+639502219324'),
(3, 'Filipino Name 3', 'Philippine Address 3', 'email3@example.com', '+639957852969'),
(4, 'Filipino Name 4', 'Philippine Address 4', 'email4@example.com', '+639282606906'),
(5, 'Filipino Name 5', 'Philippine Address 5', 'email5@example.com', '+639539475653'),
(6, 'Filipino Name 6', 'Philippine Address 6', 'email6@example.com', '+639849557580'),
(7, 'Filipino Name 7', 'Philippine Address 7', 'email7@example.com', '+639629360970'),
(8, 'Filipino Name 8', 'Philippine Address 8', 'email8@example.com', '+639598132143'),
(9, 'Filipino Name 9', 'Philippine Address 9', 'email9@example.com', '+639102577835'),
(10, 'Filipino Name 10', 'Philippine Address 10', 'email10@example.com', '+639718492777'),
(11, 'Filipino Name 11', 'Philippine Address 11', 'email11@example.com', '+639284730413'),
(12, 'Filipino Name 12', 'Philippine Address 12', 'email12@example.com', '+639268173763'),
(13, 'Filipino Name 13', 'Philippine Address 13', 'email13@example.com', '+639486677609'),
(14, 'Filipino Name 14', 'Philippine Address 14', 'email14@example.com', '+639628866788'),
(15, 'Filipino Name 15', 'Philippine Address 15', 'email15@example.com', '+639684301144'),
(16, 'Filipino Name 16', 'Philippine Address 16', 'email16@example.com', '+639534905385'),
(17, 'Filipino Name 17', 'Philippine Address 17', 'email17@example.com', '+639621623526'),
(18, 'Filipino Name 18', 'Philippine Address 18', 'email18@example.com', '+639503401508'),
(19, 'Filipino Name 19', 'Philippine Address 19', 'email19@example.com', '+639652136990'),
(20, 'Filipino Name 20', 'Philippine Address 20', 'email20@example.com', '+639750480458'),
(21, 'Filipino Name 21', 'Philippine Address 21', 'email21@example.com', '+639795991353'),
(22, 'Filipino Name 22', 'Philippine Address 22', 'email22@example.com', '+639728515421'),
(23, 'Filipino Name 23', 'Philippine Address 23', 'email23@example.com', '+639254603078'),
(24, 'Filipino Name 24', 'Philippine Address 24', 'email24@example.com', '+639087469159'),
(25, 'Filipino Name 25', 'Philippine Address 25', 'email25@example.com', '+639673536592'),
(26, 'Filipino Name 26', 'Philippine Address 26', 'email26@example.com', '+639105275515'),
(27, 'Filipino Name 27', 'Philippine Address 27', 'email27@example.com', '+639505767830'),
(28, 'Filipino Name 28', 'Philippine Address 28', 'email28@example.com', '+639213012634'),
(29, 'Filipino Name 29', 'Philippine Address 29', 'email29@example.com', '+639547759715'),
(30, 'Filipino Name 30', 'Philippine Address 30', 'email30@example.com', '+639099760701'),
(31, 'Filipino Name 31', 'Philippine Address 31', 'email31@example.com', '+639855524390'),
(32, 'Filipino Name 32', 'Philippine Address 32', 'email32@example.com', '+639978339881'),
(33, 'Filipino Name 33', 'Philippine Address 33', 'email33@example.com', '+639325126266'),
(34, 'Filipino Name 34', 'Philippine Address 34', 'email34@example.com', '+639690611717'),
(35, 'Filipino Name 35', 'Philippine Address 35', 'email35@example.com', '+639477679821'),
(36, 'Filipino Name 36', 'Philippine Address 36', 'email36@example.com', '+639316563982'),
(37, 'Filipino Name 37', 'Philippine Address 37', 'email37@example.com', '+639149780481'),
(38, 'Filipino Name 38', 'Philippine Address 38', 'email38@example.com', '+639799210488'),
(39, 'Filipino Name 39', 'Philippine Address 39', 'email39@example.com', '+639546711029'),
(40, 'Filipino Name 40', 'Philippine Address 40', 'email40@example.com', '+639335923714'),
(41, 'Filipino Name 41', 'Philippine Address 41', 'email41@example.com', '+639039485515'),
(42, 'Filipino Name 42', 'Philippine Address 42', 'email42@example.com', '+639189656461'),
(43, 'Filipino Name 43', 'Philippine Address 43', 'email43@example.com', '+639829825794'),
(44, 'Filipino Name 44', 'Philippine Address 44', 'email44@example.com', '+639580159616'),
(45, 'Filipino Name 45', 'Philippine Address 45', 'email45@example.com', '+639411320729'),
(46, 'Filipino Name 46', 'Philippine Address 46', 'email46@example.com', '+639316124829'),
(47, 'Filipino Name 47', 'Philippine Address 47', 'email47@example.com', '+639346661990'),
(48, 'Filipino Name 48', 'Philippine Address 48', 'email48@example.com', '+639784935495'),
(49, 'Filipino Name 49', 'Philippine Address 49', 'email49@example.com', '+639884691536'),
(50, 'Filipino Name 50', 'Philippine Address 50', 'email50@example.com', '+639068651225'),
(64, 'Helo World', 'Brgy. Bataan Sampaloc, Quezon', 'sanvictoresjohnandrewe@gmail.com', '9167003378'),
(65, 'Hello World', 'fsd', '', '092345634342'),
(66, 'HI', 'fsd', '', '09999999999999'),
(67, 'Josep Kudo', 'Brgy. Gulang Gulang', 'josepkudo@gmail.com', '09267004015'),
(69, 'andrew sanvictores', 'fsd', 'sanvictoresjohnandrewe@gmail.com', '09167003378'),
(72, 'Andrew San victores', 'Brgy. 7', 'u73962304@gmail.com', '9163025698'),
(73, 'kua ko lang to', 'mauban', 'asdasdas@gmail.com', '09634901275'),
(75, 'Nicko Balmes', 'Lucena', 'nckoblms@gmail.com', '09634902175'),
(76, 'Alduz Garcia', 'Lucena', 'congrooogaming@gmail.com', '09634901275'),
(77, 'John Andrew San Victores', 'Sampaloc, Quezon', 'sanvictoresjohnandrewe@gmail.com', '09167003378'),
(78, 'fdd', 'dffd', 'johnandrewsanvictores@gmail.com', '091670033784'),
(81, 'fs', 'fs', 'sanvictoresjohnandrewe@gmail.com', '009999999999999'),
(82, 'Mikha Lim', 'Manila', 'Mikha_lim@gmail.com', '09672982421'),
(83, 'Josep Kudo', 'Brgy. Gulang Gulang', 'josepkudo@gmail.com', '09267004015'),
(84, 'Ben Llorin', 'Cotta', 'sanvictoresjohnandrewe@gmail.com', '09167003378'),
(86, 'fds', 'j', 'andrewsanvictores@gmail.com', '099999999999'),
(87, 'fiso', 'djfs', 'sanvictoresjohnandrewe@gmail.com', '099167003378'),
(88, 'test', 'fdsij', 'sanvictoresjohnandrewe@gmail.com', '099167003378'),
(89, 'racelis josep', 'lucena', 'sanvictoresjohnandrewe@gmail.com', '09267004015'),
(90, 'racelis mark', 'gulang gulang', 'josepkudo@gmail.com', '09267004015'),
(91, 'Dennis arellano', 'site sm', 'adormeorhesty@gmail.com', '09934570930');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`referenceNum`),
  ADD KEY `fk_appointmentUser` (`userId`),
  ADD KEY `fk_appointmentCondition` (`conditionId`);

--
-- Indexes for table `available_time`
--
ALTER TABLE `available_time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test` (`theme_id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `off_days`
--
ALTER TABLE `off_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`theme_id`),
  ADD UNIQUE KEY `theme` (`theme`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_conditions`
--
ALTER TABLE `user_conditions`
  ADD PRIMARY KEY (`conditionId`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `available_time`
--
ALTER TABLE `available_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `off_days`
--
ALTER TABLE `off_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `theme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_conditions`
--
ALTER TABLE `user_conditions`
  MODIFY `conditionId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `userId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user_info` (`userId`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user_info` (`userId`),
  ADD CONSTRAINT `fk_appointmentCondition` FOREIGN KEY (`conditionId`) REFERENCES `user_conditions` (`conditionId`),
  ADD CONSTRAINT `fk_appointmentUser` FOREIGN KEY (`userId`) REFERENCES `user_info` (`userId`);

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `test` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`theme_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
