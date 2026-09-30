<?php
$page_title = "Cấu Hình Thông Tin Website";
require_once __DIR__ . '/../includes/admin-header.php';

require_admin(); // Bắt buộc phải là role = admin mới được truy cập

$db = getDBConnection();
$info = get_all_site_info();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'site_name', 'company_short_name', 'phone', 'hotline', 
        'email', 'address', 'factory_address', 'working_hours',
        'hero_title', 'hero_subtitle', 'about_summary'
    ];

    if ($db) {
        try {
            $stmt = $db->prepare("INSERT INTO site_info (info_key, info_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE info_value = VALUES(info_value)");
            foreach ($fields as $field) {
                $val = trim($_POST[$field] ?? '');
                $stmt->execute(['key' => $field, 'value' => $val]);
            }
            set_flash_message('success', 'Đã cập nhật thông tin cấu hình website thành công!');
        } catch (Exception $e) {
            set_flash_message('danger', 'Lỗi khi lưu cấu hình: ' . $e->getMessage());
        }
    } else {
        set_flash_message('success', 'Đã cập nhật cài đặt website (chế độ demo).');
    }

    header("Location: /test/web_cty/admin/settings/site_info.php");
    exit;
}
?>

<div style="max-width: 900px; margin: 0 auto;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2><i class="fa-solid fa-sliders"></i> Cài Đặt Thông Tin & Banner Website</h2>
    <span class="badge badge-admin" style="font-size: 13px; padding: 6px 14px;"><i class="fa-solid fa-lock"></i> Khu Vực Cho Admin</span>
  </div>

  <div style="background: #fff; padding: 30px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="" method="POST">
      
      <h3 style="font-size: 16px; margin-bottom: 20px; color: #0f172a; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; display: inline-block;">
        <i class="fa-solid fa-building"></i> Thông Tin Thương Hiệu Công Ty
      </h3>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Tên đầy đủ công ty</label>
          <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($info['site_name'] ?? 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC'); ?>">
        </div>

        <div class="form-group">
          <label>Tên viết tắt / Thương hiệu</label>
          <input type="text" name="company_short_name" class="form-control" value="<?php echo htmlspecialchars($info['company_short_name'] ?? 'PNMEC'); ?>">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Số điện thoại liên hệ</label>
          <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($info['phone'] ?? '0988.123.456'); ?>">
        </div>

        <div class="form-group">
          <label>Hotline tổng đài</label>
          <input type="text" name="hotline" class="form-control" value="<?php echo htmlspecialchars($info['hotline'] ?? '1900.6868'); ?>">
        </div>

        <div class="form-group">
          <label>Email chính</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($info['email'] ?? 'contact@pnmec.vn'); ?>">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Địa chỉ văn phòng chính</label>
          <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($info['address'] ?? 'Khu Công Nghiệp Quang Minh, Mê Linh, Hà Nội'); ?>">
        </div>

        <div class="form-group">
          <label>Địa chỉ nhà máy gia công cơ khí</label>
          <input type="text" name="factory_address" class="form-control" value="<?php echo htmlspecialchars($info['factory_address'] ?? 'Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên'); ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Thời gian làm việc</label>
        <input type="text" name="working_hours" class="form-control" value="<?php echo htmlspecialchars($info['working_hours'] ?? 'Thứ 2 - Thứ 7: 07:30 - 17:30'); ?>">
      </div>

      <h3 style="font-size: 16px; margin: 30px 0 20px; color: #0f172a; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; display: inline-block;">
        <i class="fa-solid fa-display"></i> Nội Dung Banner & Giới Thiệu Trang Chủ
      </h3>

      <div class="form-group">
        <label>Tiêu đề lớn Hero Banner Trang Chủ</label>
        <input type="text" name="hero_title" class="form-control" value="<?php echo htmlspecialchars($info['hero_title'] ?? 'GIẢI PHÁP CƠ KHÍ CHẾ TẠO & THI CÔNG XÂY DỰNG TIÊN TIẾN'); ?>">
      </div>

      <div class="form-group">
        <label>Mô tả ngắn Hero Banner Trang Chủ</label>
        <textarea name="hero_subtitle" class="form-control" style="min-height: 70px;"><?php echo htmlspecialchars($info['hero_subtitle'] ?? 'Đồng hành cùng hàng trăm nhà xưởng, dự án kết cấu thép và công trình công nghiệp quy mô lớn trên toàn quốc.'); ?></textarea>
      </div>

      <div class="form-group">
        <label>Tóm tắt năng lực công ty (Footer & Giới thiệu)</label>
        <textarea name="about_summary" class="form-control" style="min-height: 90px;"><?php echo htmlspecialchars($info['about_summary'] ?? 'PNMEC là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công nhà xưởng kết cấu thép với hơn 15 năm kinh nghiệm.'); ?></textarea>
      </div>

      <div style="margin-top: 30px;">
        <button type="submit" class="btn-action" style="padding: 12px 28px; background: #0f172a; color: #fff; font-weight: 700; font-size: 15px; border-radius: 8px;">
          <i class="fa-solid fa-floppy-disk"></i> Lưu Tất Cả Thay Đổi
        </button>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
