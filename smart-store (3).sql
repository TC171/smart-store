-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 13, 2026 at 04:21 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart-store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `link`, `position`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tiêu đề', 'banners/8xW6O7VsXuQPd00Iqd83B7GQB3Mbe7zuUNHTkKJD.png', 'https://google.com', 'header', 1, 1, '2026-03-13 02:38:37', '2026-03-13 02:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text,
  `website` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `description`, `website`, `country`, `status`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Apple', 'apple', NULL, NULL, NULL, 'USA', 0, NULL, NULL, '2026-03-01 04:30:46', '2026-03-13 02:46:12', NULL),
(2, 'Samsung', 'samsung', NULL, NULL, NULL, 'Hàn Quốc', 1, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(3, 'Dell', 'dell', NULL, NULL, NULL, 'USA', 1, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(4, 'Xiaomi', 'xiaomi', NULL, NULL, NULL, 'Trung Quốc', 1, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(5, 'Asus', 'asus', NULL, NULL, NULL, 'Đài Loan', 1, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `total_items` int DEFAULT '0',
  `total_price` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `icon`, `parent_id`, `is_featured`, `status`, `sort_order`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Điện thoại', 'dien-thoai', NULL, NULL, NULL, NULL, 1, 1, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-06 09:46:05', NULL),
(2, 'Laptop', 'laptop', NULL, NULL, NULL, NULL, 1, 1, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-13 02:45:54', NULL),
(3, 'Máy tính bảng', 'may-tinh-bang', NULL, NULL, NULL, NULL, 0, 1, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(4, 'Phụ kiện', 'phu-kien', NULL, NULL, NULL, NULL, 0, 1, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(5, 'Đồng hồ thông minh', 'dong-ho', NULL, NULL, NULL, NULL, 0, 1, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-05 18:56:38', '2026-03-05 18:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('fixed','percent') NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `min_order_amount` decimal(15,2) DEFAULT NULL,
  `max_discount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_amount`, `max_discount`, `usage_limit`, `used_count`, `starts_at`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TEST970', 'percent', '10.00', '100.00', '50.00', 100, 0, '2026-03-13 03:11:34', '2026-04-12 03:11:34', 1, '2026-03-13 03:11:34', '2026-03-13 03:11:34'),
(2, 'Sale50', 'percent', '50.00', '15000000.00', '2000000.00', 5, 0, '2026-03-13 03:10:00', '2026-03-14 03:00:00', 1, '2026-03-13 03:12:13', '2026-03-13 03:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_history`
--

CREATE TABLE `inventory_history` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment','return','sale','purchase') NOT NULL,
  `quantity` int NOT NULL,
  `previous_stock` int NOT NULL,
  `current_stock` int NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_history`
--

INSERT INTO `inventory_history` (`id`, `product_variant_id`, `type`, `quantity`, `previous_stock`, `current_stock`, `reference_type`, `reference_id`, `notes`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 23, 'sale', 1, 1, 0, 'order', 2, 'Đơn hàng mẫu #1', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(2, 4, 'sale', 2, 25, 23, 'order', 3, 'Đơn hàng mẫu #2', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(3, 1, 'sale', 2, 20, 18, 'order', 5, 'Đơn hàng mẫu #4', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(4, 29, 'sale', 3, 5, 2, 'order', 6, 'Đơn hàng mẫu #5', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(5, 4, 'sale', 2, 23, 21, 'order', 7, 'Đơn hàng mẫu #6', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(6, 1, 'sale', 2, 18, 16, 'order', 8, 'Đơn hàng mẫu #7', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(7, 4, 'sale', 2, 21, 19, 'order', 8, 'Đơn hàng mẫu #7', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(8, 4, 'sale', 3, 19, 16, 'order', 9, 'Đơn hàng mẫu #8', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(9, 18, 'sale', 1, 1, 0, 'order', 9, 'Đơn hàng mẫu #8', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(10, 1, 'sale', 1, 16, 15, 'order', 10, 'Đơn hàng mẫu #9', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(11, 28, 'sale', 2, 2, 0, 'order', 10, 'Đơn hàng mẫu #9', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_01_24_201734_create_personal_access_tokens_table', 2),
(6, '2026_02_28_225901_add_role_to_users_table', 2),
(7, '2026_03_13_094836_create_product_attributes_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `shipping_fee` decimal(15,2) DEFAULT '0.00',
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','cancelled','refunded') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_district` varchar(100) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL,
  `note` text,
  `ordered_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `coupon_id`, `total_amount`, `shipping_fee`, `discount_amount`, `tax_amount`, `grand_total`, `status`, `payment_status`, `shipping_name`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_district`, `shipping_country`, `note`, `ordered_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(2, 'ORD-20260313-001', 2, NULL, '3.00', '30000.00', '0.00', '0.00', '30003.00', 'completed', 'unpaid', 'Customer 1', '0123456781', 'Address 1', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-03-02 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(3, 'ORD-20260313-002', 6, 1, '23000001.00', '30000.00', '50.00', '0.00', '23029951.00', 'completed', 'unpaid', 'Customer 5', '0123456785', 'Address 5', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-02-23 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(4, 'ORD-20260313-003', 5, NULL, '0.00', '30000.00', '0.00', '0.00', '30000.00', 'pending', 'unpaid', 'Customer 4', '0123456784', 'Address 4', 'Hanoi', 'Quận 1', 'Vietnam', 'Giao hàng cẩn thận', '2026-03-10 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(5, 'ORD-20260313-004', 4, NULL, '67980002.00', '30000.00', '0.00', '0.00', '68010002.00', 'shipping', 'paid', 'Customer 3', '0123456783', 'Address 3', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-03-09 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(6, 'ORD-20260313-005', 4, NULL, '15.00', '30000.00', '0.00', '0.00', '30015.00', 'completed', 'unpaid', 'Customer 3', '0123456783', 'Address 3', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-03-06 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(7, 'ORD-20260313-006', 6, NULL, '23000000.00', '30000.00', '0.00', '0.00', '23030000.00', 'confirmed', 'paid', 'Customer 5', '0123456785', 'Address 5', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-02-15 03:18:54', '2026-03-08 03:18:54', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(8, 'ORD-20260313-007', 3, NULL, '90980000.00', '30000.00', '0.00', '0.00', '91010000.00', 'completed', 'paid', 'Customer 2', '0123456782', 'Address 2', 'Hanoi', 'Quận 1', 'Vietnam', 'Giao hàng cẩn thận', '2026-02-22 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(9, 'ORD-20260313-008', 2, NULL, '34500003.00', '30000.00', '0.00', '0.00', '34530003.00', 'shipping', 'paid', 'Customer 1', '0123456781', 'Address 1', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-03-13 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(10, 'ORD-20260313-009', 4, NULL, '33990010.00', '30000.00', '0.00', '0.00', '34020010.00', 'shipping', 'unpaid', 'Customer 3', '0123456783', 'Address 3', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-02-28 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(11, 'ORD-20260313-010', 3, NULL, '2.00', '30000.00', '0.00', '0.00', '30002.00', 'confirmed', 'paid', 'Customer 2', '0123456782', 'Address 2', 'Hanoi', 'Quận 1', 'Vietnam', NULL, '2026-02-16 03:18:54', NULL, '2026-03-13 03:18:54', '2026-03-13 03:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `product_name`, `sku`, `price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(2, 2, 11, 20, 'iPhone 16 Pro Max', 'IPHON-BLU-128-8', '1.00', 2, '2.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(3, 2, 11, 23, 'iPhone 16 Pro Max', 'IPHON-BLU-258-12', '1.00', 1, '1.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(4, 3, 4, 4, 'Xiaomi Pad 6', 'PAD6-128', '11500000.00', 2, '23000000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(5, 3, 11, 23, 'iPhone 16 Pro Max', 'IPHON-BLU-258-12', '1.00', 1, '1.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(6, 4, 11, 33, 'iPhone 16 Pro Max', 'IPHON-WHI-128gb-12gb', '0.00', 1, '0.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(7, 5, 1, 1, 'iPhone 15 Pro Max', 'IP15PM-256', '33990000.00', 2, '67980000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(8, 5, 11, 21, 'iPhone 16 Pro Max', 'IPHON-BLU-128-12', '1.00', 2, '2.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(9, 5, 11, 31, 'iPhone 16 Pro Max', 'IPHON-RED-128gb-12gb', '0.00', 1, '0.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(10, 6, 4, 29, 'Xiaomi Pad 6', 'XIAOM-BLU-512gb-12gb', '5.00', 3, '15.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(11, 7, 4, 4, 'Xiaomi Pad 6', 'PAD6-128', '11500000.00', 2, '23000000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(12, 7, 11, 33, 'iPhone 16 Pro Max', 'IPHON-WHI-128gb-12gb', '0.00', 2, '0.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(13, 8, 1, 1, 'iPhone 15 Pro Max', 'IP15PM-256', '33990000.00', 2, '67980000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(14, 8, 4, 4, 'Xiaomi Pad 6', 'PAD6-128', '11500000.00', 2, '23000000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(15, 8, 11, 31, 'iPhone 16 Pro Max', 'IPHON-RED-128gb-12gb', '0.00', 1, '0.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(16, 9, 4, 4, 'Xiaomi Pad 6', 'PAD6-128', '11500000.00', 3, '34500000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(17, 9, 11, 18, 'iPhone 16 Pro Max', 'IPHON-BLA-258-8', '1.00', 1, '1.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(18, 9, 11, 24, 'iPhone 16 Pro Max', 'IPHON-PIN-128-8', '1.00', 2, '2.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(19, 10, 1, 1, 'iPhone 15 Pro Max', 'IP15PM-256', '33990000.00', 1, '33990000.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(20, 10, 1, 28, 'iPhone 15 Pro Max', 'IPHON-BLA-128gb-8gb', '5.00', 2, '10.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54'),
(21, 11, 11, 20, 'iPhone 16 Pro Max', 'IPHON-BLU-128-8', '1.00', 2, '2.00', '2026-03-13 03:18:54', '2026-03-13 03:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_method` enum('cod','vnpay','momo','paypal','stripe','bank_transfer') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'VND',
  `status` enum('pending','success','failed','refunded') DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text,
  `description` longtext,
  `category_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `warranty_months` int DEFAULT '12',
  `weight` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `view_count` int DEFAULT '0',
  `sold_count` int DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `short_description`, `description`, `category_id`, `brand_id`, `thumbnail`, `warranty_months`, `weight`, `length`, `width`, `height`, `is_featured`, `is_new`, `status`, `view_count`, `sold_count`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'iPhone cao cấp', 'Mô tả iPhone', 1, 1, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(2, 'Samsung Galaxy S24', 'galaxy-s24', 'Flagship Samsung', 'Mô tả S24', 1, 2, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-02 15:23:59', '2026-03-02 15:23:59'),
(3, 'Dell XPS 15', 'dell-xps-15', 'Laptop cao cấp', 'Mô tả XPS', 2, 3, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(4, 'Xiaomi Pad 6', 'xiaomi-pad-6', 'Tablet mạnh mẽ', 'Mô tả Pad 6', 3, 4, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(5, 'Asus ROG Phone 8', 'rog-phone-8', 'Gaming phone', 'Mô tả ROG', 1, 5, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(6, '1', '1', NULL, NULL, 1, 1, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:02:25', '2026-03-02 21:31:20', '2026-03-02 21:31:20'),
(7, '2', '2', NULL, NULL, 1, 1, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:03:14', '2026-03-02 21:31:17', '2026-03-02 21:31:17'),
(8, '3', '3', NULL, NULL, 1, 1, 'products/htIjzqLhT9ZOiU0cB8SqAXN8hLrJFSd4HAC8j0SZ.png', 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:08:31', '2026-03-02 21:31:11', '2026-03-02 21:31:11'),
(9, '4', '4', NULL, NULL, 1, 1, 'products/ZTxHqZApvm2Ecbzfbc1RYdfjj7d8hbc6JuJhTxDz.jpg', 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:08:57', '2026-03-02 21:31:07', '2026-03-02 21:31:07'),
(11, 'iPhone 16 Pro Max', 'iphone-16-pro-max', NULL, NULL, 1, 1, 'products/r7xEFSsEh5mmUcQUxBR7Jf2mwhZgzXx8uGEnKp8v.png', 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:30:57', '2026-03-13 02:44:25', NULL),
(12, '5', '5', NULL, NULL, 1, 1, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:33:26', '2026-03-05 18:57:11', '2026-03-05 18:57:11'),
(13, '7', '7', NULL, NULL, 1, 1, 'products/zFKSX0lNDsfhxV6qOqTBSB4XjRwM6scVlhov6CcY.png', 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:37:39', '2026-03-05 18:57:08', '2026-03-05 18:57:08'),
(14, '6', '6', NULL, NULL, 1, 1, NULL, 12, NULL, NULL, NULL, NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-03-02 21:53:47', '2026-03-05 17:34:24', '2026-03-05 17:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `type` enum('color','storage','ram') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `type`, `value`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'color', 'Đen', 1, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(2, 'color', 'Trắng', 2, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(3, 'color', 'Xanh dương', 3, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(4, 'color', 'Đỏ', 4, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(5, 'color', 'Xanh lá', 5, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(6, 'color', 'Vàng', 6, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(7, 'color', 'Tím', 7, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(8, 'color', 'Hồng', 8, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(9, 'storage', '64GB', 1, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(10, 'storage', '128GB', 2, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(11, 'storage', '256GB', 3, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(12, 'storage', '512GB', 4, '2026-03-13 02:51:39', '2026-03-13 02:51:39'),
(13, 'storage', '1TB', 5, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(14, 'storage', '2TB', 6, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(15, 'ram', '4GB', 1, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(16, 'ram', '6GB', 2, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(17, 'ram', '8GB', 3, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(18, 'ram', '12GB', 4, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(19, 'ram', '16GB', 5, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(20, 'ram', '32GB', 6, '2026-03-13 02:51:40', '2026-03-13 02:51:40'),
(21, 'storage', '32GB', 1, '2026-03-13 03:03:03', '2026-03-13 03:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `spec_group` varchar(255) DEFAULT NULL,
  `spec_key` varchar(255) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `storage` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `cpu` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `screen_size` varchar(50) DEFAULT NULL,
  `operating_system` varchar(100) DEFAULT NULL,
  `battery` varchar(100) DEFAULT NULL,
  `camera` varchar(100) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `min_stock` int DEFAULT '0',
  `max_stock` int DEFAULT '1000',
  `weight` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `barcode`, `color`, `storage`, `ram`, `cpu`, `gpu`, `screen_size`, `operating_system`, `battery`, `camera`, `price`, `sale_price`, `cost_price`, `stock`, `min_stock`, `max_stock`, `weight`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'IP15PM-256', NULL, 'Đen', '256GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '35000000.00', '33990000.00', NULL, 15, 0, 1000, NULL, NULL, 1, '2026-03-01 04:30:46', '2026-03-13 03:18:54', NULL),
(2, 2, 'S24-256', NULL, 'Xanh', '256GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '28000000.00', '26990000.00', NULL, 30, 0, 1000, NULL, NULL, 1, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(3, 3, 'XPS15-I7', NULL, 'Bạc', '1TB', '16GB', NULL, NULL, NULL, NULL, NULL, NULL, '45000000.00', '43990000.00', NULL, 10, 0, 1000, NULL, NULL, 1, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(4, 4, 'PAD6-128', NULL, 'Xám', '128GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '12000000.00', '11500000.00', NULL, 16, 0, 1000, NULL, NULL, 1, '2026-03-01 04:30:46', '2026-03-13 03:18:54', NULL),
(5, 5, 'ROG8-512', NULL, 'Đỏ', '512GB', '16GB', NULL, NULL, NULL, NULL, NULL, NULL, '30000000.00', '28990000.00', NULL, 15, 0, 1000, NULL, NULL, 1, '2026-03-01 04:30:46', '2026-03-01 04:30:46', NULL),
(10, 1, 'IP15-BLACK-8GB-256GB', NULL, 'Black', '128GB', '16GB', NULL, NULL, NULL, NULL, NULL, NULL, '15000000.00', NULL, NULL, 2, 0, 1000, NULL, NULL, 1, '2026-03-05 15:05:34', '2026-03-05 17:47:39', NULL),
(13, 1, 'IPHON-BLA-128-8', NULL, 'black', '128', '8', NULL, NULL, NULL, NULL, NULL, NULL, '15000.00', NULL, NULL, 10, 0, 1000, NULL, NULL, 1, '2026-03-05 20:29:51', '2026-03-05 20:29:51', NULL),
(14, 1, 'IPH-BLA-258-8', NULL, 'black', '258', '8', NULL, NULL, NULL, NULL, NULL, NULL, '15000.00', NULL, NULL, 5, 0, 1000, NULL, NULL, 1, '2026-03-06 08:16:30', '2026-03-06 08:16:30', NULL),
(16, 11, 'IPHON-BLA-128-8-1', NULL, 'Black', '128GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '150000000.00', NULL, NULL, 10, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 14:54:03', NULL),
(17, 11, 'IPHON-BLA-128-12', NULL, 'Black', '128GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(18, 11, 'IPHON-BLA-258-8', NULL, 'Black', '258GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-13 03:18:54', NULL),
(19, 11, 'IPHON-BLA-258-12', NULL, 'Black', '258GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(20, 11, 'IPHON-BLU-128-8', NULL, 'Blue', '128GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(21, 11, 'IPHON-BLU-128-12', NULL, 'Blue', '128GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(22, 11, 'IPHON-BLU-258-8', NULL, 'Blue', '258GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(23, 11, 'IPHON-BLU-258-12', NULL, 'Blue', '258GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-13 03:18:54', NULL),
(24, 11, 'IPHON-PIN-128-8', NULL, 'Pink', '128GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(25, 11, 'IPHON-PIN-128-12', NULL, 'Pink', '128GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(26, 11, 'IPHON-PIN-258-8', NULL, 'Pink', '258GB', '8GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(27, 11, 'IPHON-PIN-258-12', NULL, 'Pink', '258GB', '12GB', NULL, NULL, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, 1, 0, 1000, NULL, NULL, 1, '2026-03-06 08:38:27', '2026-03-06 08:38:27', NULL),
(28, 1, 'IPHON-BLA-128gb-8gb', NULL, 'black', '128gb', '8gb', NULL, NULL, NULL, NULL, NULL, NULL, '5.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 08:57:01', '2026-03-13 03:18:54', NULL),
(29, 4, 'XIAOM-BLU-512gb-12gb', NULL, 'blue', '512gb', '12gb', NULL, NULL, NULL, NULL, NULL, NULL, '5.00', NULL, NULL, 2, 0, 1000, NULL, NULL, 1, '2026-03-06 09:00:15', '2026-03-13 03:18:54', NULL),
(30, 11, 'IPHON-RED-128gb-8gb', NULL, 'red', '128gb', '8gb', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 14:53:15', '2026-03-06 14:53:15', NULL),
(31, 11, 'IPHON-RED-128gb-12gb', NULL, 'red', '128gb', '12gb', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 14:53:15', '2026-03-06 14:53:15', NULL),
(32, 11, 'IPHON-WHI-128gb-8gb', NULL, 'white', '128gb', '8gb', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 14:53:15', '2026-03-06 14:53:15', NULL),
(33, 11, 'IPHON-WHI-128gb-12gb', NULL, 'white', '128gb', '12gb', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, 0, 0, 1000, NULL, NULL, 1, '2026-03-06 14:53:15', '2026-03-06 14:53:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('eDkADW0SxsyF8C5yjlGoXhclQa0sY7Ne6eM1K9gu', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieGpKNllnYldzUHlSNkYweWtuNzEySjkyNUxnb2dqbHJRbXZOZk5hUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9pbnZlbnRvcnktaGlzdG9yeSI7czo1OiJyb3V0ZSI7czoyMzoiaW52ZW50b3J5LWhpc3RvcnkuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1773375371);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key_name` varchar(255) DEFAULT NULL,
  `value` text,
  `type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_histories`
--

CREATE TABLE `stock_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `type` enum('import','sale','refund','adjustment') DEFAULT NULL,
  `quantity` int NOT NULL,
  `note` text,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'customer',
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `avatar`, `gender`, `date_of_birth`, `address`, `city`, `country`, `postal_code`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$KQzoBbkvDqMK/4q8ucjCUuPmuXcty30ut5URFanIR6f6GQYEFOsIK', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-02-28 16:09:44', '2026-02-28 16:13:00', NULL),
(2, 'Customer 1', 'customer1@example.com', NULL, '$2y$12$1SgVBsxCooM7YhF0c1x56OGRrU.YN0OYDJh53/j983F7xtDSexGRu', 'customer', '0123456781', NULL, NULL, NULL, 'Address 1', 'Hanoi', 'Vietnam', NULL, 1, NULL, NULL, '2026-03-13 03:18:22', '2026-03-13 03:18:22', NULL),
(3, 'Customer 2', 'customer2@example.com', NULL, '$2y$12$jj4NH5FeIMj.u9ULlei2oepwCNbaPxPIVvvATfPEwnuhRMA.cREfG', 'customer', '0123456782', NULL, NULL, NULL, 'Address 2', 'Hanoi', 'Vietnam', NULL, 1, NULL, NULL, '2026-03-13 03:18:23', '2026-03-13 03:18:23', NULL),
(4, 'Customer 3', 'customer3@example.com', NULL, '$2y$12$J10o8welXcEapad00Syitu4eGDsMNi7eCc8F1lbzV.JOEQsaO8SHy', 'customer', '0123456783', NULL, NULL, NULL, 'Address 3', 'Hanoi', 'Vietnam', NULL, 1, NULL, NULL, '2026-03-13 03:18:23', '2026-03-13 03:18:23', NULL),
(5, 'Customer 4', 'customer4@example.com', NULL, '$2y$12$Jl9y/ZWi4T5KPxUi75BXnevsPy8M/FfG8390zmZRleX/gvHIjfus6', 'customer', '0123456784', NULL, NULL, NULL, 'Address 4', 'Hanoi', 'Vietnam', NULL, 1, NULL, NULL, '2026-03-13 03:18:24', '2026-03-13 03:18:24', NULL),
(6, 'Customer 5', 'customer5@example.com', NULL, '$2y$12$gNcV1Xxy4t.k8lh4h2nUOuJbAFwl401B7wLYr1X/TTcaJeQVZYMfu', 'customer', '0123456785', NULL, NULL, NULL, 'Address 5', 'Hanoi', 'Vietnam', NULL, 1, NULL, NULL, '2026-03-13 03:18:25', '2026-03-13 03:18:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_history`
--
ALTER TABLE `inventory_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_id` (`product_variant_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

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
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_attributes_type_value_unique` (`type`,`value`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_history`
--
ALTER TABLE `inventory_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_histories`
--
ALTER TABLE `stock_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_history`
--
ALTER TABLE `inventory_history`
  ADD CONSTRAINT `inventory_history_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD CONSTRAINT `stock_histories_ibfk_1` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
