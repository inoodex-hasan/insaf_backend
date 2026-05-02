-- Adminer 5.3.0 MySQL 8.4.3 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `accounting_periods`;
CREATE TABLE `accounting_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` year DEFAULT NULL,
  `month` tinyint unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('fiscal_year','monthly','quarterly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_closed` tinyint(1) DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounting_periods_year_month_type_unique` (`year`,`month`,`type`),
  KEY `accounting_periods_closed_by_foreign` (`closed_by`),
  CONSTRAINT `accounting_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `accounting_periods` (`id`, `name`, `year`, `month`, `start_date`, `end_date`, `type`, `status`, `remarks`, `is_closed`, `closed_at`, `closed_by`, `created_at`, `updated_at`) VALUES
(1,	'FY-2026',	NULL,	NULL,	'2026-01-01',	'2026-12-31',	'fiscal_year',	'open',	NULL,	NULL,	NULL,	NULL,	'2026-04-12 04:36:10',	'2026-04-12 21:26:37');

DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `application_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `university_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `course_intake_id` bigint unsigned NOT NULL,
  `tuition_fee` decimal(12,2) DEFAULT NULL,
  `tuition_fee_status` enum('pending','paid','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `service_charge_status` enum('pending','paid','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `application_priority` enum('normal','priority','vip') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `documents_checklist` json DEFAULT NULL,
  `final_status` enum('pending','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `security_deposit_status` tinyint(1) NOT NULL DEFAULT '0',
  `cvu_fee_status` tinyint(1) NOT NULL DEFAULT '0',
  `admission_fee_status` tinyint(1) NOT NULL DEFAULT '0',
  `final_payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `emgs_payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `emgs_score` int DEFAULT NULL,
  `total_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `offer_letter_received` tinyint(1) NOT NULL DEFAULT '0',
  `offer_letter_received_date` date DEFAULT NULL,
  `vfs_appointment` tinyint(1) NOT NULL DEFAULT '0',
  `vfs_appointment_date` date DEFAULT NULL,
  `file_submission` tinyint(1) NOT NULL DEFAULT '0',
  `file_submission_date` date DEFAULT NULL,
  `visa_status` enum('not_applied','pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_applied',
  `visa_decision_date` date DEFAULT NULL,
  `visa_approval_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applications_application_id_unique` (`application_id`),
  KEY `applications_student_id_foreign` (`student_id`),
  KEY `applications_university_id_foreign` (`university_id`),
  KEY `applications_course_id_foreign` (`course_id`),
  KEY `applications_course_intake_id_foreign` (`course_intake_id`),
  KEY `applications_created_by_foreign` (`created_by`),
  CONSTRAINT `applications_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `applications_course_intake_id_foreign` FOREIGN KEY (`course_intake_id`) REFERENCES `course_intakes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `applications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `applications_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applications_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `applications` (`id`, `application_id`, `student_id`, `university_id`, `course_id`, `course_intake_id`, `tuition_fee`, `tuition_fee_status`, `service_charge_status`, `application_priority`, `internal_notes`, `documents_checklist`, `final_status`, `security_deposit_status`, `cvu_fee_status`, `admission_fee_status`, `final_payment_status`, `emgs_payment_status`, `emgs_score`, `total_fee`, `status`, `offer_letter_received`, `offer_letter_received_date`, `vfs_appointment`, `vfs_appointment_date`, `file_submission`, `file_submission_date`, `visa_status`, `visa_decision_date`, `visa_approval_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(12,	'APP-2026-00001',	8,	5,	5,	5,	NULL,	'pending',	'pending',	'normal',	NULL,	NULL,	'pending',	0,	0,	0,	0,	0,	NULL,	0.00,	'pending',	0,	NULL,	0,	NULL,	0,	NULL,	'not_applied',	NULL,	NULL,	'application created by admin',	1,	'2026-04-27 04:36:01',	'2026-04-27 04:36:01');

DROP TABLE IF EXISTS `bank_reconciliation_items`;
CREATE TABLE `bank_reconciliation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reconciliation_id` bigint unsigned NOT NULL,
  `bank_statement_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('matched','unmatched','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unmatched',
  `matched_at` timestamp NULL DEFAULT NULL,
  `matched_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `journal_entry_item_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_reconciliation_items_reconciliation_id_foreign` (`reconciliation_id`),
  KEY `bank_reconciliation_items_matched_by_foreign` (`matched_by`),
  KEY `bank_reconciliation_items_journal_entry_item_id_foreign` (`journal_entry_item_id`),
  CONSTRAINT `bank_reconciliation_items_journal_entry_item_id_foreign` FOREIGN KEY (`journal_entry_item_id`) REFERENCES `journal_entry_items` (`id`),
  CONSTRAINT `bank_reconciliation_items_matched_by_foreign` FOREIGN KEY (`matched_by`) REFERENCES `users` (`id`),
  CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` FOREIGN KEY (`reconciliation_id`) REFERENCES `bank_reconciliations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `bank_reconciliations`;
CREATE TABLE `bank_reconciliations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint unsigned NOT NULL,
  `statement_date` date NOT NULL,
  `statement_balance` decimal(15,2) NOT NULL,
  `system_balance` decimal(15,2) NOT NULL,
  `difference` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_reconciliations_account_id_foreign` (`account_id`),
  KEY `bank_reconciliations_closed_by_foreign` (`closed_by`),
  CONSTRAINT `bank_reconciliations_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `office_accounts` (`id`),
  CONSTRAINT `bank_reconciliations_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bank_reconciliations` (`id`, `account_id`, `statement_date`, `statement_balance`, `system_balance`, `difference`, `status`, `closed_at`, `closed_by`, `created_at`, `updated_at`) VALUES
(1,	1,	'2026-04-13',	5000.00,	0.00,	5000.00,	'draft',	NULL,	NULL,	'2026-04-13 05:18:54',	'2026-04-13 05:18:54'),
(2,	3,	'2026-04-13',	25000.00,	100000.00,	-75000.00,	'draft',	NULL,	NULL,	'2026-04-13 05:21:12',	'2026-04-13 05:21:12');

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chart_of_account_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `period` enum('monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budgets_created_by_foreign` (`created_by`),
  KEY `budgets_chart_of_account_id_foreign` (`chart_of_account_id`),
  CONSTRAINT `budgets_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `budgets` (`id`, `chart_of_account_id`, `amount`, `period`, `start_date`, `end_date`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1,	2,	5000.00,	'monthly',	'2026-02-01',	'2026-02-28',	4,	NULL,	'2026-02-18 01:25:05',	'2026-02-18 01:25:05');

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admin-dashboard-cache-tyro:user-1:roles',	'a:1:{i:0;s:5:\"admin\";}',	1777291096),
('admin-dashboard-cache-tyro:user-2:privileges',	'a:1:{i:0;s:10:\"*marketing\";}',	1777291118),
('admin-dashboard-cache-tyro:user-2:roles',	'a:1:{i:0;s:9:\"marketing\";}',	1777291118),
('admin-dashboard-cache-tyro:user-4:privileges',	'a:4:{i:0;s:11:\"*accountant\";i:1;s:8:\"*payment\";i:2;s:10:\"*comission\";i:3;s:8:\"*invoice\";}',	1777644456),
('admin-dashboard-cache-tyro:user-4:roles',	'a:1:{i:0;s:10:\"accountant\";}',	1777644456);

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `chart_of_accounts`;
CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  KEY `chart_of_accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chart_of_accounts` (`id`, `parent_id`, `code`, `name`, `type`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(1,	NULL,	'51001',	'Rent',	'expense',	1,	0,	'2026-04-07 23:29:08',	'2026-04-07 23:29:08'),
(2,	NULL,	'51002',	'Marketing',	'expense',	1,	0,	'2026-04-07 23:30:52',	'2026-04-07 23:30:52'),
(3,	NULL,	'51003',	'Salaries',	'expense',	1,	0,	'2026-04-07 23:31:47',	'2026-04-07 23:31:47'),
(4,	NULL,	'51004',	'Utilities',	'expense',	1,	0,	'2026-04-07 23:31:47',	'2026-04-07 23:31:47'),
(5,	NULL,	'51005',	'Office Supplies',	'expense',	1,	0,	'2026-04-07 23:31:47',	'2026-04-07 23:31:47'),
(6,	NULL,	'41001',	'Student Fees',	'revenue',	1,	0,	'2026-04-07 23:31:47',	'2026-04-07 23:31:47'),
(7,	NULL,	'10001',	'Current Asset',	'asset',	1,	0,	'2026-04-12 05:19:04',	'2026-04-25 06:17:36'),
(9,	NULL,	'10002',	'Cash In Hand',	'asset',	1,	0,	'2026-04-13 00:49:29',	'2026-04-25 06:18:11'),
(10,	NULL,	'20002',	'Bank',	'asset',	1,	0,	'2026-04-15 05:57:02',	'2026-04-15 05:57:02'),
(11,	NULL,	'20003',	'MFS (bKash)',	'asset',	1,	0,	'2026-04-15 05:58:08',	'2026-04-15 05:58:08'),
(12,	NULL,	'10003',	'Fixed Asset',	'asset',	1,	0,	'2026-04-25 06:18:28',	'2026-04-25 06:18:28'),
(13,	NULL,	'30001',	'Current Liability',	'liability',	1,	0,	'2026-04-25 06:19:08',	'2026-04-25 06:19:08'),
(14,	NULL,	'30002',	'Long Term Liability',	'liability',	1,	0,	'2026-04-25 06:19:51',	'2026-04-25 06:19:51');

DROP TABLE IF EXISTS `commissions`;
CREATE TABLE `commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `proposed_amount` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `workflow_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `claimed_at` timestamp NULL DEFAULT NULL,
  `claim_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commissions_application_id_foreign` (`application_id`),
  KEY `commissions_user_id_foreign` (`user_id`),
  KEY `commissions_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `commissions_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `countries` (`id`, `name`, `code`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(4,	'Malta',	NULL,	NULL,	1,	'2026-04-14 23:35:04',	'2026-04-14 23:35:04'),
(5,	'Malaysia',	NULL,	NULL,	1,	'2026-04-14 23:48:04',	'2026-04-14 23:48:04'),
(6,	'Russia',	NULL,	NULL,	1,	'2026-04-27 03:58:15',	'2026-04-27 03:58:15');

DROP TABLE IF EXISTS `course_intakes`;
CREATE TABLE `course_intakes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `intake_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `application_start_date` date DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `class_start_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_intakes_course_id_foreign` (`course_id`),
  CONSTRAINT `course_intakes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `course_intakes` (`id`, `course_id`, `intake_name`, `application_start_date`, `application_deadline`, `class_start_date`, `status`, `created_at`, `updated_at`) VALUES
(3,	3,	'Summer 2026',	'2026-05-01',	'2026-05-31',	NULL,	1,	'2026-04-14 23:47:34',	'2026-04-14 23:47:34'),
(4,	4,	'Summer 2026',	'2026-04-01',	'2026-04-30',	NULL,	1,	'2026-04-15 00:11:26',	'2026-04-15 00:11:56'),
(5,	5,	'Summer 2026',	'2026-05-01',	'2026-05-31',	NULL,	1,	'2026-04-27 04:02:11',	'2026-04-27 04:02:11');

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `university_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tuition_fee` decimal(12,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_university_id_foreign` (`university_id`),
  CONSTRAINT `courses_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `courses` (`id`, `university_id`, `name`, `degree_level`, `duration`, `tuition_fee`, `status`, `created_at`, `updated_at`) VALUES
(3,	4,	'Law',	'Masters',	'6 Month',	NULL,	1,	'2026-04-14 23:42:41',	'2026-04-15 00:07:46'),
(4,	3,	'Cyber Security',	'Masters',	'6 Month',	NULL,	1,	'2026-04-15 00:06:14',	'2026-04-15 00:07:32'),
(5,	5,	'Physics',	'Masters',	'12',	NULL,	1,	'2026-04-27 04:01:46',	'2026-04-27 04:01:46');

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chart_of_account_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','mobile_banking','cheque') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_account_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `salary_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_office_account_id_foreign` (`office_account_id`),
  KEY `expenses_salary_id_foreign` (`salary_id`),
  KEY `expenses_chart_of_account_id_foreign` (`chart_of_account_id`),
  KEY `expenses_journal_entry_id_foreign` (`journal_entry_id`),
  CONSTRAINT `expenses_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  CONSTRAINT `expenses_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_salary_id_foreign` FOREIGN KEY (`salary_id`) REFERENCES `salaries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expenses` (`id`, `chart_of_account_id`, `description`, `amount`, `expense_date`, `payment_method`, `office_account_id`, `created_by`, `notes`, `created_at`, `updated_at`, `salary_id`, `journal_entry_id`) VALUES
(14,	5,	'Furniture Buy',	5000.00,	'2026-04-20',	'cash',	3,	4,	'Furniture Buy',	'2026-04-20 00:38:22',	'2026-04-20 00:38:22',	NULL,	NULL);

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `invitation_links`;
CREATE TABLE `invitation_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `hash` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invitation_links_hash_unique` (`hash`),
  KEY `invitation_links_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `invitation_referrals`;
CREATE TABLE `invitation_referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invitation_link_id` bigint unsigned NOT NULL,
  `referred_user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invitation_referrals_invitation_link_id_index` (`invitation_link_id`),
  KEY `invitation_referrals_referred_user_id_index` (`referred_user_id`),
  CONSTRAINT `invitation_referrals_invitation_link_id_foreign` FOREIGN KEY (`invitation_link_id`) REFERENCES `invitation_links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT '1.00',
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_chart_of_account_id_foreign` (`chart_of_account_id`),
  CONSTRAINT `invoice_items_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoice_items` (`id`, `invoice_id`, `chart_of_account_id`, `description`, `quantity`, `unit_price`, `subtotal`, `tax_amount`, `total`, `created_at`, `updated_at`) VALUES
(11,	5,	6,	'security deposit fee from student',	1.00,	5000.00,	5000.00,	0.00,	5000.00,	'2026-04-27 05:28:59',	'2026-04-27 05:28:59');

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned DEFAULT NULL,
  `application_id` bigint unsigned DEFAULT NULL,
  `university_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','paid','partially_paid','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_application_id_foreign` (`application_id`),
  KEY `invoices_student_id_foreign` (`student_id`),
  KEY `invoices_university_id_foreign` (`university_id`),
  CONSTRAINT `invoices_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `invoices_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `invoices` (`id`, `student_id`, `application_id`, `university_id`, `invoice_number`, `date`, `due_date`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5,	8,	12,	5,	'INV-20260427-F287',	'2026-04-27',	'2026-04-30',	5000.00,	'draft',	'security fee',	'2026-04-27 05:28:59',	'2026-04-27 05:28:59');

DROP TABLE IF EXISTS `job_batches`;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `period_id` bigint unsigned NOT NULL,
  `application_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','posted','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_entries_reference_number_unique` (`reference_number`),
  KEY `journal_entries_period_id_foreign` (`period_id`),
  KEY `journal_entries_created_by_foreign` (`created_by`),
  KEY `journal_entries_application_id_foreign` (`application_id`),
  CONSTRAINT `journal_entries_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL,
  CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `journal_entries_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `accounting_periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `journal_entries` (`id`, `period_id`, `application_id`, `date`, `reference_number`, `note`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(5,	1,	NULL,	'2026-04-25',	'JV-20260425-4A9F',	NULL,	'posted',	4,	'2026-04-25 05:49:21',	'2026-04-25 05:49:21'),
(6,	1,	NULL,	'2026-04-25',	'JV-20260425-A12F',	NULL,	'posted',	4,	'2026-04-25 06:06:44',	'2026-04-25 06:06:44');

DROP TABLE IF EXISTS `journal_entry_items`;
CREATE TABLE `journal_entry_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entry_items_journal_entry_id_foreign` (`journal_entry_id`),
  KEY `journal_entry_items_chart_of_account_id_foreign` (`chart_of_account_id`),
  CONSTRAINT `journal_entry_items_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_entry_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `journal_entry_items` (`id`, `journal_entry_id`, `chart_of_account_id`, `debit`, `credit`, `description`, `created_at`, `updated_at`) VALUES
(9,	5,	6,	5000.00,	0.00,	'security fee from student',	'2026-04-25 05:49:21',	'2026-04-25 05:49:21'),
(10,	5,	9,	0.00,	5000.00,	'student fee from student',	'2026-04-25 05:49:21',	'2026-04-25 05:49:21'),
(11,	6,	3,	12000.00,	0.00,	'provide salary',	'2026-04-25 06:06:44',	'2026-04-25 06:06:44'),
(12,	6,	9,	0.00,	12000.00,	'salary from office cash',	'2026-04-25 06:06:44',	'2026-04-25 06:06:44');

DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_education` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_country` bigint unsigned DEFAULT NULL,
  `preferred_course` bigint unsigned DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `next_follow_up_at` timestamp NULL DEFAULT NULL,
  `follow_up_history` json DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `consultant_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_created_by_foreign` (`created_by`),
  KEY `leads_consultant_id_foreign` (`consultant_id`),
  KEY `leads_preferred_country_foreign` (`preferred_country`),
  KEY `leads_preferred_course_foreign` (`preferred_course`),
  CONSTRAINT `leads_consultant_id_foreign` FOREIGN KEY (`consultant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `leads_preferred_country_foreign` FOREIGN KEY (`preferred_country`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_preferred_course_foreign` FOREIGN KEY (`preferred_course`) REFERENCES `courses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leads` (`id`, `student_name`, `email`, `phone`, `current_education`, `preferred_country`, `preferred_course`, `source`, `status`, `notes`, `last_contacted_at`, `next_follow_up_at`, `follow_up_history`, `created_by`, `consultant_id`, `created_at`, `updated_at`) VALUES
(8,	'Md Hasan',	'hasssaninoodex@gmail.com',	'01234567890',	'SSC',	6,	5,	'Phone',	'pending',	'will visit office',	NULL,	'2026-04-29 18:00:00',	'[{\"date\": \"2026-04-30\", \"notes\": \"will visit office\"}]',	1,	NULL,	'2026-04-27 04:13:59',	'2026-04-27 04:13:59');

DROP TABLE IF EXISTS `marketing_campaigns`;
CREATE TABLE `marketing_campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `boosting_status` enum('on','off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `created_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_campaigns_created_by_foreign` (`created_by`),
  CONSTRAINT `marketing_campaigns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `marketing_campaigns` (`id`, `name`, `start_date`, `end_date`, `boosting_status`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1,	'Summer 2026',	'2026-05-01',	'2026-05-10',	'off',	7,	NULL,	'2026-04-25 23:01:19',	'2026-04-26 02:20:13');

DROP TABLE IF EXISTS `marketing_documents`;
CREATE TABLE `marketing_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint unsigned NOT NULL,
  `document_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` enum('sop','cv','cl') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','received','not_received','ready','submitted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_documents_application_id_foreign` (`application_id`),
  KEY `marketing_documents_created_by_foreign` (`created_by`),
  CONSTRAINT `marketing_documents_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `marketing_documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `marketing_posters`;
CREATE TABLE `marketing_posters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `poster_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ready','not_ready','uploaded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_ready',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `marketing_videos`;
CREATE TABLE `marketing_videos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `video_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('edited','upload','not_edited','ready') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_edited',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'0001_01_01_000000_create_users_table',	1),
(2,	'0001_01_01_000001_create_cache_table',	1),
(3,	'0001_01_01_000002_create_jobs_table',	1),
(4,	'2022_05_17_181447_create_roles_table',	1),
(5,	'2022_05_17_181456_create_user_roles_table',	1),
(6,	'2025_01_01_000001_create_privileges_table',	1),
(7,	'2025_01_01_000002_create_privilege_role_table',	1),
(8,	'2025_01_01_000003_add_suspension_columns_to_users_table',	1),
(9,	'2026_02_02_085518_create_personal_access_tokens_table',	1),
(10,	'2026_02_03_073742_create_settings_table',	1),
(11,	'2026_02_03_085903_add_is_active_to_roles_table',	1),
(12,	'2026_02_03_100000_create_countries_table',	1),
(13,	'2026_02_03_100001_create_universities_table',	1),
(14,	'2026_02_03_100002_create_courses_table',	1),
(15,	'2026_02_03_100003_create_course_intakes_table',	1),
(16,	'2026_02_03_111812_create_leads_table',	1),
(17,	'2026_02_03_123612_create_students_table',	1),
(18,	'2026_02_03_133591_create_payments_table',	1),
(19,	'2026_02_15_202417_create_applications_table',	1),
(20,	'2026_02_17_131456_add_application_id_to_payments_table',	2),
(21,	'2026_02_17_184206_create_notifications_table',	3),
(22,	'2026_02_18_063017_create_expenses_table',	4),
(23,	'2026_02_18_064510_create_office_accounts_table',	5),
(24,	'2026_02_18_065315_create_office_transactions_table',	6),
(25,	'2026_02_18_071448_create_budgets_table',	7),
(26,	'2026_02_18_083029_create_finance_categories_table',	8),
(27,	'2026_02_18_084023_add_total_fee_to_applications_table',	9),
(28,	'2026_02_18_094859_add_currency_to_courses_table',	10),
(29,	'2026_02_18_095243_create_currencies_table',	11),
(30,	'2026_02_18_113730_add_tuition_fee_to_applications_table',	12),
(31,	'2026_02_18_114225_add_currency_to_applications_table',	13),
(32,	'2026_02_18_115623_add_exchange_rate_to_currencies_table',	14),
(33,	'2026_02_19_100000_create_salaries_table',	15),
(34,	'2024_01_01_000000_create_social_accounts_table',	16),
(35,	'2024_01_01_000001_add_two_factor_columns_to_users_table',	16),
(36,	'2024_01_01_000002_create_invitation_system_tables',	16),
(37,	'2025_02_08_000000_add_profile_photo_to_users_table',	16),
(38,	'2026_02_15_000000_create_tyro_audit_logs_table',	16),
(39,	'2026_02_21_085827_create_commissions_table',	16),
(40,	'2026_02_22_074425_add_is_template_to_salaries_table',	17),
(41,	'2026_02_22_081212_add_office_account_id_to_payments_table',	17),
(42,	'2026_02_22_081922_add_office_account_id_to_expenses_table',	18),
(43,	'2026_02_22_081930_add_income_expense_to_office_transactions_type',	18),
(44,	'2026_02_22_082610_add_basic_salary_to_users_table',	19),
(45,	'2026_02_22_090213_add_account_details_to_users_table',	20),
(46,	'2026_02_22_094533_add_commission_percentage_to_users_table',	21),
(47,	'2026_02_22_102330_add_balance_to_office_accounts_table',	22),
(48,	'2026_02_23_045430_add_notes_to_payments_table',	23),
(49,	'2026_02_23_100000_add_salary_id_to_expenses',	24),
(50,	'2026_02_23_110000_add_opening_balance_to_office_accounts',	25),
(51,	'2026_04_07_000001_create_accounting_periods_table',	26),
(52,	'2026_04_07_000002_create_chart_of_accounts_table',	26),
(53,	'2026_04_07_000003_create_journal_entries_table',	26),
(54,	'2026_04_07_000004_create_journal_entry_items_table',	26),
(55,	'2026_04_07_000005_create_taxes_table',	26),
(56,	'2026_04_07_000006_create_invoices_table',	26),
(57,	'2026_04_07_000007_create_invoice_items_table',	26),
(58,	'2026_04_07_000008_create_bank_reconciliations_table',	26),
(59,	'2026_04_07_000009_create_bank_reconciliation_items_table',	26),
(60,	'2026_04_07_000010_add_ledger_links_to_finance_tables',	27),
(61,	'2026_04_07_000011_create_marketing_campaigns_table',	28),
(62,	'2026_04_07_000012_create_marketing_videos_table',	28),
(63,	'2026_04_07_000013_create_marketing_posters_table',	28),
(66,	'2026_04_08_000000_add_missing_columns_to_accounting_periods_table',	29),
(68,	'2026_04_08_000001_migrate_finance_categories_to_chart_of_accounts',	30),
(69,	'2026_04_08_000002_convert_office_transactions_to_journal_entries',	30),
(70,	'2026_04_08_000003_remove_currencies_table_and_columns',	31),
(71,	'2026_04_08_100000_fix_payments_foreign_key_constraints',	32),
(72,	'2026_04_08_100001_fix_accounting_periods_unique_constraint',	33),
(73,	'2026_04_08_100002_remove_redundant_commission_settings',	34),
(74,	'2026_04_08_100003_fix_leads_preferred_country_and_course_foreign_keys',	35),
(75,	'2026_04_08_100004_add_application_id_to_invoices',	36),
(76,	'2026_04_08_100005_fix_expenses_payment_method_to_enum',	37),
(78,	'2026_04_08_100006_fix_remaining_foreign_key_constraints',	38),
(79,	'2026_04_08_100007_add_student_portal_fields_to_students',	38),
(80,	'2026_04_09_090000_add_follow_up_history_to_leads_table',	39),
(82,	'2026_04_11_000000_add_application_tracking_fields_to_applications_table',	40),
(83,	'2026_04_11_045646_add_payment_status_fields_to_applications_table',	41),
(84,	'2026_04_11_050000_fix_journal_entries_and_office_accounts',	42),
(85,	'2026_04_12_103504_fix_accounting_periods_old_columns',	43),
(86,	'2026_04_13_075942_fix_bank_reconciliation_items_table',	44),
(87,	'2026_04_13_104949_add_application_id_to_journal_entries',	45),
(88,	'2026_04_15_040630_create_vfs_checklists_table',	46),
(89,	'2026_04_15_180449_drop_commissions_table',	47),
(90,	'2026_04_15_180930_create_commissions_table',	47),
(91,	'2026_04_20_035455_add_emgs_score_to_applications_table',	48),
(92,	'2026_04_21_000000_create_vfs_checklist_templates_table',	49),
(93,	'2026_04_21_000001_add_country_to_vfs_checklist_templates',	50),
(94,	'2026_04_25_100000_add_workflow_fields_to_commissions_table',	51),
(95,	'2026_04_26_100000_add_dates_to_marketing_campaigns_table',	52),
(96,	'2026_04_26_110000_remove_campaign_id_from_marketing_videos',	53),
(97,	'2026_04_26_110001_remove_campaign_id_from_marketing_posters',	53),
(98,	'2026_04_26_120000_create_marketing_documents_table',	54),
(99,	'2026_04_26_130000_create_marketing_documents_table',	55),
(100,	'2026_04_27_102940_add_plain_password_to_students_table',	56);

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('1b8dac70-217e-4509-a24a-e341a5650b5b',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":4,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Rahim\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/4\\/edit\"}',	'2026-02-21 23:09:23',	'2026-02-21 23:04:30',	'2026-02-21 23:09:23'),
('272cfc76-a9c4-42ed-a739-6e6fc772464d',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":8,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/8\\/edit\"}',	'2026-04-10 23:13:06',	'2026-02-22 10:56:38',	'2026-04-10 23:13:06'),
('3b9c3a71-77f6-42b1-a0c1-3fa6e9da4486',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":6,\"student_name\":\"Hasan\",\"phone\":\"0120320020\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/6\"}',	'2026-02-22 10:56:19',	'2026-02-22 03:55:24',	'2026-02-22 10:56:19'),
('3d8759c4-3a76-4f5c-a99a-e1957c4f7282',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":11,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/11\\/edit\"}',	'2026-04-15 03:41:56',	'2026-04-15 02:17:11',	'2026-04-15 03:41:56'),
('40594f0f-ca0c-443a-9fc1-f07f215bf713',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":12,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Inoodex\",\"message\":\"New application APP-2026-00001 created by Inoodex\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/12\\/edit\"}',	NULL,	'2026-04-27 04:36:01',	'2026-04-27 04:36:01'),
('48ef3682-ba23-434a-9947-2e087b1b64e4',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":8,\"student_name\":\"Md Hasan\",\"phone\":\"01234567890\",\"created_by\":\"Inoodex\",\"message\":\"New lead submitted by Inoodex\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/8\"}',	NULL,	'2026-04-27 04:14:00',	'2026-04-27 04:14:00'),
('4919412a-a1be-4937-b3e5-477292752eaa',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":4,\"student_name\":\"Ashraful Islam\",\"phone\":\"01195674368\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/4\"}',	'2026-02-18 05:00:58',	'2026-02-18 05:00:49',	'2026-02-18 05:00:58'),
('4aeba9c4-3e3a-4d9d-bbcd-83bb23f1bb8f',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":10,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/10\\/edit\"}',	'2026-04-10 23:13:06',	'2026-02-22 11:22:42',	'2026-04-10 23:13:06'),
('50ac2d91-74ed-455f-9787-95c679ac268a',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":5,\"application_number\":\"APP-2026-00001\",\"student_name\":\"Md Hasan\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00001 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/5\\/edit\"}',	'2026-02-22 02:55:59',	'2026-02-22 00:03:14',	'2026-02-22 02:55:59'),
('6fb35d96-f99f-4ed8-b59b-d43c56c2bb05',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":3,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Abra Klein\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/3\\/edit\"}',	'2026-02-19 00:07:28',	'2026-02-19 00:07:17',	'2026-02-19 00:07:28'),
('a8e631fb-ec13-4392-868a-ff5d95ab97b4',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":2,\"student_name\":\"fsgh\",\"phone\":\"0187452963\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/2\"}',	'2026-02-17 13:09:41',	'2026-02-17 13:09:17',	'2026-02-17 13:09:41'),
('ac808b50-3412-4c91-b0bd-50ad2380f2e0',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":7,\"application_number\":\"APP-2026-00003\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00003 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/7\\/edit\"}',	'2026-04-10 23:13:06',	'2026-02-22 04:00:03',	'2026-04-10 23:13:06'),
('cf7c7ea3-4775-4be3-bbaa-7df4b4e3162e',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":9,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/9\\/edit\"}',	'2026-04-10 23:13:06',	'2026-02-22 10:56:57',	'2026-04-10 23:13:06'),
('e7e77362-16c9-47f6-b9f7-1e5972367bdb',	'App\\Notifications\\NewApplicationNotification',	'App\\Models\\User',	6,	'{\"application_id\":6,\"application_number\":\"APP-2026-00002\",\"student_name\":\"Md Rakib\",\"created_by\":\"Consultant\",\"message\":\"New application APP-2026-00002 created by Consultant\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/applications\\/6\\/edit\"}',	'2026-04-10 23:13:06',	'2026-02-22 03:58:53',	'2026-04-10 23:13:06'),
('ebe6636b-5a5f-4e95-b12b-02211b342c2e',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":5,\"student_name\":\"Rahim\",\"phone\":\"01234567890\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/5\"}',	'2026-02-21 22:54:19',	'2026-02-21 22:53:16',	'2026-02-21 22:54:19'),
('f331d0ec-990f-4465-b30e-12f38e376fd1',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":3,\"student_name\":\"Hasan\",\"phone\":\"0101010101010\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/3\"}',	'2026-02-18 04:24:14',	'2026-02-18 04:22:05',	'2026-02-18 04:24:14'),
('f82fcdd3-7af1-43c9-b014-4a94fb2562d1',	'App\\Notifications\\NewLeadSubmitted',	'App\\Models\\User',	3,	'{\"lead_id\":7,\"student_name\":\"Md Hasan\",\"phone\":\"01234567890\",\"created_by\":\"Marketing\",\"message\":\"New lead submitted by Marketing\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/dashboard\\/marketing\\/leads\\/7\"}',	'2026-04-15 01:38:00',	'2026-04-15 00:20:26',	'2026-04-15 01:38:00');

DROP TABLE IF EXISTS `office_accounts`;
CREATE TABLE `office_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('bank','mfs','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_of_account_id` bigint unsigned DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `office_accounts_created_by_foreign` (`created_by`),
  KEY `office_accounts_chart_of_account_id_foreign` (`chart_of_account_id`),
  CONSTRAINT `office_accounts_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `office_accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `office_accounts` (`id`, `account_name`, `account_type`, `provider_name`, `account_number`, `chart_of_account_id`, `opening_balance`, `branch_name`, `status`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1,	'Inoodex',	'mfs',	'bKash',	'01234567890',	NULL,	0.00,	NULL,	'active',	4,	NULL,	'2026-02-18 00:48:45',	'2026-02-23 03:44:33'),
(2,	'Inoodex',	'bank',	'Dutch Banla Bank',	'0123456789',	NULL,	0.00,	'Banani',	'active',	4,	NULL,	'2026-02-18 00:55:47',	'2026-02-23 00:41:15'),
(3,	'Office Cash',	'cash',	NULL,	'1',	NULL,	100000.00,	NULL,	'active',	4,	NULL,	'2026-02-23 01:07:07',	'2026-02-23 01:07:07');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `application_id` bigint unsigned DEFAULT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('advance','partial','final') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `collected_by` bigint unsigned DEFAULT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `office_account_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `payments_collected_by_foreign` (`collected_by`),
  KEY `payments_student_id_payment_type_payment_status_index` (`student_id`,`payment_type`,`payment_status`),
  KEY `payments_application_id_foreign` (`application_id`),
  KEY `payments_office_account_id_foreign` (`office_account_id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_journal_entry_id_foreign` (`journal_entry_id`),
  CONSTRAINT `payments_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `payments_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  CONSTRAINT `payments_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `privilege_role`;
CREATE TABLE `privilege_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `privilege_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `privilege_role_role_id_privilege_id_unique` (`role_id`,`privilege_id`),
  KEY `privilege_role_privilege_id_foreign` (`privilege_id`),
  CONSTRAINT `privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE,
  CONSTRAINT `privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privilege_role` (`id`, `role_id`, `privilege_id`, `created_at`, `updated_at`) VALUES
(1,	2,	1,	'2026-02-17 12:47:37',	'2026-02-17 12:47:37'),
(2,	3,	2,	'2026-02-17 12:50:08',	'2026-02-17 12:50:08'),
(3,	4,	3,	'2026-02-17 23:02:46',	'2026-02-17 23:02:46'),
(4,	5,	4,	'2026-02-18 02:45:49',	'2026-02-18 02:45:49'),
(5,	6,	5,	'2026-02-18 22:39:48',	'2026-02-18 22:39:48'),
(6,	1,	6,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(7,	10,	6,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(8,	1,	7,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(9,	10,	7,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(10,	10,	8,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(11,	1,	9,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(12,	7,	9,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(14,	4,	11,	'2026-04-07 21:53:18',	'2026-04-07 21:53:18'),
(15,	4,	12,	'2026-04-07 21:55:21',	'2026-04-07 21:55:21'),
(16,	3,	13,	'2026-04-11 04:25:47',	'2026-04-11 04:25:47'),
(17,	4,	13,	'2026-04-11 04:25:47',	'2026-04-11 04:25:47'),
(18,	11,	14,	'2026-04-25 22:34:35',	'2026-04-25 22:34:35');

DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `privileges_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privileges` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1,	'Marketing',	'*marketing',	NULL,	'2026-02-17 12:47:37',	'2026-02-17 12:47:37'),
(2,	'Consultant',	'*consultant',	NULL,	'2026-02-17 12:50:08',	'2026-02-17 23:20:02'),
(3,	'Accountant',	'*accountant',	NULL,	'2026-02-17 23:02:46',	'2026-02-17 23:02:46'),
(4,	'Editor',	'*editor',	NULL,	'2026-02-18 02:45:49',	'2026-02-18 02:45:49'),
(5,	'Application',	'*application',	NULL,	'2026-02-18 22:39:48',	'2026-02-18 22:39:48'),
(6,	'Generate Reports',	'report.generate',	'Allows generating system-wide reports.',	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(7,	'Manage Users',	'users.manage',	'Allows creating, editing, and deleting users.',	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(8,	'Manage Roles',	'roles.manage',	'Allows editing Tyro roles.',	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(9,	'View Billing',	'billing.view',	'Allows viewing billing statements.',	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(10,	'Wildcard',	'*',	'Grants every privilege.',	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(11,	'Payment',	'*payment',	NULL,	'2026-04-07 21:53:18',	'2026-04-07 21:55:35'),
(12,	'Comission',	'*comission',	NULL,	'2026-04-07 21:55:21',	'2026-04-07 21:55:21'),
(13,	'Invoice',	'*invoice',	NULL,	'2026-04-11 04:25:47',	'2026-04-11 04:25:47'),
(14,	'Digital Marketing',	'*digital_marketing',	NULL,	'2026-04-25 22:34:35',	'2026-04-25 22:34:35');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'Administrator',	'admin',	1,	'2026-02-16 03:13:51',	'2026-02-21 01:19:44'),
(2,	'Marketing',	'marketing',	1,	'2026-02-16 03:16:47',	'2026-02-16 03:16:47'),
(3,	'Consultant',	'consultant',	1,	'2026-02-17 12:49:50',	'2026-02-17 12:57:56'),
(4,	'Accountant',	'accountant',	1,	'2026-02-17 23:02:22',	'2026-02-17 23:02:22'),
(5,	'Editor',	'editor',	1,	'2026-02-18 02:45:05',	'2026-02-18 02:45:05'),
(6,	'Application',	'application',	1,	'2026-02-18 22:39:28',	'2026-02-18 22:52:44'),
(7,	'User',	'user',	1,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(10,	'Super Admin',	'super-admin',	1,	'2026-02-21 01:19:44',	'2026-02-21 01:19:44'),
(11,	'Digital Marketing',	'digital-marketing',	1,	'2026-04-25 22:33:54',	'2026-04-25 22:33:54');

DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
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
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salaries_user_id_month_unique` (`user_id`,`month`),
  KEY `salaries_created_by_foreign` (`created_by`),
  KEY `salaries_payment_status_month_index` (`payment_status`,`month`),
  KEY `salaries_journal_entry_id_foreign` (`journal_entry_id`),
  CONSTRAINT `salaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `salaries_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`),
  CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('e8Y10UPtDRDLWfdlnTzNejJcmTfl4GwcdFuIDBtY',	2,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiT1RwOEgxU2NmVm1xek9jek5FNW5MN0ZTTkxCb0h2RUNndWhwYno0bCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZC9zdHVkZW50cy9jcmVhdGUiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLnN0dWRlbnRzLmNyZWF0ZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',	1777291118),
('Fm80Ol9H3ljDQ0wDQG7gem9uieQf3XgaxgWlDfdx',	4,	'127.0.0.1',	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',	'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNEZSTnEzV2oyYnliR2ZBSlpZRnpKNDFEeEZuT2JRQVRGeFB6UnlWOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvcmVwb3J0cy9zdW1tYXJ5IjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5yZXBvcnRzLnN1bW1hcnkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjEwOiJ0eXJvLWxvZ2luIjthOjE6e3M6NzoiY2FwdGNoYSI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',	1777644156);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1,	'app_name',	'Inoodex',	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(2,	'contact_email',	'hello@inoodex.com',	'2026-02-16 03:20:28',	'2026-02-23 02:49:28'),
(3,	'contact_phone',	'01234567890',	'2026-02-16 03:20:28',	'2026-02-23 02:49:28'),
(4,	'address',	'Mirpur, Dhaka',	'2026-02-16 03:20:28',	'2026-02-23 02:49:28'),
(5,	'social_facebook',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(6,	'social_twitter',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(7,	'social_linkedin',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(8,	'currency_symbol',	NULL,	'2026-02-16 03:20:28',	'2026-02-22 00:06:27'),
(9,	'date_format',	'd/m/Y',	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(10,	'enable_registration',	'0',	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(11,	'maintenance_mode',	'0',	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(12,	'meta_title',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(13,	'meta_description',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(14,	'meta_keywords',	NULL,	'2026-02-16 03:20:28',	'2026-02-16 03:20:28'),
(15,	'app_logo',	'uploads/settings/55dl2iPgxNFXTX31JtwAREFkupbl3eQcVSVFVYRy.png',	'2026-02-17 22:37:17',	'2026-02-22 11:16:54'),
(16,	'app_favicon',	'uploads/settings/POlV6I7SgJuvs1SoD6ktrvHlzxRv0p9Zo1JK4HuU.png',	'2026-02-17 22:37:17',	'2026-02-22 11:16:54');

DROP TABLE IF EXISTS `social_accounts`;
CREATE TABLE `social_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  KEY `social_accounts_provider_provider_user_id_index` (`provider`,`provider_user_id`),
  KEY `social_accounts_user_id_index` (`user_id`),
  CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_validity` date DEFAULT NULL,
  `translation_documents` json DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plain_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sponsor_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dob` date DEFAULT NULL,
  `ssc_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hsc_result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ielts_score` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `university_id` bigint unsigned DEFAULT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `course_intake_id` bigint unsigned DEFAULT NULL,
  `current_stage` enum('lead','counseling','payment','application','offer','visa','enrolled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_status` enum('pending','applied','rejected','withdrawn','visa_processing','enrolled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_marketing_id` bigint unsigned DEFAULT NULL,
  `assigned_consultant_id` bigint unsigned DEFAULT NULL,
  `assigned_application_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_country_id_foreign` (`country_id`),
  KEY `students_university_id_foreign` (`university_id`),
  KEY `students_course_id_foreign` (`course_id`),
  KEY `students_course_intake_id_foreign` (`course_intake_id`),
  KEY `students_assigned_consultant_id_foreign` (`assigned_consultant_id`),
  KEY `students_assigned_application_id_foreign` (`assigned_application_id`),
  KEY `students_created_by_foreign` (`created_by`),
  KEY `students_current_stage_current_status_index` (`current_stage`,`current_status`),
  KEY `students_assignment_idx` (`assigned_marketing_id`,`assigned_consultant_id`,`assigned_application_id`,`created_by`),
  CONSTRAINT `students_assigned_application_id_foreign` FOREIGN KEY (`assigned_application_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_assigned_consultant_id_foreign` FOREIGN KEY (`assigned_consultant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_assigned_marketing_id_foreign` FOREIGN KEY (`assigned_marketing_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_course_intake_id_foreign` FOREIGN KEY (`course_intake_id`) REFERENCES `course_intakes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `students` (`id`, `first_name`, `last_name`, `father_name`, `mother_name`, `passport_number`, `passport_validity`, `translation_documents`, `email`, `password`, `plain_password`, `phone`, `sponsor_phone`, `address`, `dob`, `ssc_result`, `hsc_result`, `ielts_score`, `subject`, `country_id`, `university_id`, `course_id`, `course_intake_id`, `current_stage`, `current_status`, `assigned_marketing_id`, `assigned_consultant_id`, `assigned_application_id`, `created_by`, `documents`, `created_at`, `updated_at`) VALUES
(8,	'Md',	'Hasan',	'Md Kamruzzaman',	'Kaniz Fatema',	'AB123456',	'2030-01-01',	'[{\"name\": \"VFS CHECK LIST.pdf\", \"path\": \"documents/students/translations/GMNpC0Cpm0eVERmY6UjY0fXv7qH7zPhHzJg2PXSe.pdf\"}]',	'hasssaninoodex@gmail.com',	'$2y$12$klxP1/MTQj3AZ8OG96xoe.8y9NLb/Ptai9SgX8NcjRpZw4AQt0epC',	'hasan@1234#',	'01200000000',	'01234567890',	'Mirpur, Dhaka',	'1990-01-01',	NULL,	NULL,	NULL,	NULL,	6,	5,	5,	5,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	'[{\"name\": \"sample.pdf\", \"path\": \"documents/students/whNWgxo3toLD6SbGIG7qolI7sEUw4xh6b1fgIN6T.pdf\"}]',	'2026-04-27 04:25:40',	'2026-04-27 04:35:00');

DROP TABLE IF EXISTS `taxes`;
CREATE TABLE `taxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxes_chart_of_account_id_foreign` (`chart_of_account_id`),
  CONSTRAINT `taxes_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tyro_audit_logs`;
CREATE TABLE `tyro_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tyro_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `tyro_audit_logs_user_id_index` (`user_id`),
  KEY `tyro_audit_logs_event_index` (`event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tyro_audit_logs` (`id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `metadata`, `created_at`) VALUES
(1,	1,	'role.deleted',	'HasinHayder\\Tyro\\Models\\Role',	8,	'{\"id\": 8, \"name\": \"Customer\", \"slug\": \"customer\", \"is_active\": 1}',	NULL,	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-22 16:51:11'),
(2,	1,	'role.deleted',	NULL,	NULL,	'{\"id\": 8, \"name\": \"Customer\", \"slug\": \"customer\"}',	NULL,	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-22 16:51:11'),
(3,	1,	'role.deleted',	'HasinHayder\\Tyro\\Models\\Role',	9,	'{\"id\": 9, \"name\": \"All\", \"slug\": \"*\", \"is_active\": 1}',	NULL,	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-22 16:51:16'),
(4,	1,	'role.deleted',	NULL,	NULL,	'{\"id\": 9, \"name\": \"All\", \"slug\": \"*\"}',	NULL,	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-22 16:51:16'),
(5,	1,	'role.assigned',	'App\\Models\\User',	3,	NULL,	'{\"role_id\": 5, \"role_slug\": \"editor\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-23 07:13:12'),
(6,	1,	'role.removed',	'App\\Models\\User',	3,	NULL,	'{\"role_id\": 5, \"role_slug\": \"editor\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-23 07:15:13'),
(7,	1,	'user.suspended',	'App\\Models\\User',	5,	'{\"suspended_at\": null, \"suspension_reason\": null}',	'{\"suspended_at\": \"2026-02-23T07:15:26.738707Z\", \"suspension_reason\": \"test\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0\"}',	'2026-02-23 07:15:26'),
(8,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-07 12:11:37'),
(9,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-07 12:11:57'),
(10,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:45:54'),
(11,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	NULL,	'{\"id\": 11, \"name\": \"payment\", \"slug\": \".payment\", \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:53:18'),
(12,	1,	'privilege.attached',	'HasinHayder\\Tyro\\Models\\Role',	4,	NULL,	'{\"privilege_id\": 11, \"privilege_slug\": \".payment\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:53:18'),
(13,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	NULL,	'{\"id\": 11, \"name\": \"payment\", \"slug\": \".payment\", \"roles\": [4], \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:53:18'),
(14,	1,	'privilege.updated',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	'{\"id\": 11, \"name\": \"payment\", \"slug\": \".payment\", \"created_at\": \"2026-04-08T03:53:18.000000Z\", \"updated_at\": \"2026-04-08T03:53:18.000000Z\", \"description\": null}',	'{\"slug\": \"*payment\", \"updated_at\": \"2026-04-08 03:53:33\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:53:33'),
(15,	1,	'privilege.slug_changed',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	'{\"slug\": \".payment\"}',	'{\"slug\": \"*payment\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:53:33'),
(16,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	12,	NULL,	'{\"id\": 12, \"name\": \"Comission\", \"slug\": \"*comission\", \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:21'),
(17,	1,	'privilege.attached',	'HasinHayder\\Tyro\\Models\\Role',	4,	NULL,	'{\"privilege_id\": 12, \"privilege_slug\": \"*comission\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:21'),
(18,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	12,	NULL,	'{\"id\": 12, \"name\": \"Comission\", \"slug\": \"*comission\", \"roles\": [4], \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:21'),
(19,	1,	'privilege.updated',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	'{\"id\": 11, \"name\": \"payment\", \"slug\": \"*payment\", \"created_at\": \"2026-04-08T03:53:18.000000Z\", \"updated_at\": \"2026-04-08T03:53:33.000000Z\", \"description\": null}',	'{\"name\": \"Payment\", \"updated_at\": \"2026-04-08 03:55:35\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:35'),
(20,	1,	'privilege.name_changed',	'HasinHayder\\Tyro\\Models\\Privilege',	11,	'{\"name\": \"payment\"}',	'{\"name\": \"Payment\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:35'),
(21,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:55:55'),
(22,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:56:13'),
(23,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:57:34'),
(24,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:57:55'),
(25,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 03:58:49'),
(26,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 04:39:12'),
(27,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 04:39:22'),
(28,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-08 05:26:18'),
(29,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 10:42:19'),
(30,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 12:28:36'),
(31,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-08 12:28:50'),
(32,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 04:24:41'),
(33,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 04:25:26'),
(34,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 04:25:55'),
(35,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 04:28:31'),
(36,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 04:31:51'),
(37,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 04:42:23'),
(38,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 04:45:38'),
(39,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 04:45:59'),
(40,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 04:57:39'),
(41,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 04:58:14'),
(42,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 05:10:30'),
(43,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"192.168.0.173\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36\"}',	'2026-04-09 05:10:50'),
(44,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 07:40:52'),
(45,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 07:42:45'),
(46,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 11:50:21'),
(47,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 11:50:35'),
(48,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-09 13:21:27'),
(49,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 03:38:23'),
(50,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 03:38:43'),
(51,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 03:38:57'),
(52,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 05:12:38'),
(53,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 05:12:48'),
(54,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:08:54'),
(55,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:11'),
(56,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:21'),
(57,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	13,	NULL,	'{\"id\": 13, \"name\": \"Invoice\", \"slug\": \"*invoice\", \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:47'),
(58,	1,	'privilege.attached',	'HasinHayder\\Tyro\\Models\\Role',	3,	NULL,	'{\"privilege_id\": 13, \"privilege_slug\": \"*invoice\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:47'),
(59,	1,	'privilege.attached',	'HasinHayder\\Tyro\\Models\\Role',	4,	NULL,	'{\"privilege_id\": 13, \"privilege_slug\": \"*invoice\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:47'),
(60,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	13,	NULL,	'{\"id\": 13, \"name\": \"Invoice\", \"slug\": \"*invoice\", \"roles\": [3, 4], \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:25:47'),
(61,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:26:07'),
(62,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-11 10:26:18'),
(63,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-12 10:14:29'),
(64,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 03:17:19'),
(65,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:04:35'),
(66,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:04:55'),
(67,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:05:17'),
(68,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:10:19'),
(69,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:10:42'),
(70,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:11:22'),
(71,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:11:54'),
(72,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:12:48'),
(73,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:13:39'),
(74,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:18:07'),
(75,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:18:34'),
(76,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:20:10'),
(77,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:27:34'),
(78,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:30:10'),
(79,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:36:02'),
(80,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:36:31'),
(81,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:45:43'),
(82,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 04:51:10'),
(83,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-13 05:39:40'),
(84,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 05:47:41'),
(85,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:05:04'),
(86,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:08:17'),
(87,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:09:13'),
(88,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:13:00'),
(89,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:14:20'),
(90,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:27:49'),
(91,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:27:59'),
(92,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:29:09'),
(93,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-13 06:29:17'),
(94,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 03:18:25'),
(95,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 04:00:42'),
(96,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 04:01:08'),
(97,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 05:34:14'),
(98,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"editor@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 05:34:28'),
(99,	5,	'user.logout',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"editor@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 06:12:33'),
(100,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 06:13:58'),
(101,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 07:33:12'),
(102,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 07:33:30'),
(103,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"editor@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 07:43:36'),
(104,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 08:23:24'),
(105,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 08:23:42'),
(106,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 09:41:20'),
(107,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 09:41:32'),
(108,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 09:42:09'),
(109,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 09:42:20'),
(110,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 11:44:23'),
(111,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 11:44:34'),
(112,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 11:47:10'),
(113,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-15 11:47:19'),
(114,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-15 18:00:17'),
(115,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-15 18:15:33'),
(116,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-15 18:15:41'),
(117,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-17 10:15:10'),
(118,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-17 10:15:28'),
(119,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 07:27:42'),
(120,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 08:54:59'),
(121,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 08:55:11'),
(122,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 11:41:46'),
(123,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 11:41:58'),
(124,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 11:42:09'),
(125,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 11:57:15'),
(126,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-18 11:57:25'),
(127,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-18 12:01:46'),
(128,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-18 13:24:20'),
(129,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 03:39:10'),
(130,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 03:39:50'),
(131,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 03:39:59'),
(132,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 03:55:53'),
(133,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 03:56:00'),
(134,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:05:45'),
(135,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:05:54'),
(136,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:10:03'),
(137,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:10:12'),
(138,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:13:53'),
(139,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:14:02'),
(140,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:21:23'),
(141,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:21:32'),
(142,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:22:15'),
(143,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:22:28'),
(144,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:33:57'),
(145,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:34:05'),
(146,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:35:07'),
(147,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:35:19'),
(148,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:46:35'),
(149,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:46:50'),
(150,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:47:01'),
(151,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 04:47:08'),
(152,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 05:01:57'),
(153,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-19 05:02:05'),
(154,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 03:47:01'),
(155,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 03:49:31'),
(156,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 03:49:41'),
(157,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 05:14:30'),
(158,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 05:14:38'),
(159,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:27:58'),
(160,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:28:06'),
(161,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:28:36'),
(162,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:29:39'),
(163,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:44:42'),
(164,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:45:32'),
(165,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:55:52'),
(166,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 06:56:00'),
(167,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0\"}',	'2026-04-20 08:17:54'),
(168,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-20 09:23:40'),
(169,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-21 10:20:00'),
(170,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-22 03:13:21'),
(171,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-22 09:44:43'),
(172,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-23 07:54:34'),
(173,	6,	'user.logout',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-23 08:17:18'),
(174,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-23 08:17:31'),
(175,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 04:07:35'),
(176,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 04:22:42'),
(177,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 04:22:52'),
(178,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 06:13:24'),
(179,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 06:50:59'),
(180,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 06:51:07'),
(181,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 07:11:21'),
(182,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 07:11:30'),
(183,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 07:30:42'),
(184,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 07:30:52'),
(185,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 11:03:35'),
(186,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 11:46:51'),
(187,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 11:47:08'),
(188,	3,	'user.logout',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 11:47:31'),
(189,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 11:47:39'),
(190,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 12:20:36'),
(191,	3,	'user.login',	'App\\Models\\User',	3,	NULL,	'{\"email\": \"consultant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-25 12:20:45'),
(192,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 03:42:07'),
(193,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 03:59:46'),
(194,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:31:43'),
(195,	1,	'user.unsuspended',	'App\\Models\\User',	5,	'{\"suspended_at\": \"2026-02-23 07:15:26\", \"suspension_reason\": \"test\"}',	'{\"suspended_at\": null, \"suspension_reason\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:32:28'),
(196,	1,	'role.created',	'HasinHayder\\Tyro\\Models\\Role',	11,	NULL,	'{\"id\": 11, \"name\": \"Digital Marketing\", \"slug\": \"digital-marketing\", \"is_active\": true}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:33:54'),
(197,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	14,	NULL,	'{\"id\": 14, \"name\": \"Digital Marketing\", \"slug\": \"*digital_marketing\", \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:34:35'),
(198,	1,	'privilege.attached',	'HasinHayder\\Tyro\\Models\\Role',	11,	NULL,	'{\"privilege_id\": 14, \"privilege_slug\": \"*digital_marketing\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:34:35'),
(199,	1,	'privilege.created',	'HasinHayder\\Tyro\\Models\\Privilege',	14,	NULL,	'{\"id\": 14, \"name\": \"Digital Marketing\", \"slug\": \"*digital_marketing\", \"roles\": [11], \"description\": null}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:34:35'),
(200,	1,	'role.assigned',	'App\\Models\\User',	7,	NULL,	'{\"role_id\": 11, \"role_slug\": \"digital-marketing\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:34:57'),
(201,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:35:07'),
(202,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:35:23'),
(203,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:43:58'),
(204,	7,	'user.login',	'App\\Models\\User',	7,	NULL,	'{\"email\": \"digital_marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 04:44:09'),
(205,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 08:14:40'),
(206,	4,	'user.logout',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 08:14:53'),
(207,	7,	'user.login',	'App\\Models\\User',	7,	NULL,	'{\"email\": \"digital_marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 08:15:04'),
(208,	7,	'user.logout',	'App\\Models\\User',	7,	NULL,	'{\"email\": \"digital_marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 08:17:14'),
(209,	7,	'user.login',	'App\\Models\\User',	7,	NULL,	'{\"email\": \"digital_marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-26 08:17:31'),
(210,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 03:49:20'),
(211,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 04:34:26'),
(212,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 04:35:08'),
(213,	2,	'user.logout',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Macintosh; Intel Mac OS X 11_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15\"}',	'2026-04-27 05:17:09'),
(214,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Macintosh; Intel Mac OS X 11_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15\"}',	'2026-04-27 05:20:47'),
(215,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 06:34:47'),
(216,	5,	'user.login',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"editor@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 06:44:13'),
(217,	5,	'user.logout',	'App\\Models\\User',	5,	NULL,	'{\"email\": \"editor@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 07:19:29'),
(218,	6,	'user.login',	'App\\Models\\User',	6,	NULL,	'{\"email\": \"application@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 07:19:40'),
(219,	1,	'user.login',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 09:57:49'),
(220,	1,	'user.logout',	'App\\Models\\User',	1,	NULL,	'{\"email\": \"hello@inoodex.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 11:53:24'),
(221,	2,	'user.login',	'App\\Models\\User',	2,	NULL,	'{\"email\": \"marketing@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-04-27 11:53:37'),
(222,	4,	'user.login',	'App\\Models\\User',	4,	NULL,	'{\"email\": \"accountant@example.com\"}',	'{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\"}',	'2026-05-01 13:57:04');

DROP TABLE IF EXISTS `universities`;
CREATE TABLE `universities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `universities_country_id_foreign` (`country_id`),
  CONSTRAINT `universities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `universities` (`id`, `country_id`, `name`, `short_name`, `website`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(3,	4,	'University of Malta',	NULL,	'https://www.um.edu.mt/',	'info@um.edu.mt',	'+356 2340 2340',	'University of Malta, Msida MSD 2080, Malta',	1,	'2026-04-14 23:36:55',	'2026-04-14 23:36:55'),
(4,	5,	'Universiti Malaya',	NULL,	'https://www.um.edu.my/',	'umcced@um.edu.my',	'+603-2246 3633',	'Level 9, Chancellery Universiti Malaya, Lingkungan Budi, 50603 Kuala Lumpur,',	1,	'2026-04-14 23:51:01',	'2026-04-14 23:51:01'),
(5,	6,	'Moscow State University',	NULL,	'https://msu.ru/en/',	'info@rector.msu.ru',	'+7 (495) 939-10-00',	NULL,	1,	'2026-04-27 04:01:04',	'2026-04-27 04:01:04');

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1,	1,	1,	'2026-02-16 03:14:04',	'2026-02-16 03:14:04'),
(2,	2,	2,	'2026-02-17 12:46:55',	'2026-02-17 12:46:55'),
(3,	3,	3,	'2026-02-17 13:02:10',	'2026-02-17 13:02:10'),
(4,	4,	4,	'2026-02-17 23:03:02',	'2026-02-17 23:03:02'),
(5,	5,	5,	'2026-02-18 02:45:29',	'2026-02-18 02:45:29'),
(6,	6,	6,	'2026-02-18 22:47:11',	'2026-02-18 22:47:11'),
(8,	7,	11,	'2026-04-25 22:34:57',	'2026-04-25 22:34:57');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `basic_salary`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `suspended_at`, `suspension_reason`, `profile_photo_path`, `use_gravatar`, `account_number`, `bank_name`, `bank_branch`, `routing_number`) VALUES
(1,	'Inoodex',	'hello@inoodex.com',	15000.00,	NULL,	'$2y$12$bZ9YAxTXRcahQbLiZ6d8q.cXUkxAbriZ.WtNmnNPrwcOA0PppnPJO',	NULL,	NULL,	NULL,	NULL,	'2026-02-16 03:09:49',	'2026-02-22 04:14:08',	NULL,	NULL,	NULL,	0,	'12345678',	'Islami Bank Bangladesh',	'Banani',	'1234'),
(2,	'Marketing',	'marketing@example.com',	18000.00,	NULL,	'$2y$12$ei9LSrLrIhng1FNUsEZztOnKxDIZ3stVVswIXvKK8Dvphk3pTs5BO',	NULL,	NULL,	NULL,	NULL,	'2026-02-17 12:46:55',	'2026-02-23 02:49:28',	NULL,	NULL,	NULL,	0,	'521654651',	'Islami Bank Bangladesh',	'Banani',	'57868'),
(3,	'Consultant',	'consultant@example.com',	12000.00,	NULL,	'$2y$12$m3ZggFrGDw7maLPG1tU8DuKDOTSWUUeSzEFcW1fSfK7xCHJmwJTVW',	NULL,	NULL,	NULL,	NULL,	'2026-02-17 12:49:33',	'2026-02-22 04:14:08',	NULL,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL),
(4,	'Accountant',	'accountant@example.com',	20000.00,	NULL,	'$2y$12$eF1/DUs.OmbYrUHoLL0Z.uO5HCJSsdaYSknKdu6Sl1tnYF.dhRC.G',	NULL,	NULL,	NULL,	NULL,	'2026-02-17 23:01:58',	'2026-02-22 04:14:08',	NULL,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL),
(5,	'Editor',	'editor@example.com',	12000.00,	NULL,	'$2y$12$cmDPJgBl7B/V8acxTv3Ej.15/p9DkpiY/G/cSFL9j2iwTkKmbbjXW',	NULL,	NULL,	NULL,	NULL,	'2026-02-18 02:45:29',	'2026-04-25 22:32:28',	NULL,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL),
(6,	'Application',	'application@example.com',	15000.00,	NULL,	'$2y$12$vZrAn3nHsyH1FOtRvuBW4elSdagHiBA4YnoI2.QdnHkYqeI6MmL1e',	NULL,	NULL,	NULL,	NULL,	'2026-02-18 22:39:12',	'2026-02-22 04:14:08',	NULL,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL),
(7,	'Digital Marketing',	'digital_marketing@example.com',	0.00,	NULL,	'$2y$12$fo/Cfa7sIDcLK6ytXcZD.uCVPwMxTVYzEGuUecSP.RctWeKBlIkqi',	NULL,	NULL,	NULL,	NULL,	'2026-04-25 22:33:19',	'2026-04-25 22:33:19',	NULL,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `vfs_checklist_templates`;
CREATE TABLE `vfs_checklist_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vfs_checklist_templates_country_id_foreign` (`country_id`),
  CONSTRAINT `vfs_checklist_templates_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vfs_checklist_templates` (`id`, `item_name`, `country_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1,	'VFS Appointment',	5,	0,	1,	'2026-04-21 05:24:49',	'2026-04-22 03:49:26'),
(2,	'Visa Application',	4,	1,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(3,	'Photo 35X45',	5,	2,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(4,	'Passport',	4,	3,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(5,	'Academic Certificates (Education Board and ministry attestation)',	5,	4,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(6,	'Academic Transcripts (Education Board and ministry attestation)',	4,	5,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(7,	'English Proficiency (If any)',	5,	6,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(8,	'CV',	4,	7,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(9,	'Motivation Letter',	5,	8,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(10,	'Final Offer Letter and college others documents',	4,	9,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(11,	'Accommodation',	5,	10,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(12,	'Birth Certificate (Notarize and attested)',	4,	11,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(13,	'Insurance',	4,	12,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(14,	'Flight Booking',	5,	13,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(15,	'Student Bank ATM Card',	4,	14,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(16,	'Sponsor NID (Translation and notarize)',	4,	15,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(17,	'Applicant NID (Translation and notarize)',	4,	16,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(18,	'Sponsor Income Source (Trade License or Job Certificate) (Translation and notarize)',	5,	17,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(19,	'TIN certificate',	5,	18,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(20,	'TAX certificate 2 years',	5,	19,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(21,	'Bank Statement',	5,	20,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(22,	'Sponsor Bank ATM Card',	4,	21,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(23,	'Applicants ATM Card',	4,	22,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(24,	'Bank Account Cheque Book copy',	5,	23,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(25,	'Deposit Slip (If possible)',	5,	24,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34'),
(26,	'Financial Declaration Affidavit',	4,	25,	1,	'2026-04-21 05:24:49',	'2026-04-21 05:25:34');

DROP TABLE IF EXISTS `vfs_checklists`;
CREATE TABLE `vfs_checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint unsigned NOT NULL,
  `checklist_item` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `checked_by` bigint unsigned DEFAULT NULL,
  `checked_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vfs_checklists_application_id_foreign` (`application_id`),
  KEY `vfs_checklists_checked_by_foreign` (`checked_by`),
  CONSTRAINT `vfs_checklists_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vfs_checklists_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vfs_checklists` (`id`, `application_id`, `checklist_item`, `is_checked`, `checked_by`, `checked_at`, `notes`, `created_at`, `updated_at`) VALUES
(133,	12,	'VFS Appointment',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(134,	12,	'Visa Application',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(135,	12,	'Photo 35X45',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(136,	12,	'Passport',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(137,	12,	'Academic Certificates (Education Board and ministry attestation)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(138,	12,	'Academic Transcripts (Education Board and ministry attestation)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(139,	12,	'English Proficiency (If any)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(140,	12,	'CV',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(141,	12,	'Motivation Letter',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(142,	12,	'Final Offer Letter and college others documents',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(143,	12,	'Accommodation',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(144,	12,	'Birth Certificate (Notarize and attested)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(145,	12,	'Insurance',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(146,	12,	'Flight Booking',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(147,	12,	'Student Bank ATM Card',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(148,	12,	'Sponsor NID (Translation and notarize)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(149,	12,	'Applicant NID (Translation and notarize)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(150,	12,	'Sponsor Income Source (Trade License or Job Certificate) (Translation and notarize)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(151,	12,	'TIN certificate',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(152,	12,	'TAX certificate 2 years',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(153,	12,	'Bank Statement',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(154,	12,	'Sponsor Bank ATM Card',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(155,	12,	'Applicants ATM Card',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(156,	12,	'Bank Account Cheque Book copy',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(157,	12,	'Deposit Slip (If possible)',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13'),
(158,	12,	'Financial Declaration Affidavit',	0,	NULL,	NULL,	NULL,	'2026-04-27 05:29:13',	'2026-04-27 05:29:13');

-- 2026-05-01 18:18:58 UTC
