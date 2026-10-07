-- =========================================================================
-- DATABASE SCHEMA BASELINE: PNMEC - Công Ty CP Cơ Khí & Xây Dựng (web_cty)
-- Database Engine: MySQL / MariaDB (utf8mb4_unicode_ci)
-- =========================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- 1. Table `users` (Quản trị viên & Nhân viên)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table `services` (Dịch vụ)
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `code` VARCHAR(50) DEFAULT NULL,
  `summary` TEXT,
  `content` LONGTEXT,
  `image` VARCHAR(255) DEFAULT 'default-service.jpg',
  `featured` TINYINT(1) DEFAULT 0,
  `views` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table `projects` (Dự án công trình)
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'Xây dựng công nghiệp',
  `client` VARCHAR(255) DEFAULT 'Tập Đoàn Hàn Quốc',
  `location` VARCHAR(255) DEFAULT 'KCN Thăng Long II, Hưng Yên',
  `start_date` DATE DEFAULT NULL,
  `completion_date` DATE DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT 'default-project.jpg',
  `gallery` TEXT,
  `description` TEXT,
  `content` LONGTEXT,
  `detail_mode` ENUM('basic', 'custom') DEFAULT 'basic',
  `detail_blocks` LONGTEXT DEFAULT NULL,
  `views` INT DEFAULT 0,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_by` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table `quotes` (Yêu cầu báo giá trực tuyến)
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `service_type` VARCHAR(150) DEFAULT NULL,
  `project_location` VARCHAR(200) DEFAULT NULL,
  `message` TEXT,
  `status` ENUM('new', 'processing', 'completed', 'canceled') DEFAULT 'new',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table `news` (Tin tức doanh nghiệp)
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `summary` TEXT,
  `content` LONGTEXT,
  `image` VARCHAR(255) DEFAULT 'default-news.jpg',
  `author` VARCHAR(100) DEFAULT 'PNMEC',
  `views` INT DEFAULT 0,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table `contacts` (Liên hệ khách hàng)
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `subject` VARCHAR(255) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Table `site_info` (Cấu hình thông tin website)
CREATE TABLE IF NOT EXISTS `site_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `info_key` VARCHAR(100) NOT NULL UNIQUE,
  `info_value` TEXT,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
