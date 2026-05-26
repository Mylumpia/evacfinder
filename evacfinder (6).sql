-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2026 at 08:00 AM
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
-- Database: `evacfinder`
--

-- --------------------------------------------------------

--
-- Table structure for table `centers`
--

CREATE TABLE `centers` (
  `id` int NOT NULL,
  `center_id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `center_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `category` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `capacity` int NOT NULL,
  `max_persons` int NOT NULL,
  `current_occupants` int NOT NULL,
  `contact_number` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alternate_contact` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_established` date DEFAULT NULL,
  `facilities` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hazard_type` varchar(300) NOT NULL,
  `remarks` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `encodedby` int NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `estimated_capacity` int DEFAULT NULL,
  `accessibility` varchar(300) DEFAULT NULL,
  `available_facilities` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evacuees`
--

CREATE TABLE `evacuees` (
  `id` int NOT NULL,
  `evacuee_id` varchar(20) NOT NULL,
  `registration_date` date NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `extension_name` varchar(20) DEFAULT NULL,
  `relation_to_head` varchar(50) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `age` int DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `complete_address` varchar(200) DEFAULT NULL,
  `emergency_contact_person` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `condition_pregnant` tinyint(1) DEFAULT '0',
  `condition_lactating` tinyint(1) DEFAULT '0',
  `condition_elderly` tinyint(1) DEFAULT '0',
  `condition_pwd` tinyint(1) DEFAULT '0',
  `condition_4ps` tinyint(1) DEFAULT '0',
  `pwd_type` varchar(50) DEFAULT NULL,
  `health_status` varchar(50) DEFAULT NULL,
  `emergency_medical_condition` varchar(100) DEFAULT NULL,
  `medications_taken` text,
  `known_allergies` text,
  `evacuation_center_id` varchar(10) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `evacuee_status` varchar(20) DEFAULT 'Active',
  `encodedby` varchar(5) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lgu_users`
--

CREATE TABLE `lgu_users` (
  `id` int NOT NULL,
  `lgu_id` varchar(10) NOT NULL,
  `lgu_office_name` varchar(100) NOT NULL,
  `office_email_address` varchar(100) NOT NULL,
  `office_type` varchar(50) NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(100) NOT NULL,
  `position_role` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lgu_users`
--

INSERT INTO `lgu_users` (`id`, `lgu_id`, `lgu_office_name`, `office_email_address`, `office_type`, `province`, `region`, `position_role`, `first_name`, `last_name`, `phone_number`, `password`, `registration_date`, `status`) VALUES
(2, 'LGU00001', 'based', 'allen@gmail.com', 'municipal', 'negros-occidental', 'region-vi', 'pretty', 'allen', 'sarmiento', '09491744739', 'me', '2026-05-26 04:54:08', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `personal_users`
--

CREATE TABLE `personal_users` (
  `id` int NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_initial` varchar(2) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `sex` varchar(10) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `region` varchar(100) NOT NULL,
  `account_type` varchar(50) DEFAULT 'User',
  `password` varchar(255) NOT NULL,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `personal_users`
--

INSERT INTO `personal_users` (`id`, `user_id`, `first_name`, `last_name`, `middle_initial`, `extension`, `date_of_birth`, `sex`, `email_address`, `phone_number`, `region`, `account_type`, `password`, `registration_date`, `status`) VALUES
(9, '00004', 'lance', 'sarmiento', 'G', 'hey', '2005-12-12', 'Male', 'lancesarmiento40@gmail.com', '09491744739', 'negros-occidental-region6', 'Public User', '123', '2026-05-26 07:39:32', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `userrights`
--

CREATE TABLE `userrights` (
  `id` int NOT NULL,
  `userid` varchar(5) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(20) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userrights`
--

INSERT INTO `userrights` (`id`, `userid`, `email`, `password`, `Type`, `last_login`) VALUES
(1, '00001', 'sample@gmail.com', 'user', '', NULL),
(7, '00003', 'allen@gmail.com', 'me', 'lgu', '2026-05-26 07:46:10'),
(8, '00004', 'lancesarmiento40@gmail.com', '123', 'public', '2026-05-26 07:39:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `centers`
--
ALTER TABLE `centers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `center_id` (`center_id`);

--
-- Indexes for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evacuee_id` (`evacuee_id`),
  ADD KEY `evacuation_center_id` (`evacuation_center_id`);

--
-- Indexes for table `lgu_users`
--
ALTER TABLE `lgu_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lgu_id` (`lgu_id`),
  ADD UNIQUE KEY `office_email_address` (`office_email_address`);

--
-- Indexes for table `personal_users`
--
ALTER TABLE `personal_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `userrights`
--
ALTER TABLE `userrights`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `centers`
--
ALTER TABLE `centers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lgu_users`
--
ALTER TABLE `lgu_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_users`
--
ALTER TABLE `personal_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `userrights`
--
ALTER TABLE `userrights`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD CONSTRAINT `fk_evacuees_center` FOREIGN KEY (`evacuation_center_id`) REFERENCES `centers` (`center_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
