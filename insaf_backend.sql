-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2026 at 09:45 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vristo`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint UNSIGNED NOT NULL,
  `application_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `university_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `course_intake_id` bigint UNSIGNED NOT NULL,
  `tuition_fee` decimal(12,2) DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `application_id`, `student_id`, `university_id`, `course_id`, `course_intake_id`, `tuition_fee`, `currency`, `total_fee`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(10, 'APP-2026-00001', 6, 2, 2, 2, 1200.00, 'AUD', 100000.00, 'pending', NULL, 3, '2026-02-22 11:22:42', '2026-02-22 11:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `period` enum('monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `category`, `amount`, `period`, `start_date`, `end_date`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Marketing', 5000.00, 'monthly', '2026-02-01', '2026-02-28', 4, NULL, '2026-02-18 01:25:05', '2026-02-18 01:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admin-dashboard-cache-tyro:user-1:roles', 'a:1:{i:0;s:5:\"admin\";}', 1771836838),
('admin-dashboard-cache-tyro:user-4:privileges', 'a:1:{i:0;s:11:\"*accountant\";}', 1771840008),
('admin-dashboard-cache-tyro:user-4:roles', 'a:1:{i:0;s:10:\"accountant\";}', 1771840008),
('admin-dashboard-cache-tyro:user-5:privileges', 'a:1:{i:0;s:7:\"*editor\";}', 1771831245),
('admin-dashboard-cache-tyro:user-5:roles', 'a:1:{i:0;s:6:\"editor\";}', 1771831245),
('admin-dashboard-cache-tyro:user-6:privileges', 'a:1:{i:0;s:12:\"*application\";}', 1771831053),
('admin-dashboard-cache-tyro:user-6:roles', 'a:1:{i:0;s:11:\"application\";}', 1771831053);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commissions`
--

