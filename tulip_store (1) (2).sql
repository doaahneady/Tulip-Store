-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 10:14 AM
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
-- Database: `tulip_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_feeds`
--

CREATE TABLE `activity_feeds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_type` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `actor_type` varchar(255) NOT NULL,
  `actor_id` bigint(20) UNSIGNED NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `severity` varchar(255) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_feeds`
--

INSERT INTO `activity_feeds` (`id`, `dashboard_type`, `activity_type`, `action`, `title`, `description`, `actor_type`, `actor_id`, `target_type`, `target_id`, `metadata`, `severity`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'user', 'created', 'New User Registration', 'يوسف الحلبي signed up', 'App\\Models\\User', 23, 'App\\Models\\User', 23, '{\"email\":\"yousefalhalabi53@gmail.com\"}', 'info', 0, '2026-04-01 09:58:44', '2026-04-01 09:58:44'),
(2, 'admin', 'user', 'created', 'New User Registration', 'يوسف الحلبي signed up', 'App\\Models\\User', 24, 'App\\Models\\User', 24, '{\"email\":\"yousefalhalabi63@gmail.com\"}', 'info', 0, '2026-04-01 10:01:51', '2026-04-01 10:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrative_approvals`
--

CREATE TABLE `administrative_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requester_employee_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `decided_by_employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `decided_by_role` varchar(30) DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administrative_approvals`
--

INSERT INTO `administrative_approvals` (`id`, `requester_employee_id`, `category`, `amount`, `start_date`, `end_date`, `details`, `status`, `decided_by_employee_id`, `decided_by_role`, `decided_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'money', 10000.00, NULL, NULL, 'test', 'approved', 1, 'hr', '2026-03-11 02:49:57', '2026-03-11 02:48:22', '2026-03-11 02:49:57'),
(2, 1, 'money', 200.00, NULL, NULL, 'tt', 'rejected', 1, 'hr', '2026-03-11 02:49:55', '2026-03-11 02:49:41', '2026-03-11 02:49:55');

-- --------------------------------------------------------

--
-- Table structure for table `alert_rules`
--

CREATE TABLE `alert_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `dashboard_type` varchar(255) NOT NULL,
  `metric_type` varchar(255) NOT NULL,
  `condition` varchar(255) NOT NULL,
  `threshold_value` decimal(10,2) NOT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 5,
  `severity` varchar(255) NOT NULL DEFAULT 'warning',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notification_channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_channels`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('general','policy','event','urgent','celebration') NOT NULL,
  `target_audience` enum('all','department','role','specific_users') NOT NULL,
  `target_criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_criteria`)),
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_errors`
--

CREATE TABLE `api_errors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `status_code` int(11) NOT NULL,
  `error_message` text NOT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_data`)),
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_data`)),
  `user_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `response_time` decimal(8,3) NOT NULL,
  `occurred_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `rate_limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rate_limits`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `work_hours` int(11) DEFAULT NULL,
  `overtime_hours` int(11) NOT NULL DEFAULT 0,
  `status` enum('present','absent','late','half_day','on_leave') NOT NULL DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `work_hours`, `overtime_hours`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(2, 1, '2026-02-02', '07:51:30', '07:51:34', 0, 0, 'present', NULL, '2026-02-02 04:51:30', '2026-02-02 04:51:34'),
