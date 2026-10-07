-- =========================================================================
-- MIGRATION: 003_recruitment_management.sql
-- Create recruitments table for database-driven recruitment management
-- Date: 2026-10-07
-- Safe & Idempotent
-- =========================================================================

CREATE TABLE IF NOT EXISTS `recruitments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `employment_type` VARCHAR(100) NOT NULL DEFAULT 'Toàn thời gian',
  `salary` VARCHAR(100) NOT NULL DEFAULT 'Thỏa thuận',
  `location` VARCHAR(255) NOT NULL DEFAULT 'Hà Nội',
  `description` TEXT DEFAULT NULL,
  `requirements` TEXT DEFAULT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
