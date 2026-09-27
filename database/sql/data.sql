-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2024 at 08:01 PM
-- Server version: 9.0.1
-- PHP Version: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `market`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `google2fa_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Disabled, 1: Active',
  `google2fa_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 : Unread 1: Read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` bigint UNSIGNED NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `alias`, `position`, `size`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'head_code', 'Head Code', NULL, '<!-- Head Code -->', 0, '2024-04-16 22:20:58', '2024-04-17 10:44:13'),
(2, 'home_page_top', 'Home Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-05-08 16:17:59'),
(3, 'home_page_center', 'Home Page (Center)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(4, 'home_page_bottom', 'Home Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(5, 'item_page_top', 'Item Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(6, 'item_page_center', 'Item Page (Center)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(7, 'item_page_bottom', 'Item Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(8, 'category_page_top', 'Category Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(9, 'category_page_bottom', 'Category Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(10, 'search_page_top', 'Search Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(11, 'search_page_bottom', 'Search Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(12, 'blog_page_top', 'Blog Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(13, 'blog_page_bottom', 'Blog Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(14, 'blog_article_page_top', 'Blog Article Page (Top)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56'),
(15, 'blog_article_page_bottom', 'Blog Article Page (Bottom)', NULL, NULL, 0, '2024-04-16 22:20:58', '2024-04-16 17:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `author_taxes`
--

CREATE TABLE `author_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int UNSIGNED NOT NULL,
  `countries` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_id` bigint UNSIGNED DEFAULT NULL,
  `membership_years` bigint DEFAULT NULL,
  `is_permanent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `name`, `alias`, `title`, `image`, `country`, `level_id`, `membership_years`, `is_permanent`, `created_at`, `updated_at`) VALUES
