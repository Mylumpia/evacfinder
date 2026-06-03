-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2026 at 07:17 AM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `announcement_id` varchar(20) NOT NULL,
  `ann_title` varchar(100) NOT NULL,
  `ann_type` varchar(50) NOT NULL,
  `ann_desc` varchar(255) NOT NULL,
  `encodedby` varchar(20) NOT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `announcement_id`, `ann_title`, `ann_type`, `ann_desc`, `encodedby`, `date_created`) VALUES
(1, 'ANN00001', '', 'General', 'testing', '1', '2026-05-25 01:41:50'),
(2, 'ANN00002', '', 'Advisory', 'next', '1', '2026-05-25 01:42:07'),
(3, 'ANN00003', '', 'General', 'asdsad', '1', '2026-05-26 00:37:53'),
(4, 'ANN00004', 'asdsa', 'General', 'asds', '1', '2026-05-26 00:41:11'),
(5, 'ANN00005', 'sfss', 'General', 'efs', '1', '2026-05-26 00:43:10'),
(6, 'ANN00006', 'TITLE', 'Advisory', 'blahbblahblahh', '1', '2026-05-26 12:34:36'),
(7, 'ANN00007', 'asd', 'Advisory', 'wasaf', '1', '2026-05-26 12:35:06'),
(8, 'ANN00008', 'asdw', 'Event', 'asd', '1', '2026-05-26 12:35:33'),
(9, 'ANN00009', '', '', '', '6', '2026-05-26 22:34:38'),
(10, 'ANN00010', '', '', '', '6', '2026-05-26 22:34:47'),
(11, 'ANN00011', '', '', '', '6', '2026-05-26 22:35:26'),
(12, 'ANN00012', '', '', '', '6', '2026-05-26 22:39:24');

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
  `available_facilities` varchar(300) DEFAULT NULL,
  `assigned_lgu_user_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `centers`
--

INSERT INTO `centers` (`id`, `center_id`, `center_name`, `category`, `status`, `barangay`, `city`, `province`, `address`, `capacity`, `max_persons`, `current_occupants`, `contact_number`, `contact_person`, `alternate_contact`, `date_established`, `facilities`, `hazard_type`, `remarks`, `encodedby`, `latitude`, `longitude`, `estimated_capacity`, `accessibility`, `available_facilities`, `assigned_lgu_user_id`) VALUES
(1, 'EvacC00001', 'edit', 'Secondary', 'Inactive', '', '', 'Negros Occidental', '', 300, 0, 0, '', '', '', NULL, '', '', '', 1, NULL, NULL, 300, '', '', NULL),
(2, 'EvacC00002', 'talaga?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', '', NULL, '', '', '', 6, 10.65656800, 122.96001400, 400, '', '', '00006');

--
-- Triggers `centers`
--
DELIMITER $$
CREATE TRIGGER `after_center_status_update` AFTER UPDATE ON `centers` FOR EACH ROW BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO `center_status_history` (`center_id`, `old_status`, `new_status`, `changed_by`, `changed_at`)
        VALUES (NEW.center_id, OLD.status, NEW.status, NULL, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `center_history`
--

CREATE TABLE `center_history` (
  `history_id` int NOT NULL,
  `center_id` varchar(10) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `description` text,
  `performed_by` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `center_history`
--

INSERT INTO `center_history` (`history_id`, `center_id`, `action_type`, `description`, `performed_by`, `created_at`) VALUES
(1, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee vxc xc status changed from Active to Departed', '00006', '2026-05-31 22:07:26'),
(2, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee vxc xc status changed from Active to Departed', '00006', '2026-05-31 22:07:26'),
(3, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee vxc xc status changed from Departed to Active', '00006', '2026-05-31 22:09:21'),
(4, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee vxc xc status changed from Departed to Active', '00006', '2026-05-31 22:09:21'),
(5, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee Kit Garcia status changed from Active to Departed', '00006', '2026-05-31 22:18:58'),
(6, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee Kit Garcia status changed from Active to Departed. Remarks: Depart\n', '00006', '2026-05-31 22:18:58'),
(7, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee Kit Garcia status changed from Departed to Active', '00006', '2026-05-31 22:19:23'),
(8, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee Kit Garcia status changed from Departed to Active', '00006', '2026-05-31 22:19:23'),
(9, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee vxc xc status changed from Active to Active', '00006', '2026-05-31 22:27:32'),
(10, 'EvacC00002', 'EVACUEE_ADDED', 'Evacuee john Garcia was registered to the center', '00006', '2026-05-31 22:27:44'),
(11, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Active to Departed', '00006', '2026-05-31 22:28:13'),
(12, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Active to Departed', '00006', '2026-05-31 22:28:13'),
(13, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Departed to Active', '00006', '2026-05-31 22:28:20'),
(14, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Departed to Active', '00006', '2026-05-31 22:28:20'),
(15, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Active to Departed', '00006', '2026-05-31 22:37:23'),
(16, 'EvacC00002', 'EVACUEE_STATUS_CHANGE', 'Evacuee john Garcia status changed from Active to Departed', '00006', '2026-05-31 22:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `center_status_history`
--

CREATE TABLE `center_status_history` (
  `history_id` int NOT NULL,
  `center_id` varchar(10) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` varchar(10) DEFAULT NULL,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `center_status_history`