INSERT INTO `commissions` (`id`, `payment_id`, `user_id`, `role`, `amount`, `percentage`, `status`, `created_at`, `updated_at`) VALUES
(7, 19, 2, 'marketing', 1500.00, 3.00, 'pending', '2026-02-22 11:28:46', '2026-02-22 11:30:28'),
(8, 20, 2, 'marketing', 1500.00, 3.00, 'pending', '2026-02-22 23:14:32', '2026-02-22 23:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 'UK', NULL, NULL, 1, '2026-02-17 07:00:59', '2026-02-17 07:19:24'),
(2, 'Australia', NULL, NULL, 1, '2026-02-18 02:51:17', '2026-02-18 02:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint UNSIGNED NOT NULL,
  `university_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tuition_fee` decimal(12,2) DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `university_id`, `name`, `degree_level`, `duration`, `tuition_fee`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Data Analyst', 'Masters', '1 year', 440.00, 'GBP', 1, '2026-02-17 07:03:21', '2026-02-22 04:01:21'),
(2, 2, 'Cyber Security', 'Masters', '6 Month', 1200.00, 'AUD', 1, '2026-02-18 04:18:36', '2026-02-22 04:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `course_intakes`
--

CREATE TABLE `course_intakes` (
  `id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `intake_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_start_date` date DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `class_start_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_intakes`
--

INSERT INTO `course_intakes` (`id`, `course_id`, `intake_name`, `application_start_date`, `application_deadline`, `class_start_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Summer 2026', '2026-03-01', '2026-03-31', NULL, 1, '2026-02-17 07:03:48', '2026-02-22 03:59:35'),
(2, 2, 'Spring 26', '2026-01-03', NULL, NULL, 1, '2026-02-18 22:59:18', '2026-02-18 22:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate` decimal(15,6) NOT NULL DEFAULT '1.000000',
  `last_updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `symbol`, `exchange_rate`, `last_updated_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BDT', '৳', 1.000000, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31'),
(2, 'GBP', '£', 0.006058, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31'),
(3, 'USD', '$', 0.008179, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31'),
(4, 'EUR', '€', 0.006930, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31'),
(5, 'AUD', 'A$', 0.011549, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31'),
(6, 'CAD', 'C$', 0.011184, '2026-02-23 00:03:31', 1, '2026-02-18 04:08:52', '2026-02-23 00:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_account_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `salary_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `description`, `amount`, `expense_date`, `category`, `payment_method`, `office_account_id`, `created_by`, `notes`, `created_at`, `updated_at`, `salary_id`) VALUES
(6, 'Office Rent', 15000.00, '2026-02-22', 'Rent', 'bank_transfer', 2, 4, NULL, '2026-02-22 11:26:59', '2026-02-22 11:26:59', NULL),
(7, 'Marketing', 5000.00, '2026-02-22', 'Marketing', 'mobile_banking', 1, 4, NULL, '2026-02-22 11:28:20', '2026-02-22 11:28:20', NULL),
(8, 'Inoodex - Salary Payment', 15000.00, '2026-02-23', 'Salaries', 'bank_transfer', 2, 4, NULL, '2026-02-23 00:41:15', '2026-02-23 00:41:15', NULL),
(9, 'Salary Payment - Marketing (2026-01)', 18000.00, '2026-02-23', 'Salaries', 'bank_transfer', 1, 4, NULL, '2026-02-23 00:48:20', '2026-02-23 00:48:20', NULL),
(10, 'Salary Payment - Consultant (2026-01)', 12000.00, '2026-02-23', 'Salaries', 'bank_transfer', 1, 4, NULL, '2026-02-23 00:48:20', '2026-02-23 00:48:20', NULL),
(11, 'Salary Payment - Accountant (2026-01)', 20000.00, '2026-02-23', 'Salaries', 'bank_transfer', 1, 4, NULL, '2026-02-23 00:48:20', '2026-02-23 00:48:20', NULL),
(12, 'Salary Payment - Editor (2026-01)', 12000.00, '2026-02-23', 'Salaries', 'bank_transfer', 1, 4, NULL, '2026-02-23 00:48:20', '2026-02-23 00:48:20', NULL),
(13, 'Salary Payment - Application (2026-01)', 15000.00, '2026-02-23', 'Salaries', 'bank_transfer', 1, 4, NULL, '2026-02-23 00:48:20', '2026-02-23 00:48:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance_categories`
--

CREATE TABLE `finance_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('expense','income','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_categories`
--

INSERT INTO `finance_categories` (`id`, `name`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Rent', 'expense', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54'),
(2, 'Marketing', 'expense', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54'),
(3, 'Salaries', 'expense', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54'),
(4, 'Utilities', 'expense', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54'),
(5, 'Office Supplies', 'expense', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54'),
(6, 'Student Fees', 'income', 1, '2026-02-18 02:30:54', '2026-02-18 02:30:54');

-- --------------------------------------------------------

--
-- Table structure for table `invitation_links`
--

CREATE TABLE `invitation_links` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation_referrals`
--

CREATE TABLE `invitation_referrals` (
  `id` bigint UNSIGNED NOT NULL,
  `invitation_link_id` bigint UNSIGNED NOT NULL,
  `referred_user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint UNSIGNED NOT NULL,
  `student_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_education` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_course` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `next_follow_up_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `consultant_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `student_name`, `email`, `phone`, `current_education`, `preferred_country`, `preferred_course`, `source`, `status`, `notes`, `last_contacted_at`, `next_follow_up_at`, `created_by`, `consultant_id`, `created_at`, `updated_at`) VALUES
(5, 'Rahim', 'rahim@example.com', '01234567890', 'HSC', '2', '2', 'Phone', 'pending', NULL, NULL, '2026-02-24 18:00:00', 2, NULL, '2026-02-21 22:53:14', '2026-02-21 22:53:14'),
(6, 'Hasan', 'hasan@example.com', '0120320020', 'JSC', '1', '1', 'Phone', 'pending', NULL, NULL, '2026-02-24 18:00:00', 2, NULL, '2026-02-22 03:55:24', '2026-02-22 03:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2022_05_17_181447_create_roles_table', 1),
(5, '2022_05_17_181456_create_user_roles_table', 1),
(6, '2025_01_01_000001_create_privileges_table', 1),
(7, '2025_01_01_000002_create_privilege_role_table', 1),
(8, '2025_01_01_000003_add_suspension_columns_to_users_table', 1),
(9, '2026_02_02_085518_create_personal_access_tokens_table', 1),
(10, '2026_02_03_073742_create_settings_table', 1),
(11, '2026_02_03_085903_add_is_active_to_roles_table', 1),
(12, '2026_02_03_100000_create_countries_table', 1),
(13, '2026_02_03_100001_create_universities_table', 1),
(14, '2026_02_03_100002_create_courses_table', 1),
(15, '2026_02_03_100003_create_course_intakes_table', 1),
(16, '2026_02_03_111812_create_leads_table', 1),
(17, '2026_02_03_123612_create_students_table', 1),
(18, '2026_02_03_133591_create_payments_table', 1),
(19, '2026_02_15_202417_create_applications_table', 1),
(20, '2026_02_17_131456_add_application_id_to_payments_table', 2),
(21, '2026_02_17_184206_create_notifications_table', 3),
(22, '2026_02_18_063017_create_expenses_table', 4),
(23, '2026_02_18_064510_create_office_accounts_table', 5),
(24, '2026_02_18_065315_create_office_transactions_table', 6),
(25, '2026_02_18_071448_create_budgets_table', 7),
(26, '2026_02_18_083029_create_finance_categories_table', 8),
(27, '2026_02_18_084023_add_total_fee_to_applications_table', 9),
(28, '2026_02_18_094859_add_currency_to_courses_table', 10),
(29, '2026_02_18_095243_create_currencies_table', 11),
(30, '2026_02_18_113730_add_tuition_fee_to_applications_table', 12),
(31, '2026_02_18_114225_add_currency_to_applications_table', 13),
(32, '2026_02_18_115623_add_exchange_rate_to_currencies_table', 14),
(33, '2026_02_19_100000_create_salaries_table', 15),
(34, '2024_01_01_000000_create_social_accounts_table', 16),
(35, '2024_01_01_000001_add_two_factor_columns_to_users_table', 16),
(36, '2024_01_01_000002_create_invitation_system_tables', 16),
(37, '2025_02_08_000000_add_profile_photo_to_users_table', 16),
(38, '2026_02_15_000000_create_tyro_audit_logs_table', 16),
(39, '2026_02_21_085827_create_commissions_table', 16),
(40, '2026_02_22_074425_add_is_template_to_salaries_table', 17),
(41, '2026_02_22_081212_add_office_account_id_to_payments_table', 17),
(42, '2026_02_22_081922_add_office_account_id_to_expenses_table', 18),
(43, '2026_02_22_081930_add_income_expense_to_office_transactions_type', 18),
(44, '2026_02_22_082610_add_basic_salary_to_users_table', 19),
(45, '2026_02_22_090213_add_account_details_to_users_table', 20),
(46, '2026_02_22_094533_add_commission_percentage_to_users_table', 21),
(47, '2026_02_22_102330_add_balance_to_office_accounts_table', 22),
(48, '2026_02_23_045430_add_notes_to_payments_table', 23),
(49, '2026_02_23_100000_add_salary_id_to_expenses', 24),
(50, '2026_02_23_110000_add_opening_balance_to_office_accounts', 25);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('1b8dac70-217e-4509-a24a-e341a5650b5b', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":4,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Rahim\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/4\\/edit\"}', '2026-02-21 23:09:23', '2026-02-21 23:04:30', '2026-02-21 23:09:23'),
('272cfc76-a9c4-42ed-a739-6e6fc772464d', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":8,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/8\\/edit\"}', NULL, '2026-02-22 10:56:38', '2026-02-22 10:56:38'),
('3b9c3a71-77f6-42b1-a0c1-3fa6e9da4486', 'App\\Notifications\\NewLeadSubmitted', 'App\\Models\\User', 3, '{\"lead_id\":6,\"student_name\":\"Hasan\",\"phone\":\"0120320020\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/6\"}', '2026-02-22 10:56:19', '2026-02-22 03:55:24', '2026-02-22 10:56:19'),
('4919412a-a1be-4937-b3e5-477292752eaa', 'App\\Notifications\\NewLeadSubmitted', 'App\\Models\\User', 3, '{\"lead_id\":4,\"student_name\":\"Ashraful Islam\",\"phone\":\"01195674368\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/4\"}', '2026-02-18 05:00:58', '2026-02-18 05:00:49', '2026-02-18 05:00:58'),
('4aeba9c4-3e3a-4d9d-bbcd-83bb23f1bb8f', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":10,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/10\\/edit\"}', NULL, '2026-02-22 11:22:42', '2026-02-22 11:22:42'),
('50ac2d91-74ed-455f-9787-95c679ac268a', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":5,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/5\\/edit\"}', '2026-02-22 02:55:59', '2026-02-22 00:03:14', '2026-02-22 02:55:59'),
('6fb35d96-f99f-4ed8-b59b-d43c56c2bb05', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":3,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Abra Klein\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/3\\/edit\"}', '2026-02-19 00:07:28', '2026-02-19 00:07:17', '2026-02-19 00:07:28'),
('a8e631fb-ec13-4392-868a-ff5d95ab97b4', 'App\\Notifications\\NewLeadSubmitted', 'App\\Models\\User', 3, '{\"lead_id\":2,\"student_name\":\"fsgh\",\"phone\":\"0187452963\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/2\"}', '2026-02-17 13:09:41', '2026-02-17 13:09:17', '2026-02-17 13:09:41'),
('ac808b50-3412-4c91-b0bd-50ad2380f2e0', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":7,\"application_number\":\"APP-2026-00003\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00003 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/7\\/edit\"}', NULL, '2026-02-22 04:00:03', '2026-02-22 04:00:03'),
('cf7c7ea3-4775-4be3-bbaa-7df4b4e3162e', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":9,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/9\\/edit\"}', NULL, '2026-02-22 10:56:57', '2026-02-22 10:56:57'),
('e7e77362-16c9-47f6-b9f7-1e5972367bdb', 'App\\Notifications\\NewApplicationNotification', 'App\\Models\\User', 6, '{\"application_id\":6,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/6\\/edit\"}', NULL, '2026-02-22 03:58:53', '2026-02-22 03:58:53'),
('ebe6636b-5a5f-4e95-b12b-02211b342c2e', 'App\\Notifications\\NewLeadSubmitted', 'App\\Models\\User', 3, '{\"lead_id\":5,\"student_name\":\"Rahim\",\"phone\":\"01234567890\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/5\"}', '2026-02-21 22:54:19', '2026-02-21 22:53:16', '2026-02-21 22:54:19'),
('f331d0ec-990f-4465-b30e-12f38e376fd1', 'App\\Notifications\\NewLeadSubmitted', 'App\\Models\\User', 3, '{\"lead_id\":3,\"student_name\":\"Hasan\",\"phone\":\"0101010101010\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/3\"}', '2026-02-18 04:24:14', '2026-02-18 04:22:05', '2026-02-18 04:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `office_accounts`
--

CREATE TABLE `office_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('bank','mfs','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remaining_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `office_accounts`
--

INSERT INTO `office_accounts` (`id`, `account_name`, `account_type`, `provider_name`, `account_number`, `opening_balance`, `branch_name`, `status`, `remaining_balance`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Inoodex', 'mfs', 'bKash', '01234567890', 0.00, NULL, 'active', -14000.00, 4, NULL, '2026-02-18 00:48:45', '2026-02-23 03:44:33'),
(2, 'Inoodex', 'bank', 'Dutch Banla Bank', '0123456789', 0.00, 'Banani', 'active', 70000.00, 4, NULL, '2026-02-18 00:55:47', '2026-02-23 00:41:15'),
(3, 'Office Cash', 'cash', NULL, '1', 100000.00, NULL, 'active', 100000.00, 4, NULL, '2026-02-23 01:07:07', '2026-02-23 01:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `office_transactions`
--

CREATE TABLE `office_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `from_account_id` bigint UNSIGNED DEFAULT NULL,
  `to_account_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('transfer','deposit','withdrawal','income','expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `office_transactions`
--

INSERT INTO `office_transactions` (`id`, `from_account_id`, `to_account_id`, `amount`, `transaction_date`, `transaction_type`, `reference`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(9, NULL, 2, 50000.00, '2026-02-22', 'income', 'Payment: REC-20260222-0001', 'Payment for application #APP-2026-00001', 4, '2026-02-22 11:25:35', '2026-02-22 11:25:35'),
(10, 2, NULL, 15000.00, '2026-02-22', 'expense', 'Expense: Office Rent', NULL, 4, '2026-02-22 11:26:59', '2026-02-22 11:26:59'),
(11, 1, NULL, 5000.00, '2026-02-22', 'expense', 'Expense: Marketing', NULL, 4, '2026-02-22 11:28:20', '2026-02-22 11:28:20'),
(12, NULL, 1, 50000.00, '2026-02-22', 'income', 'Payment: REC-20260222-0002', 'Payment for application #APP-2026-00001', 4, '2026-02-22 11:28:46', '2026-02-22 11:28:46'),
(13, NULL, 2, 50000.00, '2026-02-23', 'income', 'Payment: REC-20260223-0001', 'Payment for application #APP-2026-00001', 4, '2026-02-22 23:14:32', '2026-02-22 23:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `application_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('advance','partial','final') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `collected_by` bigint UNSIGNED NOT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `office_account_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `application_id`, `amount`, `payment_type`, `payment_date`, `collected_by`, `receipt_number`, `payment_status`, `office_account_id`, `created_at`, `updated_at`, `notes`) VALUES
(19, 6, 10, 50000.00, 'partial', '2026-02-22 00:00:00', 4, 'REC-20260222-0002', 'pending', 1, '2026-02-22 11:28:46', '2026-02-22 23:13:49', 'test'),
(20, 6, 10, 50000.00, 'final', '2026-02-23 00:00:00', 4, 'REC-20260223-0001', 'completed', 2, '2026-02-22 23:14:32', '2026-02-22 23:14:32', 'payment done');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Marketing', '*marketing', NULL, '2026-02-17 12:47:37', '2026-02-17 12:47:37'),
(2, 'Consultant', '*consultant', NULL, '2026-02-17 12:50:08', '2026-02-17 23:20:02'),
(3, 'Accountant', '*accountant', NULL, '2026-02-17 23:02:46', '2026-02-17 23:02:46'),
(4, 'Editor', '*editor', NULL, '2026-02-18 02:45:49', '2026-02-18 02:45:49'),
(5, 'Application', '*application', NULL, '2026-02-18 22:39:48', '2026-02-18 22:39:48'),
(6, 'Generate Reports', 'report.generate', 'Allows generating system-wide reports.', '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(7, 'Manage Users', 'users.manage', 'Allows creating, editing, and deleting users.', '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(8, 'Manage Roles', 'roles.manage', 'Allows editing Tyro roles.', '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(9, 'View Billing', 'billing.view', 'Allows viewing billing statements.', '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(10, 'Wildcard', '*', 'Grants every privilege.', '2026-02-21 01:19:44', '2026-02-21 01:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `privilege_role`
--

CREATE TABLE `privilege_role` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `privilege_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_role`
--

INSERT INTO `privilege_role` (`id`, `role_id`, `privilege_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-02-17 12:47:37', '2026-02-17 12:47:37'),
(2, 3, 2, '2026-02-17 12:50:08', '2026-02-17 12:50:08'),
(3, 4, 3, '2026-02-17 23:02:46', '2026-02-17 23:02:46'),
(4, 5, 4, '2026-02-18 02:45:49', '2026-02-18 02:45:49'),
(5, 6, 5, '2026-02-18 22:39:48', '2026-02-18 22:39:48'),
(6, 1, 6, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(7, 10, 6, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(8, 1, 7, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(9, 10, 7, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(10, 10, 8, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(11, 1, 9, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(12, 7, 9, '2026-02-21 01:19:44', '2026-02-21 01:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 1, '2026-02-16 03:13:51', '2026-02-21 01:19:44'),
(2, 'Marketing', 'marketing', 1, '2026-02-16 03:16:47', '2026-02-16 03:16:47'),
(3, 'Consultant', 'consultant', 1, '2026-02-17 12:49:50', '2026-02-17 12:57:56'),
(4, 'Accountant', 'accountant', 1, '2026-02-17 23:02:22', '2026-02-17 23:02:22'),
(5, 'Editor', 'editor', 1, '2026-02-18 02:45:05', '2026-02-18 02:45:05'),
(6, 'Application', 'application', 1, '2026-02-18 22:39:28', '2026-02-18 22:52:44'),
(7, 'User', 'user', 1, '2026-02-21 01:19:44', '2026-02-21 01:19:44'),
(10, 'Super Admin', 'super-admin', 1, '2026-02-21 01:19:44', '2026-02-21 01:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `employee_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `overtime_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(12,2) NOT NULL,
  `tax_deduction` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insurance_deduction` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','partial','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','mobile_banking','cheque') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('t9Vb75so1nl6TSP8YSIOK4RgnJRc9rdvpdw8jXlo', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRGFKOEJmdGVIZll5emFUZlVFbEpYa1FSQWpzTzYwNnJydllhemtzNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZC9vZmZpY2UtdHJhbnNhY3Rpb25zIjtzOjU6InJvdXRlIjtzOjMxOiJhZG1pbi5vZmZpY2UtdHJhbnNhY3Rpb25zLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1771839923);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Inoodex', '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(2, 'contact_email', 'hello@inoodex.com', '2026-02-16 03:20:28', '2026-02-23 02:49:28'),
(3, 'contact_phone', '01234567890', '2026-02-16 03:20:28', '2026-02-23 02:49:28'),
(4, 'address', 'Mirpur, Dhaka', '2026-02-16 03:20:28', '2026-02-23 02:49:28'),
(5, 'social_facebook', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(6, 'social_twitter', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(7, 'social_linkedin', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(8, 'currency_symbol', NULL, '2026-02-16 03:20:28', '2026-02-22 00:06:27'),
(9, 'date_format', 'd/m/Y', '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(10, 'enable_registration', '0', '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(11, 'maintenance_mode', '0', '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(12, 'meta_title', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(13, 'meta_description', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(14, 'meta_keywords', NULL, '2026-02-16 03:20:28', '2026-02-16 03:20:28'),
(15, 'app_logo', 'uploads/settings/55dl2iPgxNFXTX31JtwAREFkupbl3eQcVSVFVYRy.png', '2026-02-17 22:37:17', '2026-02-22 11:16:54'),
(16, 'app_favicon', 'uploads/settings/POlV6I7SgJuvs1SoD6ktrvHlzxRv0p9Zo1JK4HuU.png', '2026-02-17 22:37:17', '2026-02-22 11:16:54'),
(17, 'commission_marketing_percent', '3', '2026-02-21 03:07:51', '2026-02-21 22:49:50'),
(18, 'commission_consultant_percent', '3', '2026-02-21 03:07:51', '2026-02-21 03:07:51'),
(19, 'commission_application_percent', '4', '2026-02-21 03:07:51', '2026-02-21 03:07:51'),
(20, 'commission_accountant_percent', '5', '2026-02-21 03:07:51', '2026-02-21 03:07:51');

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dob` date DEFAULT NULL,
  `ssc_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hsc_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ielts_score` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  `university_id` bigint UNSIGNED DEFAULT NULL,
  `course_id` bigint UNSIGNED DEFAULT NULL,
  `course_intake_id` bigint UNSIGNED DEFAULT NULL,
  `current_stage` enum('lead','counseling','payment','application','offer','visa','enrolled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_status` enum('pending','applied','rejected','withdrawn','visa_processing','enrolled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_marketing_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_consultant_id` bigint UNSIGNED DEFAULT NULL,
  `assigned_application_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `father_name`, `mothers_name`, `passport_number`, `email`, `phone`, `address`, `dob`, `ssc_result`, `hsc_result`, `ielts_score`, `subject`, `country_id`, `university_id`, `course_id`, `course_intake_id`, `current_stage`, `current_status`, `assigned_marketing_id`, `assigned_consultant_id`, `assigned_application_id`, `created_by`, `documents`, `created_at`, `updated_at`) VALUES
(6, 'Md', 'Hasan', 'Moniruzzaman', 'Monira Begum', '123456789', 'test1@example.com', '01200000000', 'Dhaka', '2005-01-01', '4.00', '3.75', '5.5', NULL, 2, 2, 2, 2, NULL, NULL, 2, NULL, NULL, 3, '[{\"name\": \"file-sample_150kB.pdf\", \"path\": \"documents/students/J7A1jQi8fSmhlRDyTcnPVpb4qiMKD0YGqkZHT54J.pdf\"}]', '2026-02-22 11:21:20', '2026-02-22 11:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `tyro_audit_logs`
--

CREATE TABLE `tyro_audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tyro_audit_logs`
--

INSERT INTO `tyro_audit_logs` (`id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `metadata`, `created_at`) VALUES
(1, 1, 'role.deleted', 'HasinHayder\\Tyro\\Models\\Role', 8, '{\"id\": 8, \"name\": \"Customer\", \"slug\": \"customer\", \"is_active\": 1}', NULL, '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-22 16:51:11'),
(2, 1, 'role.deleted', NULL, NULL, '{\"id\": 8, \"name\": \"Customer\", \"slug\": \"customer\"}', NULL, '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-22 16:51:11'),
(3, 1, 'role.deleted', 'HasinHayder\\Tyro\\Models\\Role', 9, '{\"id\": 9, \"name\": \"All\", \"slug\": \"*\", \"is_active\": 1}', NULL, '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-22 16:51:16'),
(4, 1, 'role.deleted', NULL, NULL, '{\"id\": 9, \"name\": \"All\", \"slug\": \"*\"}', NULL, '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-22 16:51:16'),
(5, 1, 'role.assigned', 'App\\Models\\User', 3, NULL, '{\"role_id\": 5, \"role_slug\": \"editor\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-23 07:13:12'),
(6, 1, 'role.removed', 'App\\Models\\User', 3, NULL, '{\"role_id\": 5, \"role_slug\": \"editor\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-23 07:15:13'),
(7, 1, 'user.suspended', 'App\\Models\\User', 5, '{\"suspended_at\": null, \"suspension_reason\": null}', '{\"suspended_at\": \"2026-02-23T07:15:26.738707Z\", \"suspension_reason\": \"test\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}', '2026-02-23 07:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `country_id`, `name`, `short_name`, `website`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Aston University', 'abc', 'https://www.aston.ac.uk/', 'test@test.com', '01200000000', 'Uk', 1, '2026-02-17 07:02:22', '2026-02-22 03:54:58'),
(2, 2, 'Australian Catholic University', 'Catholic', 'https://www.acu.edu.au/', 'test1@example.com', '01098765432', 'Sydney', 1, '2026-02-18 03:44:25', '2026-02-18 03:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL DEFAULT '0.00',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_gravatar` tinyint(1) NOT NULL DEFAULT '0',
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_percentage` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `basic_salary`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `suspended_at`, `suspension_reason`, `profile_photo_path`, `use_gravatar`, `account_number`, `bank_name`, `bank_branch`, `routing_number`, `commission_percentage`) VALUES
(1, 'Inoodex', 'hello@inoodex.com', 15000.00, NULL, '$2y$12$bZ9YAxTXRcahQbLiZ6d8q.cXUkxAbriZ.WtNmnNPrwcOA0PppnPJO', NULL, NULL, NULL, NULL, '2026-02-16 03:09:49', '2026-02-22 04:14:08', NULL, NULL, NULL, 0, '12345678', 'Islami Bank Bangladesh', 'Banani', '1234', NULL),
(2, 'Marketing', 'marketing@example.com', 18000.00, NULL, '$2y$12$ei9LSrLrIhng1FNUsEZztOnKxDIZ3stVVswIXvKK8Dvphk3pTs5BO', NULL, NULL, NULL, NULL, '2026-02-17 12:46:55', '2026-02-23 02:49:28', NULL, NULL, NULL, 0, '521654651', 'Islami Bank Bangladesh', 'Banani', '57868', 3.00),
(3, 'Consultant', 'consultant@example.com', 12000.00, NULL, '$2y$12$m3ZggFrGDw7maLPG1tU8DuKDOTSWUUeSzEFcW1fSfK7xCHJmwJTVW', NULL, NULL, NULL, NULL, '2026-02-17 12:49:33', '2026-02-22 04:14:08', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(4, 'Accountant', 'accountant@example.com', 20000.00, NULL, '$2y$12$eF1/DUs.OmbYrUHoLL0Z.uO5HCJSsdaYSknKdu6Sl1tnYF.dhRC.G', NULL, NULL, NULL, NULL, '2026-02-17 23:01:58', '2026-02-22 04:14:08', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(5, 'Editor', 'editor@example.com', 12000.00, NULL, '$2y$12$cmDPJgBl7B/V8acxTv3Ej.15/p9DkpiY/G/cSFL9j2iwTkKmbbjXW', NULL, NULL, NULL, NULL, '2026-02-18 02:45:29', '2026-02-23 01:15:26', '2026-02-23 01:15:26', 'test', NULL, 0, NULL, NULL, NULL, NULL, NULL),
(6, 'Application', 'application@example.com', 15000.00, NULL, '$2y$12$vZrAn3nHsyH1FOtRvuBW4elSdagHiBA4YnoI2.QdnHkYqeI6MmL1e', NULL, NULL, NULL, NULL, '2026-02-18 22:39:12', '2026-02-22 04:14:08', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-02-16 03:14:04', '2026-02-16 03:14:04'),
(2, 2, 2, '2026-02-17 12:46:55', '2026-02-17 12:46:55'),
(3, 3, 3, '2026-02-17 13:02:10', '2026-02-17 13:02:10'),
(4, 4, 4, '2026-02-17 23:03:02', '2026-02-17 23:03:02'),
(5, 5, 5, '2026-02-18 02:45:29', '2026-02-18 02:45:29'),
(6, 6, 6, '2026-02-18 22:47:11', '2026-02-18 22:47:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_application_id_unique` (`application_id`),
  ADD KEY `applications_student_id_foreign` (`student_id`),
  ADD KEY `applications_university_id_foreign` (`university_id`),
  ADD KEY `applications_course_id_foreign` (`course_id`),
  ADD KEY `applications_course_intake_id_foreign` (`course_intake_id`),
  ADD KEY `applications_created_by_foreign` (`created_by`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_payment_id_foreign` (`payment_id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_university_id_foreign` (`university_id`);

--
-- Indexes for table `course_intakes`
--
ALTER TABLE `course_intakes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_intakes_course_id_foreign` (`course_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`),
  ADD KEY `expenses_office_account_id_foreign` (`office_account_id`),
  ADD KEY `expenses_salary_id_foreign` (`salary_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `finance_categories`
--
ALTER TABLE `finance_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `finance_categories_name_unique` (`name`);

--
-- Indexes for table `invitation_links`
--
ALTER TABLE `invitation_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitation_links_hash_unique` (`hash`),
  ADD KEY `invitation_links_user_id_index` (`user_id`);

--
-- Indexes for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_referrals_invitation_link_id_index` (`invitation_link_id`),
  ADD KEY `invitation_referrals_referred_user_id_index` (`referred_user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_created_by_foreign` (`created_by`),
  ADD KEY `leads_consultant_id_foreign` (`consultant_id`);

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
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `office_accounts`
--
ALTER TABLE `office_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_accounts_created_by_foreign` (`created_by`);

--
-- Indexes for table `office_transactions`
--
ALTER TABLE `office_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_transactions_from_account_id_foreign` (`from_account_id`),
  ADD KEY `office_transactions_to_account_id_foreign` (`to_account_id`),
  ADD KEY `office_transactions_created_by_foreign` (`created_by`);

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
  ADD KEY `payments_collected_by_foreign` (`collected_by`),
  ADD KEY `payments_student_id_payment_type_payment_status_index` (`student_id`,`payment_type`,`payment_status`),
  ADD KEY `payments_application_id_foreign` (`application_id`),
  ADD KEY `payments_office_account_id_foreign` (`office_account_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privileges_slug_unique` (`slug`);

--
-- Indexes for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privilege_role_role_id_privilege_id_unique` (`role_id`,`privilege_id`),
  ADD KEY `privilege_role_privilege_id_foreign` (`privilege_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_slug_index` (`slug`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `salaries_user_id_month_unique` (`user_id`,`month`),
  ADD KEY `salaries_created_by_foreign` (`created_by`),
  ADD KEY `salaries_payment_status_month_index` (`payment_status`,`month`);

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
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_provider_provider_user_id_index` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_user_id_index` (`user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_country_id_foreign` (`country_id`),
  ADD KEY `students_university_id_foreign` (`university_id`),
  ADD KEY `students_course_id_foreign` (`course_id`),
  ADD KEY `students_course_intake_id_foreign` (`course_intake_id`),
  ADD KEY `students_assigned_consultant_id_foreign` (`assigned_consultant_id`),
  ADD KEY `students_assigned_application_id_foreign` (`assigned_application_id`),
  ADD KEY `students_created_by_foreign` (`created_by`),
  ADD KEY `students_current_stage_current_status_index` (`current_stage`,`current_status`),
  ADD KEY `students_assignment_idx` (`assigned_marketing_id`,`assigned_consultant_id`,`assigned_application_id`,`created_by`);

--
-- Indexes for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tyro_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `tyro_audit_logs_user_id_index` (`user_id`),
  ADD KEY `tyro_audit_logs_event_index` (`event`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universities_country_id_foreign` (`country_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_intakes`
--
ALTER TABLE `course_intakes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finance_categories`
--
ALTER TABLE `finance_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invitation_links`
--
ALTER TABLE `invitation_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `office_accounts`
--
ALTER TABLE `office_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `office_transactions`
--
ALTER TABLE `office_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `privilege_role`
--
ALTER TABLE `privilege_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `applications_course_intake_id_foreign` FOREIGN KEY (`course_intake_id`) REFERENCES `course_intakes` (`id`),
  ADD CONSTRAINT `applications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_intakes`
--
ALTER TABLE `course_intakes`
  ADD CONSTRAINT `course_intakes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_salary_id_foreign` FOREIGN KEY (`salary_id`) REFERENCES `salaries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD CONSTRAINT `invitation_referrals_invitation_link_id_foreign` FOREIGN KEY (`invitation_link_id`) REFERENCES `invitation_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_consultant_id_foreign` FOREIGN KEY (`consultant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `office_accounts`
--
ALTER TABLE `office_accounts`
  ADD CONSTRAINT `office_accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `office_transactions`
--
ALTER TABLE `office_transactions`
  ADD CONSTRAINT `office_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `office_transactions_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `office_transactions_to_account_id_foreign` FOREIGN KEY (`to_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD CONSTRAINT `privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_assigned_application_id_foreign` FOREIGN KEY (`assigned_application_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_assigned_consultant_id_foreign` FOREIGN KEY (`assigned_consultant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_assigned_marketing_id_foreign` FOREIGN KEY (`assigned_marketing_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_course_intake_id_foreign` FOREIGN KEY (`course_intake_id`) REFERENCES `course_intakes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `universities`
--
ALTER TABLE `universities`
  ADD CONSTRAINT `universities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