(1, 'Verified Account', 'verified_account', 'The account has been verified by our team', 'images/badges/E7KHY7JN6k5e2uY_1704495382.svg', NULL, NULL, NULL, 1, '2024-01-05 17:56:22', '2024-01-05 19:07:17'),
(2, 'Exclusive Author', 'exclusive_author', 'He sells exclusive items', 'images/badges/Rvd9rlxmiXiv9rR_1704495444.svg', NULL, NULL, NULL, 1, '2024-01-05 17:57:24', '2024-01-05 17:57:24'),
(3, 'Trend Master', 'trend_master', 'He had an item that was trending', 'images/badges/Npo0RQSbyvl4bDs_1704495462.svg', NULL, NULL, NULL, 1, '2024-01-05 17:57:42', '2024-03-05 17:16:12'),
(4, 'Featured Author', 'featured_author', 'He had been a featured author', 'images/badges/v8pT8eGx7xaNvvZ_1718672138.png', NULL, NULL, NULL, 1, '2024-06-17 17:46:26', '2024-06-18 15:10:59'),
(5, 'Featured Item', 'featured_item', 'He had a featured item', 'images/badges/yhDbZKZyQ7bSoVj_1718672600.png', NULL, NULL, NULL, 1, '2024-06-17 18:03:20', '2024-06-18 15:11:16'),
(6, 'Referrer', 'referrer', 'He referred one or more persons', 'images/badges/BH7JSFkqO6YyCW5_1704495483.svg', NULL, NULL, NULL, 1, '2024-01-05 17:58:03', '2024-01-05 17:58:03'),
(7, 'Discount Master', 'discount_master', 'Had one or more discounted items', 'images/badges/bD2nnZB4AiO5kpl_1710699376.svg', NULL, NULL, NULL, 1, '2024-03-17 18:16:16', '2024-03-17 18:16:16'),
(8, 'Premiumer', 'premiumer', 'Had an item in premium subscription', 'images/badges/nIUU3A7yQwsDDgW_1726082927.png', NULL, NULL, NULL, 1, '2024-01-05 17:56:22', '2024-09-11 18:28:47'),
(9, 'Premium Membership', 'premium_membership', 'Has a premium subscription', 'images/badges/Z6Hi3LF9f7Yc8Ip_1726083022.png', NULL, NULL, NULL, 1, '2024-01-05 17:56:22', '2024-09-11 18:30:22'),
(10, 'Team Member', 'team_member', 'A member of the Marketbob Team', 'images/badges/7ZGgVZHjitn9rfG_1713747386.png', NULL, NULL, NULL, 0, '2024-01-05 18:00:02', '2024-04-21 17:56:26'),
(11, 'Copyright Reporter', 'copyright_reporter', 'He reported copyrighted content', 'images/badges/85zqqobXEIzM3gj_1704495621.svg', NULL, NULL, NULL, 0, '2024-01-05 18:00:21', '2024-01-05 18:00:21'),
(12, 'Bug Reporter', 'bug_reporter', 'He reported one or more bugs', 'images/badges/KM0VPjosjrQ25Ca_1704495638.svg', NULL, NULL, NULL, 0, '2024-01-05 18:00:38', '2024-01-05 18:00:38'),
(13, '1 Year Membership', 'membership_years', 'He joined a year ago', 'images/badges/pIOzUNrNs1XlpNp_1704497178.svg', NULL, NULL, 1, 0, '2024-01-05 18:26:18', '2024-01-05 18:26:18'),
(14, '2 Years Membership', 'membership_years', 'He joined two years ago', 'images/badges/ytVRbSL1vXhBv9L_1704497229.svg', NULL, NULL, 2, 0, '2024-01-05 18:27:09', '2024-01-05 18:27:09'),
(15, '3 Years Membership', 'membership_years', 'He joined 3 years ago', 'images/badges/5AysSo7UpehBHpb_1704497275.svg', NULL, NULL, 3, 0, '2024-01-05 18:27:55', '2024-01-05 18:27:55'),
(16, '4 Years Membership', 'membership_years', 'He joined 4 years ago', 'images/badges/oR9DcBF5s85eyNr_1704497339.svg', NULL, NULL, 4, 0, '2024-01-05 18:28:59', '2024-01-05 18:28:59'),
(17, '5 Years Membership', 'membership_years', 'He joined 5 years ago', 'images/badges/8InoRCMwijRwQ7Q_1704497600.svg', NULL, NULL, 5, 0, '2024-01-05 18:33:20', '2024-01-05 18:33:20'),
(18, '6 Years Membership', 'membership_years', 'He joined 6 years ago', 'images/badges/rwrwhsM5URPbeuT_1704497816.svg', NULL, NULL, 6, 0, '2024-01-05 18:36:56', '2024-01-05 18:36:56'),
(19, '7 Years Membership', 'membership_years', 'He joined 7 years ago', 'images/badges/hNmgIjWpaxVMXFy_1704497862.svg', NULL, NULL, 7, 0, '2024-01-05 18:37:42', '2024-01-05 18:37:42'),
(20, '8 Years Membership', 'membership_years', 'He joined 8 years ago', 'images/badges/uzd7teVpI8Hyqvz_1704497884.svg', NULL, NULL, 8, 0, '2024-01-05 18:38:04', '2024-01-05 18:38:04'),
(21, '9 Years Membership', 'membership_years', 'He joined 9 years ago', 'images/badges/Rs8v55zNY6wIHyi_1704497919.svg', NULL, NULL, 9, 0, '2024-01-05 18:38:39', '2024-01-05 18:38:39'),
(22, '+10 Years Membership', 'membership_years', 'Membership for 10 years or more', 'images/badges/1SZKQ8jW7S9LcPM_1704498197.svg', NULL, NULL, 10, 0, '2024-01-05 18:43:17', '2024-03-06 05:05:11'),
(23, 'New Author', 'author_level', NULL, 'images/badges/oYY2O5SKEQPbwDk_1713448047.svg', NULL, 1, NULL, 0, '2024-01-05 18:46:46', '2024-04-18 06:49:22'),
(24, 'Author level 1', 'author_level', 'He made $100 or more in sales', 'images/badges/VrF5shysCjcJ4is_1704498406.svg', NULL, 2, NULL, 0, '2024-01-05 18:46:46', '2024-04-21 18:08:12'),
(25, 'Author level 2', 'author_level', 'He made $1,000 or more in sales', 'images/badges/2i30DFThWdX6gpC_1704498444.svg', NULL, 3, NULL, 0, '2024-01-05 18:47:24', '2024-04-21 18:08:22'),
(26, 'Author level 3', 'author_level', 'He made $5,000 or more in sales', 'images/badges/kY1rtNWKDdvg13T_1704498476.svg', NULL, 4, NULL, 0, '2024-01-05 18:47:56', '2024-04-21 18:08:25'),
(27, 'Author level 4', 'author_level', 'He made $10,000 or more in sales', 'images/badges/9Kkep2QwIzke4jk_1704498512.svg', NULL, 5, NULL, 0, '2024-01-05 18:48:32', '2024-04-21 18:08:28'),
(28, 'Author level 5', 'author_level', 'He made $25,000 or more in sales', 'images/badges/5OiihmkAAf9gz1P_1704498535.svg', NULL, 6, NULL, 0, '2024-01-05 18:48:55', '2024-04-21 18:08:32'),
(29, 'Author level 6', 'author_level', 'He made $45,000 or more in sales', 'images/badges/qoh5TyETEBJvRiQ_1704498561.svg', NULL, 7, NULL, 0, '2024-01-05 18:49:21', '2024-04-21 18:08:35'),
(30, 'Author level 7', 'author_level', 'He made $75,000 or more in sales', 'images/badges/kpCkIEG5nGLLXGc_1704498582.svg', NULL, 8, NULL, 0, '2024-01-05 18:49:42', '2024-04-21 18:08:38'),
(31, 'Author level 8', 'author_level', 'He made $250,000 or more in sales', 'images/badges/lBvmt2Iz4RI2sRM_1704498609.svg', NULL, 9, NULL, 0, '2024-01-05 18:50:09', '2024-04-21 18:08:42'),
(32, 'Author level 9', 'author_level', 'He made $500,000 or more in sales', 'images/badges/GtrlNV3clMHCkaF_1704498640.svg', NULL, 10, NULL, 0, '2024-01-05 18:50:40', '2024-04-21 18:08:46'),
(33, 'Top Rated Author', 'author_level', 'He made $1,000,000 or more in sales', 'images/badges/Llkq7gzoAfD6WaY_1704498679.svg', NULL, 11, NULL, 0, '2024-01-05 18:51:19', '2024-04-21 18:08:50'),
(34, 'From Unknown Location', 'country', NULL, 'images/badges/sEquAHS64UJ3Cn9_1709695221.svg', NULL, NULL, NULL, 1, '2024-03-05 17:20:21', '2024-03-05 17:20:21'),
(35, 'From United states', 'country', NULL, 'images/badges/Aw0omnT8Nbs6a8q_1709736946.svg', 'US', NULL, NULL, 0, '2024-03-06 04:55:46', '2024-03-06 04:55:46'),
(36, 'From United Kingdom', 'country', NULL, 'images/badges/e2GnccbKyq19uSX_1709736969.svg', 'GB', NULL, NULL, 0, '2024-03-06 04:56:09', '2024-03-06 04:56:09'),
(37, 'From India', 'country', NULL, 'images/badges/fh5OgwhD7ps9JM1_1709736988.svg', 'IN', NULL, NULL, 0, '2024-03-06 04:56:28', '2024-03-06 04:56:28'),
(38, 'From France', 'country', NULL, 'images/badges/2RExURnmkfsolPT_1709737011.svg', 'FR', NULL, NULL, 0, '2024-03-06 04:56:51', '2024-03-06 04:56:51'),
(39, 'From Germany', 'country', NULL, 'images/badges/uZy5nPQktuxT8go_1709737035.svg', 'DE', NULL, NULL, 0, '2024-03-06 04:57:15', '2024-03-06 04:57:15'),
(40, 'From Brazil', 'country', NULL, 'images/badges/6wrcUP3Gc9H0MtB_1709737054.svg', 'BR', NULL, NULL, 0, '2024-03-06 04:57:34', '2024-03-06 04:57:34'),
(41, 'From Mexico', 'country', NULL, 'images/badges/AuoKt2dvcMkVURl_1709737076.svg', 'MX', NULL, NULL, 0, '2024-03-06 04:57:56', '2024-03-06 04:57:56'),
(42, 'From Spain', 'country', NULL, 'images/badges/WmRZ0OsLPFc368K_1709737095.svg', 'ES', NULL, NULL, 0, '2024-03-06 04:58:15', '2024-03-06 04:58:15'),
(43, 'From Turkey', 'country', NULL, 'images/badges/TylGiX2ZKu52wUK_1709737119.svg', 'TR', NULL, NULL, 0, '2024-03-06 04:58:39', '2024-03-06 04:58:39'),
(44, 'From Japan', 'country', NULL, 'images/badges/alxb6OTP5OVR8GO_1709737143.svg', 'JP', NULL, NULL, 0, '2024-03-06 04:59:03', '2024-03-06 04:59:03'),
(45, 'From Bangladesh', 'country', NULL, 'images/badges/Ty6RbswypvU8zfb_1709737158.svg', 'BD', NULL, NULL, 0, '2024-03-06 04:59:18', '2024-03-06 04:59:18'),
(46, 'From Nigeria', 'country', NULL, 'images/badges/KEwIbalTLDC8cHv_1709737176.svg', 'NG', NULL, NULL, 0, '2024-03-06 04:59:36', '2024-03-06 04:59:36'),
(47, 'From Morocco', 'country', NULL, 'images/badges/9PfDbCUAKXhqeIk_1709737195.svg', 'MA', NULL, NULL, 0, '2024-03-06 04:59:55', '2024-03-06 04:59:55'),
(48, 'From Egypt', 'country', NULL, 'images/badges/WJaCHU8KlFvUOOS_1709737211.svg', 'EG', NULL, NULL, 0, '2024-03-06 05:00:11', '2024-03-06 05:00:11'),
(49, 'From United Arab Emirates', 'country', NULL, 'images/badges/CWRQpogNIIC7XUY_1709737244.svg', 'UA', NULL, NULL, 0, '2024-03-06 05:00:44', '2024-03-06 05:00:44'),
(50, 'From Saudi Arabia', 'country', NULL, 'images/badges/82XoBdlG62xMb4z_1709737266.svg', 'SA', NULL, NULL, 0, '2024-03-06 05:01:06', '2024-03-06 05:01:06'),
(51, 'From Pakistan', 'country', NULL, 'images/badges/ev2m6i0itmE3fTP_1709737286.svg', 'PK', NULL, NULL, 0, '2024-03-06 05:01:26', '2024-03-06 05:01:26'),
(52, 'From Indonesia', 'country', NULL, 'images/badges/mYM7LUIVzNlXZz8_1709737308.svg', 'Indonesia', NULL, NULL, 0, '2024-03-06 05:01:48', '2024-03-06 05:01:48'),
(53, 'From Australia', 'country', NULL, 'images/badges/eMjURJuLeqMYFau_1709737324.svg', 'AU', NULL, NULL, 0, '2024-03-06 05:02:04', '2024-03-06 05:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `blog_articles`
--

CREATE TABLE `blog_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_category_id` bigint UNSIGNED NOT NULL,
  `views` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `blog_article_id` bigint UNSIGNED NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bottom_nav_links`
--

CREATE TABLE `bottom_nav_links` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Internal 2:External',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bottom_nav_links`
--

INSERT INTO `bottom_nav_links` (`id`, `name`, `link`, `link_type`, `parent_id`, `order`, `created_at`, `updated_at`) VALUES
(2, 'Code', '/', 1, NULL, 2, '2023-12-01 13:01:53', '2023-12-15 16:17:09'),
(3, 'JavaScript', '/categories/code/javascript', 1, 2, 1, '2023-12-01 13:02:10', '2023-12-15 16:17:25'),
(4, 'PHP Scripts', '/categories/code/php-scripts', 1, 2, 2, '2023-12-01 13:30:23', '2023-12-15 16:17:34'),
(5, 'CSS', '/categories/code/css', 1, 2, 3, '2023-12-01 13:30:48', '2023-12-15 16:17:41'),
(6, 'HTML5', '/categories/code/html5', 1, 2, 4, '2023-12-01 13:31:00', '2023-12-15 16:17:53'),
(7, '.NET', '/categories/code/net', 1, 2, 5, '2023-12-01 13:31:18', '2023-12-15 16:17:56'),
(8, 'Themes', '/', 1, NULL, 1, '2023-12-01 13:32:06', '2023-12-14 16:11:23'),
(9, 'WordPress', '/categories/themes/wordpress', 1, 8, 1, '2023-12-01 13:33:48', '2023-12-15 12:17:57'),
(10, 'HTML/CSS', '/categories/themes/html-css', 1, 8, 2, '2023-12-01 13:34:06', '2024-05-03 21:06:25'),
(11, 'Shopify', '/categories/themes/shopify', 1, 8, 4, '2023-12-02 15:00:52', '2023-12-15 12:18:16'),
(12, 'Bootstrap', '/categories/themes/bootstrap', 1, 8, 3, '2023-12-02 15:01:16', '2023-12-15 12:18:07'),
(13, 'Blogger', '/categories/themes/blogger', 1, 8, 5, '2023-12-02 15:01:35', '2023-12-15 12:18:27'),
(14, 'Graphics', '/', 1, NULL, 6, '2023-12-02 15:02:16', '2024-09-05 10:13:43'),
(15, 'Illustrations', '/categories/graphics/illustrations', 1, 14, 4, '2023-12-02 15:02:31', '2023-12-15 12:22:19'),
(16, 'Icons', '/categories/graphics/icons', 1, 14, 1, '2023-12-02 15:02:43', '2023-12-15 12:21:47'),
(17, 'Objects', '/categories/graphics/objects', 1, 14, 2, '2023-12-02 15:03:06', '2023-12-15 12:21:59'),
(18, 'Patterns', '/categories/graphics/patterns', 1, 14, 3, '2023-12-02 15:03:17', '2023-12-15 12:22:10'),
(19, 'Web Elements', '/categories/graphics/web-elements', 1, 14, 5, '2023-12-02 15:03:31', '2023-12-15 12:22:31'),
(20, 'Mobile Apps', '/', 1, NULL, 7, '2023-12-02 15:04:03', '2024-09-05 10:13:43'),
(21, 'Android', '/categories/mobile-apps/android', 1, 20, 1, '2023-12-02 15:04:22', '2023-12-15 12:22:51'),
(22, 'IOS', '/categories/mobile-apps/ios', 1, 20, 2, '2023-12-02 15:04:27', '2023-12-15 12:22:59'),
(23, 'Native Web', '/categories/mobile-apps/native-web', 1, 20, 3, '2023-12-02 15:04:41', '2023-12-15 12:23:08'),
(24, 'Unity', '/categories/mobile-apps/unity', 1, 20, 4, '2023-12-02 15:04:59', '2023-12-15 12:23:19'),
(25, 'Plugins', '/', 1, NULL, 5, '2023-12-02 15:06:09', '2024-09-05 10:13:43'),
(26, 'WordPress Plugins', '/categories/plugins/wordpress-plugins', 1, 25, 1, '2023-12-02 15:06:29', '2023-12-15 12:20:24'),
(27, 'Magento Extensions', '/categories/plugins/magento-extensions', 1, 25, 2, '2023-12-02 15:06:52', '2023-12-15 12:20:33'),
(28, 'OpenCart', '/categories/plugins/opencart', 1, 25, 3, '2023-12-02 15:07:10', '2023-12-15 12:20:46'),
(29, 'Joomla', '/categories/plugins/joomla', 1, 25, 4, '2023-12-02 15:07:44', '2023-12-15 12:20:58'),
(30, 'Drupal', '/categories/plugins/drupal', 1, 25, 5, '2023-12-02 15:08:01', '2023-12-15 12:21:09'),
(31, 'Video', '/categories/video', 1, NULL, 3, '2024-09-05 10:09:47', '2024-09-05 10:13:35'),
(32, 'Effects', '/categories/video/effects', 1, 31, 1, '2024-09-05 10:10:29', '2024-09-05 10:12:56'),
(33, 'Graphics', '/categories/video/graphics', 1, 31, 2, '2024-09-05 10:10:42', '2024-09-05 10:12:41'),
(34, 'Audio', '/categories/audio', 1, NULL, 4, '2024-09-05 10:10:59', '2024-09-05 10:13:43'),
(35, 'Music', '/categories/audio/music', 1, 34, 1, '2024-09-05 10:11:53', '2024-09-05 10:11:55'),
(36, 'Sound Effects', '/categories/audio/sound-effects', 1, 34, 2, '2024-09-05 10:12:17', '2024-09-05 10:12:20');

-- --------------------------------------------------------

--
-- Table structure for table `buyer_taxes`
--

CREATE TABLE `buyer_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int UNSIGNED NOT NULL,
  `countries` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `captcha_providers`
--

CREATE TABLE `captcha_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:Disabled 1:Enabled',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:No 1:Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `captcha_providers`
--

INSERT INTO `captcha_providers` (`id`, `name`, `alias`, `logo`, `settings`, `instructions`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Google reCAPTCHA', 'google_recaptcha', 'images/captcha-providers/google-recaptcha.png', '{\"site_key\":null,\"secret_key\":null}', NULL, 0, 1, '2024-06-29 19:15:34', '2024-12-07 13:30:21'),
(2, 'hCaptcha', 'hcaptcha', 'images/captcha-providers/hcaptcha.png', '{\"site_key\":null,\"secret_key\":null}', NULL, 0, 0, '2024-06-29 19:15:34', '2024-06-29 17:01:25'),
(3, 'Cloudflare Turnstile', 'cloudflare_turnstile', 'images/captcha-providers/cloudflare-turnstile.png', '{\"site_key\":null,\"secret_key\":null}', NULL, 0, 0, '2024-06-29 19:15:34', '2024-07-02 19:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `license_type` tinyint NOT NULL DEFAULT '1' COMMENT '1:Regular 2:Extended',
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `support_period_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regular_buyer_fee` double DEFAULT '0',
  `extended_buyer_fee` double DEFAULT '0',
  `file_type` tinyint(1) DEFAULT NULL COMMENT '1:File With Preview Image 2:File With Video Preview 3:File With Audio Preview',
  `thumbnail_width` bigint UNSIGNED NOT NULL DEFAULT '120',
  `thumbnail_height` bigint UNSIGNED NOT NULL DEFAULT '120',
  `preview_image_width` bigint UNSIGNED DEFAULT NULL,
  `preview_image_height` bigint UNSIGNED DEFAULT NULL,
  `maximum_screenshots` bigint UNSIGNED DEFAULT NULL,
  `main_file_types` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zip,rar,pdf',
  `max_preview_file_size` bigint UNSIGNED NOT NULL DEFAULT '10485760',
  `views` bigint NOT NULL DEFAULT '0',
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `title`, `description`, `regular_buyer_fee`, `extended_buyer_fee`, `file_type`, `thumbnail_width`, `thumbnail_height`, `preview_image_width`, `preview_image_height`, `maximum_screenshots`, `main_file_types`, `max_preview_file_size`, `views`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'Themes', 'themes', NULL, NULL, 5, 25, 1, 120, 120, 1200, 610, 10, 'zip,rar', 5242880, 15, 1, '2024-03-05 18:46:55', '2024-12-04 19:07:08'),
(2, 'Code', 'code', 'Premium Codes PHP Scripts, Javascript, HTML5', NULL, 10, 25, 1, 120, 120, 1200, 610, 10, 'zip,rar', 5242880, 18, 2, '2024-03-05 18:47:11', '2024-12-04 18:54:21'),
(3, 'Plugins', 'plugins', NULL, NULL, 4, 20, 1, 120, 120, 1200, 610, 10, 'zip,rar', 5242880, 11, 5, '2024-03-05 18:47:51', '2024-12-04 18:54:21'),
(4, 'Graphics', 'graphics', NULL, NULL, 3, 10, 1, 120, 120, 1200, 600, 10, 'zip,rar,jpeg,jpg,png,pdf,psd', 5242880, 11, 6, '2024-03-05 18:48:03', '2024-12-04 18:54:21'),
(5, 'Mobile Apps', 'mobile-apps', NULL, NULL, 25, 50, 1, 120, 120, 1200, 610, 10, 'zip,rar,apk,ipa', 5242880, 15, 7, '2024-03-05 18:48:15', '2024-12-04 18:54:21'),
(7, 'Video', 'video', NULL, NULL, 3, 5, 2, 120, 120, 1200, 610, NULL, 'zip,rar,mp4,webm', 10485760, 7, 3, '2024-09-05 09:06:02', '2024-12-04 18:54:21'),
(8, 'Audio', 'audio', NULL, NULL, 3, 5, 3, 120, 120, NULL, NULL, NULL, 'zip,rar,mp3,wav', 10485760, 7, 4, '2024-09-05 09:06:15', '2024-12-04 18:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `category_options`
--

CREATE TABLE `category_options` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `type` tinyint NOT NULL COMMENT '1:Single Select 2:Multiple Select',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_options`
--

INSERT INTO `category_options` (`id`, `category_id`, `type`, `name`, `options`, `is_required`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'High Resolution', '[\"Yes\",\"No\"]', 1, 7, '2024-03-05 19:58:13', '2024-05-06 20:07:48'),
(2, 1, 2, 'Compatible Browsers', '[\"Firefox\",\"Chrome\",\"Safari\",\"Opera\",\"Edge\"]', 1, 8, '2024-03-05 20:05:46', '2024-05-06 20:07:57'),
(3, 1, 2, 'Files Included', '[\"HTML Files\",\"JavaScript\",\"CSS\",\"XML\",\"LESS\",\"Sass\",\"PSD\",\"PHP\",\"SQL\"]', 1, 9, '2024-03-05 20:04:28', '2024-05-06 20:07:57'),
(4, 1, 2, 'Frameworks', '[\"React\",\"Vue\",\"Angular\",\"Next JS\",\"Wordpress\"]', 1, 10, '2024-03-05 20:07:13', '2024-05-06 20:07:57'),
(6, 2, 1, 'High Resolution', '[\"Yes\",\"No\"]', 1, 11, '2024-03-05 18:58:13', '2024-05-06 20:07:57'),
(7, 2, 2, 'Compatible Browsers', '[\"Firefox\",\"Chrome\",\"Safari\",\"Opera\",\"Edge\"]', 1, 12, '2024-03-05 19:05:46', '2024-05-06 20:07:53'),
(8, 2, 2, 'Files Included', '[\"HTML Files\",\"JavaScript\",\"CSS\",\"XML\",\"LESS\",\"Sass\",\"PSD\",\"PHP\",\"SQL\"]', 1, 13, '2024-03-05 19:04:28', '2024-05-06 20:07:48'),
(9, 2, 2, 'Frameworks', '[\"React\",\"Vue\",\"Angular\",\"Next JS\",\"Laravel\",\"Codeigniter\",\"Cake PHP\",\"Yii\"]', 1, 14, '2024-03-05 19:07:13', '2024-05-06 20:07:48'),
(10, 3, 1, 'High Resolution', '[\"Yes\",\"No\"]', 1, 1, '2024-03-05 18:58:13', '2024-05-06 20:07:48'),
(11, 3, 2, 'Compatible Browsers', '[\"Firefox\",\"Chrome\",\"Safari\",\"Opera\",\"Edge\"]', 1, 2, '2024-03-05 19:05:46', '2024-05-06 20:07:48'),
(12, 3, 2, 'Files Included', '[\"HTML Files\",\"JavaScript\",\"CSS\",\"XML\",\"LESS\",\"Sass\",\"PSD\",\"PHP\",\"SQL\"]', 1, 3, '2024-03-05 19:04:28', '2024-05-06 20:07:48'),
(13, 4, 1, 'High Resolution', '[\"Yes\",\"No\"]', 1, 4, '2024-03-05 18:58:13', '2024-05-06 20:07:48'),
(14, 4, 2, 'Files Included', '[\"PSD Files\",\"JPEG\",\"PNG\",\"SVG\",\"WebP\"]', 1, 5, '2024-03-05 20:12:17', '2024-05-06 20:07:48'),
(15, 5, 1, 'High Resolution', '[\"Yes\",\"No\"]', 1, 6, '2024-03-05 20:12:49', '2024-05-06 20:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `category_reviewer`
--

CREATE TABLE `category_reviewer` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `reviewer_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `editor_images`
--

CREATE TABLE `editor_images` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:Disabled 1:Enabled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `alias`, `logo`, `settings`, `instructions`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Google Analytics 4', 'google_analytics', 'images/extensions/google-analytics.png', '{\"measurement_id\":null}', '<ul class=\"mb-0\"> \n<li>Enter google analytics 4 measurement ID, like <strong>G-12345ABC</strong></li> \n</ul>', 0, '2022-02-23 19:40:12', '2023-08-02 15:31:49'),
(2, 'Tawk.to', 'tawk_to', 'images/extensions/tawk-to.png', '{\"api_key\":null}', '<ul class=\"mb-0\"> \r\n<li>https://tawk.to/chat/<strong>API-KEY</strong></li> \r\n</ul>', 0, '2022-02-23 19:40:12', '2023-08-02 15:33:08'),
(3, 'Trustip', 'trustip', 'images/extensions/trustip.png', '{\"api_key\":null}', '<ul class=\"mb-0\"> \r\n<li class=\"mb-2\">Trustip is used to block people who uses VPN, Proxy, etc from registering or purchasing from the marketplace.</li>\r\n<li>Get your api key from:\r\n<a href=\"https://trustip.io\" traget=\"_blank\">https://trustip.io</a>\r\n</li> \r\n</ul>', 0, '2022-02-23 19:40:12', '2024-11-10 09:17:00');

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `title`, `body`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'What is Lorem Ipsum?', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 1, '2022-07-16 23:58:31', '2024-03-05 20:42:43'),
(2, 'Why do we use it?', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', 2, '2022-07-16 23:58:58', '2024-03-05 20:40:41'),
(3, 'Where does it come from?', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', 3, '2022-07-16 23:59:17', '2024-03-05 20:40:45'),
(4, 'Where can I get some?', '<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 4, '2022-07-16 23:59:33', '2024-03-05 20:40:48'),
(5, 'Essential Lorem Ipsum a placeholder odyssey?', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>', 5, '2022-07-16 23:58:31', '2024-03-05 20:43:05'),
(6, 'The Lorem Ipsum Chronicles?', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', 6, '2022-07-16 23:58:58', '2024-03-05 20:42:06'),
(7, 'Lorem Ipsum Unmasked?', '<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', 7, '2022-07-16 23:59:17', '2024-03-05 20:42:31'),
(8, 'Mastering the Art of Lorem Ipsum?', '<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 8, '2022-07-16 23:59:33', '2024-03-05 20:42:39');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` bigint UNSIGNED NOT NULL,
  `follower_id` bigint UNSIGNED NOT NULL,
  `following_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `footer_links`
--

CREATE TABLE `footer_links` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Internal 2:External',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_links`
--

INSERT INTO `footer_links` (`id`, `name`, `link`, `link_type`, `parent_id`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Company', '/page-example', 1, NULL, 1, '2023-02-05 15:20:43', '2024-05-03 21:06:33'),
(2, 'About Us', '/page-example', 1, 1, 1, '2023-02-05 15:21:04', '2024-05-03 21:10:00'),
(3, 'Careers', '/page-example', 1, 1, 2, '2023-02-05 15:21:21', '2023-02-05 15:32:58'),
(4, 'Legal', '/page-example', 1, NULL, 2, '2023-02-05 15:21:53', '2023-02-05 15:33:43'),
(5, 'Privacy policy', '/privacy-policy', 1, 4, 1, '2023-02-05 15:22:03', '2023-02-10 18:47:39'),
(6, 'Terms of use', '/terms-of-use', 1, 4, 2, '2023-02-05 15:22:16', '2023-02-10 18:47:48'),
(7, 'Copyright Policy', '/page-example', 1, 4, 4, '2023-02-05 15:22:27', '2023-02-05 15:34:26'),
(8, 'Contact Us', '/page-example', 1, 1, 3, '2023-02-05 15:22:53', '2023-02-05 15:33:09'),
(10, 'Press Room', '/page-example', 1, 1, 4, '2023-02-05 15:33:25', '2023-02-05 15:33:33'),
(11, 'Cookies Policy', '/page-example', 1, 4, 3, '2023-02-05 15:34:06', '2023-02-05 15:34:11'),
(12, 'Support', '/page-example', 1, NULL, 3, '2023-02-05 15:34:49', '2024-06-23 12:26:41'),
(13, 'Help Center', '/page-example', 1, 12, 1, '2023-02-05 15:35:02', '2023-02-05 15:35:22'),
(14, 'Customer Service', '/page-example', 1, 12, 2, '2023-02-05 15:35:12', '2023-02-05 15:35:22'),
(15, 'Frequently Asked Questions', '/page-example', 1, 12, 3, '2023-02-05 15:35:28', '2023-02-05 15:35:33'),
(16, 'Report a Problem', '/page-example', 1, 12, 4, '2023-02-05 15:35:49', '2023-02-05 15:35:53');

-- --------------------------------------------------------

--
-- Table structure for table `home_categories`
--

CREATE TABLE `home_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_categories`
--

INSERT INTO `home_categories` (`id`, `name`, `icon`, `link`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'WordPress Themes', 'images/home-categories/w2KxPEK8FjNjbfs_1733598767.jpg', '/categories/themes/wordpress', 1, '2024-03-06 09:34:53', '2024-12-07 13:12:47'),
(2, 'PHP Scripts', 'images/home-categories/VukPsIdffapI0Ty_1733598772.jpg', '/categories/code/php-scripts', 2, '2024-03-06 09:35:24', '2024-12-07 13:12:52'),
(3, 'HTML5 Codes', 'images/home-categories/T9qm8Gaj3ZzsuRd_1733598777.jpg', '/categories/code/html5', 3, '2024-03-06 09:36:11', '2024-12-07 13:12:57'),
(4, 'CSS Codes', 'images/home-categories/rEvitg7QwNJtS47_1733598783.jpg', '/categories/code/css', 4, '2024-03-06 09:36:40', '2024-12-07 13:13:03'),
(5, 'Android Apps', 'images/home-categories/67XDh5PdKqLmEVZ_1733598788.jpg', '/categories/mobile-apps/android', 5, '2024-03-06 09:37:31', '2024-12-07 13:13:08'),
(6, 'IOS Apps', 'images/home-categories/tIBpazWSgnkz3iX_1733598793.jpg', '/categories/mobile-apps/ios', 6, '2024-03-06 09:37:41', '2024-12-07 13:13:13'),
(7, 'Graphics', 'images/home-categories/UbBiYqbKpcNXrsS_1733598799.jpg', '/categories/graphics', 7, '2024-03-06 09:38:29', '2024-12-07 13:13:19');

-- --------------------------------------------------------

--
-- Table structure for table `home_sections`
--

CREATE TABLE `home_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `items_number` int DEFAULT NULL,
  `cache_expiry_time` int UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_id` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_sections`
--

INSERT INTO `home_sections` (`id`, `name`, `alias`, `description`, `items_number`, `cache_expiry_time`, `status`, `sort_id`) VALUES
(1, 'Explore Categories', 'categories', NULL, NULL, 10, 1, 1),
(2, 'Trending Items', 'trending_items', NULL, 4, 1440, 1, 2),
(3, 'Best Selling Items', 'best_selling_items', NULL, 4, 1440, 1, 3),
(4, 'Sale Items', 'sale_items', NULL, 4, 1440, 1, 4),
(5, 'Free Items', 'free_items', NULL, 4, 1440, 1, 5),
(6, 'Our Latest Items', 'latest_items', 'Explore our latest digital offerings, including PHP scripts, templates, and plugins. Stay updated with our newest arrivals, designed to enhance your web projects with cutting-edge functionality and innovation.', 8, 60, 1, 8),
(7, 'FAQ\'s', 'faqs', 'Got questions? We\'ve got answers. Delve into our Frequently Asked Questions (FAQs) section to find comprehensive information about our items, services, and more.', NULL, 30, 1, 10),
(8, 'Testimonials', 'testimonials', 'Discover what our valued clients are saying about their experiences with us.', NULL, 1440, 1, 11),
(9, 'Latest Blog Posts', 'blog_articles', 'Stay informed and inspired with our latest blog posts. Dive into a treasure trove of articles covering a diverse range of topics, from expert insights to practical tips.', 6, 1440, 1, 12),
(11, 'Featured Author', 'featured_author', NULL, 3, 1440, 1, 7),
(12, 'Featured Items', 'featured_items', 'Each week, our team carefully selects the finest new themes, scripts, and plugins from our extensive library.', 4, 1440, 1, 9),
(13, 'Premium Items', 'premium_items', '', 4, 1440, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_audio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_main_file_external` tinyint(1) NOT NULL DEFAULT '0',
  `screenshots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `regular_price` double NOT NULL,
  `extended_price` double NOT NULL,
  `is_supported` tinyint(1) DEFAULT '0',
  `support_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Soft rejected 3:Resubmitted 4:Approved 5:Hard Rejected 6:Deleted',
  `total_sales` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_sales_amount` double DEFAULT '0',
  `total_earnings` double DEFAULT '0',
  `total_reviews` bigint UNSIGNED NOT NULL DEFAULT '0',
  `avg_reviews` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_comments` bigint NOT NULL DEFAULT '0',
  `total_views` bigint UNSIGNED NOT NULL DEFAULT '0',
  `current_month_views` bigint UNSIGNED NOT NULL DEFAULT '0',
  `free_downloads` bigint UNSIGNED NOT NULL DEFAULT '0',
  `purchasing_status` tinyint(1) NOT NULL DEFAULT '1',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `is_free` tinyint(1) DEFAULT '0',
  `is_trending` tinyint(1) NOT NULL DEFAULT '0',
  `is_best_selling` tinyint(1) NOT NULL DEFAULT '0',
  `is_on_discount` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `was_featured` tinyint(1) NOT NULL DEFAULT '0',
  `last_update_at` datetime DEFAULT NULL,
  `last_discount_at` datetime DEFAULT NULL,
  `price_updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_change_logs`
--

CREATE TABLE `item_change_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_comments`
--

CREATE TABLE `item_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_comment_replies`
--

CREATE TABLE `item_comment_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `item_comment_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_comment_reports`
--

CREATE TABLE `item_comment_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `item_comment_reply_id` bigint UNSIGNED NOT NULL,
  `reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_discounts`
--

CREATE TABLE `item_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `regular_percentage` int UNSIGNED NOT NULL,
  `regular_price` double NOT NULL,
  `extended_percentage` int UNSIGNED DEFAULT NULL,
  `extended_price` double DEFAULT NULL,
  `starting_at` date NOT NULL,
  `ending_at` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_histories`
--

CREATE TABLE `item_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED DEFAULT NULL,
  `reviewer_id` bigint UNSIGNED DEFAULT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_reviews`
--

CREATE TABLE `item_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `stars` int NOT NULL,
  `subject` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_review_replies`
--

CREATE TABLE `item_review_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `item_review_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_updates`
--

CREATE TABLE `item_updates` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demo_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_audio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_main_file_external` tinyint(1) NOT NULL DEFAULT '0',
  `screenshots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `regular_price` double DEFAULT NULL,
  `extended_price` double DEFAULT NULL,
  `is_supported` tinyint(1) DEFAULT '0',
  `support_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `purchasing_status` tinyint(1) NOT NULL DEFAULT '1',
  `is_free` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_views`
--

CREATE TABLE `item_views` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `kyc_verifications`
--

CREATE TABLE `kyc_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `document_type` enum('national_id','passport') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `documents` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Approved 3:Rejected',
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_earnings` bigint UNSIGNED NOT NULL,
  `fees` int NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`, `min_earnings`, `fees`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Default', 0, 50, 1, '2024-03-05 19:23:03', '2024-03-05 19:23:03'),
(2, 'Level 1', 100, 37, 0, '2024-03-05 19:23:03', '2024-03-05 19:23:03'),
(3, 'Level 2', 1000, 34, 0, '2024-03-05 19:23:23', '2024-03-05 19:23:23'),
(4, 'Level 3', 5000, 30, 0, '2024-03-05 19:23:43', '2024-03-05 19:23:43'),
(5, 'Level 4', 10000, 25, 0, '2024-03-05 19:24:03', '2024-03-05 19:24:03'),
(6, 'Level 5', 25000, 20, 0, '2024-03-05 19:24:19', '2024-11-14 18:29:42'),
(7, 'Level 6', 45000, 15, 0, '2024-03-05 19:24:37', '2024-03-05 19:24:37'),
(8, 'Level 7', 75000, 12, 0, '2024-03-05 19:25:15', '2024-03-05 19:25:15'),
(9, 'Level 8', 250000, 10, 0, '2024-03-05 19:25:35', '2024-03-05 19:25:35'),
(10, 'Level 9', 500000, 5, 0, '2024-03-05 19:26:17', '2024-03-05 19:26:17'),
(11, 'Level 10', 1000000, 0, 0, '2024-03-05 19:27:31', '2024-03-05 19:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortcodes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `alias`, `name`, `subject`, `body`, `shortcodes`, `status`) VALUES
(1, 'password_reset', 'Reset Password', 'Reset Your Account Password', '<h2><strong>Hello!</strong></h2><p>You are receiving this email because we received a password reset request for your account, please click on the link below to reset your password.</p><p><a href=\"{{link}}\">{{link}}</a></p><p>This password reset link will expire in <strong>{{expiry_time}}</strong> minutes. If you did not request a password reset, no further action is required.</p><p>Regards,<br><strong>{{website_name}}</strong></p>', '[\"link\",\"expiry_time\",\"website_name\"]', 1),
(2, 'email_verification', 'Email Verification', 'Verify Email Address', '<h2>Hello!</h2><p>Please click on the link below to verify your email address.</p><p><a href=\"{{link}}\">{{link}}</a></p><p>If you did not create an account, no further action is required.</p><p>&nbsp;</p><p>Regards,<br><strong>{{website_name}}</strong></p>', '[\"link\",\"website_name\"]', 1),
(3, 'kyc_verification_approved', 'KYC Verification Approved', 'Your KYC verification has been approved', '<h2>Hi, {{username}}</h2><p>We are pleased to inform you that your Know Your Customer (KYC) verification process has been successfully completed and approved. Your account is now fully verified and ready for use.</p><p>This verification process ensures the security and integrity of our platform, and we appreciate your cooperation throughout this process. With your KYC verification approved, you now have access to all features and functionalities of our platform without any restrictions.</p><p>Should you have any questions or require further assistance, please do not hesitate to contact our customer support team. We are here to help you with any queries you may have.</p><p>Thank you for choosing our platform. We look forward to serving you and providing you with an excellent user experience.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"website_name\"]', 1),
(4, 'kyc_verification_rejected', 'KYC Verification Rejected', 'Your KYC verification has been rejected', '<h2>Hi, {{username}}</h2><p>We regret to inform you that your recent Know Your Customer (KYC) verification submission has been rejected. After a thorough review, we have determined that we are unable to approve your KYC verification at this time.</p><p>The reason for the rejection is as follows:&nbsp;<br>“ {{rejection_reason}} ”</p><p>We understand that this may be disappointing, and we apologize for any inconvenience this may cause. Please review the reason provided above to understand why your submission was not successful.</p><p>To address this issue and proceed with the verification process, we kindly request that you review the provided reason and take the necessary steps to rectify any discrepancies or issues. Once you have addressed the concerns, you may resubmit your KYC verification documents for further review.</p><p>If you have any questions or require assistance in understanding the rejection reason or the steps to take for resubmission, please don\'t hesitate to reach out to our customer support team. We are here to assist you throughout this process.</p><p>Thank you for your understanding and cooperation.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"rejection_reason\",\"website_name\"]', 1),
(5, 'new_ticket', 'New Ticket', 'New Ticket [#{{ticket_id}}]', '<h2>Hi <strong>{{username}},</strong></h2><p>We trust this message finds you well. We want to inform you that a new support ticket has been created.</p><p>Here are the details of the ticket:</p><p><strong>Ticket ID:</strong> #{{ticket_id}}</p><p><strong>Category: </strong>{{category}}</p><p><strong>Date Created:</strong> {{date}}</p><p><strong>Brief Description of the Issue:</strong> {{subject}}<strong>.</strong></p><p>You can view the entire conversation and reply by following this link:<a href=\"{{link}}\"> {{link}}</a></p><p>We understand the importance of your concern and assure you that it\'s receiving our immediate attention. Our dedicated team will be reviewing your request and will provide updates, solutions, or further assistance as needed.</p><p>Should you have any additional information to share or any questions, feel free to log in to our support portal and contribute to the ongoing conversation about your ticket.</p><p>Thank you for giving us the opportunity to assist you.</p><p>Regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"ticket_id\",\"subject\",\"category\",\"link\",\"date\",\"website_name\"]', 1),
(6, 'new_ticket_reply', 'New Ticket Reply', 'New reply on your ticket [#{{ticket_id}}]', '<h2>Hi <strong>{{username}},</strong></h2><p>We hope this message finds you well. We wanted to inform you that a new reply has been added to your support ticket #<strong>{{ticket_id}}</strong>. Our team has been working diligently to assist you.</p><p><strong>Reply Message:</strong></p><p>{{reply_message}}</p><p><strong>Reply date: </strong>{{date}}</p><p>You can view the entire conversation and reply by following this link:<a href=\"{{link}}\"> {{link}}</a></p><p>Thank you for your patience and cooperation as we work to resolve your issue.</p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"username\",\"ticket_id\",\"reply_message\",\"link\",\"date\",\"website_name\"]', 1),
(7, 'buyer_item_update', 'Buyer Item Update', 'New Update ({{item_name}})', '<h2>Hi, {{buyer_username}}!</h2><p>The author <strong>{{author_username}} </strong>has released a new update for <strong>{{item_name}}, </strong>here is the details:</p><p><a href=\"{{item_link}}\"><strong>{{item_name}}</strong></a></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p><strong>View Item:</strong> <a href=\"{{item_link}}\">{{item_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"buyer_username\",\"author_username\",\"item_name\",\"item_preview_image\",\"item_link\",\"website_name\"]', 1),
(8, 'follower_new_item', 'Follower New Item', '{{author_username}} has published a new item', '<h2>Hi, {{follower_username}}!</h2><p>The author <strong>{{author_username}} </strong>has published a new item, here is the details:</p><p><a href=\"{{item_link}}\"><strong>{{item_name}}</strong></a></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p><strong>View Item:</strong> <a href=\"{{item_link}}\">{{item_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"follower_username\",\"author_username\",\"item_name\",\"item_preview_image\",\"item_link\",\"website_name\"]', 1),
(9, 'payment_confirmation', 'Payment Confirmation', 'Payment Confirmation [#{{transaction_id}}]', '<h2>Hi, {{username}}</h2><p>We hope this email finds you well. We are reaching out to confirm the successful payment for your recent transaction.</p><p><strong><u>Here are the details of your transaction:</u></strong></p><p><strong>Transaction ID:</strong> #{{transaction_id}}</p><p><strong>Payment Method:</strong> {{payment_method}}</p><p><strong>SubTotal:</strong> {{transaction_subtotal}}</p><p><strong>Fees:</strong> {{transaction_fees}}</p><p><strong>Total :</strong> {{transaction_total}}</p><p><strong>Date: </strong>{{transaction_date}}</p><p>Your payment has been processed successfully, and your transaction is now complete. You can view the transaction and print your invoice by clicking on the link below</p><p><a href=\"{{transaction_view_link}}\">{{transaction_view_link}}</a></p><p>If you have any questions or require further assistance, please don\'t hesitate to contact us. We are here to help.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"transaction_id\",\"transaction_subtotal\",\"payment_method\",\"transaction_fees\",\"transaction_total\",\"transaction_date\",\"transaction_view_link\",\"website_name\"]', 1),
(10, 'purchase_confirmation', 'Purchase Confirmation', 'Your Purchase Confirmation ({{item_name}})', '<h2>Hi, {{username}}</h2><p>We are thrilled to inform you that your recent purchase has been successfully processed. Your satisfaction is our priority, and we are grateful for your trust in {{website_name}}.</p><p><strong><u>Here are the details of your purchase:</u></strong></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p><strong>Item Name: </strong><a href=\"{{item_link}}\">{{item_name}}</a></p><p><strong>Purchase Code: </strong>{{purchase_code}}</p><p><strong>Download Link: </strong><a href=\"{{download_link}}\">{{download_link}}</a></p><p>If you have any questions or require further assistance, please don\'t hesitate to contact us. We are here to help.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"item_name\",\"item_preview_image\",\"item_link\",\"purchase_code\",\"download_link\",\"website_name\"]', 1),
(11, 'transaction_cancelled', 'Transaction Cancelled', 'Your transaction has been canceled [#{{transaction_id}}]', '<h2>Hi, {{username}}</h2><p>We hope this email finds you well. We are reaching out because your recent transaction has been canceled for the following reason:</p><p>--</p><p><i>{{cancellation_reason}}</i></p><p><i>--</i></p><p><strong><u>Here are the details of your transaction:</u></strong></p><p><strong>Transaction ID:</strong> #{{transaction_id}}</p><p><strong>Payment Method:</strong> {{payment_method}}</p><p><strong>SubTotal:</strong> {{transaction_subtotal}}</p><p><strong>Fees:</strong> {{transaction_fees}}</p><p><strong>Total:</strong> {{transaction_total}}</p><p><strong>Date: </strong>{{transaction_date}}</p><p><strong>View Link:</strong> <a href=\"{{transaction_view_link}}\">{{transaction_view_link}}</a></p><p>If you have any questions or require further assistance, please don\'t hesitate to contact us. We are here to help.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"transaction_id\",\"transaction_subtotal\",\"payment_method\",\"transaction_fees\",\"transaction_total\",\"transaction_date\",\"transaction_view_link\",\"cancellation_reason\",\"website_name\"]', 1),
(12, 'item_comment_reply', 'Item Comment Reply', '{{author_username}} Replied to your comment on \"{{item_name}}\"', '<h2>Hi, {{user_username}}</h2><p>The author <strong>{{author_username}}</strong> replied to your comment on “<a href=\"{{item_link}}\">{{item_name}}</a>”</p><p>--</p><p><i>{{comment_reply}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can reply by following this link</span><strong>:</strong> <a href=\"{{comment_link}}\">{{comment_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"user_username\",\"author_username\",\"comment_reply\",\"item_name\",\"item_link\",\"comment_link\",\"website_name\"]', 1),
(13, 'refund_request_new_reply', 'Refund Request New Reply', 'New reply on your refund request for \"{{refund_item_name}}\"', '<h2>Hi, {{user_username}}</h2><p>You have a new reply on your refund request for “<strong>{{refund_item_name}}</strong>” from <strong>{{author_username}}&nbsp;</strong></p><p>--</p><p><i>{{refund_reply}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can view and reply by following this link</span><strong>:</strong> <a href=\"{{refund_link}}\">{{refund_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"user_username\",\"author_username\",\"refund_id\",\"refund_item_name\",\"refund_reply\",\"refund_link\",\"website_name\"]', 1),
(14, 'refund_request_accepted', 'Refund Request Accepted', 'Your refund request for \"{{refund_item_name}}\" has been accepted', '<h2>Hi, {{user_username}}</h2><p>Your refund request for “<strong>{{refund_item_name}}</strong>” has been accepted by <strong>{{author_username}}</strong> and the full amount of <strong>{{refund_amount}}</strong> has been refunded to your {{website_name}} account.</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can view the refund request by following this link</span><strong>:</strong> <a href=\"{{refund_link}}\">{{refund_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"user_username\",\"author_username\",\"refund_id\",\"refund_item_name\",\"refund_amount\",\"refund_link\",\"website_name\"]', 1),
(15, 'refund_request_declined', 'Refund Request Declined', 'Your refund request for \"{{refund_item_name}}\" has declined by {{author_username}}', '<h2>Hi, {{user_username}}</h2><p>Your refund request for “<strong>{{refund_item_name}}</strong>” has declined by <strong>{{author_username}}&nbsp;</strong></p><p>--</p><p><i>{{refund_decline_reason}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can view the refund request by following this link</span><strong>:</strong> <a href=\"{{refund_link}}\">{{refund_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"user_username\",\"author_username\",\"refund_id\",\"refund_item_name\",\"refund_decline_reason\",\"refund_link\",\"website_name\"]', 1),
(16, 'author_item_soft_rejected', 'Author Item Soft Rejected', '[Soft Rejected] {{item_name}}', '<h2>Hi, {{author_username}}!</h2><p>Your item <strong>{{item_name}}</strong> needs improvements, check the rejection reason edit your item, and resubmit it.</p><p><strong><u>Item Details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Item Edit Link:</strong><a href=\"{{review_link}}\"> </a><a href=\"{{item_edit_link}}\">{{item_edit_link}}</a></p><p><strong><u>Rejection Reason:</u></strong></p><p>--</p><p><i>{{rejection_reason}}</i></p><p><i>--</i></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"item_edit_link\",\"rejection_reason\",\"website_name\"]', 1),
(17, 'author_item_approved', 'Author Item Approved', 'Your Item has been Approved', '<h2>Hi, {{author_username}}!</h2><p>Your item has been approved and it\'s available for purchase.</p><p><a href=\"{{item_link}}\"><strong>{{item_name}}</strong></a></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_name\",\"item_preview_image\",\"item_link\",\"website_name\"]', 1),
(18, 'author_item_hard_rejected', 'Author Item Hard Rejected', '[Hard Rejected] {{item_name}}', '<h2>Hi, {{author_username}}!</h2><p>Your item has been rejected because it does not meet our site standards.</p><p><strong>{{item_name}}</strong></p><p>{{item_preview_image}}</p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_name\",\"item_preview_image\",\"website_name\"]', 1),
(19, 'author_item_update_approved', 'Author Item Update Approved', 'Your Item update has been Approved', '<h2>Hi, {{author_username}}!</h2><p>Your item update has been approved.</p><p><a href=\"{{item_link}}\"><strong>{{item_name}}</strong></a></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_name\",\"item_preview_image\",\"item_link\",\"website_name\"]', 1),
(20, 'author_item_update_rejected', 'Author Item Update Rejected', '[Update Rejected] {{item_name}}', '<h2>Hi, {{author_username}}!</h2><p>Your item update has been rejected for the following reasons:</p><p>--</p><p><i>{{rejection_reason}}</i></p><p><i>--</i></p><p><a href=\"{{item_link}}\"><strong>{{item_name}}</strong></a></p><p><a href=\"{{item_link}}\">{{item_preview_image}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_name\",\"item_preview_image\",\"item_link\",\"rejection_reason\",\"website_name\"]', 1),
(21, 'author_item_review', 'Author Item Review ', 'New review on your item \"{{item_name}}\" from {{user_username}}', '<h2>Hi, {{author_username}}</h2><p>You have a new review on your item “<a href=\"{{item_link}}\">{{item_name}}</a>” from {{user_username}}</p><p><strong>Stars:</strong> {{stars}}</p><p><strong>Subject:</strong> {{subject}}</p><p>--</p><p><i>{{review}}</i></p><p>--</p><p>Review link: <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"user_username\",\"stars\",\"subject\",\"review\",\"item_name\",\"item_link\",\"review_link\",\"website_name\"]', 1),
(22, 'author_item_comment', 'Author Item Comment ', 'New comment on your item \"{{item_name}}\" from {{user_username}}', '<h2>Hi, {{author_username}}</h2><p>You have a new comment on your item “<a href=\"{{item_link}}\">{{item_name}}</a>” from {{user_username}}</p><p>--</p><p><i>{{comment}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can reply by following this link</span><strong>:</strong> <a href=\"{{comment_link}}\">{{comment_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"user_username\",\"comment\",\"item_name\",\"item_link\",\"comment_link\",\"website_name\"]', 1),
(23, 'author_item_comment_reply', 'Author Item Comment Reply', 'New comment reply on your item \"{{item_name}}\" from {{user_username}}', '<h2>Hi, {{author_username}}</h2><p>You have a new comment reply on your item “<a href=\"{{item_link}}\">{{item_name}}</a>” from {{user_username}}</p><p>--</p><p><i>{{comment_reply}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can reply by following this link</span><strong>:</strong> <a href=\"{{comment_link}}\">{{comment_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"user_username\",\"comment_reply\",\"item_name\",\"item_link\",\"comment_link\",\"website_name\"]', 1),
(24, 'author_withdrawal_status_updated', 'Author Withdrawal Status Updated', 'Your withdrawal status has been updated', '<h2>Hi, <strong>{{author_username}}</strong></h2><p>The status of your withdrawal request has been updated</p><p><strong><u>Withdrawal details:</u></strong></p><p><strong>Request ID: </strong>#{{request_id}}</p><p><strong>Amount : </strong>{{amount}}</p><p><strong>Method : </strong>{{method}}</p><p><strong>Account : </strong>{{account}}</p><p><strong>Status : </strong>{{status}}</p><p><strong>Date</strong>: {{date}}</p><p><strong>Link:</strong> <a href=\"{{link}}\">{{link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"request_id\",\"amount\",\"method\",\"account\",\"status\",\"date\",\"link\",\"website_name\"]', 1),
(25, 'author_new_refund_request', 'Author New Refund Request', 'Refund Request for \"{{refund_item_name}}\" from {{user_username}}', '<h2>Hi, {{author_username}}</h2><p>You have a new refund request for “<strong>{{refund_item_name}}</strong>” from <strong>{{user_username}}</strong></p><p>--</p><p><i>{{refund_reason}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can view and take action by following this link</span><strong>:</strong> <a href=\"{{refund_link}}\">{{refund_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"user_username\",\"refund_id\",\"refund_item_name\",\"refund_reason\",\"refund_link\",\"website_name\"]', 1),
(26, 'author_refund_request_new_reply', 'Author Refund Request New Reply', 'New Reply On Refund request [#{{refund_id}}]', '<h2>Hi, {{author_username}}</h2><p>You have a new reply on refund request for “<strong>{{refund_item_name}}</strong>” from <strong>{{user_username}}&nbsp;</strong></p><p>--</p><p><i>{{refund_reply}}</i></p><p>--</p><p><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\">You can view and take action by following this link</span><strong>:</strong> <a href=\"{{refund_link}}\">{{refund_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"user_username\",\"refund_id\",\"refund_item_name\",\"refund_reply\",\"refund_link\",\"website_name\"]', 1),
(27, 'reviewer_item_pending', 'Reviewer Item Pending', 'New Pending Item ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> has submitted a new item, please review it and take necessary action.</p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(28, 'reviewer_item_resubmitted', 'Reviewer Item Resubmitted', 'Item Resubmitted ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> has resubmitted the item <strong>{{item_name}}</strong>, please review it and take necessary action.</p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(29, 'reviewer_item_update', 'Reviewer Item Update', 'Item Update Request ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> submitted an updating request for item <strong>{{item_name}}</strong>, please review it and take necessary action.</p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong><a href=\"{{review_link}}\"> {{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(30, 'admin_kyc_pending', 'Admin KYC Pending', 'KYC Verification Request [#{{kyc_verification_id}}]', '<h2>Hello!</h2><p>You have a new KYC Verification Request submitted by <strong>{{username}} </strong>and the ID is #<strong>{{kyc_verification_id}}</strong></p><p><a href=\"{{review_link}}\">{{review_link}}</a></p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"kyc_verification_id\",\"review_link\",\"website_name\"]', 1),
(31, 'admin_item_pending', 'Admin Item Pending', 'New Pending Item ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> has submitted a new item and its waiting for review.</p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(32, 'admin_item_resubmitted', 'Admin Item Resubmitted', 'Item Resubmitted ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> has resubmitted the item <strong>{{item_name}} </strong>and it is waiting for review.</p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(33, 'admin_item_update', 'Admin Item Update', 'Item Update Request ({{item_name}})', '<h2>Hello!</h2><p>The author <strong>{{author_username}}</strong> request updating the item <strong>{{item_name}}.</strong></p><p><strong><u>Here is the details:</u></strong></p><p>{{item_preview_image}}</p><p><strong>Item ID:</strong> #{{item_id}}</p><p><strong>Item Name: </strong>{{item_name}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"item_id\",\"item_name\",\"item_preview_image\",\"review_link\",\"website_name\"]', 1),
(34, 'admin_transaction_pending', 'Admin Transaction Pending', 'New Pending Transaction [#{{transaction_id}}]', '<h2>Hello!</h2><p>You have a new pending transaction made by <strong>{{username}}</strong>.&nbsp;</p><p><strong><u>Here are the details:</u></strong></p><p><strong>Transaction ID:</strong> #{{transaction_id}}</p><p><strong>Payment Method:</strong> {{payment_method}}</p><p><strong>SubTotal:</strong> {{transaction_subtotal}}</p><p><strong>Fees:</strong> {{transaction_fees}}</p><p><strong>Total:</strong> {{transaction_total}}</p><p><strong>Date: </strong>{{transaction_date}}</p><p><strong>Review Link: </strong><a href=\"{{review_link}}\">{{review_link}}</a></p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"transaction_id\",\"transaction_subtotal\",\"transaction_fees\",\"transaction_total\",\"payment_method\",\"transaction_date\",\"review_link\",\"website_name\"]', 1),
(35, 'admin_withdrawal_pending', 'Admin Withdrawal Pending', 'New Withdrawal Request [#{{request_id}}]', '<h2>Hello!</h2><p>The author <strong>{{author_username}} </strong>has requested a new withdrawal.</p><p><strong><u>Withdrawal details:</u></strong></p><p><strong>Request ID: </strong>#{{request_id}}</p><p><strong>Amount : </strong>{{amount}}</p><p><strong>Method : </strong>{{method}}</p><p><strong>Account : </strong>{{account}}</p><p><strong>Status : </strong>{{status}}</p><p><strong>Date</strong>: {{date}}</p><p><strong>Review Link:</strong> <a href=\"{{review_link}}\">{{review_link}}</a></p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"author_username\",\"request_id\",\"amount\",\"method\",\"account\",\"status\",\"date\",\"review_link\",\"website_name\"]', 1),
(36, 'admin_new_ticket', 'Admin New Ticket', 'New Ticket [#{{ticket_id}}]', '<h2>Hello!</h2><p>A new ticket has been created by <strong>{{username}}</strong>. Here are the details:</p><p><strong>Ticket ID:</strong> #{{ticket_id}}</p><p><strong>Category: </strong>{{category}}</p><p><strong>Date Created:</strong> {{date}}</p><p><strong>Brief Description of the Issue:</strong> {{subject}}<strong>.</strong></p><p>You can view the entire conversation and reply by following this link:<a href=\"{{link}}\"> {{link}}</a></p><p>The department agents and the admins have been informed, action will be taken and assistance will be provided.</p><p>Regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"ticket_id\",\"subject\",\"category\",\"link\",\"date\",\"website_name\"]', 1),
(37, 'admin_new_ticket_reply', 'Admin New Ticket Reply', 'New Ticket Reply [#{{ticket_id}}]', '<h2>Hello!</h2><p>A new reply by<strong> {{username}}</strong> has been added to the ticket #<strong>{{ticket_id}}</strong>.</p><p><strong>Reply message:</strong></p><p>{{reply_message}}</p><p><strong>Reply date: </strong>{{date}}</p><p>You can view the entire conversation and reply by following this link:<a href=\"{{link}}\"> {{link}}</a></p><p>The department agents and the admins have been informed, action will be taken and assistance will be provided</p><p>Regards,&nbsp;<br><strong>{{website_name}}</strong></p>', '[\"username\",\"ticket_id\",\"reply_message\",\"link\",\"date\",\"website_name\"]', 1),
(38, 'subscription_about_to_expire', 'Subscription About To Expire', 'Your subscription is about to expire', '<h2>Hi {{username}},</h2><p>We hope you\'re enjoying your experience on {{website_name}}. We wanted to remind you that your subscription is set to expire on <strong>{{expiry_date}}</strong>.</p><p>Don\'t miss out on continued access to our extensive library of assets and exclusive resources. To renew or upgrade your subscription, simply click the link below:</p><p><a href=\"{{renewing_link}}\">{{renewing_link}}</a></p><p>We look forward to continuing to support you with the best resources and tools.</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"expiry_date\",\"renewing_link\",\"website_name\"]', 1),
(39, 'subscription_expired', 'Subscription Expired', 'Your subscription has been expired', '<h2>Hi {{username}},</h2><p>We wanted to inform you that your subscription expired on <strong>{{expiry_date}}</strong>. Unfortunately, you no longer have access to our exclusive library of assets.</p><p>But don\'t worry—you can easily renew or upgrade your subscription to regain access to all the resources you love! Just click the link below to renew:</p><p><a href=\"{{renewing_link}}\">{{renewing_link}}</a></p><p>We hope to see you back soon!</p><p>Best regards,<br><strong>{{website_name}}</strong></p>', '[\"username\",\"expiry_date\",\"renewing_link\",\"website_name\"]', 1);

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
(2, '2014_10_11_1000000_create_admin_notifications_table', 2),
(5, '2014_10_12_100000_create_password_resets_table', 5),
(11, '2021_10_04_213420_create_pages_table', 11),
(15, '2021_10_07_221832_create_settings_table', 15),
(17, '2022_02_23_213634_create_extensions_table', 17),
(18, '2022_04_03_220038_create_mail_templates_table', 18),
(19, '2023_07_30_152852_create_oauth_providers_table', 19),
(20, '2023_09_17_154715_create_payment_gateways_table', 20),
(21, '2023_09_26_125948_create_themes_table', 21),
(22, '2023_10_01_131610_create_addons_table', 22),
(45, '2019_12_14_000001_create_personal_access_tokens_table', 45),
(47, '2021_10_06_201713_create_blog_categories_table', 46),
(48, '2021_10_06_201752_create_blog_articles_table', 46),
(49, '2021_10_06_202153_create_blog_comments_table', 46),
(50, '2014_10_13_000000_create_reviewers_table', 47),
(51, '2014_10_13_100000_create_reviewer_password_resets_table', 47),
(55, '2021_10_28_191044_create_storage_providers_table', 49),
(56, '2023_11_20_171940_create_withdrawal_methods_table', 50),
(57, '2023_11_22_173153_create_faqs_table', 51),
(58, '2023_11_22_182033_create_testimonials_table', 52),
(59, '2023_12_10_172652_create_top_nav_links_table', 53),
(60, '2023_12_10_172707_create_bottom_nav_links_table', 53),
(61, '2023_12_10_172728_create_footer_links_table', 53),
(62, '2023_12_13_172603_create_categories_table', 54),
(63, '2023_12_13_173552_create_sub_categories_table', 54),
(64, '2023_12_15_151129_create_home_categories_table', 54),
(65, '2023_12_16_181029_create_category_reviewer_table', 54),
(66, '2023_12_21_135143_create_category_options_table', 54),
(71, '2024_01_05_195352_create_levels_table', 56),
(72, '2024_01_05_213820_create_badges_table', 56),
(76, '2014_10_12_000000_create_users_table', 57),
(78, '2014_10_12_300000_create_user_login_logs_table', 57),
(79, '2023_12_29_155557_create_uploaded_files_table', 57),
(81, '2024_01_03_003738_create_items_table', 57),
(83, '2024_01_06_014922_create_user_badges_table', 57),
(84, '2024_02_18_165133_create_withdrawals_table', 57),
(85, '2024_02_24_162334_create_kyc_verifications_table', 57),
(89, '2014_10_11_000000_create_admins_table', 58),
(90, '2014_10_11_100000_create_admin_password_resets', 58),
(91, '2024_01_05_165601_create_item_histories_table', 58),
(92, '2024_03_02_194241_create_item_updates_table', 58),
(93, '2024_03_09_193935_create_item_discounts_table', 59),
(95, '2024_03_13_165718_create_home_sections_table', 60),
(100, '2024_03_19_171106_create_cart_items_table', 62),
(120, '2024_04_01_235823_create_item_reviews_table', 65),
(123, '2024_04_04_223313_create_item_comments_table', 66),
(124, '2024_04_04_223335_create_item_comment_replies_table', 66),
(126, '2024_04_16_191052_create_ads_table', 68),
(127, '2024_01_02_221517_create_referrals_table', 69),
(132, '2024_03_21_025080_create_sales_table', 70),
(133, '2024_03_21_025090_create_purchases_table', 70),
(134, '2024_04_20_185327_create_referral_earnings_table', 70),
(137, '2024_04_23_173759_create_favorites_table', 72),
(140, '2024_04_24_192449_create_refunds_table', 73),
(141, '2024_04_24_192550_create_refund_replies_table', 73),
(145, '2024_04_20_185430_create_statements_table', 74),
(146, '2024_05_02_201701_create_followers_table', 75),
(147, '2024_05_02_212918_add_columns_to_users_table', 76),
(148, '2024_05_03_174916_create_jobs_table', 77),
(149, '2024_05_03_183505_create_failed_jobs_table', 78),
(151, '2024_05_04_210907_create_ticket_categories_table', 79),
(152, '2024_05_06_170025_create_tickets_table', 80),
(155, '2024_05_07_171258_create_ticket_replies_table', 81),
(156, '2024_05_07_171419_create_ticket_reply_attachments_table', 81),
(157, '2024_04_04_223335_create_item_views_table', 82),
(158, '2024_04_01_235824_create_item_review_replies_table', 83),
(160, '2024_05_15_205642_create_translates_table', 84),
(162, '2024_05_30_162644_create_author_taxes_table', 85),
(163, '2024_05_30_163150_create_buyer_taxes_table', 85),
(170, '2024_06_21_191033_create_item_change_logs_table', 87),
(172, '2024_06_29_151017_create_captcha_providers_table', 88),
(174, '2024_07_09_110258_create_support_periods_table', 89),
(175, '2024_03_21_025100_create_transactions_table', 90),
(177, '2024_03_21_050420_create_transaction_items_table', 91),
(182, '2024_07_10_105956_create_support_earnings_table', 92),
(183, '2024_08_24_114518_create_item_comment_reports_table', 93),
(184, '2024_09_03_111407_create_plans_table', 94),
(185, '2024_09_09_152943_create_subscriptions_table', 95),
(189, '2024_09_12_002743_create_premium_earnings_table', 96),
(190, '2024_10_24_205653_create_help_categories_table', 97),
(191, '2024_10_24_205705_create_help_articles_table', 97),
(193, '2024_12_04_113954_create_currencies_table', 98),
(194, '2024_12_06_152252_create_editor_images_table', 99);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_providers`
--

CREATE TABLE `oauth_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_providers`
--

INSERT INTO `oauth_providers` (`id`, `name`, `alias`, `logo`, `credentials`, `instructions`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'facebook', 'images/oauth/facebook.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\"><li><strong>Redirect URL :</strong> [URL]/oauth/facebook/callback</li> \n</ul>', 0, '2022-02-23 18:40:12', '2024-08-30 17:47:08'),
(2, 'Google', 'google', 'images/oauth/google.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\">  <li><strong>Redirect URL :</strong> [URL]/oauth/google/callback</li>  </ul>', 0, '2022-02-23 18:40:12', '2024-08-30 17:47:00'),
(3, 'Microsoft', 'microsoft', 'images/oauth/microsoft.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\">  <li><strong>Redirect URL :</strong> [URL]/oauth/microsoft/callback</li>  </ul>', 0, '2022-02-23 18:40:12', '2024-08-31 19:53:37'),
(4, 'Vkontakte', 'vkontakte', 'images/oauth/vkontakte.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\">  <li><strong>Redirect URL :</strong> [URL]/oauth/vkontakte/callback</li>  </ul>', 0, '2022-02-23 18:40:12', '2024-08-31 15:56:04'),
(5, 'Envato', 'envato', 'images/oauth/envato.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\">  <li><strong>Redirect URL :</strong> [URL]/oauth/envato/callback</li>  </ul>', 0, '2022-02-23 18:40:12', '2024-08-31 15:11:48'),
(6, 'Github', 'github', 'images/oauth/github.png', '{\"client_id\":null,\"client_secret\":null}', '<ul class=\"mb-0\">  <li><strong>Redirect URL :</strong> [URL]/oauth/github/callback</li>  </ul>', 0, '2022-02-23 18:40:12', '2024-08-30 17:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `body`, `short_description`, `views`, `created_at`, `updated_at`) VALUES
(2, 'Privacy policy', 'privacy-policy', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 65, '2024-03-05 20:19:14', '2024-12-06 16:36:48'),
(3, 'Terms of use', 'terms-of-use', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 65, '2024-03-05 20:19:38', '2024-12-06 16:36:49'),
(4, 'Refund policy', 'refund-policy', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 83, '2024-03-05 20:19:58', '2024-12-06 16:36:48'),
(5, 'Page Example', 'page-example', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 24, '2024-03-05 20:20:33', '2024-12-06 15:57:00'),
(6, 'Licenses Terms', 'licenses-terms', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 14, '2024-03-17 22:45:16', '2024-12-04 18:54:22'),
(7, 'Author Terms', 'author-terms', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 1, '2024-04-17 19:54:28', '2024-04-22 10:02:41'),
(8, 'Referral Program Terms', 'referral-program-terms', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 1, '2024-04-22 10:02:22', '2024-04-22 10:02:24'),
(9, 'Free Items Policy', 'free-items-policy', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 5, '2024-06-01 13:39:34', '2024-12-04 18:54:22'),
(10, 'Premium Terms', 'premium-terms', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 3, '2024-09-11 00:15:04', '2024-12-04 18:54:22'),
(12, 'GDPR Policy', 'gdpr-policy', '<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 5, '2024-11-15 10:00:25', '2024-12-04 18:54:22');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fees` int NOT NULL DEFAULT '0',
  `charge_currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge_rate` decimal(28,9) DEFAULT NULL,
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mode` enum('sandbox','live') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `sort_id`, `name`, `alias`, `logo`, `fees`, `charge_currency`, `charge_rate`, `credentials`, `parameters`, `is_manual`, `instructions`, `mode`, `status`) VALUES
(1, 1, 'Acount Balance', 'balance', 'images/payment-gateways/balance.png', 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0),
(2, 2, 'Paypal', 'paypal', 'images/payment-gateways/paypal.png', 0, NULL, NULL, '{\"client_id\":null,\"client_secret\":null,\"webhook_id\":null}', '[{\"type\": \"event\", \"label\": \"Webhook Event\", \"content\": \"payment.capture.completed\"},\r\n{\"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/paypal\" }]', 0, NULL, 'sandbox', 0),
(3, 2, 'Paypal IPN', 'paypal_ipn', 'images/payment-gateways/paypal_ipn.png', 0, NULL, NULL, '{\"email\":null}', '', 0, NULL, 'sandbox', 0),
(4, 3, 'Stripe', 'stripe', 'images/payment-gateways/stripe.png', 0, NULL, NULL, '{\"publishable_key\":null,\"secret_key\":null,\"webhook_secret\":null}', '[{\"type\": \"event\", \"label\": \"Webhook Event\", \"content\": \"checkout.session.completed\"},\n{\"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/stripe\" }]', 0, NULL, NULL, 0),
(5, 5, 'Razorpay', 'razorpay', 'images/payment-gateways/razorpay.png', 0, NULL, NULL, '{\"key_id\":null,\"key_secret\":null,\"webhook_secret\":null}', '[{\"type\": \"event\", \"label\": \"Webhook Event\", \"content\": \"payment.captured\"},{ \"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/razorpay\"}]', 0, NULL, NULL, 0),
(6, 4, 'Paystack', 'paystack', 'images/payment-gateways/paystack.png', 0, 'NGN', 1606.400000000, '{\"public_key\":null,\"secret_key\":null}', '[{ \"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/paystack\" }]', 0, NULL, NULL, 0),
(7, 6, 'Mollie', 'mollie', 'images/payment-gateways/mollie.png', 0, NULL, NULL, '{\"api_key\":null}', NULL, 0, NULL, NULL, 0),
(8, 7, 'Coinbase', 'coinbase', 'images/payment-gateways/coinbase.png', 0, NULL, NULL, '{\"api_key\":null,\"webhook_shared_secret\":null}', '[{ \"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/coinbase\" }]', 0, NULL, NULL, 0),
(9, 8, 'Coingate', 'coingate', 'images/payment-gateways/coingate.png', 0, NULL, NULL, '{\"auth_token\":null}', NULL, 0, NULL, NULL, 0),
(10, 9, 'Flutterwave', 'flutterwave', 'images/payment-gateways/flutterwave.png', 0, NULL, NULL, '{\"public_key\":null,\"secret_key\":null,\"secret_hash\":null}', '[{ \"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/flutterwave\" }]', 0, NULL, NULL, 0),
(11, 13, 'Bank Wire', 'bankwire', 'images/payment-gateways/bankwire.png', 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0),
(12, 10, 'Midtrans', 'midtrans', 'images/payment-gateways/midtrans.png', 0, 'IDR', 15846.100000000, '{\"server_key\":null}', '[{\"type\": \"route\", \"label\": \"Finish URL\", \"content\": \"payments/ipn/midtrans\"},\n{\"type\": \"route\", \"label\": \"Unfinish URL\", \"content\": \"payments/ipn/midtrans\"},\n{\"type\": \"route\", \"label\": \"Error Payment URL\", \"content\":\"payments/ipn/midtrans\"}]', 0, NULL, 'sandbox', 0),
(13, 11, 'Xendit', 'xendit', 'images/payment-gateways/xendit.png', 0, 'IDR', 15846.100000000, '{\"api_secret_key\":null,\"webhook_verification_token\":null}', '[{\"type\": \"event\", \"label\": \"Webhook Event\", \"content\": \"invoices.paid\"},\r\n{\"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/xendit\" }]', 0, NULL, NULL, 0),
(14, 12, 'Iyzico', 'iyzico', 'images/payment-gateways/iyzipay.png', 0, NULL, NULL, '{\"api_key\":null,\"secret_key\":null}', '[{\"type\": \"route\", \"label\": \"Webhook Endpoint\", \"content\": \"payments/webhooks/iyzipay\" }]', 0, NULL, 'sandbox', 0);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` enum('week','month','year','lifetime') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double DEFAULT NULL,
  `author_earning_percentage` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `downloads` bigint UNSIGNED DEFAULT NULL,
  `custom_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `premium_earnings`
--

CREATE TABLE `premium_earnings` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `item_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `author_earning` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `license_type` tinyint(1) NOT NULL COMMENT '1:Regular 2:Extended',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `support_expiry_at` datetime DEFAULT NULL,
  `is_downloaded` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Active 2:Refunded 3:Cancelled	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `earnings` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_earnings`
--

CREATE TABLE `referral_earnings` (
  `id` bigint UNSIGNED NOT NULL,
  `referral_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `author_earning` double NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Active 2:Refunded 3:Cancelled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Accepted 3:Declined',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refund_replies`
--

CREATE TABLE `refund_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `refund_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviewers`
--

CREATE TABLE `reviewers` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `google2fa_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Disabled, 1: Active',
  `google2fa_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviewer_password_resets`
--

CREATE TABLE `reviewer_password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `license_type` tinyint(1) NOT NULL COMMENT '1:Regular 2:Extended',
  `price` double NOT NULL,
  `buyer_fee` double DEFAULT '0',
  `buyer_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_fee` double DEFAULT '0',
  `author_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_earning` double DEFAULT '0',
  `country` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Active 2:Refunded 3:Cancelled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'general', '{\"site_name\":\"Marketbob\",\"site_url\":\"\",\"date_format\":\"10\",\"timezone\":\"America\\/New_York\",\"contact_email\":null}'),
(2, 'smtp', '{\"status\":0,\"mailer\":\"smtp\",\"host\":null,\"port\":null,\"username\":null,\"password\":null,\"encryption\":\"tls\",\"from_email\":null,\"from_name\":null}'),
(3, 'actions', '{\"registration\":1,\"email_verification\":0,\"become_an_author\":1,\"api\":1,\"gdpr_cookie\":1,\"force_ssl\":0,\"blog\":1,\"tickets\":1,\"refunds\":1,\"contact_page\":0}'),
(4, 'currency', '{\"code\":\"USD\",\"symbol\":\"$\",\"position\":\"1\"}'),
(5, 'deposit', '{\"status\":1,\"minimum\":\"10\",\"maximum\":\"1000\"}'),
(6, 'seo', '{\"title\":\"Multi-Vendor Digital Marketplace\",\"description\":\"WordPress Templates, Plugins, PHP Scripts, and Graphics Digital Marketplace\",\"keywords\":\"JavaScript, PHP Scripts, CSS, HTML5, Site Templates, WordPress Themes, Plugins, Mobile Apps, Graphics, Prints, Brochures, Flyers, Resumes,\"}'),
(7, 'system_admin', '{\"colors\":{\"primary_color\":\"#4caf50\",\"secondary_color\":\"#222222\",\"background_color\":\"#f9fafb\",\"sidebar_background_color\":\"#222222\",\"navbar_background_color\":\"#ffffff\"}}'),
(8, 'system_reviewer', '{\"colors\":{\"primary_color\":\"#4caf50\",\"secondary_color\":\"#222222\",\"background_color\":\"#f9fafb\",\"sidebar_background_color\":\"#222222\",\"navbar_background_color\":\"#ffffff\"}}'),
(9, 'kyc', '{\"status\":1,\"selfie_verification\":1,\"required\":0,\"id_front_image\":\"images\\/kyc\\/F6uxReOavrBbRnr_1708719956.svg\",\"id_back_image\":\"images\\/kyc\\/lDNgqlaFCClbRaA_1708720002.svg\",\"passport_image\":\"images\\/kyc\\/QLEDc8sXn6h2e7E_1708729601.svg\",\"selfie_image\":\"images\\/kyc\\/5CwgvmI9gcd067i_1708720379.svg\"}'),
(10, 'item', '{\"maximum_tags\":\"15\",\"minimum_price\":\"1.00\",\"maximum_price\":\"5000.00\",\"buy_now_button\":1,\"free_item_option\":1,\"free_item_total_downloads\":1,\"free_items_require_login\":1,\"external_file_link_option\":1,\"reviews_status\":1,\"comments_status\":1,\"changelogs_status\":1,\"support_status\":1,\"discount_status\":1,\"discount_max_percentage\":\"70\",\"discount_max_days\":\"20\",\"discount_tb\":\"30\",\"discount_tb_pch\":\"30\",\"trending_number\":\"20\",\"best_selling_number\":\"20\",\"max_files\":\"12\",\"max_file_size\":314572800,\"convert_images_webp\":\"1\",\"file_duration\":\"24\",\"adding_require_review\":1,\"updating_require_review\":0}'),
(11, 'referral', '{\"status\":1,\"percentage\":\"20\"}'),
(12, 'profile', '{\"default_avatar\":\"images\\/profiles\\/default\\/fymG7nwhBiXI12c_1733601562.png\",\"default_cover\":\"images\\/profiles\\/default\\/bjhPVvmXixCNqAH_1733601554.png\"}'),
(13, 'language', '{\"code\":\"en\",\"direction\":\"ltr\"}'),
(14, 'links', '{\"terms_of_use_link\":\"\\/terms-of-use\",\"author_terms_link\":\"\\/author-terms\",\"referral_terms_link\":\"\\/referral-program-terms\",\"licenses_terms_link\":\"\\/licenses-terms\",\"free_items_policy_link\":\"\\/free-items-policy\",\"gdpr_cookie_policy_link\":\"\\/gdpr-policy\"}'),
(15, 'announcement', '{\"body\":\"Unlocking Joy: 50% Off On WordPress Themes\",\"button_title\":\"Get It Now >\",\"button_link\":\"\\/\",\"background_color\":\"#4caf50\",\"button_background_color\":\"#ffffff\",\"button_text_color\":\"#4caf50\",\"status\":1}'),
(16, 'home_page', '{\"trending_items_refresh\":\"24\",\"best_selling_items_refresh\":\"168\",\"sale_items_refresh\":\"24\",\"latest_items_refresh\":\"1\"}'),
(17, 'cronjob', '{\"key\":\"\",\"last_execution\":\"\"}'),
(18, 'ticket', '{\"file_types\":\"jpeg,jpg,png,pdf\",\"max_files\":\"5\",\"max_file_size\":\"10\"}'),
(19, 'maintenance', '{\"status\":0,\"title\":\"Under Maintenance\",\"body\":\"Our site is currently undergoing scheduled maintenance to enhance your browsing experience. We apologize for any inconvenience and appreciate your patience. Please check back soon!\",\"icon\":\"images\\/maintenance\\/h835EHWOXolE4Pd_1718820166.jpg\"}'),
(20, 'social_links', '{\"facebook\":\"\\/\",\"x\":\"\\/\",\"youtube\":\"\\/\",\"linkedin\":\"\\/\",\"instagram\":\"\\/\",\"pinterest\":\"\\/\"}'),
(21, 'premium', '{\"status\":1,\"terms_link\":\"\\/premium-terms\"}');

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

CREATE TABLE `statements` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `buyer_fee` double DEFAULT '0',
  `author_fee` double DEFAULT '0',
  `tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total` double DEFAULT NULL,
  `type` tinyint NOT NULL COMMENT '1:credit 2:debit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `storage_providers`
--

CREATE TABLE `storage_providers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `processor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storage_providers`
--

INSERT INTO `storage_providers` (`id`, `name`, `alias`, `processor`, `credentials`, `created_at`, `updated_at`) VALUES
(1, 'Local', 'local', 'App\\Http\\Controllers\\Storage\\LocalController', NULL, '2024-03-06 00:23:02', '2024-03-06 00:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `total_downloads` bigint UNSIGNED NOT NULL DEFAULT '0',
  `expiry_at` datetime DEFAULT NULL,
  `last_notification_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `views` bigint NOT NULL DEFAULT '0',
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `slug`, `title`, `description`, `category_id`, `views`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'WordPress', 'wordpress', NULL, NULL, 1, 19, 1, '2024-03-05 19:49:25', '2024-12-04 18:54:20'),
(2, 'Shopify', 'shopify', NULL, NULL, 1, 8, 5, '2024-03-05 19:49:34', '2024-12-04 18:54:20'),
(3, 'HTML/CSS', 'html-css', NULL, NULL, 1, 12, 2, '2024-03-05 19:49:52', '2024-12-04 18:54:20'),
(4, 'Bootstrap', 'bootstrap', NULL, NULL, 1, 10, 3, '2024-03-05 19:51:05', '2024-12-04 18:54:20'),
(5, 'Blogger', 'blogger', NULL, NULL, 1, 7, 4, '2024-03-05 19:51:15', '2024-12-04 18:54:20'),
(6, 'PHP Scripts', 'php-scripts', NULL, NULL, 2, 22, 6, '2024-03-05 19:51:26', '2024-12-04 18:54:20'),
(7, 'JavaScript', 'javascript', NULL, NULL, 2, 17, 7, '2024-03-05 19:51:35', '2024-12-04 18:54:20'),
(8, 'CSS', 'css', NULL, NULL, 2, 9, 8, '2024-03-05 19:52:02', '2024-12-06 13:04:23'),
(9, 'HTML5', 'html5', NULL, NULL, 2, 10, 9, '2024-03-05 19:52:08', '2024-12-04 18:54:20'),
(10, '.NET', 'net', NULL, NULL, 2, 7, 10, '2024-03-05 19:52:15', '2024-12-04 18:54:20'),
(11, 'WordPress Plugins', 'wordpress-plugins', NULL, NULL, 3, 12, 11, '2024-03-05 19:52:26', '2024-12-04 18:54:20'),
(12, 'Magento Extensions', 'magento-extensions', NULL, NULL, 3, 8, 12, '2024-03-05 19:52:37', '2024-12-04 18:54:20'),
(13, 'OpenCart', 'opencart', NULL, NULL, 3, 8, 13, '2024-03-05 19:52:47', '2024-12-04 18:54:20'),
(14, 'Joomla', 'joomla', NULL, NULL, 3, 8, 14, '2024-03-05 19:53:25', '2024-12-04 18:54:20'),
(15, 'Drupal', 'drupal', NULL, NULL, 3, 8, 15, '2024-03-05 19:53:31', '2024-12-04 18:54:20'),
(16, 'Icons', 'icons', NULL, NULL, 4, 12, 16, '2024-03-05 19:53:42', '2024-12-04 18:54:20'),
(17, 'Objects', 'objects', NULL, NULL, 4, 10, 17, '2024-03-05 19:53:50', '2024-12-04 18:54:20'),
(18, 'Patterns', 'patterns', NULL, NULL, 4, 7, 18, '2024-03-05 19:54:02', '2024-12-04 18:54:20'),
(19, 'Illustrations', 'illustrations', NULL, NULL, 4, 7, 19, '2024-03-05 19:54:08', '2024-12-04 18:54:21'),
(20, 'Web Elements', 'web-elements', NULL, NULL, 4, 7, 20, '2024-03-05 19:54:20', '2024-12-04 18:54:21'),
(21, 'Android', 'android', NULL, NULL, 5, 15, 21, '2024-03-05 19:54:41', '2024-12-04 18:54:21'),
(22, 'IOS', 'ios', NULL, NULL, 5, 14, 22, '2024-03-05 19:54:48', '2024-12-04 18:54:21'),
(23, 'Native Web', 'native-web', NULL, NULL, 5, 7, 23, '2024-03-05 19:55:01', '2024-12-04 18:54:21'),
(24, 'Unity', 'unity', NULL, NULL, 5, 8, 24, '2024-03-05 19:55:09', '2024-12-04 18:54:21'),
(28, 'Effects', 'effects', NULL, NULL, 7, 9, 25, '2024-09-05 10:07:34', '2024-12-04 18:54:20'),
(29, 'Graphics', 'graphics', NULL, NULL, 7, 6, 26, '2024-09-05 10:07:44', '2024-12-04 18:54:20'),
(30, 'Music', 'music', NULL, NULL, 8, 8, 27, '2024-09-05 10:08:34', '2024-12-04 18:54:20'),
(31, 'Sound Effects', 'sound-effects', NULL, NULL, 8, 6, 28, '2024-09-05 10:08:52', '2024-12-04 18:54:20');

-- --------------------------------------------------------

--
-- Table structure for table `support_earnings`
--

CREATE TABLE `support_earnings` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `days` bigint UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `buyer_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_fee` double DEFAULT '0',
  `author_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_earning` double NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1:Active 2:Refunded 3:Cancelled',
  `support_expiry_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_periods`
--

CREATE TABLE `support_periods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `days` bigint UNSIGNED NOT NULL,
  `percentage` int UNSIGNED NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_periods`
--

INSERT INTO `support_periods` (`id`, `name`, `title`, `days`, `percentage`, `is_default`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, '6 months', '6 months of support', 182, 0, 1, 0, '2024-07-09 13:46:39', '2024-07-09 17:43:11'),
(2, '12 months', '12 months of support', 365, 11, 0, 0, '2024-07-09 13:47:00', '2024-07-09 17:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `avatar`, `title`, `body`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'John Carter', 'images/sections/testimonials/GLaTeOUn8DsdOcs_1733598830.jpg', 'Web Developer', 'Marketbob is a goldmine for web developers like me. The platform\'s extensive collection of PHP scripts and HTML templates has been a lifesaver, saving me time and effort. The quality and variety are unmatched, making Marketbob my top choice for digital assets.', 0, '2024-03-05 19:35:15', '2024-12-07 13:13:50'),
(2, 'Emma Carter', 'images/sections/testimonials/s1Qvpw5INDTo89B_1733598825.jpg', 'Graphic Designer', 'As a graphic designer, Marketbob is a dream come true. The marketplace not only showcases my work to a broader audience but also provides a seamless platform for selling graphics. It\'s a go-to place for both buyers and sellers in the creative industry.', 0, '2024-03-05 19:36:15', '2024-12-07 13:13:45'),
(3, 'Amanda Evans', 'images/sections/testimonials/mWN8YLCJBoDNFcT_1733598820.jpg', 'Startup Founder', 'Marketbob played a pivotal role in our startup journey. We found crucial PHP scripts on the marketplace, significantly speeding up our development process. The reliability and efficiency of Marketbob have been instrumental in our successful launch.', 0, '2024-03-05 19:36:43', '2024-12-07 13:13:40'),
(4, 'Carlos Martinez', 'images/sections/testimonials/qegCxlxRp2U6T2N_1733598815.jpg', 'E-commerce Entrepreneur', 'Marketbob is a hidden gem for e-commerce businesses. The platform\'s rich variety of graphics and templates allowed me to enhance my online store\'s visual appeal. Marketbob is now my first choice for sourcing digital assets for my business.', 0, '2024-03-05 19:37:15', '2024-12-07 13:13:35'),
(5, 'Linda Thompson', 'images/sections/testimonials/OiloYYZTBwP9aQQ_1733598810.jpg', 'Marketing Manager', 'Marketbob has become an indispensable tool for our marketing team. The marketplace\'s extensive range of templates and scripts empowered us to elevate our online marketing strategies. Marketbob is a must-have resource for any marketing professional navigating the digital landscape.', 0, '2024-03-05 19:37:54', '2024-12-07 13:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `name`, `alias`, `version`, `preview_image`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Basic', 'basic', '1.0', 'themes/basic/images/preview.jpg', 'Basic theme offers a sleek and modern design, prioritizing user-friendly navigation and aesthetics. ', '2023-09-29 22:14:13', '2023-09-29 22:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ticket_category_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1:Opened 2:Closed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_categories`
--

INSERT INTO `ticket_categories` (`id`, `name`, `status`, `sort_id`, `created_at`, `updated_at`) VALUES
(1, 'My Items', 1, 1, '2024-05-06 20:30:10', '2024-05-06 20:36:10'),
(2, 'Payments', 1, 2, '2024-05-06 20:31:11', '2024-05-06 20:36:00'),
(3, 'Purchases', 1, 3, '2024-05-06 20:31:29', '2024-05-06 20:33:30'),
(4, 'Withdrawals', 1, 4, '2024-05-06 20:31:45', '2024-05-06 20:33:30'),
(5, 'Transactions', 1, 5, '2024-05-06 20:32:45', '2024-05-06 20:33:33'),
(6, 'Refunds', 1, 6, '2024-05-06 20:32:50', '2024-05-06 20:33:33'),
(7, 'Other', 1, 7, '2024-05-06 20:33:39', '2024-05-06 20:33:42');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_reply_attachments`
--

CREATE TABLE `ticket_reply_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_reply_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `top_nav_links`
--

CREATE TABLE `top_nav_links` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Internal 2:External',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `order` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `top_nav_links`
--

INSERT INTO `top_nav_links` (`id`, `name`, `link`, `link_type`, `parent_id`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Privacy policy', '/privacy-policy', 1, NULL, 1, '2024-03-05 20:15:18', '2024-05-03 21:06:13'),
(2, 'Terms of use', '/terms-of-use', 1, NULL, 3, '2024-03-05 20:16:02', '2024-03-17 03:45:17'),
(3, 'Refund Policy', '/refund-policy', 1, NULL, 2, '2024-03-05 20:16:09', '2024-03-17 03:44:54'),
(6, 'API Docs', '/api-docs', 2, NULL, 4, '2024-04-28 19:10:33', '2024-12-03 17:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `fees` double DEFAULT '0',
  `tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total` double NOT NULL,
  `payment_gateway_id` bigint UNSIGNED DEFAULT NULL,
  `payment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('purchase','support_purchase','support_extend','deposit','subscription') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `support` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `purchase_id` bigint UNSIGNED DEFAULT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0:Unpaid 1:Pending 2:Paid 3:Cancelled',
  `cancellation_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED DEFAULT NULL,
  `license_type` tinyint NOT NULL DEFAULT '1' COMMENT '1:Regular 2:Extended',
  `price` double NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `total` double(8,2) NOT NULL,
  `support` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translates`
--

CREATE TABLE `translates` (
  `id` bigint UNSIGNED NOT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translates`
--

INSERT INTO `translates` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'The :attribute field must be accepted.', 'The :attribute field must be accepted.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(2, 'The :attribute field must be accepted when :other is :value.', 'The :attribute field must be accepted when :other is :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(3, 'The :attribute field must be a valid URL.', 'The :attribute field must be a valid URL.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(4, 'The :attribute field must be a date after :date.', 'The :attribute field must be a date after :date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(5, 'The :attribute field must be a date after or equal to :date.', 'The :attribute field must be a date after or equal to :date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(6, 'The :attribute field must only contain letters.', 'The :attribute field must only contain letters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(7, 'The :attribute field must only contain letters, numbers, dashes, and underscores.', 'The :attribute field must only contain letters, numbers, dashes, and underscores.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(8, 'The :attribute field must only contain letters and numbers.', 'The :attribute field must only contain letters and numbers.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(9, 'The :attribute field must be an array.', 'The :attribute field must be an array.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(10, 'The :attribute field must only contain single-byte alphanumeric characters and symbols.', 'The :attribute field must only contain single-byte alphanumeric characters and symbols.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(11, 'The :attribute field must be a date before :date.', 'The :attribute field must be a date before :date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(12, 'The :attribute field must be a date before or equal to :date.', 'The :attribute field must be a date before or equal to :date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(13, 'The :attribute field must have between :min and :max items.', 'The :attribute field must have between :min and :max items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(14, 'The :attribute field must be between :min and :max kilobytes.', 'The :attribute field must be between :min and :max kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(15, 'The :attribute field must be between :min and :max.', 'The :attribute field must be between :min and :max.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(16, 'The :attribute field must be between :min and :max characters.', 'The :attribute field must be between :min and :max characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(17, 'The :attribute field must be true or false.', 'The :attribute field must be true or false.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(18, 'The :attribute field contains an unauthorized value.', 'The :attribute field contains an unauthorized value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(19, 'The :attribute field confirmation does not match.', 'The :attribute field confirmation does not match.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(20, 'The password is incorrect.', 'The password is incorrect.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(21, 'The :attribute field must be a valid date.', 'The :attribute field must be a valid date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(22, 'The :attribute field must be a date equal to :date.', 'The :attribute field must be a date equal to :date.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(23, 'The :attribute field must match the format :format.', 'The :attribute field must match the format :format.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(24, 'The :attribute field must have :decimal decimal places.', 'The :attribute field must have :decimal decimal places.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(25, 'The :attribute field must be declined.', 'The :attribute field must be declined.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(26, 'The :attribute field must be declined when :other is :value.', 'The :attribute field must be declined when :other is :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(27, 'The :attribute field and :other must be different.', 'The :attribute field and :other must be different.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(28, 'The :attribute field must be :digits digits.', 'The :attribute field must be :digits digits.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(29, 'The :attribute field must be between :min and :max digits.', 'The :attribute field must be between :min and :max digits.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(30, 'The :attribute field has invalid image dimensions.', 'The :attribute field has invalid image dimensions.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(31, 'The :attribute field has a duplicate value.', 'The :attribute field has a duplicate value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(32, 'The :attribute field must not end with one of the following: :values.', 'The :attribute field must not end with one of the following: :values.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(33, 'The :attribute field must not start with one of the following: :values.', 'The :attribute field must not start with one of the following: :values.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(34, 'The :attribute field must be a valid email address.', 'The :attribute field must be a valid email address.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(35, 'The :attribute field must be a valid username.', 'The :attribute field must be a valid username.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(36, 'The :attribute field must end with one of the following: :values.', 'The :attribute field must end with one of the following: :values.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(37, 'The selected :attribute is invalid.', 'The selected :attribute is invalid.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(38, 'The :attribute field must be a file.', 'The :attribute field must be a file.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(39, 'The :attribute field must have a value.', 'The :attribute field must have a value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(40, 'The :attribute field must have more than :value items.', 'The :attribute field must have more than :value items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(41, 'The :attribute field must be greater than :value kilobytes.', 'The :attribute field must be greater than :value kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(42, 'The :attribute field must be greater than :value.', 'The :attribute field must be greater than :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(43, 'The :attribute field must be greater than :value characters.', 'The :attribute field must be greater than :value characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(44, 'The :attribute field must have :value items or more.', 'The :attribute field must have :value items or more.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(45, 'The :attribute field must be greater than or equal to :value kilobytes.', 'The :attribute field must be greater than or equal to :value kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(46, 'The :attribute field must be greater than or equal to :value.', 'The :attribute field must be greater than or equal to :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(47, 'The :attribute field must be greater than or equal to :value characters.', 'The :attribute field must be greater than or equal to :value characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(48, 'The :attribute field must be an image.', 'The :attribute field must be an image.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(49, 'The :attribute field must exist in :other.', 'The :attribute field must exist in :other.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(50, 'The :attribute field must be an integer.', 'The :attribute field must be an integer.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(51, 'The :attribute field must be a valid IP address.', 'The :attribute field must be a valid IP address.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(52, 'The :attribute field must be a valid IPv4 address.', 'The :attribute field must be a valid IPv4 address.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(53, 'The :attribute field must be a valid IPv6 address.', 'The :attribute field must be a valid IPv6 address.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(54, 'The :attribute field must be a valid JSON string.', 'The :attribute field must be a valid JSON string.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(55, 'The :attribute field must be lowercase.', 'The :attribute field must be lowercase.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(56, 'The :attribute field must have less than :value items.', 'The :attribute field must have less than :value items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(57, 'The :attribute field must be less than :value kilobytes.', 'The :attribute field must be less than :value kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(58, 'The :attribute field must be less than :value.', 'The :attribute field must be less than :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(59, 'The :attribute field must be less than :value characters.', 'The :attribute field must be less than :value characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(60, 'The :attribute field must not have more than :value items.', 'The :attribute field must not have more than :value items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(61, 'The :attribute field must be less than or equal to :value kilobytes.', 'The :attribute field must be less than or equal to :value kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(62, 'The :attribute field must be less than or equal to :value.', 'The :attribute field must be less than or equal to :value.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(63, 'The :attribute field must be less than or equal to :value characters.', 'The :attribute field must be less than or equal to :value characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(64, 'The :attribute field must be a valid MAC address.', 'The :attribute field must be a valid MAC address.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(65, 'The :attribute field must not have more than :max items.', 'The :attribute field must not have more than :max items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(66, 'The :attribute field must not be greater than :max kilobytes.', 'The :attribute field must not be greater than :max kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(67, 'The :attribute field must not be greater than :max.', 'The :attribute field must not be greater than :max.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(68, 'The :attribute field must not be greater than :max characters.', 'The :attribute field must not be greater than :max characters.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(69, 'The :attribute field must not have more than :max digits.', 'The :attribute field must not have more than :max digits.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(70, 'The :attribute field must be a file of type: :values.', 'The :attribute field must be a file of type: :values.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(71, 'The :attribute field must have at least :min items.', 'The :attribute field must have at least :min items.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(72, 'The :attribute field must be at least :min kilobytes.', 'The :attribute field must be at least :min kilobytes.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(73, 'The :attribute field must be at least :min.', 'The :attribute field must be at least :min.', '2024-05-15 20:57:40', '2024-05-15 20:57:40'),
(74, 'The :attribute field must be at least :min characters.', 'The :attribute field must be at least :min characters.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(75, 'The :attribute field must have at least :min digits.', 'The :attribute field must have at least :min digits.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(76, 'The :attribute field must be missing.', 'The :attribute field must be missing.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(77, 'The :attribute field must be missing when :other is :value.', 'The :attribute field must be missing when :other is :value.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(78, 'The :attribute field must be missing unless :other is :value.', 'The :attribute field must be missing unless :other is :value.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(79, 'The :attribute field must be missing when :values is present.', 'The :attribute field must be missing when :values is present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(80, 'The :attribute field must be missing when :values are present.', 'The :attribute field must be missing when :values are present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(81, 'The :attribute field must be a multiple of :value.', 'The :attribute field must be a multiple of :value.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(82, 'The :attribute field format is invalid.', 'The :attribute field format is invalid.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(83, 'The :attribute field must be a number.', 'The :attribute field must be a number.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(84, 'The :attribute field must contain at least one letter.', 'The :attribute field must contain at least one letter.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(85, 'The :attribute field must contain at least one uppercase and one lowercase letter.', 'The :attribute field must contain at least one uppercase and one lowercase letter.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(86, 'The :attribute field must contain at least one number.', 'The :attribute field must contain at least one number.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(87, 'The :attribute field must contain at least one symbol.', 'The :attribute field must contain at least one symbol.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(88, 'The given :attribute has appeared in a data leak. Please choose a different :attribute.', 'The given :attribute has appeared in a data leak. Please choose a different :attribute.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(89, 'The :attribute field must be present.', 'The :attribute field must be present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(90, 'The :attribute field is prohibited.', 'The :attribute field is prohibited.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(91, 'The :attribute field is prohibited when :other is :value.', 'The :attribute field is prohibited when :other is :value.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(92, 'The :attribute field is prohibited unless :other is in :values.', 'The :attribute field is prohibited unless :other is in :values.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(93, 'The :attribute field prohibits :other from being present.', 'The :attribute field prohibits :other from being present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(94, 'The :attribute field is required.', 'The :attribute field is required.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(95, 'The :attribute field must contain entries for: :values.', 'The :attribute field must contain entries for: :values.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(96, 'The :attribute field is required when :other is :value.', 'The :attribute field is required when :other is :value.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(97, 'The :attribute field is required when :other is accepted.', 'The :attribute field is required when :other is accepted.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(98, 'The :attribute field is required unless :other is in :values.', 'The :attribute field is required unless :other is in :values.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(99, 'The :attribute field is required when :values is present.', 'The :attribute field is required when :values is present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(100, 'The :attribute field is required when :values are present.', 'The :attribute field is required when :values are present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(101, 'The :attribute field is required when :values is not present.', 'The :attribute field is required when :values is not present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(102, 'The :attribute field is required when none of :values are present.', 'The :attribute field is required when none of :values are present.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(103, 'The :attribute field must match :other.', 'The :attribute field must match :other.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(104, 'The :attribute field must contain :size items.', 'The :attribute field must contain :size items.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(105, 'The :attribute field must be :size kilobytes.', 'The :attribute field must be :size kilobytes.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(106, 'The :attribute field must be :size.', 'The :attribute field must be :size.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(107, 'The :attribute field must be :size characters.', 'The :attribute field must be :size characters.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(108, 'The :attribute field must start with one of the following: :values.', 'The :attribute field must start with one of the following: :values.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(109, 'The :attribute field must be a string.', 'The :attribute field must be a string.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(110, 'The :attribute field must be a valid timezone.', 'The :attribute field must be a valid timezone.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(111, 'The :attribute has already been taken.', 'The :attribute has already been taken.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(112, 'The :attribute failed to upload.', 'The :attribute failed to upload.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(113, 'The :attribute field must be uppercase.', 'The :attribute field must be uppercase.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(114, 'The :attribute field must be a valid ULID.', 'The :attribute field must be a valid ULID.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(115, 'The :attribute field must be a valid UUID.', 'The :attribute field must be a valid UUID.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(116, 'captcha', 'captcha', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(117, 'terms of service', 'terms of service', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(118, 'Phone number must be a valid 10-digit phone number.', 'Phone number must be a valid 10-digit phone number.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(119, 'The :attribute contains blocked patterns.', 'The :attribute contains blocked patterns.', '2024-05-15 20:57:41', '2024-05-15 20:57:41'),
(120, 'These credentials do not match our records.', 'These credentials do not match our records.', '2024-05-15 20:58:09', '2024-05-16 10:16:16'),
(121, 'The provided password is incorrect.', 'The provided password is incorrect.', '2024-05-15 20:58:09', '2024-05-15 20:58:09'),
(122, 'Too many login attempts. Please try again in :seconds seconds.', 'Too many login attempts. Please try again in :seconds seconds.', '2024-05-15 20:58:09', '2024-05-15 20:58:09'),
(123, 'Your password has been reset!', 'Your password has been reset!', '2024-05-15 20:58:20', '2024-05-15 20:58:20'),
(124, 'We have emailed your password reset link!', 'We have emailed your password reset link!', '2024-05-15 20:58:20', '2024-05-15 20:58:20'),
(125, 'Please wait before retrying.', 'Please wait before retrying.', '2024-05-15 20:58:20', '2024-05-15 20:58:20'),
(126, 'This password reset token is invalid.', 'This password reset token is invalid.', '2024-05-15 20:58:20', '2024-05-15 20:58:20'),
(127, 'We can\'t find a user with that email address.', 'We can\'t find a user with that email address.', '2024-05-15 20:58:20', '2024-05-15 20:58:20'),
(128, 'Previous', 'Previous', '2024-05-15 20:58:30', '2024-05-15 20:58:30'),
(129, 'Next', 'Next', '2024-05-15 20:58:30', '2024-05-15 20:58:30'),
(130, 'Afghanistan', 'Afghanistan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(131, 'Albania', 'Albania', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(132, 'Algeria', 'Algeria', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(133, 'American Samoa', 'American Samoa', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(134, 'Andorra', 'Andorra', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(135, 'Angola', 'Angola', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(136, 'Anguilla', 'Anguilla', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(137, 'Antarctica', 'Antarctica', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(138, 'Antigua and Barbuda', 'Antigua and Barbuda', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(139, 'Argentina', 'Argentina', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(140, 'Armenia', 'Armenia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(141, 'Aruba', 'Aruba', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(142, 'Australia', 'Australia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(143, 'Austria', 'Austria', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(144, 'Azerbaijan', 'Azerbaijan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(145, 'Bahamas', 'Bahamas', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(146, 'Bahrain', 'Bahrain', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(147, 'Bangladesh', 'Bangladesh', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(148, 'Barbados', 'Barbados', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(149, 'Belarus', 'Belarus', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(150, 'Belgium', 'Belgium', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(151, 'Belize', 'Belize', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(152, 'Benin', 'Benin', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(153, 'Bermuda', 'Bermuda', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(154, 'Bhutan', 'Bhutan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(155, 'Bolivia', 'Bolivia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(156, 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(157, 'Botswana', 'Botswana', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(158, 'Bouvet Island', 'Bouvet Island', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(159, 'Brazil', 'Brazil', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(160, 'British Antarctic Territory', 'British Antarctic Territory', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(161, 'British Indian Ocean Territory', 'British Indian Ocean Territory', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(162, 'British Virgin Islands', 'British Virgin Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(163, 'Brunei', 'Brunei', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(164, 'Bulgaria', 'Bulgaria', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(165, 'Burkina Faso', 'Burkina Faso', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(166, 'Burundi', 'Burundi', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(167, 'Cambodia', 'Cambodia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(168, 'Cameroon', 'Cameroon', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(169, 'Canada', 'Canada', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(170, 'Canton and Enderbury Islands', 'Canton and Enderbury Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(171, 'Cape Verde', 'Cape Verde', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(172, 'Cayman Islands', 'Cayman Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(173, 'Central African Republic', 'Central African Republic', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(174, 'Chad', 'Chad', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(175, 'Chile', 'Chile', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(176, 'China', 'China', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(177, 'Christmas Island', 'Christmas Island', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(178, 'Cocos [Keeling] Islands', 'Cocos [Keeling] Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(179, 'Colombia', 'Colombia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(180, 'Comoros', 'Comoros', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(181, 'Congo - Brazzaville', 'Congo - Brazzaville', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(182, 'Congo - Kinshasa', 'Congo - Kinshasa', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(183, 'Cook Islands', 'Cook Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(184, 'Costa Rica', 'Costa Rica', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(185, 'Croatia', 'Croatia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(186, 'Cuba', 'Cuba', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(187, 'Cyprus', 'Cyprus', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(188, 'Czech Republic', 'Czech Republic', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(189, 'Côte d’Ivoire', 'Côte d’Ivoire', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(190, 'Denmark', 'Denmark', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(191, 'Djibouti', 'Djibouti', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(192, 'Dominica', 'Dominica', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(193, 'Dominican Republic', 'Dominican Republic', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(194, 'Dronning Maud Land', 'Dronning Maud Land', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(195, 'East Germany', 'East Germany', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(196, 'Ecuador', 'Ecuador', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(197, 'Egypt', 'Egypt', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(198, 'El Salvador', 'El Salvador', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(199, 'Equatorial Guinea', 'Equatorial Guinea', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(200, 'Eritrea', 'Eritrea', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(201, 'Estonia', 'Estonia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(202, 'Ethiopia', 'Ethiopia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(203, 'Falkland Islands', 'Falkland Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(204, 'Faroe Islands', 'Faroe Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(205, 'Fiji', 'Fiji', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(206, 'Finland', 'Finland', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(207, 'France', 'France', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(208, 'French Guiana', 'French Guiana', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(209, 'French Polynesia', 'French Polynesia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(210, 'French Southern Territories', 'French Southern Territories', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(211, 'French Southern and Antarctic Territories', 'French Southern and Antarctic Territories', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(212, 'Gabon', 'Gabon', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(213, 'Gambia', 'Gambia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(214, 'Georgia', 'Georgia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(215, 'Germany', 'Germany', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(216, 'Ghana', 'Ghana', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(217, 'Gibraltar', 'Gibraltar', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(218, 'Greece', 'Greece', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(219, 'Greenland', 'Greenland', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(220, 'Grenada', 'Grenada', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(221, 'Guadeloupe', 'Guadeloupe', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(222, 'Guam', 'Guam', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(223, 'Guatemala', 'Guatemala', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(224, 'Guernsey', 'Guernsey', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(225, 'Guinea', 'Guinea', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(226, 'Guinea-Bissau', 'Guinea-Bissau', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(227, 'Guyana', 'Guyana', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(228, 'Haiti', 'Haiti', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(229, 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(230, 'Honduras', 'Honduras', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(231, 'Hong Kong SAR China', 'Hong Kong SAR China', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(232, 'Hungary', 'Hungary', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(233, 'Iceland', 'Iceland', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(234, 'India', 'India', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(235, 'Indonesia', 'Indonesia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(236, 'Iran', 'Iran', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(237, 'Iraq', 'Iraq', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(238, 'Ireland', 'Ireland', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(239, 'Isle of Man', 'Isle of Man', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(240, 'Israel', 'Israel', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(241, 'Italy', 'Italy', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(242, 'Jamaica', 'Jamaica', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(243, 'Japan', 'Japan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(244, 'Jersey', 'Jersey', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(245, 'Johnston Island', 'Johnston Island', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(246, 'Jordan', 'Jordan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(247, 'Kazakhstan', 'Kazakhstan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(248, 'Kenya', 'Kenya', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(249, 'Kiribati', 'Kiribati', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(250, 'Kuwait', 'Kuwait', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(251, 'Kyrgyzstan', 'Kyrgyzstan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(252, 'Laos', 'Laos', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(253, 'Latvia', 'Latvia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(254, 'Lebanon', 'Lebanon', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(255, 'Lesotho', 'Lesotho', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(256, 'Liberia', 'Liberia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(257, 'Libya', 'Libya', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(258, 'Liechtenstein', 'Liechtenstein', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(259, 'Lithuania', 'Lithuania', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(260, 'Luxembourg', 'Luxembourg', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(261, 'Macau SAR China', 'Macau SAR China', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(262, 'Macedonia', 'Macedonia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(263, 'Madagascar', 'Madagascar', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(264, 'Malawi', 'Malawi', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(265, 'Malaysia', 'Malaysia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(266, 'Maldives', 'Maldives', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(267, 'Mali', 'Mali', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(268, 'Malta', 'Malta', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(269, 'Marshall Islands', 'Marshall Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(270, 'Martinique', 'Martinique', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(271, 'Mauritania', 'Mauritania', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(272, 'Mauritius', 'Mauritius', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(273, 'Mayotte', 'Mayotte', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(274, 'Metropolitan France', 'Metropolitan France', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(275, 'Mexico', 'Mexico', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(276, 'Micronesia', 'Micronesia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(277, 'Midway Islands', 'Midway Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(278, 'Moldova', 'Moldova', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(279, 'Monaco', 'Monaco', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(280, 'Mongolia', 'Mongolia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(281, 'Montenegro', 'Montenegro', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(282, 'Montserrat', 'Montserrat', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(283, 'Morocco', 'Morocco', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(284, 'Mozambique', 'Mozambique', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(285, 'Myanmar [Burma]', 'Myanmar [Burma]', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(286, 'Namibia', 'Namibia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(287, 'Nauru', 'Nauru', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(288, 'Nepal', 'Nepal', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(289, 'Netherlands', 'Netherlands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(290, 'Netherlands Antilles', 'Netherlands Antilles', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(291, 'Neutral Zone', 'Neutral Zone', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(292, 'New Caledonia', 'New Caledonia', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(293, 'New Zealand', 'New Zealand', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(294, 'Nicaragua', 'Nicaragua', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(295, 'Niger', 'Niger', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(296, 'Nigeria', 'Nigeria', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(297, 'Niue', 'Niue', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(298, 'Norfolk Island', 'Norfolk Island', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(299, 'North Korea', 'North Korea', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(300, 'North Vietnam', 'North Vietnam', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(301, 'Northern Mariana Islands', 'Northern Mariana Islands', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(302, 'Norway', 'Norway', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(303, 'Oman', 'Oman', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(304, 'Pacific Islands Trust Territory', 'Pacific Islands Trust Territory', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(305, 'Pakistan', 'Pakistan', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(306, 'Palau', 'Palau', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(307, 'Palestinian Territories', 'Palestinian Territories', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(308, 'Panama', 'Panama', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(309, 'Panama Canal Zone', 'Panama Canal Zone', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(310, 'Papua New Guinea', 'Papua New Guinea', '2024-05-15 22:16:32', '2024-05-15 22:16:32'),
(311, 'Paraguay', 'Paraguay', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(312, 'People\'s Democratic Republic of Yemen', 'People\'s Democratic Republic of Yemen', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(313, 'Peru', 'Peru', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(314, 'Philippines', 'Philippines', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(315, 'Pitcairn Islands', 'Pitcairn Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(316, 'Poland', 'Poland', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(317, 'Portugal', 'Portugal', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(318, 'Puerto Rico', 'Puerto Rico', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(319, 'Qatar', 'Qatar', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(320, 'Romania', 'Romania', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(321, 'Russia', 'Russia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(322, 'Rwanda', 'Rwanda', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(323, 'Réunion', 'Réunion', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(324, 'Saint Barthélemy', 'Saint Barthélemy', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(325, 'Saint Helena', 'Saint Helena', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(326, 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(327, 'Saint Lucia', 'Saint Lucia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(328, 'Saint Martin', 'Saint Martin', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(329, 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(330, 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(331, 'Samoa', 'Samoa', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(332, 'San Marino', 'San Marino', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(333, 'Saudi Arabia', 'Saudi Arabia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(334, 'Senegal', 'Senegal', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(335, 'Serbia', 'Serbia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(336, 'Serbia and Montenegro', 'Serbia and Montenegro', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(337, 'Seychelles', 'Seychelles', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(338, 'Sierra Leone', 'Sierra Leone', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(339, 'Singapore', 'Singapore', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(340, 'Slovakia', 'Slovakia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(341, 'Slovenia', 'Slovenia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(342, 'Solomon Islands', 'Solomon Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(343, 'Somalia', 'Somalia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(344, 'South Africa', 'South Africa', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(345, 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(346, 'South Korea', 'South Korea', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(347, 'Spain', 'Spain', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(348, 'Sri Lanka', 'Sri Lanka', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(349, 'Sudan', 'Sudan', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(350, 'Suriname', 'Suriname', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(351, 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(352, 'Swaziland', 'Swaziland', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(353, 'Sweden', 'Sweden', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(354, 'Switzerland', 'Switzerland', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(355, 'Syria', 'Syria', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(356, 'São Tomé and Príncipe', 'São Tomé and Príncipe', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(357, 'Taiwan', 'Taiwan', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(358, 'Tajikistan', 'Tajikistan', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(359, 'Tanzania', 'Tanzania', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(360, 'Thailand', 'Thailand', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(361, 'Timor-Leste', 'Timor-Leste', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(362, 'Togo', 'Togo', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(363, 'Tokelau', 'Tokelau', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(364, 'Tonga', 'Tonga', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(365, 'Trinidad and Tobago', 'Trinidad and Tobago', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(366, 'Tunisia', 'Tunisia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(367, 'Turkey', 'Turkey', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(368, 'Turkmenistan', 'Turkmenistan', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(369, 'Turks and Caicos Islands', 'Turks and Caicos Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(370, 'Tuvalu', 'Tuvalu', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(371, 'U.S. Minor Outlying Islands', 'U.S. Minor Outlying Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(372, 'U.S. Miscellaneous Pacific Islands', 'U.S. Miscellaneous Pacific Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(373, 'U.S. Virgin Islands', 'U.S. Virgin Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(374, 'Uganda', 'Uganda', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(375, 'Ukraine', 'Ukraine', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(376, 'Union of Soviet Socialist Republics', 'Union of Soviet Socialist Republics', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(377, 'United Arab Emirates', 'United Arab Emirates', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(378, 'United Kingdom', 'United Kingdom', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(379, 'United States', 'United States', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(380, 'Unknown or Invalid Region', 'Unknown or Invalid Region', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(381, 'Uruguay', 'Uruguay', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(382, 'Uzbekistan', 'Uzbekistan', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(383, 'Vanuatu', 'Vanuatu', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(384, 'Vatican City', 'Vatican City', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(385, 'Venezuela', 'Venezuela', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(386, 'Vietnam', 'Vietnam', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(387, 'Wake Island', 'Wake Island', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(388, 'Wallis and Futuna', 'Wallis and Futuna', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(389, 'Western Sahara', 'Western Sahara', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(390, 'Yemen', 'Yemen', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(391, 'Zambia', 'Zambia', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(392, 'Zimbabwe', 'Zimbabwe', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(393, 'Åland Islands', 'Åland Islands', '2024-05-15 22:16:33', '2024-05-15 22:16:33'),
(394, 'Afar', 'Afar', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(395, 'Abkhazian', 'Abkhazian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(396, 'Avestan', 'Avestan', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(397, 'Afrikaans', 'Afrikaans', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(398, 'Akan', 'Akan', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(399, 'Amharic', 'Amharic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(400, 'Aragonese', 'Aragonese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(401, 'Arabic', 'Arabic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(402, 'Assamese', 'Assamese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(403, 'Avaric', 'Avaric', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(404, 'Aymara', 'Aymara', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(405, 'Azerbaijani', 'Azerbaijani', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(406, 'Bashkir', 'Bashkir', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(407, 'Belarusian', 'Belarusian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(408, 'Bulgarian', 'Bulgarian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(409, 'Bihari languages', 'Bihari languages', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(410, 'Bislama', 'Bislama', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(411, 'Bambara', 'Bambara', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(412, 'Bengali', 'Bengali', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(413, 'Tibetan', 'Tibetan', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(414, 'Breton', 'Breton', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(415, 'Bosnian', 'Bosnian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(416, 'Catalan, Valencian', 'Catalan, Valencian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(417, 'Chechen', 'Chechen', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(418, 'Chamorro', 'Chamorro', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(419, 'Corsican', 'Corsican', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(420, 'Cree', 'Cree', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(421, 'Czech', 'Czech', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(422, 'Church Slavonic, Old Bulgarian, Old Church Slavonic', 'Church Slavonic, Old Bulgarian, Old Church Slavonic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(423, 'Chuvash', 'Chuvash', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(424, 'Welsh', 'Welsh', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(425, 'Danish', 'Danish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(426, 'German', 'German', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(427, 'Divehi, Dhivehi, Maldivian', 'Divehi, Dhivehi, Maldivian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(428, 'Dzongkha', 'Dzongkha', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(429, 'Ewe', 'Ewe', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(430, 'Greek (Modern)', 'Greek (Modern)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(431, 'English', 'English', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(432, 'Esperanto', 'Esperanto', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(433, 'Spanish, Castilian', 'Spanish, Castilian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(434, 'Estonian', 'Estonian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(435, 'Basque', 'Basque', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(436, 'Persian', 'Persian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(437, 'Fulah', 'Fulah', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(438, 'Finnish', 'Finnish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(439, 'Fijian', 'Fijian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(440, 'Faroese', 'Faroese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(441, 'French', 'French', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(442, 'Western Frisian', 'Western Frisian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(443, 'Irish', 'Irish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(444, 'Gaelic, Scottish Gaelic', 'Gaelic, Scottish Gaelic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(445, 'Galician', 'Galician', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(446, 'Guarani', 'Guarani', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(447, 'Gujarati', 'Gujarati', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(448, 'Manx', 'Manx', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(449, 'Hausa', 'Hausa', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(450, 'Hebrew', 'Hebrew', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(451, 'Hindi', 'Hindi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(452, 'Hiri Motu', 'Hiri Motu', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(453, 'Croatian', 'Croatian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(454, 'Haitian, Haitian Creole', 'Haitian, Haitian Creole', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(455, 'Hungarian', 'Hungarian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(456, 'Armenian', 'Armenian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(457, 'Herero', 'Herero', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(458, 'Interlingua (International Auxiliary Language Association)', 'Interlingua (International Auxiliary Language Association)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(459, 'Indonesian', 'Indonesian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(460, 'Interlingue', 'Interlingue', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(461, 'Igbo', 'Igbo', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(462, 'Nuosu, Sichuan Yi', 'Nuosu, Sichuan Yi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(463, 'Inupiaq', 'Inupiaq', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(464, 'Ido', 'Ido', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(465, 'Icelandic', 'Icelandic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(466, 'Italian', 'Italian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(467, 'Inuktitut', 'Inuktitut', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(468, 'Japanese', 'Japanese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(469, 'Javanese', 'Javanese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(470, 'Georgian', 'Georgian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(471, 'Kongo', 'Kongo', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(472, 'Gikuyu, Kikuyu', 'Gikuyu, Kikuyu', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(473, 'Kwanyama, Kuanyama', 'Kwanyama, Kuanyama', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(474, 'Kazakh', 'Kazakh', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(475, 'Greenlandic, Kalaallisut', 'Greenlandic, Kalaallisut', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(476, 'Central Khmer', 'Central Khmer', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(477, 'Kannada', 'Kannada', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(478, 'Korean', 'Korean', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(479, 'Kanuri', 'Kanuri', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(480, 'Kashmiri', 'Kashmiri', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(481, 'Kurdish', 'Kurdish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(482, 'Komi', 'Komi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(483, 'Cornish', 'Cornish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(484, 'Kyrgyz', 'Kyrgyz', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(485, 'Latin', 'Latin', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(486, 'Letzeburgesch, Luxembourgish', 'Letzeburgesch, Luxembourgish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(487, 'Ganda', 'Ganda', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(488, 'Limburgish, Limburgan, Limburger', 'Limburgish, Limburgan, Limburger', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(489, 'Lingala', 'Lingala', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(490, 'Lao', 'Lao', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(491, 'Lithuanian', 'Lithuanian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(492, 'Luba-Katanga', 'Luba-Katanga', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(493, 'Latvian', 'Latvian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(494, 'Malagasy', 'Malagasy', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(495, 'Marshallese', 'Marshallese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(496, 'Maori', 'Maori', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(497, 'Macedonian', 'Macedonian', '2024-05-15 22:26:45', '2024-05-15 22:26:45');
INSERT INTO `translates` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(498, 'Malayalam', 'Malayalam', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(499, 'Mongolian', 'Mongolian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(500, 'Marathi', 'Marathi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(501, 'Malay', 'Malay', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(502, 'Maltese', 'Maltese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(503, 'Burmese', 'Burmese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(504, 'Norwegian Bokmål', 'Norwegian Bokmål', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(505, 'Northern Ndebele', 'Northern Ndebele', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(506, 'Nepali', 'Nepali', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(507, 'Ndonga', 'Ndonga', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(508, 'Dutch, Flemish', 'Dutch, Flemish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(509, 'Norwegian Nynorsk', 'Norwegian Nynorsk', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(510, 'Norwegian', 'Norwegian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(511, 'South Ndebele', 'South Ndebele', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(512, 'Navajo, Navaho', 'Navajo, Navaho', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(513, 'Chichewa, Chewa, Nyanja', 'Chichewa, Chewa, Nyanja', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(514, 'Occitan (post 1500)', 'Occitan (post 1500)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(515, 'Ojibwa', 'Ojibwa', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(516, 'Oromo', 'Oromo', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(517, 'Oriya', 'Oriya', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(518, 'Ossetian, Ossetic', 'Ossetian, Ossetic', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(519, 'Panjabi, Punjabi', 'Panjabi, Punjabi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(520, 'Pali', 'Pali', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(521, 'Polish', 'Polish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(522, 'Pashto, Pushto', 'Pashto, Pushto', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(523, 'Portuguese', 'Portuguese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(524, 'Quechua', 'Quechua', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(525, 'Romansh', 'Romansh', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(526, 'Rundi', 'Rundi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(527, 'Moldovan, Moldavian, Romanian', 'Moldovan, Moldavian, Romanian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(528, 'Russian', 'Russian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(529, 'Kinyarwanda', 'Kinyarwanda', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(530, 'Sanskrit', 'Sanskrit', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(531, 'Sardinian', 'Sardinian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(532, 'Sindhi', 'Sindhi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(533, 'Northern Sami', 'Northern Sami', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(534, 'Sango', 'Sango', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(535, 'Sinhala, Sinhalese', 'Sinhala, Sinhalese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(536, 'Slovak', 'Slovak', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(537, 'Slovenian', 'Slovenian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(538, 'Samoan', 'Samoan', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(539, 'Shona', 'Shona', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(540, 'Somali', 'Somali', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(541, 'Albanian', 'Albanian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(542, 'Serbian', 'Serbian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(543, 'Swati', 'Swati', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(544, 'Sotho, Southern', 'Sotho, Southern', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(545, 'Sundanese', 'Sundanese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(546, 'Swedish', 'Swedish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(547, 'Swahili', 'Swahili', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(548, 'Tamil', 'Tamil', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(549, 'Telugu', 'Telugu', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(550, 'Tajik', 'Tajik', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(551, 'Thai', 'Thai', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(552, 'Tigrinya', 'Tigrinya', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(553, 'Turkmen', 'Turkmen', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(554, 'Tagalog', 'Tagalog', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(555, 'Tswana', 'Tswana', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(556, 'Tonga (Tonga Islands)', 'Tonga (Tonga Islands)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(557, 'Turkish', 'Turkish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(558, 'Tsonga', 'Tsonga', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(559, 'Tatar', 'Tatar', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(560, 'Twi', 'Twi', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(561, 'Tahitian', 'Tahitian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(562, 'Uighur, Uyghur', 'Uighur, Uyghur', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(563, 'Ukrainian', 'Ukrainian', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(564, 'Urdu', 'Urdu', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(565, 'Uzbek', 'Uzbek', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(566, 'Venda', 'Venda', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(567, 'Vietnamese', 'Vietnamese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(568, 'Volap_k', 'Volap_k', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(569, 'Walloon', 'Walloon', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(570, 'Wolof', 'Wolof', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(571, 'Xhosa', 'Xhosa', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(572, 'Yiddish', 'Yiddish', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(573, 'Yoruba', 'Yoruba', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(574, 'Zhuang, Chuang', 'Zhuang, Chuang', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(575, 'Chinese', 'Chinese', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(576, 'Zulu', 'Zulu', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(577, '[Hidden In Demo]', '[Hidden In Demo]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(583, 'Failed to sort the table', 'Failed to sort the table', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(584, 'Users', 'Users', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(585, 'Sales', 'Sales', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(586, 'The item has been approved', 'The item has been approved', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(587, 'The item has been soft rejected', 'The item has been soft rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(588, 'The item has been rejected', 'The item has been rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(589, 'Views', 'Views', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(590, 'The update request has been approved', 'The update request has been approved', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(591, 'The update request has been rejected', 'The update request has been rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(592, 'Failed to sort the data', 'Failed to sort the data', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(593, 'Failed to sort the list', 'Failed to sort the list', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(594, 'Failed to update the addon status', 'Failed to update the addon status', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(595, '[Withdrawal] #:id', '[Withdrawal] #:id', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(597, 'New comment waiting review', 'New comment waiting review', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(598, 'The chosen item are not available', 'The chosen item are not available', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(599, 'You cannot purchase your own item', 'You cannot purchase your own item', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(600, 'You have reached the limit for each item', 'You have reached the limit for each item', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(601, 'The item added to cart', 'The item added to cart', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(602, 'Invalid Cron Job Key', 'Invalid Cron Job Key', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(603, 'Cron Job executed successfully', 'Cron Job executed successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(604, 'Invalid file type. Only image files are allowed (JPEG, JPG, PNG, GIF).', 'Invalid file type. Only image files are allowed (JPEG, JPG, PNG, GIF).', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(605, 'API Client ID is missing, please setup your client ID in general settings.', 'API Client ID is missing, please setup your client ID in general settings.', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(606, 'Failed to upload the image', 'Failed to upload the image', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(607, 'Your account balance is insufficient', 'Your account balance is insufficient', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(608, 'Payment for order #:number', 'Payment for order #:number', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(609, 'Regular License', 'Regular License', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(610, 'Extended License', 'Extended License', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(611, 'Pay Now', 'Pay Now', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(612, 'Handling fees', 'Handling fees', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(613, 'Message sent via your :website_name profile from :username', 'Message sent via your :website_name profile from :username', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(614, 'All Items', 'All Items', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(615, ':status Items', ':status Items', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(616, 'File has been deleted successfully', 'File has been deleted successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(617, 'Your item has been resubmitted successfully', 'Your item has been resubmitted successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(618, 'Your item has been updated successfully', 'Your item has been updated successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(619, 'Failed to sort the badges', 'Failed to sort the badges', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(620, 'Regular', 'Regular', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(621, 'Extended', 'Extended', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(622, '[Cancellation] Purchase #:id (:item_name)', '[Cancellation] Purchase #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(623, '[Cancellation] Sale #:id (:item_name)', '[Cancellation] Sale #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(624, '[Cancellation] Referral Earnings #:id', '[Cancellation] Referral Earnings #:id', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(625, '[Purchase] #:id (:item_name)', '[Purchase] #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(626, '[Sale] #:id (:item_name)', '[Sale] #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(627, '[Referral Earnings] #:id', '[Referral Earnings] #:id', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(628, 'KYC Verification Request [#:id]', 'KYC Verification Request [#:id]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(629, 'New Pending Transaction [#:id]', 'New Pending Transaction [#:id]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(630, '[Refund] Purchase #:id (:item_name)', '[Refund] Purchase #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(631, '[Refund] Sale #:id (:item_name)', '[Refund] Sale #:id (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(632, '[Refund] Referral Earnings #:id', '[Refund] Referral Earnings #:id', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(633, 'Item Resubmitted (:item_name)', 'Item Resubmitted (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(634, 'New Pending Item (:item_name)', 'New Pending Item (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(635, 'New Withdrawal Request [#:id]', 'New Withdrawal Request [#:id]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(636, 'New Ticket [#:id]', 'New Ticket [#:id]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(637, 'New Ticket Reply [#:id]', 'New Ticket Reply [#:id]', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(638, 'Item Update Request (:item_name)', 'Item Update Request (:item_name)', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(639, 'Invalid request action', 'Invalid request action', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(640, 'Your are writing too many replies in shorter time, please try again later', 'Your are writing too many replies in shorter time, please try again later', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(641, 'Your reply has been published successfully', 'Your reply has been published successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(642, 'Your are writing too many comments in shorter time, please try again later', 'Your are writing too many comments in shorter time, please try again later', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(643, 'Your comment has been published successfully', 'Your comment has been published successfully', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(644, 'Pending', 'Pending', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(645, 'Soft Rejected', 'Soft Rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(646, 'Resubmitted', 'Resubmitted', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(647, 'Approved', 'Approved', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(648, 'Hard Rejected', 'Hard Rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(649, 'Deleted', 'Deleted', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(650, 'Rejected', 'Rejected', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(651, 'Accepted', 'Accepted', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(652, 'Declined', 'Declined', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(653, 'Opened', 'Opened', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(654, 'Closed', 'Closed', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(655, 'Paid', 'Paid', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(656, 'Cancelled', 'Cancelled', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(657, 'Returned', 'Returned', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(658, 'Completed', 'Completed', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(659, 'We can\\', 'We can\\', '2024-05-15 22:26:45', '2024-05-15 22:26:45'),
(660, 'Account settings', 'Account settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(661, 'Account Details', 'Account Details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(662, 'Choose Image', 'Choose Image', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(663, 'First Name', 'First Name', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(664, 'Last Name', 'Last Name', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(665, 'Username', 'Username', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(666, 'Email Address', 'Email Address', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(667, 'Save Changes', 'Save Changes', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(668, 'Change Password', 'Change Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(669, 'Password', 'Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(670, 'New Password', 'New Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(671, 'Confirm New Password', 'Confirm New Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(672, '2Factor Authentication', '2Factor Authentication', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(673, 'Disabled', 'Disabled', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(674, 'Enabled', 'Enabled', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(675, 'Two-factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two-factor authentication protects against phishing, social engineering, and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.', 'Two-factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two-factor authentication protects against phishing, social engineering, and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(676, 'Enable 2FA Authentication', 'Enable 2FA Authentication', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(677, 'Disable 2FA Authentication', 'Disable 2FA Authentication', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(678, 'To use the two factor authentication, you have to install a Google Authenticator compatible app. Here are some that are currently available:', 'To use the two factor authentication, you have to install a Google Authenticator compatible app. Here are some that are currently available:', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(679, 'Google Authenticator for iOS', 'Google Authenticator for iOS', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(680, 'Google Authenticator for Android', 'Google Authenticator for Android', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(681, 'Microsoft Authenticator for iOS', 'Microsoft Authenticator for iOS', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(682, 'Microsoft Authenticator for Android', 'Microsoft Authenticator for Android', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(683, 'OTP Code', 'OTP Code', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(684, 'Close', 'Close', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(685, 'Enable', 'Enable', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(686, 'Disable', 'Disable', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(687, 'Edit Advertisement', 'Edit Advertisement', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(688, 'Advertisements', 'Advertisements', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(689, 'Position', 'Position', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(690, 'Size', 'Size', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(691, 'Status', 'Status', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(692, 'Last update', 'Last update', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(693, 'Active', 'Active', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(694, 'Edit', 'Edit', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(695, 'Appearance', 'Appearance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(696, ':theme_name Theme Custom CSS', ':theme_name Theme Custom CSS', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(697, 'Themes', 'Themes', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(698, 'Upload', 'Upload', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(699, 'v', 'v', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(700, 'Make Active', 'Make Active', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(701, 'Settings', 'Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(702, 'Custom CSS', 'Custom CSS', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(703, 'Upload a theme', 'Upload a theme', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(704, 'Important!', 'Important!', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(705, 'Make sure you are uploading the correct files.', 'Make sure you are uploading the correct files.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(706, 'Before uploading a new theme make sure to take a backup of your website files and database.', 'Before uploading a new theme make sure to take a backup of your website files and database.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(707, 'Theme Purchase Code', 'Theme Purchase Code', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(708, 'Purchase code', 'Purchase code', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(709, 'Theme Files (Zip)', 'Theme Files (Zip)', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(710, ':theme_name Theme Settings', ':theme_name Theme Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(711, 'Choose ', 'Choose ', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(712, 'Admin', 'Admin', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(713, '2Fa Verification', '2Fa Verification', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(714, 'Please enter the OTP code to continue', 'Please enter the OTP code to continue', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(715, 'Continue', 'Continue', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(716, 'Login', 'Login', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(717, 'Log in to your account to continue.', 'Log in to your account to continue.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(718, 'Email or Username', 'Email or Username', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(719, 'Remember me', 'Remember me', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(720, 'Forgot password', 'Forgot password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(721, 'Reset Password', 'Reset Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(722, 'Enter the email address associated with your account and we will send a link to reset your password.', 'Enter the email address associated with your account and we will send a link to reset your password.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(723, 'Remember your password', 'Remember your password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(724, 'Enter the email address and a new password to start using your account.', 'Enter the email address and a new password to start using your account.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(725, 'Confirm Password', 'Confirm Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(726, 'Blog', 'Blog', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(727, 'New Blog Article', 'New Blog Article', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(728, 'Title', 'Title', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(729, 'Slug', 'Slug', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(730, 'Body', 'Body', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(731, 'Allowed (PNG, JPG, JPEG, WEBP)', 'Allowed (PNG, JPG, JPEG, WEBP)', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(732, 'Category', 'Category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(733, 'Short description', 'Short description', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(734, '50 to 200 character at most', '50 to 200 character at most', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(735, 'Edit Blog Article', 'Edit Blog Article', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(736, 'View', 'View', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(737, 'Blog Articles', 'Blog Articles', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(739, 'Reset', 'Reset', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(740, 'ID', 'ID', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(741, 'Article', 'Article', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(742, 'Comments', 'Comments', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(743, 'Published date', 'Published date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(744, 'Delete', 'Delete', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(745, 'New blog category', 'New blog category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(746, 'Name', 'Name', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(747, 'Edit Blog Category', 'Edit Blog Category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(748, 'Blog Categories', 'Blog Categories', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(749, 'Blog Comments', 'Blog Comments', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(750, 'Posted by', 'Posted by', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(751, 'Posted date', 'Posted date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(752, 'Published', 'Published', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(753, 'View Comment', 'View Comment', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(754, 'Publish', 'Publish', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(755, 'Category Options', 'Category Options', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(756, 'New Category Option', 'New Category Option', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(757, 'Required', 'Required', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(758, 'Yes', 'Yes', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(759, 'No', 'No', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(760, 'Type', 'Type', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(761, 'Single Select', 'Single Select', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(762, 'Multiple Select', 'Multiple Select', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(763, 'Options', 'Options', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(764, 'Option Name', 'Option Name', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(765, 'Created date', 'Created date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(766, 'No Category options Found', 'No Category options Found', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(767, 'Main Categories', 'Main Categories', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(768, 'New Category', 'New Category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(769, 'Regular License Buyer fee', 'Regular License Buyer fee', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(770, 'Extended License Buyer fee', 'Extended License Buyer fee', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(771, 'Regular Buyer Fee', 'Regular Buyer Fee', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(772, 'Extended Buyer Fee', 'Extended Buyer Fee', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(773, 'Sub Categories', 'Sub Categories', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(774, 'No Categories Found', 'No Categories Found', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(775, 'New Sub Category', 'New Sub Category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(776, 'Categories', 'Categories', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(777, 'Main Category', 'Main Category', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(778, 'No Sub Categories Found', 'No Sub Categories Found', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(779, 'Dashboard', 'Dashboard', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(780, 'Cron Job Not Working', 'Cron Job Not Working', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(781, 'It seems that your Cron Job isn\'t set up correctly, which might be causing it not to work as expected. Please double-check and ensure that your Cron Job is properly configured.', 'It seems that your Cron Job isn\'t set up correctly, which might be causing it not to work as expected. Please double-check and ensure that your Cron Job is properly configured.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(782, 'Cron Job is required by multiple things to be run (Emails, Badges, Discounts, Cache, Sitemap, etc...)', 'Cron Job is required by multiple things to be run (Emails, Badges, Discounts, Cache, Sitemap, etc...)', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(783, 'Setup Cron Job', 'Setup Cron Job', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(784, 'SMTP Is Not Enabled', 'SMTP Is Not Enabled', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(785, 'SMTP is not enabled, set it now to be able to recover the password and use all the features that needs to send an email.', 'SMTP is not enabled, set it now to be able to recover the password and use all the features that needs to send an email.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(786, 'Setup SMTP', 'Setup SMTP', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(787, 'Authors Sales', 'Authors Sales', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(788, 'Authors Earnings', 'Authors Earnings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(789, 'Buyer Fees', 'Buyer Fees', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(790, 'Author Fees', 'Author Fees', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(791, 'Referral Earnings', 'Referral Earnings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(792, 'Withdrawal Amount', 'Withdrawal Amount', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(793, 'Total Withdrawals', 'Total Withdrawals', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(794, 'Total Items', 'Total Items', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(795, 'Total Sales', 'Total Sales', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(796, 'Total Refunds', 'Total Refunds', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(797, 'Total Users', 'Total Users', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(798, 'Total Authors', 'Total Authors', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(799, 'Users Statistics For This Month', 'Users Statistics For This Month', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(800, 'View All', 'View All', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(801, 'Recently registered', 'Recently registered', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(802, 'Top Selling Items', 'Top Selling Items', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(803, 'Sales (:count)', 'Sales (:count)', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(804, 'Sales Statistics For This Month', 'Sales Statistics For This Month', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(805, 'Purchasing Countries', 'Purchasing Countries', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(806, 'Top Purchasing Countries', 'Top Purchasing Countries', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(807, 'Copied to clipboard', 'Copied to clipboard', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(808, 'Are you sure?', 'Are you sure?', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(809, 'No data available in table', 'No data available in table', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(810, 'Start typing to search...', 'Start typing to search...', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(811, 'Rows per page _MENU_', 'Rows per page _MENU_', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(812, 'Showing page _PAGE_ of _PAGES_', 'Showing page _PAGE_ of _PAGES_', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(813, 'Showing 0 to 0 of 0 entries', 'Showing 0 to 0 of 0 entries', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(814, '(filtered from _MAX_ total entries)', '(filtered from _MAX_ total entries)', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(815, 'No matching records found', 'No matching records found', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(816, 'First', 'First', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(817, 'Last', 'Last', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(818, 'Nothing selected', 'Nothing selected', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(819, 'No results match', 'No results match', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(820, '{0} of {1} selected', '{0} of {1} selected', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(821, 'All rights reserved.', 'All rights reserved.', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(822, 'Powered by Vironeer', 'Powered by Vironeer', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(823, 'Clear Cache', 'Clear Cache', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(824, 'Preview', 'Preview', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(825, 'Notifications', 'Notifications', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(826, 'Mark All as Read', 'Mark All as Read', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(827, 'No notifications found', 'No notifications found', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(828, 'Account', 'Account', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(829, 'Logout', 'Logout', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(830, 'Members', 'Members', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(831, 'Reviewers', 'Reviewers', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(832, 'Admins', 'Admins', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(833, 'Items', 'Items', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(834, 'Updated Items', 'Updated Items', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(835, 'Records', 'Records', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(836, 'Purchases', 'Purchases', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(837, 'Refunds', 'Refunds', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(838, 'Statements', 'Statements', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(839, 'Withdrawals', 'Withdrawals', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(840, 'Transactions', 'Transactions', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(841, 'KYC Verifications', 'KYC Verifications', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(842, 'Tickets', 'Tickets', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(843, 'Navigation', 'Navigation', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(844, 'Top Nav', 'Top Nav', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(845, 'Bottom Nav', 'Bottom Nav', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(846, 'Footer', 'Footer', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(847, 'Articles', 'Articles', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(848, 'General', 'General', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(849, 'Item Settings', 'Item Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(850, 'Referral Settings', 'Referral Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(851, 'Profile Settings', 'Profile Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(852, 'KYC Settings', 'KYC Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(853, 'Ticket Settings', 'Ticket Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(854, 'Author Levels', 'Author Levels', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(855, 'Manage Badges', 'Manage Badges', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(856, 'OAuth Providers', 'OAuth Providers', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(857, 'SMTP Settings', 'SMTP Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(858, 'Manage Pages', 'Manage Pages', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(859, 'Extensions', 'Extensions', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(860, 'Language', 'Language', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(861, 'Mail Templates', 'Mail Templates', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(862, 'Payment Gateways', 'Payment Gateways', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(863, 'Withdrawal Methods', 'Withdrawal Methods', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(864, 'Sections', 'Sections', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(865, 'Announcement', 'Announcement', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(866, 'Home Sections', 'Home Sections', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(867, 'Home Categories', 'Home Categories', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(868, 'Manage FAQs', 'Manage FAQs', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(869, 'Testimonials', 'Testimonials', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(870, 'System', 'System', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(871, 'Information', 'Information', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(872, 'Addons', 'Addons', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(873, 'Admin Style', 'Admin Style', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(874, 'Reviewer Style', 'Reviewer Style', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(875, 'Cron Job', 'Cron Job', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(876, 'Approve', 'Approve', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(877, 'Soft Reject', 'Soft Reject', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(878, 'Hard Reject', 'Hard Reject', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(879, 'Reason', 'Reason', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(880, 'Submit', 'Submit', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(881, 'Comment', 'Comment', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(882, 'User', 'User', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(883, 'Action', 'Action', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(884, 'Starting at', 'Starting at', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(885, 'Ending at', 'Ending at', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(886, 'Discount', 'Discount', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(887, 'Purchase Price', 'Purchase Price', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(888, 'Inactive', 'Inactive', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(889, 'Author', 'Author', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(890, 'Download', 'Download', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(891, 'Soft Delete', 'Soft Delete', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(892, 'Permanently Delete', 'Permanently Delete', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(893, 'Item Details', 'Item Details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(894, 'History', 'History', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(895, 'Take Action', 'Take Action', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(896, 'Change Status', 'Change Status', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(897, 'Reviews', 'Reviews', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(898, 'Statistics', 'Statistics', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(899, 'Details', 'Details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(900, 'Licenses Price', 'Licenses Price', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(901, 'View Item', 'View Item', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(902, 'Review', 'Review', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(903, 'Buyer', 'Buyer', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(904, 'Preview Image', 'Preview Image', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(905, 'Screenshots', 'Screenshots', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(906, 'Description', 'Description', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(907, 'Category And Attributes', 'Category And Attributes', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(908, 'SubCategory', 'SubCategory', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(909, 'Demo Link', 'Demo Link', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(910, 'Tags', 'Tags', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(911, 'Regular License Price', 'Regular License Price', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(912, 'Extended License Price', 'Extended License Price', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(913, 'Total Sales Amount', 'Total Sales Amount', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(914, 'Total Earnings', 'Total Earnings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(915, 'Total Views', 'Total Views', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(916, 'Sales Statistics', 'Sales Statistics', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(917, 'Views Statistics', 'Views Statistics', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(918, 'Top Referrals', 'Top Referrals', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(919, 'This option is to change the status of the item at any time without notifying the user, in case the user wants to restore the item after deleting or allowing the user to update it after hard rejection etc...', 'This option is to change the status of the item at any time without notifying the user, in case the user wants to restore the item after deleting or allowing the user to update it after hard rejection etc...', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(920, 'Save', 'Save', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(921, 'Update Review: :item_name', 'Update Review: :item_name', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(922, 'Reject', 'Reject', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(923, 'Submitted Date', 'Submitted Date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(924, 'Original Item', 'Original Item', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(925, 'Update Details', 'Update Details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(926, 'Document Type', 'Document Type', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(927, 'User details', 'User details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(928, 'Document Number', 'Document Number', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(929, 'Submited Date', 'Submited Date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(930, 'National ID', 'National ID', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(931, 'Passport', 'Passport', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(932, 'KYC Verification #:id', 'KYC Verification #:id', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(933, 'Rejection Reason', 'Rejection Reason', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(934, 'Send Email Notification', 'Send Email Notification', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(935, 'User Informartion', 'User Informartion', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(936, 'E-mail Address', 'E-mail Address', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(937, 'View Full Information', 'View Full Information', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(938, 'Documents', 'Documents', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(939, 'View Document', 'View Document', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(940, 'Back', 'Back', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(941, 'Quick Access', 'Quick Access', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(942, 'General Settings', 'General Settings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(943, 'Go to dashboard', 'Go to dashboard', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(944, 'Add Badge', 'Add Badge', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(945, 'Make All as Read', 'Make All as Read', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(946, 'Delete All Read', 'Delete All Read', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(947, 'Get Help', 'Get Help', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(948, ':name Actions', ':name Actions', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(949, 'Actions', 'Actions', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(950, 'Two-Factor Authentication', 'Two-Factor Authentication', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(951, 'New Admin', 'New Admin', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(952, 'Generate', 'Generate', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(953, ':name Account details', ':name Account details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(954, 'Send Email', 'Send Email', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(956, 'Subject', 'Subject', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(957, 'Reply to', 'Reply to', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(958, 'Send', 'Send', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(959, 'Admin details', 'Admin details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(960, 'Added date', 'Added date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(961, 'View Details', 'View Details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(962, ':name Password', ':name Password', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(963, 'New Reviewer', 'New Reviewer', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(964, 'Login as Reviewer', 'Login as Reviewer', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(965, 'Reviewer details', 'Reviewer details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(966, 'Account status', 'Account status', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(967, 'Banned', 'Banned', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(968, 'KYC Status', 'KYC Status', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(969, 'Verified', 'Verified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(970, 'Unverified', 'Unverified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(971, 'Email status', 'Email status', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(972, ':name API Key', ':name API Key', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(973, 'API Key', 'API Key', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(974, 'Generate New API Key', 'Generate New API Key', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(975, 'Generate API Key', 'Generate API Key', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(976, 'API Docs', 'API Docs', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(977, ':name Badges', ':name Badges', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(979, 'Choose badge', 'Choose badge', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(980, ':name Account balance', ':name Account balance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(981, 'Account balance', 'Account balance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(982, 'Current balance', 'Current balance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(983, 'View Statements', 'View Statements', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(984, 'Credit or Debit the balance', 'Credit or Debit the balance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(985, 'Credit', 'Credit', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(986, 'Debit', 'Debit', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(987, 'Amount', 'Amount', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(988, 'New User', 'New User', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(989, 'Address line 1', 'Address line 1', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(990, 'Address line 2', 'Address line 2', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(991, 'City', 'City', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(992, 'State', 'State', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(993, 'Postal code', 'Postal code', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(994, 'Country', 'Country', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(995, 'Exclusivity of Author Items', 'Exclusivity of Author Items', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(996, 'Exclusive', 'Exclusive', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(997, 'Non Exclusive', 'Non Exclusive', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(998, 'The user will be awarded an exclusive author badge', 'The user will be awarded an exclusive author badge', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(999, 'Balance', 'Balance', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1000, 'Total Spend', 'Total Spend', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1001, 'Author Level', 'Author Level', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1002, 'Total Reviews', 'Total Reviews', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1003, 'Total Referral Earnings', 'Total Referral Earnings', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1004, 'Total Withdrawal Amount', 'Total Withdrawal Amount', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1005, 'Quick Actions', 'Quick Actions', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1006, 'View profile', 'View profile', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1007, 'Login as User', 'Login as User', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1008, 'Open a Ticket', 'Open a Ticket', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1009, 'Requested Refunds', 'Requested Refunds', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1010, 'Received Refunds', 'Received Refunds', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1011, 'Profile details', 'Profile details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1012, 'Withdrawal details', 'Withdrawal details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1013, 'Badges', 'Badges', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1014, 'Referrals', 'Referrals', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1015, 'Login Logs', 'Login Logs', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1016, 'KYC Verified', 'KYC Verified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1017, 'KYC Unverified', 'KYC Unverified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1018, 'Email Verified', 'Email Verified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1019, 'Email Unverified', 'Email Unverified', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1020, 'Role', 'Role', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1021, 'Registred date', 'Registred date', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1022, ':name Login logs', ':name Login logs', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1023, 'IP', 'IP', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1024, 'Location', 'Location', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1025, 'Browser', 'Browser', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1026, 'OS', 'OS', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1027, ':name Profile details', ':name Profile details', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1028, 'Avatar', 'Avatar', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1029, 'Allowed types (JPG,JPEG,PNG) Size 120x120px', 'Allowed types (JPG,JPEG,PNG) Size 120x120px', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1030, 'Profile Cover', 'Profile Cover', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1031, 'Allowed types (JPG,JPEG,PNG) Size 1200x500px', 'Allowed types (JPG,JPEG,PNG) Size 1200x500px', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1032, 'Profile Heading', 'Profile Heading', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1033, 'Profile Description', 'Profile Description', '2024-05-15 22:26:46', '2024-05-15 22:26:46'),
(1034, 'Profile Contact Email', 'Profile Contact Email', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1035, 'Email', 'Email', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1036, 'Profile Social Links', 'Profile Social Links', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1037, 'Facebook', 'Facebook', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1038, 'X.com', 'X.com', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1039, 'Youtube', 'Youtube', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1040, 'Linkedin', 'Linkedin', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1041, 'Instagram', 'Instagram', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1042, 'Pinterest', 'Pinterest', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1043, ':name Referrals', ':name Referrals', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1044, 'Referral Link', 'Referral Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1045, 'Copy', 'Copy', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1046, 'Earnings', 'Earnings', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1047, 'No Referrals Found', 'No Referrals Found', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1048, ':name Withdrawal details', ':name Withdrawal details', '2024-05-15 22:26:47', '2024-05-15 22:26:47');
INSERT INTO `translates` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1049, 'Withdrawal Method', 'Withdrawal Method', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1050, 'Withdrawal Account', 'Withdrawal Account', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1051, 'New Bottom Nav link', 'New Bottom Nav link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1052, 'Link Type', 'Link Type', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1053, 'Internal', 'Internal', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1054, 'External', 'External', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1055, 'Link', 'Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1056, 'Edit Bottom Nav Link', 'Edit Bottom Nav Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1057, 'Bottom Nav Links', 'Bottom Nav Links', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1058, 'New Footer Link', 'New Footer Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1059, 'Edit Footer Link', 'Edit Footer Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1060, 'Footer Links', 'Footer Links', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1061, 'New Top Nav link', 'New Top Nav link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1062, 'Edit Top Nav link', 'Edit Top Nav link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1063, 'Top Nav Links', 'Top Nav Links', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1064, 'No Data Found', 'No Data Found', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1065, 'It appears that the section is empty or your', 'It appears that the section is empty or your', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1066, 'search did not return any results', 'search did not return any results', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1067, 'Refunded', 'Refunded', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1068, 'From Date', 'From Date', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1069, 'To Date', 'To Date', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1070, 'Item', 'Item', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1071, 'License Type', 'License Type', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1072, 'Date', 'Date', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1073, 'View Sale', 'View Sale', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1074, 'Purchase #:id', 'Purchase #:id', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1075, 'Purchase ID', 'Purchase ID', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1076, 'Downloaded', 'Downloaded', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1077, 'Purchase Date', 'Purchase Date', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1078, 'Referral ID', 'Referral ID', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1079, 'Referred User', 'Referred User', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1080, 'Referred By Author', 'Referred By Author', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1081, 'Author Earnings', 'Author Earnings', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1082, 'Purchase', 'Purchase', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1083, 'View Purchase', 'View Purchase', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1084, 'Refund #:id', 'Refund #:id', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1085, 'Refund ID', 'Refund ID', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1086, 'Price', 'Price', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1087, 'Sale #:id', 'Sale #:id', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1088, 'Sale ID', 'Sale ID', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1089, 'Buyer Fee', 'Buyer Fee', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1090, 'Author Fee', 'Author Fee', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1091, 'Author Earning', 'Author Earning', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1092, 'Sale Date', 'Sale Date', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1093, 'Cancel', 'Cancel', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1094, 'Statments', 'Statments', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1095, 'Total Cedited', 'Total Cedited', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1096, 'Total Debited', 'Total Debited', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1097, 'Total', 'Total', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1098, 'View User', 'View User', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1099, 'Announcement Body', 'Announcement Body', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1100, 'Button Title', 'Button Title', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1101, 'Button Link', 'Button Link', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1102, 'Announcement Background Color', 'Announcement Background Color', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1103, 'Button Background Color', 'Button Background Color', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1104, 'Button Text Color', 'Button Text Color', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1105, 'New FAQ', 'New FAQ', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1106, 'Edit FAQ', 'Edit FAQ', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1107, 'Faqs', 'Faqs', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1108, 'New Home Category', 'New Home Category', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1109, 'Choose Icon', 'Choose Icon', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1110, 'Edit Home Category', 'Edit Home Category', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1111, 'Edit Home Section', 'Edit Home Section', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1112, 'Items Number', 'Items Number', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1113, 'Between 1 to 100 maximum', 'Between 1 to 100 maximum', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1114, 'Cache Expiry time', 'Cache Expiry time', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1115, 'Minutes', 'Minutes', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1116, 'From 1 to 525600 minutes', 'From 1 to 525600 minutes', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1117, 'You must clear the cache every time you changed the \"Items Number\" or \"Cache Expiry time\"', 'You must clear the cache every time you changed the \"Items Number\" or \"Cache Expiry time\"', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1118, 'New Testimonial', 'New Testimonial', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1119, 'Choose Avatar', 'Choose Avatar', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1120, 'Edit Testimonial', 'Edit Testimonial', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1121, 'New badge', 'New badge', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1122, 'Badge Image', 'Badge Image', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1123, 'Allowed (PNG, SVG)', 'Allowed (PNG, SVG)', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1124, 'Title (Optional)', 'Title (Optional)', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1125, 'Type (Optional)', 'Type (Optional)', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1126, 'None', 'None', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1127, 'Countries', 'Countries', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1128, 'Membership years', 'Membership years', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1129, 'Years', 'Years', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1130, 'Edit Badge', 'Edit Badge', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1131, 'None type', 'None type', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1132, 'badge', 'badge', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1133, 'No Badge Found', 'No Badge Found', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1134, 'Edit Extension', 'Edit Extension', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1135, 'Instructions', 'Instructions', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1136, 'Logo', 'Logo', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1137, 'General Details', 'General Details', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1138, 'Site Name', 'Site Name', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1139, 'Site URL', 'Site URL', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1140, 'Date format', 'Date format', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1141, 'Timezone', 'Timezone', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1142, 'Links', 'Links', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1143, 'SEO', 'SEO', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1144, 'Home title', 'Home title', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1145, 'Title must be within 70 Characters', 'Title must be within 70 Characters', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1146, 'Description must be within 150 Characters', 'Description must be within 150 Characters', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1147, 'Site keywords', 'Site keywords', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1148, 'keyword1, keyword2, keyword3', 'keyword1, keyword2, keyword3', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1149, 'Currency', 'Currency', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1150, 'Currency Code', 'Currency Code', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1151, 'USD', 'USD', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1152, 'Currency Symbol', 'Currency Symbol', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1153, 'Currency position', 'Currency position', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1154, 'Before price', 'Before price', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1155, 'After price', 'After price', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1156, 'Imgur API', 'Imgur API', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1157, 'Client ID', 'Client ID', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1159, 'Maximum Tags', 'Maximum Tags', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1160, 'Maximum tags that the author can set for item', 'Maximum tags that the author can set for item', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1161, 'Minimum Price', 'Minimum Price', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1162, 'Maximum Price', 'Maximum Price', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1163, 'Adding the items require review', 'Adding the items require review', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1164, 'Updating the items require review', 'Updating the items require review', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1165, 'Discount maximum percentage', 'Discount maximum percentage', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1166, 'The maximum should be less than 90%', 'The maximum should be less than 90%', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1167, 'Discount maximum days', 'Discount maximum days', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1168, 'Days', 'Days', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1169, 'Enter 0 to disable it', 'Enter 0 to disable it', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1170, 'Time between discounts', 'Time between discounts', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1171, 'Time between changing price and discount', 'Time between changing price and discount', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1172, 'Trending And Best selling', 'Trending And Best selling', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1173, 'Trending Items Number', 'Trending Items Number', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1174, 'Best Selling Items Number', 'Best Selling Items Number', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1175, 'You must setup the cron job to refresh the Items every 24 hours.', 'You must setup the cron job to refresh the Items every 24 hours.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1176, 'Files', 'Files', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1177, 'Maximum Upload Files', 'Maximum Upload Files', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1178, 'Maximum files that can author upload every time', 'Maximum files that can author upload every time', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1179, 'Maximum File Size', 'Maximum File Size', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1180, 'The size of each file the author can upload', 'The size of each file the author can upload', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1181, 'Preview Image Width', 'Preview Image Width', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1182, 'px', 'px', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1183, 'Preview Image Height', 'Preview Image Height', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1184, 'Maximum Screenshots', 'Maximum Screenshots', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1185, 'Number between 1 to 100', 'Number between 1 to 100', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1186, 'Convert Images To WEBP', 'Convert Images To WEBP', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1187, 'Convert uploaded images to WEBP like Preview Image, Screenshots, etc...', 'Convert uploaded images to WEBP like Preview Image, Screenshots, etc...', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1188, 'File duration', 'File duration', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1189, 'Hours', 'Hours', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1190, 'Uploaded files will expire after this time if the author does not use them.', 'Uploaded files will expire after this time if the author does not use them.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1191, 'Files Storage', 'Files Storage', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1192, 'Storage Provider', 'Storage Provider', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1193, 'When you change the storage provider, you must move all files form those paths to new storage provider.', 'When you change the storage provider, you must move all files form those paths to new storage provider.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1194, 'Local', 'Local', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1195, 's3 and others', 's3 and others', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1196, 'Test Storage Connection', 'Test Storage Connection', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1198, 'When KYC is required the user will not be able to buy or sell items until finish the KYC verification.', 'When KYC is required the user will not be able to buy or sell items until finish the KYC verification.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1199, 'Selfie Verification', 'Selfie Verification', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1200, 'Avatars', 'Avatars', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1201, 'Choose ID Front Image', 'Choose ID Front Image', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1202, 'Supported (JPEG, JPG, PNG, SVG)', 'Supported (JPEG, JPG, PNG, SVG)', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1203, 'Choose ID Back Image', 'Choose ID Back Image', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1204, 'Choose ID Passport Image', 'Choose ID Passport Image', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1205, 'Choose Selfie Image', 'Choose Selfie Image', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1206, 'Direction', 'Direction', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1207, 'LTR', 'LTR', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1208, 'RTL', 'RTL', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1209, 'View Translates', 'View Translates', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1210, ':language_name Translates', ':language_name Translates', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1211, 'There are some words that should not be translated that start with some tags or are inside a tag :words etc...', 'There are some words that should not be translated that start with some tags or are inside a tag :words etc...', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1212, 'New Author Level', 'New Author Level', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1213, 'Minimum Earnings', 'Minimum Earnings', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1214, 'Fees', 'Fees', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1215, 'Edit Author Level', 'Edit Author Level', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1216, 'Edit Mail Template', 'Edit Mail Template', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1217, 'Short Codes', 'Short Codes', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1218, 'Edit OAuth Provider', 'Edit OAuth Provider', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1219, 'Credentials', 'Credentials', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1220, 'New Page', 'New Page', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1221, 'Edit Page', 'Edit Page', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1222, 'Pages', 'Pages', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1223, 'Page Name', 'Page Name', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1224, 'Edit Payment Gateway', 'Edit Payment Gateway', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1225, 'Choose Logo', 'Choose Logo', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1226, 'Mode', 'Mode', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1227, 'Parameters', 'Parameters', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1228, 'Not data found', 'Not data found', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1229, 'Default Images', 'Default Images', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1230, 'Default Avatar', 'Default Avatar', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1231, 'Supported (JPEG, JPG, PNG) Size 120x120px.', 'Supported (JPEG, JPG, PNG) Size 120x120px.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1232, 'Default Cover', 'Default Cover', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1233, 'Supported (JPEG, JPG, PNG) Size 1200x500px.', 'Supported (JPEG, JPG, PNG) Size 1200x500px.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1234, 'Referral Status', 'Referral Status', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1235, 'Referral Percentage', 'Referral Percentage', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1236, 'SMTP', 'SMTP', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1237, 'SMTP details', 'SMTP details', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1238, 'Mail mailer', 'Mail mailer', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1239, 'SENDMAIL', 'SENDMAIL', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1240, 'Mail Host', 'Mail Host', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1241, 'Enter mail host', 'Enter mail host', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1242, 'Mail Port', 'Mail Port', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1243, 'Enter mail port', 'Enter mail port', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1244, 'Mail username', 'Mail username', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1245, 'Enter username', 'Enter username', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1246, 'Mail password', 'Mail password', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1247, 'Enter password', 'Enter password', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1248, 'Mail encryption', 'Mail encryption', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1249, 'TLS', 'TLS', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1250, 'SSL', 'SSL', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1251, 'From email', 'From email', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1252, 'Enter from email', 'Enter from email', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1253, 'From name', 'From name', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1254, 'Enter from name', 'Enter from name', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1255, 'Testing', 'Testing', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1256, 'Allowed file types', 'Allowed file types', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1257, 'Enter the file extension', 'Enter the file extension', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1258, 'Max upload files', 'Max upload files', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1259, 'Max size per file', 'Max size per file', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1261, 'New Withdrawal Method', 'New Withdrawal Method', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1262, 'Minimum Withdrawal Amount', 'Minimum Withdrawal Amount', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1263, 'Edit Withdrawal Method', 'Edit Withdrawal Method', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1264, 'Version: :version', 'Version: :version', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1265, 'Upload an addon', 'Upload an addon', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1266, 'Before uploading a new addon make sure to take a backup of your website files and database.', 'Before uploading a new addon make sure to take a backup of your website files and database.', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1267, 'Addon Purchase Code', 'Addon Purchase Code', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1268, 'Addon Files (Zip)', 'Addon Files (Zip)', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1269, 'Admin Panel Style', 'Admin Panel Style', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1270, 'Colors', 'Colors', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1271, 'Command', 'Command', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1272, 'The cron job command must be set to run every minute', 'The cron job command must be set to run every minute', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1273, 'Generate Key', 'Generate Key', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1274, 'Remove Key', 'Remove Key', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1275, 'System Information', 'System Information', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1276, 'Application', 'Application', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1277, 'Laravel Version', 'Laravel Version', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1278, 'Server Details', 'Server Details', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1279, 'Software', 'Software', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1280, 'PHP Version', 'PHP Version', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1281, 'IP Address', 'IP Address', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1282, 'Protocol', 'Protocol', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1283, 'HTTP Host', 'HTTP Host', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1284, 'Port', 'Port', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1285, 'System Cache', 'System Cache', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1286, 'Compiled views will be cleared', 'Compiled views will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1287, 'Application cache will be cleared', 'Application cache will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1288, 'Route cache will be cleared', 'Route cache will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1289, 'Configuration cache will be cleared', 'Configuration cache will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1290, 'All Other Caches will be cleared', 'All Other Caches will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1291, 'Error logs file will be cleared', 'Error logs file will be cleared', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1292, 'Clear System Cache', 'Clear System Cache', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1293, 'Reviewer Panel Style', 'Reviewer Panel Style', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1294, 'Ticket Categories', 'Ticket Categories', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1295, 'New Ticket Category', 'New Ticket Category', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1296, 'Edit Ticket Category', 'Edit Ticket Category', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1297, 'New Ticket', 'New Ticket', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1298, 'Attachments', 'Attachments', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1299, 'Opened tickets', 'Opened tickets', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1300, 'Closed tickets', 'Closed tickets', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1301, 'Ticket #:id', 'Ticket #:id', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1302, 'Support', 'Support', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1303, 'Attached files', 'Attached files', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1304, 'Reply', 'Reply', '2024-05-15 22:26:47', '2024-05-15 22:26:47'),
(1305, 'Ticket ID', 'Ticket ID', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1306, 'Last Activity', 'Last Activity', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1307, 'Close ticket', 'Close ticket', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1308, 'Payment Method', 'Payment Method', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1309, 'Payment Gateway', 'Payment Gateway', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1310, 'Transaction #:id', 'Transaction #:id', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1311, 'Cancellation Reason', 'Cancellation Reason', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1312, 'View Payment Proof', 'View Payment Proof', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1313, 'Transaction ID', 'Transaction ID', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1314, 'Transaction Date', 'Transaction Date', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1315, 'Transaction Status', 'Transaction Status', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1316, 'SubTotal', 'SubTotal', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1317, 'Method', 'Method', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1318, 'Withdrawal Date', 'Withdrawal Date', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1319, 'Withdrawal #:id', 'Withdrawal #:id', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1320, 'Introduction', 'Introduction', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1321, 'Overview', 'Overview', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1322, 'Authentication', 'Authentication', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1323, 'Get Account Details', 'Get Account Details', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1324, 'Get All Items', 'Get All Items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1325, 'Get An Item Details', 'Get An Item Details', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1326, 'Purchase Validation', 'Purchase Validation', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1327, 'Retrieves details of the account associated with the provided API key', 'Retrieves details of the account associated with the provided API key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1328, 'Endpoint', 'Endpoint', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1329, 'GET', 'GET', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1330, 'Your API key', 'Your API key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1331, 'Responses', 'Responses', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1332, 'Success Response', 'Success Response', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1333, 'success', 'success', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1334, 'Error Response', 'Error Response', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1335, 'error', 'error', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1336, 'Unauthorized', 'Unauthorized', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1337, 'Navigate to Workspace Settings', 'Navigate to Workspace Settings', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1338, 'The user should first log in to their account on the platform. Then, they can navigate to the \"Settings\" section of their workspace.', 'The user should first log in to their account on the platform. Then, they can navigate to the \"Settings\" section of their workspace.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1339, 'Locate API Key Section', 'Locate API Key Section', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1340, 'Within the workspace settings, the user should look for a section specifically labeled \"API Key\" or \"API Access.\"', 'Within the workspace settings, the user should look for a section specifically labeled \"API Key\" or \"API Access.\"', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1341, 'Generate or Retrieve API Key', 'Generate or Retrieve API Key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1342, 'In this section, the user can either generate a new API key or retrieve an existing one if it has been previously generated. If there is an option to generate a new key, the user can click on it to create a fresh API key.', 'In this section, the user can either generate a new API key or retrieve an existing one if it has been previously generated. If there is an option to generate a new key, the user can click on it to create a fresh API key.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1343, 'Copy the API Key', 'Copy the API Key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1344, 'Once the API key is generated or retrieved, the user should be able to see it displayed on the screen. They can simply click on a button or icon next to the key to copy it to their clipboard.', 'Once the API key is generated or retrieved, the user should be able to see it displayed on the screen. They can simply click on a button or icon next to the key to copy it to their clipboard.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1345, 'Use the API Key', 'Use the API Key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1346, 'With the API key copied, the user can now use it to authenticate their requests when accessing the platform API endpoints. They typically need to include the API key as part of the request headers or parameters, depending on the API authentication mechanism.', 'With the API key copied, the user can now use it to authenticate their requests when accessing the platform API endpoints. They typically need to include the API key as part of the request headers or parameters, depending on the API authentication mechanism.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1347, 'Secure the API Key', 'Secure the API Key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1348, 'Its essential to remind users to keep their API keys secure and not share them publicly. They should avoid hardcoding API keys in client-side code or sharing them in publicly accessible repositories. Instead, they should consider storing the API key securely on their server-side applications and using appropriate access controls.', 'Its essential to remind users to keep their API keys secure and not share them publicly. They should avoid hardcoding API keys in client-side code or sharing them in publicly accessible repositories. Instead, they should consider storing the API key securely on their server-side applications and using appropriate access controls.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1349, 'Retrieves all items associated with the provided API key', 'Retrieves all items associated with the provided API key', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1350, 'This only works for authors and will not work for regular users.', 'This only works for authors and will not work for regular users.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1351, 'Retrieves details of a specific item based on the provided item ID and API key.', 'Retrieves details of a specific item based on the provided item ID and API key.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1352, 'The ID of the item to retrieve', 'The ID of the item to retrieve', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1353, 'Item Not Found', 'Item Not Found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1354, 'API Documentation Overview', 'API Documentation Overview', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1355, '1. Get Account Details', '1. Get Account Details', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1356, 'Retrieves details of the account associated with the provided API key.', 'Retrieves details of the account associated with the provided API key.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1357, '2. Get All Items', '2. Get All Items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1358, 'Retrieves all items associated with the provided API key.', 'Retrieves all items associated with the provided API key.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1359, '3. Get An Item Details', '3. Get An Item Details', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1360, '4. Purchase Validation', '4. Purchase Validation', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1361, 'POST', 'POST', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1362, 'Validate a purchase code and returns details about the purchase if valid.', 'Validate a purchase code and returns details about the purchase if valid.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1363, 'The purchase code to validate', 'The purchase code to validate', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1364, 'Invalid purchase code', 'Invalid purchase code', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1365, 'Reviewer', 'Reviewer', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1366, 'Updated', 'Updated', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1367, 'Item are submitted and waiting review', 'Item are submitted and waiting review', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1368, 'Item needs improvement by the author', 'Item needs improvement by the author', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1369, 'Item was re-sent after the soft rejection', 'Item was re-sent after the soft rejection', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1370, 'Item are accepted and available for purchase', 'Item are accepted and available for purchase', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1371, 'Item are rejected permanently', 'Item are rejected permanently', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1372, 'All rights reserved', 'All rights reserved', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1373, 'Author :username', 'Author :username', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1375, 'Sign In', 'Sign In', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1376, 'Enter your account details to sign in', 'Enter your account details to sign in', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1377, 'Forgot Your Password?', 'Forgot Your Password?', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1378, 'Complete your information', 'Complete your information', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1379, 'You need to complete some basic information required to log in next time', 'You need to complete some basic information required to log in next time', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1380, 'I agree to the', 'I agree to the', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1381, 'You will receive an email with a link to reset your password', 'You will receive an email with a link to reset your password', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1382, 'Enter a new password to continue.', 'Enter a new password to continue.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1383, 'Sign Up', 'Sign Up', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1384, 'Enter your details to create an account.', 'Enter your details to create an account.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1385, 'Verify Your Email Address', 'Verify Your Email Address', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1386, 'Please verify your email, simply click on the verification link that has been sent to your email address. If you haven\'t received the verification email, please check your spam or junk folder, or request a new verification email to be sent.', 'Please verify your email, simply click on the verification link that has been sent to your email address. If you haven\'t received the verification email, please check your spam or junk folder, or request a new verification email to be sent.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1387, 'Resend', 'Resend', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1388, 'Change Email', 'Change Email', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1389, 'Leave a comment', 'Leave a comment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1390, 'Your comment', 'Your comment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1391, 'Login or create account to leave comments', 'Login or create account to leave comments', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1392, 'No blog articles found', 'No blog articles found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1393, 'Read More...', 'Read More...', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1394, 'Your Cart', 'Your Cart', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1395, 'Continue browsing', 'Continue browsing', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1396, 'Empty Cart', 'Empty Cart', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1397, 'Quantity', 'Quantity', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1398, 'Update', 'Update', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1399, 'Your Cart Total', 'Your Cart Total', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1400, 'Checkout', 'Checkout', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1401, 'Your Cart is Empty', 'Your Cart is Empty', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1402, 'Your cart is currently empty. Start adding items to make your shopping experience complete!', 'Your cart is currently empty. Start adding items to make your shopping experience complete!', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1403, 'Your search results for the \":name\" category', 'Your search results for the \":name\" category', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1404, 'All results for the \":name\" category', 'All results for the \":name\" category', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1405, 'Payments Method', 'Payments Method', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1406, 'Billing address', 'Billing address', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1407, 'SSL Secure Payment', 'SSL Secure Payment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1408, 'Your information is protected by 256-bit SSL encryption', 'Your information is protected by 256-bit SSL encryption', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1409, 'Order Summary', 'Order Summary', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1410, 'License', 'License', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1411, 'Qty', 'Qty', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1413, 'Payment completed', 'Payment completed', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1414, 'Thank you for your purchase. Your payment has been completed successfully.', 'Thank you for your purchase. Your payment has been completed successfully.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1415, 'View My Purchases', 'View My Purchases', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1416, 'Or With', 'Or With', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1417, 'We use cookies to personalize your experience. By continuing to visit this website you agree to our use of cookies', 'We use cookies to personalize your experience. By continuing to visit this website you agree to our use of cookies', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1418, 'Got it', 'Got it', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1419, 'More', 'More', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1420, 'Favorites', 'Favorites', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1421, 'Your search results', 'Your search results', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1422, 'Your favorite items', 'Your favorite items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1423, 'No items found', 'No items found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1424, 'No Items in Favorite', 'No Items in Favorite', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1425, 'You do not have any items in favorite. browse and add items you like!', 'You do not have any items in favorite. browse and add items you like!', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1426, 'WordPress Templates, Plugins, PHP Scripts, and Graphics Digital Marketplace', 'WordPress Templates, Plugins, PHP Scripts, and Graphics Digital Marketplace', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1427, 'JavaScript, PHP Scripts, CSS, HTML5, Site Templates, WordPress Themes, Plugins, Mobile Apps, Graphics, Prints, Brochures, Flyers, Resumes, and More...', 'JavaScript, PHP Scripts, CSS, HTML5, Site Templates, WordPress Themes, Plugins, Mobile Apps, Graphics, Prints, Brochures, Flyers, Resumes, and More...', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1428, 'e.g. Wordpress landing page', 'e.g. Wordpress landing page', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1429, 'Items Sold', 'Items Sold', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1430, 'Live Preview', 'Live Preview', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1431, 'Trending', 'Trending', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1432, 'On Sale', 'On Sale', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1433, 'Reviews (:count)', 'Reviews (:count)', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1434, 'License Option', 'License Option', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1436, 'For one project', 'For one project', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1437, 'For unlimited projects', 'For unlimited projects', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1438, 'Add to Cart', 'Add to Cart', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1439, 'Quality checked by :website_name', 'Quality checked by :website_name', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1440, 'Future updates', 'Future updates', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1442, 'Member since :date', 'Member since :date', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1443, 'View Portfolio', 'View Portfolio', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1444, 'Share', 'Share', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1445, ':username\'s items', ':username\'s items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1446, 'View More', 'View More', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1447, 'Similar items', 'Similar items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1448, 'Buy Now', 'Buy Now', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1449, ':count out of 5 stars', ':count out of 5 stars', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1450, 'Write a review', 'Write a review', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1451, 'Your Review', 'Your Review', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1452, 'This item has no reviews', 'This item has no reviews', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1453, 'Link has been resend Successfully', 'Link has been resend Successfully', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1454, 'Back to home', 'Back to home', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1455, 'Clear All', 'Clear All', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1456, 'Following', 'Following', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1457, 'Follow', 'Follow', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1458, 'Purchased', 'Purchased', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1459, 'Your reply', 'Your reply', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1460, 'Comments (:count)', 'Comments (:count)', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1461, 'This item has no comments', 'This item has no comments', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1462, ':sign_in to comment', ':sign_in to comment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1463, 'Browse Items', 'Browse Items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1464, 'By :username', 'By :username', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1465, 'By :username in :category', 'By :username in :category', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1466, 'Search for...', 'Search for...', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1468, 'Not Items Found', 'Not Items Found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1469, 'There are no items in this section or your search did not return any results', 'There are no items in this section or your search did not return any results', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1470, 'Best Selling', 'Best Selling', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1471, 'min', 'min', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1472, 'max', 'max', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1473, 'Rating', 'Rating', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1474, 'Show All', 'Show All', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1475, '5 stars', '5 stars', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1476, '4 stars', '4 stars', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1477, '3 stars', '3 stars', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1478, '2 stars', '2 stars', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1479, '1 star', '1 star', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1480, 'Date Added', 'Date Added', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1481, 'Any time', 'Any time', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1482, 'This month', 'This month', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1483, 'Last month', 'Last month', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1484, 'This year', 'This year', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1485, 'Last year', 'Last year', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1486, 'Profile', 'Profile', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1487, 'My Items', 'My Items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1488, 'Start Selling', 'Start Selling', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1489, 'Complete the payment', 'Complete the payment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1490, 'Payment details', 'Payment details', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1491, 'Payment proof', 'Payment proof', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1492, 'Choose payment Proof (Receipt, Bank statement, etc..), allowed file types (jpg, jpeg, png, pdf) in max size 2MB.', 'Choose payment Proof (Receipt, Bank statement, etc..), allowed file types (jpg, jpeg, png, pdf) in max size 2MB.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1493, 'Cancel Payment', 'Cancel Payment', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1494, 'Followers', 'Followers', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1495, 'No followers found', 'No followers found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1496, 'No followings found', 'No followings found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1497, 'Portfolio', 'Portfolio', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1498, 'Followers (:count)', 'Followers (:count)', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1499, 'Following (:count)', 'Following (:count)', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1500, 'Contact :username', 'Contact :username', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1501, 'From', 'From', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1502, 'Message', 'Message', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1503, 'Enter Your Message', 'Enter Your Message', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1504, 'Please sign in to contact this :username.', 'Please sign in to contact this :username.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1505, 'Social links', 'Social links', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1506, 'No reviews found', 'No reviews found', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1507, 'Read More', 'Read More', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1508, 'All Categories', 'All Categories', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1509, 'Become an Author', 'Become an Author', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1510, 'Unleash your creativity and earn by joining our platform as an author! As an author, you have the unique opportunity to craft and sell your own items, reaching a wide audience eager to discover new content. Whether you are a developer, designer, or you have any unique items', 'Unleash your creativity and earn by joining our platform as an author! As an author, you have the unique opportunity to craft and sell your own items, reaching a wide audience eager to discover new content. Whether you are a developer, designer, or you have any unique items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1511, 'Upload and sell your items', 'Upload and sell your items', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1512, 'Start selling your items today! Our easy upload process allows you to quickly bring your unique items to a global audience.', 'Start selling your items today! Our easy upload process allows you to quickly bring your unique items to a global audience.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1513, 'Earn from referral program', 'Earn from referral program', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1514, 'Maximize your earnings with our referral program! Simply invite friends to join our platform, and earn a commission on their purchases.', 'Maximize your earnings with our referral program! Simply invite friends to join our platform, and earn a commission on their purchases.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1515, 'I read and I agree with the', 'I read and I agree with the', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1516, 'Author terms.', 'Author terms.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1517, 'Referral program terms.', 'Referral program terms.', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1518, 'Get Started', 'Get Started', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1519, 'Sales Earnings', 'Sales Earnings', '2024-05-15 22:26:48', '2024-05-15 22:26:48'),
(1520, 'New Item', 'New Item', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1521, 'Name And Description', 'Name And Description', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1522, 'SubCategory (Optional)', 'SubCategory (Optional)', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1523, 'Type your tag and click enter, maximum :maximum_tags tags.', 'Type your tag and click enter, maximum :maximum_tags tags.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1524, 'Licenses terms', 'Licenses terms', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1525, 'Message to the Reviewer', 'Message to the Reviewer', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1526, 'Your message', 'Your message', '2024-05-15 22:26:49', '2024-05-15 22:26:49');
INSERT INTO `translates` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1527, 'Regular License discount', 'Regular License discount', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1528, 'The maximum discount percentage should be less or equal :percentage%', 'The maximum discount percentage should be less or equal :percentage%', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1529, 'Discount Percentage', 'Discount Percentage', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1530, 'Extended License discount (Optional)', 'Extended License discount (Optional)', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1531, 'Discount Period', 'Discount Period', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1532, 'The starting date cannot be in the past', 'The starting date cannot be in the past', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1533, 'The discount maximum days should be less or equal :days days', 'The discount maximum days should be less or equal :days days', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1534, 'to', 'to', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1535, 'Create discount', 'Create discount', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1536, 'This option is not available for unapproved items', 'This option is not available for unapproved items', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1537, 'The price cannot update while the item is on discount', 'The price cannot update while the item is on discount', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1538, 'Drop files here to upload', 'Drop files here to upload', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1540, ':dimensions preview image (.JPG or .PNG)', ':dimensions preview image (.JPG or .PNG)', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1541, 'Main File', 'Main File', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1543, 'Screenshots (Optional)', 'Screenshots (Optional)', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1544, 'Item screenshots images (.JPG or .PNG) and maximum :maximum screenshots', 'Item screenshots images (.JPG or .PNG) and maximum :maximum screenshots', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1545, 'You can not upload any more files.', 'You can not upload any more files.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1546, 'You cannot attach the same file twice', 'You cannot attach the same file twice', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1547, 'Empty files cannot be uploaded', 'Empty files cannot be uploaded', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1548, 'File is too big, Max file size :max_file_size', 'File is too big, Max file size :max_file_size', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1549, 'Edit details', 'Edit details', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1550, 'You do not have any items', 'You do not have any items', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1551, 'You do not have any items, you can start by clicking New Item button.', 'You do not have any items, you can start by clicking New Item button.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1552, 'KYC Verification Pending', 'KYC Verification Pending', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1553, 'Your KYC verification is currently pending. We are processing your information, and you will be notified once the verification is complete.', 'Your KYC verification is currently pending. We are processing your information, and you will be notified once the verification is complete.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1554, 'KYC Verification Required', 'KYC Verification Required', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1555, 'Your KYC verification is required to continue using our platform. Please complete the verification process as soon as possible.', 'Your KYC verification is required to continue using our platform. Please complete the verification process as soon as possible.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1556, 'Complete KYC', 'Complete KYC', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1557, 'Invoice', 'Invoice', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1558, 'Withdraw', 'Withdraw', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1559, 'Go to My Purchases', 'Go to My Purchases', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1560, 'file is too big max file size: {{maxFilesize}}MiB.', 'file is too big max file size: {{maxFilesize}}MiB.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1561, 'Server responded with {{statusCode}} code.', 'Server responded with {{statusCode}} code.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1562, 'Your browser does not support drag and drop file uploads.', 'Your browser does not support drag and drop file uploads.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1563, 'Please use the fallback form below to upload your files like in the olden days.', 'Please use the fallback form below to upload your files like in the olden days.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1564, 'You cannot upload files of this type.', 'You cannot upload files of this type.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1565, 'Cancel upload', 'Cancel upload', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1566, 'Are you sure you want to cancel this upload?', 'Are you sure you want to cancel this upload?', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1567, 'Remove file', 'Remove file', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1568, 'Request a refund', 'Request a refund', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1569, 'You do not have any purchases', 'You do not have any purchases', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1570, 'When you purchase item, you will be able to see them here.', 'When you purchase item, you will be able to see them here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1571, 'Your Purchase Code', 'Your Purchase Code', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1572, 'Invite your friends and earn money!', 'Invite your friends and earn money!', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1573, 'Our referral program offers a great way to earn more income on the platform by just referring people who may purchase items. On each referral you have, you get :percentage% of their purchases.', 'Our referral program offers a great way to earn more income on the platform by just referring people who may purchase items. On each referral you have, you get :percentage% of their purchases.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1574, 'You do not have any referrals', 'You do not have any referrals', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1575, 'Share your referral link with your friends or via social media so that your referrals appear here.', 'Share your referral link with your friends or via social media so that your referrals appear here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1576, 'Purchased Item', 'Purchased Item', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1577, 'Explain the reason for requesting a refund to the author, which will help to process your request faster', 'Explain the reason for requesting a refund to the author, which will help to process your request faster', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1578, 'You do not have any refund requests', 'You do not have any refund requests', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1579, 'You do not have any refund requests, when you have refund requests you will see them here.', 'You do not have any refund requests, when you have refund requests you will see them here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1580, 'Refund: :item_name', 'Refund: :item_name', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1581, 'Report a problem', 'Report a problem', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1582, 'Accept', 'Accept', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1583, 'Decline', 'Decline', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1584, '2FA Authentication', '2FA Authentication', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1585, 'My Badges', 'My Badges', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1586, 'You do not have any badges', 'You do not have any badges', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1587, 'When you have badges, they will appear here and you can sort them as you want.', 'When you have badges, they will appear here and you can sort them as you want.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1588, 'KYC Verification', 'KYC Verification', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1589, 'Exclusivity of Your Items', 'Exclusivity of Your Items', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1590, 'You will be awarded an exclusive author badge', 'You will be awarded an exclusive author badge', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1591, 'KYC Verification Completed', 'KYC Verification Completed', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1592, 'Congratulations! Your KYC verification has been successfully completed. You can now fully access our platform without any restrictions.', 'Congratulations! Your KYC verification has been successfully completed. You can now fully access our platform without any restrictions.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1593, 'ID Verification', 'ID Verification', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1594, 'Upload a clear, legible image and Ensure that all relevant details, such as your name, photo, and ID number, are visible. the image must be type of .JPG or .PNG', 'Upload a clear, legible image and Ensure that all relevant details, such as your name, photo, and ID number, are visible. the image must be type of .JPG or .PNG', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1595, 'National ID Number', 'National ID Number', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1596, 'Passport Number', 'Passport Number', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1597, 'Front Of ID', 'Front Of ID', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1598, 'Back Of ID', 'Back Of ID', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1599, 'Upload a clear selfie and Ensure it\'s well-lit and visible. the image must be type of.JPG or .PNG', 'Upload a clear selfie and Ensure it\'s well-lit and visible. the image must be type of.JPG or .PNG', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1600, 'Allowed types (JPG,PNG) Size 120x120px', 'Allowed types (JPG,PNG) Size 120x120px', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1601, 'Allowed types (JPG,PNG) Size 1200x500px', 'Allowed types (JPG,PNG) Size 1200x500px', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1602, 'Add your email to enable the contact form in your profile.', 'Add your email to enable the contact form in your profile.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1603, 'You do not have any statements', 'You do not have any statements', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1604, 'You do not have any statements, when you have statements you will see them here.', 'You do not have any statements, when you have statements you will see them here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1605, 'Max :max files can be uploaded', 'Max :max files can be uploaded', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1606, 'You do not have any support tickets', 'You do not have any support tickets', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1607, 'You do not have any support tickets, when you have tickets with our support you will see them here.', 'You do not have any support tickets, when you have tickets with our support you will see them here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1608, 'You do not have any transactions', 'You do not have any transactions', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1609, 'You do not have any transactions currently, when you purchase new items transactions will show up here', 'You do not have any transactions currently, when you purchase new items transactions will show up here', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1610, 'Invoice #:number', 'Invoice #:number', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1611, 'Number', 'Number', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1612, 'Billed to', 'Billed to', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1613, '* This transaction was processed by :payment_method', '* This transaction was processed by :payment_method', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1614, 'Print', 'Print', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1615, 'Available Balance', 'Available Balance', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1616, 'Pending Withdrawn', 'Pending Withdrawn', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1617, 'Total Withdraw', 'Total Withdraw', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1618, 'You do not have any withdrawals', 'You do not have any withdrawals', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1619, 'When you have withdrawal requests, you will be able to see them here.', 'When you have withdrawal requests, you will be able to see them here.', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1620, 'Are you sure you want to withdraw :amount to :account via :method?', 'Are you sure you want to withdraw :amount to :account via :method?', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1621, 'Confirm', 'Confirm', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1622, 'Your balance is less than the minimum withdrawal limit', 'Your balance is less than the minimum withdrawal limit', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1623, 'Missing withdrawal details alert', 'Missing withdrawal details alert', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1624, 'Setup Now', 'Setup Now', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1626, 'Forbidden', 'Forbidden', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1628, 'Page Not Found', 'Page Not Found', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1630, 'Page Expired', 'Page Expired', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1632, 'Too Many Requests', 'Too Many Requests', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1634, 'Server Error', 'Server Error', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1636, 'Service Unavailable', 'Service Unavailable', '2024-05-15 22:26:49', '2024-05-15 22:26:49'),
(1638, 'translates', 'translates', '2024-05-15 22:27:02', '2024-05-15 22:27:02'),
(1639, 'Home', 'Home', '2024-05-15 22:27:29', '2024-05-15 22:27:29'),
(1640, 'Cart', 'Cart', '2024-05-15 22:27:29', '2024-05-15 22:27:29'),
(1641, 'Google', 'Google', '2024-05-15 22:28:19', '2024-05-15 22:28:19'),
(1642, 'Workspace', 'Workspace', '2024-05-15 22:28:48', '2024-05-15 22:28:48'),
(1643, 'Cache Cleared Successfully', 'Cache Cleared Successfully', '2024-05-15 22:33:30', '2024-05-15 22:33:30'),
(1644, 'Updated Successfully', 'Updated Successfully', '2024-05-15 22:33:36', '2024-05-15 22:33:36'),
(1645, 'Terms of use link', 'Terms of use link', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1646, 'Author terms', 'Author terms', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1647, 'Referral terms', 'Referral terms', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1648, 'Licenses terms link', 'Licenses terms link', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1649, 'Registration', 'Registration', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1650, 'Email verification', 'Email verification', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1651, 'Api', 'Api', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1652, 'Gdpr cookie', 'Gdpr cookie', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1653, 'Force ssl', 'Force ssl', '2024-05-15 22:34:16', '2024-05-15 22:34:16'),
(1654, 'referral', 'referral', '2024-05-15 22:34:30', '2024-05-15 22:34:30'),
(1655, 'kyc', 'kyc', '2024-05-15 22:34:35', '2024-05-15 22:34:35'),
(1656, 'KYC Verification Approved', 'KYC Verification Approved', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1657, 'KYC Verification Rejected', 'KYC Verification Rejected', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1658, 'New Ticket Reply', 'New Ticket Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1659, 'Buyer Item Update', 'Buyer Item Update', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1660, 'Follower New Item', 'Follower New Item', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1661, 'Payment Confirmation', 'Payment Confirmation', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1662, 'Purchase Confirmation', 'Purchase Confirmation', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1663, 'Transaction Cancelled', 'Transaction Cancelled', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1664, 'Item Comment Reply', 'Item Comment Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1665, 'Refund Request New Reply', 'Refund Request New Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1666, 'Refund Request Accepted', 'Refund Request Accepted', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1667, 'Refund Request Declined', 'Refund Request Declined', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1668, 'Author Item Soft Rejected', 'Author Item Soft Rejected', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1669, 'Author Item Approved', 'Author Item Approved', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1670, 'Author Item Hard Rejected', 'Author Item Hard Rejected', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1671, 'Author Item Update Approved', 'Author Item Update Approved', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1672, 'Author Item Update Rejected', 'Author Item Update Rejected', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1673, 'Author Item Review ', 'Author Item Review ', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1674, 'Author Item Comment ', 'Author Item Comment ', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1675, 'Author Item Comment Reply', 'Author Item Comment Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1676, 'Author Withdrawal Status Updated', 'Author Withdrawal Status Updated', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1677, 'Author New Refund Request', 'Author New Refund Request', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1678, 'Author Refund Request New Reply', 'Author Refund Request New Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1679, 'Reviewer Item Pending', 'Reviewer Item Pending', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1680, 'Reviewer Item Resubmitted', 'Reviewer Item Resubmitted', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1681, 'Reviewer Item Update', 'Reviewer Item Update', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1682, 'Admin KYC Pending', 'Admin KYC Pending', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1683, 'Admin Item Pending', 'Admin Item Pending', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1684, 'Admin Item Resubmitted', 'Admin Item Resubmitted', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1685, 'Admin Item Update', 'Admin Item Update', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1686, 'Admin Transaction Pending', 'Admin Transaction Pending', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1687, 'Admin Withdrawal Pending', 'Admin Withdrawal Pending', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1688, 'Admin New Ticket', 'Admin New Ticket', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1689, 'Admin New Ticket Reply', 'Admin New Ticket Reply', '2024-05-15 22:34:38', '2024-05-15 22:34:38'),
(1693, 'home page', 'home page', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1694, 'extra codes', 'extra codes', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1695, 'Logo dark', 'Logo dark', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1696, 'Allowed types (JPG,JPEG,PNG,SVG,WEBP)', 'Allowed types (JPG,JPEG,PNG,SVG,WEBP)', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1697, 'Logo light', 'Logo light', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1698, 'Favicon', 'Favicon', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1699, 'Allowed types (JPG,JPEG,PNG,ICO)', 'Allowed types (JPG,JPEG,PNG,ICO)', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1700, 'Social Image', 'Social Image', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1701, 'Allowed types (JPG,JPEG)', 'Allowed types (JPG,JPEG)', '2024-05-15 22:34:54', '2024-05-15 22:34:54'),
(1702, 'Primary Color', 'Primary Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1703, 'Secondary Color', 'Secondary Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1704, 'Background Color', 'Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1705, 'Navbar 1 Background Color', 'Navbar 1 Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1706, 'Navbar 2 Background Color', 'Navbar 2 Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1709, 'Header Background Color', 'Header Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1710, 'Swiper Background Color', 'Swiper Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1711, 'Text Color', 'Text Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1712, 'Heading Color', 'Heading Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1713, 'Text Muted', 'Text Muted', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1714, 'Border Color', 'Border Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1715, 'Dashboard Sidebar Header Background Color', 'Dashboard Sidebar Header Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1716, 'Dashboard Sidebar Body Background Color', 'Dashboard Sidebar Body Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1717, 'Dashboard Sidebar Elements Background Color', 'Dashboard Sidebar Elements Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1718, 'Files Icon Color', 'Files Icon Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1719, 'Item Preview Navbar Background', 'Item Preview Navbar Background', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1720, 'Footer Background Color', 'Footer Background Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1721, 'Footer Border Color', 'Footer Border Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1722, 'Footer Text Color', 'Footer Text Color', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1723, 'Choose Header Background', 'Choose Header Background', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1724, 'Supported (JPEG, JPG, PNG, SVG, WEBP) Size 1920x700px.', 'Supported (JPEG, JPG, PNG, SVG, WEBP) Size 1920x700px.', '2024-05-15 22:34:56', '2024-05-15 22:34:56'),
(1725, 'Profile Author Header background', 'Profile Author Header background', '2024-05-15 22:34:57', '2024-05-15 22:34:57'),
(1726, 'Supported (JPEG, JPG, PNG) Size 1900x275px.', 'Supported (JPEG, JPG, PNG) Size 1900x275px.', '2024-05-15 22:34:57', '2024-05-15 22:34:57'),
(1727, 'Footer About', 'Footer About', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1728, 'Hide', 'Hide', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1729, 'Show', 'Show', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1730, 'Footer Payment Methods', 'Footer Payment Methods', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1731, 'Footer Logo', 'Footer Logo', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1732, 'Footer Payment Methods Logo', 'Footer Payment Methods Logo', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1733, 'Allowed types (JPG,JPEG,PNG)', 'Allowed types (JPG,JPEG,PNG)', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1734, 'About Content', 'About Content', '2024-05-15 22:34:58', '2024-05-15 22:34:58'),
(1735, 'Head Code', 'Head Code', '2024-05-15 22:34:59', '2024-05-15 22:34:59'),
(1736, 'Footer Code', 'Footer Code', '2024-05-15 22:34:59', '2024-05-15 22:34:59'),
(1737, 'info', 'info', '2024-05-15 22:35:13', '2024-05-15 22:35:13'),
(1738, 'Sidebar background color', 'Sidebar background color', '2024-05-15 22:35:16', '2024-05-15 22:35:16'),
(1739, 'Navbar background color', 'Navbar background color', '2024-05-15 22:35:16', '2024-05-15 22:35:16'),
(1740, 'cronjob', 'cronjob', '2024-05-15 22:35:18', '2024-05-15 22:35:18'),
(1742, 'B', 'B', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1743, 'KB', 'KB', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1744, 'MB', 'MB', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1745, 'GB', 'GB', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1746, 'TB', 'TB', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1747, 'PB', 'PB', '2024-05-15 22:40:12', '2024-05-15 22:40:12'),
(1748, 'Account details has been updated successfully', 'Account details has been updated successfully', '2024-05-15 22:41:24', '2024-05-15 22:41:24'),
(1749, 'Profile details has been updated successfully', 'Profile details has been updated successfully', '2024-05-15 22:41:36', '2024-05-15 22:41:36'),
(1750, 'Withdrawal details has been updated successfully', 'Withdrawal details has been updated successfully', '2024-05-15 22:41:43', '2024-05-15 22:41:43'),
(1751, 'API key has been generated successfully', 'API key has been generated successfully', '2024-05-15 22:41:54', '2024-05-15 22:41:54'),
(1752, 'Your current password does not matches with the password you provided.', 'Your current password does not matches with the password you provided.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1753, 'New Password cannot be same as your current password. Please choose a different password.', 'New Password cannot be same as your current password. Please choose a different password.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1754, 'Invalid OTP code', 'Invalid OTP code', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1755, '2FA Authentication has been enabled successfully', '2FA Authentication has been enabled successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1756, '2FA Authentication has been disabled successfully', '2FA Authentication has been disabled successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1757, 'The code cannot be empty', 'The code cannot be empty', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1758, 'ZipArchive extension is not enabled', 'ZipArchive extension is not enabled', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1759, 'Could not open the theme zip file', 'Could not open the theme zip file', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1760, 'Theme Config is missing', 'Theme Config is missing', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1761, 'Invalid theme files', 'Invalid theme files', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1762, 'Failed to validate the purchase code', 'Failed to validate the purchase code', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1763, 'The :addon_name addon require :script_name version :script_version or above', 'The :addon_name addon require :script_name version :script_version or above', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1765, 'Cannot unprepared the database file ', 'Cannot unprepared the database file ', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1766, 'Theme uploaded successfully', 'Theme uploaded successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1767, 'Theme has been changed Successfully', 'Theme has been changed Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1768, 'Settings file is missing', 'Settings file is missing', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1769, 'Created Successfully', 'Created Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1770, 'Deleted Successfully', 'Deleted Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1771, 'The selected category has articles, it cannot be deleted', 'The selected category has articles, it cannot be deleted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1772, 'Published Successfully', 'Published Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1773, 'The selected category has items, it cannot be deleted', 'The selected category has items, it cannot be deleted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1774, 'The selected category has subCategories, it cannot be deleted', 'The selected category has subCategories, it cannot be deleted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1775, 'Options required', 'Options required', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1776, 'The selected sub category has items, it cannot be deleted', 'The selected sub category has items, it cannot be deleted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1777, 'The soft rejection reason is required', 'The soft rejection reason is required', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1778, 'Item has marked as deleted successfully', 'Item has marked as deleted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1779, 'Item has permanently deleted successfully', 'Item has permanently deleted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1780, 'The rejection reason is required', 'The rejection reason is required', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1781, 'KYC Verification has been Approved', 'KYC Verification has been Approved', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1782, 'KYC Verification has been Rejected', 'KYC Verification has been Rejected', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1783, 'Sent successfully', 'Sent successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1784, 'Sent error', 'Sent error', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1785, 'Two-Factor authentication cannot activated from admin side', 'Two-Factor authentication cannot activated from admin side', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1786, 'Invalid Category', 'Invalid Category', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1787, 'Withdrawal account cannot be empty', 'Withdrawal account cannot be empty', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1788, 'Credited Successfully', 'Credited Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1789, 'Debited Successfully', 'Debited Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1790, 'Avatar image must be 120x120px', 'Avatar image must be 120x120px', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1791, 'Profile cover image must be 1200x500px', 'Profile cover image must be 1200x500px', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1792, 'The user already has the selected badge', 'The user already has the selected badge', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1793, 'Badge has been added Successfully', 'Badge has been added Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1794, 'All notifications marked as read', 'All notifications marked as read', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1795, 'Read notifications deleted successfully', 'Read notifications deleted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1796, 'Cancelled Successfully', 'Cancelled Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1797, 'Updated Error', 'Updated Error', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1798, 'Settings parameter error', 'Settings parameter error', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1799, 'Max files cannot be less than total screenshots + item preview image + item main files', 'Max files cannot be less than total screenshots + item preview image + item main files', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1800, 'Mismatch credentials', 'Mismatch credentials', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1803, 'Invalid language name', 'Invalid language name', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1804, 'Credentials parameter error', 'Credentials parameter error', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1805, 'Invalid mode', 'Invalid mode', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1806, 'Credentials error', 'Credentials error', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1807, 'Instructions cannot be empty', 'Instructions cannot be empty', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1808, 'Sending failed', 'Sending failed', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1809, 'Could not open the addon zip file', 'Could not open the addon zip file', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1810, 'Addon Config is missing', 'Addon Config is missing', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1811, 'Invalid addon files', 'Invalid addon files', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1812, 'The addon has been installed successfully', 'The addon has been installed successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1813, 'Cannot unprepared the database file {$databaseFile}', 'Cannot unprepared the database file {$databaseFile}', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1814, 'Cron Job key generated successfully', 'Cron Job key generated successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1815, 'Cron Job key removed successfully', 'Cron Job key removed successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1816, 'The selected category has tickets, it cannot be deleted', 'The selected category has tickets, it cannot be deleted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1817, 'Ticket Created Successfully', 'Ticket Created Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1818, 'Your Reply Sent Successfully', 'Your Reply Sent Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1819, 'The requested file are not exists', 'The requested file are not exists', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1820, 'Ticket Closed Successfully', 'Ticket Closed Successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1821, 'Transaction has been paid successfully', 'Transaction has been paid successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1822, 'Transaction has been cancelled successfully', 'Transaction has been cancelled successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1823, 'The status is not changed', 'The status is not changed', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1824, 'Email template is disabled from mail templates', 'Email template is disabled from mail templates', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1825, 'Your account has been blocked', 'Your account has been blocked', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1826, 'Registration is currently disabled.', 'Registration is currently disabled.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1827, 'Authentication failed. Please try again later.', 'Authentication failed. Please try again later.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1828, 'Email has been changed successfully', 'Email has been changed successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1829, 'Your comment is under review it will be published soon', 'Your comment is under review it will be published soon', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1830, 'The cart item has been updated', 'The cart item has been updated', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1832, 'Your review has been successfully published', 'Your review has been successfully published', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1833, 'Your reply has been successfully published', 'Your reply has been successfully published', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1834, 'Payment proof was sent successfully. Our team will review it as soon as possible', 'Payment proof was sent successfully. Our team will review it as soon as possible', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1835, 'An error occurred while calling the API', 'An error occurred while calling the API', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1836, 'Payment failed', 'Payment failed', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1837, 'You cannot send a message to yourself', 'You cannot send a message to yourself', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1838, 'Failed to send the message', 'Failed to send the message', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1839, 'Your message has been sent successfully', 'Your message has been sent successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1840, 'The file upload failed due to an error in the storage provider', 'The file upload failed due to an error in the storage provider', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1841, 'The upload failed due to an error in the storage provider', 'The upload failed due to an error in the storage provider', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1842, 'The download failed due to an error on the storage provider', 'The download failed due to an error on the storage provider', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1843, 'Congratulations! You are now and author', 'Congratulations! You are now and author', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1844, 'Your item has been submitted successfully, we will review it as soon as possible.', 'Your item has been submitted successfully, we will review it as soon as possible.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1845, 'Your item has been added successfully.', 'Your item has been added successfully.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1846, 'You have a pending update please wait until we processed.', 'You have a pending update please wait until we processed.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1847, 'Your update has been submitted successfully, we will review it as soon as possible.', 'Your update has been submitted successfully, we will review it as soon as possible.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1848, 'Description cannot be empty', 'Description cannot be empty', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1849, 'Something went wrong, please refresh the page and try again.', 'Something went wrong, please refresh the page and try again.', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1850, 'One or more of the selected files are expired or not exist', 'One or more of the selected files are expired or not exist', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1851, 'Preview image must be the type of JPG or PNG', 'Preview image must be the type of JPG or PNG', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1853, 'Screenshots must be the type of JPG or PNG', 'Screenshots must be the type of JPG or PNG', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1854, 'The discount cannot start and end at same day', 'The discount cannot start and end at same day', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1855, 'The discount has been created successfully', 'The discount has been created successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1856, 'The discount has been deleted successfully', 'The discount has been deleted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1857, 'The item has been deleted successfully', 'The item has been deleted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1858, 'You have pending refund request for that item already', 'You have pending refund request for that item already', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1859, 'Your refund request has been submitted successfully', 'Your refund request has been submitted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1860, 'Your reply has been sent successfully', 'Your reply has been sent successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1861, 'The refund request has been declined', 'The refund request has been declined', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1862, 'The refund request has been accepted', 'The refund request has been accepted', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1863, 'Your current password does not matches with the password you provided', 'Your current password does not matches with the password you provided', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1864, 'New Password cannot be same as your current password. Please choose a different password', 'New Password cannot be same as your current password. Please choose a different password', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1865, 'Account password has been changed successfully', 'Account password has been changed successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1866, 'Your documents has been submitted successfully', 'Your documents has been submitted successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1867, 'Some uploaded files are not supported', 'Some uploaded files are not supported', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1868, 'The file name contain blocked patterns', 'The file name contain blocked patterns', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1869, 'You cannot upload files of this type', 'You cannot upload files of this type', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1870, 'Unavailable storage provider', 'Unavailable storage provider', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1871, 'Your withdrawal request has been sent successfully', 'Your withdrawal request has been sent successfully', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1872, 'Please complete the KYC verification', 'Please complete the KYC verification', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1873, 'SMTP is not enabled, please enable the smtp from settings', 'SMTP is not enabled, please enable the smtp from settings', '2024-05-15 22:48:16', '2024-05-15 22:48:16'),
(1874, 'You must clear the cache after saving the translations.', 'You must clear the cache after saving the translations.', '2024-05-15 22:55:00', '2024-05-15 22:55:00'),
(1875, 'Update Rejected', 'Update Rejected', '2024-05-16 10:25:31', '2024-05-16 10:25:31'),
(1876, 'Update Approved', 'Update Approved', '2024-05-16 10:25:31', '2024-05-16 10:25:31'),
(1877, 'Update Submission', 'Update Submission', '2024-05-16 10:25:31', '2024-05-16 10:25:31'),
(1878, 'Trust Update', 'Trust Update', '2024-05-16 10:25:31', '2024-05-16 10:25:31'),
(1880, 'Sorry, you are not authorized to access this resource. Please make sure you have the necessary permissions to view this page.', 'Sorry, you are not authorized to access this resource. Please make sure you have the necessary permissions to view this page.', '2024-05-16 20:56:20', '2024-05-16 20:56:20'),
(1881, 'Sorry, the page you are looking for could not be found. It may have been moved, renamed, or deleted. Please check the URL and try again, or back to the homepage', 'Sorry, the page you are looking for could not be found. It may have been moved, renamed, or deleted. Please check the URL and try again, or back to the homepage', '2024-05-16 20:56:36', '2024-05-16 20:56:36'),
(1882, 'Sorry, your session has expired, or the form has become invalid. Please refresh the page and try again.', 'Sorry, your session has expired, or the form has become invalid. Please refresh the page and try again.', '2024-05-16 20:56:44', '2024-05-16 20:56:44'),
(1883, 'Sorry, you have exceeded the rate limit for accessing this resource. Please wait a few minutes and try again.', 'Sorry, you have exceeded the rate limit for accessing this resource. Please wait a few minutes and try again.', '2024-05-16 20:56:50', '2024-05-16 20:56:50'),
(1884, 'Sorry, there was an internal server error, and we were unable to fulfill your request. Please try again later.', 'Sorry, there was an internal server error, and we were unable to fulfill your request. Please try again later.', '2024-05-16 20:56:58', '2024-05-16 20:56:58'),
(1885, 'Sorry, the server is currently unavailable, and we are unable to fulfill your request. Please try again later.', 'Sorry, the server is currently unavailable, and we are unable to fulfill your request. Please try again later.', '2024-05-16 20:57:04', '2024-05-16 20:57:04'),
(1886, 'Trust Submission', 'Trust Submission', '2024-05-16 20:58:42', '2024-05-16 20:58:42'),
(1887, 'Invalid request', 'Invalid request', '2024-05-16 21:39:07', '2024-05-16 21:39:07'),
(1888, 'Home Page (Top)', 'Home Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1889, 'Home Page (Center)', 'Home Page (Center)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1890, 'Home Page (Bottom)', 'Home Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1891, 'Item Page (Top)', 'Item Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1892, 'Item Page (Center)', 'Item Page (Center)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1893, 'Item Page (Bottom)', 'Item Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1894, 'Category Page (Top)', 'Category Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1895, 'Category Page (Bottom)', 'Category Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1896, 'Search Page (Top)', 'Search Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1897, 'Search Page (Bottom)', 'Search Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1898, 'Blog Page (Top)', 'Blog Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1899, 'Blog Page (Bottom)', 'Blog Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1900, 'Blog Article Page (Top)', 'Blog Article Page (Top)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1901, 'Blog Article Page (Bottom)', 'Blog Article Page (Bottom)', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1902, 'ads', 'ads', '2024-05-16 21:39:19', '2024-05-16 21:39:19'),
(1903, 'Google reCAPTCHA', 'Google reCAPTCHA', '2024-05-17 18:19:22', '2024-05-17 18:19:22'),
(1904, 'Google Analytics 4', 'Google Analytics 4', '2024-05-17 18:19:22', '2024-05-17 18:19:22'),
(1905, 'Tawk.to', 'Tawk.to', '2024-05-17 18:19:22', '2024-05-17 18:19:22'),
(1906, 'ticket', 'ticket', '2024-05-17 19:06:28', '2024-05-17 19:06:28'),
(1907, 'levels', 'levels', '2024-05-17 19:08:59', '2024-05-17 19:08:59'),
(1908, 'Search...', 'Search...', '2024-05-17 19:21:11', '2024-05-17 19:21:11'),
(1909, 'Search', 'Search', '2024-05-17 19:23:53', '2024-05-17 19:23:53'),
(1912, 'sandbox', 'sandbox', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1913, 'live', 'live', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1914, 'Webhook Event', 'Webhook Event', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1915, 'Webhook Endpoint', 'Webhook Endpoint', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1916, 'client secret', 'client secret', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1917, 'webhook id', 'webhook id', '2024-05-17 19:24:18', '2024-05-17 19:24:18'),
(1920, 'create', 'create', '2024-05-17 19:24:47', '2024-05-17 19:24:47'),
(1922, 'publishable key', 'publishable key', '2024-05-18 13:41:48', '2024-05-18 13:41:48'),
(1923, 'secret key', 'secret key', '2024-05-18 13:41:48', '2024-05-18 13:41:48'),
(1924, 'webhook secret', 'webhook secret', '2024-05-18 13:41:48', '2024-05-18 13:41:48'),
(1926, ':count Sale', ':count Sale', '2024-05-18 17:31:21', '2024-05-18 17:31:21'),
(1929, 'Connection Failed', 'Connection Failed', '2024-05-18 18:28:33', '2024-05-18 18:28:33'),
(1930, 'Connected successfully', 'Connected successfully', '2024-05-18 18:28:33', '2024-05-18 18:28:33'),
(1931, 'Test Connection', 'Test Connection', '2024-05-18 18:29:21', '2024-05-18 18:29:21'),
(1932, 'Send Mail to :email', 'Send Mail to :email', '2024-05-18 18:29:30', '2024-05-18 18:29:30'),
(1933, 'Add badge to :username', 'Add badge to :username', '2024-05-18 18:29:45', '2024-05-18 18:29:45'),
(1936, 'measurement id', 'measurement id', '2024-05-18 21:56:22', '2024-05-18 21:56:22'),
(1937, 'site key', 'site key', '2024-05-18 21:56:32', '2024-05-18 21:56:32'),
(1938, 'public key', 'public key', '2024-05-20 09:53:50', '2024-05-20 09:53:50'),
(1939, 'key id', 'key id', '2024-05-20 09:53:51', '2024-05-20 09:53:51'),
(1940, 'key secret', 'key secret', '2024-05-20 09:53:51', '2024-05-20 09:53:51'),
(1941, 'webhook shared secret', 'webhook shared secret', '2024-05-20 09:53:51', '2024-05-20 09:53:51'),
(1942, 'auth token', 'auth token', '2024-05-20 09:53:53', '2024-05-20 09:53:53'),
(1943, 'secret hash', 'secret hash', '2024-05-20 09:53:53', '2024-05-20 09:53:53'),
(1944, 'Finish URL', 'Finish URL', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1945, 'Unfinish URL', 'Unfinish URL', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1946, 'Error Payment URL', 'Error Payment URL', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1947, 'server key', 'server key', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1948, 'api secret key', 'api secret key', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1949, 'webhook verification token', 'webhook verification token', '2024-05-20 09:53:54', '2024-05-20 09:53:54'),
(1950, ':count Sales', ':count Sales', '2024-05-29 10:44:12', '2024-05-29 10:44:12'),
(1951, 'Buy Now Button', 'Buy Now Button', '2024-05-29 13:20:54', '2024-05-29 13:20:54'),
(1952, 'ipn secret key', 'ipn secret key', '2024-05-29 17:19:17', '2024-05-29 17:19:17'),
(1953, ':key cannot be empty', ':key cannot be empty', '2024-05-29 17:20:21', '2024-05-29 17:20:21'),
(1954, 'Buyer Taxes', 'Buyer Taxes', '2024-05-30 15:39:49', '2024-05-30 15:39:49'),
(1955, 'Author Taxes', 'Author Taxes', '2024-05-30 15:39:49', '2024-05-30 15:39:49'),
(1956, 'Rate', 'Rate', '2024-05-30 15:47:48', '2024-05-30 15:47:48'),
(1957, 'Invalid Country', 'Invalid Country', '2024-05-30 16:00:53', '2024-05-30 16:00:53'),
(1958, 'Edit Author Tax', 'Edit Author Tax', '2024-05-30 16:06:20', '2024-05-30 16:06:20'),
(1959, ':country is already exists', ':country is already exists', '2024-05-30 17:12:12', '2024-05-30 17:12:12'),
(1960, '+:count more', '+:count more', '2024-05-30 17:18:00', '2024-05-30 17:18:00'),
(1961, 'New Author Tax', 'New Author Tax', '2024-05-30 18:38:14', '2024-05-30 18:38:14'),
(1962, 'New Buyer Tax', 'New Buyer Tax', '2024-05-30 18:42:20', '2024-05-30 18:42:20'),
(1963, 'Edit Buyer Tax', 'Edit Buyer Tax', '2024-05-30 18:45:04', '2024-05-30 18:45:04'),
(1965, ':payment_gateway Fees (:percentage%)', ':payment_gateway Fees (:percentage%)', '2024-05-30 21:00:00', '2024-05-30 21:00:00'),
(1966, ':count Countries', ':count Countries', '2024-05-31 09:31:59', '2024-05-31 09:31:59'),
(1967, 'There has been a change in your transaction', 'There has been a change in your transaction', '2024-05-31 10:09:40', '2024-05-31 10:09:40'),
(1968, ':tax_name (:tax_rate%)', ':tax_name (:tax_rate%)', '2024-05-31 12:23:43', '2024-05-31 12:23:43'),
(1969, 'Tax', 'Tax', '2024-05-31 14:15:03', '2024-05-31 14:15:03'),
(1973, '[:tax_name (:tax_rate%)] Purchase #:id (:item_name)', '[:tax_name (:tax_rate%)] Purchase #:id (:item_name)', '2024-05-31 20:05:49', '2024-05-31 20:05:49');
INSERT INTO `translates` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1974, '[:tax_name (:tax_rate%)] Sale #:id (:item_name)', '[:tax_name (:tax_rate%)] Sale #:id (:item_name)', '2024-05-31 20:05:49', '2024-05-31 20:05:49'),
(1975, '[Refund] :tax_name (:tax_rate%) Purchase #:id (:item_name)', '[Refund] :tax_name (:tax_rate%) Purchase #:id (:item_name)', '2024-05-31 20:24:06', '2024-05-31 20:24:06'),
(1976, '[Refund] :tax_name (:tax_rate%) Sale #:id (:item_name)', '[Refund] :tax_name (:tax_rate%) Sale #:id (:item_name)', '2024-05-31 20:24:06', '2024-05-31 20:24:06'),
(1977, '[Cancellation] :tax_name (:tax_rate%) Purchase #:id (:item_name)', '[Cancellation] :tax_name (:tax_rate%) Purchase #:id (:item_name)', '2024-05-31 20:58:23', '2024-05-31 20:58:23'),
(1978, '[Cancellation] :tax_name (:tax_rate%) Sale #:id (:item_name)', '[Cancellation] :tax_name (:tax_rate%) Sale #:id (:item_name)', '2024-05-31 20:58:23', '2024-05-31 20:58:23'),
(1979, 'Support tickets', 'Support tickets', '2024-06-01 09:52:46', '2024-06-01 09:52:46'),
(1980, 'Contact page', 'Contact page', '2024-06-01 10:27:00', '2024-06-01 10:27:00'),
(1981, 'Contact Email', 'Contact Email', '2024-06-01 10:29:34', '2024-06-01 10:29:34'),
(1982, 'Contact email is required to enable contact page', 'Contact email is required to enable contact page', '2024-06-01 10:30:07', '2024-06-01 10:30:07'),
(1983, 'This email is required to receive emails from contact page', 'This email is required to receive emails from contact page', '2024-06-01 10:32:55', '2024-06-01 10:32:55'),
(1984, 'Contact US', 'Contact US', '2024-06-01 10:34:11', '2024-06-01 10:34:11'),
(1985, 'Trending Item Badge Color', 'Trending Item Badge Color', '2024-06-01 13:16:57', '2024-06-01 13:16:57'),
(1986, 'Sale Item Badge Color', 'Sale Item Badge Color', '2024-06-01 13:16:57', '2024-06-01 13:16:57'),
(1987, 'Free Item Badge Color', 'Free Item Badge Color', '2024-06-01 13:16:57', '2024-06-01 13:16:57'),
(1988, 'Free', 'Free', '2024-06-01 13:17:25', '2024-06-01 13:17:25'),
(1989, 'Free Item', 'Free Item', '2024-06-01 13:36:42', '2024-06-01 13:36:42'),
(1990, 'The author :author has offered the item for free, you can now download it.', 'The author :author has offered the item for free, you can now download it.', '2024-06-01 20:29:15', '2024-06-01 20:29:15'),
(1991, 'Free items policy', 'Free items policy', '2024-06-01 13:38:20', '2024-06-01 13:38:20'),
(1998, 'Free item option', 'Free item option', '2024-06-01 19:45:32', '2024-06-01 19:45:32'),
(1999, 'You can allow downloading your item for free, please note that everyone can download the item directly from the item page without purchasing, please make sure your item has no purchase code verification.', 'You can allow downloading your item for free, please note that everyone can download the item directly from the item page without purchasing, please make sure your item has no purchase code verification.', '2024-06-01 19:47:42', '2024-06-01 19:47:42'),
(2001, ':username has registered', ':username has registered', '2024-06-02 19:56:35', '2024-06-02 19:56:35'),
(2002, '(:count Review)', '(:count Review)', '2024-06-03 18:08:26', '2024-06-03 18:08:26'),
(2003, 'Recently Updated', 'Recently Updated', '2024-06-03 18:09:31', '2024-06-03 18:09:31'),
(2005, 'Addon', 'Addon', '2024-06-07 10:32:44', '2024-06-07 10:32:44'),
(2016, 'Version', 'Version', '2024-06-07 17:40:04', '2024-06-07 17:40:04'),
(2017, 'Tools', 'Tools', '2024-06-07 19:40:03', '2024-06-07 19:40:03'),
(2021, 'Drag and drop or click here to upload, allowed types (:types) and max file size is :max_file_size', 'Drag and drop or click here to upload, allowed types (:types) and max file size is :max_file_size', '2024-06-10 17:25:34', '2024-06-10 17:25:34'),
(2022, 'Item files that will buyers download (:types).', 'Item files that will buyers download (:types).', '2024-06-10 17:25:34', '2024-06-10 17:25:34'),
(2023, 'Maintenance Mode', 'Maintenance Mode', '2024-06-16 19:07:34', '2024-06-16 19:07:34'),
(2026, 'Icon', 'Icon', '2024-06-16 19:07:34', '2024-06-16 19:07:34'),
(2027, 'Image', 'Image', '2024-06-16 19:07:34', '2024-06-16 19:07:34'),
(2028, 'Maintenance', 'Maintenance', '2024-06-16 19:07:34', '2024-06-16 19:07:34'),
(2029, 'Note!', 'Note!', '2024-06-16 19:09:25', '2024-06-16 19:09:25'),
(2030, 'As an admin, you can still view and control your website but the visitors will redirect to the maintenance page.', 'As an admin, you can still view and control your website but the visitors will redirect to the maintenance page.', '2024-06-16 19:09:25', '2024-06-16 19:09:25'),
(2031, 'The email type are not allowed.', 'The email type are not allowed.', '2024-06-16 20:01:52', '2024-06-16 20:01:52'),
(2032, 'License Certificate for :item_name', 'License Certificate for :item_name', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2033, 'License Certificate', 'License Certificate', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2034, 'This document certifies the purchase of the following license:', 'This document certifies the purchase of the following license:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2035, 'Details of the license can be accessed from your workspace purchases page.', 'Details of the license can be accessed from your workspace purchases page.', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2036, 'Licensor\'s Author Username:', 'Licensor\'s Author Username:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2037, 'Licensee:', 'Licensee:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2038, 'Item ID:', 'Item ID:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2039, 'Item Name:', 'Item Name:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2040, 'Item URL:', 'Item URL:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2041, 'Item Purchase Code:', 'Item Purchase Code:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2042, 'Purchase Date:', 'Purchase Date:', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2043, 'For any queries related to this document or licenses please contact us via', 'For any queries related to this document or licenses please contact us via', '2024-06-17 16:45:56', '2024-06-17 16:45:56'),
(2044, 'View All Featured Items', 'View All Featured Items', '2024-06-19 10:18:37', '2024-06-19 10:18:37'),
(2045, 'Enter the external URL where the buyer will be redirected to download the file.', 'Enter the external URL where the buyer will be redirected to download the file.', '2024-06-19 10:29:25', '2024-06-19 10:29:25'),
(2046, 'You can also allow the purchase option along with the free download in case anyone wants to purchase a license.', 'You can also allow the purchase option along with the free download in case anyone wants to purchase a license.', '2024-06-19 10:29:25', '2024-06-19 10:29:25'),
(2047, 'Enable Purchasing', 'Enable Purchasing', '2024-06-19 10:29:25', '2024-06-19 10:29:25'),
(2048, 'Disable Purchasing', 'Disable Purchasing', '2024-06-19 10:29:25', '2024-06-19 10:29:25'),
(2049, 'Purchasing Enabled', 'Purchasing Enabled', '2024-06-19 10:30:55', '2024-06-19 10:30:55'),
(2050, 'Downloads (:count)', 'Downloads (:count)', '2024-06-19 10:41:03', '2024-06-19 10:41:03'),
(2051, 'Featured Item', 'Featured Item', '2024-06-19 10:41:03', '2024-06-19 10:41:03'),
(2052, 'This item was featured on :website_name', 'This item was featured on :website_name', '2024-06-19 10:41:03', '2024-06-19 10:41:03'),
(2053, 'Show free item total downloads', 'Show free item total downloads', '2024-06-19 12:25:37', '2024-06-19 12:25:37'),
(2054, 'External file link option', 'External file link option', '2024-06-19 12:25:37', '2024-06-19 12:25:37'),
(2055, 'Main File Types', 'Main File Types', '2024-06-19 12:25:37', '2024-06-19 12:25:37'),
(2056, 'The allowed files to be uploaded as main file, like (ZIP, RAR, PDF, etc...)', 'The allowed files to be uploaded as main file, like (ZIP, RAR, PDF, etc...)', '2024-06-19 12:25:37', '2024-06-19 12:25:37'),
(2057, 'Featured', 'Featured', '2024-06-19 12:27:16', '2024-06-19 12:27:16'),
(2058, 'Remove Featured', 'Remove Featured', '2024-06-19 12:27:16', '2024-06-19 12:27:16'),
(2059, 'Make Featured', 'Make Featured', '2024-06-19 12:27:16', '2024-06-19 12:27:16'),
(2060, 'Featured Author', 'Featured Author', '2024-06-19 12:32:01', '2024-06-19 12:32:01'),
(2061, 'access token', 'access token', '2024-06-20 13:10:26', '2024-06-20 13:10:26'),
(2062, 'webhook secret signature', 'webhook secret signature', '2024-06-20 13:10:26', '2024-06-20 13:10:26'),
(2063, 'Item Reviews', 'Item Reviews', '2024-06-21 12:23:20', '2024-06-21 12:23:20'),
(2064, 'Item Comments', 'Item Comments', '2024-06-21 12:23:20', '2024-06-21 12:23:20'),
(2065, 'View All Followers', 'View All Followers', '2024-06-21 17:21:45', '2024-06-21 17:21:45'),
(2066, ':count Downloads', ':count Downloads', '2024-06-22 13:48:01', '2024-06-22 13:48:01'),
(2067, 'Changelogs', 'Changelogs', '2024-06-22 14:22:45', '2024-06-22 14:22:45'),
(2068, 'v:version', 'v:version', '2024-06-22 14:22:47', '2024-06-22 14:22:47'),
(2069, ':count Download', ':count Download', '2024-06-23 11:57:35', '2024-06-23 11:57:35'),
(2070, 'Item Changelogs', 'Item Changelogs', '2024-06-26 10:24:34', '2024-06-26 10:24:34'),
(2071, 'Upload failed', 'Upload failed', '2024-06-26 10:34:22', '2024-06-26 10:34:22'),
(2072, 'Social Media Links', 'Social Media Links', '2024-06-28 15:37:45', '2024-06-28 15:37:45'),
(2073, 'X', 'X', '2024-06-28 15:37:45', '2024-06-28 15:37:45'),
(2074, 'vendor id', 'vendor id', '2024-06-28 15:47:11', '2024-06-28 15:47:11'),
(2075, 'auth code', 'auth code', '2024-06-28 16:07:01', '2024-06-28 16:07:01'),
(2076, 'Imgur', 'Imgur', '2024-06-29 14:01:09', '2024-06-29 14:01:09'),
(2077, 'Trustip', 'Trustip', '2024-06-29 14:01:09', '2024-06-29 14:01:09'),
(2078, 'Captcha Providers', 'Captcha Providers', '2024-06-29 14:22:58', '2024-06-29 14:22:58'),
(2079, 'hCaptcha', 'hCaptcha', '2024-06-29 14:26:25', '2024-06-29 14:26:25'),
(2080, 'Cloudflare Turnstile', 'Cloudflare Turnstile', '2024-06-29 14:26:25', '2024-06-29 14:26:25'),
(2081, 'Edit Captcha Provider', 'Edit Captcha Provider', '2024-06-29 14:26:47', '2024-06-29 14:26:47'),
(2082, '(Default)', '(Default)', '2024-06-29 14:28:19', '2024-06-29 14:28:19'),
(2083, 'Make default', 'Make default', '2024-06-29 14:33:42', '2024-06-29 14:33:42'),
(2084, 'The selected captcha provider is not active', 'The selected captcha provider is not active', '2024-06-29 14:33:45', '2024-06-29 14:33:45'),
(2085, 'Captcha verification failed.', 'Captcha verification failed.', '2024-06-29 15:32:35', '2024-06-29 15:32:35'),
(2086, 'Invalid captcha provider', 'Invalid captcha provider', '2024-06-29 15:50:42', '2024-06-29 15:50:42'),
(2087, 'The default captcha providers has been updated', 'The default captcha providers has been updated', '2024-06-29 15:52:15', '2024-06-29 15:52:15'),
(2088, 'Version (Optional)', 'Version (Optional)', '2024-07-01 10:41:09', '2024-07-01 10:41:09'),
(2089, '1.0 or 1.0.0', '1.0 or 1.0.0', '2024-07-01 10:41:09', '2024-07-01 10:41:09'),
(2090, 'Demo Link (Optional)', 'Demo Link (Optional)', '2024-07-01 10:41:09', '2024-07-01 10:41:09'),
(2091, 'Failed to upload (:filename)', 'Failed to upload (:filename)', '2024-07-01 10:42:47', '2024-07-01 10:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_author` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured_author` tinyint(1) NOT NULL DEFAULT '0',
  `balance` double NOT NULL DEFAULT '0',
  `level_id` bigint UNSIGNED DEFAULT NULL,
  `exclusivity` enum('exclusive','non_exclusive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_sales` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_sales_amount` double DEFAULT '0',
  `total_referrals_earnings` double DEFAULT '0',
  `total_reviews` bigint UNSIGNED NOT NULL DEFAULT '0',
  `avg_reviews` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_followers` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_following` bigint UNSIGNED NOT NULL DEFAULT '0',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_heading` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_social_links` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `microsoft_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vkontakte_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `envato_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withdrawal_method_id` bigint UNSIGNED DEFAULT NULL,
  `withdrawal_account` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `was_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `kyc_status` tinyint(1) NOT NULL DEFAULT '0',
  `google2fa_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Disabled, 1: Active',
  `google2fa_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Banned, 1: Active',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `badge_id` bigint UNSIGNED NOT NULL,
  `badge_alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_logs`
--

CREATE TABLE `user_login_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Pending 2:Returned 3:Approved 4:Completed 5:Cancelled	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_methods`
--

CREATE TABLE `withdrawal_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum` bigint NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_id` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawal_methods`
--

INSERT INTO `withdrawal_methods` (`id`, `name`, `minimum`, `description`, `sort_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Paypal', 40, '<p>Lorem Ipsum<span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 1, 1, '2024-03-06 09:42:54', '2024-05-08 17:14:59'),
(2, 'Advcash', 100, '<p><span style=\"background-color:rgb(255,255,255);color:rgb(33,37,41);\">Lorem Ipsum</span><span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 2, 1, '2024-03-06 09:43:29', '2024-03-06 09:47:34'),
(3, 'Binance', 500, '<p><span style=\"background-color:rgb(255,255,255);color:rgb(33,37,41);\">Lorem Ipsum</span><span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 4, 1, '2024-03-06 09:43:44', '2024-03-06 09:44:12'),
(4, 'Payeer', 50, '<p><span style=\"background-color:rgb(255,255,255);color:rgb(33,37,41);\">Lorem Ipsum</span><span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 3, 1, '2024-03-06 09:44:08', '2024-11-14 18:43:46'),
(5, 'Bank Transfer', 500, '<p><span style=\"background-color:rgb(255,255,255);color:rgb(33,37,41);\">Lorem Ipsum</span><span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 6, 1, '2024-03-06 09:44:42', '2024-03-06 09:47:21'),
(6, 'Bitcoin', 100, '<p><span style=\"background-color:rgb(255,255,255);color:rgb(33,37,41);\">Lorem Ipsum</span><span style=\"background-color:rgb(255,255,255);color:rgb(43,43,43);\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>', 5, 1, '2024-03-06 09:45:19', '2024-03-06 09:47:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `addons_alias_unique` (`alias`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD KEY `admin_password_resets_email_index` (`email`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author_taxes`
--
ALTER TABLE `author_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `badges_name_unique` (`name`),
  ADD UNIQUE KEY `badges_country_unique` (`country`),
  ADD UNIQUE KEY `badges_level_id_unique` (`level_id`),
  ADD UNIQUE KEY `badges_membership_years_unique` (`membership_years`);

--
-- Indexes for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_articles_slug_unique` (`slug`),
  ADD KEY `blog_articles_blog_category_id_foreign` (`blog_category_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_slug_unique` (`slug`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_comments_blog_article_id_foreign` (`blog_article_id`),
  ADD KEY `blog_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `bottom_nav_links`
--
ALTER TABLE `bottom_nav_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bottom_nav_links_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `buyer_taxes`
--
ALTER TABLE `buyer_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `captcha_providers`
--
ALTER TABLE `captcha_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_item_id_foreign` (`item_id`),
  ADD KEY `cart_items_user_id_foreign` (`user_id`),
  ADD KEY `cart_items_support_period_id_foreign` (`support_period_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `category_options`
--
ALTER TABLE `category_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_options_category_id_foreign` (`category_id`);

--
-- Indexes for table `category_reviewer`
--
ALTER TABLE `category_reviewer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_reviewer_category_id_foreign` (`category_id`),
  ADD KEY `category_reviewer_reviewer_id_foreign` (`reviewer_id`);

--
-- Indexes for table `editor_images`
--
ALTER TABLE `editor_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`),
  ADD KEY `favorites_item_id_foreign` (`item_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `followers_follower_id_foreign` (`follower_id`),
  ADD KEY `followers_following_id_foreign` (`following_id`);

--
-- Indexes for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `footer_links_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `home_categories`
--
ALTER TABLE `home_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_sections`
--
ALTER TABLE `home_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_name_unique` (`name`),
  ADD UNIQUE KEY `items_slug_unique` (`slug`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `items_ author_id_foreign` (`author_id`) USING BTREE;

--
-- Indexes for table `item_change_logs`
--
ALTER TABLE `item_change_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_change_logs_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_comments`
--
ALTER TABLE `item_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_comments_user_id_foreign` (`user_id`),
  ADD KEY `item_comments_author_id_foreign` (`author_id`),
  ADD KEY `item_comments_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_comment_replies`
--
ALTER TABLE `item_comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_comment_replies_item_comment_id_foreign` (`item_comment_id`),
  ADD KEY `item_comment_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_comment_reports`
--
ALTER TABLE `item_comment_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_comment_reports_item_comment_reply_id_foreign` (`item_comment_reply_id`),
  ADD KEY `item_comment_reports_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_discounts`
--
ALTER TABLE `item_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_discounts_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_histories_reviewer_id_foreign` (`reviewer_id`),
  ADD KEY `item_histories_admin_id_foreign` (`admin_id`),
  ADD KEY `item_histories_item_id_foreign` (`item_id`),
  ADD KEY `item_histories_ author_id_foreign` (`author_id`) USING BTREE;

--
-- Indexes for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_reviews_user_id_foreign` (`user_id`),
  ADD KEY `item_reviews_author_id_foreign` (`author_id`),
  ADD KEY `item_reviews_item_id_foreign` (`item_id`);

--
-- Indexes for table `item_review_replies`
--
ALTER TABLE `item_review_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_review_replies_item_review_id_foreign` (`item_review_id`),
  ADD KEY `item_review_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `item_updates`
--
ALTER TABLE `item_updates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_updates_name_unique` (`name`),
  ADD KEY `item_updates_item_id_foreign` (`item_id`),
  ADD KEY `item_updates_category_id_foreign` (`category_id`),
  ADD KEY `item_updates_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `item_updates_ author_id_foreign` (`author_id`) USING BTREE;

--
-- Indexes for table `item_views`
--
ALTER TABLE `item_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_views_item_id_foreign` (`item_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_verifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `levels_name_unique` (`name`),
  ADD UNIQUE KEY `levels_min_earnings_unique` (`min_earnings`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_providers`
--
ALTER TABLE `oauth_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `premium_earnings`
--
ALTER TABLE `premium_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `premium_earnings_author_id_foreign` (`author_id`),
  ADD KEY `premium_earnings_subscription_id_foreign` (`subscription_id`),
  ADD KEY `premium_earnings_item_id_foreign` (`item_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_code_unique` (`code`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_sale_id_foreign` (`sale_id`),
  ADD KEY `purchases_item_id_foreign` (`item_id`),
  ADD KEY `purchases_author_id_foreign` (`author_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_user_id_foreign` (`user_id`),
  ADD KEY `referrals_author_id_foreign` (`author_id`);

--
-- Indexes for table `referral_earnings`
--
ALTER TABLE `referral_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referral_earnings_referral_id_foreign` (`referral_id`),
  ADD KEY `referral_earnings_author_id_foreign` (`author_id`),
  ADD KEY `referral_earnings_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_user_id_foreign` (`user_id`),
  ADD KEY `refunds_author_id_foreign` (`author_id`),
  ADD KEY `refunds_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `refund_replies`
--
ALTER TABLE `refund_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refund_replies_user_id_foreign` (`user_id`),
  ADD KEY `refund_replies_refund_id_foreign` (`refund_id`);

--
-- Indexes for table `reviewers`
--
ALTER TABLE `reviewers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviewers_username_unique` (`username`),
  ADD UNIQUE KEY `reviewers_email_unique` (`email`);

--
-- Indexes for table `reviewer_password_resets`
--
ALTER TABLE `reviewer_password_resets`
  ADD KEY `reviewer_password_resets_email_index` (`email`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_author_id_foreign` (`author_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_item_id_foreign` (`item_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statements`
--
ALTER TABLE `statements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statements_user_id_foreign` (`user_id`);

--
-- Indexes for table `storage_providers`
--
ALTER TABLE `storage_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_categories_slug_unique` (`slug`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `support_earnings`
--
ALTER TABLE `support_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_earnings_author_id_foreign` (`author_id`),
  ADD KEY `support_earnings_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `support_periods`
--
ALTER TABLE `support_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_periods_name_unique` (`name`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `themes_alias_unique` (`alias`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_ticket_category_id_foreign` (`ticket_category_id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_categories_name_unique` (`name`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`),
  ADD KEY `ticket_replies_admin_id_foreign` (`admin_id`),
  ADD KEY `ticket_replies_ticket_id_foreign` (`ticket_id`);

--
-- Indexes for table `ticket_reply_attachments`
--
ALTER TABLE `ticket_reply_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_reply_attachments_ticket_reply_id_foreign` (`ticket_reply_id`);

--
-- Indexes for table `top_nav_links`
--
ALTER TABLE `top_nav_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `top_nav_links_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_purchase_id_foreign` (`purchase_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_payment_gateway_id_foreign` (`payment_gateway_id`),
  ADD KEY `transactions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_item_id_foreign` (`item_id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `translates`
--
ALTER TABLE `translates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_files_author_id_foreign` (`author_id`) USING BTREE,
  ADD KEY `uploaded_files_category_id_foreign` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_facebook_id_unique` (`facebook_id`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`),
  ADD UNIQUE KEY `envato_id` (`envato_id`),
  ADD UNIQUE KEY `github_id` (`github_id`),
  ADD UNIQUE KEY `microsoft_id` (`microsoft_id`),
  ADD UNIQUE KEY `vkontakte_id` (`vkontakte_id`),
  ADD KEY `users_level_id_foreign` (`level_id`),
  ADD KEY `users_withdrawal_method_id_foreign` (`withdrawal_method_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_badges_user_id_foreign` (`user_id`),
  ADD KEY `user_badges_badge_id_foreign` (`badge_id`);

--
-- Indexes for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_author_id_foreign` (`author_id`) USING BTREE;

--
-- Indexes for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdrawal_methods_name_unique` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `author_taxes`
--
ALTER TABLE `author_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `blog_articles`
--
ALTER TABLE `blog_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bottom_nav_links`
--
ALTER TABLE `bottom_nav_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `buyer_taxes`
--
ALTER TABLE `buyer_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `captcha_providers`
--
ALTER TABLE `captcha_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category_options`
--
ALTER TABLE `category_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `category_reviewer`
--
ALTER TABLE `category_reviewer`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `editor_images`
--
ALTER TABLE `editor_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `home_categories`
--
ALTER TABLE `home_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `home_sections`
--
ALTER TABLE `home_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `item_change_logs`
--
ALTER TABLE `item_change_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_comments`
--
ALTER TABLE `item_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `item_comment_replies`
--
ALTER TABLE `item_comment_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_comment_reports`
--
ALTER TABLE `item_comment_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `item_discounts`
--
ALTER TABLE `item_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_histories`
--
ALTER TABLE `item_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_reviews`
--
ALTER TABLE `item_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `item_review_replies`
--
ALTER TABLE `item_review_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_updates`
--
ALTER TABLE `item_updates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `item_views`
--
ALTER TABLE `item_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `oauth_providers`
--
ALTER TABLE `oauth_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `premium_earnings`
--
ALTER TABLE `premium_earnings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_earnings`
--
ALTER TABLE `referral_earnings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `refund_replies`
--
ALTER TABLE `refund_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviewers`
--
ALTER TABLE `reviewers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `statements`
--
ALTER TABLE `statements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `storage_providers`
--
ALTER TABLE `storage_providers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `support_earnings`
--
ALTER TABLE `support_earnings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `support_periods`
--
ALTER TABLE `support_periods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_reply_attachments`
--
ALTER TABLE `ticket_reply_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `top_nav_links`
--
ALTER TABLE `top_nav_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translates`
--
ALTER TABLE `translates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2118;

--
-- AUTO_INCREMENT for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD CONSTRAINT `blog_articles_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_blog_article_id_foreign` FOREIGN KEY (`blog_article_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bottom_nav_links`
--
ALTER TABLE `bottom_nav_links`
  ADD CONSTRAINT `bottom_nav_links_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `bottom_nav_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_support_period_id_foreign` FOREIGN KEY (`support_period_id`) REFERENCES `support_periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_options`
--
ALTER TABLE `category_options`
  ADD CONSTRAINT `category_options_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_reviewer`
--
ALTER TABLE `category_reviewer`
  ADD CONSTRAINT `category_reviewer_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_reviewer_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `reviewers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_following_id_foreign` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD CONSTRAINT `footer_links_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `footer_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_change_logs`
--
ALTER TABLE `item_change_logs`
  ADD CONSTRAINT `item_change_logs_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_comments`
--
ALTER TABLE `item_comments`
  ADD CONSTRAINT `item_comments_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_comments_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_comment_replies`
--
ALTER TABLE `item_comment_replies`
  ADD CONSTRAINT `item_comment_replies_item_comment_id_foreign` FOREIGN KEY (`item_comment_id`) REFERENCES `item_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_comment_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_comment_reports`
--
ALTER TABLE `item_comment_reports`
  ADD CONSTRAINT `item_comment_reports_item_comment_reply_id_foreign` FOREIGN KEY (`item_comment_reply_id`) REFERENCES `item_comment_replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_comment_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_discounts`
--
ALTER TABLE `item_discounts`
  ADD CONSTRAINT `item_discounts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_histories`
--
ALTER TABLE `item_histories`
  ADD CONSTRAINT `item_histories_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_histories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_histories_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `reviewers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_histories_user_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_reviews`
--
ALTER TABLE `item_reviews`
  ADD CONSTRAINT `item_reviews_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_reviews_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_review_replies`
--
ALTER TABLE `item_review_replies`
  ADD CONSTRAINT `item_review_replies_item_review_id_foreign` FOREIGN KEY (`item_review_id`) REFERENCES `item_reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_review_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_updates`
--
ALTER TABLE `item_updates`
  ADD CONSTRAINT `item_updates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_updates_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_updates_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_updates_user_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_views`
--
ALTER TABLE `item_views`
  ADD CONSTRAINT `item_views_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD CONSTRAINT `kyc_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `premium_earnings`
--
ALTER TABLE `premium_earnings`
  ADD CONSTRAINT `premium_earnings_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `premium_earnings_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `premium_earnings_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_earnings`
--
ALTER TABLE `referral_earnings`
  ADD CONSTRAINT `referral_earnings_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referral_earnings_referral_id_foreign` FOREIGN KEY (`referral_id`) REFERENCES `referrals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referral_earnings_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refund_replies`
--
ALTER TABLE `refund_replies`
  ADD CONSTRAINT `refund_replies_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refund_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `statements`
--
ALTER TABLE `statements`
  ADD CONSTRAINT `statements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_earnings`
--
ALTER TABLE `support_earnings`
  ADD CONSTRAINT `support_earnings_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_earnings_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ticket_category_id_foreign` FOREIGN KEY (`ticket_category_id`) REFERENCES `ticket_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_reply_attachments`
--
ALTER TABLE `ticket_reply_attachments`
  ADD CONSTRAINT `ticket_reply_attachments_ticket_reply_id_foreign` FOREIGN KEY (`ticket_reply_id`) REFERENCES `ticket_replies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `top_nav_links`
--
ALTER TABLE `top_nav_links`
  ADD CONSTRAINT `top_nav_links_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `top_nav_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`),
  ADD CONSTRAINT `transactions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD CONSTRAINT `uploaded_files_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uploaded_files_user_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_withdrawal_method_id_foreign` FOREIGN KEY (`withdrawal_method_id`) REFERENCES `withdrawal_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_badge_id_foreign` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD CONSTRAINT `user_login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
