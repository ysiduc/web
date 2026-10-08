-- =========================================================================
-- MIGRATION 004: Cập Nhật Đồng Bộ Thông Tin Liên Hệ Thực Tế PNMEC
-- Database Engine: MySQL / MariaDB (utf8mb4_unicode_ci)
-- =========================================================================

-- Cập nhật các thông số liên hệ chính thức cho PNMEC vào bảng `site_info`:
-- 1. Tên công ty: PNMEC
-- 2. Trụ sở chính & Văn phòng: Số 26 Ngõ 139, Phố Hoa Lâm, Việt Hưng, Hà Nội
-- 3. Nhà xưởng chế tạo cơ khí: Chưa có địa chỉ cụ thể -> lưu rỗng '' (frontend tự động ẩn)
-- 4. Điện thoại tư vấn kỹ thuật: 0981700888
-- 5. Hotline: 0911391999
-- 6. Email tiếp nhận hồ sơ / Báo giá: pnmec.vn@gmail.com
-- 7. Thời gian làm việc: 24/7

INSERT INTO `site_info` (`info_key`, `info_value`) VALUES
('company_short_name', 'PNMEC'),
('address', 'Số 26 Ngõ 139, Phố Hoa Lâm, Việt Hưng, Hà Nội'),
('factory_address', ''),
('phone', '0981700888'),
('hotline', '0911391999'),
('email', 'pnmec.vn@gmail.com'),
('working_hours', '24/7')
ON DUPLICATE KEY UPDATE `info_value` = VALUES(`info_value`);
