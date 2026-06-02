-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 02:05 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evacfinder-nope`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int NOT NULL,
  `center_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `center_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `category` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `capacity` int NOT NULL,
  `max_persons` int NOT NULL,
  `current_occupants` int NOT NULL,
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_established` date DEFAULT NULL,
  `facilities` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `remarks` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `encodedby` int NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `estimated_capacity` int DEFAULT NULL,
  `accessibility` varchar(300) DEFAULT NULL,
  `available_facilities` varchar(300) DEFAULT NULL,
  `history_date` datetime DEFAULT NULL,
  `action_made` varchar(100) NOT NULL,
  `assigned_lgu_user_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `center_id`, `center_name`, `category`, `status`, `barangay`, `city`, `province`, `address`, `capacity`, `max_persons`, `current_occupants`, `contact_number`, `contact_person`, `date_established`, `facilities`, `remarks`, `encodedby`, `latitude`, `longitude`, `estimated_capacity`, `accessibility`, `available_facilities`, `history_date`, `action_made`, `assigned_lgu_user_id`) VALUES
(1, 'EvacC00001', 'Banago Elem', 'Secondary', 'Inactive', '', '', 'Negros Occidental', '', 300, 0, 0, '', '', NULL, '', '', 1, NULL, NULL, 300, '', '', NULL, '0', NULL),
(2, 'EvacC00002', 'Alijis', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis', 400, 400, 1, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', NULL, '0', '00006'),
(3, 'EvacC00002', 'Alijis', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', '', 400, 400, 1, '', '', NULL, '', 'hmm', 6, NULL, NULL, 400, '', '', '2026-05-31 11:31:00', 'Updated', '00006');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_centers_assigned_lgu` (`assigned_lgu_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
