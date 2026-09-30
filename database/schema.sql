-- =========================================================================
-- DATABASE SCHEMA: PNMEC - Công Ty CP Cơ Khí & Xây Dựng (web_cty)
-- =========================================================================

CREATE DATABASE IF NOT EXISTS `web_cty` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `web_cty`;

-- 1. Table Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'staff') DEFAULT 'staff',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Admin (Password: admin123)
INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản Trị Viên (Admin)', 'admin@pnmec.vn', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 2. Table Services
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
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table Projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'Xây dựng công nghiệp',
  `client` VARCHAR(255) DEFAULT 'Tập Đoàn Hàn Quốc',
  `location` VARCHAR(255) DEFAULT 'KCN Thăng Long II, Hưng Yên',
  `image` VARCHAR(255) DEFAULT 'default-project.jpg',
  `gallery` TEXT,
  `description` TEXT,
  `content` LONGTEXT,
  `views` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table Quotes (Yêu cầu báo giá)
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

-- 5. Table News (Bài viết tin tức)
CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `summary` TEXT,
  `content` LONGTEXT,
  `image` VARCHAR(255) DEFAULT 'default-news.jpg',
  `author` VARCHAR(100) DEFAULT 'PNMEC',
  `views` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Table Contacts (Liên hệ khách hàng)
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

-- 7. Table Site Info
CREATE TABLE IF NOT EXISTS `site_info` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `info_key` VARCHAR(100) NOT NULL UNIQUE,
  `info_value` TEXT,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Seed Data for Site Info
INSERT INTO `site_info` (`info_key`, `info_value`) VALUES
('site_name', 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC'),
('company_short_name', 'PNMEC'),
('hotline', '1900.6868'),
('phone', '0988.123.456'),
('email', 'contact@pnmec.vn'),
('address', 'Khu Công Nghiệp Quang Minh, Mê Linh, Hà Nội'),
('factory_address', 'Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên'),
('working_hours', 'Thứ 2 - Thứ 7: 07:30 - 17:30'),
('hero_title', 'GIẢI PHÁP CƠ KHÍ CHẾ TẠO & THI CÔNG XÂY DỰNG TIÊN TIẾN'),
('hero_subtitle', 'Cung cấp giải pháp tổng thầu cơ khí & xây dựng công nghiệp uy tín — đúng tiến độ, đúng chất lượng, tối ưu chi phí.'),
('about_summary', 'PNMEC Group là đơn vị tiên phong trong lĩnh vực gia công cơ khí chính xác và thi công xây dựng công nghiệp tại Việt Nam.')
ON DUPLICATE KEY UPDATE `info_value`=VALUES(`info_value`);