(3, 1, '2026-02-04', '05:41:28', '05:41:34', 0, 0, 'present', NULL, '2026-02-04 02:41:28', '2026-02-04 02:41:34'),
(9, 1, '2026-02-04', '09:25:43', '09:25:47', 0, 0, 'present', NULL, '2026-02-04 06:25:43', '2026-02-04 06:25:47'),
(10, 1, '2026-02-04', '09:25:52', '09:25:56', 0, 0, 'present', NULL, '2026-02-04 06:25:52', '2026-02-04 06:25:56'),
(11, 1, '2026-03-11', '05:57:59', NULL, NULL, 0, 'present', NULL, '2026-03-11 02:57:59', '2026-03-11 02:57:59'),
(12, 1, '2026-03-15', '09:53:34', NULL, NULL, 0, 'present', NULL, '2026-03-15 06:53:34', '2026-03-15 06:53:34');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `session_id`, `created_at`, `updated_at`, `metadata`) VALUES
(4, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-07 15:08:00', NULL, NULL),
(5, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:07:37', NULL, NULL),
(6, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:07:45', NULL, NULL),
(7, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:07:51', NULL, NULL),
(8, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:08:40', NULL, NULL),
(9, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:08:42', NULL, NULL),
(10, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:19:02', NULL, NULL),
(11, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 03:19:05', NULL, NULL),
(12, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 04:09:53', NULL, NULL),
(13, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 04:10:01', NULL, NULL),
(14, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 06:57:16', NULL, NULL),
(15, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 06:57:17', NULL, NULL),
(16, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-08 06:57:19', NULL, NULL),
(17, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 04:39:20', NULL, NULL),
(18, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 04:39:27', NULL, NULL),
(19, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-12 04:39:29', NULL, NULL),
(20, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 03:44:34', NULL, NULL),
(21, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 03:44:38', NULL, NULL),
(22, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, '2026-01-13 03:44:42', NULL, NULL),
(23, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-03 07:14:11', NULL, NULL),
(24, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-03 07:14:15', NULL, NULL),
(25, NULL, 'export', 'csv_export', NULL, NULL, '{\"record_count\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-03 07:14:20', NULL, NULL),
(27, NULL, 'test_employee_action', NULL, NULL, NULL, NULL, '127.0.0.1', 'Symfony', NULL, '2026-02-04 06:36:46', NULL, '{\"ok\":true}'),
(28, NULL, 'calculate_payroll', NULL, NULL, NULL, '{\"pay_period\":\"2026-02\",\"employees_count\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-04 06:38:31', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"ADM103\"}}'),
(29, NULL, 'test_action', NULL, NULL, NULL, NULL, '127.0.0.1', 'Symfony', NULL, '2026-02-05 02:43:43', NULL, NULL),
(30, NULL, 'calculate_payroll', NULL, NULL, NULL, '{\"pay_period\":\"2026-02\",\"employees_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-05 06:59:23', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"ADM103\"}}'),
(31, NULL, 'calculate_payroll', NULL, NULL, NULL, '{\"pay_period\":\"2026-02\",\"employees_count\":0}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-05 07:40:34', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"ADM103\"}}'),
(32, NULL, 'calculate_payroll', NULL, NULL, NULL, '{\"pay_period\":\"2026-03\",\"employees_count\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', NULL, '2026-02-05 07:40:50', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"ADM103\"}}'),
(33, NULL, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 14:02:42', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(34, NULL, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"pending\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 14:02:42', NULL, '{\"actor\":\"cs\"}'),
(35, NULL, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:16:18', NULL, NULL),
(36, NULL, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:16:29', NULL, NULL),
(37, NULL, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:17:06', NULL, NULL),
(38, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:37', NULL, NULL),
(39, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:54', NULL, NULL),
(40, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:59', NULL, NULL),
(42, NULL, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:17', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(43, NULL, 'status_transition', 'App\\Models\\Order', 4, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:17', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-10T05:37:17.876005Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(44, NULL, 'driver_assigned', 'App\\Models\\Order', 4, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"1\",\"assignment_id\":1}', NULL, NULL, NULL, '2026-03-10 02:37:18', NULL, NULL),
(45, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:18', NULL, NULL),
(49, NULL, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"failed\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:34:16', NULL, NULL),
(50, NULL, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(51, NULL, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:25.326779Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(52, NULL, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(53, NULL, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:26', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:26.034782Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(54, NULL, 'driver_assigned', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"1\",\"assignment_id\":2}', NULL, NULL, NULL, '2026-03-10 03:41:26', NULL, NULL),
(55, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:26', NULL, NULL),
(56, NULL, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:32', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(57, NULL, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:32', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"out_for_delivery\",\"to\":\"delivered\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:32.231058Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(58, NULL, 'order_completed', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\",\"commission_transaction_id\":17}', NULL, NULL, NULL, '2026-03-10 03:41:32', NULL, NULL),
(59, NULL, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:33', NULL, NULL),
(60, NULL, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(61, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 15, '{\"status\":\"pending\",\"amount\":\"161.16\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"161.16\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"order_finalization\",\"order_id\":6}'),
(62, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 6, NULL, '{\"order_id\":6,\"amount\":\"161.16\",\"currency\":\"USD\",\"financial_transaction_id\":15}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, NULL),
(63, NULL, 'store_sales_incremented', 'App\\Models\\Order', 6, NULL, '{\"store_id\":1,\"amount\":161.16,\"source\":\"order_finalization\",\"order_id\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, NULL),
(64, NULL, 'order_marked_completed', 'App\\Models\\Order', 6, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-10T06:42:50.370755Z\",\"revenue_recognized_at\":\"2026-03-10T06:42:50.387021Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"order_finalization\"}'),
(65, NULL, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-10T06:42:50.420590Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(66, NULL, 'order_status_changed', 'App\\Models\\Order', 7, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:33:24', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(67, NULL, 'status_transition', 'App\\Models\\Order', 7, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:33:24', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-11T05:33:24.102861Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(68, NULL, 'order_status_changed', 'App\\Models\\Order', 7, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:33:24', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(69, NULL, 'status_transition', 'App\\Models\\Order', 7, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:33:24', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-11T05:33:24.262453Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(70, NULL, 'driver_assigned', 'App\\Models\\Order', 7, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"1\",\"assignment_id\":3}', NULL, NULL, NULL, '2026-03-11 02:33:24', NULL, NULL),
(71, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:33:24', NULL, NULL),
(72, NULL, 'order_status_changed', 'App\\Models\\Order', 7, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:18', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(73, NULL, 'status_transition', 'App\\Models\\Order', 7, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:18', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"out_for_delivery\",\"to\":\"delivered\",\"admin_override\":false,\"timestamp\":\"2026-03-11T05:34:18.888966Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(74, NULL, 'order_completed', 'App\\Models\\Order', 7, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\",\"commission_transaction_id\":19}', NULL, NULL, NULL, '2026-03-11 02:34:19', NULL, NULL),
(75, NULL, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:19', NULL, NULL),
(76, NULL, 'order_status_changed', 'App\\Models\\Order', 7, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(77, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 18, '{\"status\":\"pending\",\"amount\":\"111.38\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"111.38\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, '{\"source\":\"order_finalization\",\"order_id\":7}'),
(78, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 7, NULL, '{\"order_id\":7,\"amount\":\"111.38\",\"currency\":\"USD\",\"financial_transaction_id\":18}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, NULL),
(79, NULL, 'store_sales_incremented', 'App\\Models\\Order', 7, NULL, '{\"store_id\":1,\"amount\":111.38,\"source\":\"order_finalization\",\"order_id\":7}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, NULL),
(80, NULL, 'order_marked_completed', 'App\\Models\\Order', 7, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-11T05:34:51.951527Z\",\"revenue_recognized_at\":\"2026-03-11T05:34:51.960255Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, '{\"source\":\"order_finalization\"}'),
(81, NULL, 'status_transition', 'App\\Models\\Order', 7, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:34:51', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-11T05:34:51.984846Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(82, NULL, 'update_employee', 'App\\Models\\Employee', 1, '{\"id\":1,\"employee_id\":\"EMP001\",\"department\":\"Administration\",\"work_location\":null,\"position\":\"Super Admin\",\"manager_id\":null,\"hourly_rate\":null,\"monthly_salary\":null,\"hire_date\":\"2026-03-09T00:00:00.000000Z\",\"termination_date\":null,\"employment_type\":\"full_time\",\"work_schedule\":null,\"status\":\"active\",\"security_level\":\"1\",\"emergency_contact\":null,\"documents\":null,\"created_at\":\"2026-01-06T07:15:19.000000Z\",\"updated_at\":\"2026-03-11T05:46:46.000000Z\",\"employee_code\":\"EMP001\",\"employee_id_card\":null,\"first_name\":\"Admin\",\"last_name\":\"User\",\"email\":\"admin@tulipstore.com\",\"profile_photo\":null,\"bio\":null,\"password\":\"$2y$12$d7Vlh1WPQk7pk\\/\\/l6iJVOekrpbxpmO\\/RtHX3i9E0GLXAnbCRe5NJW\",\"email_verified_at\":null,\"remember_token\":null,\"last_login_at\":null,\"login_count\":0,\"two_factor_enabled\":false,\"ip_restrictions\":null,\"performance_score\":null,\"last_review_date\":null,\"next_review_date\":null,\"phone\":\"1234567890\",\"national_id\":null,\"date_of_birth\":null,\"gender\":null,\"marital_status\":null,\"address\":null,\"city\":null,\"country\":\"Saudi Arabia\",\"is_admin\":true,\"is_it\":true,\"is_hr\":true,\"is_cs\":true,\"is_finance\":true,\"is_driver_supervisor\":true,\"is_trader\":true,\"is_manager\":false,\"is_team_lead\":false,\"can_approve_expenses\":false,\"can_manage_inventory\":false,\"contract_end_date\":null,\"salary\":\"50000.00\",\"approval_limit\":\"0.00\",\"commission_rate\":\"0.00\",\"bank_name\":null,\"bank_account\":null,\"iban\":null,\"emergency_contact_name\":null,\"emergency_contact_phone\":null,\"emergency_contact_relation\":null,\"notes\":null,\"skills\":null,\"qualifications\":null,\"certifications\":null,\"languages\":null,\"preferred_communication\":\"email\",\"deleted_at\":null}', '{\"id\":1,\"employee_id\":\"EMP001\",\"department\":\"Administration\",\"work_location\":null,\"position\":\"Super Admin\",\"manager_id\":null,\"hourly_rate\":null,\"monthly_salary\":null,\"hire_date\":\"2026-03-09T00:00:00.000000Z\",\"termination_date\":null,\"employment_type\":\"full_time\",\"work_schedule\":null,\"status\":\"active\",\"security_level\":\"1\",\"emergency_contact\":null,\"documents\":null,\"created_at\":\"2026-01-06T07:15:19.000000Z\",\"updated_at\":\"2026-03-11T05:46:46.000000Z\",\"employee_code\":\"EMP001\",\"employee_id_card\":null,\"first_name\":\"Admin\",\"last_name\":\"User\",\"email\":\"admin@tulipstore.com\",\"profile_photo\":null,\"bio\":null,\"email_verified_at\":null,\"last_login_at\":null,\"login_count\":0,\"two_factor_enabled\":false,\"ip_restrictions\":null,\"performance_score\":null,\"last_review_date\":null,\"next_review_date\":null,\"phone\":\"1234567890\",\"national_id\":null,\"date_of_birth\":null,\"gender\":null,\"marital_status\":null,\"address\":null,\"city\":null,\"country\":\"Saudi Arabia\",\"is_admin\":true,\"is_it\":true,\"is_hr\":true,\"is_cs\":true,\"is_finance\":true,\"is_driver_supervisor\":true,\"is_trader\":true,\"is_manager\":false,\"is_team_lead\":false,\"can_approve_expenses\":false,\"can_manage_inventory\":false,\"contract_end_date\":null,\"salary\":\"50000.00\",\"approval_limit\":\"0.00\",\"commission_rate\":\"0.00\",\"bank_name\":null,\"bank_account\":null,\"iban\":null,\"emergency_contact_name\":null,\"emergency_contact_phone\":null,\"emergency_contact_relation\":null,\"notes\":null,\"skills\":null,\"qualifications\":null,\"certifications\":null,\"languages\":null,\"preferred_communication\":\"email\",\"deleted_at\":null,\"user\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-11 02:54:54', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(83, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":false,\"can_view\":null,\"can_edit\":null,\"sections\":null,\"actions\":null,\"can_view_sensitive\":null}}', '127.0.0.1', NULL, NULL, '2026-03-21 17:59:59', NULL, NULL),
(84, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":false,\"can_view\":null,\"can_edit\":null,\"sections\":null,\"actions\":null,\"can_view_sensitive\":null}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:37:49', NULL, NULL),
(85, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":false,\"can_view\":null,\"can_edit\":null,\"sections\":null,\"actions\":null,\"can_view_sensitive\":null}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:38:04', NULL, NULL),
(86, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":true,\"can_view\":false,\"can_edit\":false,\"sections\":[],\"actions\":[],\"can_view_sensitive\":false}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:40:58', NULL, NULL),
(87, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":true,\"can_view\":true,\"can_edit\":true,\"sections\":[],\"actions\":[],\"can_view_sensitive\":false}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:41:14', NULL, NULL),
(88, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":true,\"can_view\":false,\"can_edit\":false,\"sections\":[],\"actions\":[],\"can_view_sensitive\":false}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:45:34', NULL, NULL),
(89, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":true,\"can_view\":false,\"can_edit\":false,\"sections\":[],\"actions\":[],\"can_view_sensitive\":false}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:45:36', NULL, NULL),
(90, NULL, 'employee_permission_override_update', 'Employee', 1, NULL, '{\"dashboard\":\"it\",\"payload\":{\"is_override\":true,\"can_view\":true,\"can_edit\":true,\"sections\":[],\"actions\":[],\"can_view_sensitive\":false}}', '127.0.0.1', NULL, NULL, '2026-03-21 18:46:01', NULL, NULL),
(103, NULL, 'order_status_changed', 'App\\Models\\Order', 8, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:01:23', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(104, NULL, 'status_transition', 'App\\Models\\Order', 8, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:01:23', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-22T10:01:23.923195Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(105, NULL, 'order_status_changed', 'App\\Models\\Order', 8, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:01:24', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(106, NULL, 'status_transition', 'App\\Models\\Order', 8, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:01:24', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-22T10:01:24.535759Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(107, NULL, 'driver_assigned', 'App\\Models\\Order', 8, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"2\",\"assignment_id\":6}', NULL, NULL, NULL, '2026-03-22 10:01:25', NULL, NULL),
(108, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:01:25', NULL, NULL),
(109, NULL, 'order_status_changed', 'App\\Models\\Order', 8, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:03', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(110, NULL, 'status_transition', 'App\\Models\\Order', 8, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:03', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"out_for_delivery\",\"to\":\"delivered\",\"admin_override\":false,\"timestamp\":\"2026-03-22T10:03:03.199641Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(111, NULL, 'order_completed', 'App\\Models\\Order', 8, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\",\"commission_transaction_id\":22}', NULL, NULL, NULL, '2026-03-22 10:03:03', NULL, NULL),
(112, NULL, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:07', NULL, NULL),
(113, NULL, 'order_status_changed', 'App\\Models\\Order', 8, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(114, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 21, '{\"status\":\"pending\",\"amount\":\"16.04\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"16.04\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\",\"order_id\":8}'),
(115, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 8, NULL, '{\"order_id\":8,\"amount\":\"16.04\",\"currency\":\"USD\",\"financial_transaction_id\":21}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(116, NULL, 'store_sales_incremented', 'App\\Models\\Order', 8, NULL, '{\"store_id\":2,\"amount\":16.04,\"source\":\"order_finalization\",\"order_id\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(117, NULL, 'order_marked_completed', 'App\\Models\\Order', 8, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-22T10:03:19.608326Z\",\"revenue_recognized_at\":\"2026-03-22T10:03:19.619454Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\"}'),
(118, NULL, 'status_transition', 'App\\Models\\Order', 8, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 10:03:19', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-22T10:03:19.708783Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(119, NULL, 'order_status_changed', 'App\\Models\\Order', 9, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:12:23', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(120, NULL, 'status_transition', 'App\\Models\\Order', 9, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:12:23', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-22T11:12:23.345188Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(121, NULL, 'order_status_changed', 'App\\Models\\Order', 9, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:12:23', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(122, NULL, 'status_transition', 'App\\Models\\Order', 9, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:12:23', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-22T11:12:23.558379Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(123, NULL, 'driver_assigned', 'App\\Models\\Order', 9, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"7\",\"assignment_id\":7}', NULL, NULL, NULL, '2026-03-22 11:12:23', NULL, NULL),
(124, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:12:23', NULL, NULL),
(125, NULL, 'order_status_changed', 'App\\Models\\Order', 9, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:26:23', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":8,\"employee_email\":\"yousefalhalabi445@gmail.com\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(126, NULL, 'order_status_changed', 'App\\Models\\Order', 9, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:28', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(127, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 23, '{\"status\":\"pending\",\"amount\":\"12.04\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"12.04\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:29', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\",\"order_id\":9}'),
(128, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 9, NULL, '{\"order_id\":9,\"amount\":\"12.04\",\"currency\":\"USD\",\"financial_transaction_id\":23}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:29', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(129, NULL, 'store_sales_incremented', 'App\\Models\\Order', 9, NULL, '{\"store_id\":2,\"amount\":12.04,\"source\":\"order_finalization\",\"order_id\":9}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:29', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(130, NULL, 'order_marked_completed', 'App\\Models\\Order', 9, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-22T11:27:29.217390Z\",\"revenue_recognized_at\":\"2026-03-22T11:27:29.246968Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:29', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\"}'),
(131, NULL, 'status_transition', 'App\\Models\\Order', 9, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:27:29', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-22T11:27:29.307948Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(132, NULL, 'order_status_changed', 'App\\Models\\Order', 10, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:31:54', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(133, NULL, 'status_transition', 'App\\Models\\Order', 10, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:31:54', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-22T11:31:54.546216Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(134, NULL, 'order_status_changed', 'App\\Models\\Order', 10, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:31:54', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(135, NULL, 'status_transition', 'App\\Models\\Order', 10, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:31:54', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-22T11:31:54.759730Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(136, NULL, 'driver_assigned', 'App\\Models\\Order', 10, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"7\",\"assignment_id\":8}', NULL, NULL, NULL, '2026-03-22 11:31:55', NULL, NULL),
(137, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:31:55', NULL, NULL),
(138, NULL, 'order_status_changed', 'App\\Models\\Order', 10, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:33:01', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":8,\"employee_email\":\"yousefalhalabi445@gmail.com\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(139, NULL, 'order_status_changed', 'App\\Models\\Order', 10, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:21', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(140, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 24, '{\"status\":\"pending\",\"amount\":\"17.04\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"17.04\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:25', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\",\"order_id\":10}'),
(141, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 10, NULL, '{\"order_id\":10,\"amount\":\"17.04\",\"currency\":\"USD\",\"financial_transaction_id\":24}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:25', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(142, NULL, 'store_sales_incremented', 'App\\Models\\Order', 10, NULL, '{\"store_id\":2,\"amount\":17.04,\"source\":\"order_finalization\",\"order_id\":10}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:25', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(143, NULL, 'order_marked_completed', 'App\\Models\\Order', 10, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-22T11:34:25.359930Z\",\"revenue_recognized_at\":\"2026-03-22T11:34:25.374460Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:25', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\"}'),
(144, NULL, 'status_transition', 'App\\Models\\Order', 10, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-22 11:34:25', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-22T11:34:25.448247Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(145, NULL, 'order_status_changed', 'App\\Models\\Order', 11, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:22:53', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `session_id`, `created_at`, `updated_at`, `metadata`) VALUES
(146, NULL, 'status_transition', 'App\\Models\\Order', 11, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:22:53', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-23T06:22:53.338577Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(147, NULL, 'order_status_changed', 'App\\Models\\Order', 11, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:22:53', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(148, NULL, 'status_transition', 'App\\Models\\Order', 11, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:22:53', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-23T06:22:53.506782Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(149, NULL, 'driver_assigned', 'App\\Models\\Order', 11, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"2\",\"assignment_id\":9}', NULL, NULL, NULL, '2026-03-23 06:22:53', NULL, NULL),
(150, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:22:53', NULL, NULL),
(151, NULL, 'order_status_changed', 'App\\Models\\Order', 12, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:26:10', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(152, NULL, 'status_transition', 'App\\Models\\Order', 12, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:26:10', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-23T06:26:10.827574Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(153, NULL, 'order_status_changed', 'App\\Models\\Order', 12, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:26:11', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(154, NULL, 'status_transition', 'App\\Models\\Order', 12, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:26:11', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-23T06:26:11.013048Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(155, NULL, 'driver_assigned', 'App\\Models\\Order', 12, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"7\",\"assignment_id\":10}', NULL, NULL, NULL, '2026-03-23 06:26:11', NULL, NULL),
(156, NULL, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:26:11', NULL, NULL),
(157, NULL, 'order_status_changed', 'App\\Models\\Order', 12, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:27:19', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":8,\"employee_email\":\"yousefalhalabi445@gmail.com\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(158, NULL, 'order_status_changed', 'App\\Models\\Order', 12, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:12', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(159, NULL, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 26, '{\"status\":\"pending\",\"amount\":\"111.77\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"111.77\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:13', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\",\"order_id\":12}'),
(160, NULL, 'order_revenue_recorded', 'App\\Models\\Order', 12, NULL, '{\"order_id\":12,\"amount\":\"111.77\",\"currency\":\"USD\",\"financial_transaction_id\":26}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:13', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(161, NULL, 'store_sales_incremented', 'App\\Models\\Order', 12, NULL, '{\"store_id\":2,\"amount\":111.77,\"source\":\"order_finalization\",\"order_id\":12}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:13', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(162, NULL, 'order_marked_completed', 'App\\Models\\Order', 12, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-23T06:29:13.144115Z\",\"revenue_recognized_at\":\"2026-03-23T06:29:13.152841Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:13', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"order_finalization\"}'),
(163, NULL, 'status_transition', 'App\\Models\\Order', 12, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-23 06:29:13', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":false,\"timestamp\":\"2026-03-23T06:29:13.180023Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(166, NULL, 'trader_product_custom_fields_updated', 'App\\Models\\Product', 1155, '{\"custom_fields\":[]}', '{\"custom_fields\":[{\"id\":202,\"name\":\"color\",\"key\":\"color\",\"type\":\"radio_group\",\"value\":\"\",\"sort_order\":0,\"is_required\":false,\"rules\":{\"min_length\":null,\"max_length\":null,\"min\":null,\"max\":null,\"allowed_file_types\":null,\"max_file_size_kb\":null}}]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-31 12:25:27', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"trader_dashboard\"}'),
(167, NULL, 'support_trader_product_approved_to_admin', 'App\\Models\\Product', 1155, NULL, '{\"status\":\"pending_admin\",\"is_active\":true,\"reviewed_by\":1,\"reviewed_at\":\"2026-03-31T12:25:36.730453Z\",\"rejection_reason\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-03-31 12:25:36', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"cs_dashboard\"}'),
(172, NULL, 'category_create', 'Category', 1046, NULL, '{\"name\":\"\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0627\\u062a\",\"slug\":\"electronic\",\"description\":null,\"display_order\":0,\"is_active\":true,\"market\":\"store\",\"image\":\"categories\\/ledsOIAppSfgxR7VuGF2phudqQM0CBTt7kRol6qk.jpg\"}', '127.0.0.1', NULL, NULL, '2026-04-01 08:40:08', NULL, NULL),
(173, NULL, 'category_update', 'Category', 1046, '{\"name\":\"\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0627\\u062a\",\"slug\":\"electronic\",\"description\":null,\"display_order\":0,\"is_active\":true}', '{\"name\":\"\\u0627\\u0644\\u0643\\u062a\\u0631\\u0648\\u0646\\u064a\\u0627\\u062a\",\"slug\":\"electronic\",\"description\":null,\"display_order\":0,\"is_active\":true,\"image\":\"categories\\/UULffiNNa8DVeTQnGUpTehRsneTJXUWk6ItLHcA0.jpg\"}', '127.0.0.1', NULL, NULL, '2026-04-01 08:49:26', NULL, NULL),
(174, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/verify-registration\",\"status\":200,\"input\":[]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:21', NULL, '{\"route\":null}'),
(175, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"SYP\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:37', NULL, '{\"route\":null}'),
(176, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/add\",\"status\":200,\"input\":{\"product_id\":1155,\"quantity\":1}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:51', NULL, '{\"route\":null}'),
(177, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"SYP\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:56', NULL, '{\"route\":null}'),
(178, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:58', NULL, '{\"route\":null}'),
(179, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"address\":null,\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:02:59', NULL, '{\"route\":null}'),
(180, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:03:02', NULL, '{\"route\":null}'),
(181, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:03:18', NULL, '{\"route\":null}'),
(182, 24, 'POST:dashboard.cs.customers.balance.adjust', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"dashboard\\/cs\\/customers\\/24\\/balance\",\"status\":302,\"input\":{\"action\":\"add\",\"amount\":\"50\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:03:41', NULL, '{\"route\":\"dashboard.cs.customers.balance.adjust\"}'),
(183, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:03:58', NULL, '{\"route\":null}'),
(184, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/addresses\",\"status\":500,\"input\":{\"label\":\"Rajm al-Zeitoun, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"contact_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"line1\":\"Rajm al-Zeitoun, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"line2\":null,\"city\":\"As-Suwayda\",\"country\":\"SY\",\"lat\":32.71098822350655,\"lng\":36.57849956470163,\"is_default\":false}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:04:08', NULL, '{\"route\":null}'),
(185, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/orders\\/create\",\"status\":200,\"input\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Rajm al-Zeitoun, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.71098822350655,\"lng\":36.57849956470163},\"delivery_method\":\"normal\",\"payment_method\":\"balance\",\"delivery_cost\":1.8717948717948718,\"service_fee\":0,\"idempotency_key\":\"ef2df8d2-1ef8-4457-b6cc-62679b799e2a\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:04:12', NULL, '{\"route\":null}'),
(186, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/add\",\"status\":200,\"input\":{\"product_id\":1155,\"quantity\":1}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:05:49', NULL, '{\"route\":null}'),
(187, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:06:52', NULL, '{\"route\":null}'),
(188, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:14:13', NULL, '{\"route\":null}'),
(189, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:22:18', NULL, '{\"route\":null}'),
(190, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/remove\",\"status\":200,\"input\":{\"item_id\":1155}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:22:27', NULL, '{\"route\":null}'),
(191, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/add\",\"status\":200,\"input\":{\"product_id\":1154,\"quantity\":1}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:43:09', NULL, '{\"route\":null}'),
(192, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-01 10:44:10', NULL, '{\"route\":null}'),
(193, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/login\",\"status\":200,\"input\":{\"email\":\"yousefalhalabi63@gmail.com\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:21:58', NULL, '{\"route\":null}'),
(194, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:35', NULL, '{\"route\":null}'),
(195, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:40', NULL, '{\"route\":null}'),
(196, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:50', NULL, '{\"route\":null}'),
(197, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:51', NULL, '{\"route\":null}'),
(198, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:52', NULL, '{\"route\":null}'),
(199, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:53', NULL, '{\"route\":null}'),
(200, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:53', NULL, '{\"route\":null}'),
(201, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:54', NULL, '{\"route\":null}'),
(202, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:55', NULL, '{\"route\":null}'),
(203, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:55', NULL, '{\"route\":null}'),
(204, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:56', NULL, '{\"route\":null}'),
(205, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:57', NULL, '{\"route\":null}'),
(206, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:57', NULL, '{\"route\":null}'),
(207, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:58', NULL, '{\"route\":null}'),
(208, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:22:59', NULL, '{\"route\":null}'),
(209, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:23:00', NULL, '{\"route\":null}'),
(210, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/remove\",\"status\":200,\"input\":{\"item_id\":1154}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:24:23', NULL, '{\"route\":null}'),
(211, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:02', NULL, '{\"route\":null}'),
(212, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:04', NULL, '{\"route\":null}'),
(213, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:05', NULL, '{\"route\":null}'),
(214, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:05', NULL, '{\"route\":null}'),
(215, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:06', NULL, '{\"route\":null}'),
(216, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:07', NULL, '{\"route\":null}'),
(217, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:07', NULL, '{\"route\":null}'),
(218, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:08', NULL, '{\"route\":null}'),
(219, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:09', NULL, '{\"route\":null}'),
(220, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:09', NULL, '{\"route\":null}'),
(221, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:10', NULL, '{\"route\":null}'),
(222, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:11', NULL, '{\"route\":null}'),
(223, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:27:20', NULL, '{\"route\":null}'),
(224, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:29:34', NULL, '{\"route\":null}'),
(225, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:29:48', NULL, '{\"route\":null}'),
(226, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:34:19', NULL, '{\"route\":null}'),
(227, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:34:30', NULL, '{\"route\":null}'),
(228, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:38:28', NULL, '{\"route\":null}'),
(229, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":500,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:38:38', NULL, '{\"route\":null}'),
(230, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":200,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u062a\\u062c\\u0631\\u0628\\u0629\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:42:16', NULL, '{\"route\":null}'),
(231, 24, 'DELETE', NULL, NULL, NULL, '{\"method\":\"DELETE\",\"path\":\"api\\/support\\/coupons\\/1\",\"status\":200,\"input\":[]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:42:47', NULL, '{\"route\":null}'),
(232, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":200,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u0639\\u064a\\u062f \\u0627\\u0644\\u0641\\u0635\\u062d\",\"max_uses\":\"100\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:43:28', NULL, '{\"route\":null}'),
(233, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:44:00', NULL, '{\"route\":null}'),
(234, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:44:50', NULL, '{\"route\":null}'),
(235, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:47:36', NULL, '{\"route\":null}'),
(236, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:53:01', NULL, '{\"route\":null}'),
(237, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:55:52', NULL, '{\"route\":null}'),
(238, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/addresses\",\"status\":500,\"input\":{\"label\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"contact_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"line1\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"line2\":null,\"city\":\"As-Suwayda\",\"country\":\"SY\",\"lat\":32.711884177847196,\"lng\":36.57639712834898,\"is_default\":false}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:57:04', NULL, '{\"route\":null}'),
(239, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/orders\\/create\",\"status\":200,\"input\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.711884177847196,\"lng\":36.57639712834898},\"delivery_method\":\"normal\",\"payment_method\":\"cash\",\"delivery_cost\":1.6324786324786325,\"service_fee\":0,\"idempotency_key\":\"5f930ff9-fc74-46c2-b063-79b0f467ea39\",\"coupon_code\":\"TB10\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 05:57:05', NULL, '{\"route\":null}'),
(240, 24, 'DELETE', NULL, NULL, NULL, '{\"method\":\"DELETE\",\"path\":\"api\\/support\\/coupons\\/2\",\"status\":200,\"input\":[]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:00:43', NULL, '{\"route\":null}'),
(241, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/add\",\"status\":200,\"input\":{\"product_id\":1155,\"quantity\":1}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:01:51', NULL, '{\"route\":null}'),
(242, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:02:10', NULL, '{\"route\":null}'),
(243, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/support\\/coupons\",\"status\":200,\"input\":{\"discount_percentage\":\"10\",\"purpose\":\"\\u0639\\u064a\\u062f \\u0633\\u0639\\u064a\\u062f\",\"max_uses\":\"1\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:12:12', NULL, '{\"route\":null}'),
(244, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:12:28', NULL, '{\"route\":null}'),
(245, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/addresses\",\"status\":500,\"input\":{\"label\":\"\\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"contact_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"line1\":\"\\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"line2\":null,\"city\":\"As-Suwayda\",\"country\":\"SY\",\"lat\":32.70851671152332,\"lng\":36.578842658161754,\"is_default\":false}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:13:15', NULL, '{\"route\":null}'),
(246, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/orders\\/create\",\"status\":200,\"input\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"\\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.70851671152332,\"lng\":36.578842658161754},\"delivery_method\":\"normal\",\"payment_method\":\"balance\",\"delivery_cost\":1.811965811965812,\"service_fee\":0,\"idempotency_key\":\"19caab10-6a69-4259-a6da-09ba7ba565ea\",\"coupon_code\":\"TB10\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:13:20', NULL, '{\"route\":null}'),
(247, 24, 'DELETE', NULL, NULL, NULL, '{\"method\":\"DELETE\",\"path\":\"api\\/support\\/coupons\\/3\",\"status\":200,\"input\":[]}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:14:03', NULL, '{\"route\":null}'),
(248, 24, 'POST:dashboard.supervisor.drivers.store', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"dashboard\\/supervisor\\/drivers\",\"status\":302,\"input\":{\"name\":\"\\u0623\\u062d\\u0645\\u062f\",\"username\":\"Ahmad1\",\"phone\":\"0994251800\",\"license_number\":\"LIC-0035\",\"license_expiry\":\"2030-01-13\",\"vehicle_type\":\"\\u062f\\u0631\\u0627\\u062c\\u0629 \\u0646\\u0627\\u0631\\u064a\\u0629\",\"vehicle_plate\":\"\\u0637 9012\",\"status\":\"active\",\"availability\":\"available\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:20:09', NULL, '{\"route\":\"dashboard.supervisor.drivers.store\"}'),
(270, 1, 'order_status_changed', 'App\\Models\\Order', 14, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:24', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(271, NULL, 'status_transition', 'App\\Models\\Order', 14, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:24', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-04-02T06:43:24.210574Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(272, 1, 'order_status_changed', 'App\\Models\\Order', 14, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:24', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(273, NULL, 'status_transition', 'App\\Models\\Order', 14, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:24', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-04-02T06:43:24.370415Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(274, NULL, 'driver_assigned', 'App\\Models\\Order', 14, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"8\",\"assignment_id\":12}', NULL, NULL, NULL, '2026-04-02 06:43:24', NULL, NULL),
(275, 1, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:24', NULL, NULL),
(276, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/login\",\"status\":200,\"input\":{\"email\":\"yousefalhalabi63@gmail.com\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:43:39', NULL, '{\"route\":null}'),
(277, 9, 'order_status_changed', 'App\\Models\\Order', 14, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:49:40', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":9,\"employee_email\":\"ahmad1@drivers.local\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(278, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/login\",\"status\":200,\"input\":{\"email\":\"yousefalhalabi63@gmail.com\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:49:56', NULL, '{\"route\":null}'),
(279, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 06:52:42', NULL, '{\"route\":null}'),
(280, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/add\",\"status\":200,\"input\":{\"product_id\":1155,\"quantity\":1}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:32:07', NULL, '{\"route\":null}'),
(281, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/login\",\"status\":200,\"input\":{\"email\":\"yousefalhalabi63@gmail.com\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:42:55', NULL, '{\"route\":null}'),
(282, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/cart\\/update\",\"status\":200,\"input\":{\"item_id\":1155,\"quantity\":2}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:43:10', NULL, '{\"route\":null}'),
(283, 24, 'PUT', NULL, NULL, NULL, '{\"method\":\"PUT\",\"path\":\"profile\\/update\",\"status\":200,\"input\":{\"currency\":\"USD\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:43:20', NULL, '{\"route\":null}');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `session_id`, `created_at`, `updated_at`, `metadata`) VALUES
(284, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/addresses\",\"status\":500,\"input\":{\"label\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"contact_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"line1\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"line2\":null,\"city\":\"As-Suwayda\",\"country\":\"SY\",\"lat\":32.712010308128356,\"lng\":36.57543126449436,\"is_default\":false}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:43:34', NULL, '{\"route\":null}'),
(285, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/orders\\/create\",\"status\":200,\"input\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.712010308128356,\"lng\":36.57543126449436},\"delivery_method\":\"normal\",\"payment_method\":\"cash\",\"delivery_cost\":1.564102564102564,\"service_fee\":0,\"idempotency_key\":\"042dbcdc-edd8-48ce-9fbf-43694e88596d\",\"coupon_code\":\"TEST123\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:43:36', NULL, '{\"route\":null}'),
(286, 24, 'order_status_changed', 'App\\Models\\Order', 16, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(287, NULL, 'status_transition', 'App\\Models\\Order', 16, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-04-02T07:44:15.158347Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(288, 24, 'order_status_changed', 'App\\Models\\Order', 16, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(289, NULL, 'status_transition', 'App\\Models\\Order', 16, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-04-02T07:44:15.379475Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(290, NULL, 'driver_assigned', 'App\\Models\\Order', 16, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"8\",\"assignment_id\":13}', NULL, NULL, NULL, '2026-04-02 07:44:15', NULL, NULL),
(291, 1, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, NULL),
(292, 24, 'POST:dashboard.supervisor.assign-order', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"dashboard\\/supervisor\\/assign-order\",\"status\":200,\"input\":{\"order_id\":16,\"driver_id\":\"8\",\"delivery_fee\":0,\"notes\":null}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:44:15', NULL, '{\"route\":\"dashboard.supervisor.assign-order\"}'),
(293, 9, 'order_status_changed', 'App\\Models\\Order', 16, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:45:09', NULL, '{\"actor\":{\"guard\":\"employee\",\"employee_id\":9,\"employee_email\":\"ahmad1@drivers.local\"},\"source\":\"observer\",\"guard\":\"employee\"}'),
(294, 24, 'POST', NULL, NULL, NULL, '{\"method\":\"POST\",\"path\":\"api\\/login\",\"status\":200,\"input\":{\"email\":\"yousefalhalabi63@gmail.com\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', NULL, '2026-04-02 07:46:51', NULL, '{\"route\":null}');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_reference` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'transfer',
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `occurred_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` varchar(50) NOT NULL,
  `image_url` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `button_text` varchar(50) DEFAULT NULL,
  `link_url` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'store'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image_url`, `title`, `subtitle`, `button_text`, `link_url`, `type`) VALUES
('test_b1', 'assets/images/banners/banner1.png', 'تخفيضات الشتاء', 'خصم يصل إلى 50%', 'تسوق الآن', '/catalog?category=1001', 'store'),
('test_b2', 'assets/images/banners/banner2.png', 'مجموعة الربيع', 'أحدث الموديلات وصلت', 'اكتشف المزيد', '/catalog', 'store'),
('test_g1', 'assets/images/banners/promo1.png', 'نسق هديتك بنفسك', 'أفضل التنسيقات لأحبائك', 'ابدأ التنسيق', '/gift-coordination', 'gift_promo'),
('test_m1', 'assets/images/banners/market_banner1.png', 'خضروات طازجة', 'يومياً من المزرعة إليك', 'اطلب الآن', '/market', 'market');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `period_type` varchar(255) NOT NULL,
  `period` varchar(255) NOT NULL,
  `budgeted_amount` decimal(12,2) NOT NULL,
  `actual_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `variance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `variance_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tulip-store-cache-a75f3f172bfb296f2e10cbfc6dfc1883', 'i:3;', 1775116077),
('tulip-store-cache-a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1775116077;', 1775116077),
('tulip-store-cache-admin_global_metrics', 'a:29:{s:11:\"total_users\";i:1;s:12:\"active_users\";i:1;s:12:\"total_stores\";i:1;s:13:\"active_stores\";i:1;s:13:\"total_revenue\";d:22.68;s:13:\"revenue_today\";d:10.81;s:15:\"monthly_revenue\";d:22.68;s:16:\"total_commission\";i:0;s:18:\"monthly_commission\";i:0;s:12:\"total_orders\";i:3;s:14:\"monthly_orders\";i:3;s:14:\"pending_orders\";i:3;s:13:\"active_orders\";i:3;s:15:\"avg_order_value\";d:11.34;s:14:\"total_products\";i:2;s:15:\"active_products\";i:2;s:16:\"low_stock_alerts\";i:0;s:18:\"low_stock_products\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"user_growth\";i:100;s:14:\"revenue_growth\";i:100;s:12:\"order_growth\";i:100;s:13:\"system_alerts\";i:0;s:23:\"pending_support_tickets\";i:0;s:17:\"recent_activities\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-69CE08FC8AF15\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:5:\"10.81\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-02 09:13:16.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:13:\"Asia/Damascus\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:1;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-69CE053152A9F\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:5:\"10.63\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-02 08:57:05.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:13:\"Asia/Damascus\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:2;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-69CCED98D0F8D\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:5:\"11.87\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-01 13:04:09.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:13:\"Asia/Damascus\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:3;a:7:{s:4:\"type\";s:4:\"user\";s:5:\"title\";s:21:\"New User Registration\";s:11:\"description\";s:41:\"يوسف الحلبي joined the platform\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-01 13:01:51.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:13:\"Asia/Damascus\";}s:4:\"icon\";s:12:\"fa-user-plus\";s:5:\"color\";s:14:\"text-green-600\";}i:4;a:7:{s:4:\"type\";s:5:\"store\";s:5:\"title\";s:17:\"New Store Created\";s:11:\"description\";s:27:\"My Store opened their store\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-04-01 09:11:57.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:13:\"Asia/Damascus\";}s:4:\"icon\";s:8:\"fa-store\";s:5:\"color\";s:15:\"text-purple-600\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"top_performing_stores\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:16:\"App\\Models\\Store\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"stores\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:7;s:4:\"name\";s:8:\"My Store\";s:4:\"slug\";s:16:\"store-bfhamx5vzb\";s:11:\"description\";N;s:4:\"logo\";N;s:5:\"phone\";N;s:5:\"email\";N;s:6:\"status\";s:6:\"active\";s:11:\"total_sales\";s:4:\"0.00\";s:10:\"created_at\";s:19:\"2026-04-01 09:11:57\";s:16:\"orders_sum_total\";s:4:\"0.00\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:7;s:4:\"name\";s:8:\"My Store\";s:4:\"slug\";s:16:\"store-bfhamx5vzb\";s:11:\"description\";N;s:4:\"logo\";N;s:5:\"phone\";N;s:5:\"email\";N;s:6:\"status\";s:6:\"active\";s:11:\"total_sales\";s:4:\"0.00\";s:10:\"created_at\";s:19:\"2026-04-01 09:11:57\";s:16:\"orders_sum_total\";s:4:\"0.00\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:6:{s:15:\"commission_rate\";s:9:\"decimal:2\";s:11:\"total_sales\";s:9:\"decimal:2\";s:16:\"total_commission\";s:9:\"decimal:2\";s:7:\"balance\";s:9:\"decimal:2\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:17:{i:0;s:15:\"organization_id\";i:1;s:8:\"owner_id\";i:2;s:7:\"user_id\";i:3;s:4:\"name\";i:4;s:4:\"slug\";i:5;s:11:\"description\";i:6;s:4:\"logo\";i:7;s:6:\"banner\";i:8;s:5:\"phone\";i:9;s:5:\"email\";i:10;s:7:\"address\";i:11;s:6:\"status\";i:12;s:15:\"commission_rate\";i:13;s:11:\"total_sales\";i:14;s:16:\"total_commission\";i:15;s:7:\"balance\";i:16;s:11:\"is_featured\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:17:\"revenue_chart_30d\";a:30:{i:0;a:2:{s:4:\"date\";s:10:\"2026-03-04\";s:7:\"revenue\";d:0;}i:1;a:2:{s:4:\"date\";s:10:\"2026-03-05\";s:7:\"revenue\";d:0;}i:2;a:2:{s:4:\"date\";s:10:\"2026-03-06\";s:7:\"revenue\";d:0;}i:3;a:2:{s:4:\"date\";s:10:\"2026-03-07\";s:7:\"revenue\";d:0;}i:4;a:2:{s:4:\"date\";s:10:\"2026-03-08\";s:7:\"revenue\";d:0;}i:5;a:2:{s:4:\"date\";s:10:\"2026-03-09\";s:7:\"revenue\";d:0;}i:6;a:2:{s:4:\"date\";s:10:\"2026-03-10\";s:7:\"revenue\";d:0;}i:7;a:2:{s:4:\"date\";s:10:\"2026-03-11\";s:7:\"revenue\";d:0;}i:8;a:2:{s:4:\"date\";s:10:\"2026-03-12\";s:7:\"revenue\";d:0;}i:9;a:2:{s:4:\"date\";s:10:\"2026-03-13\";s:7:\"revenue\";d:0;}i:10;a:2:{s:4:\"date\";s:10:\"2026-03-14\";s:7:\"revenue\";d:0;}i:11;a:2:{s:4:\"date\";s:10:\"2026-03-15\";s:7:\"revenue\";d:0;}i:12;a:2:{s:4:\"date\";s:10:\"2026-03-16\";s:7:\"revenue\";d:0;}i:13;a:2:{s:4:\"date\";s:10:\"2026-03-17\";s:7:\"revenue\";d:0;}i:14;a:2:{s:4:\"date\";s:10:\"2026-03-18\";s:7:\"revenue\";d:0;}i:15;a:2:{s:4:\"date\";s:10:\"2026-03-19\";s:7:\"revenue\";d:0;}i:16;a:2:{s:4:\"date\";s:10:\"2026-03-20\";s:7:\"revenue\";d:0;}i:17;a:2:{s:4:\"date\";s:10:\"2026-03-21\";s:7:\"revenue\";d:0;}i:18;a:2:{s:4:\"date\";s:10:\"2026-03-22\";s:7:\"revenue\";d:0;}i:19;a:2:{s:4:\"date\";s:10:\"2026-03-23\";s:7:\"revenue\";d:0;}i:20;a:2:{s:4:\"date\";s:10:\"2026-03-24\";s:7:\"revenue\";d:0;}i:21;a:2:{s:4:\"date\";s:10:\"2026-03-25\";s:7:\"revenue\";d:0;}i:22;a:2:{s:4:\"date\";s:10:\"2026-03-26\";s:7:\"revenue\";d:0;}i:23;a:2:{s:4:\"date\";s:10:\"2026-03-27\";s:7:\"revenue\";d:0;}i:24;a:2:{s:4:\"date\";s:10:\"2026-03-28\";s:7:\"revenue\";d:0;}i:25;a:2:{s:4:\"date\";s:10:\"2026-03-29\";s:7:\"revenue\";d:0;}i:26;a:2:{s:4:\"date\";s:10:\"2026-03-30\";s:7:\"revenue\";d:0;}i:27;a:2:{s:4:\"date\";s:10:\"2026-03-31\";s:7:\"revenue\";d:0;}i:28;a:2:{s:4:\"date\";s:10:\"2026-04-01\";s:7:\"revenue\";d:11.87;}i:29;a:2:{s:4:\"date\";s:10:\"2026-04-02\";s:7:\"revenue\";d:10.81;}}s:20:\"orders_by_status_30d\";a:2:{s:6:\"labels\";a:1:{i:0;s:7:\"pending\";}s:6:\"values\";a:1:{i:0;i:3;}}s:16:\"top_products_30d\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":4:{s:4:\"name\";s:28:\"فستان سهرة أنيق\";s:5:\"image\";N;s:10:\"total_sold\";s:1:\"2\";s:13:\"total_revenue\";s:5:\"20.00\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"geo_orders_30d\";a:1:{i:0;a:4:{s:1:\"x\";d:36.58;s:1:\"y\";d:32.71;s:1:\"r\";i:3;s:5:\"count\";i:3;}}}', 1775110894),
('tulip-store-cache-it_metrics', 'a:24:{s:15:\"services_online\";i:0;s:16:\"services_offline\";i:0;s:17:\"services_degraded\";i:0;s:17:\"avg_response_time\";d:0;s:15:\"critical_alerts\";i:0;s:14:\"warning_alerts\";i:0;s:19:\"total_active_alerts\";i:0;s:16:\"api_errors_today\";i:0;s:14:\"error_rate_24h\";d:0;s:18:\"slow_queries_today\";i:0;s:14:\"avg_query_time\";d:0;s:13:\"database_size\";s:8:\"11.03 MB\";s:11:\"last_backup\";N;s:19:\"backup_success_rate\";i:100;s:15:\"last_deployment\";N;s:22:\"deployments_this_month\";i:0;s:23:\"deployment_success_rate\";i:100;s:9:\"cpu_usage\";i:0;s:12:\"memory_usage\";i:0;s:10:\"disk_usage\";d:0;s:18:\"network_throughput\";s:25:\"Monitoring not configured\";s:13:\"recent_alerts\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"recent_deployments\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:13:\"system_uptime\";s:28:\"15 days, 8 hours, 32 minutes\";}', 1775110647);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tulip-store-cache-supervisor_metrics', 'a:25:{s:13:\"total_drivers\";i:1;s:14:\"active_drivers\";i:1;s:17:\"available_drivers\";i:1;s:15:\"offline_drivers\";i:0;s:16:\"on_break_drivers\";i:0;s:19:\"drivers_on_delivery\";i:0;s:19:\"pending_assignments\";i:0;s:17:\"active_deliveries\";i:0;s:15:\"completed_today\";i:1;s:17:\"failed_deliveries\";i:0;s:22:\"deliveries_today_total\";i:3;s:17:\"in_progress_today\";i:0;s:13:\"pending_today\";i:2;s:26:\"orders_awaiting_assignment\";i:3;s:17:\"orders_in_transit\";i:0;s:17:\"avg_delivery_time\";d:6;s:21:\"on_time_delivery_rate\";d:100;s:17:\"driver_efficiency\";d:87.5;s:17:\"avg_driver_rating\";d:5;s:23:\"vehicles_in_maintenance\";i:0;s:15:\"maintenance_due\";i:0;s:18:\"recent_assignments\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:29:\"App\\Models\\DeliveryAssignment\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:20:\"delivery_assignments\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:23:{s:2:\"id\";i:12;s:8:\"order_id\";i:14;s:9:\"driver_id\";i:8;s:11:\"assigned_by\";N;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-04-02 09:43:24\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-04-02 09:49:40\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:43:24\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:11:\"\0*\0original\";a:23:{s:2:\"id\";i:12;s:8:\"order_id\";i:14;s:9:\"driver_id\";i:8;s:11:\"assigned_by\";N;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-04-02 09:43:24\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-04-02 09:49:40\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:43:24\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"picked_up_at\";s:8:\"datetime\";s:12:\"delivered_at\";s:8:\"datetime\";s:17:\"delivery_latitude\";s:9:\"decimal:8\";s:18:\"delivery_longitude\";s:9:\"decimal:8\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:5:\"order\";O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:1:{i:0;s:18:\"couponUsage.coupon\";}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:49:{s:2:\"id\";i:14;s:12:\"order_number\";s:17:\"ORD-69CE053152A9F\";s:11:\"customer_id\";i:24;s:7:\"user_id\";i:24;s:18:\"assigned_driver_id\";i:25;s:11:\"assigned_at\";s:19:\"2026-04-02 09:43:24\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"963 944251800\";s:7:\"village\";s:146:\"Al-Maqwas, السويداء, ناحية مركز السويداء, منطقة مركز السويداء, محافظة السويداء, سوريا\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7118842\";s:9:\"longitude\";s:10:\"36.5763971\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";N;s:6:\"status\";s:9:\"delivered\";s:12:\"is_completed\";i:0;s:12:\"completed_at\";N;s:21:\"revenue_recognized_at\";N;s:14:\"payment_status\";s:7:\"pending\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";s:11486:\"data:image/png;base64,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\";s:9:\"signed_at\";s:19:\"2026-04-02 09:49:40\";s:25:\"driver_delivery_signature\";s:11210:\"data:image/png;base64,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\";s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:15:\"idempotency_key\";N;s:8:\"subtotal\";s:5:\"10.00\";s:5:\"total\";s:5:\"10.63\";s:13:\"delivery_cost\";s:4:\"1.63\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:4:\"1.63\";s:15:\"discount_amount\";s:4:\"1.00\";s:12:\"total_amount\";s:5:\"10.63\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:591:\"{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.711884177847196,\"lng\":36.57639712834898}}\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-04-09 08:57:05\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-04-02 08:57:05\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";}s:11:\"\0*\0original\";a:49:{s:2:\"id\";i:14;s:12:\"order_number\";s:17:\"ORD-69CE053152A9F\";s:11:\"customer_id\";i:24;s:7:\"user_id\";i:24;s:18:\"assigned_driver_id\";i:25;s:11:\"assigned_at\";s:19:\"2026-04-02 09:43:24\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"963 944251800\";s:7:\"village\";s:146:\"Al-Maqwas, السويداء, ناحية مركز السويداء, منطقة مركز السويداء, محافظة السويداء, سوريا\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7118842\";s:9:\"longitude\";s:10:\"36.5763971\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";N;s:6:\"status\";s:9:\"delivered\";s:12:\"is_completed\";i:0;s:12:\"completed_at\";N;s:21:\"revenue_recognized_at\";N;s:14:\"payment_status\";s:7:\"pending\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";s:11486:\"data:image/png;base64,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\";s:9:\"signed_at\";s:19:\"2026-04-02 09:49:40\";s:25:\"driver_delivery_signature\";s:11210:\"data:image/png;base64,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\";s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:15:\"idempotency_key\";N;s:8:\"subtotal\";s:5:\"10.00\";s:5:\"total\";s:5:\"10.63\";s:13:\"delivery_cost\";s:4:\"1.63\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:4:\"1.63\";s:15:\"discount_amount\";s:4:\"1.00\";s:12:\"total_amount\";s:5:\"10.63\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:591:\"{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.711884177847196,\"lng\":36.57639712834898}}\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-04-09 08:57:05\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-04-02 08:57:05\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:11:\"couponUsage\";N;}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:37:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:25:\"driver_delivery_signature\";i:34;s:9:\"signed_at\";i:35;s:14:\"delivery_notes\";i:36;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:6:\"driver\";O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:19:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:8:\"LIC-0035\";s:14:\"license_expiry\";s:10:\"2030-05-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:7:\"ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:11:\"\0*\0original\";a:19:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:8:\"LIC-0035\";s:14:\"license_expiry\";s:10:\"2030-05-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:7:\"ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";O:15:\"App\\Models\\User\":32:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"users\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:46:{s:2:\"id\";i:25;s:4:\"name\";s:8:\"أحمد\";s:8:\"username\";s:6:\"Ahmad1\";s:5:\"email\";s:22:\"ahmad1+1@drivers.local\";s:9:\"google_id\";N;s:10:\"birth_date\";N;s:5:\"phone\";s:10:\"0994251800\";s:17:\"email_verified_at\";N;s:8:\"password\";s:60:\"$2y$12$sK6bOytMDm9f7AZXCP51keiLxMl29BBzPRDbEd2jnKhPyI5nZ/CmK\";s:14:\"user_full_name\";N;s:6:\"mobile\";N;s:7:\"address\";N;s:13:\"profile_photo\";N;s:8:\"language\";s:7:\"english\";s:6:\"gender\";N;s:8:\"currency\";N;s:7:\"balance\";s:4:\"0.00\";s:8:\"verified\";i:1;s:9:\"is_trader\";i:0;s:8:\"is_admin\";i:0;s:11:\"is_it_super\";i:0;s:5:\"is_it\";i:0;s:11:\"is_cs_agent\";i:0;s:13:\"is_accountant\";i:0;s:16:\"is_cs_supervisor\";i:0;s:5:\"notes\";N;s:4:\"tags\";N;s:21:\"newsletter_subscribed\";i:0;s:14:\"lifetime_value\";s:4:\"0.00\";s:14:\"remember_token\";N;s:9:\"locked_at\";N;s:12:\"locked_until\";N;s:11:\"lock_reason\";N;s:14:\"login_failures\";i:0;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:08\";s:20:\"is_driver_supervisor\";i:0;s:7:\"role_id\";N;s:5:\"is_hr\";i:0;s:5:\"is_cs\";i:0;s:10:\"is_finance\";i:0;s:13:\"is_hr_manager\";i:0;s:6:\"status\";s:6:\"active\";s:11:\"is_verified\";i:0;s:17:\"verification_code\";N;s:10:\"otp_expiry\";N;}s:11:\"\0*\0original\";a:46:{s:2:\"id\";i:25;s:4:\"name\";s:8:\"أحمد\";s:8:\"username\";s:6:\"Ahmad1\";s:5:\"email\";s:22:\"ahmad1+1@drivers.local\";s:9:\"google_id\";N;s:10:\"birth_date\";N;s:5:\"phone\";s:10:\"0994251800\";s:17:\"email_verified_at\";N;s:8:\"password\";s:60:\"$2y$12$sK6bOytMDm9f7AZXCP51keiLxMl29BBzPRDbEd2jnKhPyI5nZ/CmK\";s:14:\"user_full_name\";N;s:6:\"mobile\";N;s:7:\"address\";N;s:13:\"profile_photo\";N;s:8:\"language\";s:7:\"english\";s:6:\"gender\";N;s:8:\"currency\";N;s:7:\"balance\";s:4:\"0.00\";s:8:\"verified\";i:1;s:9:\"is_trader\";i:0;s:8:\"is_admin\";i:0;s:11:\"is_it_super\";i:0;s:5:\"is_it\";i:0;s:11:\"is_cs_agent\";i:0;s:13:\"is_accountant\";i:0;s:16:\"is_cs_supervisor\";i:0;s:5:\"notes\";N;s:4:\"tags\";N;s:21:\"newsletter_subscribed\";i:0;s:14:\"lifetime_value\";s:4:\"0.00\";s:14:\"remember_token\";N;s:9:\"locked_at\";N;s:12:\"locked_until\";N;s:11:\"lock_reason\";N;s:14:\"login_failures\";i:0;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:08\";s:20:\"is_driver_supervisor\";i:0;s:7:\"role_id\";N;s:5:\"is_hr\";i:0;s:5:\"is_cs\";i:0;s:10:\"is_finance\";i:0;s:13:\"is_hr_manager\";i:0;s:6:\"status\";s:6:\"active\";s:11:\"is_verified\";i:0;s:17:\"verification_code\";N;s:10:\"otp_expiry\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:2:{i:0;s:8:\"password\";i:1;s:14:\"remember_token\";}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:30:{i:0;s:4:\"name\";i:1;s:8:\"username\";i:2;s:5:\"email\";i:3;s:8:\"password\";i:4;s:14:\"user_full_name\";i:5;s:6:\"mobile\";i:6;s:5:\"phone\";i:7;s:10:\"birth_date\";i:8;s:7:\"address\";i:9;s:13:\"profile_photo\";i:10;s:8:\"language\";i:11;s:6:\"gender\";i:12;s:8:\"currency\";i:13;s:8:\"verified\";i:14;s:9:\"google_id\";i:15;s:9:\"is_trader\";i:16;s:8:\"is_admin\";i:17;s:11:\"is_it_super\";i:18;s:5:\"is_it\";i:19;s:5:\"is_hr\";i:20;s:5:\"is_cs\";i:21;s:10:\"is_finance\";i:22;s:7:\"country\";i:23;s:13:\"is_accountant\";i:24;s:20:\"is_driver_supervisor\";i:25;s:7:\"role_id\";i:26;s:9:\"locked_at\";i:27;s:12:\"locked_until\";i:28;s:11:\"lock_reason\";i:29;s:14:\"login_failures\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:20:\"\0*\0rememberTokenName\";s:14:\"remember_token\";s:14:\"\0*\0accessToken\";N;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:9:\"driver_id\";i:1;s:8:\"order_id\";i:2;s:6:\"status\";i:3;s:11:\"assigned_at\";i:4;s:11:\"assigned_by\";i:5;s:12:\"picked_up_at\";i:6;s:12:\"delivered_at\";i:7;s:17:\"delivery_latitude\";i:8;s:18:\"delivery_longitude\";i:9;s:5:\"notes\";i:10;s:18:\"customer_signature\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:13:\"active_routes\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:24:\"App\\Models\\DeliveryRoute\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"delivery_routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:7;s:9:\"driver_id\";i:8;s:10:\"route_date\";s:10:\"2026-04-02\";s:9:\"waypoints\";s:664:\"[{\"order_id\":14,\"address\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.711884177847196,\"lng\":36.57639712834898}},\"coordinates\":{\"lat\":40.6482,\"lng\":-73.9936}}]\";s:18:\"optimized_sequence\";s:2:\"[]\";s:14:\"total_distance\";N;s:18:\"estimated_duration\";N;s:6:\"status\";s:6:\"active\";s:10:\"started_at\";N;s:12:\"completed_at\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:43:24\";s:10:\"updated_at\";s:19:\"2026-04-02 09:43:24\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:7;s:9:\"driver_id\";i:8;s:10:\"route_date\";s:10:\"2026-04-02\";s:9:\"waypoints\";s:664:\"[{\"order_id\":14,\"address\":{\"recipient_name\":\"\\u064a\\u0648\\u0633\\u0641 \\u0627\\u0644\\u062d\\u0644\\u0628\\u064a\",\"phone\":\"963 944251800\",\"village\":\"Al-Maqwas, \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0646\\u0627\\u062d\\u064a\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u0646\\u0637\\u0642\\u0629 \\u0645\\u0631\\u0643\\u0632 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0645\\u062d\\u0627\\u0641\\u0638\\u0629 \\u0627\\u0644\\u0633\\u0648\\u064a\\u062f\\u0627\\u0621, \\u0633\\u0648\\u0631\\u064a\\u0627\",\"address_note\":null,\"location\":{\"lat\":32.711884177847196,\"lng\":36.57639712834898}},\"coordinates\":{\"lat\":40.6482,\"lng\":-73.9936}}]\";s:18:\"optimized_sequence\";s:2:\"[]\";s:14:\"total_distance\";N;s:18:\"estimated_duration\";N;s:6:\"status\";s:6:\"active\";s:10:\"started_at\";N;s:12:\"completed_at\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:43:24\";s:10:\"updated_at\";s:19:\"2026-04-02 09:43:24\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:10:\"route_date\";s:4:\"date\";s:9:\"waypoints\";s:5:\"array\";s:18:\"optimized_sequence\";s:5:\"array\";s:14:\"total_distance\";s:9:\"decimal:2\";s:18:\"estimated_duration\";s:7:\"integer\";s:10:\"started_at\";s:8:\"datetime\";s:12:\"completed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"driver\";O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:19:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:8:\"LIC-0035\";s:14:\"license_expiry\";s:10:\"2030-05-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:7:\"ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:11:\"\0*\0original\";a:19:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:8:\"LIC-0035\";s:14:\"license_expiry\";s:10:\"2030-05-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:7:\"ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:20:08\";s:10:\"updated_at\";s:19:\"2026-04-02 09:49:40\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:9:\"driver_id\";i:1;s:10:\"route_date\";i:2;s:9:\"waypoints\";i:3;s:18:\"optimized_sequence\";i:4;s:14:\"total_distance\";i:5;s:18:\"estimated_duration\";i:6;s:6:\"status\";i:7;s:10:\"started_at\";i:8;s:12:\"completed_at\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"drivers_sample\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:20:\"last_location_update\";N;s:4:\"name\";s:8:\"أحمد\";s:5:\"phone\";s:10:\"0994251800\";s:16:\"current_latitude\";N;s:17:\"current_longitude\";N;s:24:\"active_assignments_count\";i:0;}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:8;s:7:\"user_id\";i:25;s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:20:\"last_location_update\";N;s:4:\"name\";s:8:\"أحمد\";s:5:\"phone\";s:10:\"0994251800\";s:16:\"current_latitude\";N;s:17:\"current_longitude\";N;s:24:\"active_assignments_count\";i:0;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:24:\"unassigned_orders_sample\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:1:{i:0;s:18:\"couponUsage.coupon\";}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:5:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-02 10:43:35\";}s:11:\"\0*\0original\";a:5:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-02 10:43:35\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:11:\"couponUsage\";O:22:\"App\\Models\\CouponUsage\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"coupon_usage\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:3;s:9:\"coupon_id\";i:4;s:7:\"user_id\";i:24;s:8:\"order_id\";i:16;s:15:\"discount_amount\";s:4:\"4.00\";s:11:\"order_total\";s:5:\"17.56\";s:7:\"used_at\";s:19:\"2026-04-02 10:43:35\";s:10:\"created_at\";s:19:\"2026-04-02 10:43:35\";s:10:\"updated_at\";s:19:\"2026-04-02 10:43:35\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:3;s:9:\"coupon_id\";i:4;s:7:\"user_id\";i:24;s:8:\"order_id\";i:16;s:15:\"discount_amount\";s:4:\"4.00\";s:11:\"order_total\";s:5:\"17.56\";s:7:\"used_at\";s:19:\"2026-04-02 10:43:35\";s:10:\"created_at\";s:19:\"2026-04-02 10:43:35\";s:10:\"updated_at\";s:19:\"2026-04-02 10:43:35\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:15:\"discount_amount\";s:9:\"decimal:2\";s:11:\"order_total\";s:9:\"decimal:2\";s:7:\"used_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"coupon\";O:25:\"App\\Models\\DiscountCoupon\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:16:\"discount_coupons\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:4;s:4:\"code\";s:7:\"TEST123\";s:19:\"discount_percentage\";s:5:\"20.00\";s:7:\"purpose\";s:10:\"تجربة\";s:8:\"max_uses\";i:1;s:10:\"used_count\";i:1;s:10:\"expires_at\";N;s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"created_at\";s:19:\"2026-04-02 10:42:26\";s:10:\"updated_at\";s:19:\"2026-04-02 10:43:35\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:4;s:4:\"code\";s:7:\"TEST123\";s:19:\"discount_percentage\";s:5:\"20.00\";s:7:\"purpose\";s:10:\"تجربة\";s:8:\"max_uses\";i:1;s:10:\"used_count\";i:1;s:10:\"expires_at\";N;s:9:\"is_active\";i:1;s:10:\"created_by\";i:1;s:10:\"created_at\";s:19:\"2026-04-02 10:42:26\";s:10:\"updated_at\";s:19:\"2026-04-02 10:43:35\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:19:\"discount_percentage\";s:9:\"decimal:2\";s:10:\"expires_at\";s:8:\"datetime\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:8:{i:0;s:4:\"code\";i:1;s:19:\"discount_percentage\";i:2;s:7:\"purpose\";i:3;s:8:\"max_uses\";i:4;s:10:\"used_count\";i:5;s:10:\"expires_at\";i:6;s:9:\"is_active\";i:7;s:10:\"created_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:9:\"coupon_id\";i:1;s:7:\"user_id\";i:2;s:8:\"order_id\";i:3;s:15:\"discount_amount\";i:4;s:11:\"order_total\";i:5;s:7:\"used_at\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:37:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:25:\"driver_delivery_signature\";i:34;s:9:\"signed_at\";i:35;s:14:\"delivery_notes\";i:36;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:1:{i:0;s:18:\"couponUsage.coupon\";}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:5:{s:2:\"id\";i:15;s:12:\"order_number\";s:17:\"ORD-69CE08FC8AF15\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:13:16\";}s:11:\"\0*\0original\";a:5:{s:2:\"id\";i:15;s:12:\"order_number\";s:17:\"ORD-69CE08FC8AF15\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-02 09:13:16\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:11:\"couponUsage\";N;}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:37:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:25:\"driver_delivery_signature\";i:34;s:9:\"signed_at\";i:35;s:14:\"delivery_notes\";i:36;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:1:{i:0;s:18:\"couponUsage.coupon\";}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:5:{s:2:\"id\";i:13;s:12:\"order_number\";s:17:\"ORD-69CCED98D0F8D\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-01 13:04:09\";}s:11:\"\0*\0original\";a:5:{s:2:\"id\";i:13;s:12:\"order_number\";s:17:\"ORD-69CCED98D0F8D\";s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:12:\"address_note\";N;s:10:\"created_at\";s:19:\"2026-04-01 13:04:09\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:11:\"couponUsage\";N;}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:37:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:25:\"driver_delivery_signature\";i:34;s:9:\"signed_at\";i:35;s:14:\"delivery_notes\";i:36;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}', 1775115902);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tulip-store-cache-test.dashboard_events.admin', 'a:3:{i:0;a:3:{s:9:\"dashboard\";s:5:\"admin\";s:7:\"payload\";a:3:{s:4:\"type\";s:13:\"order_created\";s:8:\"order_id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";}s:9:\"timestamp\";s:27:\"2026-04-02T07:43:36.118692Z\";}i:1;a:3:{s:9:\"dashboard\";s:5:\"admin\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.234502Z\";}i:2;a:3:{s:9:\"dashboard\";s:5:\"admin\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.391487Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.cs', 'a:2:{i:0;a:3:{s:9:\"dashboard\";s:2:\"cs\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.273572Z\";}i:1;a:3:{s:9:\"dashboard\";s:2:\"cs\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.406000Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.finance', 'a:3:{i:0;a:3:{s:9:\"dashboard\";s:7:\"finance\";s:7:\"payload\";a:3:{s:4:\"type\";s:13:\"order_created\";s:8:\"order_id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";}s:9:\"timestamp\";s:27:\"2026-04-02T07:43:36.194701Z\";}i:1;a:3:{s:9:\"dashboard\";s:7:\"finance\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.268372Z\";}i:2;a:3:{s:9:\"dashboard\";s:7:\"finance\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.401823Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.hr', 'a:2:{i:0;a:3:{s:9:\"dashboard\";s:2:\"hr\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.279828Z\";}i:1;a:3:{s:9:\"dashboard\";s:2:\"hr\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.409407Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.it', 'a:2:{i:0;a:3:{s:9:\"dashboard\";s:2:\"it\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.261830Z\";}i:1;a:3:{s:9:\"dashboard\";s:2:\"it\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.397253Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.supervisor', 'a:2:{i:0;a:3:{s:9:\"dashboard\";s:10:\"supervisor\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.284904Z\";}i:1;a:3:{s:9:\"dashboard\";s:10:\"supervisor\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.414013Z\";}}', 1775116755),
('tulip-store-cache-test.dashboard_events.vendor', 'a:2:{i:0;a:3:{s:9:\"dashboard\";s:6:\"vendor\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:9:\"confirmed\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:7:\"pending\";s:2:\"to\";s:9:\"confirmed\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.291798Z\";}i:1;a:3:{s:9:\"dashboard\";s:6:\"vendor\";s:7:\"payload\";a:5:{s:4:\"type\";s:20:\"order_status_changed\";s:5:\"order\";a:6:{s:2:\"id\";i:16;s:12:\"order_number\";s:17:\"ORD-69CE1E26E5DC2\";s:6:\"status\";s:16:\"out_for_delivery\";s:14:\"payment_status\";s:7:\"pending\";s:5:\"total\";s:5:\"17.56\";s:10:\"updated_at\";s:25:\"2026-04-02T10:44:15+03:00\";}s:4:\"from\";s:9:\"confirmed\";s:2:\"to\";s:16:\"out_for_delivery\";s:17:\"actor_employee_id\";i:1;}s:9:\"timestamp\";s:27:\"2026-04-02T07:44:15.419356Z\";}}', 1775116755);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'active', '2026-02-05 03:45:13', '2026-02-05 03:45:13'),
(2, NULL, NULL, 'active', '2026-02-22 05:56:07', '2026-02-22 05:56:07'),
(3, NULL, NULL, 'active', '2026-04-01 10:02:29', '2026-04-01 10:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `product_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_snapshot`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flow_records`
--

CREATE TABLE `cash_flow_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_date` date NOT NULL,
  `flow_type` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `market` varchar(20) NOT NULL DEFAULT 'store',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(50) DEFAULT 'store'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_attribute_definitions`
--

CREATE TABLE `category_attribute_definitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `type` varchar(30) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_code` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `account_subtype` enum('current','non_current','operating','non_operating') DEFAULT NULL,
  `parent_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commission_rates`
--

CREATE TABLE `commission_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(5,4) NOT NULL,
  `minimum_amount` decimal(10,2) DEFAULT NULL,
  `category_rates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_rates`)),
  `effective_from` date NOT NULL,
  `effective_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compliance_documents`
--

CREATE TABLE `compliance_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doc_type` varchar(255) NOT NULL,
  `period` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) NOT NULL,
  `filed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `filed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso2` varchar(2) NOT NULL,
  `dial_code` varchar(8) NOT NULL,
  `flag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso2`, `dial_code`, `flag`) VALUES
(1, 'United States', 'us', '1', NULL),
(2, 'United Kingdom', 'gb', '44', NULL),
(3, 'Canada', 'ca', '1', NULL),
(4, 'Australia', 'au', '61', NULL),
(5, 'Germany', 'de', '49', NULL),
(6, 'France', 'fr', '33', NULL),
(7, 'Italy', 'it', '39', NULL),
(8, 'Spain', 'es', '34', NULL),
(9, 'Netherlands', 'nl', '31', NULL),
(10, 'Sweden', 'se', '46', NULL),
(11, 'Norway', 'no', '47', NULL),
(12, 'Denmark', 'dk', '45', NULL),
(13, 'Finland', 'fi', '358', NULL),
(14, 'Ireland', 'ie', '353', NULL),
(15, 'United Arab Emirates', 'ae', '971', NULL),
(16, 'Saudi Arabia', 'sa', '966', NULL),
(17, 'Qatar', 'qa', '974', NULL),
(18, 'Kuwait', 'kw', '965', NULL),
(19, 'Bahrain', 'bh', '973', NULL),
(20, 'Oman', 'om', '968', NULL),
(21, 'Jordan', 'jo', '962', NULL),
(22, 'Lebanon', 'lb', '961', NULL),
(23, 'Syria', 'sy', '963', NULL),
(24, 'Egypt', 'eg', '20', NULL),
(25, 'India', 'in', '91', NULL),
(26, 'Pakistan', 'pk', '92', NULL),
(27, 'China', 'cn', '86', NULL),
(28, 'Japan', 'jp', '81', NULL),
(29, 'South Korea', 'kr', '82', NULL),
(30, 'Brazil', 'br', '55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('percentage','fixed','free_shipping') NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `max_usage` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `applicable_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_products`)),
  `applicable_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_categories`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_purchase`, `max_usage`, `used_count`, `expires_at`, `is_active`, `description`, `applicable_products`, `applicable_categories`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME10', 'percentage', 10.00, 50.00, 100, 15, '2026-06-09', 1, 'خصم 10% للعملاء الجدد - الحد الأدنى للشراء 50$', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(2, 'SAVE20', 'fixed', 20.00, 100.00, 50, 8, '2026-05-09', 1, 'خصم 20$ على الطلبات فوق 100$', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(3, 'FREESHIP', 'free_shipping', 0.00, 75.00, 200, 45, '2026-04-09', 1, 'شحن مجاني للطلبات فوق 75$', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(4, 'SUMMER25', 'percentage', 25.00, 150.00, 30, 12, '2026-04-08', 1, 'عرض الصيف - خصم 25% على الطلبات فوق 150$', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(5, 'VIP50', 'fixed', 50.00, 200.00, 10, 3, '2026-09-09', 1, 'كوبون VIP - خصم 50$ للعملاء المميزين', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(6, 'EXPIRED', 'percentage', 15.00, 50.00, 100, 100, '2026-02-27', 0, 'كوبون منتهي الصلاحية', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

CREATE TABLE `coupon_usage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_usage`
--

INSERT INTO `coupon_usage` (`id`, `coupon_id`, `user_id`, `order_id`, `discount_amount`, `order_total`, `used_at`, `created_at`, `updated_at`) VALUES
(3, 4, NULL, NULL, 4.00, 17.56, '2026-04-02 07:43:35', '2026-04-02 07:43:35', '2026-04-02 07:43:35');

-- --------------------------------------------------------

--
-- Table structure for table `customer_balance_audits`
--

CREATE TABLE `customer_balance_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(32) NOT NULL,
  `support_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_feedback`
--

CREATE TABLE `customer_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('complaint','suggestion','compliment','inquiry') NOT NULL DEFAULT 'inquiry',
  `rating` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_gifts`
--

CREATE TABLE `custom_gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `gift_box_id` bigint(20) UNSIGNED NOT NULL,
  `gift_wrapping_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gift_ribbon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gift_card_id` bigint(20) UNSIGNED DEFAULT NULL,
  `card_message` text DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('draft','completed','in_cart','ordered') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_gift_items`
--

CREATE TABLE `custom_gift_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `custom_gift_id` bigint(20) UNSIGNED NOT NULL,
  `gift_filler_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_analytics`
--

CREATE TABLE `daily_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `analytics_date` date NOT NULL,
  `metric_type` varchar(255) NOT NULL,
  `dimension` varchar(255) DEFAULT NULL,
  `dimension_value` varchar(255) DEFAULT NULL,
  `metric_value` decimal(15,2) NOT NULL,
  `additional_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_data`)),
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_cache`
--

CREATE TABLE `dashboard_cache` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_type` varchar(255) NOT NULL,
  `cache_key` varchar(255) NOT NULL,
  `cache_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cache_data`)),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_notifications`
--

CREATE TABLE `dashboard_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_type` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT 'blue',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dashboard_notifications`
--

INSERT INTO `dashboard_notifications` (`id`, `dashboard_type`, `user_type`, `user_id`, `type`, `title`, `message`, `action_url`, `icon`, `color`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"yousef F alhalabi\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-09 06:34:41', '2026-03-09 06:34:41'),
(2, 'cs', 'App\\Models\\Employee', 1, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"Test\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 1, NULL, '2026-03-11 02:26:03', '2026-03-15 07:33:59'),
(3, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"Test\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-11 02:26:03', '2026-03-11 02:26:03'),
(4, 'cs', 'App\\Models\\Employee', 1, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"Testtt\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-21 12:24:46', '2026-03-21 12:24:46'),
(5, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"Testtt\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-21 12:24:46', '2026-03-21 12:24:46'),
(6, 'cs', 'App\\Models\\User', 13, 'success', 'تمت الموافقة على منتجك', 'تمت الموافقة على المنتج: Testtt', 'http://127.0.0.1:8000/dashboard/vendor/products', NULL, 'blue', 0, NULL, '2026-03-21 12:27:07', '2026-03-21 12:27:07'),
(7, 'cs', 'App\\Models\\Employee', 1, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"HI\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-22 09:26:26', '2026-03-22 09:26:26'),
(8, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"HI\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-22 09:26:26', '2026-03-22 09:26:26'),
(9, 'cs', 'App\\Models\\User', 13, 'success', 'تمت الموافقة على منتجك', 'تمت الموافقة على المنتج: HI', 'http://127.0.0.1:8000/dashboard/vendor/products', NULL, 'blue', 0, NULL, '2026-03-22 09:27:33', '2026-03-22 09:27:33'),
(10, 'cs', 'App\\Models\\Employee', 1, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"HElllo\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-22 10:05:13', '2026-03-22 10:05:13'),
(11, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"HElllo\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-22 10:05:13', '2026-03-22 10:05:13'),
(12, 'cs', 'App\\Models\\User', 13, 'success', 'تمت الموافقة على منتجك', 'تمت الموافقة على المنتج: HElllo', 'http://127.0.0.1:8000/dashboard/vendor/products', NULL, 'blue', 0, NULL, '2026-03-22 10:05:49', '2026-03-22 10:05:49'),
(13, 'cs', 'App\\Models\\Employee', 1, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"فستان سهرة أنيق\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-31 12:25:27', '2026-03-31 12:25:27'),
(14, 'cs', 'App\\Models\\User', 22, 'success', 'ط·ع¾ط¸â€¦ط·ع¾ ط·آ§ط¸â€‍ط¸â€¦ط¸ث†ط·آ§ط¸ظ¾ط¸â€ڑط·آ© ط·آ¹ط¸â€‍ط¸â€° ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط¸ئ’', 'ط·ع¾ط¸â€¦ط·ع¾ ط·آ§ط¸â€‍ط¸â€¦ط¸ث†ط·آ§ط¸ظ¾ط¸â€ڑط·آ© ط·آ¹ط¸â€‍ط¸â€° ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬: فستان سهرة أنيق', 'http://127.0.0.1:8000/dashboard/vendor/products', NULL, 'blue', 0, NULL, '2026-03-31 12:25:36', '2026-03-31 12:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_quick_actions`
--

CREATE TABLE `dashboard_quick_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_type` varchar(255) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `affected_records` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL,
  `error_message` text DEFAULT NULL,
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parameters`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_role_permissions`
--

CREATE TABLE `dashboard_role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_key` varchar(50) NOT NULL,
  `dashboard_key` varchar(50) NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 1,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sections`)),
  `actions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`actions`)),
  `can_view_sensitive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `database_backups`
--

CREATE TABLE `database_backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `backup_name` varchar(255) NOT NULL,
  `database_name` varchar(255) NOT NULL,
  `type` enum('full','incremental','differential') NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `checksum` varchar(255) DEFAULT NULL,
  `status` enum('in_progress','completed','failed','corrupted') NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_assignments`
--

CREATE TABLE `delivery_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('assigned','accepted','rejected','picked_up','in_transit','delivered','failed','cancelled') NOT NULL DEFAULT 'assigned',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accepted_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `driver_notes` text DEFAULT NULL,
  `delivery_proof` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`delivery_proof`)),
  `delivery_fee` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pickup_latitude` decimal(10,7) DEFAULT NULL,
  `pickup_longitude` decimal(10,7) DEFAULT NULL,
  `delivery_latitude` decimal(10,7) DEFAULT NULL,
  `delivery_longitude` decimal(10,7) DEFAULT NULL,
  `distance_km` decimal(8,2) DEFAULT NULL,
  `estimated_time_minutes` int(11) DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `customer_signature` varchar(255) DEFAULT NULL,
  `failure_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_attempts`
--

CREATE TABLE `delivery_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `attempt_number` int(11) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'failed',
  `reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_proofs`
--

CREATE TABLE `delivery_proofs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `proof_type` varchar(255) NOT NULL DEFAULT 'photo',
  `file_url` varchar(255) NOT NULL,
  `captured_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_ratings`
--

CREATE TABLE `delivery_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_routes`
--

CREATE TABLE `delivery_routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `route_date` date NOT NULL,
  `waypoints` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`waypoints`)),
  `optimized_sequence` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`optimized_sequence`)),
  `total_distance` decimal(10,2) DEFAULT NULL,
  `estimated_duration` int(11) DEFAULT NULL,
  `status` enum('planned','active','completed','cancelled') NOT NULL DEFAULT 'planned',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zones`
--

CREATE TABLE `delivery_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zone_name` varchar(255) NOT NULL,
  `zone_coordinates` text NOT NULL,
  `base_delivery_fee` decimal(8,2) NOT NULL,
  `estimated_time_minutes` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zone_analytics`
--

CREATE TABLE `delivery_zone_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zone_name` varchar(255) NOT NULL,
  `analytics_date` date NOT NULL,
  `total_deliveries` int(11) NOT NULL,
  `completed_deliveries` int(11) NOT NULL,
  `failed_deliveries` int(11) NOT NULL,
  `average_delivery_time_minutes` decimal(8,2) NOT NULL,
  `average_delivery_cost` decimal(10,2) NOT NULL,
  `customer_satisfaction_score` decimal(3,2) DEFAULT NULL,
  `peak_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`peak_hours`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deployment_history`
--

CREATE TABLE `deployment_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `environment` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `deployed_by` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changes`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deployment_logs`
--

CREATE TABLE `deployment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `environment` varchar(255) NOT NULL,
  `status` enum('pending','in_progress','completed','failed','rolled_back') NOT NULL,
  `deployed_by` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`changes`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conditions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupons`
--

CREATE TABLE `discount_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `purpose` text DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_coupons`
--

INSERT INTO `discount_coupons` (`id`, `code`, `type`, `value`, `min_order_amount`, `max_discount_amount`, `usage_limit`, `usage_count`, `valid_from`, `valid_until`, `user_id`, `discount_percentage`, `purpose`, `max_uses`, `used_count`, `expires_at`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'TEST123', 'percentage', 0.00, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 20.00, 'تجربة', 1, 1, NULL, 1, 1, '2026-04-02 07:42:26', '2026-04-02 07:43:35');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `license_number` varchar(255) NOT NULL,
  `license_expiry` date NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `vehicle_plate` varchar(255) NOT NULL,
  `vehicle_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vehicle_info`)),
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `availability` enum('available','busy','offline','on_break') NOT NULL DEFAULT 'offline',
  `rating` decimal(3,2) NOT NULL DEFAULT 5.00,
  `total_deliveries` int(11) NOT NULL DEFAULT 0,
  `working_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`working_hours`)),
  `last_location` point DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_location_update` timestamp NULL DEFAULT NULL,
  `current_speed` decimal(8,2) DEFAULT NULL,
  `current_heading` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_locations`
--

CREATE TABLE `driver_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `accuracy` decimal(8,2) DEFAULT NULL,
  `speed` decimal(8,2) DEFAULT NULL,
  `heading` decimal(8,2) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_performance_scores`
--

CREATE TABLE `driver_performance_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `total_deliveries` int(11) NOT NULL,
  `on_time_deliveries` int(11) NOT NULL,
  `on_time_rate` decimal(5,2) NOT NULL,
  `average_delivery_time_minutes` decimal(8,2) NOT NULL,
  `customer_rating` decimal(3,2) NOT NULL,
  `accidents` int(11) NOT NULL DEFAULT 0,
  `violations` int(11) NOT NULL DEFAULT 0,
  `overall_score` decimal(5,2) NOT NULL,
  `performance_grade` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `attempts` int(11) NOT NULL DEFAULT 0,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `verification_code`, `token`, `expires_at`, `used`, `created_at`, `updated_at`) VALUES
(1, 'yousefalhalabi6863@gmail.com', '742761', 'q3bwRLj9vEfUud3Zof4QfgtwCzFwFxs9lgoaymUwheVommBTDurDWZrdl9LjQ8pb', '2026-03-21 12:37:21', 0, '2026-03-21 12:22:21', '2026-03-21 12:22:21'),
(2, 'yousefalhalabi53@gmail.com', '282023', '6fSMaZH5XS8umb1Z5JjwTjsIVLoShauWWPC0QplwLVUPdzboW8YkBgQuCaTD9Gn4', '2026-03-31 09:34:26', 0, '2026-03-31 09:19:26', '2026-03-31 09:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `work_location` varchar(255) DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hourly_rate` decimal(8,2) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `termination_date` date DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') NOT NULL,
  `work_schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`work_schedule`)),
  `status` enum('active','inactive','terminated','on_leave') NOT NULL DEFAULT 'active',
  `security_level` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  `emergency_contact` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`emergency_contact`)),
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_code` varchar(255) DEFAULT NULL,
  `employee_id_card` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `ip_restrictions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ip_restrictions`)),
  `performance_score` decimal(3,2) DEFAULT NULL,
  `last_review_date` date DEFAULT NULL,
  `next_review_date` date DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Saudi Arabia',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_it` tinyint(1) NOT NULL DEFAULT 0,
  `is_hr` tinyint(1) NOT NULL DEFAULT 0,
  `is_cs` tinyint(1) NOT NULL DEFAULT 0,
  `is_finance` tinyint(1) NOT NULL DEFAULT 0,
  `is_driver_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `is_trader` tinyint(1) NOT NULL DEFAULT 0,
  `is_manager` tinyint(1) NOT NULL DEFAULT 0,
  `is_team_lead` tinyint(1) NOT NULL DEFAULT 0,
  `can_approve_expenses` tinyint(1) NOT NULL DEFAULT 0,
  `can_manage_inventory` tinyint(1) NOT NULL DEFAULT 0,
  `contract_end_date` date DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `approval_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relation` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `qualifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`qualifications`)),
  `certifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certifications`)),
  `languages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages`)),
  `preferred_communication` enum('email','phone','whatsapp','teams') NOT NULL DEFAULT 'email',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_id`, `department`, `work_location`, `position`, `manager_id`, `hourly_rate`, `monthly_salary`, `hire_date`, `termination_date`, `employment_type`, `work_schedule`, `status`, `security_level`, `emergency_contact`, `documents`, `created_at`, `updated_at`, `employee_code`, `employee_id_card`, `first_name`, `last_name`, `email`, `profile_photo`, `bio`, `password`, `email_verified_at`, `remember_token`, `last_login_at`, `login_count`, `two_factor_enabled`, `ip_restrictions`, `performance_score`, `last_review_date`, `next_review_date`, `phone`, `national_id`, `date_of_birth`, `gender`, `marital_status`, `address`, `city`, `country`, `is_admin`, `is_it`, `is_hr`, `is_cs`, `is_finance`, `is_driver_supervisor`, `is_trader`, `is_manager`, `is_team_lead`, `can_approve_expenses`, `can_manage_inventory`, `contract_end_date`, `salary`, `approval_limit`, `commission_rate`, `bank_name`, `bank_account`, `iban`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `notes`, `skills`, `qualifications`, `certifications`, `languages`, `preferred_communication`, `deleted_at`) VALUES
(1, NULL, 'EMP001', 'Administration', NULL, 'Super Admin', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-01-06 04:15:19', '2026-03-21 09:15:47', 'EMP001', NULL, 'Admin', 'User', 'admin@tulipstore.com', NULL, NULL, '$2y$12$d7Vlh1WPQk7pk//l6iJVOekrpbxpmO/RtHX3i9E0GLXAnbCRe5NJW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567890', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(9, NULL, 'EMP-J6CO18N92S', 'Delivery', NULL, 'Driver', NULL, NULL, NULL, '2026-04-02', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-04-02 06:20:09', '2026-04-02 06:49:09', NULL, NULL, 'أحمد', '—', 'ahmad1@drivers.local', NULL, NULL, '$2y$12$ozOGAXJ0vSN3y.26Rus7uefuivp.ZU8AEorB2JT46qHuHpcOm.ZJG', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '0994251800', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `break_minutes` int(11) NOT NULL DEFAULT 0,
  `total_hours` int(11) DEFAULT NULL,
  `status` enum('present','absent','late','half_day','holiday','sick_leave') NOT NULL,
  `notes` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_dashboard_overrides`
--

CREATE TABLE `employee_dashboard_overrides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_key` varchar(50) NOT NULL,
  `is_override` tinyint(1) NOT NULL DEFAULT 0,
  `can_view` tinyint(1) DEFAULT NULL,
  `can_edit` tinyint(1) DEFAULT NULL,
  `sections` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sections`)),
  `actions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`actions`)),
  `can_view_sensitive` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_dashboard_overrides`
--

INSERT INTO `employee_dashboard_overrides` (`id`, `employee_id`, `dashboard_key`, `is_override`, `can_view`, `can_edit`, `sections`, `actions`, `can_view_sensitive`, `created_at`, `updated_at`) VALUES
(1, 1, 'it', 1, 1, 1, '[]', '[]', 0, '2026-03-21 17:59:59', '2026-03-21 18:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `employee_dashboard_permissions`
--

CREATE TABLE `employee_dashboard_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_key` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_dashboard_permissions`
--

INSERT INTO `employee_dashboard_permissions` (`id`, `employee_id`, `dashboard_key`, `created_at`, `updated_at`) VALUES
(98, 1, 'it', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(99, 1, 'admin', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(100, 1, 'mart', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(101, 1, 'cs', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(102, 1, 'hr', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(103, 1, 'finance', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(104, 1, 'supervisor', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(105, 1, 'driver', '2026-03-21 12:18:56', '2026-03-21 12:18:56'),
(106, 1, 'vendor', '2026-03-21 12:18:56', '2026-03-21 12:18:56');

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_engagement_surveys`
--

CREATE TABLE `employee_engagement_surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `survey_period` varchar(255) NOT NULL,
  `job_satisfaction` int(11) DEFAULT NULL,
  `work_life_balance` int(11) DEFAULT NULL,
  `management_rating` int(11) DEFAULT NULL,
  `team_collaboration` int(11) DEFAULT NULL,
  `career_growth` int(11) DEFAULT NULL,
  `overall_score` decimal(3,2) NOT NULL,
  `comments` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_notes`
--

CREATE TABLE `employee_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_skill`
--

CREATE TABLE `employee_skill` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_training_records`
--

CREATE TABLE `employee_training_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `training_name` varchar(255) NOT NULL,
  `training_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `certificate_number` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `incurred_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_forecasts`
--

CREATE TABLE `financial_forecasts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `forecast_type` varchar(255) NOT NULL,
  `period_type` varchar(255) NOT NULL,
  `period` varchar(255) NOT NULL,
  `forecasted_amount` decimal(12,2) NOT NULL,
  `confidence_level` decimal(5,2) NOT NULL,
  `assumptions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assumptions`)),
  `method` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_reconciliations`
--

CREATE TABLE `financial_reconciliations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reconciliation_date` date NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_identifier` varchar(255) NOT NULL,
  `system_balance` decimal(15,2) NOT NULL,
  `external_balance` decimal(15,2) NOT NULL,
  `difference` decimal(15,2) NOT NULL,
  `status` enum('pending','reconciled','discrepancy','investigating') NOT NULL,
  `discrepancy_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`discrepancy_details`)),
  `reconciled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_type` enum('balance_sheet','income_statement','cash_flow','trial_balance','general_ledger') NOT NULL,
  `report_date` date NOT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `report_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`report_data`)),
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_transactions`
--

CREATE TABLE `financial_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('payment','order_payment','commission','payout','refund','fee','adjustment','payroll','salary_payment','expense') DEFAULT NULL,
  `status` enum('pending','pending_approval','approved','rejected','processing','completed','failed','cancelled') DEFAULT 'completed',
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `description` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `hash` varchar(255) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `locked_at` timestamp NULL DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `balance_before` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reference` varchar(255) DEFAULT NULL,
  `is_immutable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_periods`
--

CREATE TABLE `fiscal_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `period_type` enum('month','quarter','year') NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE `gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(255) NOT NULL,
  `occasion` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `size` varchar(255) DEFAULT NULL,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `customization_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`customization_options`)),
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `delivery_time` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_boxes`
--

CREATE TABLE `gift_boxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `size` enum('small','medium','large','xl') NOT NULL DEFAULT 'medium',
  `price` decimal(10,2) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `max_items` int(11) NOT NULL DEFAULT 5,
  `stock` int(11) NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `occasion` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_fillers`
--

CREATE TABLE `gift_fillers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` enum('chocolate','flower','perfume','accessory','candy','toy','other') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 100,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_ribbons`
--

CREATE TABLE `gift_ribbons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_wrappings`
--

CREATE TABLE `gift_wrappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_cases`
--

CREATE TABLE `hr_cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `case_type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `notes` text DEFAULT NULL,
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `severity` varchar(255) NOT NULL DEFAULT 'medium',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `description` text DEFAULT NULL,
  `reported_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_media`
--

CREATE TABLE `incident_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `media_type` varchar(255) NOT NULL DEFAULT 'image',
  `file_url` varchar(255) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `incident_id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_claims`
--

CREATE TABLE `insurance_claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_assignment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `claim_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_alerts`
--

CREATE TABLE `inventory_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `alert_type` varchar(255) NOT NULL,
  `current_quantity` int(11) NOT NULL,
  `threshold_quantity` int(11) NOT NULL,
  `severity` varchar(255) NOT NULL DEFAULT 'warning',
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `resulting_stock` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment','transfer') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `product_id`, `type`, `quantity`, `previous_stock`, `new_stock`, `reason`, `notes`, `order_id`, `created_by`, `metadata`, `created_at`, `updated_at`) VALUES
(13, 1155, 'out', 1, 15, 14, 'sale', 'Order ORD-69CCED98D0F8D', NULL, 1, NULL, '2026-04-01 10:04:09', '2026-04-01 10:04:09'),
(14, 1155, 'out', 1, 14, 13, 'sale', 'Order ORD-69CE053152A9F', NULL, 1, NULL, '2026-04-02 05:57:05', '2026-04-02 05:57:05'),
(15, 1155, 'out', 1, 13, 12, 'sale', 'Order ORD-69CE08FC8AF15', NULL, 1, NULL, '2026-04-02 06:13:16', '2026-04-02 06:13:16'),
(16, 1155, 'out', 2, 12, 10, 'sale', 'Order ORD-69CE1E26E5DC2', NULL, 1, NULL, '2026-04-02 07:43:35', '2026-04-02 07:43:35');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_shrinkage`
--

CREATE TABLE `inventory_shrinkage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_loss` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reported_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'USD',
  `status` varchar(24) NOT NULL DEFAULT 'issued',
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_blacklists`
--

CREATE TABLE `ip_blacklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `blocked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"a81fa3c9-79ed-4c78-b1f2-653cef5b291b\",\"displayName\":\"App\\\\Events\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:29:\\\"App\\\\Events\\\\OrderStatusUpdated\\\":1:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1773124893, 1773124893),
(2, 'default', '{\"uuid\":\"ea63b249-f462-4b4f-9faf-b7be99b49892\",\"displayName\":\"App\\\\Events\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:29:\\\"App\\\\Events\\\\OrderStatusUpdated\\\":1:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1773207259, 1773207259),
(3, 'default', '{\"uuid\":\"0f6c0ade-037e-4c79-85cb-0b57fccf4d17\",\"displayName\":\"App\\\\Events\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:29:\\\"App\\\\Events\\\\OrderStatusUpdated\\\":1:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1774173787, 1774173787);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email` varchar(255) NOT NULL,
  `applicant_phone` varchar(255) DEFAULT NULL,
  `resume_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`resume_data`)),
  `cover_letter` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`attachments`)),
  `status` enum('applied','screening','interview_scheduled','interviewed','offer_made','hired','rejected') NOT NULL DEFAULT 'applied',
  `interview_notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interview_notes`)),
  `rating` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`requirements`)),
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') NOT NULL,
  `status` enum('draft','active','paused','closed') NOT NULL DEFAULT 'draft',
  `hiring_manager_id` bigint(20) UNSIGNED NOT NULL,
  `application_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_number` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `entry_type` enum('general','sales','purchase','payment','receipt','adjustment') NOT NULL,
  `description` text NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','posted','approved','reversed') NOT NULL DEFAULT 'draft',
  `posted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `reversed_entry_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_lines`
--

CREATE TABLE `journal_entry_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('debit','credit') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

CREATE TABLE `leave_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `allocated_days` decimal(5,2) NOT NULL,
  `used_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `remaining_days` decimal(5,2) NOT NULL,
  `carried_over` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` enum('annual','sick','emergency','unpaid','maternity','paternity') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_01_01_000001_create_users_table', 1),
(3, '2024_01_01_000002_create_email_verifications_table', 1),
(4, '2024_12_03_000004_add_is_driver_supervisor_to_users_table', 1),
(5, '2025_01_01_000000_create_comprehensive_webstore_schema', 1),
(6, '2025_01_15_create_user_activity_table', 1),
(7, '2025_11_06_054523_create_sessions_table', 1),
(8, '2025_11_06_060516_create_cache_table', 1),
(9, '2025_11_06_070001_create_password_resets_table', 1),
(10, '2025_11_06_073000_add_gender_currency_to_users_table', 1),
(11, '2025_11_06_074500_create_categories_products_attributes_tables', 1),
(12, '2025_11_06_081500_add_image_to_categories_products', 1),
(13, '2025_11_10_090000_create_countries_table', 1),
(14, '2025_11_17_080300_add_display_order_to_categories_table', 1),
(15, '2025_11_17_080400_update_products_table_for_tulip_store', 1),
(16, '2025_11_19_075315_add_verification_code_to_users_table', 1),
(17, '2025_11_20_082401_add_google_id_to_users_table', 1),
(18, '2025_11_20_085348_add_token_to_email_verifications_table', 1),
(19, '2025_11_20_090458_add_is_trader_to_users_table', 1),
(20, '2025_11_23_093307_add_filter_columns_to_products_table', 1),
(21, '2025_11_23_100832_add_category_specific_filters_to_products_table', 1),
(22, '2025_11_23_100935_add_more_filter_columns_to_products', 1),
(23, '2025_11_27_080548_create_notifications_table', 1),
(24, '2025_11_27_104238_add_payment_receipt_to_orders_table', 1),
(25, '2025_11_30_055244_add_is_admin_to_users_table', 1),
(26, '2025_11_30_060508_add_admin_notes_to_orders_table', 1),
(27, '2025_11_30_074731_create_coupons_table', 1),
(28, '2025_11_30_074809_create_refunds_table', 1),
(29, '2025_11_30_074914_create_product_variants_table', 1),
(30, '2025_11_30_075004_create_activity_logs_table', 1),
(31, '2025_11_30_075049_add_additional_fields_to_products_table', 1),
(32, '2025_11_30_075103_add_additional_fields_to_users_table', 1),
(33, '2025_11_30_082426_add_missing_columns_to_orders_table', 1),
(34, '2025_11_30_082914_add_role_and_permissions_to_users_table', 1),
(35, '2025_11_30_082943_create_roles_table', 1),
(36, '2025_11_30_083003_create_permissions_table', 1),
(37, '2025_11_30_090547_create_messages_table', 1),
(38, '2025_11_30_102000_add_it_fields_to_users_table', 1),
(39, '2025_12_01_061825_create_system_logs_table', 1),
(40, '2025_12_01_061845_create_system_services_table', 1),
(41, '2025_12_01_061901_create_scheduled_tasks_table', 1),
(42, '2025_12_01_061919_create_system_alerts_table', 1),
(43, '2025_12_01_061939_create_slow_queries_table', 1),
(44, '2025_12_01_064228_create_support_tickets_table', 1),
(45, '2025_12_01_064249_create_ticket_replies_table', 1),
(46, '2025_12_01_064310_create_customer_feedback_table', 1),
(47, '2025_12_01_064332_add_cs_fields_to_users_table', 1),
(48, '2025_12_01_073543_create_accounting_tables', 1),
(49, '2025_12_01_073607_add_accountant_field_to_users_table', 1),
(50, '2025_12_02_100000_create_delivery_system_tables', 1),
(51, '2025_12_03_100000_create_hr_system_tables', 1),
(52, '2025_12_04_095356_add_estimated_delivery_to_orders_table', 1),
(53, '2025_12_04_100000_add_estimated_delivery_to_orders_table', 1),
(54, '2025_12_04_100100_add_shipping_address_to_orders_table', 1),
(55, '2025_12_04_100200_modify_shipping_address_nullable', 1),
(56, '2025_12_04_100300_add_product_name_to_order_items', 1),
(57, '2025_12_04_100400_modify_product_name_nullable', 1),
(58, '2025_12_04_120000_add_driver_assignment_to_orders', 1),
(59, '2025_12_11_074426_create_settings_table', 1),
(60, '2025_12_15_000001_add_role_fields_to_users_table', 1),
(61, '2025_12_15_000002_create_stores_and_financial_tables', 1),
(62, '2025_12_15_100000_add_immutability_fields_to_financial_transactions', 1),
(63, '2025_12_15_100001_add_approved_status_to_financial_transactions', 1),
(64, '2025_12_16_000001_add_store_id_to_products_table', 1),
(65, '2025_12_17_000001_create_enhanced_dashboard_schema', 1),
(66, '2025_12_17_000002_update_existing_tables_for_dashboards', 1),
(67, '2025_12_17_102921_add_missing_columns_to_tables', 1),
(68, '2025_12_17_103639_add_missing_columns_to_system_alerts_table', 1),
(69, '2025_12_18_090120_add_country_to_users_table', 1),
(70, '2025_12_18_090427_update_employees_table_for_authentication', 1),
(71, '2025_12_18_120000_enhance_employees_table_with_comprehensive_profiles', 1),
(72, '2025_12_18_140000_create_comprehensive_dashboard_tables', 1),
(73, '2025_12_18_141000_create_additional_dashboard_tables', 1),
(74, '2025_12_18_150000_create_missing_dashboard_tables', 2),
(75, '2025_12_18_add_store_id_to_orders_table', 2),
(76, '2025_12_18_fix_dashboard_columns', 2),
(77, '2025_12_21_064831_create_gifts_table', 2),
(78, '2025_12_22_000001_create_custom_gifts_tables', 2),
(79, '2025_12_23_100000_create_advanced_dashboard_features', 2),
(80, '2025_12_29_000001_add_user_id_to_drivers_table', 2),
(81, '2026_01_04_000001_cleanup_duplicate_rbac_and_support_tables', 2),
(82, '2026_01_06_000001_cleanup_redundant_dashboard_tables', 2),
(83, '2026_01_06_000002_consolidate_driver_tables', 2),
(84, '2026_01_08_000001_create_payroll_adjustments_table', 3),
(85, '2026_01_18_000001_create_wishlists_table', 4),
(86, '2026_01_18_120000_add_related_order_id_to_support_tickets', 5),
(87, '2026_01_25_100000_create_purchase_orders_tables', 5),
(88, '2026_01_26_000001_create_reviews_table', 5),
(89, '2026_01_26_000000_create_ip_blacklist_table', 6),
(90, '2026_01_26_000001_add_user_lock_fields', 6),
(91, '2026_01_26_090000_create_price_histories_table', 6),
(92, '2026_01_26_120000_create_compliance_documents_table', 6),
(93, '2026_01_26_130000_create_missing_flows_tables', 6),
(94, '2026_01_28_000100_add_indexes_for_performance_and_consistency', 7),
(95, '2026_01_28_000200_create_trader_tables', 7),
(96, '2026_01_28_000300_create_trader_support_and_analytics_tables', 7),
(97, '2026_01_29_120000_update_products_status_for_trader_workflows', 8),
(98, '2026_01_29_120100_update_products_nullable_fields_for_trader', 8),
(99, '2026_02_01_000100_create_employee_dashboard_permissions_table', 8),
(100, '2026_02_02_000001_add_is_cs_to_employees_table', 9),
(101, '2026_02_02_100000_add_compat_columns_to_orders_table', 10),
(102, '2026_02_03_000200_create_administrative_approvals_table', 10),
(103, '2026_02_04_000001_update_attendance_for_multiple_shifts', 11),
(104, '2026_02_04_000002_drop_attendance_employee_date_unique_index', 12),
(105, '2026_02_04_000003_force_drop_attendance_employee_date_unique_index', 12),
(106, '2026_02_04_000004_add_breakdown_to_payroll_records_if_missing', 12),
(107, '2026_02_04_000005_drop_any_attendance_employee_date_unique', 13),
(108, '2026_02_04_000006_add_metadata_to_financial_transactions_if_missing', 13),
(109, '2026_02_04_000007_add_metadata_to_system_logs_if_missing', 14),
(110, '2026_02_04_000008_add_metadata_to_security_audit_logs_if_missing', 14),
(111, '2026_02_04_000009_add_metadata_to_audit_logs_if_missing', 14),
(112, '2026_02_04_000010_create_salary_receipts_table', 14),
(113, '2026_02_04_000007_expand_financial_transactions_type_enum', 15),
(114, '2026_02_04_000008_enable_multiple_attendance_sessions_per_day', 16),
(115, '2026_02_04_000009_expand_financial_transactions_status_enum', 17),
(116, '2026_02_04_000010_expand_drivers_availability_enum', 18),
(117, '2026_02_05_000011_add_updated_at_to_audit_and_system_logs', 19),
(118, '2026_02_05_000012_create_vehicles_and_assign_to_drivers', 20),
(119, '2026_02_05_000001_create_employee_skills_tables', 21),
(120, '2026_02_25_000001_add_market_to_categories_and_products', 21),
(121, '2026_02_25_000002_backfill_market_for_mart_data', 21),
(122, '2026_02_25_000003_backfill_market_for_mart_slugs', 22),
(123, '2026_03_02_000001_add_custom_fields_to_product_attributes', 23),
(124, '2026_03_09_000001_create_category_attribute_definitions_table', 24),
(125, '2026_03_09_000001_extend_product_attributes_for_custom_builder', 25),
(126, '2026_03_09_000010_add_completion_and_revenue_tracking_to_orders', 25),
(127, '2026_03_10_000001_create_jobs_table', 26),
(128, '2026_03_10_000002_create_failed_jobs_table', 26),
(129, '2026_03_10_000003_create_job_batches_table', 26),
(130, '2026_03_10_000004_backfill_delivery_cost_and_utf8mb4', 27),
(131, '2026_03_15_000001_create_user_saved_cards_table', 27),
(132, '2026_03_20_000020_add_profile_photo_to_users_table', 28),
(133, '2026_03_21_001100_create_dashboard_permission_tables', 29),
(134, '2026_03_18_215122_add_is_mart_to_employees_table', 30),
(135, '2026_03_22_000001_add_driver_delivery_signature_to_orders_table', 30),
(136, '2026_03_22_100000_add_user_id_to_employees_for_driver_link', 31),
(137, '2026_03_22_120000_add_signed_at_to_orders_table', 32),
(138, '2026_03_29_232452_make_assigned_by_nullable_in_delivery_assignments_table', 33),
(139, '2026_04_01_000001_fix_stores_owner_foreign_key_for_traders', 33),
(140, '2026_04_01_000001_add_balance_to_users_table', 34),
(141, '2026_04_01_000002_create_customer_balance_audits_table', 34),
(142, '2026_04_01_000003_add_idempotency_key_to_orders_table', 34),
(144, '2026_04_01_000004_create_invoices_table', 35),
(145, '2026_04_01_132924_create_discount_coupons_table', 35),
(146, '2026_04_02_093727_drop_audit_logs_user_id_foreign_key', 36),
(147, '2026_04_02_105708_add_date_of_birth_to_users_table', 37),
(148, '2026_04_02_105914_add_user_id_to_discount_coupons_table', 38),
(149, '2026_04_02_110004_add_birthday_coupon_fields_to_discount_coupons_table', 38);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `channel` enum('database','email','sms','push') NOT NULL DEFAULT 'database',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT 'blue',
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notification_type` varchar(255) NOT NULL,
  `channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`channels`)),
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `template` text NOT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_tasks`
--

CREATE TABLE `onboarding_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `order` int(11) NOT NULL DEFAULT 0,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `open_positions`
--

CREATE TABLE `open_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `employment_type` enum('full_time','part_time','contract','intern') NOT NULL DEFAULT 'full_time',
  `status` enum('draft','active','paused','closed') NOT NULL DEFAULT 'active',
  `hiring_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `address_note` text DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `delivery_method` varchar(255) DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','confirmed','processing','ready','shipped','out_for_delivery','delivered','done','failed','cancelled','refunded','returned') DEFAULT 'pending',
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `revenue_recognized_at` timestamp NULL DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded','partial') NOT NULL DEFAULT 'pending',
  `confirmation_token` varchar(255) DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `customer_signature` text DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `driver_delivery_signature` text DEFAULT NULL,
  `payment_receipt` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `idempotency_key` varchar(80) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `delivery_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `service_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` text DEFAULT NULL,
  `billing_address` longtext DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `estimated_delivery` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_sku` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_snapshot` longtext DEFAULT NULL CHECK (json_valid(`product_snapshot`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `customer_reason` text NOT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'pending',
  `merchant_reason` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`id`, `order_id`, `customer_reason`, `status`, `merchant_reason`, `created_at`, `updated_at`) VALUES
(1, 104, 'مقاس غير مناسب، ويرغب الزبون باستبداله أو استرداد المبلغ.', 'accepted', 'هيك', '2026-02-18 13:12:32', '2026-02-19 08:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_revenue_records`
--

CREATE TABLE `order_revenue_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `financial_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `recognized_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `status` enum('active','suspended','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `slug`, `description`, `logo`, `settings`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Demo Organization', 'demo-org-fsyvhb', NULL, NULL, NULL, 'active', '2026-02-08 02:32:09', '2026-02-08 02:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` enum('pending','approved','processing','completed','rejected') NOT NULL DEFAULT 'pending',
  `bank_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`bank_details`)),
  `notes` text DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonuses` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `insurance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `status` enum('draft','processed','paid') NOT NULL DEFAULT 'draft',
  `payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_adjustments`
--

CREATE TABLE `payroll_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `pay_period` varchar(255) NOT NULL,
  `type` enum('bonus','overtime_bonus','deduction','penalty') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_records`
--

CREATE TABLE `payroll_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `pay_period` varchar(255) NOT NULL,
  `regular_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `overtime_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `regular_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonuses` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL,
  `status` enum('draft','approved','paid') NOT NULL DEFAULT 'draft',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`breakdown`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_bonuses`
--

CREATE TABLE `performance_bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `granted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `granted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_metrics`
--

CREATE TABLE `performance_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metric_name` varchar(255) NOT NULL,
  `metric_type` varchar(255) NOT NULL,
  `metric_date` date NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `performance_metrics`
--

INSERT INTO `performance_metrics` (`id`, `metric_name`, `metric_type`, `metric_date`, `value`, `category`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'search_zero_results', 'daily', '2026-03-09', 2.0000, 'search', '{\"query\":\"\\u0639\\u0637\\u0631\"}', '2026-03-09 12:23:18', '2026-03-09 12:23:21'),
(2, 'search_zero_results', 'daily', '2026-03-11', 1.0000, 'search', '{\"query\":\"\\u0647\\u062f\\u0627\\u064a\\u0627 \\u0623\\u0637\\u0641\\u0627\\u0644\"}', '2026-03-11 02:04:38', '2026-03-11 02:04:38');

-- --------------------------------------------------------

--
-- Table structure for table `performance_reviews`
--

CREATE TABLE `performance_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `review_period` varchar(255) NOT NULL,
  `review_date` date NOT NULL,
  `performance_score` int(11) NOT NULL DEFAULT 0,
  `attendance_score` int(11) NOT NULL DEFAULT 0,
  `quality_score` int(11) NOT NULL DEFAULT 0,
  `teamwork_score` int(11) NOT NULL DEFAULT 0,
  `overall_rating` int(11) NOT NULL DEFAULT 0,
  `strengths` text DEFAULT NULL,
  `areas_for_improvement` text DEFAULT NULL,
  `goals` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'trader-token', 'f8d01aac58ddc0e48484ebe30412ead087d12bcd970b2a20b82e100a9859b661', '[\"*\"]', NULL, NULL, '2026-02-05 03:19:41', '2026-02-05 03:19:41'),
(2, 'App\\Models\\User', 1, 'trader-token', '922f0337d63f1d9d88052288cec37237f839b43494389f250c515241570832e9', '[\"*\"]', NULL, NULL, '2026-02-05 03:19:48', '2026-02-05 03:19:48'),
(3, 'App\\Models\\User', 1, 'trader-token', 'c0b3fe981aeb5b894d05e03b00c53f062dd5b81b4392b9e12875388b1f50b221', '[\"*\"]', NULL, NULL, '2026-02-05 03:20:26', '2026-02-05 03:20:26'),
(4, 'App\\Models\\User', 1, 'trader-token', '16b1b53e266d0f276f94e88e878d8ce1282197b1e1a8e7670acd2fe1c6750ac7', '[\"*\"]', NULL, NULL, '2026-02-05 03:36:22', '2026-02-05 03:36:22'),
(5, 'App\\Models\\User', 1, 'trader-token', '23f12a5b167d50f1b1a11383addf3135460f4c051ada7ff3f5ba032c0572ad1e', '[\"*\"]', NULL, NULL, '2026-02-05 03:36:24', '2026-02-05 03:36:24'),
(6, 'App\\Models\\User', 1, 'trader-token', 'caeb74f6e7af13edb917a744e5bc54c8533e9497493277e232b788a53adcd912', '[\"*\"]', NULL, NULL, '2026-02-05 03:36:25', '2026-02-05 03:36:25'),
(7, 'App\\Models\\User', 1, 'trader-token', 'bedf000224d702b714bff3f9d782a309750808ccf93097b7ab93238eba7ae99e', '[\"*\"]', NULL, NULL, '2026-02-05 03:36:26', '2026-02-05 03:36:26'),
(8, 'App\\Models\\User', 1, 'trader-token', 'd9f26ca30366b2b0639b35e92fa4c220ced5b4b01475b6d4e385a76974dc42ea', '[\"*\"]', NULL, NULL, '2026-02-05 03:36:27', '2026-02-05 03:36:27');

-- --------------------------------------------------------

--
-- Table structure for table `price_histories`
--

CREATE TABLE `price_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `changed_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `trader_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_trader_product` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `condition` varchar(255) NOT NULL DEFAULT 'new',
  `pages` int(11) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `age_range` int(11) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 10,
  `images` longtext DEFAULT NULL CHECK (json_valid(`images`)),
  `rating` int(11) NOT NULL DEFAULT 0,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `attributes` longtext DEFAULT NULL CHECK (json_valid(`attributes`)),
  `seo_data` longtext DEFAULT NULL CHECK (json_valid(`seo_data`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `track_inventory` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `market` varchar(20) NOT NULL DEFAULT 'store',
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` longtext DEFAULT NULL CHECK (json_valid(`dimensions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fit` varchar(255) DEFAULT NULL,
  `sleeve_length` varchar(255) DEFAULT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  `shoe_size` varchar(255) DEFAULT NULL,
  `shoe_type` varchar(255) DEFAULT NULL,
  `screen_size` varchar(255) DEFAULT NULL,
  `storage` varchar(255) DEFAULT NULL,
  `ram` varchar(255) DEFAULT NULL,
  `processor` varchar(255) DEFAULT NULL,
  `battery` varchar(255) DEFAULT NULL,
  `connectivity` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `toy_type` varchar(255) DEFAULT NULL,
  `room` varchar(255) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `power` varchar(255) DEFAULT NULL,
  `sport_type` varchar(255) DEFAULT NULL,
  `skill_level` varchar(255) DEFAULT NULL,
  `warranty` varchar(255) DEFAULT NULL,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `rejection_reason` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `store_id`, `trader_id`, `is_trader_product`, `category_id`, `name`, `slug`, `description`, `condition`, `pages`, `genre`, `author`, `age_range`, `brand`, `material`, `size`, `color`, `details`, `meta_title`, `meta_description`, `short_description`, `sku`, `price`, `cost_price`, `discount_price`, `stock`, `stock_quantity`, `low_stock_threshold`, `images`, `rating`, `reviews_count`, `attributes`, `seo_data`, `is_active`, `is_featured`, `track_inventory`, `status`, `market`, `weight`, `dimensions`, `created_at`, `updated_at`, `fit`, `sleeve_length`, `pattern`, `shoe_size`, `shoe_type`, `screen_size`, `storage`, `ram`, `processor`, `battery`, `connectivity`, `publisher`, `language`, `format`, `toy_type`, `room`, `capacity`, `power`, `sport_type`, `skill_level`, `warranty`, `free_shipping`, `on_sale`, `rejection_reason`, `reviewed_by`, `reviewed_at`) VALUES
(1154, NULL, NULL, 1, NULL, 'Iphone 15', 'iphone-15', NULL, 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TRD-7-FONQ1B', 1500.00, NULL, NULL, 0, 50, 5, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending_admin', 'store', NULL, NULL, '2026-03-31 12:21:05', '2026-03-31 12:22:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1155, NULL, NULL, 1, NULL, 'فستان سهرة أنيق', 'fstan-shr-anyk', NULL, 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TRD-7-QY3LMU', 10.00, NULL, NULL, 0, 10, 5, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending_admin', 'store', NULL, NULL, '2026-03-31 12:25:27', '2026-04-02 07:43:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `attribute_key` varchar(80) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `value` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rules`)),
  `value_text` varchar(191) DEFAULT NULL,
  `value_number` decimal(12,2) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `value_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value_json`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `product_id`, `name`, `attribute_key`, `type`, `value`, `options`, `is_custom`, `sort_order`, `is_required`, `rules`, `value_text`, `value_number`, `value_date`, `value_json`, `created_at`, `updated_at`) VALUES
(201, 1154, 'color', 'color', 'radio_group', '', '[\"red\",\"blue\",\"green\"]', 1, 0, 0, '{\"min_length\":null,\"max_length\":null,\"min\":null,\"max\":null,\"allowed_file_types\":null,\"max_file_size_kb\":null,\"conditions\":[]}', NULL, NULL, NULL, NULL, '2026-03-31 12:21:05', '2026-03-31 12:21:05'),
(202, 1155, 'color', 'color', 'radio_group', '', '[\"red\",\"blue\",\"orange\"]', 1, 0, 0, '{\"min_length\":null,\"max_length\":null,\"min\":null,\"max\":null,\"allowed_file_types\":null,\"max_file_size_kb\":null}', NULL, NULL, NULL, NULL, '2026-03-31 12:25:27', '2026-03-31 12:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_performance_metrics`
--

CREATE TABLE `product_performance_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `metric_date` date NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `cart_additions` int(11) NOT NULL DEFAULT 0,
  `purchases` int(11) NOT NULL DEFAULT 0,
  `conversion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `revenue` decimal(10,2) NOT NULL DEFAULT 0.00,
  `average_rating` decimal(3,2) DEFAULT NULL,
  `review_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`attributes`)),
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profit_loss_statements`
--

CREATE TABLE `profit_loss_statements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period_type` varchar(255) NOT NULL,
  `period` varchar(255) NOT NULL,
  `total_revenue` decimal(12,2) NOT NULL,
  `cost_of_goods_sold` decimal(12,2) NOT NULL,
  `gross_profit` decimal(12,2) NOT NULL,
  `operating_expenses` decimal(12,2) NOT NULL,
  `operating_profit` decimal(12,2) NOT NULL,
  `other_income` decimal(12,2) NOT NULL DEFAULT 0.00,
  `other_expenses` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_profit` decimal(12,2) NOT NULL,
  `tax_expense` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_profit_after_tax` decimal(12,2) NOT NULL,
  `breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`breakdown`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_contact` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_approval',
  `expected_delivery_date` date DEFAULT NULL,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_quantity` int(11) NOT NULL DEFAULT 0,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('full','partial') NOT NULL DEFAULT 'full',
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected','processed') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refund_requests`
--

CREATE TABLE `refund_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `amount` decimal(12,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `is_system_role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route_optimizations`
--

CREATE TABLE `route_optimizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `optimization_date` varchar(255) NOT NULL,
  `delivery_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`delivery_ids`)),
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_distance_km` decimal(10,2) NOT NULL,
  `estimated_duration_minutes` int(11) NOT NULL,
  `fuel_cost` decimal(10,2) DEFAULT NULL,
  `route_path` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`route_path`)),
  `status` varchar(255) NOT NULL,
  `savings_percentage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_receipts`
--

CREATE TABLE `salary_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `financial_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_period` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `paid_date` date DEFAULT NULL,
  `signed_name` varchar(255) DEFAULT NULL,
  `signature_data` longtext DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `created_by_employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_forecasts`
--

CREATE TABLE `sales_forecasts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `forecast_period` varchar(255) NOT NULL,
  `forecasted_quantity` int(11) NOT NULL,
  `forecasted_revenue` decimal(12,2) NOT NULL,
  `confidence_score` decimal(5,2) NOT NULL,
  `factors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`factors`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_tasks`
--

CREATE TABLE `scheduled_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `command` varchar(255) DEFAULT NULL,
  `schedule` varchar(255) NOT NULL,
  `schedule_time` varchar(255) DEFAULT NULL,
  `status` enum('success','failed','running','pending') NOT NULL DEFAULT 'pending',
  `last_run_at` timestamp NULL DEFAULT NULL,
  `next_run_at` timestamp NULL DEFAULT NULL,
  `run_count` int(11) NOT NULL DEFAULT 0,
  `failure_count` int(11) NOT NULL DEFAULT 0,
  `last_output` text DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_logs`
--

CREATE TABLE `search_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `query_text` varchar(255) NOT NULL,
  `results_count` int(11) NOT NULL DEFAULT 0,
  `no_results` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `search_logs`
--

INSERT INTO `search_logs` (`id`, `user_id`, `query_text`, `results_count`, `no_results`, `ip_address`, `user_agent`, `metadata`, `created_at`, `updated_at`) VALUES
(1, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:07:50', '2026-03-08 05:07:50'),
(2, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:08:20', '2026-03-08 05:08:20'),
(3, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:09:19', '2026-03-08 05:09:19'),
(4, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:19', '2026-03-08 05:10:19'),
(5, NULL, 'shoe', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:21', '2026-03-08 05:10:21'),
(6, NULL, 'bag', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:22', '2026-03-08 05:10:22'),
(7, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:40', '2026-03-08 05:10:40'),
(8, NULL, 'shoe', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:42', '2026-03-08 05:10:42'),
(9, NULL, 'bag', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:10:43', '2026-03-08 05:10:43'),
(10, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:11:14', '2026-03-08 05:11:14'),
(11, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:11:36', '2026-03-08 05:11:36'),
(12, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.7705', '{\"source\":\"api.product.search\"}', '2026-03-08 05:12:04', '2026-03-08 05:12:04'),
(13, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-08 05:23:21', '2026-03-08 05:23:21'),
(14, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-08 05:23:30', '2026-03-08 05:23:30'),
(15, NULL, 'عط', 0, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-09 12:23:18', '2026-03-09 12:23:18'),
(16, NULL, 'عطر', 0, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-09 12:23:21', '2026-03-09 12:23:21'),
(17, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-09 12:23:24', '2026-03-09 12:23:24'),
(18, NULL, 'هدايا أطفال', 0, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-11 02:04:38', '2026-03-11 02:04:38'),
(19, NULL, 'electronic', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-15 03:43:08', '2026-03-15 03:43:08'),
(20, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:13', '2026-03-16 05:16:13'),
(21, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:19', '2026-03-16 05:16:19'),
(22, NULL, 'sh', 19, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:36', '2026-03-16 05:16:36'),
(23, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:38', '2026-03-16 05:16:38'),
(24, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:41', '2026-03-16 05:16:41'),
(25, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:16:48', '2026-03-16 05:16:48'),
(26, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:20:50', '2026-03-16 05:20:50'),
(27, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:20:59', '2026-03-16 05:20:59'),
(28, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-16 05:25:08', '2026-03-16 05:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `security_audit_logs`
--

CREATE TABLE `security_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `risk_level` varchar(255) NOT NULL DEFAULT 'low',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `security_audit_logs`
--

INSERT INTO `security_audit_logs` (`id`, `event_type`, `user_type`, `user_id`, `ip_address`, `user_agent`, `status`, `description`, `metadata`, `risk_level`, `created_at`, `updated_at`) VALUES
(1, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-05 03:10:05', '2026-02-05 03:10:05'),
(2, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-05 04:28:51', '2026-02-05 04:28:51'),
(3, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-05 05:13:32', '2026-02-05 05:13:32'),
(4, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-05 06:16:51', '2026-02-05 06:16:51'),
(5, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-05 06:57:04', '2026-02-05 06:57:04'),
(6, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-08 02:35:56', '2026-02-08 02:35:56'),
(7, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-08 02:40:49', '2026-02-08 02:40:49'),
(8, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"trader@demo.com\"}', 'low', '2026-02-08 03:50:13', '2026-02-08 03:50:13'),
(9, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"trader@demo.com\"}', 'low', '2026-02-08 03:50:21', '2026-02-08 03:50:21'),
(10, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"trader@demo.com\"}', 'low', '2026-02-08 03:50:30', '2026-02-08 03:50:30'),
(11, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-08 03:50:43', '2026-02-08 03:50:43'),
(12, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-08 03:51:22', '2026-02-08 03:51:22'),
(13, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-08 04:24:37', '2026-02-08 04:24:37'),
(14, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-08 04:28:43', '2026-02-08 04:28:43'),
(15, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-08 04:36:18', '2026-02-08 04:36:18'),
(16, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-08 05:26:20', '2026-02-08 05:26:20'),
(17, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-22 03:01:27', '2026-02-22 03:01:27'),
(18, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-02-22 03:50:21', '2026-02-22 03:50:21'),
(19, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-22 05:53:50', '2026-02-22 05:53:50'),
(20, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-22 05:53:55', '2026-02-22 05:53:55'),
(21, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-22 05:54:46', '2026-02-22 05:54:46'),
(22, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-22 05:56:03', '2026-02-22 05:56:03'),
(23, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-23 06:52:20', '2026-02-23 06:52:20'),
(24, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-23 06:52:33', '2026-02-23 06:52:33'),
(25, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-02-25 02:16:54', '2026-02-25 02:16:54'),
(26, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-25 02:51:33', '2026-02-25 02:51:33'),
(27, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-02-26 02:12:59', '2026-02-26 02:12:59'),
(28, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-02-26 02:13:09', '2026-02-26 02:13:09'),
(29, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-02 06:06:27', '2026-03-02 06:06:27'),
(30, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-08 03:44:59', '2026-03-08 03:44:59'),
(31, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-08 04:30:17', '2026-03-08 04:30:17'),
(32, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-08 11:50:21', '2026-03-08 11:50:21'),
(33, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-08 11:50:38', '2026-03-08 11:50:38'),
(34, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"ahmed@tulipstore.com\"}', 'low', '2026-03-08 13:02:16', '2026-03-08 13:02:16'),
(35, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"ahmed@tulipstore.com\"}', 'low', '2026-03-08 13:02:26', '2026-03-08 13:02:26'),
(36, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-08 13:16:27', '2026-03-08 13:16:27'),
(37, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-08 13:16:36', '2026-03-08 13:16:36'),
(38, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 03:30:53', '2026-03-09 03:30:53'),
(39, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 03:38:01', '2026-03-09 03:38:01'),
(40, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 03:41:12', '2026-03-09 03:41:12'),
(41, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 03:44:08', '2026-03-09 03:44:08'),
(42, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-09 05:37:51', '2026-03-09 05:37:51'),
(43, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 06:35:12', '2026-03-09 06:35:12'),
(44, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 11:47:54', '2026-03-09 11:47:54'),
(45, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 12:15:50', '2026-03-09 12:15:50'),
(46, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"trader@demo.com\"}', 'low', '2026-03-09 12:18:22', '2026-03-09 12:18:22'),
(47, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-09 12:18:33', '2026-03-09 12:18:33'),
(48, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-09 12:18:37', '2026-03-09 12:18:37'),
(49, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 12:19:07', '2026-03-09 12:19:07'),
(50, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 12:40:20', '2026-03-09 12:40:20'),
(51, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 13:04:21', '2026-03-09 13:04:21'),
(52, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 13:19:19', '2026-03-09 13:19:19'),
(53, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 13:56:58', '2026-03-09 13:56:58'),
(54, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-09 13:58:47', '2026-03-09 13:58:47'),
(55, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 15:18:26', '2026-03-09 15:18:26'),
(56, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 15:23:41', '2026-03-09 15:23:41'),
(57, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-09 16:59:09', '2026-03-09 16:59:09'),
(58, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-09 17:53:38', '2026-03-09 17:53:38'),
(59, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-10 02:02:36', '2026-03-10 02:02:36'),
(60, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-10 02:17:51', '2026-03-10 02:17:51'),
(61, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-10 05:13:54', '2026-03-10 05:13:54'),
(62, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-11 02:05:08', '2026-03-11 02:05:08'),
(63, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-11 02:27:08', '2026-03-11 02:27:08'),
(64, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-11 02:28:22', '2026-03-11 02:28:22'),
(65, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-11 02:28:42', '2026-03-11 02:28:42'),
(66, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-11 02:28:53', '2026-03-11 02:28:53'),
(67, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-11 02:29:02', '2026-03-11 02:29:02'),
(68, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-11 02:46:51', '2026-03-11 02:46:51'),
(69, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-11 02:47:08', '2026-03-11 02:47:08'),
(70, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-11 03:01:59', '2026-03-11 03:01:59'),
(71, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-11 03:06:00', '2026-03-11 03:06:00'),
(72, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-11 03:06:11', '2026-03-11 03:06:11'),
(73, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-12 03:06:28', '2026-03-12 03:06:28'),
(74, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-12 03:13:45', '2026-03-12 03:13:45'),
(75, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-12 07:13:41', '2026-03-12 07:13:41'),
(76, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-12 07:14:58', '2026-03-12 07:14:58'),
(77, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-15 03:27:45', '2026-03-15 03:27:45'),
(78, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-15 04:50:49', '2026-03-15 04:50:49'),
(79, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-15 04:51:02', '2026-03-15 04:51:02'),
(80, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-15 05:01:34', '2026-03-15 05:01:34'),
(81, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-15 07:49:51', '2026-03-15 07:49:51'),
(82, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-16 02:04:09', '2026-03-16 02:04:09'),
(83, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-16 05:44:09', '2026-03-16 05:44:09'),
(84, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-16 05:45:25', '2026-03-16 05:45:25'),
(85, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-16 06:01:35', '2026-03-16 06:01:35'),
(86, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-20 16:44:11', '2026-03-20 16:44:11'),
(87, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-20 17:10:18', '2026-03-20 17:10:18'),
(88, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-20 17:19:21', '2026-03-20 17:19:21'),
(89, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-21 08:40:55', '2026-03-21 08:40:55'),
(90, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-21 08:44:30', '2026-03-21 08:44:30'),
(91, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 08:44:41', '2026-03-21 08:44:41'),
(92, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 12:10:06', '2026-03-21 12:10:06'),
(93, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 12:20:30', '2026-03-21 12:20:30'),
(94, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 12:20:38', '2026-03-21 12:20:38'),
(95, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 12:22:42', '2026-03-21 12:22:42'),
(96, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-21 12:22:50', '2026-03-21 12:22:50'),
(97, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 12:22:58', '2026-03-21 12:22:58'),
(98, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 12:25:18', '2026-03-21 12:25:18'),
(99, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 12:26:35', '2026-03-21 12:26:35'),
(100, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 12:36:40', '2026-03-21 12:36:40'),
(101, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 16:54:00', '2026-03-21 16:54:00'),
(102, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 17:22:11', '2026-03-21 17:22:11'),
(103, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-21 17:22:22', '2026-03-21 17:22:22'),
(104, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-21 18:47:19', '2026-03-21 18:47:19'),
(105, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 09:26:55', '2026-03-22 09:26:55'),
(106, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-22 09:29:56', '2026-03-22 09:29:56'),
(107, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 09:39:23', '2026-03-22 09:39:23'),
(108, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-22 09:39:35', '2026-03-22 09:39:35'),
(109, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-22 09:39:44', '2026-03-22 09:39:44'),
(110, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 09:42:41', '2026-03-22 09:42:41'),
(111, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 10:01:34', '2026-03-22 10:01:34'),
(112, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-22 10:01:45', '2026-03-22 10:01:45'),
(113, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-22 10:01:59', '2026-03-22 10:01:59'),
(114, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 10:02:14', '2026-03-22 10:02:14'),
(115, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 10:08:00', '2026-03-22 10:08:00'),
(116, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi663@gmail.com\"}', 'low', '2026-03-22 10:09:37', '2026-03-22 10:09:37'),
(117, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi663@gmail.com\"}', 'low', '2026-03-22 10:09:45', '2026-03-22 10:09:45'),
(118, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 10:10:31', '2026-03-22 10:10:31'),
(119, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 10:11:20', '2026-03-22 10:11:20'),
(120, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'low', '2026-03-22 10:11:42', '2026-03-22 10:11:42'),
(121, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'low', '2026-03-22 10:11:54', '2026-03-22 10:11:54'),
(122, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 10:23:01', '2026-03-22 10:23:01'),
(123, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 10:24:33', '2026-03-22 10:24:33'),
(124, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'low', '2026-03-22 10:24:43', '2026-03-22 10:24:43'),
(125, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 10:25:01', '2026-03-22 10:25:01'),
(126, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 10:27:15', '2026-03-22 10:27:15'),
(127, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'low', '2026-03-22 10:50:10', '2026-03-22 10:50:10'),
(128, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 10:50:19', '2026-03-22 10:50:19'),
(129, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 11:08:31', '2026-03-22 11:08:31'),
(130, 'login_attempt', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":8}', 'low', '2026-03-22 11:08:39', '2026-03-22 11:08:39'),
(131, 'employee_logout', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":8}', 'low', '2026-03-22 11:08:49', '2026-03-22 11:08:49'),
(132, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-22 11:09:11', '2026-03-22 11:09:11'),
(133, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 11:09:20', '2026-03-22 11:09:20'),
(134, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-22 11:11:32', '2026-03-22 11:11:32'),
(135, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 11:12:30', '2026-03-22 11:12:30'),
(136, 'login_attempt', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":8}', 'low', '2026-03-22 11:12:45', '2026-03-22 11:12:45'),
(137, 'employee_logout', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":8}', 'low', '2026-03-22 11:26:43', '2026-03-22 11:26:43'),
(138, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 11:26:53', '2026-03-22 11:26:53'),
(139, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-22 11:30:53', '2026-03-22 11:30:53'),
(140, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-22 11:32:04', '2026-03-22 11:32:04'),
(141, 'login_attempt', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":8}', 'low', '2026-03-22 11:32:27', '2026-03-22 11:32:27'),
(142, 'employee_logout', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":8}', 'low', '2026-03-22 11:33:15', '2026-03-22 11:33:15'),
(143, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-22 11:33:24', '2026-03-22 11:33:24'),
(144, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 05:41:20', '2026-03-23 05:41:20'),
(145, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 05:50:22', '2026-03-23 05:50:22'),
(146, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 05:50:46', '2026-03-23 05:50:46'),
(147, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 05:54:48', '2026-03-23 05:54:48'),
(148, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-03-23 05:58:47', '2026-03-23 05:58:47'),
(149, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-23 05:58:54', '2026-03-23 05:58:54'),
(150, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:21:24', '2026-03-23 06:21:24'),
(151, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-23 06:23:28', '2026-03-23 06:23:28'),
(152, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:23:47', '2026-03-23 06:23:47'),
(153, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:23:58', '2026-03-23 06:23:58'),
(154, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:24:04', '2026-03-23 06:24:04'),
(155, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-23 06:24:37', '2026-03-23 06:24:37'),
(156, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'low', '2026-03-23 06:24:44', '2026-03-23 06:24:44'),
(157, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-23 06:25:41', '2026-03-23 06:25:41'),
(158, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-23 06:26:21', '2026-03-23 06:26:21'),
(159, 'login_attempt', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":8}', 'low', '2026-03-23 06:26:35', '2026-03-23 06:26:35'),
(160, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:27:54', '2026-03-23 06:27:54'),
(161, 'employee_logout', 'App\\Models\\Employee', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":8}', 'low', '2026-03-23 06:28:32', '2026-03-23 06:28:32'),
(162, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-23 06:28:42', '2026-03-23 06:28:42'),
(163, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:30:48', '2026-03-23 06:30:48'),
(164, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-23 06:31:41', '2026-03-23 06:31:41'),
(165, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-23 06:32:44', '2026-03-23 06:32:44'),
(166, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-03-23 06:32:51', '2026-03-23 06:32:51'),
(167, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 06:32:59', '2026-03-23 06:32:59');
INSERT INTO `security_audit_logs` (`id`, `event_type`, `user_type`, `user_id`, `ip_address`, `user_agent`, `status`, `description`, `metadata`, `risk_level`, `created_at`, `updated_at`) VALUES
(168, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 08:06:10', '2026-03-23 08:06:10'),
(169, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-23 08:06:43', '2026-03-23 08:06:43'),
(170, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-23 08:08:48', '2026-03-23 08:08:48'),
(171, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-03-31 09:10:35', '2026-03-31 09:10:35'),
(172, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-01 05:44:36', '2026-04-01 05:44:36'),
(173, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-04-01 07:45:43', '2026-04-01 07:45:43'),
(174, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-04-01 07:47:19', '2026-04-01 07:47:19'),
(175, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-01 07:47:27', '2026-04-01 07:47:27'),
(176, 'account_created', 'App\\Models\\User', 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Account created via email/password', '{\"email\":\"yousefalhalabi53@gmail.com\"}', 'low', '2026-04-01 09:58:44', '2026-04-01 09:58:44'),
(177, 'account_created', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Account created via email/password', '{\"email\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-01 10:01:51', '2026-04-01 10:01:51'),
(178, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in after verification', '{\"email\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-01 10:02:21', '2026-04-01 10:02:21'),
(179, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-02 05:05:09', '2026-04-02 05:05:09'),
(180, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 05:21:51', '2026-04-02 05:21:51'),
(181, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 05:21:58', '2026-04-02 05:21:58'),
(182, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-04-02 06:20:54', '2026-04-02 06:20:54'),
(183, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-04-02 06:21:07', '2026-04-02 06:21:07'),
(184, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-04-02 06:21:18', '2026-04-02 06:21:18'),
(185, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"admin@tulipstore.com\"}', 'low', '2026-04-02 06:21:25', '2026-04-02 06:21:25'),
(186, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-02 06:21:33', '2026-04-02 06:21:33'),
(187, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 06:43:39', '2026-04-02 06:43:39'),
(188, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-04-02 06:44:17', '2026-04-02 06:44:17'),
(189, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"Ahmad1\"}', 'low', '2026-04-02 06:44:29', '2026-04-02 06:44:29'),
(190, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"Ahmad1\"}', 'low', '2026-04-02 06:44:37', '2026-04-02 06:44:37'),
(191, 'login_attempt', 'App\\Models\\Employee', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Employee login failed', '{\"identifier\":\"Ahmad1\"}', 'low', '2026-04-02 06:44:45', '2026-04-02 06:44:45'),
(192, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-02 06:44:58', '2026-04-02 06:44:58'),
(193, 'employee_logout', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":1}', 'low', '2026-04-02 06:49:16', '2026-04-02 06:49:16'),
(194, 'login_attempt', 'App\\Models\\Employee', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":9}', 'low', '2026-04-02 06:49:27', '2026-04-02 06:49:27'),
(195, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 06:49:56', '2026-04-02 06:49:56'),
(196, 'employee_logout', 'App\\Models\\Employee', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged out', '{\"employee_id\":9}', 'low', '2026-04-02 07:41:00', '2026-04-02 07:41:00'),
(197, 'login_attempt', 'App\\Models\\Employee', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":1}', 'low', '2026-04-02 07:41:13', '2026-04-02 07:41:13'),
(198, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'failed', 'Invalid credentials', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 07:42:47', '2026-04-02 07:42:47'),
(199, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 07:42:55', '2026-04-02 07:42:55'),
(200, 'login_attempt', 'App\\Models\\Employee', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'Employee logged in', '{\"employee_id\":9}', 'low', '2026-04-02 07:44:51', '2026-04-02 07:44:51'),
(201, 'login_attempt', 'App\\Models\\User', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-04-02 07:46:51', '2026-04-02 07:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'monthly_sales_goal', '50000', 'number', 'Monthly sales target in USD', '2026-01-06 04:13:42', '2026-01-06 04:13:42'),
(2, 'yearly_sales_goal', '500000', 'number', 'Yearly sales target in USD', '2026-01-06 04:13:42', '2026-01-06 04:13:42'),
(3, 'homepage_slider_slides', '[{\"image\":\"\\/images\\/banner3.jpg\",\"title\":\"\\u0623\\u0631\\u0633\\u0644 \\u0627\\u0628\\u062a\\u0633\\u0627\\u0645\\u062a\\u0643 \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a\",\"subtitle\":\"\\u062a\\u0633\\u0648\\u0642 \\u0645\\u0639\\u0646\\u0627 \\u0623\\u0641\\u0636\\u0644 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0648\\u0627\\u0644\\u0639\\u0631\\u0648\\u0636\"},{\"image\":\"\\/images\\/banner2.jpg\",\"title\":\"\\u0647\\u062f\\u0627\\u064a\\u0627 \\u062a\\u0648\\u0644\\u064a\\u0628\",\"subtitle\":\"\\u0644\\u062d\\u0638\\u0627\\u062a \\u0627\\u0633\\u062a\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 \\u062a\\u0633\\u062a\\u062d\\u0642 \\u0647\\u062f\\u0627\\u064a\\u0627 \\u0645\\u0645\\u064a\\u0632\\u0629\"},{\"image\":\"\\/images\\/banner1.jpg\",\"title\":\"\\u0648\\u0635\\u0644 \\u062d\\u062f\\u064a\\u062b\\u0627\\u064b\",\"subtitle\":\"\\u0627\\u0643\\u062a\\u0634\\u0641 \\u0623\\u062d\\u062f\\u062b \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0641\\u064a \\u0645\\u062a\\u062c\\u0631\\u0646\\u0627\"}]', 'json', 'Home page slider slides', '2026-02-01 12:33:18', '2026-02-01 12:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `actual_start_time` time DEFAULT NULL,
  `actual_end_time` time DEFAULT NULL,
  `break_duration` decimal(4,2) NOT NULL DEFAULT 0.00,
  `hours_worked` decimal(4,2) DEFAULT NULL,
  `overtime_hours` decimal(4,2) NOT NULL DEFAULT 0.00,
  `status` enum('scheduled','in_progress','completed','missed','cancelled') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('course','strength') NOT NULL DEFAULT 'strength',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slow_queries`
--

CREATE TABLE `slow_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `query` text NOT NULL,
  `execution_time` decimal(10,3) NOT NULL,
  `call_count` int(11) NOT NULL DEFAULT 1,
  `severity` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `database` varchar(255) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `is_optimized` tinyint(1) NOT NULL DEFAULT 0,
  `optimized_at` timestamp NULL DEFAULT NULL,
  `optimization_notes` text DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `business_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`business_info`)),
  `contact_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`contact_info`)),
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `status` enum('active','pending','suspended','closed') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,4) NOT NULL DEFAULT 0.0500,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_sales` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_commission` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `total_earnings` decimal(15,2) NOT NULL DEFAULT 0.00,
  `available_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pending_payout` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `last_order_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `related_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','waiting_customer','resolved','closed') NOT NULL DEFAULT 'open',
  `category` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_response_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `satisfaction_rating` int(11) DEFAULT NULL,
  `satisfaction_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_replies`
--

CREATE TABLE `support_ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `author_type` varchar(255) NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_alerts`
--

CREATE TABLE `system_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('info','warning','error','success') NOT NULL DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('active','resolved','dismissed') NOT NULL DEFAULT 'active',
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_backups`
--

CREATE TABLE `system_backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `size_bytes` bigint(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_errors`
--

CREATE TABLE `system_errors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `error_code` varchar(255) DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `stack_trace` text DEFAULT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `severity` varchar(255) NOT NULL DEFAULT 'error',
  `occurred_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved` tinyint(1) NOT NULL DEFAULT 0,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` enum('emergency','alert','critical','error','warning','notice','info','debug') NOT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `file` varchar(255) DEFAULT NULL,
  `line` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `level`, `channel`, `message`, `context`, `file`, `line`, `user_id`, `session_id`, `ip_address`, `created_at`, `updated_at`, `action`, `user_agent`, `metadata`, `user`) VALUES
(1, 'info', NULL, 'test', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-05 02:43:43', '2026-02-05 02:43:43', 'test', NULL, NULL, 'test'),
(2, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-05 03:10:05', '2026-02-05 03:10:05', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(3, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-05 04:28:51', '2026-02-05 04:28:51', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(4, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-05 05:13:32', '2026-02-05 05:13:32', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(5, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-05 06:16:51', '2026-02-05 06:16:51', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(6, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-05 06:57:04', '2026-02-05 06:57:04', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(7, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 02:35:56', '2026-02-08 02:35:56', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(8, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 02:40:49', '2026-02-08 02:40:49', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(9, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 02:45:37', '2026-02-08 02:45:37', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(10, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:17:33', '2026-02-08 03:17:33', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(11, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:50:13', '2026-02-08 03:50:13', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(12, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:50:21', '2026-02-08 03:50:21', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(13, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:50:30', '2026-02-08 03:50:30', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(14, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:50:43', '2026-02-08 03:50:43', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(15, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 03:51:22', '2026-02-08 03:51:22', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(16, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 04:24:37', '2026-02-08 04:24:37', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(17, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 04:28:43', '2026-02-08 04:28:43', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(18, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 04:36:18', '2026-02-08 04:36:18', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(19, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-08 05:26:20', '2026-02-08 05:26:20', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(20, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 03:01:27', '2026-02-22 03:01:27', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(21, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 03:50:20', '2026-02-22 03:50:20', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(22, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 05:53:50', '2026-02-22 05:53:50', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(23, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 05:53:55', '2026-02-22 05:53:55', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(24, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 05:54:46', '2026-02-22 05:54:46', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(25, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-22 05:56:03', '2026-02-22 05:56:03', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(26, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-23 06:52:20', '2026-02-23 06:52:20', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(27, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-23 06:52:33', '2026-02-23 06:52:33', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(28, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-25 02:16:54', '2026-02-25 02:16:54', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(29, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-25 02:51:33', '2026-02-25 02:51:33', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(30, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-26 02:12:59', '2026-02-26 02:12:59', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(31, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-02-26 02:13:09', '2026-02-26 02:13:09', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(32, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-02 06:06:27', '2026-03-02 06:06:27', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(33, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 03:44:59', '2026-03-08 03:44:59', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(34, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 04:30:16', '2026-03-08 04:30:16', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(35, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 11:50:21', '2026-03-08 11:50:21', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(36, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 11:50:38', '2026-03-08 11:50:38', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(37, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 13:02:16', '2026-03-08 13:02:16', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"ahmed@tulipstore.com\"}', 'ahmed@tulipstore.com'),
(38, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 13:02:26', '2026-03-08 13:02:26', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"ahmed@tulipstore.com\"}', 'ahmed@tulipstore.com'),
(39, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 13:16:27', '2026-03-08 13:16:27', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(40, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-08 13:16:36', '2026-03-08 13:16:36', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(41, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 03:30:53', '2026-03-09 03:30:53', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(42, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 03:38:01', '2026-03-09 03:38:01', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(43, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 03:41:12', '2026-03-09 03:41:12', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(44, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 03:44:08', '2026-03-09 03:44:08', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(45, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 05:37:50', '2026-03-09 05:37:50', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(46, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 06:35:12', '2026-03-09 06:35:12', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(47, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 11:47:54', '2026-03-09 11:47:54', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(48, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:15:50', '2026-03-09 12:15:50', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(49, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:18:22', '2026-03-09 12:18:22', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"trader@demo.com\"}', 'trader@demo.com'),
(50, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:18:33', '2026-03-09 12:18:33', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(51, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:18:37', '2026-03-09 12:18:37', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(52, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:19:07', '2026-03-09 12:19:07', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(53, 'info', NULL, 'Zero-result search', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:23:18', '2026-03-09 12:23:18', 'search_zero_results', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"query\":\"\\u0639\\u0637\"}', 'guest'),
(54, 'info', NULL, 'Zero-result search', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:23:21', '2026-03-09 12:23:21', 'search_zero_results', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"query\":\"\\u0639\\u0637\\u0631\"}', 'guest'),
(55, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 12:40:20', '2026-03-09 12:40:20', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(56, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 13:04:20', '2026-03-09 13:04:20', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(57, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 13:19:19', '2026-03-09 13:19:19', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(58, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 13:56:58', '2026-03-09 13:56:58', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(59, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 13:58:47', '2026-03-09 13:58:47', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(60, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 15:18:26', '2026-03-09 15:18:26', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(61, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 15:23:41', '2026-03-09 15:23:41', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(62, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 16:59:09', '2026-03-09 16:59:09', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(63, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-09 17:53:38', '2026-03-09 17:53:38', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(64, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-10 02:02:36', '2026-03-10 02:02:36', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(65, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-10 02:17:51', '2026-03-10 02:17:51', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(66, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-10 05:13:54', '2026-03-10 05:13:54', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(67, 'info', NULL, 'Zero-result search', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:04:38', '2026-03-11 02:04:38', 'search_zero_results', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"query\":\"\\u0647\\u062f\\u0627\\u064a\\u0627 \\u0623\\u0637\\u0641\\u0627\\u0644\"}', 'guest'),
(68, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:05:08', '2026-03-11 02:05:08', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(69, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:27:08', '2026-03-11 02:27:08', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(70, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:28:22', '2026-03-11 02:28:22', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(71, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:28:42', '2026-03-11 02:28:42', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(72, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:28:53', '2026-03-11 02:28:53', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(73, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:29:02', '2026-03-11 02:29:02', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(74, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:46:51', '2026-03-11 02:46:51', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(75, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 02:47:08', '2026-03-11 02:47:08', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(76, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 03:01:59', '2026-03-11 03:01:59', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(77, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 03:06:00', '2026-03-11 03:06:00', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(78, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-11 03:06:11', '2026-03-11 03:06:11', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(79, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-12 03:06:28', '2026-03-12 03:06:28', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(80, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-12 03:13:45', '2026-03-12 03:13:45', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(81, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 06:47:50', '2026-03-12 06:47:50', NULL, NULL, NULL, NULL),
(82, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 06:47:51', '2026-03-12 06:47:51', NULL, NULL, NULL, NULL),
(83, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 06:47:52', '2026-03-12 06:47:52', NULL, NULL, NULL, NULL),
(84, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 06:49:53', '2026-03-12 06:49:53', NULL, NULL, NULL, NULL),
(85, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-12 07:13:41', '2026-03-12 07:13:41', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(86, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-12 07:14:58', '2026-03-12 07:14:58', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(87, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-15 02:55:36', '2026-03-15 02:55:36', NULL, NULL, NULL, NULL),
(88, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-15 03:27:45', '2026-03-15 03:27:45', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(89, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-15 04:50:48', '2026-03-15 04:50:48', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(90, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-15 04:51:02', '2026-03-15 04:51:02', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(91, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-15 04:52:06', '2026-03-15 04:52:06', NULL, NULL, NULL, NULL),
(92, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-15 05:01:34', '2026-03-15 05:01:34', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(93, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-15 07:49:51', '2026-03-15 07:49:51', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(94, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-16 02:04:09', '2026-03-16 02:04:09', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(95, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-16 05:44:09', '2026-03-16 05:44:09', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(96, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-16 05:45:25', '2026-03-16 05:45:25', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(97, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-16 06:01:35', '2026-03-16 06:01:35', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(98, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-16 07:29:48', '2026-03-16 07:29:48', NULL, NULL, NULL, NULL),
(99, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-16 07:29:49', '2026-03-16 07:29:49', NULL, NULL, NULL, NULL),
(100, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-16 07:29:49', '2026-03-16 07:29:49', NULL, NULL, NULL, NULL),
(101, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-20 16:44:11', '2026-03-20 16:44:11', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(102, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-20 17:10:18', '2026-03-20 17:10:18', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(103, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-20 17:19:21', '2026-03-20 17:19:21', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(104, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 08:40:55', '2026-03-21 08:40:55', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(105, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 08:44:30', '2026-03-21 08:44:30', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(106, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 08:44:41', '2026-03-21 08:44:41', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(107, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:10:06', '2026-03-21 12:10:06', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(108, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:20:30', '2026-03-21 12:20:30', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(109, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:20:38', '2026-03-21 12:20:38', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(110, 'info', NULL, 'New trader registration submitted', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:22:27', '2026-03-21 12:22:27', 'trader_registration_submitted', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"trader_id\":4,\"company\":\"Yousef\"}', 'yousefalhalabi6863@gmail.com'),
(111, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:22:42', '2026-03-21 12:22:42', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(112, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:22:50', '2026-03-21 12:22:50', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(113, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:22:58', '2026-03-21 12:22:58', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(114, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:25:18', '2026-03-21 12:25:18', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(115, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:26:35', '2026-03-21 12:26:35', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(116, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 12:36:40', '2026-03-21 12:36:40', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(117, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 16:54:00', '2026-03-21 16:54:00', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(118, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 17:22:11', '2026-03-21 17:22:11', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(119, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 17:22:21', '2026-03-21 17:22:21', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(120, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-21 18:47:19', '2026-03-21 18:47:19', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(121, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:26:55', '2026-03-22 09:26:55', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(122, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:29:56', '2026-03-22 09:29:56', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(123, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:39:23', '2026-03-22 09:39:23', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(124, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:39:35', '2026-03-22 09:39:35', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(125, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:39:44', '2026-03-22 09:39:44', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(126, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 09:42:41', '2026-03-22 09:42:41', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(127, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:01:34', '2026-03-22 10:01:34', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(128, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:01:45', '2026-03-22 10:01:45', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(129, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:01:59', '2026-03-22 10:01:59', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(130, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:02:14', '2026-03-22 10:02:14', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(131, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:08:00', '2026-03-22 10:08:00', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(132, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:09:37', '2026-03-22 10:09:37', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi663@gmail.com\"}', 'yousefalhalabi663@gmail.com'),
(133, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:09:45', '2026-03-22 10:09:45', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi663@gmail.com\"}', 'yousefalhalabi663@gmail.com'),
(134, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:10:31', '2026-03-22 10:10:31', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(135, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:11:20', '2026-03-22 10:11:20', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(136, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:11:42', '2026-03-22 10:11:42', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'yousefalhalabi456@gmail.com'),
(137, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:11:54', '2026-03-22 10:11:54', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'yousefalhalabi456@gmail.com'),
(138, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:23:00', '2026-03-22 10:23:00', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(139, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:24:33', '2026-03-22 10:24:33', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(140, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:24:43', '2026-03-22 10:24:43', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'yousefalhalabi456@gmail.com'),
(141, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:25:01', '2026-03-22 10:25:01', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(142, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:27:14', '2026-03-22 10:27:14', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(143, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:50:10', '2026-03-22 10:50:10', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi456@gmail.com\"}', 'yousefalhalabi456@gmail.com'),
(144, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 10:50:19', '2026-03-22 10:50:19', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(145, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:08:31', '2026-03-22 11:08:31', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(146, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:08:39', '2026-03-22 11:08:39', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(147, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:08:49', '2026-03-22 11:08:49', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(148, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:09:11', '2026-03-22 11:09:11', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(149, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:09:20', '2026-03-22 11:09:20', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(150, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:11:32', '2026-03-22 11:11:32', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(151, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:12:30', '2026-03-22 11:12:30', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(152, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:12:45', '2026-03-22 11:12:45', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(153, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:26:43', '2026-03-22 11:26:43', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(154, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:26:53', '2026-03-22 11:26:53', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(155, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:30:53', '2026-03-22 11:30:53', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(156, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:32:04', '2026-03-22 11:32:04', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(157, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:32:27', '2026-03-22 11:32:27', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(158, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:33:15', '2026-03-22 11:33:15', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com');
INSERT INTO `system_logs` (`id`, `level`, `channel`, `message`, `context`, `file`, `line`, `user_id`, `session_id`, `ip_address`, `created_at`, `updated_at`, `action`, `user_agent`, `metadata`, `user`) VALUES
(159, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-22 11:33:24', '2026-03-22 11:33:24', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(160, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:41:20', '2026-03-23 05:41:20', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(161, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:50:22', '2026-03-23 05:50:22', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(162, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:50:46', '2026-03-23 05:50:46', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(163, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:54:48', '2026-03-23 05:54:48', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(164, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:58:47', '2026-03-23 05:58:47', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(165, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 05:58:54', '2026-03-23 05:58:54', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(166, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:21:24', '2026-03-23 06:21:24', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(167, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:23:28', '2026-03-23 06:23:28', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(168, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:23:47', '2026-03-23 06:23:47', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(169, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:23:58', '2026-03-23 06:23:58', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(170, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:24:04', '2026-03-23 06:24:04', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(171, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:24:37', '2026-03-23 06:24:37', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(172, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:24:44', '2026-03-23 06:24:44', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi553@gmail.com\"}', 'yousefalhalabi553@gmail.com'),
(173, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:25:41', '2026-03-23 06:25:41', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(174, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:26:21', '2026-03-23 06:26:21', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(175, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:26:35', '2026-03-23 06:26:35', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(176, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:27:54', '2026-03-23 06:27:54', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(177, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:28:32', '2026-03-23 06:28:32', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":8}', 'yousefalhalabi445@gmail.com'),
(178, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:28:42', '2026-03-23 06:28:42', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(179, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:30:48', '2026-03-23 06:30:48', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(180, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:31:41', '2026-03-23 06:31:41', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(181, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:32:44', '2026-03-23 06:32:44', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(182, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:32:51', '2026-03-23 06:32:51', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(183, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 06:32:59', '2026-03-23 06:32:59', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(184, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 08:06:09', '2026-03-23 08:06:09', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(185, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 08:06:43', '2026-03-23 08:06:43', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(186, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-23 08:08:48', '2026-03-23 08:08:48', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(187, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-23 08:12:10', '2026-03-23 08:12:10', NULL, NULL, NULL, NULL),
(188, 'emergency', NULL, 'Image load failed, fallback attempted', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-23 08:12:31', '2026-03-23 08:12:31', NULL, NULL, NULL, NULL),
(189, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-31 09:10:35', '2026-03-31 09:10:35', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(190, 'info', NULL, 'New trader registration submitted', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-31 09:19:38', '2026-03-31 09:19:38', 'trader_registration_submitted', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"trader_id\":5,\"company\":\"Yousef\"}', 'yousefalhalabi53@gmail.com'),
(191, 'info', NULL, 'New trader registration submitted', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-31 11:50:14', '2026-03-31 11:50:14', 'trader_registration_submitted', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"trader_id\":6}', 'yousefalhalabi63@gmail.com'),
(192, 'info', NULL, 'New trader registration submitted', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-31 12:13:49', '2026-03-31 12:13:49', 'trader_registration_submitted', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"trader_id\":7}', 'yousefalhalabi63@gmail.com'),
(193, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 05:44:36', '2026-04-01 05:44:36', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(194, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 07:45:43', '2026-04-01 07:45:43', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(195, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 07:47:19', '2026-04-01 07:47:19', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(196, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 07:47:27', '2026-04-01 07:47:27', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(197, 'info', NULL, 'User registered via email/password', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 09:58:44', '2026-04-01 09:58:44', 'user_registered', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email_password\"}', 'yousefalhalabi53@gmail.com'),
(198, 'info', NULL, 'User registered via email/password', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 10:01:51', '2026-04-01 10:01:51', 'user_registered', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email_password\"}', 'yousefalhalabi63@gmail.com'),
(199, 'info', NULL, 'User logged in after verification', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-01 10:02:21', '2026-04-01 10:02:21', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email_password\"}', 'yousefalhalabi63@gmail.com'),
(200, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 05:05:09', '2026-04-02 05:05:09', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(201, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 05:21:51', '2026-04-02 05:21:51', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(202, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 05:21:58', '2026-04-02 05:21:58', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(203, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:20:54', '2026-04-02 06:20:54', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(204, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:21:07', '2026-04-02 06:21:07', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(205, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:21:18', '2026-04-02 06:21:18', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(206, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:21:25', '2026-04-02 06:21:25', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"admin@tulipstore.com\"}', 'admin@tulipstore.com'),
(207, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:21:33', '2026-04-02 06:21:33', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(208, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:43:39', '2026-04-02 06:43:39', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(209, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:44:17', '2026-04-02 06:44:17', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(210, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:44:29', '2026-04-02 06:44:29', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"Ahmad1\"}', 'Ahmad1'),
(211, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:44:37', '2026-04-02 06:44:37', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"Ahmad1\"}', 'Ahmad1'),
(212, 'warning', NULL, 'Employee login failed', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:44:45', '2026-04-02 06:44:45', 'employee_login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"Ahmad1\"}', 'Ahmad1'),
(213, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:44:58', '2026-04-02 06:44:58', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(214, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:49:16', '2026-04-02 06:49:16', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(215, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:49:27', '2026-04-02 06:49:27', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":9}', 'ahmad1@drivers.local'),
(216, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 06:49:56', '2026-04-02 06:49:56', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(217, 'info', NULL, 'Employee logged out', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:41:00', '2026-04-02 07:41:00', 'employee_logout', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":9}', 'ahmad1@drivers.local'),
(218, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:41:13', '2026-04-02 07:41:13', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":1}', 'admin@tulipstore.com'),
(219, 'error', NULL, 'Failed login attempt', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:42:47', '2026-04-02 07:42:47', 'login_failed', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'yousefalhalabi63@gmail.com'),
(220, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:42:55', '2026-04-02 07:42:55', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com'),
(221, 'info', NULL, 'Employee logged in', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:44:51', '2026-04-02 07:44:51', 'employee_login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"employee_id\":9}', 'ahmad1@drivers.local'),
(222, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-04-02 07:46:51', '2026-04-02 07:46:51', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `system_resources`
--

CREATE TABLE `system_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resource_type` varchar(255) NOT NULL,
  `server_name` varchar(255) NOT NULL,
  `usage_percentage` decimal(5,2) NOT NULL,
  `used_bytes` bigint(20) DEFAULT NULL,
  `total_bytes` bigint(20) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_services`
--

CREATE TABLE `system_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(11) DEFAULT NULL,
  `status` enum('online','offline','degraded','maintenance') NOT NULL DEFAULT 'offline',
  `response_time` decimal(8,3) DEFAULT NULL,
  `uptime_percentage` int(11) NOT NULL DEFAULT 0,
  `last_check` timestamp NULL DEFAULT NULL,
  `health_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`health_data`)),
  `configuration` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`configuration`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `uptime` varchar(255) DEFAULT NULL,
  `cpu_usage` varchar(255) DEFAULT NULL,
  `memory_usage` varchar(255) DEFAULT NULL,
  `last_checked_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'feature.cs_complete_delivered_to_completed', 'true', 'boolean', NULL, 0, '2026-03-09 19:19:26', '2026-03-09 19:19:26'),
(2, 'usd_to_syp_rate', '117', 'string', NULL, 0, '2026-03-23 06:11:02', '2026-03-23 08:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` varchar(50) NOT NULL,
  `text` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_calculations`
--

CREATE TABLE `tax_calculations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` varchar(255) NOT NULL,
  `tax_rate` decimal(5,4) NOT NULL,
  `taxable_amount` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL,
  `tax_jurisdiction` varchar(255) DEFAULT NULL,
  `calculation_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`calculation_details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `traders`
--

CREATE TABLE `traders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','suspended','rejected') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `payout_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payout_settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_name_en` varchar(255) NOT NULL,
  `account_name_ar` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `responsible_name` varchar(255) NOT NULL,
  `work_address` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `owner_id_image_path` text DEFAULT NULL,
  `logo_image_path` text DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL DEFAULT '',
  `bank_account_holder` varchar(255) NOT NULL DEFAULT '',
  `bank_account_number` varchar(64) NOT NULL DEFAULT '',
  `iban` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_analytics_daily`
--

CREATE TABLE `trader_analytics_daily` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `total_items_sold` int(11) NOT NULL DEFAULT 0,
  `total_revenue` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_earnings` decimal(10,2) NOT NULL DEFAULT 0.00,
  `products_added` int(11) NOT NULL DEFAULT 0,
  `products_approved` int(11) NOT NULL DEFAULT 0,
  `products_rejected` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_orders`
--

CREATE TABLE `trader_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `commission_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_payouts`
--

CREATE TABLE `trader_payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` enum('pending','approved','processing','completed','rejected') NOT NULL DEFAULT 'pending',
  `bank_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bank_details`)),
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_products`
--

CREATE TABLE `trader_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price_override` decimal(12,2) DEFAULT NULL,
  `stock_managed_by` enum('platform','trader') NOT NULL DEFAULT 'platform',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_reports`
--

CREATE TABLE `trader_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `report_type` enum('sales','inventory','earnings','custom','issue') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `report_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`report_data`)),
  `file_url` varchar(500) DEFAULT NULL,
  `submitted_to` enum('owner','admin','support') NOT NULL DEFAULT 'owner',
  `status` enum('submitted','under_review','resolved','closed') NOT NULL DEFAULT 'submitted',
  `admin_response` text DEFAULT NULL,
  `responded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_support_messages`
--

CREATE TABLE `trader_support_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('trader','support') NOT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_internal_note` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trader_support_tickets`
--

CREATE TABLE `trader_support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trader_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `category` enum('product_approval','payment','order_issue','technical','general','dispute') NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `description` text NOT NULL,
  `status` enum('open','in_progress','waiting_trader','resolved','closed') NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_assignments`
--

CREATE TABLE `training_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` enum('assigned','in_progress','completed','expired') NOT NULL DEFAULT 'assigned',
  `assigned_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_enrollments`
--

CREATE TABLE `training_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_program_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('enrolled','completed','failed','withdrawn') NOT NULL DEFAULT 'enrolled',
  `score` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_programs`
--

CREATE TABLE `training_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `trainer` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_hours` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_participants` int(11) DEFAULT NULL,
  `status` enum('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_full_name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'english',
  `gender` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_trader` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_it_super` tinyint(1) NOT NULL DEFAULT 0,
  `is_it` tinyint(1) NOT NULL DEFAULT 0,
  `is_cs_agent` tinyint(1) NOT NULL DEFAULT 0,
  `is_accountant` tinyint(1) NOT NULL DEFAULT 0,
  `is_cs_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `newsletter_subscribed` tinyint(1) NOT NULL DEFAULT 0,
  `lifetime_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remember_token` varchar(100) DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_until` timestamp NULL DEFAULT NULL,
  `lock_reason` varchar(255) DEFAULT NULL,
  `login_failures` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_driver_supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_hr` tinyint(1) NOT NULL DEFAULT 0,
  `is_cs` tinyint(1) NOT NULL DEFAULT 0,
  `is_finance` tinyint(1) NOT NULL DEFAULT 0,
  `is_hr_manager` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activity_type` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `search_query` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `favorite_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`favorite_categories`)),
  `search_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`search_keywords`)),
  `viewed_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`viewed_products`)),
  `purchased_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`purchased_products`)),
  `activity_score` int(11) NOT NULL DEFAULT 0,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_saved_cards`
--

CREATE TABLE `user_saved_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `brand` varchar(32) DEFAULT NULL,
  `last4` varchar(4) NOT NULL,
  `expiry` varchar(7) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `make` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `year` smallint(5) UNSIGNED DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `vin` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_maintenance`
--

CREATE TABLE `vehicle_maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('routine','repair','inspection','emergency') NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `maintenance_date` date NOT NULL,
  `next_due_date` date DEFAULT NULL,
  `odometer_reading` int(11) DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_feeds`
--
ALTER TABLE `activity_feeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_feeds_actor_type_actor_id_index` (`actor_type`,`actor_id`),
  ADD KEY `activity_feeds_target_type_target_id_index` (`target_type`,`target_id`),
  ADD KEY `activity_feeds_dashboard_type_created_at_index` (`dashboard_type`,`created_at`),
  ADD KEY `activity_feeds_activity_type_created_at_index` (`activity_type`,`created_at`),
  ADD KEY `activity_feeds_is_read_index` (`is_read`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`);

--
-- Indexes for table `administrative_approvals`
--
ALTER TABLE `administrative_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrative_approvals_decided_by_employee_id_foreign` (`decided_by_employee_id`),
  ADD KEY `administrative_approvals_status_category_index` (`status`,`category`),
  ADD KEY `administrative_approvals_requester_employee_id_status_index` (`requester_employee_id`,`status`);

--
-- Indexes for table `alert_rules`
--
ALTER TABLE `alert_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alert_rules_dashboard_type_is_active_index` (`dashboard_type`,`is_active`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_created_by_foreign` (`created_by`),
  ADD KEY `announcements_type_published_at_index` (`type`,`published_at`),
  ADD KEY `announcements_target_audience_is_pinned_index` (`target_audience`,`is_pinned`);

--
-- Indexes for table `api_errors`
--
ALTER TABLE `api_errors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_errors_endpoint_status_code_index` (`endpoint`,`status_code`),
  ADD KEY `api_errors_occurred_at_status_code_index` (`occurred_at`,`status_code`),
  ADD KEY `api_errors_user_id_occurred_at_index` (`user_id`,`occurred_at`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`),
  ADD KEY `api_keys_user_id_foreign` (`user_id`),
  ADD KEY `api_keys_key_is_active_index` (`key`,`is_active`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attendance_employee_id` (`employee_id`),
  ADD KEY `idx_attendance_employee_date` (`employee_id`,`date`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_transactions_type_status_index` (`type`,`status`),
  ADD KEY `bank_transactions_occurred_at_index` (`occurred_at`),
  ADD KEY `bank_transactions_bank_reference_index` (`bank_reference`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_created_by_foreign` (`created_by`),
  ADD KEY `budgets_category_period_index` (`category`,`period`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_status_index` (`user_id`,`status`),
  ADD KEY `carts_session_id_index` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_cart_id_product_id_unique` (`cart_id`,`product_id`),
  ADD KEY `cart_items_product_id_index` (`product_id`);

--
-- Indexes for table `cash_flow_records`
--
ALTER TABLE `cash_flow_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_flow_records_transaction_date_flow_type_index` (`transaction_date`,`flow_type`),
  ADD KEY `cash_flow_records_category_transaction_date_index` (`category`,`transaction_date`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_is_active_sort_order_index` (`parent_id`,`is_active`,`sort_order`),
  ADD KEY `categories_market_index` (`market`);

--
-- Indexes for table `category_attribute_definitions`
--
ALTER TABLE `category_attribute_definitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_attribute_definitions_category_id_sort_order_index` (`category_id`,`sort_order`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  ADD KEY `chart_of_accounts_parent_account_id_foreign` (`parent_account_id`),
  ADD KEY `chart_of_accounts_account_code_index` (`account_code`),
  ADD KEY `chart_of_accounts_account_type_index` (`account_type`);

--
-- Indexes for table `commission_rates`
--
ALTER TABLE `commission_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commission_rates_store_id_effective_from_index` (`store_id`,`effective_from`),
  ADD KEY `commission_rates_is_active_effective_from_index` (`is_active`,`effective_from`);

--
-- Indexes for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compliance_documents_doc_type_period_index` (`doc_type`,`period`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_iso2_unique` (`iso2`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usage_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_usage_user_id_foreign` (`user_id`),
  ADD KEY `coupon_usage_order_id_foreign` (`order_id`);

--
-- Indexes for table `customer_balance_audits`
--
ALTER TABLE `customer_balance_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_balance_audits_support_user_id_foreign` (`support_user_id`),
  ADD KEY `customer_balance_audits_customer_id_created_at_index` (`customer_id`,`created_at`),
  ADD KEY `customer_balance_audits_type_created_at_index` (`type`,`created_at`);

--
-- Indexes for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_feedback_order_id_foreign` (`order_id`),
  ADD KEY `customer_feedback_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `customer_feedback_user_id_index` (`user_id`),
  ADD KEY `customer_feedback_type_index` (`type`),
  ADD KEY `customer_feedback_status_index` (`status`),
  ADD KEY `customer_feedback_rating_index` (`rating`),
  ADD KEY `customer_feedback_created_at_index` (`created_at`);

--
-- Indexes for table `custom_gifts`
--
ALTER TABLE `custom_gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_gifts_user_id_foreign` (`user_id`),
  ADD KEY `custom_gifts_gift_box_id_foreign` (`gift_box_id`),
  ADD KEY `custom_gifts_gift_wrapping_id_foreign` (`gift_wrapping_id`),
  ADD KEY `custom_gifts_gift_ribbon_id_foreign` (`gift_ribbon_id`),
  ADD KEY `custom_gifts_gift_card_id_foreign` (`gift_card_id`);

--
-- Indexes for table `custom_gift_items`
--
ALTER TABLE `custom_gift_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_gift_items_custom_gift_id_foreign` (`custom_gift_id`),
  ADD KEY `custom_gift_items_gift_filler_id_foreign` (`gift_filler_id`);

--
-- Indexes for table `daily_analytics`
--
ALTER TABLE `daily_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_analytics_unique_index` (`analytics_date`,`metric_type`,`dimension`,`dimension_value`),
  ADD KEY `daily_analytics_analytics_date_metric_type_index` (`analytics_date`,`metric_type`);

--
-- Indexes for table `dashboard_cache`
--
ALTER TABLE `dashboard_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dashboard_cache_dashboard_type_cache_key_unique` (`dashboard_type`,`cache_key`),
  ADD KEY `dashboard_cache_expires_at_index` (`expires_at`);

--
-- Indexes for table `dashboard_notifications`
--
ALTER TABLE `dashboard_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dashboard_notifications_user_type_user_id_index` (`user_type`,`user_id`),
  ADD KEY `dashboard_notifications_user_type_user_id_is_read_index` (`user_type`,`user_id`,`is_read`),
  ADD KEY `dashboard_notifications_dashboard_type_created_at_index` (`dashboard_type`,`created_at`),
  ADD KEY `dashboard_notifications_dashboard_type_index` (`dashboard_type`),
  ADD KEY `dashboard_notifications_user_type_index` (`user_type`),
  ADD KEY `dashboard_notifications_user_id_index` (`user_id`),
  ADD KEY `dashboard_notifications_is_read_index` (`is_read`),
  ADD KEY `dashboard_notifications_created_at_index` (`created_at`);

--
-- Indexes for table `dashboard_quick_actions`
--
ALTER TABLE `dashboard_quick_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dashboard_quick_actions_user_type_user_id_index` (`user_type`,`user_id`),
  ADD KEY `dashboard_quick_actions_dashboard_type_created_at_index` (`dashboard_type`,`created_at`);

--
-- Indexes for table `dashboard_role_permissions`
--
ALTER TABLE `dashboard_role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drp_role_dashboard_unique` (`role_key`,`dashboard_key`);

--
-- Indexes for table `database_backups`
--
ALTER TABLE `database_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `database_backups_database_name_status_index` (`database_name`,`status`),
  ADD KEY `database_backups_type_completed_at_index` (`type`,`completed_at`);

--
-- Indexes for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `delivery_assignments_driver_id_status_index` (`driver_id`,`status`),
  ADD KEY `delivery_assignments_order_id_status_index` (`order_id`,`status`),
  ADD KEY `delivery_assignments_driver_id_index` (`driver_id`),
  ADD KEY `delivery_assignments_order_id_index` (`order_id`),
  ADD KEY `delivery_assignments_status_index` (`status`);

--
-- Indexes for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_attempts_delivery_assignment_id_attempt_number_unique` (`delivery_assignment_id`,`attempt_number`),
  ADD KEY `delivery_attempts_status_attempted_at_index` (`status`,`attempted_at`);

--
-- Indexes for table `delivery_proofs`
--
ALTER TABLE `delivery_proofs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_proofs_delivery_assignment_id_captured_at_index` (`delivery_assignment_id`,`captured_at`);

--
-- Indexes for table `delivery_ratings`
--
ALTER TABLE `delivery_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_ratings_user_id_foreign` (`user_id`),
  ADD KEY `delivery_ratings_driver_id_rating_created_at_index` (`driver_id`,`rating`,`created_at`),
  ADD KEY `delivery_ratings_order_id_created_at_index` (`order_id`,`created_at`);

--
-- Indexes for table `delivery_routes`
--
ALTER TABLE `delivery_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_routes_driver_id_route_date_index` (`driver_id`,`route_date`),
  ADD KEY `delivery_routes_status_route_date_index` (`status`,`route_date`);

--
-- Indexes for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_zone_analytics`
--
ALTER TABLE `delivery_zone_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_zone_analytics_zone_name_analytics_date_unique` (`zone_name`,`analytics_date`),
  ADD KEY `delivery_zone_analytics_analytics_date_index` (`analytics_date`);

--
-- Indexes for table `deployment_history`
--
ALTER TABLE `deployment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployment_history_deployed_by_foreign` (`deployed_by`),
  ADD KEY `deployment_history_environment_status_index` (`environment`,`status`),
  ADD KEY `deployment_history_version_environment_index` (`version`,`environment`);

--
-- Indexes for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployment_logs_deployed_by_foreign` (`deployed_by`),
  ADD KEY `deployment_logs_environment_status_index` (`environment`,`status`),
  ADD KEY `deployment_logs_version_environment_index` (`version`,`environment`);

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_codes_code_unique` (`code`),
  ADD KEY `discount_codes_store_id_foreign` (`store_id`),
  ADD KEY `discount_codes_is_active_valid_from_valid_until_index` (`is_active`,`valid_from`,`valid_until`);

--
-- Indexes for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_coupons_code_unique` (`code`),
  ADD KEY `discount_coupons_created_by_foreign` (`created_by`),
  ADD KEY `discount_coupons_user_id_foreign` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_license_number_unique` (`license_number`),
  ADD UNIQUE KEY `drivers_vehicle_plate_unique` (`vehicle_plate`),
  ADD KEY `drivers_user_id_foreign` (`user_id`),
  ADD KEY `drivers_status_availability_index` (`status`,`availability`),
  ADD KEY `drivers_rating_total_deliveries_index` (`rating`,`total_deliveries`),
  ADD KEY `drivers_vehicle_id_index` (`vehicle_id`);

--
-- Indexes for table `driver_locations`
--
ALTER TABLE `driver_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_locations_driver_id_recorded_at_index` (`driver_id`,`recorded_at`),
  ADD KEY `driver_locations_latitude_longitude_index` (`latitude`,`longitude`);

--
-- Indexes for table `driver_performance_scores`
--
ALTER TABLE `driver_performance_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `driver_performance_scores_driver_id_period_unique` (`driver_id`,`period`),
  ADD KEY `driver_performance_scores_overall_score_index` (`overall_score`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_queue_status_scheduled_at_index` (`status`,`scheduled_at`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_verifications_token_unique` (`token`),
  ADD KEY `email_verifications_email_verification_code_index` (`email`,`verification_code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_department_status_index` (`department`,`status`),
  ADD KEY `employees_status_hire_date_index` (`status`,`hire_date`),
  ADD KEY `employees_dept_status_hire` (`department`,`status`,`hire_date`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_attendance_employee_id_date_unique` (`employee_id`,`date`),
  ADD KEY `employee_attendance_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `employee_dashboard_overrides`
--
ALTER TABLE `employee_dashboard_overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `edo_employee_dashboard_unique` (`employee_id`,`dashboard_key`);

--
-- Indexes for table `employee_dashboard_permissions`
--
ALTER TABLE `employee_dashboard_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_employee_dashboard` (`employee_id`,`dashboard_key`),
  ADD KEY `idx_employee_dashboard_key` (`dashboard_key`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_documents_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_engagement_surveys`
--
ALTER TABLE `employee_engagement_surveys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_engagement_surveys_employee_id_survey_period_index` (`employee_id`,`survey_period`);

--
-- Indexes for table `employee_notes`
--
ALTER TABLE `employee_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_notes_author_id_foreign` (`author_id`),
  ADD KEY `employee_notes_employee_id_author_id_index` (`employee_id`,`author_id`);

--
-- Indexes for table `employee_skill`
--
ALTER TABLE `employee_skill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_skill_employee_id_skill_id_unique` (`employee_id`,`skill_id`),
  ADD KEY `employee_skill_skill_id_employee_id_index` (`skill_id`,`employee_id`);

--
-- Indexes for table `employee_training_records`
--
ALTER TABLE `employee_training_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_training_records_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `employee_training_records_expiry_date_index` (`expiry_date`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_approved_by_foreign` (`approved_by`),
  ADD KEY `expenses_store_id_status_index` (`store_id`,`status`),
  ADD KEY `expenses_department_category_index` (`department`,`category`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_login_attempts_user_id_attempted_at_index` (`user_id`,`attempted_at`),
  ADD KEY `failed_login_attempts_email_attempted_at_index` (`email`,`attempted_at`),
  ADD KEY `failed_login_attempts_ip_address_attempted_at_index` (`ip_address`,`attempted_at`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `favorites_product_id_index` (`product_id`);

--
-- Indexes for table `financial_forecasts`
--
ALTER TABLE `financial_forecasts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_forecasts_created_by_foreign` (`created_by`),
  ADD KEY `financial_forecasts_forecast_type_period_index` (`forecast_type`,`period`);

--
-- Indexes for table `financial_reconciliations`
--
ALTER TABLE `financial_reconciliations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_reconciliations_reconciled_by_foreign` (`reconciled_by`),
  ADD KEY `financial_reconciliations_reconciliation_date_status_index` (`reconciliation_date`,`status`),
  ADD KEY `financial_reconciliations_account_type_reconciliation_date_index` (`account_type`,`reconciliation_date`);

--
-- Indexes for table `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_reports_generated_by_foreign` (`generated_by`),
  ADD KEY `financial_reports_report_type_index` (`report_type`),
  ADD KEY `financial_reports_report_date_index` (`report_date`);

--
-- Indexes for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `financial_transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `financial_transactions_approved_by_foreign` (`approved_by`),
  ADD KEY `financial_transactions_type_status_index` (`type`,`status`),
  ADD KEY `financial_transactions_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `financial_transactions_store_id_type_index` (`store_id`,`type`),
  ADD KEY `financial_transactions_approval_status_created_at_index` (`approval_status`,`created_at`),
  ADD KEY `transactions_store_type_status` (`store_id`,`type`,`status`),
  ADD KEY `transactions_approval_date` (`approval_status`,`created_at`),
  ADD KEY `transactions_type_date` (`type`,`created_at`),
  ADD KEY `financial_transactions_order_id_index` (`order_id`),
  ADD KEY `financial_transactions_user_id_index` (`user_id`),
  ADD KEY `financial_transactions_store_id_index` (`store_id`),
  ADD KEY `financial_transactions_type_index` (`type`),
  ADD KEY `financial_transactions_status_index` (`status`),
  ADD KEY `financial_transactions_created_at_index` (`created_at`);

--
-- Indexes for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fiscal_periods_closed_by_foreign` (`closed_by`),
  ADD KEY `fiscal_periods_start_date_index` (`start_date`),
  ADD KEY `fiscal_periods_end_date_index` (`end_date`),
  ADD KEY `fiscal_periods_is_closed_index` (`is_closed`);

--
-- Indexes for table `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gifts_category_is_active_index` (`category`,`is_active`),
  ADD KEY `gifts_is_featured_is_active_index` (`is_featured`,`is_active`);

--
-- Indexes for table `gift_boxes`
--
ALTER TABLE `gift_boxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_fillers`
--
ALTER TABLE `gift_fillers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_ribbons`
--
ALTER TABLE `gift_ribbons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_wrappings`
--
ALTER TABLE `gift_wrappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_cases`
--
ALTER TABLE `hr_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_cases_employee_id_status_index` (`employee_id`,`status`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incidents_reported_by_foreign` (`reported_by`),
  ADD KEY `incidents_type_severity_status_index` (`type`,`severity`,`status`);

--
-- Indexes for table `incident_media`
--
ALTER TABLE `incident_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_media_incident_id_media_type_index` (`incident_id`,`media_type`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_reports_author_id_foreign` (`author_id`),
  ADD KEY `incident_reports_incident_id_author_id_index` (`incident_id`,`author_id`);

--
-- Indexes for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurance_claims_order_id_foreign` (`order_id`),
  ADD KEY `insurance_claims_delivery_assignment_id_foreign` (`delivery_assignment_id`),
  ADD KEY `insurance_claims_processed_by_foreign` (`processed_by`),
  ADD KEY `insurance_claims_status_submitted_at_index` (`status`,`submitted_at`),
  ADD KEY `insurance_claims_driver_id_status_index` (`driver_id`,`status`);

--
-- Indexes for table `inventory_alerts`
--
ALTER TABLE `inventory_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_alerts_product_id_is_resolved_index` (`product_id`,`is_resolved`),
  ADD KEY `inventory_alerts_alert_type_index` (`alert_type`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_product_id_action_index` (`product_id`,`action`),
  ADD KEY `inventory_logs_performed_by_created_at_index` (`performed_by`,`created_at`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_order_id_foreign` (`order_id`),
  ADD KEY `inventory_movements_created_by_foreign` (`created_by`),
  ADD KEY `inventory_movements_product_id_type_index` (`product_id`,`type`),
  ADD KEY `inventory_movements_product_id_index` (`product_id`),
  ADD KEY `inventory_movements_created_at_index` (`created_at`);

--
-- Indexes for table `inventory_shrinkage`
--
ALTER TABLE `inventory_shrinkage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_shrinkage_reported_by_foreign` (`reported_by`),
  ADD KEY `inventory_shrinkage_product_id_reported_at_index` (`product_id`,`reported_at`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_order_id_unique` (`order_id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_issued_at_index` (`issued_at`);

--
-- Indexes for table `ip_blacklists`
--
ALTER TABLE `ip_blacklists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_blacklists_ip_address_is_active_unique` (`ip_address`,`is_active`),
  ADD KEY `ip_blacklists_blocked_by_foreign` (`blocked_by`),
  ADD KEY `ip_blacklists_ip_address_index` (`ip_address`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applications_position_id_status_index` (`position_id`,`status`),
  ADD KEY `job_applications_applicant_email_status_index` (`applicant_email`,`status`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_positions_status_department_index` (`status`,`department`),
  ADD KEY `job_positions_hiring_manager_id_status_index` (`hiring_manager_id`,`status`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_entry_number_unique` (`entry_number`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`),
  ADD KEY `journal_entries_approved_by_foreign` (`approved_by`),
  ADD KEY `journal_entries_reversed_entry_id_foreign` (`reversed_entry_id`),
  ADD KEY `journal_entries_entry_number_index` (`entry_number`),
  ADD KEY `journal_entries_entry_date_index` (`entry_date`),
  ADD KEY `journal_entries_status_index` (`status`);

--
-- Indexes for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_lines_journal_entry_id_index` (`journal_entry_id`),
  ADD KEY `journal_entry_lines_account_id_index` (`account_id`);

--
-- Indexes for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leave_balances_employee_id_leave_type_year_unique` (`employee_id`,`leave_type`,`year`),
  ADD KEY `leave_balances_employee_id_year_index` (`employee_id`,`year`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_employee_id_foreign` (`employee_id`),
  ADD KEY `leave_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `notifications_type_created_at_index` (`type`,`created_at`);

--
-- Indexes for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_preferences_user_id_notification_type_unique` (`user_id`,`notification_type`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_templates_name_unique` (`name`),
  ADD KEY `notification_templates_type_channel_index` (`type`,`channel`);

--
-- Indexes for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `onboarding_tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `onboarding_tasks_employee_id_status_index` (`employee_id`,`status`);

--
-- Indexes for table `open_positions`
--
ALTER TABLE `open_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `open_positions_status_department_index` (`status`,`department`),
  ADD KEY `open_positions_hiring_manager_id_status_index` (`hiring_manager_id`,`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD UNIQUE KEY `orders_confirmation_token_unique` (`confirmation_token`),
  ADD UNIQUE KEY `orders_customer_id_idempotency_key_unique` (`customer_id`,`idempotency_key`),
  ADD KEY `orders_customer_id_status_index` (`customer_id`,`status`),
  ADD KEY `orders_store_id_status_index` (`store_id`,`status`),
  ADD KEY `orders_status_created_at_index` (`status`,`created_at`),
  ADD KEY `orders_payment_status_created_at_index` (`payment_status`,`created_at`),
  ADD KEY `orders_assigned_driver_id_foreign` (`assigned_driver_id`),
  ADD KEY `orders_assigned_by_foreign` (`assigned_by`),
  ADD KEY `orders_store_status_date` (`store_id`,`status`,`created_at`),
  ADD KEY `orders_payment_date` (`payment_status`,`created_at`),
  ADD KEY `orders_status_delivery` (`status`,`estimated_delivery`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_store_id_index` (`store_id`),
  ADD KEY `orders_created_at_index` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_product_id_index` (`order_id`,`product_id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_product_id_index` (`product_id`);

--
-- Indexes for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_revenue_records`
--
ALTER TABLE `order_revenue_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_revenue_records_order_id_unique` (`order_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organizations_slug_unique` (`slug`),
  ADD KEY `organizations_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_verification_code_index` (`email`,`verification_code`),
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_status_index` (`order_id`,`status`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payouts_requested_by_foreign` (`requested_by`),
  ADD KEY `payouts_processed_by_foreign` (`processed_by`),
  ADD KEY `payouts_store_id_status_index` (`store_id`,`status`),
  ADD KEY `payouts_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_employee_id_month_unique` (`employee_id`,`month`);

--
-- Indexes for table `payroll_adjustments`
--
ALTER TABLE `payroll_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_adjustments_employee_id_pay_period_type_index` (`employee_id`,`pay_period`,`type`);

--
-- Indexes for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_records_employee_id_pay_period_unique` (`employee_id`,`pay_period`),
  ADD KEY `payroll_records_approved_by_foreign` (`approved_by`),
  ADD KEY `payroll_records_pay_period_status_index` (`pay_period`,`status`);

--
-- Indexes for table `performance_bonuses`
--
ALTER TABLE `performance_bonuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `performance_bonuses_employee_id_period_unique` (`employee_id`,`period`),
  ADD KEY `performance_bonuses_granted_by_foreign` (`granted_by`),
  ADD KEY `performance_bonuses_granted_at_index` (`granted_at`);

--
-- Indexes for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `performance_metrics_metric_name_metric_type_metric_date_unique` (`metric_name`,`metric_type`,`metric_date`),
  ADD KEY `performance_metrics_metric_name_metric_date_index` (`metric_name`,`metric_date`);

--
-- Indexes for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performance_reviews_employee_id_foreign` (`employee_id`),
  ADD KEY `performance_reviews_reviewer_id_foreign` (`reviewer_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD KEY `permissions_category_name_index` (`category`,`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `price_histories`
--
ALTER TABLE `price_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_histories_product_id_changed_at_index` (`product_id`,`changed_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_store_id_status_index` (`store_id`,`status`),
  ADD KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  ADD KEY `products_sku_is_active_index` (`sku`,`is_active`),
  ADD KEY `products_stock_quantity_low_stock_threshold_index` (`stock_quantity`,`low_stock_threshold`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_is_active_index` (`is_active`),
  ADD KEY `products_store_active_date` (`store_id`,`is_active`,`created_at`),
  ADD KEY `products_stock_levels` (`stock_quantity`,`low_stock_threshold`),
  ADD KEY `products_stock_quantity_index` (`stock_quantity`),
  ADD KEY `idx_products_trader` (`trader_id`),
  ADD KEY `products_market_index` (`market`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attributes_product_custom_idx` (`product_id`,`is_custom`),
  ADD KEY `product_attributes_key_text_idx` (`attribute_key`,`value_text`),
  ADD KEY `product_attributes_key_number_idx` (`attribute_key`,`value_number`),
  ADD KEY `product_attributes_key_date_idx` (`attribute_key`,`value_date`);

--
-- Indexes for table `product_performance_metrics`
--
ALTER TABLE `product_performance_metrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_performance_metrics_product_id_metric_date_unique` (`product_id`,`metric_date`),
  ADD KEY `product_performance_metrics_metric_date_index` (`metric_date`),
  ADD KEY `product_performance_metrics_product_id_index` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `profit_loss_statements`
--
ALTER TABLE `profit_loss_statements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profit_loss_statements_period_type_period_unique` (`period_type`,`period`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `purchase_orders_store_id_status_index` (`store_id`,`status`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_items_purchase_order_id_product_id_index` (`purchase_order_id`,`product_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_order_id_index` (`order_id`),
  ADD KEY `refunds_user_id_index` (`user_id`),
  ADD KEY `refunds_approved_by_index` (`approved_by`),
  ADD KEY `refunds_status_index` (`status`);

--
-- Indexes for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_requests_processed_by_foreign` (`processed_by`),
  ADD KEY `refund_requests_order_id_status_index` (`order_id`,`status`),
  ADD KEY `refund_requests_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_is_approved_created_at_index` (`product_id`,`is_approved`,`created_at`),
  ADD KEY `reviews_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD KEY `roles_name_is_system_role_index` (`name`,`is_system_role`);

--
-- Indexes for table `route_optimizations`
--
ALTER TABLE `route_optimizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_optimizations_driver_id_status_index` (`driver_id`,`status`),
  ADD KEY `route_optimizations_optimization_date_index` (`optimization_date`);

--
-- Indexes for table `salary_receipts`
--
ALTER TABLE `salary_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_receipts_employee_id_pay_period_index` (`employee_id`,`pay_period`);

--
-- Indexes for table `sales_forecasts`
--
ALTER TABLE `sales_forecasts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_forecasts_store_id_forecast_period_index` (`store_id`,`forecast_period`),
  ADD KEY `sales_forecasts_product_id_forecast_period_index` (`product_id`,`forecast_period`);

--
-- Indexes for table `scheduled_tasks`
--
ALTER TABLE `scheduled_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheduled_tasks_status_index` (`status`),
  ADD KEY `scheduled_tasks_is_enabled_index` (`is_enabled`),
  ADD KEY `scheduled_tasks_next_run_at_index` (`next_run_at`);

--
-- Indexes for table `search_logs`
--
ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `search_logs_no_results_created_at_index` (`no_results`,`created_at`),
  ADD KEY `search_logs_user_id_index` (`user_id`),
  ADD KEY `search_logs_no_results_index` (`no_results`),
  ADD KEY `search_logs_created_at_index` (`created_at`);

--
-- Indexes for table `security_audit_logs`
--
ALTER TABLE `security_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_audit_logs_user_type_user_id_index` (`user_type`,`user_id`),
  ADD KEY `security_audit_logs_event_type_created_at_index` (`event_type`,`created_at`),
  ADD KEY `security_audit_logs_user_type_user_id_created_at_index` (`user_type`,`user_id`,`created_at`),
  ADD KEY `security_audit_logs_risk_level_index` (`risk_level`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_employee_id_shift_date_index` (`employee_id`,`shift_date`),
  ADD KEY `shifts_shift_date_status_index` (`shift_date`,`status`),
  ADD KEY `shifts_date_status` (`shift_date`,`status`),
  ADD KEY `shifts_employee_date_status` (`employee_id`,`shift_date`,`status`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skills_name_unique` (`name`),
  ADD KEY `skills_type_is_active_index` (`type`,`is_active`);

--
-- Indexes for table `slow_queries`
--
ALTER TABLE `slow_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slow_queries_severity_index` (`severity`),
  ADD KEY `slow_queries_is_optimized_index` (`is_optimized`),
  ADD KEY `slow_queries_execution_time_index` (`execution_time`),
  ADD KEY `slow_queries_last_seen_at_index` (`last_seen_at`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stores_slug_unique` (`slug`),
  ADD KEY `stores_owner_id_status_index` (`owner_id`,`status`),
  ADD KEY `stores_organization_id_status_index` (`organization_id`,`status`),
  ADD KEY `stores_user_id_index` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_status_name_index` (`status`,`name`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_tickets_ticket_number_unique` (`ticket_number`),
  ADD KEY `support_tickets_related_order_id_foreign` (`order_id`),
  ADD KEY `support_tickets_status_priority_index` (`status`,`priority`),
  ADD KEY `support_tickets_assigned_to_status_index` (`assigned_to`,`status`),
  ADD KEY `support_tickets_customer_id_status_index` (`user_id`,`status`),
  ADD KEY `support_tickets_related_order_id_index` (`related_order_id`),
  ADD KEY `support_tickets_user_id_index` (`user_id`),
  ADD KEY `support_tickets_status_index` (`status`),
  ADD KEY `support_tickets_priority_index` (`priority`),
  ADD KEY `support_tickets_created_at_index` (`created_at`);

--
-- Indexes for table `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_ticket_replies_author_type_author_id_index` (`author_type`,`author_id`),
  ADD KEY `support_ticket_replies_ticket_id_foreign` (`ticket_id`);

--
-- Indexes for table `system_alerts`
--
ALTER TABLE `system_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_alerts_resolved_by_foreign` (`resolved_by`),
  ADD KEY `system_alerts_type_index` (`type`),
  ADD KEY `system_alerts_priority_index` (`priority`),
  ADD KEY `system_alerts_is_read_index` (`is_read`),
  ADD KEY `system_alerts_is_resolved_index` (`is_resolved`),
  ADD KEY `system_alerts_created_at_index` (`created_at`);

--
-- Indexes for table `system_backups`
--
ALTER TABLE `system_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_backups_created_by_foreign` (`created_by`),
  ADD KEY `system_backups_type_status_index` (`type`,`status`);

--
-- Indexes for table `system_errors`
--
ALTER TABLE `system_errors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_errors_resolved_by_foreign` (`resolved_by`),
  ADD KEY `system_errors_severity_occurred_at_index` (`severity`,`occurred_at`),
  ADD KEY `system_errors_resolved_resolved_at_index` (`resolved`,`resolved_at`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_logs_level_created_at_index` (`level`,`created_at`),
  ADD KEY `system_logs_channel_created_at_index` (`channel`,`created_at`),
  ADD KEY `system_logs_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `system_resources`
--
ALTER TABLE `system_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_resources_resource_type_server_name_recorded_at_index` (`resource_type`,`server_name`,`recorded_at`);

--
-- Indexes for table `system_services`
--
ALTER TABLE `system_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_services_status_last_check_index` (`status`,`last_check`),
  ADD KEY `system_services_type_status_index` (`type`,`status`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`),
  ADD KEY `system_settings_key_is_public_index` (`key`,`is_public`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_calculations_transaction_id_foreign` (`transaction_id`),
  ADD KEY `tax_calculations_order_id_tax_type_index` (`order_id`,`tax_type`),
  ADD KEY `tax_calculations_tax_type_created_at_index` (`tax_type`,`created_at`);

--
-- Indexes for table `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `traders_user_id_unique` (`user_id`),
  ADD KEY `traders_status_index` (`status`);

--
-- Indexes for table `trader_analytics_daily`
--
ALTER TABLE `trader_analytics_daily`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_trader_date` (`trader_id`,`date`),
  ADD KEY `idx_trader_analytics_daily_trader` (`trader_id`),
  ADD KEY `idx_trader_analytics_daily_date` (`date`);

--
-- Indexes for table `trader_orders`
--
ALTER TABLE `trader_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trader_orders_trader_id_order_id_unique` (`trader_id`,`order_id`),
  ADD KEY `trader_orders_order_id_foreign` (`order_id`),
  ADD KEY `trader_orders_status_index` (`status`),
  ADD KEY `trader_orders_created_at_index` (`created_at`);

--
-- Indexes for table `trader_payouts`
--
ALTER TABLE `trader_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trader_payouts_trader_id_foreign` (`trader_id`),
  ADD KEY `trader_payouts_processed_by_foreign` (`processed_by`),
  ADD KEY `trader_payouts_status_index` (`status`),
  ADD KEY `trader_payouts_created_at_index` (`created_at`);

--
-- Indexes for table `trader_products`
--
ALTER TABLE `trader_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trader_products_trader_id_product_id_unique` (`trader_id`,`product_id`),
  ADD KEY `trader_products_product_id_foreign` (`product_id`),
  ADD KEY `trader_products_status_index` (`status`);

--
-- Indexes for table `trader_reports`
--
ALTER TABLE `trader_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trader_reports_responded_by_foreign` (`responded_by`),
  ADD KEY `idx_trader_reports_trader` (`trader_id`),
  ADD KEY `idx_trader_reports_status` (`status`),
  ADD KEY `idx_trader_reports_type` (`report_type`);

--
-- Indexes for table `trader_support_messages`
--
ALTER TABLE `trader_support_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trader_support_messages_sender_id_foreign` (`sender_id`),
  ADD KEY `idx_trader_support_messages_ticket` (`ticket_id`);

--
-- Indexes for table `trader_support_tickets`
--
ALTER TABLE `trader_support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_trader_support_tickets_trader` (`trader_id`),
  ADD KEY `idx_trader_support_tickets_status` (`status`),
  ADD KEY `idx_trader_support_tickets_assigned` (`assigned_to`);

--
-- Indexes for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `training_assignments_employee_id_status_index` (`employee_id`,`status`),
  ADD KEY `training_assignments_due_date_index` (`due_date`);

--
-- Indexes for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `training_enrollments_training_program_id_employee_id_unique` (`training_program_id`,`employee_id`),
  ADD KEY `training_enrollments_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `training_programs`
--
ALTER TABLE `training_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activity_session_id_created_at_index` (`session_id`,`created_at`),
  ADD KEY `user_activity_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `user_activity_session_id_index` (`session_id`),
  ADD KEY `user_activity_user_id_index` (`user_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_preferences_session_id_unique` (`session_id`),
  ADD UNIQUE KEY `user_preferences_user_id_unique` (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`),
  ADD KEY `user_roles_assigned_by_foreign` (`assigned_by`),
  ADD KEY `user_roles_user_id_is_active_index` (`user_id`,`is_active`);

--
-- Indexes for table `user_saved_cards`
--
ALTER TABLE `user_saved_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_saved_cards_user_id_foreign` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`),
  ADD UNIQUE KEY `vehicles_vin_unique` (`vin`);

--
-- Indexes for table `vehicle_maintenance`
--
ALTER TABLE `vehicle_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_maintenance_driver_id_maintenance_date_index` (`driver_id`,`maintenance_date`),
  ADD KEY `vehicle_maintenance_type_status_index` (`type`,`status`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_feeds`
--
ALTER TABLE `activity_feeds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `administrative_approvals`
--
ALTER TABLE `administrative_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `alert_rules`
--
ALTER TABLE `alert_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_errors`
--
ALTER TABLE `api_errors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cash_flow_records`
--
ALTER TABLE `cash_flow_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1047;

--
-- AUTO_INCREMENT for table `category_attribute_definitions`
--
ALTER TABLE `category_attribute_definitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commission_rates`
--
ALTER TABLE `commission_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compliance_documents`
--
ALTER TABLE `compliance_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_balance_audits`
--
ALTER TABLE `customer_balance_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_gifts`
--
ALTER TABLE `custom_gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_gift_items`
--
ALTER TABLE `custom_gift_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_analytics`
--
ALTER TABLE `daily_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_cache`
--
ALTER TABLE `dashboard_cache`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_notifications`
--
ALTER TABLE `dashboard_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `dashboard_quick_actions`
--
ALTER TABLE `dashboard_quick_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_role_permissions`
--
ALTER TABLE `dashboard_role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `database_backups`
--
ALTER TABLE `database_backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_proofs`
--
ALTER TABLE `delivery_proofs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_ratings`
--
ALTER TABLE `delivery_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_routes`
--
ALTER TABLE `delivery_routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_zone_analytics`
--
ALTER TABLE `delivery_zone_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployment_history`
--
ALTER TABLE `deployment_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `driver_locations`
--
ALTER TABLE `driver_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_performance_scores`
--
ALTER TABLE `driver_performance_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_dashboard_overrides`
--
ALTER TABLE `employee_dashboard_overrides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_dashboard_permissions`
--
ALTER TABLE `employee_dashboard_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_engagement_surveys`
--
ALTER TABLE `employee_engagement_surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_notes`
--
ALTER TABLE `employee_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_skill`
--
ALTER TABLE `employee_skill`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_training_records`
--
ALTER TABLE `employee_training_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `financial_forecasts`
--
ALTER TABLE `financial_forecasts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_reconciliations`
--
ALTER TABLE `financial_reconciliations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `gift_boxes`
--
ALTER TABLE `gift_boxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gift_fillers`
--
ALTER TABLE `gift_fillers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gift_ribbons`
--
ALTER TABLE `gift_ribbons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gift_wrappings`
--
ALTER TABLE `gift_wrappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_cases`
--
ALTER TABLE `hr_cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_media`
--
ALTER TABLE `incident_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_alerts`
--
ALTER TABLE `inventory_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `inventory_shrinkage`
--
ALTER TABLE `inventory_shrinkage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ip_blacklists`
--
ALTER TABLE `ip_blacklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_balances`
--
ALTER TABLE `leave_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `open_positions`
--
ALTER TABLE `open_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_revenue_records`
--
ALTER TABLE `order_revenue_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_adjustments`
--
ALTER TABLE `payroll_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_records`
--
ALTER TABLE `payroll_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `performance_bonuses`
--
ALTER TABLE `performance_bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_metrics`
--
ALTER TABLE `performance_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `price_histories`
--
ALTER TABLE `price_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1156;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `product_performance_metrics`
--
ALTER TABLE `product_performance_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `profit_loss_statements`
--
ALTER TABLE `profit_loss_statements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `refund_requests`
--
ALTER TABLE `refund_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route_optimizations`
--
ALTER TABLE `route_optimizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salary_receipts`
--
ALTER TABLE `salary_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_forecasts`
--
ALTER TABLE `sales_forecasts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_tasks`
--
ALTER TABLE `scheduled_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_logs`
--
ALTER TABLE `search_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `security_audit_logs`
--
ALTER TABLE `security_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slow_queries`
--
ALTER TABLE `slow_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_alerts`
--
ALTER TABLE `system_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_backups`
--
ALTER TABLE `system_backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_errors`
--
ALTER TABLE `system_errors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `system_resources`
--
ALTER TABLE `system_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_services`
--
ALTER TABLE `system_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trader_analytics_daily`
--
ALTER TABLE `trader_analytics_daily`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_orders`
--
ALTER TABLE `trader_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_payouts`
--
ALTER TABLE `trader_payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_products`
--
ALTER TABLE `trader_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_reports`
--
ALTER TABLE `trader_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_support_messages`
--
ALTER TABLE `trader_support_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trader_support_tickets`
--
ALTER TABLE `trader_support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_assignments`
--
ALTER TABLE `training_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_programs`
--
ALTER TABLE `training_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_saved_cards`
--
ALTER TABLE `user_saved_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_maintenance`
--
ALTER TABLE `vehicle_maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `administrative_approvals`
--
ALTER TABLE `administrative_approvals`
  ADD CONSTRAINT `administrative_approvals_decided_by_employee_id_foreign` FOREIGN KEY (`decided_by_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `administrative_approvals_requester_employee_id_foreign` FOREIGN KEY (`requester_employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_account_id_foreign` FOREIGN KEY (`parent_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `commission_rates`
--
ALTER TABLE `commission_rates`
  ADD CONSTRAINT `commission_rates_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usage`
--
ALTER TABLE `coupon_usage`
  ADD CONSTRAINT `coupon_usage_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `discount_coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupon_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_balance_audits`
--
ALTER TABLE `customer_balance_audits`
  ADD CONSTRAINT `customer_balance_audits_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_balance_audits_support_user_id_foreign` FOREIGN KEY (`support_user_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD CONSTRAINT `customer_feedback_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_feedback_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_gifts`
--
ALTER TABLE `custom_gifts`
  ADD CONSTRAINT `custom_gifts_gift_box_id_foreign` FOREIGN KEY (`gift_box_id`) REFERENCES `gift_boxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_gifts_gift_card_id_foreign` FOREIGN KEY (`gift_card_id`) REFERENCES `gift_cards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `custom_gifts_gift_ribbon_id_foreign` FOREIGN KEY (`gift_ribbon_id`) REFERENCES `gift_ribbons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `custom_gifts_gift_wrapping_id_foreign` FOREIGN KEY (`gift_wrapping_id`) REFERENCES `gift_wrappings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `custom_gifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `custom_gift_items`
--
ALTER TABLE `custom_gift_items`
  ADD CONSTRAINT `custom_gift_items_custom_gift_id_foreign` FOREIGN KEY (`custom_gift_id`) REFERENCES `custom_gifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_gift_items_gift_filler_id_foreign` FOREIGN KEY (`gift_filler_id`) REFERENCES `gift_fillers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD CONSTRAINT `delivery_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_assignments_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_assignments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  ADD CONSTRAINT `delivery_attempts_delivery_assignment_id_foreign` FOREIGN KEY (`delivery_assignment_id`) REFERENCES `delivery_assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_proofs`
--
ALTER TABLE `delivery_proofs`
  ADD CONSTRAINT `delivery_proofs_delivery_assignment_id_foreign` FOREIGN KEY (`delivery_assignment_id`) REFERENCES `delivery_assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_ratings`
--
ALTER TABLE `delivery_ratings`
  ADD CONSTRAINT `delivery_ratings_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_ratings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_routes`
--
ALTER TABLE `delivery_routes`
  ADD CONSTRAINT `delivery_routes_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deployment_history`
--
ALTER TABLE `deployment_history`
  ADD CONSTRAINT `deployment_history_deployed_by_foreign` FOREIGN KEY (`deployed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deployment_logs`
--
ALTER TABLE `deployment_logs`
  ADD CONSTRAINT `deployment_logs_deployed_by_foreign` FOREIGN KEY (`deployed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD CONSTRAINT `discount_codes_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD CONSTRAINT `discount_coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `discount_coupons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drivers_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `driver_locations`
--
ALTER TABLE `driver_locations`
  ADD CONSTRAINT `driver_locations_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `driver_performance_scores`
--
ALTER TABLE `driver_performance_scores`
  ADD CONSTRAINT `driver_performance_scores_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD CONSTRAINT `employee_attendance_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_dashboard_overrides`
--
ALTER TABLE `employee_dashboard_overrides`
  ADD CONSTRAINT `employee_dashboard_overrides_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_dashboard_permissions`
--
ALTER TABLE `employee_dashboard_permissions`
  ADD CONSTRAINT `employee_dashboard_permissions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD CONSTRAINT `employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_engagement_surveys`
--
ALTER TABLE `employee_engagement_surveys`
  ADD CONSTRAINT `employee_engagement_surveys_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_notes`
--
ALTER TABLE `employee_notes`
  ADD CONSTRAINT `employee_notes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_notes_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_skill`
--
ALTER TABLE `employee_skill`
  ADD CONSTRAINT `employee_skill_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_skill_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_training_records`
--
ALTER TABLE `employee_training_records`
  ADD CONSTRAINT `employee_training_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD CONSTRAINT `failed_login_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_forecasts`
--
ALTER TABLE `financial_forecasts`
  ADD CONSTRAINT `financial_forecasts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `financial_reconciliations`
--
ALTER TABLE `financial_reconciliations`
  ADD CONSTRAINT `financial_reconciliations_reconciled_by_foreign` FOREIGN KEY (`reconciled_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD CONSTRAINT `financial_reports_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD CONSTRAINT `financial_transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `financial_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_transactions_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fiscal_periods`
--
ALTER TABLE `fiscal_periods`
  ADD CONSTRAINT `fiscal_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_cases`
--
ALTER TABLE `hr_cases`
  ADD CONSTRAINT `hr_cases_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `incident_media`
--
ALTER TABLE `incident_media`
  ADD CONSTRAINT `incident_media_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `incident_reports_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_reports_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `insurance_claims`
--
ALTER TABLE `insurance_claims`
  ADD CONSTRAINT `insurance_claims_delivery_assignment_id_foreign` FOREIGN KEY (`delivery_assignment_id`) REFERENCES `delivery_assignments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `insurance_claims_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `insurance_claims_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `insurance_claims_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_alerts`
--
ALTER TABLE `inventory_alerts`
  ADD CONSTRAINT `inventory_alerts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_shrinkage`
--
ALTER TABLE `inventory_shrinkage`
  ADD CONSTRAINT `inventory_shrinkage_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_shrinkage_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ip_blacklists`
--
ALTER TABLE `ip_blacklists`
  ADD CONSTRAINT `ip_blacklists_blocked_by_foreign` FOREIGN KEY (`blocked_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `job_positions_hiring_manager_id_foreign` FOREIGN KEY (`hiring_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_entries_reversed_entry_id_foreign` FOREIGN KEY (`reversed_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD CONSTRAINT `journal_entry_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_balances`
--
ALTER TABLE `leave_balances`
  ADD CONSTRAINT `leave_balances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  ADD CONSTRAINT `onboarding_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `onboarding_tasks_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `open_positions`
--
ALTER TABLE `open_positions`
  ADD CONSTRAINT `open_positions_hiring_manager_id_foreign` FOREIGN KEY (`hiring_manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_assigned_driver_id_foreign` FOREIGN KEY (`assigned_driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_revenue_records`
--
ALTER TABLE `order_revenue_records`
  ADD CONSTRAINT `order_revenue_records_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payouts_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payouts_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_adjustments`
--
ALTER TABLE `payroll_adjustments`
  ADD CONSTRAINT `payroll_adjustments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_records`
--
ALTER TABLE `payroll_records`
  ADD CONSTRAINT `payroll_records_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payroll_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `performance_bonuses`
--
ALTER TABLE `performance_bonuses`
  ADD CONSTRAINT `performance_bonuses_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `performance_bonuses_granted_by_foreign` FOREIGN KEY (`granted_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD CONSTRAINT `performance_reviews_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `performance_reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_performance_metrics`
--
ALTER TABLE `product_performance_metrics`
  ADD CONSTRAINT `product_performance_metrics_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refund_requests`
--
ALTER TABLE `refund_requests`
  ADD CONSTRAINT `refund_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `refund_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `route_optimizations`
--
ALTER TABLE `route_optimizations`
  ADD CONSTRAINT `route_optimizations_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_forecasts`
--
ALTER TABLE `sales_forecasts`
  ADD CONSTRAINT `sales_forecasts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_forecasts_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `search_logs`
--
ALTER TABLE `search_logs`
  ADD CONSTRAINT `search_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `shifts_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stores_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_customer_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_tickets_related_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  ADD CONSTRAINT `support_ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `system_alerts`
--
ALTER TABLE `system_alerts`
  ADD CONSTRAINT `system_alerts_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `system_backups`
--
ALTER TABLE `system_backups`
  ADD CONSTRAINT `system_backups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `system_errors`
--
ALTER TABLE `system_errors`
  ADD CONSTRAINT `system_errors_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  ADD CONSTRAINT `tax_calculations_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tax_calculations_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `financial_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `traders`
--
ALTER TABLE `traders`
  ADD CONSTRAINT `traders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `trader_analytics_daily`
--
ALTER TABLE `trader_analytics_daily`
  ADD CONSTRAINT `trader_analytics_daily_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_orders`
--
ALTER TABLE `trader_orders`
  ADD CONSTRAINT `trader_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trader_orders_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_payouts`
--
ALTER TABLE `trader_payouts`
  ADD CONSTRAINT `trader_payouts_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trader_payouts_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_products`
--
ALTER TABLE `trader_products`
  ADD CONSTRAINT `trader_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trader_products_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_reports`
--
ALTER TABLE `trader_reports`
  ADD CONSTRAINT `trader_reports_responded_by_foreign` FOREIGN KEY (`responded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trader_reports_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_support_messages`
--
ALTER TABLE `trader_support_messages`
  ADD CONSTRAINT `trader_support_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trader_support_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `trader_support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trader_support_tickets`
--
ALTER TABLE `trader_support_tickets`
  ADD CONSTRAINT `trader_support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `trader_support_tickets_trader_id_foreign` FOREIGN KEY (`trader_id`) REFERENCES `traders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_assignments`
--
ALTER TABLE `training_assignments`
  ADD CONSTRAINT `training_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `training_assignments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_enrollments`
--
ALTER TABLE `training_enrollments`
  ADD CONSTRAINT `training_enrollments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_enrollments_training_program_id_foreign` FOREIGN KEY (`training_program_id`) REFERENCES `training_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_saved_cards`
--
ALTER TABLE `user_saved_cards`
  ADD CONSTRAINT `user_saved_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_maintenance`
--
ALTER TABLE `vehicle_maintenance`
  ADD CONSTRAINT `vehicle_maintenance_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