--

INSERT INTO `center_status_history` (`history_id`, `center_id`, `old_status`, `new_status`, `changed_by`, `changed_at`) VALUES
(1, 'EvacC00002', 'Active', 'Inactive', NULL, '2026-05-31 22:25:15'),
(2, 'EvacC00002', 'Inactive', 'Active', NULL, '2026-05-31 22:27:17');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `registered_by_lgu_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `evacuees`
--

INSERT INTO `evacuees` (`id`, `evacuee_id`, `registration_date`, `last_name`, `first_name`, `middle_name`, `extension_name`, `relation_to_head`, `sex`, `birth_date`, `age`, `civil_status`, `occupation`, `contact_number`, `complete_address`, `emergency_contact_person`, `emergency_contact_number`, `condition_pregnant`, `condition_lactating`, `condition_elderly`, `condition_pwd`, `condition_4ps`, `pwd_type`, `health_status`, `emergency_medical_condition`, `medications_taken`, `known_allergies`, `evacuation_center_id`, `arrival_date`, `departure_date`, `evacuee_status`, `encodedby`, `created_at`, `registered_by_lgu_id`) VALUES
(1, 'Evac00001', '2026-05-26', 'Garcia', 'Kit', '', '', '', 'Male', NULL, NULL, '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '', '', '', 'EvacC00002', '2026-05-26', '2026-05-31', 'Active', '00006', '2026-05-26 14:52:14', NULL),
(2, 'Evac00002', '2026-05-30', 'xc', 'vxc', '', '', '', 'Other', NULL, NULL, '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '', '', '', 'EvacC00002', '2026-05-30', '2026-05-31', 'Active', '00006', '2026-05-30 14:10:44', '00006'),
(3, 'Evac00003', '2026-05-31', 'Garcia', 'john', '', '', '', 'Male', NULL, NULL, '', '', '', '', '', '', 0, 0, 0, 0, 0, '', '', '', '', '', 'EvacC00002', '2026-05-31', '2026-05-31', 'Departed', '00006', '2026-05-31 14:27:44', '00006');

--
-- Triggers `evacuees`
--
DELIMITER $$
CREATE TRIGGER `after_evacuee_insert` AFTER INSERT ON `evacuees` FOR EACH ROW BEGIN
    INSERT INTO `center_history` (`center_id`, `action_type`, `description`, `performed_by`, `created_at`)
    VALUES (
        NEW.evacuation_center_id, 
        'EVACUEE_ADDED', 
        CONCAT('Evacuee ', NEW.first_name, ' ', NEW.last_name, ' was registered to the center'), 
        NEW.encodedby, 
        NOW()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_evacuee_status_update` AFTER UPDATE ON `evacuees` FOR EACH ROW BEGIN
    IF OLD.evacuee_status != NEW.evacuee_status THEN
        INSERT INTO `center_history` (`center_id`, `action_type`, `description`, `performed_by`, `created_at`)
        VALUES (
            NEW.evacuation_center_id, 
            'EVACUEE_STATUS_CHANGE', 
            CONCAT('Evacuee ', NEW.first_name, ' ', NEW.last_name, ' status changed from ', OLD.evacuee_status, ' to ', NEW.evacuee_status), 
            NEW.encodedby, 
            NOW()
        );
    END IF;
