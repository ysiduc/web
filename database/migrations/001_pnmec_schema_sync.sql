-- =========================================================================
-- MIGRATION: 001_pnmec_schema_sync.sql
-- Synchronize schema for PNMEC Admin Dashboard & React UI
-- Date: 2026-09-30
-- Safe & Idempotent (Preserves existing data)
-- =========================================================================

-- 1. Table users: Ensure columns exist
SET @dbname = DATABASE();

-- Add phone column if not exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'users' AND COLUMN_NAME = 'phone');
SET @query = IF(@col_exists = 0, 'ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL AFTER `email`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add status column if not exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'users' AND COLUMN_NAME = 'status');
SET @query = IF(@col_exists = 0, "ALTER TABLE `users` ADD COLUMN `status` ENUM('active', 'inactive') DEFAULT 'active' AFTER `role`", 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2. Table projects: Ensure gallery and status exist
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'projects' AND COLUMN_NAME = 'gallery');
SET @query = IF(@col_exists = 0, 'ALTER TABLE `projects` ADD COLUMN `gallery` TEXT DEFAULT NULL AFTER `image`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'projects' AND COLUMN_NAME = 'status');
SET @query = IF(@col_exists = 0, "ALTER TABLE `projects` ADD COLUMN `status` ENUM('published', 'draft') DEFAULT 'published' AFTER `views`", 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3. Table services: Ensure status exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'services' AND COLUMN_NAME = 'status');
SET @query = IF(@col_exists = 0, "ALTER TABLE `services` ADD COLUMN `status` ENUM('active', 'inactive') DEFAULT 'active' AFTER `views`", 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 4. Table news: Ensure status exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'news' AND COLUMN_NAME = 'status');
SET @query = IF(@col_exists = 0, "ALTER TABLE `news` ADD COLUMN `status` ENUM('published', 'draft') DEFAULT 'published' AFTER `views`", 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 5. Insert or update default site_info records
INSERT INTO `site_info` (`info_key`, `info_value`) VALUES
('site_name', 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC'),
('company_short_name', 'PNMEC'),
('phone', '0988.123.456'),
('hotline', '1900.6868'),
('email', 'contact@pnmec.vn'),
('address', 'Khu Công Nghiệp Quang Minh, Mê Linh, Hà Nội'),
('factory_address', 'Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên'),
('working_hours', 'Thứ 2 - Thứ 7: 07:30 - 17:30'),
('hero_title', 'GIẢI PHÁP CƠ KHÍ CHẾ TẠO & THI CÔNG XÂY DỰNG TIÊN TIẾN'),
('hero_subtitle', 'Đồng hành cùng hàng trăm nhà xưởng, dự án kết cấu thép và công trình công nghiệp quy mô lớn.'),
('about_summary', 'PNMEC là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công nhà xưởng kết cấu thép.'),
('facebook_url', 'https://facebook.com/pnmec'),
('youtube_url', 'https://youtube.com/@pnmec')
ON DUPLICATE KEY UPDATE `info_key` = `info_key`;
