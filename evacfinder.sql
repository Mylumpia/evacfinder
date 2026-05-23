-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2026 at 12:00 PM
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
-- (ORIGINAL - UNCHANGED)
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
-- (ORIGINAL - UNCHANGED)
--

CREATE TABLE `evacuees` (
  `id` int NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evacuee_id` (`evacuee_id`),
  KEY `evacuation_center_id` (`evacuation_center_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userrights`
-- (ORIGINAL - UNCHANGED)
--

CREATE TABLE `userrights` (
  `id` int NOT NULL,
  `userid` varchar(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userrights`
--

INSERT INTO `userrights` (`id`, `userid`, `username`, `password`) VALUES
(1, '00001', 'user', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `personal_users`
-- (NEW - For Personal/Individual Registration)
--

CREATE TABLE `personal_users` (
  `id` int NOT NULL AUTO_INCREMENT,
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
  `status` varchar(20) DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `email_address` (`email_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lgu_users`
-- (NEW - For LGU Registration)
--

CREATE TABLE `lgu_users` (
  `id` int NOT NULL AUTO_INCREMENT,
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
  `status` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lgu_id` (`lgu_id`),
  UNIQUE KEY `office_email_address` (`office_email_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- AUTO_INCREMENT for table `userrights`
--
ALTER TABLE `userrights`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Add foreign key constraint for evacuees
--
ALTER TABLE `evacuees`
  ADD CONSTRAINT `fk_evacuees_center` 
  FOREIGN KEY (`evacuation_center_id`) 
  REFERENCES `centers` (`center_id`) 
  ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;