-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db:3306
-- Generation Time: Dec 09, 2025 at 12:04 AM
-- Server version: 9.4.0
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_emr`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `clinician_id` bigint UNSIGNED DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `status` enum('scheduled','cancelled','completed','no_show') COLLATE utf8mb4_unicode_ci DEFAULT 'scheduled',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `clinician_id`, `scheduled_at`, `status`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-09-01 09:00:00', 'completed', 'General consultation', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(2, 2, 2, '2025-09-02 11:30:00', 'completed', 'Blood test', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(3, 3, 3, '2025-09-03 14:00:00', 'scheduled', 'Follow-up', '2025-10-19 14:40:34', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `patient_id`, `action`, `object_type`, `object_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 2, 1, 'view_patient', 'patient', '1', '{\"note\": \"Viewed patient record from web UI\"}', '127.0.0.1', 'Mozilla/5.0 (lab)', '2025-10-19 14:40:34'),
(2, 2, 2, 'view_billing', 'billing', '2', '{\"note\": \"Viewed billing record for patient 2\"}', '127.0.0.1', 'Mozilla/5.0 (lab)', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `status` enum('pending','paid','cancelled','adjusted') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `description` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `patient_id`, `appointment_id`, `amount`, `currency`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 150.00, 'USD', 'pending', 'Consultation fee', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(2, 2, 2, 250.00, 'USD', 'paid', 'Blood test and lab fees', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(3, 3, 3, 75.00, 'USD', 'pending', 'Follow-up visit', '2025-10-19 14:40:34', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED DEFAULT NULL,
  `uploaded_by_id` bigint UNSIGNED DEFAULT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `filename` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint UNSIGNED DEFAULT NULL,
  `storage_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checksum` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `patient_id`, `uploaded_by_id`, `appointment_id`, `filename`, `mime_type`, `file_size`, `storage_path`, `checksum`, `created_at`) VALUES
(1, 1, 2, 1, 'xray_john_doe_20250901.pdf', 'application/pdf', 245678, '/storage/patient_1/xray_john_doe_20250901.pdf', 'sha256:examplechecksum1', '2025-10-19 14:40:34'),
(2, 2, 3, 2, 'cbc_result_jane_20250902.pdf', 'application/pdf', 123456, '/storage/patient_2/cbc_result_jane_20250902.pdf', 'sha256:examplechecksum2', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `ordered_by_id` bigint UNSIGNED DEFAULT NULL,
  `performed_by_id` bigint UNSIGNED DEFAULT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `test_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_text` mediumtext COLLATE utf8mb4_unicode_ci,
  `result_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ordered','in_progress','completed','amended','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'ordered',
  `recorded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `patient_id`, `ordered_by_id`, `performed_by_id`, `appointment_id`, `test_code`, `result_text`, `result_value`, `result_unit`, `status`, `recorded_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 3, 2, 'CBC-001', 'CBC normal. WBC: 6.2, RBC: 4.7', 'WBC=6.2;RBC=4.7', '', 'completed', '2025-10-19 14:40:34', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(2, 1, 2, 3, 1, 'GLU-001', 'Fasting glucose 95 mg/dL', '95', 'mg/dL', 'completed', '2025-10-19 14:40:34', '2025-10-19 14:40:34', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `medical_notes`
--

CREATE TABLE `medical_notes` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `note_text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_notes`
--

INSERT INTO `medical_notes` (`id`, `patient_id`, `author_id`, `appointment_id`, `note_text`, `note_type`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'Patient reports mild headache for 2 days. Vitals stable. Recommending paracetamol and rest.', 'consultation', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(2, 2, 2, 2, 'Ordered CBC and lipid panel. Awaiting results.', 'order', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(3, 3, 3, 3, 'Follow-up scheduled. Check wound healing and vitals.', 'followup', '2025-10-19 14:40:34', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint UNSIGNED NOT NULL,
  `medical_record_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT 'other',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `medical_record_number`, `first_name`, `last_name`, `dob`, `gender`, `phone`, `email`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES
(1, 'MRN0001', 'John', 'Doe', '1980-01-01', 'male', '+212610000001', 'john.doe@example.local\r\n', '123 Main St, City', 'Jane Doe', '+212610000009', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(2, 'MRN0002', 'Jane', 'Smith', '1990-05-05', 'female', '+212610000002', 'jane.smith@example.local\r\n', '45 Oak Ave, City', 'John Smith', '+212610000010', '2025-10-19 14:40:34', '2025-10-19 14:40:34'),
(3, 'MRN0003', 'Sam', 'Brown', '1975-07-20', 'male', '+212610000003', 'sam.brown@example.local\r\n', '78 Pine Rd, City', 'Sara Brown', '+212610000011', '2025-10-19 14:40:34', '2025-10-19 14:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrator', '2025-10-19 14:40:33'),
(2, 'doctor', 'Doctor', '2025-10-19 14:40:33'),
(3, 'nurse', 'Nurse', '2025-10-19 14:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `session_token` char(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `session_token`, `ip_address`, `user_agent`, `created_at`, `expires_at`, `revoked`) VALUES
(1, 2, 'sessiontoken_example_doc_alice_1', '127.0.0.1', 'Mozilla/5.0 (lab)', '2025-10-19 14:40:34', '2025-10-19 16:40:34', 0),
(2, 1, 'sessiontoken_example_admin_1', '127.0.0.1', 'Mozilla/5.0 (lab)', '2025-10-19 14:40:34', '2025-10-19 16:40:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `email`, `password_hash`, `full_name`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@lab.local', '123456', 'Lab Admin', '+212600000001', 1, '2025-10-19 14:40:34', '2025-10-19 14:54:39'),
(2, 2, 'doc.alice@lab.local', '123456', 'Dr. Alice', '+212600000002', 1, '2025-10-19 14:40:34', '2025-10-19 14:54:43'),
(3, 3, 'nurse.bob@lab.local', '123456', 'Nurse Bob', '+212600000003', 1, '2025-10-19 14:40:34', '2025-10-19 14:54:46'),
(4, 2, 'doc.alice2@lab.local', '123456', 'Dr. Alice2', '+212600000002', 1, '2025-10-19 14:40:34', '2025-10-19 14:54:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `clinician_id` (`clinician_id`),
  ADD KEY `scheduled_at` (`scheduled_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `uploaded_by_id` (`uploaded_by_id`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordered_by_id` (`ordered_by_id`),
  ADD KEY `performed_by_id` (`performed_by_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `test_code` (`test_code`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `medical_notes`
--
ALTER TABLE `medical_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `appointment_id` (`appointment_id`);
ALTER TABLE `medical_notes` ADD FULLTEXT KEY `ft_note_text` (`note_text`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medical_record_number` (`medical_record_number`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `expires_at` (`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medical_notes`
--
ALTER TABLE `medical_notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`clinician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `billing_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `files_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `lab_results_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_results_ibfk_2` FOREIGN KEY (`ordered_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_results_ibfk_3` FOREIGN KEY (`performed_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_results_ibfk_4` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `medical_notes`
--
ALTER TABLE `medical_notes`
  ADD CONSTRAINT `medical_notes_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_notes_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_notes_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
