-- =========================================================================
-- MIGRATION: 002_project_detail_builder.sql
-- Add start_date, detail_mode, and detail_blocks to projects table
-- Date: 2026-10-01
-- Safe & Idempotent (Preserves existing data)
-- =========================================================================

SET @dbname = DATABASE();

-- 1. Add start_date column if not exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'projects' AND COLUMN_NAME = 'start_date');
SET @query = IF(@col_exists = 0, 'ALTER TABLE `projects` ADD COLUMN `start_date` DATE DEFAULT NULL AFTER `location`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2. Add detail_mode column if not exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'projects' AND COLUMN_NAME = 'detail_mode');
SET @query = IF(@col_exists = 0, "ALTER TABLE `projects` ADD COLUMN `detail_mode` ENUM('basic', 'custom') DEFAULT 'basic' AFTER `status`", 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3. Add detail_blocks column if not exists
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'projects' AND COLUMN_NAME = 'detail_blocks');
SET @query = IF(@col_exists = 0, 'ALTER TABLE `projects` ADD COLUMN `detail_blocks` LONGTEXT DEFAULT NULL AFTER `detail_mode`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;
