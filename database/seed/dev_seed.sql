-- =========================================================================
-- DEVELOPMENT SEED DATA: PNMEC - Công Ty CP Cơ Khí & Xây Dựng (web_cty)
-- For development / testing environments only.
-- DO NOT use default admin credentials in production!
-- =========================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- 1. Default Admin Account (Username: admin | Password: password)
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi corresponds to default bcrypt
INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `phone`, `role`, `status`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản Trị Viên (Admin)', 'admin@pnmec.vn', '0988.123.456', 'admin', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 2. Seed Site Info
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
('hero_subtitle', 'Đồng hành cùng hàng trăm nhà xưởng, dự án kết cấu thép và công trình công nghiệp quy mô lớn trên toàn quốc.'),
('about_summary', 'PNMEC là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công công trình kết cấu thép, nhà xưởng công nghiệp với hơn 15 năm kinh nghiệm.'),
('facebook_url', 'https://facebook.com/pnmec'),
('youtube_url', 'https://youtube.com/@pnmec')
ON DUPLICATE KEY UPDATE `info_value`=VALUES(`info_value`);

-- 3. Seed Services
INSERT INTO `services` (`id`, `title`, `slug`, `code`, `summary`, `content`, `image`, `featured`, `views`, `status`) VALUES
(1, 'Gia Công Cơ Khí Chế Tạo Chính Xác', 'gia-cong-co-khi-che-tao-chinh-xac', 'SRV-01', 'Gia công chi tiết máy móc hạng nặng, dầm thép tổ hợp, cắt laser fiber và chấn gấp CNC.', 'PNMEC sở hữu hệ thống máy CNC 5 trục hiện đại, trung tâm gia công tiện phay tốc độ cao và máy cắt Laser Fiber công suất 20.000W đáp ứng mọi yêu cầu gia công cơ khí chính xác.', 'srv-coki.jpg', 1, 350, 'active'),
(2, 'Thi Công Nhà Xưởng Kết Cấu Thép', 'thi-cong-nha-xuong-ket-cau-thep', 'SRV-02', 'Thiết kế, sản xuất và thi công lắp dựng khung kèo thép tiền chế cho nhà máy, kho bãi.', 'Giải pháp thi công trọn gói từ khâu thiết kế 3D, sản xuất dầm thép tại nhà máy tới lắp dựng an toàn tại công trường. Cam kết vượt nhịp lớn, tối ưu diện tích và độ bền trên 50 năm.', 'srv-kct.jpg', 1, 520, 'active'),
(3, 'Chế Tạo Bồn Bể & Đường Ống Áp Lực', 'che-tao-bon-be-duong-ong-ap-luc', 'SRV-03', 'Gia công bồn inox, bồn composite chịu hóa chất, bồn khí nén và hệ thống đường ống công nghiệp.', 'Sản xuất và kiểm định siêu âm đường hàn 100%, đáp ứng các tiêu chuẩn áp lực ASME, ISO và tiêu chuẩn phòng cháy chữa cháy công nghiệp.', 'srv-bonbe.jpg', 1, 210, 'active'),
(4, 'Bảo Trì & Nâng Cấp Hệ Thống Cơ Điện Công Nghiệp', 'bao-tri-nang-cap-co-dien', 'SRV-04', 'Dịch vụ bảo dưỡng máy móc định kỳ, cải tạo dây chuyền sản xuất và gia cố khung nhà xưởng.', 'Đội ngũ kỹ sư cơ điện giàu kinh nghiệm trực chiến 24/7, khắc phục sự cố nhanh chóng, giảm thiểu tối đa thời gian dừng sản xuất của nhà máy.', 'srv-baotri.jpg', 0, 180, 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 4. Seed Projects
INSERT INTO `projects` (`id`, `title`, `slug`, `category`, `description`, `content`, `image`, `client`, `location`, `completion_date`, `created_by`, `views`, `status`) VALUES
(1, 'Nhà Xưởng Công Nghiệp Tập Đoàn Samsung Bắc Ninh', 'nha-xuong-cong-nghiep-samsung-bac-ninh', 'Xây dựng công nghiệp', 'Thi công tổng thầu nhà xưởng sản xuất quy mô 25.000m2 với kết cấu thép vượt khổ lớn.', 'Dự án Tổng thầu Xây dựng Nhà xưởng Sản xuất Linh kiện số 3 Samsung Bắc Ninh đòi hỏi tiêu chuẩn khắt khe về tải trọng và độ chính xác kết cấu thép. PNMEC đã áp dụng công nghệ hàn tự động dầm H và lắp dựng đạt tiến độ trước 15 ngày.', 'project-samsung.jpg', 'Tập đoàn Samsung Electronics', 'KCN Yên Phong, Bắc Ninh', '2025-11-20', 1, 450, 'published'),
(2, 'Gia Công Hệ Thống Băng Tải Luyện Kim Hoà Phát', 'gia-cong-he-thong-bang-tai-hoa-phat', 'Cơ khí chế tạo', 'Chế tạo và gia công hệ thống truyền động băng tải chịu nhiệt cho khu liên hợp thép Dung Quất.', 'Chế tạo chi tiết cơ khí hạng nặng cho khu liên hợp sản xuất thép Hoà Phát Dung Quất. Toàn bộ thiết bị được gia công trên máy CNC 5 trục hiện đại, phủ chống ăn mòn bề mặt theo tiêu chuẩn ISO 12944.', 'project-hoaphat.jpg', 'Tập đoàn Hòa Phát', 'KKT Dung Quất, Quảng Ngãi', '2025-08-15', 1, 320, 'published'),
(3, 'Tòa Nhà Văn Phòng & Showroom Ô Tô VinFast', 'toa-nha-van-phong-showroom-vinfast', 'Kết cấu thép', 'Thi công khung kết cấu thép chịu lực 8 tầng kết hợp vách kính hiện đại.', 'Dự án thi công khung thép định hình tổ hợp showroom và trung tâm dịch vụ kỹ thuật VinFast. Kết cấu vượt nhịp 32m không cột giữa giúp tối ưu không gian trưng bày xe sang trọng.', 'project-vinfast.jpg', 'Tập đoàn Vingroup', 'Cầu Giấy, Hà Nội', '2025-12-10', 1, 610, 'published'),
(4, 'Tổ Hợp Nhà Máy Chế Biến Thực Phẩm Masan Hưng Yên', 'to-hop-nha-may-thuc-pham-masan-hung-yen', 'Xây dựng công nghiệp', 'Thi công hệ thống phòng sạch và nhà máy tiêu chuẩn ISO 22000.', 'Xây dựng nhà xưởng khép kín đạt tiêu chuẩn quốc tế về an toàn vệ sinh thực phẩm. Sử dụng tấm Panel cách nhiệt cao cấp và đường ống cơ điện cơ khí inox 316L.', 'project-masan.jpg', 'Công ty CP Tập đoàn Masan', 'KCN Thăng Long II, Hưng Yên', '2025-05-30', 1, 280, 'published'),
(5, 'Chế Tạo Bồn Áp Lực & Đường Ống Viễn Đông Chemical', 'che-tao-bon-ap-luc-vien-dong-chemical', 'Cơ khí chế tạo', 'Gia công bồn inox chịu áp suất 40 Bar và hệ thống đường ống hóa chất công nghiệp.', 'PNMEC phụ trách thiết kế, kiểm định siêu âm đường hàn và lắp đặt hoàn thiện cụm 12 bồn bể dung tích 50m3 cho nhà máy hóa chất Viễn Đông.', 'project-bon-ap-luc.jpg', 'Công ty Hóa chất Viễn Đông', 'KCN Đình Vũ, Hải Phòng', '2025-09-05', 1, 195, 'published')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 5. Seed News
INSERT INTO `news` (`id`, `title`, `slug`, `summary`, `content`, `image`, `author`, `views`, `status`) VALUES
(1, 'PNMEC Khởi Công Dự Án Kết Cấu Thép Nhà Máy Thăng Long III', 'pnmec-khoi-cong-du-an-thang-long-3', 'Lễ khởi công tổ hợp nhà xưởng chế tạo linh kiện điện tử tại KCN Thăng Long III, Vĩnh Phúc.', 'Sáng ngày 15/02/2026, PNMEC cùng các đối tác đã chính thức làm lễ động thổ và khởi công gói thầu kết cấu thép quy mô 30.000m2. Dự kiến bàn giao vào Quý III/2026.', 'news-1.jpg', 'Ban Truyền Thông', 120, 'published'),
(2, 'Ứng Dụng Công Nghệ Hàn Laser Tự Động Trong Chế Tạo Bồn Bể', 'ung-dung-cong-nghe-han-laser-tu-dong', 'Giải pháp nâng cao chất lượng mối hàn và độ kín tuyệt đối cho các hệ thống đường ống công nghiệp.', 'Việc đưa robot hàn laser fiber công suất lớn vào dây chuyền sản xuất bồn áp lực đã giúp tăng năng suất 300% và đảm bảo 100% mối hàn vượt qua bài test siêu âm khắt khe.', 'news-2.jpg', 'Phòng Kỹ Thuật', 240, 'published')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 6. Seed Quotes
INSERT INTO `quotes` (`id`, `fullname`, `phone`, `email`, `service_type`, `project_location`, `message`, `status`) VALUES
(1, 'Trần Minh Tuấn', '0912345678', 'tuan.tran@gmail.com', 'Thi công nhà xưởng kết cấu thép', 'Bắc Ninh', 'Cần tư vấn thiết kế và thi công nhà xưởng 3.000m2', 'new'),
(2, 'Nguyễn Hoàng Long', '0988776655', 'long.nh@vietmec.vn', 'Gia công cơ khí chế tạo', 'Hải Phòng', 'Báo giá cắt laser thép tấm 20mm và chấn góc', 'processing')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- 7. Seed Contacts
INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
(1, 'Phạm Quốc Hưng', 'hung.pq@gmail.com', '0901234567', 'Tư vấn giải pháp cơ khí', 'Chúng tôi cần hợp tác gia công chi tiết khuôn mẫu', 'unread')
ON DUPLICATE KEY UPDATE `id`=`id`;