END
$$
DELIMITER ;

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
  `assigned_lgu_user_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `center_id`, `center_name`, `category`, `status`, `barangay`, `city`, `province`, `address`, `capacity`, `max_persons`, `current_occupants`, `contact_number`, `contact_person`, `date_established`, `facilities`, `remarks`, `encodedby`, `latitude`, `longitude`, `estimated_capacity`, `accessibility`, `available_facilities`, `history_date`, `action_made`, `assigned_lgu_user_id`) VALUES
(1, 'EvacC00001', 'Banago Elem', 'Secondary', 'Inactive', '', '', 'Negros Occidental', '', 300, 0, 0, '', '', NULL, '', '', 1, NULL, NULL, 300, '', '', NULL, '0', NULL),
(2, 'EvacC00002', 'Alijis', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis', 400, 400, 1, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', NULL, '0', '00006'),
(3, 'EvacC00002', 'Alijis', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', '', 400, 400, 1, '', '', NULL, '', 'hmm', 6, NULL, NULL, 400, '', '', '2026-05-31 11:31:00', 'Updated', '00006'),
(4, 'EvacC00002', 'Alijis Hall weh??', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 22:14:13', 'Updated', '00006'),
(5, 'EvacC00002', 'Alijis Hall wah', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 22:14:45', 'Updated', '00006'),
(6, 'EvacC00002', 'Alijis Hall sure na?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 22:22:03', 'Updated', '00006'),
(7, 'EvacC00002', 'Alijis Hall isa pa', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 22:39:27', 'Updated', '00006'),
(8, 'EvacC00002', 'Alijis Hall sige daw bi?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 23:03:07', 'Updated', '00006'),
(9, 'EvacC00002', 'Alijis Hall change bi', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-02 23:04:02', 'Updated', '00006'),
(10, 'EvacC00002', 'Alijis Hall sige', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 13:13:52', 'Updated', '00006'),
(11, 'EvacC00002', 'Alijis Hall try', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:03:01', 'Updated', '00006'),
(12, 'EvacC00002', 'Alijis Hall try bi', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:03:51', 'Updated', '00006'),
(13, 'EvacC00002', 'Alijis Hall liwat na naman?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:08:39', 'Updated', '00006'),
(14, 'EvacC00002', 'Alijis Hall allen', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:14:34', 'Updated', '00006'),
(15, 'EvacC00002', 'Alijis Hall patya nlng ko bi?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:39:58', 'Updated', '00006'),
(16, 'EvacC00002', 'Alijis Hall talaka', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 3, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:41:31', 'Updated', '00006'),
(17, 'EvacC00002', 'wow', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:42:24', 'Updated', '00006'),
(18, 'EvacC00002', 'Alijis Hall ', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:43:04', 'Updated', '00006'),
(19, 'EvacC00002', 'wow', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:43:23', 'Updated', '00006'),
(20, 'EvacC00001', 'edit', 'Secondary', 'Inactive', '', '', 'Negros Occidental', '', 300, 0, 0, '', '', NULL, '', '', 6, NULL, NULL, 300, '', '', '2026-06-03 14:43:35', 'Updated', NULL),
(21, 'EvacC00002', 'talaga?', 'Primary', 'Active', 'Alijis', 'Bacolod', 'Negros Occidental', 'Alijis bcd', 400, 400, 2, '', '', NULL, '', '', 6, 10.65656800, 122.96001400, 400, '', '', '2026-06-03 14:44:07', 'Updated', '00006');

-- --------------------------------------------------------

--
-- Table structure for table `lgu_center_assignments`
--

CREATE TABLE `lgu_center_assignments` (
  `id` int NOT NULL,
  `lgu_user_id` varchar(10) NOT NULL,
  `center_id` varchar(10) NOT NULL,
  `assigned_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `assigned_by` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lgu_center_assignments`
--

INSERT INTO `lgu_center_assignments` (`id`, `lgu_user_id`, `center_id`, `assigned_date`, `assigned_by`, `status`) VALUES
(1, '00006', 'EvacC00002', '2026-05-28 02:33:54', '00006', 'Active');

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
(2, 'LGU00001', 'based', 'allen@gmail.com', 'municipal', 'negros-occidental', 'region-vi', 'pretty', 'allen', 'sarmiento', '09491744739', 'me', '2026-05-26 04:54:08', 'Pending'),
(3, 'LGU00003', 'Bacolod', '123@gmail.com', 'city', 'negros-occidental', 'region-vi', 'Okay', 'john', 'Garcia', '12312321', '123', '2026-05-26 08:42:47', 'Pending');

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
(9, '00004', 'lance', 'sarmiento', 'G', 'hey', '2005-12-12', 'Male', 'lancesarmiento40@gmail.com', '09491744739', 'negros-occidental-region6', 'Public User', '123', '2026-05-26 07:39:32', 'Active'),
(10, '00005', 'john', 'Garcia', 'P', '', '2026-04-30', 'Male', 'capkeith43@gmail.com', '09494949494', 'negros-occidental-region6', 'Public User', '123', '2026-05-26 08:41:52', 'Active');

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
  `last_login` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userrights`
