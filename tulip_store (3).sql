-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 09:42 AM
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
(1, 2, '2026-01-12', '08:37:02', NULL, NULL, 0, 'present', NULL, '2026-01-12 05:37:02', '2026-01-12 05:37:02'),
(2, 1, '2026-02-02', '07:51:30', '07:51:34', 0, 0, 'present', NULL, '2026-02-02 04:51:30', '2026-02-02 04:51:34'),
(3, 1, '2026-02-04', '05:41:28', '05:41:34', 0, 0, 'present', NULL, '2026-02-04 02:41:28', '2026-02-04 02:41:34'),
(9, 1, '2026-02-04', '09:25:43', '09:25:47', 0, 0, 'present', NULL, '2026-02-04 06:25:43', '2026-02-04 06:25:47'),
(10, 1, '2026-02-04', '09:25:52', '09:25:56', 0, 0, 'present', NULL, '2026-02-04 06:25:52', '2026-02-04 06:25:56');

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
(33, 5, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 14:02:42', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(34, 5, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"pending\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 14:02:42', NULL, '{\"actor\":\"cs\"}'),
(35, 1, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:16:18', NULL, NULL),
(36, 1, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"pending\"}', '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:16:29', NULL, NULL),
(37, 1, 'order_status_update', 'Order', 4, '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '{\"status\":\"confirmed\",\"payment_status\":\"paid\"}', '127.0.0.1', NULL, NULL, '2026-03-09 15:17:06', NULL, NULL),
(38, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:37', NULL, NULL),
(39, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:54', NULL, NULL),
(40, NULL, 'export', 'pdf_export', NULL, NULL, '{\"record_count\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-09 17:57:59', NULL, NULL),
(42, 5, 'order_status_changed', 'App\\Models\\Order', 4, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:17', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(43, 1, 'status_transition', 'App\\Models\\Order', 4, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:17', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-10T05:37:17.876005Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(44, 1, 'driver_assigned', 'App\\Models\\Order', 4, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"1\",\"assignment_id\":1}', NULL, NULL, NULL, '2026-03-10 02:37:18', NULL, NULL),
(45, 1, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 02:37:18', NULL, NULL),
(49, 1, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"failed\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:34:16', NULL, NULL),
(50, 5, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(51, 1, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"pending\"}', '{\"status\":\"confirmed\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"pending\",\"to\":\"confirmed\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:25.326779Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(52, 5, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:25', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(53, 1, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"confirmed\"}', '{\"status\":\"out_for_delivery\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:26', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"confirmed\",\"to\":\"out_for_delivery\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:26.034782Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(54, 1, 'driver_assigned', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"out_for_delivery\",\"driver_id\":\"1\",\"assignment_id\":2}', NULL, NULL, NULL, '2026-03-10 03:41:26', NULL, NULL),
(55, 1, 'driver_assignment_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"driver_assignment_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:26', NULL, NULL),
(56, 5, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:32', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(57, 1, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:32', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"out_for_delivery\",\"to\":\"delivered\",\"admin_override\":false,\"timestamp\":\"2026-03-10T06:41:32.231058Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}'),
(58, 1, 'order_completed', 'App\\Models\\Order', 6, '{\"status\":\"out_for_delivery\"}', '{\"status\":\"delivered\",\"commission_transaction_id\":17}', NULL, NULL, NULL, '2026-03-10 03:41:32', NULL, NULL),
(59, 1, 'order_completion_flow', 'Transaction', NULL, NULL, '{\"status\":\"success\",\"operation\":\"order_completion_flow\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:41:33', NULL, NULL),
(60, 5, 'order_status_changed', 'App\\Models\\Order', 6, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"observer\",\"guard\":\"employee\"}'),
(61, 5, 'financial_transaction_updated', 'App\\Models\\FinancialTransaction', 15, '{\"status\":\"pending\",\"amount\":\"161.16\",\"currency\":\"SYP\"}', '{\"status\":\"completed\",\"amount\":\"161.16\",\"currency\":\"USD\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"order_finalization\",\"order_id\":6}'),
(62, 5, 'order_revenue_recorded', 'App\\Models\\Order', 6, NULL, '{\"order_id\":6,\"amount\":\"161.16\",\"currency\":\"USD\",\"financial_transaction_id\":15}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, NULL),
(63, 5, 'store_sales_incremented', 'App\\Models\\Order', 6, NULL, '{\"store_id\":1,\"amount\":161.16,\"source\":\"order_finalization\",\"order_id\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, NULL),
(64, 5, 'order_marked_completed', 'App\\Models\\Order', 6, '{\"is_completed\":0,\"completed_at\":null,\"revenue_recognized_at\":null}', '{\"is_completed\":true,\"completed_at\":\"2026-03-10T06:42:50.370755Z\",\"revenue_recognized_at\":\"2026-03-10T06:42:50.387021Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"source\":\"order_finalization\"}'),
(65, 1, 'status_transition', 'App\\Models\\Order', 6, '{\"status\":\"delivered\"}', '{\"status\":\"done\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 03:42:50', NULL, '{\"transition\":{\"entity_type\":\"order\",\"status_field\":\"status\",\"from\":\"delivered\",\"to\":\"done\",\"admin_override\":true,\"timestamp\":\"2026-03-10T06:42:50.420590Z\"},\"actor\":{\"guard\":\"employee\",\"employee_id\":1,\"employee_email\":\"admin@tulipstore.com\",\"employee_code\":\"EMP001\"}}');

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
('tulip-store-cache-a75f3f172bfb296f2e10cbfc6dfc1883', 'i:5;', 1773130507),
('tulip-store-cache-a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1773130507;', 1773130507);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tulip-store-cache-admin_global_metrics', 'a:29:{s:11:\"total_users\";i:7;s:12:\"active_users\";i:7;s:12:\"total_stores\";i:1;s:13:\"active_stores\";i:1;s:13:\"total_revenue\";d:320.84;s:13:\"revenue_today\";d:161.16;s:15:\"monthly_revenue\";d:320.84;s:16:\"total_commission\";i:0;s:18:\"monthly_commission\";i:0;s:12:\"total_orders\";i:5;s:14:\"monthly_orders\";i:3;s:14:\"pending_orders\";i:1;s:13:\"active_orders\";i:4;s:15:\"avg_order_value\";d:160.42;s:14:\"total_products\";i:103;s:15:\"active_products\";i:103;s:16:\"low_stock_alerts\";i:89;s:18:\"low_stock_products\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:10:{i:0;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1058;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 3\";s:4:\"slug\";s:21:\"electronics-product-3\";s:11:\"description\";s:37:\"Electronics Description for product 3\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-003\";s:5:\"price\";s:6:\"122.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:137;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:34:\"[\"\\/images\\/category\\/2.3tap.jpg\"]\";s:6:\"rating\";i:3;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1058;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 3\";s:4:\"slug\";s:21:\"electronics-product-3\";s:11:\"description\";s:37:\"Electronics Description for product 3\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-003\";s:5:\"price\";s:6:\"122.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:137;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:34:\"[\"\\/images\\/category\\/2.3tap.jpg\"]\";s:6:\"rating\";i:3;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1059;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 4\";s:4:\"slug\";s:21:\"electronics-product-4\";s:11:\"description\";s:37:\"Electronics Description for product 4\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-004\";s:5:\"price\";s:6:\"270.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:214;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:41:\"[\"\\/images\\/category\\/2.4smartWatch.jpg\"]\";s:6:\"rating\";i:5;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1059;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 4\";s:4:\"slug\";s:21:\"electronics-product-4\";s:11:\"description\";s:37:\"Electronics Description for product 4\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-004\";s:5:\"price\";s:6:\"270.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:214;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:41:\"[\"\\/images\\/category\\/2.4smartWatch.jpg\"]\";s:6:\"rating\";i:5;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1060;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 5\";s:4:\"slug\";s:21:\"electronics-product-5\";s:11:\"description\";s:37:\"Electronics Description for product 5\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-005\";s:5:\"price\";s:5:\"79.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:241;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:38:\"[\"\\/images\\/category\\/2.5earbuds.jpg\"]\";s:6:\"rating\";i:1;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1060;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 5\";s:4:\"slug\";s:21:\"electronics-product-5\";s:11:\"description\";s:37:\"Electronics Description for product 5\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-005\";s:5:\"price\";s:5:\"79.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:241;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:38:\"[\"\\/images\\/category\\/2.5earbuds.jpg\"]\";s:6:\"rating\";i:1;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1061;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 6\";s:4:\"slug\";s:21:\"electronics-product-6\";s:11:\"description\";s:37:\"Electronics Description for product 6\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-006\";s:5:\"price\";s:6:\"111.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:186;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:33:\"[\"\\/images\\/category\\/2.6TV.jpg\"]\";s:6:\"rating\";i:1;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1061;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 6\";s:4:\"slug\";s:21:\"electronics-product-6\";s:11:\"description\";s:37:\"Electronics Description for product 6\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-006\";s:5:\"price\";s:6:\"111.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:186;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:33:\"[\"\\/images\\/category\\/2.6TV.jpg\"]\";s:6:\"rating\";i:1;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1062;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 7\";s:4:\"slug\";s:21:\"electronics-product-7\";s:11:\"description\";s:37:\"Electronics Description for product 7\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-007\";s:5:\"price\";s:6:\"187.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:170;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:38:\"[\"\\/images\\/category\\/2.7cameras.jpg\"]\";s:6:\"rating\";i:2;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1062;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 7\";s:4:\"slug\";s:21:\"electronics-product-7\";s:11:\"description\";s:37:\"Electronics Description for product 7\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-007\";s:5:\"price\";s:6:\"187.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:170;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:38:\"[\"\\/images\\/category\\/2.7cameras.jpg\"]\";s:6:\"rating\";i:2;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1063;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 8\";s:4:\"slug\";s:21:\"electronics-product-8\";s:11:\"description\";s:37:\"Electronics Description for product 8\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-008\";s:5:\"price\";s:6:\"126.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:194;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:37:\"[\"\\/images\\/category\\/2.1phone.jpeg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1063;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1030;s:4:\"name\";s:21:\"Electronics Product 8\";s:4:\"slug\";s:21:\"electronics-product-8\";s:11:\"description\";s:37:\"Electronics Description for product 8\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:15:\"ELECTRONICS-008\";s:5:\"price\";s:6:\"126.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:194;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:37:\"[\"\\/images\\/category\\/2.1phone.jpeg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1064;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 1\";s:4:\"slug\";s:17:\"fashion-product-1\";s:11:\"description\";s:33:\"Fashion Description for product 1\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-001\";s:5:\"price\";s:6:\"215.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:142;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:34:\"[\"\\/images\\/category\\/1.1men.jpg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1064;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 1\";s:4:\"slug\";s:17:\"fashion-product-1\";s:11:\"description\";s:33:\"Fashion Description for product 1\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-001\";s:5:\"price\";s:6:\"215.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:142;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:34:\"[\"\\/images\\/category\\/1.1men.jpg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1065;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 2\";s:4:\"slug\";s:17:\"fashion-product-2\";s:11:\"description\";s:33:\"Fashion Description for product 2\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-002\";s:5:\"price\";s:5:\"75.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:56;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:36:\"[\"\\/images\\/category\\/1.2women.jpg\"]\";s:6:\"rating\";i:3;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1065;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 2\";s:4:\"slug\";s:17:\"fashion-product-2\";s:11:\"description\";s:33:\"Fashion Description for product 2\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-002\";s:5:\"price\";s:5:\"75.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:56;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:36:\"[\"\\/images\\/category\\/1.2women.jpg\"]\";s:6:\"rating\";i:3;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:8;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1066;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 3\";s:4:\"slug\";s:17:\"fashion-product-3\";s:11:\"description\";s:33:\"Fashion Description for product 3\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-003\";s:5:\"price\";s:6:\"171.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:74;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:35:\"[\"\\/images\\/category\\/1.4kids.jpg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1066;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 3\";s:4:\"slug\";s:17:\"fashion-product-3\";s:11:\"description\";s:33:\"Fashion Description for product 3\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-003\";s:5:\"price\";s:6:\"171.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:74;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:35:\"[\"\\/images\\/category\\/1.4kids.jpg\"]\";s:6:\"rating\";i:4;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:9;O:18:\"App\\Models\\Product\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:8:\"products\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:68:{s:2:\"id\";i:1067;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 4\";s:4:\"slug\";s:17:\"fashion-product-4\";s:11:\"description\";s:33:\"Fashion Description for product 4\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-004\";s:5:\"price\";s:6:\"198.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:22;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:36:\"[\"\\/images\\/category\\/1.5bags.jpeg\"]\";s:6:\"rating\";i:2;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:11:\"\0*\0original\";a:68:{s:2:\"id\";i:1067;s:8:\"store_id\";i:1;s:9:\"trader_id\";N;s:17:\"is_trader_product\";i:0;s:11:\"category_id\";i:1031;s:4:\"name\";s:17:\"Fashion Product 4\";s:4:\"slug\";s:17:\"fashion-product-4\";s:11:\"description\";s:33:\"Fashion Description for product 4\";s:9:\"condition\";s:3:\"new\";s:5:\"pages\";N;s:5:\"genre\";N;s:6:\"author\";N;s:9:\"age_range\";N;s:5:\"brand\";N;s:8:\"material\";N;s:4:\"size\";N;s:5:\"color\";N;s:7:\"details\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:17:\"short_description\";N;s:3:\"sku\";s:11:\"FASHION-004\";s:5:\"price\";s:6:\"198.00\";s:10:\"cost_price\";N;s:14:\"discount_price\";N;s:5:\"stock\";i:22;s:14:\"stock_quantity\";i:0;s:19:\"low_stock_threshold\";i:10;s:6:\"images\";s:36:\"[\"\\/images\\/category\\/1.5bags.jpeg\"]\";s:6:\"rating\";i:2;s:13:\"reviews_count\";i:0;s:10:\"attributes\";N;s:8:\"seo_data\";N;s:9:\"is_active\";i:1;s:11:\"is_featured\";i:0;s:15:\"track_inventory\";i:1;s:6:\"status\";s:7:\"pending\";s:6:\"market\";s:5:\"store\";s:6:\"weight\";N;s:10:\"dimensions\";N;s:10:\"created_at\";s:19:\"2026-02-25 10:28:51\";s:10:\"updated_at\";s:19:\"2026-03-09 21:10:24\";s:3:\"fit\";N;s:13:\"sleeve_length\";N;s:7:\"pattern\";N;s:9:\"shoe_size\";N;s:9:\"shoe_type\";N;s:11:\"screen_size\";N;s:7:\"storage\";N;s:3:\"ram\";N;s:9:\"processor\";N;s:7:\"battery\";N;s:12:\"connectivity\";N;s:9:\"publisher\";N;s:8:\"language\";N;s:6:\"format\";N;s:8:\"toy_type\";N;s:4:\"room\";N;s:8:\"capacity\";N;s:5:\"power\";N;s:10:\"sport_type\";N;s:11:\"skill_level\";N;s:8:\"warranty\";N;s:13:\"free_shipping\";i:0;s:7:\"on_sale\";i:0;s:16:\"rejection_reason\";N;s:11:\"reviewed_by\";N;s:11:\"reviewed_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:12:{s:5:\"price\";s:9:\"decimal:2\";s:14:\"discount_price\";s:9:\"decimal:2\";s:10:\"cost_price\";s:9:\"decimal:2\";s:14:\"stock_quantity\";s:7:\"integer\";s:19:\"low_stock_threshold\";s:7:\"integer\";s:15:\"track_inventory\";s:7:\"boolean\";s:6:\"rating\";s:7:\"integer\";s:13:\"reviews_count\";s:7:\"integer\";s:11:\"is_featured\";s:7:\"boolean\";s:9:\"is_active\";s:7:\"boolean\";s:17:\"is_trader_product\";s:7:\"boolean\";s:6:\"images\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:2:{i:0;s:17:\"primary_image_url\";i:1;s:20:\"primary_image_srcset\";}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:23:{i:0;s:4:\"name\";i:1;s:4:\"slug\";i:2;s:11:\"description\";i:3;s:7:\"details\";i:4;s:11:\"category_id\";i:5;s:8:\"store_id\";i:6;s:9:\"trader_id\";i:7;s:3:\"sku\";i:8;s:5:\"price\";i:9;s:14:\"discount_price\";i:10;s:10:\"cost_price\";i:11;s:14:\"stock_quantity\";i:12;s:19:\"low_stock_threshold\";i:13;s:15:\"track_inventory\";i:14;s:5:\"image\";i:15;s:6:\"images\";i:16;s:6:\"rating\";i:17;s:13:\"reviews_count\";i:18;s:11:\"is_featured\";i:19;s:9:\"is_active\";i:20;s:17:\"is_trader_product\";i:21;s:6:\"status\";i:22;s:6:\"market\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"user_growth\";d:100;s:14:\"revenue_growth\";i:100;s:12:\"order_growth\";d:50;s:13:\"system_alerts\";i:0;s:23:\"pending_support_tickets\";i:0;s:17:\"recent_activities\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:9:{i:0;a:7:{s:4:\"type\";s:4:\"user\";s:5:\"title\";s:21:\"New User Registration\";s:11:\"description\";s:37:\"yousef F alhalabi joined the platform\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-10 05:21:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:12:\"fa-user-plus\";s:5:\"color\";s:14:\"text-green-600\";}i:1;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-69AFA9FAE87E5\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:6:\"161.16\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-10 05:19:55.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:2;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:32:\"New Order #ORD-E2E-69AF483A2C0ED\";s:11:\"description\";s:27:\"Order placed by Demo Trader\";s:6:\"amount\";s:5:\"50.00\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-09 22:22:50.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:3;a:7:{s:4:\"type\";s:4:\"user\";s:5:\"title\";s:21:\"New User Registration\";s:11:\"description\";s:20:\" joined the platform\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-09 21:10:28.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:12:\"fa-user-plus\";s:5:\"color\";s:14:\"text-green-600\";}i:4;a:7:{s:4:\"type\";s:4:\"user\";s:5:\"title\";s:21:\"New User Registration\";s:11:\"description\";s:20:\" joined the platform\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-09 21:10:27.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:12:\"fa-user-plus\";s:5:\"color\";s:14:\"text-green-600\";}i:5;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-69AEFC9353683\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:6:\"159.68\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-03-09 17:00:03.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:6;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-1770878983237\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:7:\"8413.20\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-02-12 09:49:43.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:7;a:7:{s:4:\"type\";s:5:\"order\";s:5:\"title\";s:28:\"New Order #ORD-1770801370601\";s:11:\"description\";s:37:\"Order placed by يوسف الحلبي\";s:6:\"amount\";s:8:\"25941.60\";s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-02-11 12:16:10.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:16:\"fa-shopping-cart\";s:5:\"color\";s:13:\"text-blue-600\";}i:8;a:7:{s:4:\"type\";s:5:\"store\";s:5:\"title\";s:17:\"New Store Created\";s:11:\"description\";s:29:\"Demo Store opened their store\";s:6:\"amount\";N;s:4:\"time\";O:25:\"Illuminate\\Support\\Carbon\":3:{s:4:\"date\";s:26:\"2026-02-08 07:02:37.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:4:\"icon\";s:8:\"fa-store\";s:5:\"color\";s:15:\"text-purple-600\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"top_performing_stores\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:16:\"App\\Models\\Store\":31:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"stores\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:11:{s:2:\"id\";i:1;s:4:\"name\";s:10:\"Demo Store\";s:4:\"slug\";s:17:\"demo-store-di7vk0\";s:11:\"description\";s:28:\"Demo store for local testing\";s:4:\"logo\";N;s:5:\"phone\";s:12:\"+10000000001\";s:5:\"email\";s:15:\"trader@demo.com\";s:6:\"status\";s:6:\"active\";s:11:\"total_sales\";s:6:\"161.16\";s:10:\"created_at\";s:19:\"2026-02-08 07:02:37\";s:16:\"orders_sum_total\";s:6:\"320.84\";}s:11:\"\0*\0original\";a:11:{s:2:\"id\";i:1;s:4:\"name\";s:10:\"Demo Store\";s:4:\"slug\";s:17:\"demo-store-di7vk0\";s:11:\"description\";s:28:\"Demo store for local testing\";s:4:\"logo\";N;s:5:\"phone\";s:12:\"+10000000001\";s:5:\"email\";s:15:\"trader@demo.com\";s:6:\"status\";s:6:\"active\";s:11:\"total_sales\";s:6:\"161.16\";s:10:\"created_at\";s:19:\"2026-02-08 07:02:37\";s:16:\"orders_sum_total\";s:6:\"320.84\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:6:{s:15:\"commission_rate\";s:9:\"decimal:2\";s:11:\"total_sales\";s:9:\"decimal:2\";s:16:\"total_commission\";s:9:\"decimal:2\";s:7:\"balance\";s:9:\"decimal:2\";s:11:\"is_featured\";s:7:\"boolean\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:17:{i:0;s:15:\"organization_id\";i:1;s:8:\"owner_id\";i:2;s:7:\"user_id\";i:3;s:4:\"name\";i:4;s:4:\"slug\";i:5;s:11:\"description\";i:6;s:4:\"logo\";i:7;s:6:\"banner\";i:8;s:5:\"phone\";i:9;s:5:\"email\";i:10;s:7:\"address\";i:11;s:6:\"status\";i:12;s:15:\"commission_rate\";i:13;s:11:\"total_sales\";i:14;s:16:\"total_commission\";i:15;s:7:\"balance\";i:16;s:11:\"is_featured\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:17:\"revenue_chart_30d\";a:30:{i:0;a:2:{s:4:\"date\";s:10:\"2026-02-09\";s:7:\"revenue\";d:0;}i:1;a:2:{s:4:\"date\";s:10:\"2026-02-10\";s:7:\"revenue\";d:0;}i:2;a:2:{s:4:\"date\";s:10:\"2026-02-11\";s:7:\"revenue\";d:0;}i:3;a:2:{s:4:\"date\";s:10:\"2026-02-12\";s:7:\"revenue\";d:0;}i:4;a:2:{s:4:\"date\";s:10:\"2026-02-13\";s:7:\"revenue\";d:0;}i:5;a:2:{s:4:\"date\";s:10:\"2026-02-14\";s:7:\"revenue\";d:0;}i:6;a:2:{s:4:\"date\";s:10:\"2026-02-15\";s:7:\"revenue\";d:0;}i:7;a:2:{s:4:\"date\";s:10:\"2026-02-16\";s:7:\"revenue\";d:0;}i:8;a:2:{s:4:\"date\";s:10:\"2026-02-17\";s:7:\"revenue\";d:0;}i:9;a:2:{s:4:\"date\";s:10:\"2026-02-18\";s:7:\"revenue\";d:0;}i:10;a:2:{s:4:\"date\";s:10:\"2026-02-19\";s:7:\"revenue\";d:0;}i:11;a:2:{s:4:\"date\";s:10:\"2026-02-20\";s:7:\"revenue\";d:0;}i:12;a:2:{s:4:\"date\";s:10:\"2026-02-21\";s:7:\"revenue\";d:0;}i:13;a:2:{s:4:\"date\";s:10:\"2026-02-22\";s:7:\"revenue\";d:0;}i:14;a:2:{s:4:\"date\";s:10:\"2026-02-23\";s:7:\"revenue\";d:0;}i:15;a:2:{s:4:\"date\";s:10:\"2026-02-24\";s:7:\"revenue\";d:0;}i:16;a:2:{s:4:\"date\";s:10:\"2026-02-25\";s:7:\"revenue\";d:0;}i:17;a:2:{s:4:\"date\";s:10:\"2026-02-26\";s:7:\"revenue\";d:0;}i:18;a:2:{s:4:\"date\";s:10:\"2026-02-27\";s:7:\"revenue\";d:0;}i:19;a:2:{s:4:\"date\";s:10:\"2026-02-28\";s:7:\"revenue\";d:0;}i:20;a:2:{s:4:\"date\";s:10:\"2026-03-01\";s:7:\"revenue\";d:0;}i:21;a:2:{s:4:\"date\";s:10:\"2026-03-02\";s:7:\"revenue\";d:0;}i:22;a:2:{s:4:\"date\";s:10:\"2026-03-03\";s:7:\"revenue\";d:0;}i:23;a:2:{s:4:\"date\";s:10:\"2026-03-04\";s:7:\"revenue\";d:0;}i:24;a:2:{s:4:\"date\";s:10:\"2026-03-05\";s:7:\"revenue\";d:0;}i:25;a:2:{s:4:\"date\";s:10:\"2026-03-06\";s:7:\"revenue\";d:0;}i:26;a:2:{s:4:\"date\";s:10:\"2026-03-07\";s:7:\"revenue\";d:0;}i:27;a:2:{s:4:\"date\";s:10:\"2026-03-08\";s:7:\"revenue\";d:0;}i:28;a:2:{s:4:\"date\";s:10:\"2026-03-09\";s:7:\"revenue\";d:159.68;}i:29;a:2:{s:4:\"date\";s:10:\"2026-03-10\";s:7:\"revenue\";d:161.16;}}s:20:\"orders_by_status_30d\";a:2:{s:6:\"labels\";a:4:{i:0;s:10:\"processing\";i:1;s:7:\"pending\";i:2;s:16:\"out_for_delivery\";i:3;s:4:\"done\";}s:6:\"values\";a:4:{i:0;i:2;i:1;i:1;i:2;i:1;i:3;i:1;}}s:16:\"top_products_30d\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":4:{s:4:\"name\";s:17:\"yousef F alhalabi\";s:5:\"image\";N;s:10:\"total_sold\";s:1:\"2\";s:13:\"total_revenue\";s:6:\"300.00\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"geo_orders_30d\";a:2:{i:0;a:4:{s:1:\"x\";d:36.51;s:1:\"y\";d:32.8;s:1:\"r\";i:3;s:5:\"count\";i:2;}i:1;a:4:{s:1:\"x\";d:36.28;s:1:\"y\";d:33.51;s:1:\"r\";i:3;s:5:\"count\";i:1;}}}', 1773127147);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tulip-store-cache-it_metrics', 'a:24:{s:15:\"services_online\";i:0;s:16:\"services_offline\";i:0;s:17:\"services_degraded\";i:0;s:17:\"avg_response_time\";d:0;s:15:\"critical_alerts\";i:0;s:14:\"warning_alerts\";i:0;s:19:\"total_active_alerts\";i:0;s:16:\"api_errors_today\";i:0;s:14:\"error_rate_24h\";d:0;s:18:\"slow_queries_today\";i:0;s:14:\"avg_query_time\";d:0;s:13:\"database_size\";s:8:\"10.69 MB\";s:11:\"last_backup\";N;s:19:\"backup_success_rate\";i:100;s:15:\"last_deployment\";N;s:22:\"deployments_this_month\";i:0;s:23:\"deployment_success_rate\";i:100;s:9:\"cpu_usage\";i:0;s:12:\"memory_usage\";i:0;s:10:\"disk_usage\";d:0;s:18:\"network_throughput\";s:25:\"Monitoring not configured\";s:13:\"recent_alerts\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:18:\"recent_deployments\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:13:\"system_uptime\";s:28:\"15 days, 8 hours, 32 minutes\";}', 1773126748),
('tulip-store-cache-supervisor_metrics', 'a:25:{s:13:\"total_drivers\";i:1;s:14:\"active_drivers\";i:1;s:17:\"available_drivers\";i:1;s:15:\"offline_drivers\";i:0;s:16:\"on_break_drivers\";i:0;s:19:\"drivers_on_delivery\";i:0;s:19:\"pending_assignments\";i:0;s:17:\"active_deliveries\";i:0;s:15:\"completed_today\";i:2;s:17:\"failed_deliveries\";i:0;s:22:\"deliveries_today_total\";i:2;s:17:\"in_progress_today\";i:0;s:13:\"pending_today\";i:0;s:26:\"orders_awaiting_assignment\";i:1;s:17:\"orders_in_transit\";i:1;s:17:\"avg_delivery_time\";d:28;s:21:\"on_time_delivery_rate\";d:100;s:17:\"driver_efficiency\";d:87.5;s:17:\"avg_driver_rating\";d:5;s:23:\"vehicles_in_maintenance\";i:0;s:15:\"maintenance_due\";i:0;s:18:\"recent_assignments\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;O:29:\"App\\Models\\DeliveryAssignment\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:20:\"delivery_assignments\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:23:{s:2:\"id\";i:2;s:8:\"order_id\";i:6;s:9:\"driver_id\";i:1;s:11:\"assigned_by\";i:1;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-03-10 06:41:25\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-03-10 06:41:31\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-03-10 06:41:25\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:11:\"\0*\0original\";a:23:{s:2:\"id\";i:2;s:8:\"order_id\";i:6;s:9:\"driver_id\";i:1;s:11:\"assigned_by\";i:1;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-03-10 06:41:25\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-03-10 06:41:31\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-03-10 06:41:25\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"picked_up_at\";s:8:\"datetime\";s:12:\"delivered_at\";s:8:\"datetime\";s:17:\"delivery_latitude\";s:9:\"decimal:8\";s:18:\"delivery_longitude\";s:9:\"decimal:8\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:5:\"order\";O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:46:{s:2:\"id\";i:6;s:12:\"order_number\";s:17:\"ORD-69AFA9FAE87E5\";s:11:\"customer_id\";i:5;s:7:\"user_id\";i:5;s:18:\"assigned_driver_id\";i:1;s:11:\"assigned_at\";s:19:\"2026-03-10 06:41:26\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"+963994251800\";s:7:\"village\";s:120:\"المجدل, ناحية المزرعة, منطقة مركز السويداء, محافظة السويداء, سوريا\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7968198\";s:9:\"longitude\";s:10:\"36.5076010\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";i:1;s:6:\"status\";s:4:\"done\";s:12:\"is_completed\";i:1;s:12:\"completed_at\";s:19:\"2026-03-10 06:42:50\";s:21:\"revenue_recognized_at\";s:19:\"2026-03-10 06:42:50\";s:14:\"payment_status\";s:4:\"paid\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";N;s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:8:\"subtotal\";s:6:\"150.00\";s:5:\"total\";s:6:\"161.16\";s:13:\"delivery_cost\";s:4:\"0.00\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:5:\"11.16\";s:15:\"discount_amount\";s:4:\"0.00\";s:12:\"total_amount\";s:6:\"161.16\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:622:\"\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\"\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-03-17 05:19:54\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:19:55\";s:10:\"updated_at\";s:19:\"2026-03-10 06:42:50\";}s:11:\"\0*\0original\";a:46:{s:2:\"id\";i:6;s:12:\"order_number\";s:17:\"ORD-69AFA9FAE87E5\";s:11:\"customer_id\";i:5;s:7:\"user_id\";i:5;s:18:\"assigned_driver_id\";i:1;s:11:\"assigned_at\";s:19:\"2026-03-10 06:41:26\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"+963994251800\";s:7:\"village\";s:120:\"المجدل, ناحية المزرعة, منطقة مركز السويداء, محافظة السويداء, سوريا\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7968198\";s:9:\"longitude\";s:10:\"36.5076010\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";i:1;s:6:\"status\";s:4:\"done\";s:12:\"is_completed\";i:1;s:12:\"completed_at\";s:19:\"2026-03-10 06:42:50\";s:21:\"revenue_recognized_at\";s:19:\"2026-03-10 06:42:50\";s:14:\"payment_status\";s:4:\"paid\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";N;s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:8:\"subtotal\";s:6:\"150.00\";s:5:\"total\";s:6:\"161.16\";s:13:\"delivery_cost\";s:4:\"0.00\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:5:\"11.16\";s:15:\"discount_amount\";s:4:\"0.00\";s:12:\"total_amount\";s:6:\"161.16\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:622:\"\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\"\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-03-17 05:19:54\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:19:55\";s:10:\"updated_at\";s:19:\"2026-03-10 06:42:50\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:36:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:9:\"signed_at\";i:34;s:14:\"delivery_notes\";i:35;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:6:\"driver\";O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:19:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:7:\"LIC-003\";s:14:\"license_expiry\";s:10:\"2002-01-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:13:\"ز ح ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:11:\"\0*\0original\";a:19:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:7:\"LIC-003\";s:14:\"license_expiry\";s:10:\"2002-01-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:13:\"ز ح ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";O:15:\"App\\Models\\User\":32:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"users\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:44:{s:2:\"id\";i:9;s:4:\"name\";s:17:\"yousef F alhalabi\";s:8:\"username\";s:16:\"yousefalhalabi53\";s:5:\"email\";s:26:\"yousefalhalabi53@gmail.com\";s:9:\"google_id\";N;s:10:\"birth_date\";N;s:5:\"phone\";s:10:\"0994251800\";s:17:\"email_verified_at\";N;s:8:\"password\";s:60:\"$2y$12$KP.dFnusRssuLyNXQQedIOt9uT6RojMqcZBXeKtrCzRF0XrxGOKly\";s:14:\"user_full_name\";N;s:6:\"mobile\";N;s:7:\"address\";N;s:8:\"language\";s:7:\"english\";s:6:\"gender\";N;s:8:\"currency\";N;s:8:\"verified\";i:1;s:9:\"is_trader\";i:0;s:8:\"is_admin\";i:0;s:11:\"is_it_super\";i:0;s:5:\"is_it\";i:0;s:11:\"is_cs_agent\";i:0;s:13:\"is_accountant\";i:0;s:16:\"is_cs_supervisor\";i:0;s:5:\"notes\";N;s:4:\"tags\";N;s:21:\"newsletter_subscribed\";i:0;s:14:\"lifetime_value\";s:4:\"0.00\";s:14:\"remember_token\";N;s:9:\"locked_at\";N;s:12:\"locked_until\";N;s:11:\"lock_reason\";N;s:14:\"login_failures\";i:0;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 05:21:37\";s:20:\"is_driver_supervisor\";i:0;s:7:\"role_id\";N;s:5:\"is_hr\";i:0;s:5:\"is_cs\";i:0;s:10:\"is_finance\";i:0;s:13:\"is_hr_manager\";i:0;s:6:\"status\";s:6:\"active\";s:11:\"is_verified\";i:0;s:17:\"verification_code\";N;s:10:\"otp_expiry\";N;}s:11:\"\0*\0original\";a:44:{s:2:\"id\";i:9;s:4:\"name\";s:17:\"yousef F alhalabi\";s:8:\"username\";s:16:\"yousefalhalabi53\";s:5:\"email\";s:26:\"yousefalhalabi53@gmail.com\";s:9:\"google_id\";N;s:10:\"birth_date\";N;s:5:\"phone\";s:10:\"0994251800\";s:17:\"email_verified_at\";N;s:8:\"password\";s:60:\"$2y$12$KP.dFnusRssuLyNXQQedIOt9uT6RojMqcZBXeKtrCzRF0XrxGOKly\";s:14:\"user_full_name\";N;s:6:\"mobile\";N;s:7:\"address\";N;s:8:\"language\";s:7:\"english\";s:6:\"gender\";N;s:8:\"currency\";N;s:8:\"verified\";i:1;s:9:\"is_trader\";i:0;s:8:\"is_admin\";i:0;s:11:\"is_it_super\";i:0;s:5:\"is_it\";i:0;s:11:\"is_cs_agent\";i:0;s:13:\"is_accountant\";i:0;s:16:\"is_cs_supervisor\";i:0;s:5:\"notes\";N;s:4:\"tags\";N;s:21:\"newsletter_subscribed\";i:0;s:14:\"lifetime_value\";s:4:\"0.00\";s:14:\"remember_token\";N;s:9:\"locked_at\";N;s:12:\"locked_until\";N;s:11:\"lock_reason\";N;s:14:\"login_failures\";i:0;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 05:21:37\";s:20:\"is_driver_supervisor\";i:0;s:7:\"role_id\";N;s:5:\"is_hr\";i:0;s:5:\"is_cs\";i:0;s:10:\"is_finance\";i:0;s:13:\"is_hr_manager\";i:0;s:6:\"status\";s:6:\"active\";s:11:\"is_verified\";i:0;s:17:\"verification_code\";N;s:10:\"otp_expiry\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:2:{i:0;s:8:\"password\";i:1;s:14:\"remember_token\";}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:29:{i:0;s:4:\"name\";i:1;s:8:\"username\";i:2;s:5:\"email\";i:3;s:8:\"password\";i:4;s:14:\"user_full_name\";i:5;s:6:\"mobile\";i:6;s:5:\"phone\";i:7;s:10:\"birth_date\";i:8;s:7:\"address\";i:9;s:8:\"language\";i:10;s:6:\"gender\";i:11;s:8:\"currency\";i:12;s:8:\"verified\";i:13;s:9:\"google_id\";i:14;s:9:\"is_trader\";i:15;s:8:\"is_admin\";i:16;s:11:\"is_it_super\";i:17;s:5:\"is_it\";i:18;s:5:\"is_hr\";i:19;s:5:\"is_cs\";i:20;s:10:\"is_finance\";i:21;s:7:\"country\";i:22;s:13:\"is_accountant\";i:23;s:20:\"is_driver_supervisor\";i:24;s:7:\"role_id\";i:25;s:9:\"locked_at\";i:26;s:12:\"locked_until\";i:27;s:11:\"lock_reason\";i:28;s:14:\"login_failures\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:20:\"\0*\0rememberTokenName\";s:14:\"remember_token\";s:14:\"\0*\0accessToken\";N;}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:9:\"driver_id\";i:1;s:8:\"order_id\";i:2;s:6:\"status\";i:3;s:11:\"assigned_at\";i:4;s:11:\"assigned_by\";i:5;s:12:\"picked_up_at\";i:6;s:12:\"delivered_at\";i:7;s:17:\"delivery_latitude\";i:8;s:18:\"delivery_longitude\";i:9;s:5:\"notes\";i:10;s:18:\"customer_signature\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:29:\"App\\Models\\DeliveryAssignment\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:20:\"delivery_assignments\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:23:{s:2:\"id\";i:1;s:8:\"order_id\";i:4;s:9:\"driver_id\";i:1;s:11:\"assigned_by\";i:1;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-03-10 05:37:17\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-03-10 06:34:15\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:37:17\";s:10:\"updated_at\";s:19:\"2026-03-10 06:34:15\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:11:\"\0*\0original\";a:23:{s:2:\"id\";i:1;s:8:\"order_id\";i:4;s:9:\"driver_id\";i:1;s:11:\"assigned_by\";i:1;s:6:\"status\";s:9:\"delivered\";s:11:\"assigned_at\";s:19:\"2026-03-10 05:37:17\";s:11:\"accepted_at\";N;s:12:\"picked_up_at\";N;s:12:\"delivered_at\";s:19:\"2026-03-10 06:34:15\";s:12:\"driver_notes\";N;s:14:\"delivery_proof\";N;s:12:\"delivery_fee\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:37:17\";s:10:\"updated_at\";s:19:\"2026-03-10 06:34:15\";s:15:\"pickup_latitude\";N;s:16:\"pickup_longitude\";N;s:17:\"delivery_latitude\";N;s:18:\"delivery_longitude\";N;s:11:\"distance_km\";N;s:22:\"estimated_time_minutes\";N;s:14:\"delivery_notes\";N;s:18:\"customer_signature\";N;s:14:\"failure_reason\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:5:{s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"picked_up_at\";s:8:\"datetime\";s:12:\"delivered_at\";s:8:\"datetime\";s:17:\"delivery_latitude\";s:9:\"decimal:8\";s:18:\"delivery_longitude\";s:9:\"decimal:8\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:5:\"order\";O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:46:{s:2:\"id\";i:4;s:12:\"order_number\";s:17:\"ORD-69AEFC9353683\";s:11:\"customer_id\";i:5;s:7:\"user_id\";i:5;s:18:\"assigned_driver_id\";i:1;s:11:\"assigned_at\";s:19:\"2026-03-10 05:37:18\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"+963994251800\";s:7:\"village\";s:12:\"المجدل\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7960321\";s:9:\"longitude\";s:10:\"36.5064582\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";N;s:6:\"status\";s:16:\"out_for_delivery\";s:12:\"is_completed\";i:0;s:12:\"completed_at\";N;s:21:\"revenue_recognized_at\";N;s:14:\"payment_status\";s:4:\"paid\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";N;s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:8:\"subtotal\";s:6:\"150.00\";s:5:\"total\";s:6:\"159.68\";s:13:\"delivery_cost\";s:4:\"0.00\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:4:\"9.68\";s:15:\"discount_amount\";s:4:\"0.00\";s:12:\"total_amount\";s:6:\"159.68\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:275:\"\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\"\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-03-16 17:00:03\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-03-09 17:00:03\";s:10:\"updated_at\";s:19:\"2026-03-10 05:37:18\";}s:11:\"\0*\0original\";a:46:{s:2:\"id\";i:4;s:12:\"order_number\";s:17:\"ORD-69AEFC9353683\";s:11:\"customer_id\";i:5;s:7:\"user_id\";i:5;s:18:\"assigned_driver_id\";i:1;s:11:\"assigned_at\";s:19:\"2026-03-10 05:37:18\";s:11:\"assigned_by\";N;s:14:\"recipient_name\";s:21:\"يوسف الحلبي\";s:5:\"phone\";s:13:\"+963994251800\";s:7:\"village\";s:12:\"المجدل\";s:12:\"address_note\";N;s:14:\"delivery_notes\";N;s:8:\"latitude\";s:10:\"32.7960321\";s:9:\"longitude\";s:10:\"36.5064582\";s:15:\"delivery_method\";s:6:\"normal\";s:8:\"store_id\";N;s:6:\"status\";s:16:\"out_for_delivery\";s:12:\"is_completed\";i:0;s:12:\"completed_at\";N;s:21:\"revenue_recognized_at\";N;s:14:\"payment_status\";s:4:\"paid\";s:18:\"confirmation_token\";N;s:12:\"confirmed_at\";N;s:18:\"customer_signature\";N;s:15:\"payment_receipt\";N;s:14:\"payment_method\";s:4:\"cash\";s:17:\"payment_reference\";N;s:8:\"subtotal\";s:6:\"150.00\";s:5:\"total\";s:6:\"159.68\";s:13:\"delivery_cost\";s:4:\"0.00\";s:11:\"service_fee\";s:4:\"0.00\";s:10:\"tax_amount\";s:4:\"0.00\";s:13:\"shipping_cost\";s:4:\"9.68\";s:15:\"discount_amount\";s:4:\"0.00\";s:12:\"total_amount\";s:6:\"159.68\";s:17:\"commission_amount\";s:4:\"0.00\";s:16:\"shipping_address\";s:275:\"\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\"\";s:15:\"billing_address\";N;s:18:\"estimated_delivery\";s:19:\"2026-03-16 17:00:03\";s:10:\"shipped_at\";N;s:12:\"delivered_at\";N;s:15:\"tracking_number\";N;s:14:\"customer_notes\";N;s:11:\"admin_notes\";N;s:10:\"created_at\";s:19:\"2026-03-09 17:00:03\";s:10:\"updated_at\";s:19:\"2026-03-10 05:37:18\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:36:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:9:\"signed_at\";i:34;s:14:\"delivery_notes\";i:35;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}s:6:\"driver\";r:275;}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:11:{i:0;s:9:\"driver_id\";i:1;s:8:\"order_id\";i:2;s:6:\"status\";i:3;s:11:\"assigned_at\";i:4;s:11:\"assigned_by\";i:5;s:12:\"picked_up_at\";i:6;s:12:\"delivered_at\";i:7;s:17:\"delivery_latitude\";i:8;s:18:\"delivery_longitude\";i:9;s:5:\"notes\";i:10;s:18:\"customer_signature\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:13:\"active_routes\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:24:\"App\\Models\\DeliveryRoute\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:15:\"delivery_routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:1;s:9:\"driver_id\";i:1;s:10:\"route_date\";s:10:\"2026-03-10\";s:9:\"waypoints\";s:1039:\"[{\"order_id\":4,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\",\"coordinates\":{\"lat\":40.779,\"lng\":-74.0224}},{\"order_id\":6,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\",\"coordinates\":{\"lat\":40.7442,\"lng\":-74.0629}}]\";s:18:\"optimized_sequence\";s:2:\"[]\";s:14:\"total_distance\";N;s:18:\"estimated_duration\";N;s:6:\"status\";s:6:\"active\";s:10:\"started_at\";N;s:12:\"completed_at\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:37:18\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:26\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:1;s:9:\"driver_id\";i:1;s:10:\"route_date\";s:10:\"2026-03-10\";s:9:\"waypoints\";s:1039:\"[{\"order_id\":4,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\",\"coordinates\":{\"lat\":40.779,\"lng\":-74.0224}},{\"order_id\":6,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\",\"coordinates\":{\"lat\":40.7442,\"lng\":-74.0629}}]\";s:18:\"optimized_sequence\";s:2:\"[]\";s:14:\"total_distance\";N;s:18:\"estimated_duration\";N;s:6:\"status\";s:6:\"active\";s:10:\"started_at\";N;s:12:\"completed_at\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:37:18\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:26\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:10:\"route_date\";s:4:\"date\";s:9:\"waypoints\";s:5:\"array\";s:18:\"optimized_sequence\";s:5:\"array\";s:14:\"total_distance\";s:9:\"decimal:2\";s:18:\"estimated_duration\";s:7:\"integer\";s:10:\"started_at\";s:8:\"datetime\";s:12:\"completed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:6:\"driver\";O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:19:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:7:\"LIC-003\";s:14:\"license_expiry\";s:10:\"2002-01-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:13:\"ز ح ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:11:\"\0*\0original\";a:19:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:10:\"vehicle_id\";N;s:14:\"license_number\";s:7:\"LIC-003\";s:14:\"license_expiry\";s:10:\"2002-01-13\";s:12:\"vehicle_type\";s:21:\"دراجة نارية\";s:13:\"vehicle_plate\";s:13:\"ز ح ط 9012\";s:12:\"vehicle_info\";N;s:6:\"status\";s:6:\"active\";s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:16:\"total_deliveries\";i:0;s:13:\"working_hours\";N;s:13:\"last_location\";N;s:10:\"created_at\";s:19:\"2026-03-10 05:21:37\";s:10:\"updated_at\";s:19:\"2026-03-10 06:41:31\";s:20:\"last_location_update\";N;s:13:\"current_speed\";N;s:15:\"current_heading\";N;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:9:\"driver_id\";i:1;s:10:\"route_date\";i:2;s:9:\"waypoints\";i:3;s:18:\"optimized_sequence\";i:4;s:14:\"total_distance\";i:5;s:18:\"estimated_duration\";i:6;s:6:\"status\";i:7;s:10:\"started_at\";i:8;s:12:\"completed_at\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"drivers_sample\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:17:\"App\\Models\\Driver\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"drivers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:10:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:20:\"last_location_update\";N;s:4:\"name\";s:17:\"yousef F alhalabi\";s:5:\"phone\";s:10:\"0994251800\";s:16:\"current_latitude\";N;s:17:\"current_longitude\";N;s:24:\"active_assignments_count\";i:0;}s:11:\"\0*\0original\";a:10:{s:2:\"id\";i:1;s:7:\"user_id\";i:9;s:12:\"availability\";s:9:\"available\";s:6:\"rating\";s:4:\"5.00\";s:20:\"last_location_update\";N;s:4:\"name\";s:17:\"yousef F alhalabi\";s:5:\"phone\";s:10:\"0994251800\";s:16:\"current_latitude\";N;s:17:\"current_longitude\";N;s:24:\"active_assignments_count\";i:0;}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:20:\"last_location_update\";s:8:\"datetime\";s:6:\"rating\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:16:{i:0;s:7:\"user_id\";i:1;s:10:\"vehicle_id\";i:2;s:14:\"license_number\";i:3;s:14:\"license_expiry\";i:4;s:12:\"vehicle_type\";i:5;s:13:\"vehicle_plate\";i:6;s:12:\"vehicle_info\";i:7;s:6:\"status\";i:8;s:12:\"availability\";i:9;s:13:\"working_hours\";i:10;s:13:\"last_location\";i:11;s:20:\"last_location_update\";i:12;s:16:\"total_deliveries\";i:13;s:6:\"rating\";i:14;s:13:\"current_speed\";i:15;s:15:\"current_heading\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:24:\"unassigned_orders_sample\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:16:\"App\\Models\\Order\":30:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:5:{s:2:\"id\";i:5;s:12:\"order_number\";s:21:\"ORD-E2E-69AF483A2C0ED\";s:14:\"recipient_name\";s:19:\"Playwright Customer\";s:12:\"address_note\";s:8:\"E2E test\";s:10:\"created_at\";s:19:\"2026-03-09 22:22:50\";}s:11:\"\0*\0original\";a:5:{s:2:\"id\";i:5;s:12:\"order_number\";s:21:\"ORD-E2E-69AF483A2C0ED\";s:14:\"recipient_name\";s:19:\"Playwright Customer\";s:12:\"address_note\";s:8:\"E2E test\";s:10:\"created_at\";s:19:\"2026-03-09 22:22:50\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:8:\"latitude\";s:9:\"decimal:7\";s:9:\"longitude\";s:9:\"decimal:7\";s:8:\"subtotal\";s:9:\"decimal:2\";s:10:\"tax_amount\";s:9:\"decimal:2\";s:13:\"shipping_cost\";s:9:\"decimal:2\";s:13:\"delivery_cost\";s:9:\"decimal:2\";s:11:\"service_fee\";s:9:\"decimal:2\";s:5:\"total\";s:9:\"decimal:2\";s:15:\"discount_amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:16:\"shipping_address\";s:5:\"array\";s:15:\"billing_address\";s:5:\"array\";s:18:\"estimated_delivery\";s:8:\"datetime\";s:11:\"assigned_at\";s:8:\"datetime\";s:12:\"confirmed_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:36:{i:0;s:7:\"user_id\";i:1;s:11:\"customer_id\";i:2;s:8:\"store_id\";i:3;s:12:\"order_number\";i:4;s:14:\"recipient_name\";i:5;s:5:\"phone\";i:6;s:7:\"village\";i:7;s:12:\"address_note\";i:8;s:8:\"latitude\";i:9;s:9:\"longitude\";i:10;s:15:\"delivery_method\";i:11;s:14:\"payment_method\";i:12;s:17:\"payment_reference\";i:13;s:6:\"status\";i:14;s:14:\"payment_status\";i:15;s:15:\"payment_receipt\";i:16;s:8:\"subtotal\";i:17;s:10:\"tax_amount\";i:18;s:13:\"shipping_cost\";i:19;s:13:\"delivery_cost\";i:20;s:11:\"service_fee\";i:21;s:5:\"total\";i:22;s:15:\"discount_amount\";i:23;s:12:\"total_amount\";i:24;s:16:\"shipping_address\";i:25;s:15:\"billing_address\";i:26;s:18:\"estimated_delivery\";i:27;s:18:\"assigned_driver_id\";i:28;s:11:\"assigned_at\";i:29;s:11:\"assigned_by\";i:30;s:18:\"confirmation_token\";i:31;s:12:\"confirmed_at\";i:32;s:18:\"customer_signature\";i:33;s:9:\"signed_at\";i:34;s:14:\"delivery_notes\";i:35;s:15:\"tracking_number\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}', 1773126764);

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
(1, 1, NULL, 'active', '2026-02-05 03:45:13', '2026-02-05 03:45:13'),
(2, 5, NULL, 'active', '2026-02-22 05:56:07', '2026-02-22 05:56:07');

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

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `display_order`, `parent_id`, `sort_order`, `is_active`, `market`, `metadata`, `created_at`, `updated_at`, `type`) VALUES
(1023, 'هدايا فاخرة', 'luxury-gifts', 'هدايا مميزة وفاخرة', 'https://via.placeholder.com/300x300?text=Luxury', 1, NULL, 0, 1, 'store', NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', 'store'),
(1024, 'ورد وباقات', 'bouquets', 'باقات ورد أنيقة', 'https://via.placeholder.com/300x300?text=Bouquets', 2, NULL, 0, 1, 'store', NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', 'store'),
(1025, 'حلويات وشوكولاتة', 'sweets', 'حلويات وشوكولاتة فاخرة', 'https://via.placeholder.com/300x300?text=Sweets', 3, NULL, 0, 1, 'store', NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', 'store'),
(1026, 'فواكه', 'fruits', 'فواكه طازجة يومياً', NULL, 1, NULL, 0, 1, 'mart', NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', 'store'),
(1027, 'خضار', 'vegetables', 'خضروات موسمية طازجة', NULL, 2, NULL, 0, 1, 'mart', NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', 'store'),
(1028, 'ألبان', 'dairy', 'أجبان وألبان', NULL, 3, NULL, 0, 1, 'mart', NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', 'store'),
(1029, 'مخبوزات', 'bakery', 'خبز ومخبوزات طازجة', NULL, 4, NULL, 0, 1, 'mart', NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', 'store'),
(1030, 'Electronics', 'electronics', 'Devices, gadgets, and more.', '/images/category/2.6TV.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51', 'store'),
(1031, 'Fashion', 'fashion', 'Trendy and classic apparel.', '/images/category/1.2women.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51', 'store'),
(1032, 'Books', 'books', 'Books and literature.', '/images/category/6.2.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51', 'store'),
(1033, 'Shoes', 'shoes', 'Footwear for every occasion.', '/images/category/1.6shoes.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51', 'store'),
(1034, 'Bags', 'bags', 'Handbags and backpacks.', '/images/category/1.5bags.jpeg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1035, 'Toys', 'toys', 'Fun and learning.', '/images/category/3.1education.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1036, 'Sports', 'sports', 'Sport gear and wear.', '/images/category/4.1fitness.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1037, 'Jewelry', 'jewelry', 'Shine and elegance.', '/images/category/5.1jewelry.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1038, 'Care', 'care', 'Personal care.', '/images/category/7.2.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1039, 'Kitchen', 'kitchen', 'Home and kitchen.', '/images/category/8.1.jpg', 0, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52', 'store'),
(1040, 'الزهور الطازة', 'fresh-flowers', 'مجموعة متنوعة من الزهور الطازة', 'https://via.placeholder.com/300x300?text=Fresh+Flowers', 1, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:53', '2026-02-25 07:28:53', 'store'),
(1041, 'الهدايا والمفاجآت', 'gifts', 'هدايا فاخرة لأحبائك', 'https://via.placeholder.com/300x300?text=Gifts', 2, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:53', '2026-02-25 07:28:53', 'store'),
(1042, 'الشوكولاتة والحلويات', 'chocolates', 'شوكولاتة وحلويات عالية الجودة', 'https://via.placeholder.com/300x300?text=Chocolates', 3, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:53', '2026-02-25 07:28:53', 'store'),
(1043, 'البالونات والديكور', 'balloons', 'بالونات وديكورات احتفالية', 'https://via.placeholder.com/300x300?text=Balloons', 4, NULL, 0, 1, 'store', NULL, '2026-02-25 07:28:53', '2026-02-25 07:28:53', 'store');

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
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, 'cs', 'App\\Models\\Employee', 6, 'trader_product_review', 'New trader product pending review', 'A trader submitted \"yousef F alhalabi\" for approval.', 'http://127.0.0.1:8000/dashboard/cs/trader-products', 'fa-box', 'amber', 0, NULL, '2026-03-09 06:34:41', '2026-03-09 06:34:41');

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
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
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

--
-- Dumping data for table `delivery_assignments`
--

INSERT INTO `delivery_assignments` (`id`, `order_id`, `driver_id`, `assigned_by`, `status`, `assigned_at`, `accepted_at`, `picked_up_at`, `delivered_at`, `driver_notes`, `delivery_proof`, `delivery_fee`, `created_at`, `updated_at`, `pickup_latitude`, `pickup_longitude`, `delivery_latitude`, `delivery_longitude`, `distance_km`, `estimated_time_minutes`, `delivery_notes`, `customer_signature`, `failure_reason`) VALUES
(1, 4, 1, 1, 'delivered', '2026-03-10 02:37:17', NULL, NULL, '2026-03-10 03:34:15', NULL, NULL, NULL, '2026-03-10 02:37:17', '2026-03-10 03:34:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 6, 1, 1, 'delivered', '2026-03-10 03:41:25', NULL, NULL, '2026-03-10 03:41:31', NULL, NULL, NULL, '2026-03-10 03:41:25', '2026-03-10 03:41:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `delivery_routes`
--

INSERT INTO `delivery_routes` (`id`, `driver_id`, `route_date`, `waypoints`, `optimized_sequence`, `total_distance`, `estimated_duration`, `status`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-10', '[{\"order_id\":4,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\",\"coordinates\":{\"lat\":40.779,\"lng\":-74.0224}},{\"order_id\":6,\"address\":\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\",\"coordinates\":{\"lat\":40.7442,\"lng\":-74.0629}}]', '[]', NULL, NULL, 'active', NULL, NULL, '2026-03-10 02:37:18', '2026-03-10 03:41:26');

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

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `vehicle_id`, `license_number`, `license_expiry`, `vehicle_type`, `vehicle_plate`, `vehicle_info`, `status`, `availability`, `rating`, `total_deliveries`, `working_hours`, `last_location`, `created_at`, `updated_at`, `last_location_update`, `current_speed`, `current_heading`) VALUES
(1, 9, NULL, 'LIC-003', '2002-01-13', 'دراجة نارية', 'ز ح ط 9012', NULL, 'active', 'available', 5.00, 0, NULL, NULL, '2026-03-10 02:21:37', '2026-03-10 03:41:31', NULL, NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
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

INSERT INTO `employees` (`id`, `employee_id`, `department`, `work_location`, `position`, `manager_id`, `hourly_rate`, `monthly_salary`, `hire_date`, `termination_date`, `employment_type`, `work_schedule`, `status`, `security_level`, `emergency_contact`, `documents`, `created_at`, `updated_at`, `employee_code`, `employee_id_card`, `first_name`, `last_name`, `email`, `profile_photo`, `bio`, `password`, `email_verified_at`, `remember_token`, `last_login_at`, `login_count`, `two_factor_enabled`, `ip_restrictions`, `performance_score`, `last_review_date`, `next_review_date`, `phone`, `national_id`, `date_of_birth`, `gender`, `marital_status`, `address`, `city`, `country`, `is_admin`, `is_it`, `is_hr`, `is_cs`, `is_finance`, `is_driver_supervisor`, `is_trader`, `is_manager`, `is_team_lead`, `can_approve_expenses`, `can_manage_inventory`, `contract_end_date`, `salary`, `approval_limit`, `commission_rate`, `bank_name`, `bank_account`, `iban`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `notes`, `skills`, `qualifications`, `certifications`, `languages`, `preferred_communication`, `deleted_at`) VALUES
(1, 'EMP001', 'Administration', NULL, 'Super Admin', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-01-06 04:15:19', '2026-03-09 19:22:31', 'EMP001', NULL, 'Admin', 'User', 'admin@tulipstore.com', NULL, NULL, '$2y$12$d7Vlh1WPQk7pk//l6iJVOekrpbxpmO/RtHX3i9E0GLXAnbCRe5NJW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567890', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(2, 'EMP002', 'Information Technology', NULL, 'IT Specialist', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-01-06 04:16:21', '2026-03-09 19:22:32', 'EMP002', NULL, 'John', 'Tech', 'it@tulipstore.com', NULL, NULL, '$2y$12$a12JMEkXtqgAQCe7cAMe/eHRJO9nwSVqJkzepdd5v3MCEfm7QoMtG', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567891', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(3, 'EMP003', 'Management', NULL, 'Department Manager', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-01-06 04:16:21', '2026-03-09 19:22:35', 'EMP003', NULL, 'Sarah', 'Multi', 'multi@tulipstore.com', NULL, NULL, '$2y$12$WI/TT.l56xKIkiSeZVzq2eVRPScRd5fSptQX3NEIu5D09DAa70Od6', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567892', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(4, 'EMP-RLKIG4ZCIS', 'Human Resources', NULL, 'HR Specialist', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-02-02 04:03:18', '2026-03-09 19:22:33', 'EMP004', NULL, 'Hana', 'HR', 'hr@tulipstore.com', NULL, NULL, '$2y$12$G5S9870qNJqO.O.1jFyNOO5kmd/JoiADOEG12prCSg4msUxkfN5Iu', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567893', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(5, 'EMP-OJSEBNVGLH', 'Finance', NULL, 'Accountant', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-02-02 04:03:19', '2026-03-09 19:22:33', 'EMP005', NULL, 'Fadi', 'Finance', 'finance@tulipstore.com', NULL, NULL, '$2y$12$9l2ofgjuUzFZv07XkP7WiOJRI3xNjY0g.vIc/.wBCAI1vW3pm7QNq', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567894', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(6, 'EMP-VNW5K1KCGA', 'Customer Support', NULL, 'Support Agent', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-02-02 04:38:57', '2026-03-09 19:22:34', 'EMP006', NULL, 'Noor', 'Support', 'support@tulipstore.com', NULL, NULL, '$2y$12$VrwA3pI7ijv3sqBFQBpcguuTgYZR/EJN.c0hSTEKQS/V.gyKUgfnW', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567895', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, NULL, 50000.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL),
(7, 'EMP-5I0OSPNB1P', 'Delivery', NULL, 'Dispatch Supervisor', NULL, NULL, NULL, '2026-03-09', NULL, 'full_time', NULL, 'active', '1', NULL, NULL, '2026-03-09 19:19:21', '2026-03-09 19:22:35', 'EMP007', NULL, 'Samer', 'Supervisor', 'supervisor@tulipstore.com', NULL, NULL, '$2y$12$pGCTPTd2.qE0MA5Js2T9ZeWFI/Ixli.K2m9Tk1BMcSqbY2tRE/9De', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, '1234567896', NULL, NULL, NULL, NULL, NULL, NULL, 'Saudi Arabia', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email', NULL);

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
-- Table structure for table `employee_dashboard_permissions`
--

CREATE TABLE `employee_dashboard_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `dashboard_key` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `financial_transactions`
--

INSERT INTO `financial_transactions` (`id`, `transaction_id`, `user_id`, `order_id`, `store_id`, `type`, `status`, `amount`, `currency`, `description`, `metadata`, `hash`, `is_locked`, `locked_at`, `approval_status`, `approved_by`, `approved_at`, `approval_notes`, `created_at`, `updated_at`, `balance_before`, `balance_after`, `reference`, `is_immutable`) VALUES
(1, 'TEST_1770197410', NULL, NULL, NULL, 'salary_payment', 'pending_approval', 1.00, 'USD', 'test', NULL, NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-04 06:30:10', '2026-02-04 06:30:10', 0.00, 0.00, NULL, 0),
(2, 'SAL_1770197472_5935', NULL, NULL, NULL, 'salary_payment', 'pending_approval', 0.00, 'USD', 'Salary: Demo Admin (2026-02)', '{\"payroll_record_id\":1,\"employee_id\":1,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-04 06:31:12', '2026-02-04 06:31:12', 0.00, 0.00, NULL, 0),
(3, 'SAL_1770197473_6841', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo IT (2026-02)', '{\"payroll_record_id\":2,\"employee_id\":2,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:13', NULL, '2026-02-04 06:31:13', '2026-02-04 06:39:13', 0.00, 0.00, NULL, 0),
(4, 'SAL_1770197473_9915', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Sarah Multi (2026-02)', '{\"payroll_record_id\":3,\"employee_id\":3,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:15', NULL, '2026-02-04 06:31:13', '2026-02-04 06:39:15', 0.00, 0.00, NULL, 0),
(5, 'SAL_1770197473_5801', NULL, NULL, NULL, 'salary_payment', 'pending_approval', 0.00, 'USD', 'Salary: Demo HR (2026-02)', '{\"payroll_record_id\":4,\"employee_id\":4,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-04 06:31:13', '2026-02-04 06:31:13', 0.00, 0.00, NULL, 0),
(6, 'SAL_1770197473_3465', NULL, NULL, NULL, 'salary_payment', 'pending_approval', 0.00, 'USD', 'Salary: Demo Finance (2026-02)', '{\"payroll_record_id\":5,\"employee_id\":5,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-04 06:31:13', '2026-02-04 06:31:13', 0.00, 0.00, NULL, 0),
(7, 'SAL_1770197473_5192', NULL, NULL, NULL, 'salary_payment', 'pending_approval', 0.00, 'USD', 'Salary: Demo Support (2026-02)', '{\"payroll_record_id\":6,\"employee_id\":6,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-02-04 06:31:13', '2026-02-04 06:31:13', 0.00, 0.00, NULL, 0),
(8, 'SAL_1770197910_5310', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo Admin (2026-02)', '{\"payroll_record_id\":1,\"employee_id\":1,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:03', NULL, '2026-02-04 06:38:30', '2026-02-04 06:39:03', 0.00, 0.00, NULL, 0),
(9, 'SAL_1770197910_7963', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo IT (2026-02)', '{\"payroll_record_id\":2,\"employee_id\":2,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:38:58', NULL, '2026-02-04 06:38:30', '2026-02-04 06:38:58', 0.00, 0.00, NULL, 0),
(10, 'SAL_1770197910_1409', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Sarah Multi (2026-02)', '{\"payroll_record_id\":3,\"employee_id\":3,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:06', NULL, '2026-02-04 06:38:30', '2026-02-04 06:39:06', 0.00, 0.00, NULL, 0),
(11, 'SAL_1770197910_7855', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo HR (2026-02)', '{\"payroll_record_id\":4,\"employee_id\":4,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:08', NULL, '2026-02-04 06:38:30', '2026-02-04 06:39:08', 0.00, 0.00, NULL, 0),
(12, 'SAL_1770197910_9054', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo Finance (2026-02)', '{\"payroll_record_id\":5,\"employee_id\":5,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:39:10', NULL, '2026-02-04 06:38:30', '2026-02-04 06:39:10', 0.00, 0.00, NULL, 0),
(13, 'SAL_1770197911_4567', NULL, NULL, NULL, 'salary_payment', 'approved', 0.00, 'USD', 'Salary: Demo Support (2026-02)', '{\"payroll_record_id\":6,\"employee_id\":6,\"pay_period\":\"2026-02\",\"requested_date\":\"2026-02-04\"}', NULL, 0, NULL, 'approved', NULL, '2026-02-04 06:38:52', NULL, '2026-02-04 06:38:31', '2026-02-04 06:38:52', 0.00, 0.00, NULL, 0),
(14, 'TXN-69AEFC9393ED4', 5, 4, NULL, 'order_payment', 'pending', 159.68, 'SYP', 'Order Payment #ORD-69AEFC9353683', '{\"payment_method\":\"cash\",\"items_count\":1,\"vip_order\":false}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-03-09 14:00:03', '2026-03-09 14:00:03', 0.00, 0.00, NULL, 0),
(15, 'TXN-69AFA9FB5F32B', 5, 6, NULL, 'order_payment', 'completed', 161.16, 'USD', 'Order Payment #ORD-69AFA9FAE87E5', '{\"payment_method\":\"cash\",\"items_count\":1,\"vip_order\":false}', NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-03-10 02:19:55', '2026-03-10 03:42:50', 0.00, 0.00, NULL, 0),
(17, 'COM_1773124892_2380', NULL, 6, 1, 'commission', 'pending_approval', 15.00, 'USD', 'Platform commission for order ORD-69AFA9FAE87E5', NULL, NULL, 0, NULL, 'pending', NULL, NULL, NULL, '2026-03-10 03:41:32', '2026-03-10 03:41:32', 0.00, 0.00, NULL, 0);

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

--
-- Dumping data for table `gifts`
--

INSERT INTO `gifts` (`id`, `name`, `description`, `price`, `category`, `occasion`, `images`, `size`, `is_customizable`, `customization_options`, `stock_quantity`, `is_featured`, `is_active`, `delivery_time`, `rating`, `reviews_count`, `created_at`, `updated_at`) VALUES
(11, 'باقة رومانسية صغيرة', 'باقة أنيقة من الورود الصغيرة مع تغليف بسيط ورسالة قصيرة.', 129.00, 'valentine', 'عيد الحب', '[\"\\/images\\/gift-placeholder.jpg\"]', 'small', 0, NULL, 10, 1, 1, NULL, 0.00, 0, '2026-02-25 07:34:40', '2026-02-25 07:34:40'),
(12, 'باقة تخرج بيضاء', 'باقة زهور بيضاء للتخرج بتنسيق راقٍ وبطاقة تهنئة.', 179.00, 'graduation', 'تخرج', '[\"\\/images\\/gift-placeholder.jpg\"]', 'medium', 0, NULL, 14, 0, 1, NULL, 0.00, 0, '2026-02-25 07:34:41', '2026-02-25 07:34:41'),
(13, 'باقة ورود حمراء رومانسية', 'باقة رائعة من الورود الحمراء الطازجة مع تغليف أنيق، مثالية للتعبير عن الحب والرومانسية في المناسبات الخاصة.', 150.00, 'valentine', 'عيد الحب', '[\"\\/images\\/gifts\\/red-roses.jpg\"]', 'medium', 1, '[\"\\u0631\\u0633\\u0627\\u0644\\u0629 \\u0634\\u062e\\u0635\\u064a\\u0629\",\"\\u0644\\u0648\\u0646 \\u0627\\u0644\\u062a\\u063a\\u0644\\u064a\\u0641\",\"\\u0625\\u0636\\u0627\\u0641\\u0629 \\u0634\\u0648\\u0643\\u0648\\u0644\\u0627\\u062a\\u0629\"]', 25, 1, 1, 'نفس اليوم', 4.80, 42, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(14, 'صندوق شوكولاتة فاخر', 'مجموعة مختارة من أفخر أنواع الشوكولاتة البلجيكية في صندوق أنيق، هدية مثالية لعشاق الحلويات.', 120.00, 'birthday', 'عيد ميلاد', '[\"\\/images\\/gifts\\/chocolate-box.jpg\"]', 'small', 1, '[\"\\u0646\\u0648\\u0639 \\u0627\\u0644\\u0634\\u0648\\u0643\\u0648\\u0644\\u0627\\u062a\\u0629\",\"\\u0631\\u0633\\u0627\\u0644\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0635\\u0646\\u062f\\u0648\\u0642\"]', 30, 1, 1, 'خلال 24 ساعة', 4.90, 67, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(15, 'سلة فواكه طازجة', 'سلة مليئة بأطيب وأجود أنواع الفواكه الطازجة والموسمية، مرتبة بشكل جميل ومغلفة بأناقة.', 80.00, 'general', 'زيارة', '[\"\\/images\\/gifts\\/fruit-basket.jpg\"]', 'large', 0, NULL, 20, 0, 1, 'نفس اليوم', 4.50, 28, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(16, 'دبدوب عملاق مع قلب', 'دبدوب ناعم وكبير الحجم يحمل قلباً أحمر، هدية رائعة للأطفال والأحباب في المناسبات الرومانسية.', 200.00, 'valentine', 'عيد الحب', '[\"\\/images\\/gifts\\/teddy-bear.jpg\"]', 'large', 1, '[\"\\u0644\\u0648\\u0646 \\u0627\\u0644\\u062f\\u0628\\u062f\\u0648\\u0628\",\"\\u0631\\u0633\\u0627\\u0644\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0642\\u0644\\u0628\"]', 15, 1, 1, 'خلال 24 ساعة', 4.70, 35, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(17, 'مجموعة عطور فاخرة', 'مجموعة من العطور الفاخرة في عبوات أنيقة، تشمل عطور رجالية ونسائية من أفضل الماركات العالمية.', 350.00, 'anniversary', 'ذكرى زواج', '[\"\\/images\\/gifts\\/perfume-set.jpg\"]', 'medium', 1, '[\"\\u0646\\u0648\\u0639 \\u0627\\u0644\\u0639\\u0637\\u0631\",\"\\u062a\\u063a\\u0644\\u064a\\u0641 \\u062e\\u0627\\u0635\"]', 12, 1, 1, 'خلال 48 ساعة', 4.90, 23, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(18, 'كيكة عيد ميلاد مخصصة', 'كيكة عيد ميلاد مصنوعة خصيصاً حسب الطلب، بنكهات متنوعة وتصميم مخصص حسب المناسبة.', 180.00, 'birthday', 'عيد ميلاد', '[\"\\/images\\/gifts\\/birthday-cake.jpg\"]', 'medium', 1, '[\"\\u0627\\u0644\\u0646\\u0643\\u0647\\u0629\",\"\\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645\",\"\\u0627\\u0644\\u0643\\u062a\\u0627\\u0628\\u0629 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0643\\u064a\\u0643\\u0629\"]', 0, 0, 1, 'خلال 24 ساعة', 4.60, 51, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(19, 'مجموعة ألعاب تعليمية للأطفال', 'مجموعة متنوعة من الألعاب التعليمية المناسبة للأطفال، تساعد على تنمية المهارات والإبداع.', 95.00, 'baby', 'مولود جديد', '[\"\\/images\\/gifts\\/educational-toys.jpg\"]', 'medium', 0, NULL, 18, 0, 1, 'خلال 24 ساعة', 4.40, 19, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(20, 'باقة زهور مختلطة', 'باقة جميلة من الزهور المختلطة بألوان زاهية، مناسبة لجميع المناسبات السعيدة.', 110.00, 'general', 'تهنئة', '[\"\\/images\\/gifts\\/mixed-flowers.jpg\"]', 'medium', 1, '[\"\\u0623\\u0646\\u0648\\u0627\\u0639 \\u0627\\u0644\\u0632\\u0647\\u0648\\u0631\",\"\\u0644\\u0648\\u0646 \\u0627\\u0644\\u062a\\u063a\\u0644\\u064a\\u0641\"]', 22, 0, 1, 'نفس اليوم', 4.30, 31, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(21, 'مجموعة قرطاسية فاخرة', 'مجموعة أنيقة من القرطاسية الفاخرة تشمل دفاتر وأقلام مميزة، مثالية لطلاب الجامعة.', 75.00, 'graduation', 'تخرج', '[\"\\/images\\/gifts\\/stationery-set.jpg\"]', 'small', 1, '[\"\\u0627\\u0644\\u0644\\u0648\\u0646\",\"\\u0627\\u0644\\u0646\\u0642\\u0634 \\u0627\\u0644\\u0634\\u062e\\u0635\\u064a\"]', 35, 0, 1, 'خلال 24 ساعة', 4.20, 14, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(22, 'سلة هدايا العيد', 'سلة مليئة بالحلويات والمكسرات والتمور الفاخرة، مثالية لتهنئة العيد ومشاركة الفرحة.', 160.00, 'eid', 'عيد الفطر', '[\"\\/images\\/gifts\\/eid-basket.jpg\"]', 'large', 1, '[\"\\u0645\\u062d\\u062a\\u0648\\u064a\\u0627\\u062a \\u0627\\u0644\\u0633\\u0644\\u0629\",\"\\u0628\\u0637\\u0627\\u0642\\u0629 \\u062a\\u0647\\u0646\\u0626\\u0629\"]', 28, 1, 1, 'نفس اليوم', 4.80, 45, '2026-03-09 18:10:25', '2026-03-09 18:10:25');

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

--
-- Dumping data for table `gift_boxes`
--

INSERT INTO `gift_boxes` (`id`, `name`, `name_en`, `description`, `image`, `size`, `price`, `color`, `max_items`, `stock`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'صندوق صغير', NULL, NULL, NULL, 'small', 49.00, NULL, 6, 50, 1, 1, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(3, 'صندوق متوسط', NULL, NULL, NULL, 'medium', 79.00, NULL, 12, 40, 1, 2, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(4, 'صندوق كبير', NULL, NULL, NULL, 'large', 119.00, NULL, 18, 30, 1, 3, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(5, 'صندوق فاخر XL', NULL, NULL, NULL, 'xl', 179.00, NULL, 24, 20, 1, 4, '2026-02-25 07:27:07', '2026-02-25 07:27:07');

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

--
-- Dumping data for table `gift_cards`
--

INSERT INTO `gift_cards` (`id`, `name`, `name_en`, `image`, `occasion`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'بطاقة حب', NULL, NULL, 'valentine', 5.00, 1, 1, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(2, 'بطاقة عيد ميلاد', NULL, NULL, 'birthday', 5.00, 1, 2, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(3, 'بطاقة تهنئة', NULL, NULL, 'congrats', 5.00, 1, 3, '2026-02-25 07:27:07', '2026-02-25 07:27:07');

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

--
-- Dumping data for table `gift_fillers`
--

INSERT INTO `gift_fillers` (`id`, `name`, `name_en`, `description`, `image`, `category`, `price`, `stock`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'ورد أحمر', NULL, NULL, NULL, 'flower', 8.00, 200, 1, 1, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(3, 'ورد أبيض', NULL, NULL, NULL, 'flower', 7.00, 180, 1, 2, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(4, 'توليب وردي', NULL, NULL, NULL, 'flower', 10.00, 150, 1, 3, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(5, 'شوكولاتة داكنة', NULL, NULL, NULL, 'chocolate', 25.00, 120, 1, 4, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(6, 'دبدوب صغير', NULL, NULL, NULL, 'accessory', 35.00, 60, 1, 5, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(7, 'بالون هيليوم', NULL, NULL, NULL, 'other', 15.00, 80, 1, 6, '2026-02-25 07:27:07', '2026-02-25 07:27:07');

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

--
-- Dumping data for table `gift_ribbons`
--

INSERT INTO `gift_ribbons` (`id`, `name`, `name_en`, `image`, `color`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'شريط أحمر', NULL, NULL, 'أحمر', 5.00, 1, 1, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(2, 'شريط ذهبي', NULL, NULL, 'ذهبي', 7.00, 1, 2, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(3, 'شريط أبيض', NULL, NULL, 'أبيض', 5.00, 1, 3, '2026-02-25 07:27:07', '2026-02-25 07:27:07');

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

--
-- Dumping data for table `gift_wrappings`
--

INSERT INTO `gift_wrappings` (`id`, `name`, `name_en`, `image`, `color`, `pattern`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'ورق كرافت', NULL, NULL, 'بني', 'سادة', 0.00, 1, 1, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(2, 'ورق وردي', NULL, NULL, 'وردي', 'سادة', 10.00, 1, 2, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(3, 'ورق ذهبي', NULL, NULL, 'ذهبي', 'لامع', 15.00, 1, 3, '2026-02-25 07:27:07', '2026-02-25 07:27:07');

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
(1, 1056, 'in', 1, 0, 1, 'Manual Restock', NULL, NULL, 1, NULL, '2026-02-26 02:47:22', '2026-02-26 02:47:22'),
(2, 1057, 'in', 10, 0, 10, 'Manual Restock', NULL, NULL, 1, NULL, '2026-03-09 03:37:12', '2026-03-09 03:37:12'),
(3, 1138, 'out', 1, 50, 49, 'sale', 'Order ORD-69AEFC9353683', 4, 1, NULL, '2026-03-09 14:00:03', '2026-03-09 14:00:03'),
(4, 1138, 'out', 1, 49, 48, 'sale', 'Order ORD-69AFA9FAE87E5', 6, 1, NULL, '2026-03-10 02:19:55', '2026-03-10 02:19:55');

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
(1, 'default', '{\"uuid\":\"a81fa3c9-79ed-4c78-b1f2-653cef5b291b\",\"displayName\":\"App\\\\Events\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:29:\\\"App\\\\Events\\\\OrderStatusUpdated\\\":1:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1773124893, 1773124893);

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
(129, '2026_03_10_000003_create_job_batches_table', 26);

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `channel`, `is_read`, `read_at`, `sent_at`, `created_at`, `updated_at`, `icon`, `color`, `link`) VALUES
(1, 5, 'order_created', 'Order Created', 'Your order ORD-69AEFC9353683 has been created', NULL, 'database', 0, NULL, NULL, '2026-03-09 14:00:03', '2026-03-09 14:00:03', NULL, 'blue', '/orders/4'),
(2, 5, 'order_created', 'Order Created', 'Your order ORD-69AFA9FAE87E5 has been created', NULL, 'database', 0, NULL, NULL, '2026-03-10 02:19:55', '2026-03-10 02:19:55', NULL, 'blue', '/orders/6'),
(3, 9, 'order_assigned', 'New Delivery Assignment', 'You have been assigned order ORD-69AEFC9353683', '{\"order_id\":4,\"assignment_id\":1}', 'database', 0, NULL, NULL, '2026-03-10 02:37:18', '2026-03-10 02:37:18', 'fa-truck', 'orange', '/dashboard/supervisor/order-assignment'),
(4, 5, 'order_out_for_delivery', 'Order Out for Delivery', 'Your order ORD-69AEFC9353683 is out for delivery', '{\"order_id\":4}', 'database', 0, NULL, NULL, '2026-03-10 02:37:18', '2026-03-10 02:37:18', 'fa-truck', 'orange', '/profile'),
(6, 5, 'order', 'Order Status Updated', 'Your order ORD-69AFA9FAE87E5 status changed: pending → confirmed', NULL, 'database', 0, NULL, NULL, '2026-03-10 03:41:25', '2026-03-10 03:41:25', 'fa-shopping-bag', 'blue', '/profile'),
(7, 9, 'order_assigned', 'New Delivery Assignment', 'You have been assigned order ORD-69AFA9FAE87E5', '{\"order_id\":6,\"assignment_id\":2}', 'database', 0, NULL, NULL, '2026-03-10 03:41:26', '2026-03-10 03:41:26', 'fa-truck', 'orange', '/dashboard/supervisor/order-assignment'),
(8, 5, 'order_out_for_delivery', 'Order Out for Delivery', 'Your order ORD-69AFA9FAE87E5 is out for delivery', '{\"order_id\":6}', 'database', 0, NULL, NULL, '2026-03-10 03:41:26', '2026-03-10 03:41:26', 'fa-truck', 'orange', '/profile'),
(9, 5, 'order_delivered', 'Order Delivered', 'Your order ORD-69AFA9FAE87E5 has been delivered.', '{\"order_id\":6}', 'database', 0, NULL, NULL, '2026-03-10 03:41:32', '2026-03-10 03:41:32', 'fa-shopping-bag', 'green', '/profile'),
(10, 3, 'order_completed', 'Order Completed', 'Order ORD-69AFA9FAE87E5 has been completed. Commission: 15', '{\"order_id\":6,\"commission\":15}', 'database', 0, NULL, NULL, '2026-03-10 03:41:32', '2026-03-10 03:41:32', 'fa-check-circle', 'blue', '/dashboard/vendor'),
(11, 5, 'order', 'Order Status Updated', 'Your order ORD-69AFA9FAE87E5 status changed: delivered → done', NULL, 'database', 0, NULL, NULL, '2026-03-10 03:42:50', '2026-03-10 03:42:50', 'fa-shopping-bag', 'blue', '/profile');

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
  `payment_receipt` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `delivery_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `service_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `estimated_delivery` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `customer_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `user_id`, `assigned_driver_id`, `assigned_at`, `assigned_by`, `recipient_name`, `phone`, `village`, `address_note`, `delivery_notes`, `latitude`, `longitude`, `delivery_method`, `store_id`, `status`, `is_completed`, `completed_at`, `revenue_recognized_at`, `payment_status`, `confirmation_token`, `confirmed_at`, `customer_signature`, `payment_receipt`, `payment_method`, `payment_reference`, `subtotal`, `total`, `delivery_cost`, `service_fee`, `tax_amount`, `shipping_cost`, `discount_amount`, `total_amount`, `commission_amount`, `shipping_address`, `billing_address`, `estimated_delivery`, `shipped_at`, `delivered_at`, `tracking_number`, `customer_notes`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-1770801370601', 5, 5, NULL, NULL, NULL, NULL, '+963994251800', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 0, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, 'الدفع عند الاستلام', NULL, 150.00, 25941.60, 25791.60, 0.00, 0.00, 0.00, 0.00, 25941.60, 0.00, 'السويداء - ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-11 09:16:10', NULL),
(2, 'ORD-1770878983237', 5, 5, NULL, NULL, NULL, NULL, '+963994251800', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'processing', 0, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, 'الدفع عند الاستلام', NULL, 150.00, 8413.20, 8263.20, 0.00, 0.00, 0.00, 0.00, 8413.20, 0.00, 'السويداء - ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-12 06:49:43', NULL),
(4, 'ORD-69AEFC9353683', 5, 5, 1, '2026-03-10 02:37:18', NULL, 'يوسف الحلبي', '+963994251800', 'المجدل', NULL, NULL, 32.7960321, 36.5064582, 'normal', NULL, 'out_for_delivery', 0, NULL, NULL, 'paid', NULL, NULL, NULL, NULL, 'cash', NULL, 150.00, 159.68, 0.00, 0.00, 0.00, 9.68, 0.00, 159.68, 0.00, '\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.796032119502506,\\\"lng\\\":36.50645820822727}}\"', NULL, '2026-03-16 14:00:03', NULL, NULL, NULL, NULL, NULL, '2026-03-09 14:00:03', '2026-03-10 02:37:18'),
(5, 'ORD-E2E-69AF483A2C0ED', 1, 1, NULL, NULL, NULL, 'Playwright Customer', '+10000000000', 'Test Village', 'E2E test', NULL, 33.5138000, 36.2765000, 'normal', NULL, 'pending', 0, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, 'cash', NULL, 50.00, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50.00, 0.00, '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 19:22:50', '2026-03-09 19:22:50'),
(6, 'ORD-69AFA9FAE87E5', 5, 5, 1, '2026-03-10 03:41:26', NULL, 'يوسف الحلبي', '+963994251800', 'المجدل, ناحية المزرعة, منطقة مركز السويداء, محافظة السويداء, سوريا', NULL, NULL, 32.7968198, 36.5076010, 'normal', 1, 'done', 1, '2026-03-10 03:42:50', '2026-03-10 03:42:50', 'paid', NULL, NULL, NULL, NULL, 'cash', NULL, 150.00, 161.16, 0.00, 0.00, 0.00, 11.16, 0.00, 161.16, 0.00, '\"{\\\"recipient_name\\\":\\\"\\\\u064a\\\\u0648\\\\u0633\\\\u0641 \\\\u0627\\\\u0644\\\\u062d\\\\u0644\\\\u0628\\\\u064a\\\",\\\"phone\\\":\\\"+963994251800\\\",\\\"village\\\":\\\"\\\\u0627\\\\u0644\\\\u0645\\\\u062c\\\\u062f\\\\u0644, \\\\u0646\\\\u0627\\\\u062d\\\\u064a\\\\u0629 \\\\u0627\\\\u0644\\\\u0645\\\\u0632\\\\u0631\\\\u0639\\\\u0629, \\\\u0645\\\\u0646\\\\u0637\\\\u0642\\\\u0629 \\\\u0645\\\\u0631\\\\u0643\\\\u0632 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0645\\\\u062d\\\\u0627\\\\u0641\\\\u0638\\\\u0629 \\\\u0627\\\\u0644\\\\u0633\\\\u0648\\\\u064a\\\\u062f\\\\u0627\\\\u0621, \\\\u0633\\\\u0648\\\\u0631\\\\u064a\\\\u0627\\\",\\\"address_note\\\":null,\\\"location\\\":{\\\"lat\\\":32.79681984341925,\\\"lng\\\":36.50760095084724}}\"', NULL, '2026-03-17 02:19:54', NULL, NULL, NULL, NULL, NULL, '2026-03-10 02:19:55', '2026-03-10 03:42:50');

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
  `product_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_snapshot`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `unit_price`, `quantity`, `total_price`, `product_snapshot`, `created_at`, `updated_at`) VALUES
(3, 4, 1138, 'yousef F alhalabi', 'STR001-0081', 150.00, 1, 150.00, NULL, '2026-03-09 14:00:03', '2026-03-09 14:00:03'),
(4, 6, 1138, 'yousef F alhalabi', 'STR001-0081', 150.00, 1, 150.00, NULL, '2026-03-10 02:19:55', '2026-03-10 02:19:55');

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

--
-- Dumping data for table `order_revenue_records`
--

INSERT INTO `order_revenue_records` (`id`, `order_id`, `financial_transaction_id`, `amount`, `currency`, `recognized_at`, `created_at`, `updated_at`) VALUES
(1, 6, 15, 161.16, 'USD', '2026-03-10 03:42:50', '2026-03-10 03:42:50', '2026-03-10 03:42:50');

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

--
-- Dumping data for table `payroll_records`
--

INSERT INTO `payroll_records` (`id`, `employee_id`, `pay_period`, `regular_hours`, `overtime_hours`, `regular_pay`, `overtime_pay`, `bonuses`, `deductions`, `gross_pay`, `net_pay`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`, `breakdown`) VALUES
(1, 1, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:40:06', '2026-02-04 06:39:03', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":2,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":8,\"sent_to_finance_at\":\"2026-02-04 09:38:30\",\"requested_date\":\"2026-02-04\"}'),
(2, 2, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:53:21', '2026-02-04 06:39:13', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":9,\"sent_to_finance_at\":\"2026-02-04 09:38:30\",\"requested_date\":\"2026-02-04\"}'),
(3, 3, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:53:22', '2026-02-04 06:39:15', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":10,\"sent_to_finance_at\":\"2026-02-04 09:38:30\",\"requested_date\":\"2026-02-04\"}'),
(4, 4, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:53:22', '2026-02-04 06:39:08', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":11,\"sent_to_finance_at\":\"2026-02-04 09:38:30\",\"requested_date\":\"2026-02-04\"}'),
(5, 5, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:53:22', '2026-02-04 06:39:10', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":12,\"sent_to_finance_at\":\"2026-02-04 09:38:30\",\"requested_date\":\"2026-02-04\"}'),
(6, 6, '2026-02', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'approved', NULL, NULL, '2026-02-04 04:53:23', '2026-02-04 06:38:52', '{\"period_start\":\"2026-02-01\",\"period_end\":\"2026-02-28\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":2,\"year\":2026,\"salary_tx_id\":13,\"sent_to_finance_at\":\"2026-02-04 09:38:31\",\"requested_date\":\"2026-02-04\"}'),
(7, 1, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:49', '2026-02-05 07:40:49', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}'),
(8, 2, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:49', '2026-02-05 07:40:49', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}'),
(9, 3, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:49', '2026-02-05 07:40:49', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}'),
(10, 4, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:49', '2026-02-05 07:40:49', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}'),
(11, 5, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:50', '2026-02-05 07:40:50', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}'),
(12, 6, '2026-03', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'draft', NULL, NULL, '2026-02-05 07:40:50', '2026-02-05 07:40:50', '{\"period_start\":\"2026-03-01\",\"period_end\":\"2026-03-31\",\"regular_hours\":0,\"days_worked\":0,\"days_absent\":0,\"days_late\":0,\"month\":3,\"year\":2026}');

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
(1, 'search_zero_results', 'daily', '2026-03-09', 2.0000, 'search', '{\"query\":\"\\u0639\\u0637\\u0631\"}', '2026-03-09 12:23:18', '2026-03-09 12:23:21');

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
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `rating` int(11) NOT NULL DEFAULT 0,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attributes`)),
  `seo_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_data`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `track_inventory` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `market` varchar(20) NOT NULL DEFAULT 'store',
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dimensions`)),
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
(1043, NULL, NULL, 0, 1024, 'باقة التوليب البيضاء', 'white-tulip', 'باقة توليب أبيض أنيقة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 189.00, NULL, 159.00, 0, 24, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1044, NULL, NULL, 0, 1023, 'صندوق هدايا فاخر ذهبي', 'gold-lux-box', 'صندوق ذهبي بتغليف أنيق', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 249.00, NULL, NULL, 0, 15, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1045, NULL, NULL, 0, 1025, 'شوكولاتة داكنة بلجيكية', 'dark-belgian-choco', 'شوكولاتة بلجيكية 70%', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49.00, NULL, 39.00, 0, 60, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:27:06', '2026-02-25 07:27:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1046, NULL, NULL, 0, 1026, 'تفاح لبناني', 'apple-lebanese', 'تفاح لبناني', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8.50, NULL, NULL, 0, 120, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1047, NULL, NULL, 0, 1026, 'موز إكوادوري', 'banana-ecuador', 'موز إكوادوري', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7.20, NULL, 6.50, 0, 200, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1048, NULL, NULL, 0, 1026, 'برتقال مصري', 'orange-egypt', 'برتقال مصري', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.90, NULL, NULL, 0, 150, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1049, NULL, NULL, 0, 1027, 'طماطم بلدي', 'tomato-local', 'طماطم بلدي', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4.00, NULL, 3.50, 0, 180, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1050, NULL, NULL, 0, 1027, 'خيار صوبي', 'cucumber-greenhouse', 'خيار صوبي', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6.25, NULL, NULL, 0, 140, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1051, NULL, NULL, 0, 1027, 'بطاطا سورية', 'potato-syrian', 'بطاطا سورية', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3.20, NULL, NULL, 0, 300, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1052, NULL, NULL, 0, 1028, 'حليب بقر طازج', 'fresh-milk', 'حليب بقر طازج', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9.90, NULL, NULL, 0, 80, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1053, NULL, NULL, 0, 1028, 'لبنة بقريّة', 'labneh', 'لبنة بقريّة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14.00, NULL, 12.50, 0, 60, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1054, NULL, NULL, 0, 1029, 'خبز عربي', 'arabic-bread', 'خبز عربي', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2.00, NULL, NULL, 0, 400, 10, NULL, 0, 0, NULL, NULL, 1, 1, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1055, NULL, NULL, 0, 1029, 'كرواسون زبدة', 'butter-croissant', 'كرواسون زبدة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3.50, NULL, NULL, 0, 90, 10, NULL, 0, 0, NULL, NULL, 1, 0, 1, 'pending', 'mart', NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1056, 1, NULL, 0, 1030, 'Electronics Product 1', 'electronics-product-1', 'Electronics Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-001', 250.00, NULL, NULL, 42, 1, 10, '[\"\\/images\\/category\\/2.1phone.jpeg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1057, 1, NULL, 0, 1030, 'Electronics Product 2', 'electronics-product-2', 'Electronics Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-002', 233.00, NULL, NULL, 103, 10, 10, '[\"\\/images\\/category\\/2.2laptop.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1058, 1, NULL, 0, 1030, 'Electronics Product 3', 'electronics-product-3', 'Electronics Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-003', 122.00, NULL, NULL, 137, 0, 10, '[\"\\/images\\/category\\/2.3tap.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1059, 1, NULL, 0, 1030, 'Electronics Product 4', 'electronics-product-4', 'Electronics Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-004', 270.00, NULL, NULL, 214, 0, 10, '[\"\\/images\\/category\\/2.4smartWatch.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1060, 1, NULL, 0, 1030, 'Electronics Product 5', 'electronics-product-5', 'Electronics Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-005', 79.00, NULL, NULL, 241, 0, 10, '[\"\\/images\\/category\\/2.5earbuds.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1061, 1, NULL, 0, 1030, 'Electronics Product 6', 'electronics-product-6', 'Electronics Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-006', 111.00, NULL, NULL, 186, 0, 10, '[\"\\/images\\/category\\/2.6TV.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1062, 1, NULL, 0, 1030, 'Electronics Product 7', 'electronics-product-7', 'Electronics Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-007', 187.00, NULL, NULL, 170, 0, 10, '[\"\\/images\\/category\\/2.7cameras.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1063, 1, NULL, 0, 1030, 'Electronics Product 8', 'electronics-product-8', 'Electronics Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ELECTRONICS-008', 126.00, NULL, NULL, 194, 0, 10, '[\"\\/images\\/category\\/2.1phone.jpeg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1064, 1, NULL, 0, 1031, 'Fashion Product 1', 'fashion-product-1', 'Fashion Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-001', 215.00, NULL, NULL, 142, 0, 10, '[\"\\/images\\/category\\/1.1men.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1065, 1, NULL, 0, 1031, 'Fashion Product 2', 'fashion-product-2', 'Fashion Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-002', 75.00, NULL, NULL, 56, 0, 10, '[\"\\/images\\/category\\/1.2women.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1066, 1, NULL, 0, 1031, 'Fashion Product 3', 'fashion-product-3', 'Fashion Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-003', 171.00, NULL, NULL, 74, 0, 10, '[\"\\/images\\/category\\/1.4kids.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1067, 1, NULL, 0, 1031, 'Fashion Product 4', 'fashion-product-4', 'Fashion Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-004', 198.00, NULL, NULL, 22, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1068, 1, NULL, 0, 1031, 'Fashion Product 5', 'fashion-product-5', 'Fashion Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-005', 234.00, NULL, NULL, 217, 0, 10, '[\"\\/images\\/category\\/1.6shoes.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1069, 1, NULL, 0, 1031, 'Fashion Product 6', 'fashion-product-6', 'Fashion Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-006', 280.00, NULL, NULL, 15, 0, 10, '[\"\\/images\\/category\\/1.7menShoes.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1070, 1, NULL, 0, 1031, 'Fashion Product 7', 'fashion-product-7', 'Fashion Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-007', 78.00, NULL, NULL, 39, 0, 10, '[\"\\/images\\/category\\/1.8BabyShoes.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1071, 1, NULL, 0, 1031, 'Fashion Product 8', 'fashion-product-8', 'Fashion Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FASHION-008', 279.00, NULL, NULL, 242, 0, 10, '[\"\\/images\\/category\\/1.3baby.jpeg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1072, 1, NULL, 0, 1032, 'Books Product 1', 'books-product-1', 'Books Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-001', 217.00, NULL, NULL, 222, 0, 10, '[\"\\/images\\/category\\/6.2.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1073, 1, NULL, 0, 1032, 'Books Product 2', 'books-product-2', 'Books Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-002', 188.00, NULL, NULL, 178, 0, 10, '[\"\\/images\\/category\\/6.4bottles.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1074, 1, NULL, 0, 1032, 'Books Product 3', 'books-product-3', 'Books Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-003', 265.00, NULL, NULL, 205, 0, 10, '[\"\\/images\\/category\\/6.3stickers.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1075, 1, NULL, 0, 1032, 'Books Product 4', 'books-product-4', 'Books Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-004', 107.00, NULL, NULL, 111, 0, 10, '[\"\\/images\\/category\\/6.1bags.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1076, 1, NULL, 0, 1032, 'Books Product 5', 'books-product-5', 'Books Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-005', 137.00, NULL, NULL, 18, 0, 10, '[\"\\/images\\/category\\/6.2.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1077, 1, NULL, 0, 1032, 'Books Product 6', 'books-product-6', 'Books Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-006', 53.00, NULL, NULL, 90, 0, 10, '[\"\\/images\\/category\\/6.4bottles.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1078, 1, NULL, 0, 1032, 'Books Product 7', 'books-product-7', 'Books Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-007', 171.00, NULL, NULL, 60, 0, 10, '[\"\\/images\\/category\\/6.3stickers.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1079, 1, NULL, 0, 1032, 'Books Product 8', 'books-product-8', 'Books Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOOKS-008', 77.00, NULL, NULL, 24, 0, 10, '[\"\\/images\\/category\\/6.1bags.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1080, 1, NULL, 0, 1033, 'Shoes Product 1', 'shoes-product-1', 'Shoes Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-001', 78.00, NULL, NULL, 23, 0, 10, '[\"\\/images\\/category\\/1.6shoes.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1081, 1, NULL, 0, 1033, 'Shoes Product 2', 'shoes-product-2', 'Shoes Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-002', 84.00, NULL, NULL, 157, 0, 10, '[\"\\/images\\/category\\/1.7menShoes.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1082, 1, NULL, 0, 1033, 'Shoes Product 3', 'shoes-product-3', 'Shoes Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-003', 232.00, NULL, NULL, 187, 0, 10, '[\"\\/images\\/category\\/1.8BabyShoes.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1083, 1, NULL, 0, 1033, 'Shoes Product 4', 'shoes-product-4', 'Shoes Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-004', 253.00, NULL, NULL, 245, 0, 10, '[\"\\/images\\/category\\/1.6shoes.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1084, 1, NULL, 0, 1033, 'Shoes Product 5', 'shoes-product-5', 'Shoes Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-005', 196.00, NULL, NULL, 66, 0, 10, '[\"\\/images\\/category\\/1.7menShoes.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:51', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1085, 1, NULL, 0, 1033, 'Shoes Product 6', 'shoes-product-6', 'Shoes Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-006', 114.00, NULL, NULL, 226, 0, 10, '[\"\\/images\\/category\\/1.8BabyShoes.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1086, 1, NULL, 0, 1033, 'Shoes Product 7', 'shoes-product-7', 'Shoes Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-007', 238.00, NULL, NULL, 24, 0, 10, '[\"\\/images\\/category\\/1.6shoes.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1087, 1, NULL, 0, 1033, 'Shoes Product 8', 'shoes-product-8', 'Shoes Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SHOES-008', 120.00, NULL, NULL, 122, 0, 10, '[\"\\/images\\/category\\/1.7menShoes.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1088, 1, NULL, 0, 1034, 'Bags Product 1', 'bags-product-1', 'Bags Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-001', 98.00, NULL, NULL, 165, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1089, 1, NULL, 0, 1034, 'Bags Product 2', 'bags-product-2', 'Bags Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-002', 162.00, NULL, NULL, 148, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1090, 1, NULL, 0, 1034, 'Bags Product 3', 'bags-product-3', 'Bags Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-003', 71.00, NULL, NULL, 229, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1091, 1, NULL, 0, 1034, 'Bags Product 4', 'bags-product-4', 'Bags Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-004', 49.00, NULL, NULL, 163, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1092, 1, NULL, 0, 1034, 'Bags Product 5', 'bags-product-5', 'Bags Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-005', 92.00, NULL, NULL, 133, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1093, 1, NULL, 0, 1034, 'Bags Product 6', 'bags-product-6', 'Bags Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-006', 245.00, NULL, NULL, 137, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1094, 1, NULL, 0, 1034, 'Bags Product 7', 'bags-product-7', 'Bags Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-007', 235.00, NULL, NULL, 58, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1095, 1, NULL, 0, 1034, 'Bags Product 8', 'bags-product-8', 'Bags Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BAGS-008', 180.00, NULL, NULL, 78, 0, 10, '[\"\\/images\\/category\\/1.5bags.jpeg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1096, 1, NULL, 0, 1035, 'Toys Product 1', 'toys-product-1', 'Toys Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-001', 113.00, NULL, NULL, 11, 0, 10, '[\"\\/images\\/category\\/3.1education.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1097, 1, NULL, 0, 1035, 'Toys Product 2', 'toys-product-2', 'Toys Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-002', 103.00, NULL, NULL, 70, 0, 10, '[\"\\/images\\/category\\/3.2building.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1098, 1, NULL, 0, 1035, 'Toys Product 3', 'toys-product-3', 'Toys Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-003', 105.00, NULL, NULL, 81, 0, 10, '[\"\\/images\\/category\\/3.3remot.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1099, 1, NULL, 0, 1035, 'Toys Product 4', 'toys-product-4', 'Toys Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-004', 212.00, NULL, NULL, 236, 0, 10, '[\"\\/images\\/category\\/3.4doll.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1100, 1, NULL, 0, 1035, 'Toys Product 5', 'toys-product-5', 'Toys Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-005', 197.00, NULL, NULL, 34, 0, 10, '[\"\\/images\\/category\\/3.1education.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1101, 1, NULL, 0, 1035, 'Toys Product 6', 'toys-product-6', 'Toys Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-006', 154.00, NULL, NULL, 82, 0, 10, '[\"\\/images\\/category\\/3.2building.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1102, 1, NULL, 0, 1035, 'Toys Product 7', 'toys-product-7', 'Toys Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-007', 142.00, NULL, NULL, 93, 0, 10, '[\"\\/images\\/category\\/3.3remot.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1103, 1, NULL, 0, 1035, 'Toys Product 8', 'toys-product-8', 'Toys Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOYS-008', 60.00, NULL, NULL, 86, 0, 10, '[\"\\/images\\/category\\/3.4doll.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1104, 1, NULL, 0, 1036, 'Sports Product 1', 'sports-product-1', 'Sports Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-001', 182.00, NULL, NULL, 217, 0, 10, '[\"\\/images\\/category\\/4.1fitness.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1105, 1, NULL, 0, 1036, 'Sports Product 2', 'sports-product-2', 'Sports Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-002', 257.00, NULL, NULL, 242, 0, 10, '[\"\\/images\\/category\\/4.2sportwear.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1106, 1, NULL, 0, 1036, 'Sports Product 3', 'sports-product-3', 'Sports Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-003', 157.00, NULL, NULL, 93, 0, 10, '[\"\\/images\\/category\\/4.1fitness.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1107, 1, NULL, 0, 1036, 'Sports Product 4', 'sports-product-4', 'Sports Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-004', 112.00, NULL, NULL, 29, 0, 10, '[\"\\/images\\/category\\/4.2sportwear.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1108, 1, NULL, 0, 1036, 'Sports Product 5', 'sports-product-5', 'Sports Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-005', 244.00, NULL, NULL, 209, 0, 10, '[\"\\/images\\/category\\/4.1fitness.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1109, 1, NULL, 0, 1036, 'Sports Product 6', 'sports-product-6', 'Sports Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-006', 180.00, NULL, NULL, 135, 0, 10, '[\"\\/images\\/category\\/4.2sportwear.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1110, 1, NULL, 0, 1036, 'Sports Product 7', 'sports-product-7', 'Sports Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-007', 264.00, NULL, NULL, 157, 0, 10, '[\"\\/images\\/category\\/4.1fitness.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1111, 1, NULL, 0, 1036, 'Sports Product 8', 'sports-product-8', 'Sports Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SPORTS-008', 164.00, NULL, NULL, 114, 0, 10, '[\"\\/images\\/category\\/4.2sportwear.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1112, 1, NULL, 0, 1037, 'Jewelry Product 1', 'jewelry-product-1', 'Jewelry Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-001', 103.00, NULL, NULL, 222, 0, 10, '[\"\\/images\\/category\\/5.1jewelry.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1113, 1, NULL, 0, 1037, 'Jewelry Product 2', 'jewelry-product-2', 'Jewelry Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-002', 161.00, NULL, NULL, 169, 0, 10, '[\"\\/images\\/category\\/5.2watch.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1114, 1, NULL, 0, 1037, 'Jewelry Product 3', 'jewelry-product-3', 'Jewelry Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-003', 124.00, NULL, NULL, 69, 0, 10, '[\"\\/images\\/category\\/5.4watch2.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1115, 1, NULL, 0, 1037, 'Jewelry Product 4', 'jewelry-product-4', 'Jewelry Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-004', 212.00, NULL, NULL, 241, 0, 10, '[\"\\/images\\/category\\/5.3sunglass.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1116, 1, NULL, 0, 1037, 'Jewelry Product 5', 'jewelry-product-5', 'Jewelry Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-005', 98.00, NULL, NULL, 117, 0, 10, '[\"\\/images\\/category\\/5.1jewelry.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1117, 1, NULL, 0, 1037, 'Jewelry Product 6', 'jewelry-product-6', 'Jewelry Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-006', 78.00, NULL, NULL, 211, 0, 10, '[\"\\/images\\/category\\/5.2watch.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1118, 1, NULL, 0, 1037, 'Jewelry Product 7', 'jewelry-product-7', 'Jewelry Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-007', 171.00, NULL, NULL, 118, 0, 10, '[\"\\/images\\/category\\/5.4watch2.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1119, 1, NULL, 0, 1037, 'Jewelry Product 8', 'jewelry-product-8', 'Jewelry Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'JEWELRY-008', 78.00, NULL, NULL, 129, 0, 10, '[\"\\/images\\/category\\/5.3sunglass.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1120, 1, NULL, 0, 1038, 'Care Product 1', 'care-product-1', 'Care Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-001', 219.00, NULL, NULL, 77, 0, 10, '[\"\\/images\\/category\\/7.1.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1121, 1, NULL, 0, 1038, 'Care Product 2', 'care-product-2', 'Care Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-002', 68.00, NULL, NULL, 26, 0, 10, '[\"\\/images\\/category\\/7.2.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1122, 1, NULL, 0, 1038, 'Care Product 3', 'care-product-3', 'Care Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-003', 125.00, NULL, NULL, 227, 0, 10, '[\"\\/images\\/category\\/7.4.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1123, 1, NULL, 0, 1038, 'Care Product 4', 'care-product-4', 'Care Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-004', 135.00, NULL, NULL, 149, 0, 10, '[\"\\/images\\/category\\/7.8.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1124, 1, NULL, 0, 1038, 'Care Product 5', 'care-product-5', 'Care Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-005', 175.00, NULL, NULL, 242, 0, 10, '[\"\\/images\\/category\\/7.1.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1125, 1, NULL, 0, 1038, 'Care Product 6', 'care-product-6', 'Care Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-006', 182.00, NULL, NULL, 101, 0, 10, '[\"\\/images\\/category\\/7.2.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1126, 1, NULL, 0, 1038, 'Care Product 7', 'care-product-7', 'Care Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-007', 202.00, NULL, NULL, 26, 0, 10, '[\"\\/images\\/category\\/7.4.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1127, 1, NULL, 0, 1038, 'Care Product 8', 'care-product-8', 'Care Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CARE-008', 78.00, NULL, NULL, 182, 0, 10, '[\"\\/images\\/category\\/7.8.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1128, 1, NULL, 0, 1039, 'Kitchen Product 1', 'kitchen-product-1', 'Kitchen Description for product 1', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-001', 57.00, NULL, NULL, 117, 0, 10, '[\"\\/images\\/category\\/8.1.jpg\"]', 5, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1129, 1, NULL, 0, 1039, 'Kitchen Product 2', 'kitchen-product-2', 'Kitchen Description for product 2', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-002', 202.00, NULL, NULL, 55, 0, 10, '[\"\\/images\\/category\\/8.2.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1130, 1, NULL, 0, 1039, 'Kitchen Product 3', 'kitchen-product-3', 'Kitchen Description for product 3', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-003', 31.00, NULL, NULL, 177, 0, 10, '[\"\\/images\\/category\\/8.1.jpg\"]', 3, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1131, 1, NULL, 0, 1039, 'Kitchen Product 4', 'kitchen-product-4', 'Kitchen Description for product 4', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-004', 130.00, NULL, NULL, 176, 0, 10, '[\"\\/images\\/category\\/8.2.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1132, 1, NULL, 0, 1039, 'Kitchen Product 5', 'kitchen-product-5', 'Kitchen Description for product 5', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-005', 143.00, NULL, NULL, 180, 0, 10, '[\"\\/images\\/category\\/8.1.jpg\"]', 2, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1133, 1, NULL, 0, 1039, 'Kitchen Product 6', 'kitchen-product-6', 'Kitchen Description for product 6', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-006', 126.00, NULL, NULL, 250, 0, 10, '[\"\\/images\\/category\\/8.2.jpg\"]', 1, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1134, 1, NULL, 0, 1039, 'Kitchen Product 7', 'kitchen-product-7', 'Kitchen Description for product 7', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-007', 192.00, NULL, NULL, 150, 0, 10, '[\"\\/images\\/category\\/8.1.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1135, 1, NULL, 0, 1039, 'Kitchen Product 8', 'kitchen-product-8', 'Kitchen Description for product 8', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KITCHEN-008', 193.00, NULL, NULL, 32, 0, 10, '[\"\\/images\\/category\\/8.2.jpg\"]', 4, 0, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-02-25 07:28:52', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1138, 1, 2, 1, 1023, 'yousef F alhalabi', 'yousef-f-alhalabi-J9yP52', 'fdsfs', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'STR001-0081', 150.00, 100.00, NULL, 0, 48, 10, '[\"products\\/8YLcFDQVjb82y3akmhOF9ihbrSUCk33PghRQeACO.jpg\"]', 0, 0, NULL, NULL, 1, 0, 1, 'active', 'store', NULL, NULL, '2026-03-09 06:34:41', '2026-03-10 02:19:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1139, NULL, NULL, 0, 1040, 'باقة الورود الحمراء الفاخرة', 'red-roses-premium', 'باقة جميلة من 24 وردة حمراء طازة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'تحتوي على: 24 وردة حمراء طازة، أوراق خضراء مختارة، تغليف فاخر', NULL, NULL, NULL, 'RED-303', 299.99, NULL, NULL, 50, 0, 10, NULL, 5, 45, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL);
INSERT INTO `products` (`id`, `store_id`, `trader_id`, `is_trader_product`, `category_id`, `name`, `slug`, `description`, `condition`, `pages`, `genre`, `author`, `age_range`, `brand`, `material`, `size`, `color`, `details`, `meta_title`, `meta_description`, `short_description`, `sku`, `price`, `cost_price`, `discount_price`, `stock`, `stock_quantity`, `low_stock_threshold`, `images`, `rating`, `reviews_count`, `attributes`, `seo_data`, `is_active`, `is_featured`, `track_inventory`, `status`, `market`, `weight`, `dimensions`, `created_at`, `updated_at`, `fit`, `sleeve_length`, `pattern`, `shoe_size`, `shoe_type`, `screen_size`, `storage`, `ram`, `processor`, `battery`, `connectivity`, `publisher`, `language`, `format`, `toy_type`, `room`, `capacity`, `power`, `sport_type`, `skill_level`, `warranty`, `free_shipping`, `on_sale`, `rejection_reason`, `reviewed_by`, `reviewed_at`) VALUES
(1140, NULL, NULL, 0, 1040, 'باقة الزهور المتعددة الألوان', 'mixed-flowers', 'مزيج جميل من الزهور الملونة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'تحتوي على: ورود، ستاتس، يوكاليبتس، تغليف فاخر', NULL, NULL, NULL, 'MIX-276', 199.99, NULL, 149.99, 60, 0, 10, NULL, 4, 28, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1141, NULL, NULL, 0, 1040, 'باقة الزنبق البيضاء', 'white-lilies', 'باقة أنيقة من الزنبق الأبيض', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'WHI-931', 249.99, NULL, NULL, 40, 0, 10, NULL, 5, 22, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1142, NULL, NULL, 0, 1041, 'صندوق الهدايا الذهبي', 'gold-gift-box', 'صندوق هدايا فاخر بألوان ذهبية', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GOL-549', 149.99, NULL, NULL, 30, 0, 10, NULL, 4, 15, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1143, NULL, NULL, 0, 1041, 'مجموعة الشموع العطرية', 'scented-candles', 'مجموعة شموع عطرية فاخرة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SCE-769', 99.99, NULL, 79.99, 50, 0, 10, NULL, 5, 34, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1144, NULL, NULL, 0, 1042, 'صندوق الشوكولاتة البلجيكية الفاخرة', 'belgian-chocolates', 'شوكولاتة بلجيكية عالية الجودة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'تحتوي على 20 قطعة شوكولاتة بنكهات متنوعة', NULL, NULL, NULL, 'BEL-845', 179.99, NULL, NULL, 40, 0, 10, NULL, 5, 56, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1145, NULL, NULL, 0, 1042, 'حلويات الفواكه الطازة', 'fruit-sweets', 'حلويات لذيذة بنكهات الفواكه', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FRU-583', 89.99, NULL, NULL, 45, 0, 10, NULL, 4, 18, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1146, NULL, NULL, 0, 1043, 'باقة البالونات الملونة', 'colorful-balloons', 'بالونات ملونة للحفلات والمناسبات', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COL-772', 69.99, NULL, NULL, 100, 0, 10, NULL, 4, 12, NULL, NULL, 1, 1, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL),
(1147, NULL, NULL, 0, 1043, 'بالونات الهيليوم الفضية', 'silver-helium-balloons', 'بالونات هيليوم فضية أنيقة', 'new', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SIL-885', 119.99, NULL, 99.99, 60, 0, 10, NULL, 5, 27, NULL, NULL, 1, 0, 1, 'pending', 'store', NULL, NULL, '2026-03-09 18:10:25', '2026-03-09 18:10:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL);

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
(17, 1046, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(18, 1046, 'origin', NULL, NULL, 'لبناني', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(19, 1047, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(20, 1047, 'origin', NULL, NULL, 'مستورد', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(21, 1048, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(22, 1048, 'origin', NULL, NULL, 'مصري', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(23, 1049, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(24, 1049, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(25, 1050, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(26, 1050, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(27, 1051, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(28, 1051, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(29, 1052, 'unit', NULL, NULL, 'لتر', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(30, 1052, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(31, 1053, 'unit', NULL, NULL, 'كغ', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(32, 1053, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(33, 1054, 'unit', NULL, NULL, 'ربطة', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(34, 1054, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(35, 1055, 'unit', NULL, NULL, 'قطعة', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(36, 1055, 'origin', NULL, NULL, 'محلي', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:27:07', '2026-02-25 07:27:07'),
(37, 1056, 'brand', NULL, NULL, 'Samsung', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(38, 1056, 'color', NULL, NULL, 'Black', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(39, 1057, 'brand', NULL, NULL, 'Apple', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(40, 1057, 'color', NULL, NULL, 'White', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(41, 1058, 'brand', NULL, NULL, 'HP', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(42, 1058, 'color', NULL, NULL, 'Silver', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(43, 1059, 'brand', NULL, NULL, 'Sony', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(44, 1059, 'color', NULL, NULL, 'Gray', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(45, 1060, 'brand', NULL, NULL, 'Samsung', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(46, 1060, 'color', NULL, NULL, 'Black', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(47, 1061, 'brand', NULL, NULL, 'Apple', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(48, 1061, 'color', NULL, NULL, 'White', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(49, 1062, 'brand', NULL, NULL, 'HP', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(50, 1062, 'color', NULL, NULL, 'Silver', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(51, 1063, 'brand', NULL, NULL, 'Sony', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(52, 1063, 'color', NULL, NULL, 'Gray', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(53, 1064, 'size', NULL, NULL, 'M', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(54, 1064, 'material', NULL, NULL, 'Cotton', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(55, 1065, 'size', NULL, NULL, 'L', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(56, 1065, 'material', NULL, NULL, 'Wool', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(57, 1066, 'size', NULL, NULL, 'XL', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(58, 1066, 'material', NULL, NULL, 'Polyester', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(59, 1067, 'size', NULL, NULL, 'S', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(60, 1067, 'material', NULL, NULL, 'Silk', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(61, 1068, 'size', NULL, NULL, 'M', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(62, 1068, 'material', NULL, NULL, 'Cotton', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(63, 1069, 'size', NULL, NULL, 'L', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(64, 1069, 'material', NULL, NULL, 'Wool', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(65, 1070, 'size', NULL, NULL, 'XL', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(66, 1070, 'material', NULL, NULL, 'Polyester', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(67, 1071, 'size', NULL, NULL, 'S', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(68, 1071, 'material', NULL, NULL, 'Silk', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(69, 1072, 'author', NULL, NULL, 'Author A', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(70, 1072, 'year', NULL, NULL, '2001', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(71, 1073, 'author', NULL, NULL, 'Author B', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(72, 1073, 'year', NULL, NULL, '2002', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(73, 1074, 'author', NULL, NULL, 'Author C', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(74, 1074, 'year', NULL, NULL, '2003', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(75, 1075, 'author', NULL, NULL, 'Author D', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(76, 1075, 'year', NULL, NULL, '2004', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(77, 1076, 'author', NULL, NULL, 'Author E', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(78, 1076, 'year', NULL, NULL, '2005', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(79, 1077, 'author', NULL, NULL, 'Author F', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(80, 1077, 'year', NULL, NULL, '2006', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(81, 1078, 'author', NULL, NULL, 'Author G', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(82, 1078, 'year', NULL, NULL, '2007', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(83, 1079, 'author', NULL, NULL, 'Author H', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(84, 1079, 'year', NULL, NULL, '2008', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(85, 1080, 'type', NULL, NULL, 'sport', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(86, 1080, 'gender', NULL, NULL, 'men', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(87, 1081, 'type', NULL, NULL, 'formal', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(88, 1081, 'gender', NULL, NULL, 'women', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(89, 1082, 'type', NULL, NULL, 'sandal', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(90, 1082, 'gender', NULL, NULL, 'kids', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(91, 1083, 'type', NULL, NULL, 'boot', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(92, 1083, 'gender', NULL, NULL, 'unisex', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:51', '2026-02-25 07:28:51'),
(93, 1084, 'type', NULL, NULL, 'sport', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(94, 1084, 'gender', NULL, NULL, 'men', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(95, 1085, 'type', NULL, NULL, 'formal', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(96, 1085, 'gender', NULL, NULL, 'women', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(97, 1086, 'type', NULL, NULL, 'sandal', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(98, 1086, 'gender', NULL, NULL, 'kids', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(99, 1087, 'type', NULL, NULL, 'boot', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(100, 1087, 'gender', NULL, NULL, 'unisex', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(101, 1088, 'type', NULL, NULL, 'handbag', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(102, 1088, 'material', NULL, NULL, 'leather', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(103, 1089, 'type', NULL, NULL, 'backpack', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(104, 1089, 'material', NULL, NULL, 'fabric', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(105, 1090, 'type', NULL, NULL, 'tote', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(106, 1090, 'material', NULL, NULL, 'nylon', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(107, 1091, 'type', NULL, NULL, 'crossbody', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(108, 1091, 'material', NULL, NULL, 'synthetic', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(109, 1092, 'type', NULL, NULL, 'handbag', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(110, 1092, 'material', NULL, NULL, 'leather', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(111, 1093, 'type', NULL, NULL, 'backpack', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(112, 1093, 'material', NULL, NULL, 'fabric', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(113, 1094, 'type', NULL, NULL, 'tote', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(114, 1094, 'material', NULL, NULL, 'nylon', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(115, 1095, 'type', NULL, NULL, 'crossbody', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(116, 1095, 'material', NULL, NULL, 'synthetic', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(117, 1096, 'age', NULL, NULL, '3+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(118, 1096, 'type', NULL, NULL, 'education', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(119, 1097, 'age', NULL, NULL, '5+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(120, 1097, 'type', NULL, NULL, 'building', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(121, 1098, 'age', NULL, NULL, '8+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(122, 1098, 'type', NULL, NULL, 'remote', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(123, 1099, 'age', NULL, NULL, '12+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(124, 1099, 'type', NULL, NULL, 'doll', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(125, 1100, 'age', NULL, NULL, '3+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(126, 1100, 'type', NULL, NULL, 'education', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(127, 1101, 'age', NULL, NULL, '5+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(128, 1101, 'type', NULL, NULL, 'building', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(129, 1102, 'age', NULL, NULL, '8+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(130, 1102, 'type', NULL, NULL, 'remote', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(131, 1103, 'age', NULL, NULL, '12+', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(132, 1103, 'type', NULL, NULL, 'doll', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(133, 1104, 'type', NULL, NULL, 'fitness', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(134, 1104, 'brand', NULL, NULL, 'Nike', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(135, 1105, 'type', NULL, NULL, 'wear', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(136, 1105, 'brand', NULL, NULL, 'Adidas', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(137, 1106, 'type', NULL, NULL, 'accessory', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(138, 1106, 'brand', NULL, NULL, 'Puma', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(139, 1107, 'type', NULL, NULL, 'gear', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(140, 1107, 'brand', NULL, NULL, 'Reebok', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(141, 1108, 'type', NULL, NULL, 'fitness', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(142, 1108, 'brand', NULL, NULL, 'Nike', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(143, 1109, 'type', NULL, NULL, 'wear', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(144, 1109, 'brand', NULL, NULL, 'Adidas', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(145, 1110, 'type', NULL, NULL, 'accessory', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(146, 1110, 'brand', NULL, NULL, 'Puma', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(147, 1111, 'type', NULL, NULL, 'gear', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(148, 1111, 'brand', NULL, NULL, 'Reebok', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(149, 1112, 'type', NULL, NULL, 'ring', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(150, 1112, 'material', NULL, NULL, 'gold', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(151, 1113, 'type', NULL, NULL, 'necklace', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(152, 1113, 'material', NULL, NULL, 'silver', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(153, 1114, 'type', NULL, NULL, 'watch', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(154, 1114, 'material', NULL, NULL, 'steel', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(155, 1115, 'type', NULL, NULL, 'sunglass', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(156, 1115, 'material', NULL, NULL, 'alloy', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(157, 1116, 'type', NULL, NULL, 'ring', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(158, 1116, 'material', NULL, NULL, 'gold', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(159, 1117, 'type', NULL, NULL, 'necklace', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(160, 1117, 'material', NULL, NULL, 'silver', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(161, 1118, 'type', NULL, NULL, 'watch', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(162, 1118, 'material', NULL, NULL, 'steel', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(163, 1119, 'type', NULL, NULL, 'sunglass', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(164, 1119, 'material', NULL, NULL, 'alloy', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(165, 1120, 'type', NULL, NULL, 'hair', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(166, 1120, 'brand', NULL, NULL, 'BrandA', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(167, 1121, 'type', NULL, NULL, 'nail', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(168, 1121, 'brand', NULL, NULL, 'BrandB', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(169, 1122, 'type', NULL, NULL, 'perfume', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(170, 1122, 'brand', NULL, NULL, 'BrandC', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(171, 1123, 'type', NULL, NULL, 'skin', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(172, 1123, 'brand', NULL, NULL, 'BrandD', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(173, 1124, 'type', NULL, NULL, 'hair', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(174, 1124, 'brand', NULL, NULL, 'BrandA', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(175, 1125, 'type', NULL, NULL, 'nail', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(176, 1125, 'brand', NULL, NULL, 'BrandB', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(177, 1126, 'type', NULL, NULL, 'perfume', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(178, 1126, 'brand', NULL, NULL, 'BrandC', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(179, 1127, 'type', NULL, NULL, 'skin', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(180, 1127, 'brand', NULL, NULL, 'BrandD', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(181, 1128, 'type', NULL, NULL, 'bottle', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(182, 1128, 'material', NULL, NULL, 'plastic', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(183, 1129, 'type', NULL, NULL, 'utensil', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(184, 1129, 'material', NULL, NULL, 'steel', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(185, 1130, 'type', NULL, NULL, 'appliance', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(186, 1130, 'material', NULL, NULL, 'glass', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(187, 1131, 'type', NULL, NULL, 'container', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(188, 1131, 'material', NULL, NULL, 'wood', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(189, 1132, 'type', NULL, NULL, 'bottle', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(190, 1132, 'material', NULL, NULL, 'plastic', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(191, 1133, 'type', NULL, NULL, 'utensil', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(192, 1133, 'material', NULL, NULL, 'steel', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(193, 1134, 'type', NULL, NULL, 'appliance', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(194, 1134, 'material', NULL, NULL, 'glass', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(195, 1135, 'type', NULL, NULL, 'container', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:52', '2026-02-25 07:28:52'),
(196, 1135, 'material', NULL, NULL, 'wood', NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, '2026-02-25 07:28:53', '2026-02-25 07:28:53');

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

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `sku`, `price`, `stock`, `attributes`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1043, 'Small - Red', 'VAR-1043-SR', 189.00, 35, '{\"size\":\"Small\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(2, 1043, 'Small - Blue', 'VAR-1043-SB', 189.00, 34, '{\"size\":\"Small\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(3, 1043, 'Medium - Red', 'VAR-1043-MR', 194.00, 19, '{\"size\":\"Medium\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(4, 1043, 'Medium - Blue', 'VAR-1043-MB', 194.00, 22, '{\"size\":\"Medium\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(5, 1043, 'Large - Red', 'VAR-1043-LR', 199.00, 19, '{\"size\":\"Large\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(6, 1043, 'Large - Blue', 'VAR-1043-LB', 199.00, 45, '{\"size\":\"Large\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(7, 1043, 'XL - Red', 'VAR-1043-XR', 204.00, 48, '{\"size\":\"XL\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(8, 1043, 'XL - Blue', 'VAR-1043-XB', 204.00, 20, '{\"size\":\"XL\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(9, 1044, 'Small - Red', 'VAR-1044-SR', 249.00, 31, '{\"size\":\"Small\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(10, 1044, 'Small - Blue', 'VAR-1044-SB', 249.00, 18, '{\"size\":\"Small\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(11, 1044, 'Medium - Red', 'VAR-1044-MR', 254.00, 9, '{\"size\":\"Medium\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(12, 1044, 'Medium - Blue', 'VAR-1044-MB', 254.00, 30, '{\"size\":\"Medium\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(13, 1044, 'Large - Red', 'VAR-1044-LR', 259.00, 26, '{\"size\":\"Large\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(14, 1044, 'Large - Blue', 'VAR-1044-LB', 259.00, 8, '{\"size\":\"Large\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(15, 1044, 'XL - Red', 'VAR-1044-XR', 264.00, 16, '{\"size\":\"XL\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(16, 1044, 'XL - Blue', 'VAR-1044-XB', 264.00, 48, '{\"size\":\"XL\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(17, 1045, 'Small - Red', 'VAR-1045-SR', 49.00, 43, '{\"size\":\"Small\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(18, 1045, 'Small - Blue', 'VAR-1045-SB', 49.00, 12, '{\"size\":\"Small\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(19, 1045, 'Medium - Red', 'VAR-1045-MR', 54.00, 41, '{\"size\":\"Medium\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(20, 1045, 'Medium - Blue', 'VAR-1045-MB', 54.00, 38, '{\"size\":\"Medium\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(21, 1045, 'Large - Red', 'VAR-1045-LR', 59.00, 35, '{\"size\":\"Large\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(22, 1045, 'Large - Blue', 'VAR-1045-LB', 59.00, 34, '{\"size\":\"Large\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(23, 1045, 'XL - Red', 'VAR-1045-XR', 64.00, 48, '{\"size\":\"XL\",\"color\":\"Red\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25'),
(24, 1045, 'XL - Blue', 'VAR-1045-XB', 64.00, 46, '{\"size\":\"XL\",\"color\":\"Blue\"}', NULL, 1, '2026-03-09 18:10:25', '2026-03-09 18:10:25');

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

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`id`, `order_id`, `user_id`, `amount`, `type`, `reason`, `status`, `approved_by`, `admin_notes`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 25941.60, 'full', 'المنتج معيب', 'pending', NULL, NULL, NULL, '2026-03-08 18:10:25', '2026-03-09 18:10:25'),
(2, 2, 5, 4206.60, 'partial', 'تم إرجاع بعض المنتجات', 'approved', 1, 'تمت الموافقة على الاسترجاع', '2026-03-08 18:10:25', '2026-03-07 18:10:25', '2026-03-08 18:10:25'),
(3, 4, 5, 79.84, 'partial', 'تم إرجاع بعض المنتجات', 'processed', 1, 'تمت الموافقة على الاسترجاع', '2026-03-07 18:10:25', '2026-03-06 18:10:25', '2026-03-07 18:10:25');

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
(17, NULL, 'shoes', 8, 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"source\":\"api.product.search\"}', '2026-03-09 12:23:24', '2026-03-09 12:23:24');

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
(61, 'login_attempt', 'App\\Models\\User', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'success', 'User logged in successfully', '{\"identifier\":\"yousefalhalabi63@gmail.com\"}', 'low', '2026-03-10 05:13:54', '2026-03-10 05:13:54');

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
(3, 'homepage_slider_slides', '[{\"image\":\"\\/images\\/footer.png\",\"title\":\"\\u0623\\u0631\\u0633\\u0644 \\u0627\\u0628\\u062a\\u0633\\u0627\\u0645\\u062a\\u0643 \\u0623\\u064a\\u0646\\u0645\\u0627 \\u0643\\u0646\\u062a\",\"subtitle\":\"\\u062a\\u0633\\u0648\\u0642 \\u0645\\u0639\\u0646\\u0627 \\u0623\\u0641\\u0636\\u0644 \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0648\\u0627\\u0644\\u0639\\u0631\\u0648\\u0636\"},{\"image\":\"\\/images\\/logo-girl.jpg\",\"title\":\"\\u0647\\u062f\\u0627\\u064a\\u0627 \\u062a\\u0648\\u0644\\u064a\\u0628\",\"subtitle\":\"\\u0644\\u062d\\u0638\\u0627\\u062a \\u0627\\u0633\\u062a\\u062b\\u0646\\u0627\\u0626\\u064a\\u0629 \\u062a\\u0633\\u062a\\u062d\\u0642 \\u0647\\u062f\\u0627\\u064a\\u0627 \\u0645\\u0645\\u064a\\u0632\\u0629\"},{\"image\":\"\\/images\\/white_orange_logo.png\",\"title\":\"\\u0648\\u0635\\u0644 \\u062d\\u062f\\u064a\\u062b\\u0627\\u064b\",\"subtitle\":\"\\u0627\\u0643\\u062a\\u0634\\u0641 \\u0623\\u062d\\u062f\\u062b \\u0627\\u0644\\u0645\\u0646\\u062a\\u062c\\u0627\\u062a \\u0641\\u064a \\u0645\\u062a\\u062c\\u0631\\u0646\\u0627\"}]', 'json', 'Home page slider slides', '2026-02-01 12:33:18', '2026-02-01 12:33:18');

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
  `owner_id` bigint(20) UNSIGNED NOT NULL,
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

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `organization_id`, `owner_id`, `name`, `slug`, `description`, `logo`, `business_info`, `contact_info`, `settings`, `status`, `commission_rate`, `created_at`, `updated_at`, `banner`, `phone`, `email`, `address`, `total_sales`, `total_commission`, `balance`, `is_featured`, `deleted_at`, `total_earnings`, `available_balance`, `pending_payout`, `total_orders`, `last_order_at`) VALUES
(1, 1, 3, 'Demo Store', 'demo-store-di7vk0', 'Demo store for local testing', NULL, NULL, NULL, NULL, 'active', 0.1000, '2026-02-08 04:02:37', '2026-03-09 18:10:26', NULL, '+10000000001', 'trader@demo.com', 'Demo Address', 161.16, 0.00, 0.00, 1, NULL, 0.00, 0.00, 0.00, 0, NULL);

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
(66, 'info', NULL, 'User logged in successfully', NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2026-03-10 05:13:54', '2026-03-10 05:13:54', 'login_success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"method\":\"email\"}', 'yousefalhalabi63@gmail.com');

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
(1, 'feature.cs_complete_delivered_to_completed', 'true', 'boolean', NULL, 0, '2026-03-09 19:19:26', '2026-03-09 19:19:26');

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

--
-- Dumping data for table `traders`
--

INSERT INTO `traders` (`id`, `user_id`, `name`, `company_name`, `contact_email`, `contact_phone`, `status`, `commission_rate`, `payout_settings`, `created_at`, `updated_at`, `account_name_en`, `account_name_ar`, `email`, `phone`, `responsible_name`, `work_address`, `activity`, `owner_id_image_path`, `logo_image_path`, `bank_name`, `bank_account_holder`, `bank_account_number`, `iban`, `password`) VALUES
(1, 1, 'Demo Trader', 'Tulip Demo Trading', 'demo.trader@tulipstore.com', '+963900000001', 'approved', 10.00, '{\"bank\":{\"account_name\":\"Demo Trader\",\"account_number\":\"0000000000\",\"iban\":\"\",\"swift\":\"\"},\"documents\":[]}', '2026-02-05 03:18:59', '2026-02-05 03:18:59', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', ''),
(2, 3, 'Demo Trader', 'Demo Store', 'trader@demo.com', '+10000000001', 'approved', 0.10, '{\"bank\":{\"bank_name\":\"Demo Bank\",\"account_holder\":\"Demo Trader\",\"account_number\":\"0000000000\",\"iban\":null},\"business\":{\"registration_number\":\"DEMO-REG\",\"tax_id\":\"DEMO-TAX\",\"contact_person\":\"Demo Trader\",\"business_address\":\"Demo Address\"}}', '2026-02-05 07:47:24', '2026-02-08 04:02:36', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', ''),
(3, NULL, '', NULL, NULL, NULL, 'pending', 10.00, NULL, NULL, NULL, 'Yousef', 'يوسف', 'yousefalhalabi63@gmail.com', '+963 994251800', 'يوسف', 'السويداء', 'مطاعم ومقاهي', 'F:\\جوازات\\يوسف\\Untitled-6.jpg', 'C:\\Users\\2024\\Downloads\\pexels-fabiano-rodrigues-794857-1662298.jpg', '', '', '', '', '267724Yousef!');

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
  `google_id` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_full_name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'english',
  `gender` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `google_id`, `birth_date`, `phone`, `email_verified_at`, `password`, `user_full_name`, `mobile`, `address`, `language`, `gender`, `currency`, `verified`, `is_trader`, `is_admin`, `is_it_super`, `is_it`, `is_cs_agent`, `is_accountant`, `is_cs_supervisor`, `notes`, `tags`, `newsletter_subscribed`, `lifetime_value`, `remember_token`, `locked_at`, `locked_until`, `lock_reason`, `login_failures`, `created_at`, `updated_at`, `is_driver_supervisor`, `role_id`, `is_hr`, `is_cs`, `is_finance`, `is_hr_manager`, `status`, `is_verified`, `verification_code`, `otp_expiry`) VALUES
(1, 'Demo Trader', 'demo_trader', 'demo.trader@tulipstore.com', NULL, NULL, '+963900000001', NULL, '$2y$12$xTHPYZ3XX95pySd1BCmX1OZomd.jhl2O8xy/O6BNmruR2uSWLZbFa', NULL, NULL, NULL, 'english', NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-02-05 03:18:59', '2026-02-05 03:18:59', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL),
(3, 'Demo Trader', 'demo_trader_5033', 'trader@demo.com', NULL, NULL, '+10000000001', '2026-03-09 18:10:26', '$2y$12$AOqit.hz8kLhGZ7Zl5UUh.n2aqh1POJxsun/IWGrZT0G5aLQN0Izi', 'Demo Trader', '+10000000001', NULL, 'english', NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-02-05 07:47:24', '2026-03-09 18:10:26', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL),
(5, 'يوسف الحلبي', '', 'yousefalhalabi63@gmail.com', NULL, '2002-01-13', '+963994251800', NULL, '$2y$12$jUST3I3vaUaprGdXMuol7.69QafI9MwI3vOU1JCG0dIMqS1dTwMU6', NULL, NULL, NULL, 'english', 'ذكر', 'USD', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, NULL, '2026-03-10 02:19:45', 0, NULL, 0, 0, 0, 0, 'active', 1, NULL, NULL),
(6, NULL, 'john_doe', 'john@example.com', NULL, NULL, NULL, NULL, '$2y$12$o6Np89N9wtPqqQnqr6JNAOwn4PSPeRN8CMN.QjPz8gC413E4r1jCW', 'John Doe', '+1234567890', '123 Main Street', 'english', 'male', 'USD', 1, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-03-09 18:10:27', '2026-03-09 18:10:27', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL),
(7, NULL, 'jane_doe', 'jane@example.com', NULL, NULL, NULL, NULL, '$2y$12$yg26/m804GDnjMEQf7YHReBLPuaJwQUHSIzWyfAubDpu.wYY7yqiW', 'Jane Doe', '+1987654321', '456 Market Street', 'english', 'female', 'USD', 1, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-03-09 18:10:27', '2026-03-09 18:10:27', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL),
(8, NULL, 'test_user', 'test@example.com', NULL, NULL, NULL, NULL, '$2y$12$PvVUz3d850eKN5pccP7o0eW1JqlhWT5nJAGxWvxFHEe.qlMSIiITa', 'Test User', '+10000000000', '789 Test Ave', 'english', 'other', 'USD', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-03-09 18:10:28', '2026-03-09 18:10:28', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL),
(9, 'yousef F alhalabi', 'yousefalhalabi53', 'yousefalhalabi53@gmail.com', NULL, NULL, '0994251800', NULL, '$2y$12$KP.dFnusRssuLyNXQQedIOt9uT6RojMqcZBXeKtrCzRF0XrxGOKly', NULL, NULL, NULL, 'english', NULL, NULL, 1, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0.00, NULL, NULL, NULL, NULL, 0, '2026-03-10 02:21:37', '2026-03-10 02:21:37', 0, NULL, 0, 0, 0, 0, 'active', 0, NULL, NULL);

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

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `session_id`, `user_id`, `activity_type`, `product_id`, `category_id`, `search_query`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'fr3A0gPK0AIBEmacTokBM6zdLgIKhPZJBXzp6K4P', NULL, 'view', 1001, NULL, NULL, NULL, '2026-02-22 03:59:46', '2026-02-22 03:59:46'),
(2, 'SsNolUpXVRHtRb4JEyIXjo6fyxwqvGFreJMIUiZu', NULL, 'view', 1001, NULL, NULL, NULL, '2026-02-22 04:07:52', '2026-02-22 04:07:52'),
(3, 'D82G6IGgE1jwSs9Fq5bHjVZpbTZ9ulOEdgdB0lZk', NULL, 'cart_add', 1001, NULL, NULL, NULL, '2026-02-22 06:31:56', '2026-02-22 06:31:56'),
(4, 'xorZovEt04IdiqzY8vzNE54m5QlMkHnZMVQAGPeK', NULL, 'cart_add', 1001, NULL, NULL, NULL, '2026-02-22 06:32:11', '2026-02-22 06:32:11'),
(5, 'SNUuWDl5279JPY8dW8od3IkUPDH77N41LeSMi0Yd', NULL, 'cart_add', 1001, NULL, NULL, NULL, '2026-02-23 05:42:22', '2026-02-23 05:42:22'),
(6, 'h8dxaqjtGiofsPfJtowZJnQ7No5MWbmOax00IBkV', NULL, 'view', 1086, NULL, NULL, NULL, '2026-02-26 02:48:03', '2026-02-26 02:48:03'),
(7, 'HJPNDdgr4LlS1M4KwXcegcmm5ns7b5m4flcDkrTI', NULL, 'view', 1087, NULL, NULL, NULL, '2026-02-26 02:48:06', '2026-02-26 02:48:06'),
(8, 'vCMurOb1QFGXfmQ2W3ciHi7ae4m5uuZ4FfJ8t0va', NULL, 'view', 1086, NULL, NULL, NULL, '2026-02-26 04:39:50', '2026-02-26 04:39:50'),
(9, '2L3xRVZpnW4qzaPqnZIUcveJjH3aVqWrk4TeMbt4', NULL, 'view', 1085, NULL, NULL, NULL, '2026-03-02 06:53:03', '2026-03-02 06:53:03'),
(10, 'BktwXcn77V6JKq5sfofz46z1ThtqIToA9PLVv7Dw', NULL, 'view', 1086, NULL, NULL, NULL, '2026-03-02 06:53:07', '2026-03-02 06:53:07'),
(11, 'fBKQ7elRHkGF0wib4P3uHEyP74i1dQcU0wZEO6nj', NULL, 'view', 1085, NULL, NULL, NULL, '2026-03-02 07:27:37', '2026-03-02 07:27:37'),
(12, '2O3b4KwPvSwIbFoAULhaMREJO9TXaAk0Lf5wCarx', NULL, 'view', 1087, NULL, NULL, NULL, '2026-03-08 03:48:01', '2026-03-08 03:48:01'),
(13, 'rmqTCGAqDvl5ex2YTS2JMB4ikOrnEpdxWkj8zQXy', NULL, 'view', 1086, NULL, NULL, NULL, '2026-03-08 03:49:22', '2026-03-08 03:49:22'),
(14, 'QmBqGVgXHWR6OmApgvM6vPs04kTvLQkajgE5Khux', NULL, 'view', 1086, NULL, NULL, NULL, '2026-03-08 04:29:02', '2026-03-08 04:29:02'),
(15, 'Pmf7NAiRfNEu29aeBAxtzZml0w65bn3hVEXbKjuX', NULL, 'view', 1086, NULL, NULL, NULL, '2026-03-08 04:33:53', '2026-03-08 04:33:53'),
(16, 'Zpk9ZYGj73sKV0KIwtn5gjBdERwrW8mbJiOEXWeq', NULL, 'view', 1086, NULL, NULL, NULL, '2026-03-08 05:23:52', '2026-03-08 05:23:52'),
(17, 'mZa9FALCHSa7KEaiK4UsyImifCjrZDdRzYnyOnKR', NULL, 'view', 1087, NULL, NULL, NULL, '2026-03-08 06:04:50', '2026-03-08 06:04:50'),
(18, 'fU8TrkzrmFdG2hNzJASxATdRlll2OO1dU5spFyiL', NULL, 'view', 1137, NULL, NULL, NULL, '2026-03-09 05:30:25', '2026-03-09 05:30:25'),
(19, 'ikHB7O2WLo0VcCfawWxP3107xVj0zOEfZJclYmDm', NULL, 'view', 1138, NULL, NULL, NULL, '2026-03-09 12:19:43', '2026-03-09 12:19:43'),
(20, 'Q3FEjWz4qizngE4lWKuAJVSIvIZThanLB8wbcibE', NULL, 'cart_add', 1138, NULL, NULL, NULL, '2026-03-09 13:59:02', '2026-03-09 13:59:02'),
(21, 'Qms2N3pW7QuzKlcJFsNvsykE031WP73Y24IhuoP8', NULL, 'cart_add', 1138, NULL, NULL, NULL, '2026-03-10 02:17:33', '2026-03-10 02:17:33');

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

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `session_id`, `user_id`, `favorite_categories`, `search_keywords`, `viewed_products`, `purchased_products`, `activity_score`, `last_activity`, `created_at`, `updated_at`) VALUES
(1, 'fr3A0gPK0AIBEmacTokBM6zdLgIKhPZJBXzp6K4P', NULL, '[]', '[]', '[1001]', '[]', 1, '2026-02-22 03:59:46', NULL, '2026-02-22 03:59:46'),
(2, 'SsNolUpXVRHtRb4JEyIXjo6fyxwqvGFreJMIUiZu', NULL, '[]', '[]', '[1001]', '[]', 1, '2026-02-22 04:07:52', NULL, '2026-02-22 04:07:52'),
(3, 'D82G6IGgE1jwSs9Fq5bHjVZpbTZ9ulOEdgdB0lZk', NULL, '[]', '[]', '[]', '[]', 5, '2026-02-22 06:31:56', NULL, '2026-02-22 06:31:56'),
(4, 'xorZovEt04IdiqzY8vzNE54m5QlMkHnZMVQAGPeK', NULL, '[]', '[]', '[]', '[]', 5, '2026-02-22 06:32:11', NULL, '2026-02-22 06:32:11'),
(5, 'SNUuWDl5279JPY8dW8od3IkUPDH77N41LeSMi0Yd', NULL, '[]', '[]', '[]', '[]', 5, '2026-02-23 05:42:22', NULL, '2026-02-23 05:42:22'),
(6, 'h8dxaqjtGiofsPfJtowZJnQ7No5MWbmOax00IBkV', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-02-26 02:48:03', NULL, '2026-02-26 02:48:03'),
(7, 'HJPNDdgr4LlS1M4KwXcegcmm5ns7b5m4flcDkrTI', NULL, '[]', '[]', '[1087]', '[]', 1, '2026-02-26 02:48:06', NULL, '2026-02-26 02:48:06'),
(8, 'vCMurOb1QFGXfmQ2W3ciHi7ae4m5uuZ4FfJ8t0va', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-02-26 04:39:50', NULL, '2026-02-26 04:39:50'),
(9, '2L3xRVZpnW4qzaPqnZIUcveJjH3aVqWrk4TeMbt4', NULL, '[]', '[]', '[1085]', '[]', 1, '2026-03-02 06:53:03', NULL, '2026-03-02 06:53:03'),
(10, 'BktwXcn77V6JKq5sfofz46z1ThtqIToA9PLVv7Dw', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-03-02 06:53:07', NULL, '2026-03-02 06:53:07'),
(11, 'fBKQ7elRHkGF0wib4P3uHEyP74i1dQcU0wZEO6nj', NULL, '[]', '[]', '[1085]', '[]', 1, '2026-03-02 07:27:37', NULL, '2026-03-02 07:27:37'),
(12, '2O3b4KwPvSwIbFoAULhaMREJO9TXaAk0Lf5wCarx', NULL, '[]', '[]', '[1087]', '[]', 1, '2026-03-08 03:48:01', NULL, '2026-03-08 03:48:01'),
(13, 'rmqTCGAqDvl5ex2YTS2JMB4ikOrnEpdxWkj8zQXy', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-03-08 03:49:22', NULL, '2026-03-08 03:49:22'),
(14, 'QmBqGVgXHWR6OmApgvM6vPs04kTvLQkajgE5Khux', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-03-08 04:29:02', NULL, '2026-03-08 04:29:02'),
(15, 'Pmf7NAiRfNEu29aeBAxtzZml0w65bn3hVEXbKjuX', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-03-08 04:33:53', NULL, '2026-03-08 04:33:53'),
(16, 'Zpk9ZYGj73sKV0KIwtn5gjBdERwrW8mbJiOEXWeq', NULL, '[]', '[]', '[1086]', '[]', 1, '2026-03-08 05:23:52', NULL, '2026-03-08 05:23:52'),
(17, 'mZa9FALCHSa7KEaiK4UsyImifCjrZDdRzYnyOnKR', NULL, '[]', '[]', '[1087]', '[]', 1, '2026-03-08 06:04:50', NULL, '2026-03-08 06:04:50'),
(18, 'fU8TrkzrmFdG2hNzJASxATdRlll2OO1dU5spFyiL', NULL, '[]', '[]', '[1137]', '[]', 1, '2026-03-09 05:30:25', NULL, '2026-03-09 05:30:25'),
(19, 'ikHB7O2WLo0VcCfawWxP3107xVj0zOEfZJclYmDm', NULL, '[]', '[]', '[1138]', '[]', 1, '2026-03-09 12:19:43', NULL, '2026-03-09 12:19:43'),
(20, 'Q3FEjWz4qizngE4lWKuAJVSIvIZThanLB8wbcibE', NULL, '[]', '[]', '[]', '[]', 5, '2026-03-09 13:59:02', NULL, '2026-03-09 13:59:02'),
(21, 'Qms2N3pW7QuzKlcJFsNvsykE031WP73Y24IhuoP8', NULL, '[]', '[]', '[]', '[]', 5, '2026-03-10 02:17:33', NULL, '2026-03-10 02:17:33');

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
  ADD KEY `employees_dept_status_hire` (`department`,`status`,`hire_date`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_attendance_employee_id_date_unique` (`employee_id`,`date`),
  ADD KEY `employee_attendance_approved_by_foreign` (`approved_by`);

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
  ADD KEY `stores_organization_id_status_index` (`organization_id`,`status`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `administrative_approvals`
--
ALTER TABLE `administrative_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cash_flow_records`
--
ALTER TABLE `cash_flow_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1044;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dashboard_quick_actions`
--
ALTER TABLE `dashboard_quick_actions`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_dashboard_permissions`
--
ALTER TABLE `employee_dashboard_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_shrinkage`
--
ALTER TABLE `inventory_shrinkage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_blacklists`
--
ALTER TABLE `ip_blacklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_revenue_records`
--
ALTER TABLE `order_revenue_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1148;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `security_audit_logs`
--
ALTER TABLE `security_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_calculations`
--
ALTER TABLE `tax_calculations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traders`
--
ALTER TABLE `traders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `coupon_usage_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD CONSTRAINT `employee_attendance_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employee_attendance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `stores_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