--

INSERT INTO `userrights` (`id`, `userid`, `email`, `password`, `Type`, `last_login`, `status`) VALUES
(1, '00001', 'sample@gmail.com', 'user', '', '2026-05-26 08:41:12', 'Active'),
(7, '00003', 'allen@gmail.com', 'me', 'lgu', '2026-06-03 06:08:11', 'Active'),
(8, '00004', 'lancesarmiento40@gmail.com', '123', 'public', '2026-05-26 07:39:47', 'Active'),
(9, '00005', 'capkeith43@gmail.com', '123', 'public', '2026-06-02 14:58:50', 'Active'),
(10, '00006', '123@gmail.com', '123', 'lgu', '2026-06-03 06:42:09', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `centers`
--
ALTER TABLE `centers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `center_id` (`center_id`),
  ADD KEY `fk_centers_assigned_lgu` (`assigned_lgu_user_id`);

--
-- Indexes for table `center_history`
--
ALTER TABLE `center_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_center_history_center` (`center_id`),
  ADD KEY `idx_center_history_created` (`created_at`);

--
-- Indexes for table `center_status_history`
--
ALTER TABLE `center_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_status_history_center` (`center_id`),
  ADD KEY `idx_status_history_changed` (`changed_at`);

--
-- Indexes for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evacuee_id` (`evacuee_id`),
  ADD KEY `evacuation_center_id` (`evacuation_center_id`),
  ADD KEY `fk_evacuees_registered_by` (`registered_by_lgu_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_centers_assigned_lgu` (`assigned_lgu_user_id`);

--
-- Indexes for table `lgu_center_assignments`
--
ALTER TABLE `lgu_center_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`lgu_user_id`,`center_id`),
  ADD KEY `fk_lgu_assignments_center` (`center_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `centers`
--
ALTER TABLE `centers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `center_history`
--
ALTER TABLE `center_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `center_status_history`
--
ALTER TABLE `center_status_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evacuees`
--
ALTER TABLE `evacuees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `lgu_center_assignments`
--
ALTER TABLE `lgu_center_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lgu_users`
--
ALTER TABLE `lgu_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_users`
--
ALTER TABLE `personal_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `userrights`
--
ALTER TABLE `userrights`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `centers`
--
ALTER TABLE `centers`
  ADD CONSTRAINT `fk_centers_assigned_lgu` FOREIGN KEY (`assigned_lgu_user_id`) REFERENCES `userrights` (`userid`) ON DELETE SET NULL;

--
-- Constraints for table `center_history`
--
ALTER TABLE `center_history`
  ADD CONSTRAINT `fk_center_history_center` FOREIGN KEY (`center_id`) REFERENCES `centers` (`center_id`) ON DELETE CASCADE;

--
-- Constraints for table `center_status_history`
--
ALTER TABLE `center_status_history`
  ADD CONSTRAINT `fk_status_history_center` FOREIGN KEY (`center_id`) REFERENCES `centers` (`center_id`) ON DELETE CASCADE;

--
-- Constraints for table `evacuees`
--
ALTER TABLE `evacuees`
  ADD CONSTRAINT `fk_evacuees_center` FOREIGN KEY (`evacuation_center_id`) REFERENCES `centers` (`center_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_evacuees_registered_by` FOREIGN KEY (`registered_by_lgu_id`) REFERENCES `userrights` (`userid`) ON DELETE SET NULL;

--
-- Constraints for table `lgu_center_assignments`
--
ALTER TABLE `lgu_center_assignments`
  ADD CONSTRAINT `fk_lgu_assignments_center` FOREIGN KEY (`center_id`) REFERENCES `centers` (`center_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lgu_assignments_user` FOREIGN KEY (`lgu_user_id`) REFERENCES `userrights` (`userid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
