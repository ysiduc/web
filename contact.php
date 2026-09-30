<?php
$page_title = "Liên Hệ & Báo Giá";
require_once __DIR__ . '/includes/header.php';

$sent_status = false;
$msg_text = '';
$error_text = '';
$selected_service = $_GET['service'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $service  = trim($_POST['service'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    if (empty($fullname) || empty($phone)) {
        $error_text = 'Vui lòng điền đầy đủ Họ tên và Số điện thoại liên hệ.';
    } else {
        $db = getDBConnection();
        if ($db) {
            try {
                // 1. Insert into quotes table
                $stmt_q = $db->prepare("INSERT INTO quotes (fullname, phone, email, service_type, message, status) VALUES (:fullname, :phone, :email, :service_type, :message, 'new')");
                $stmt_q->execute([
                    'fullname'     => $fullname,
                    'phone'        => $phone,
                    'email'        => $email,
                    'service_type' => $service,
                    'message'      => $message
                ]);

                // 2. Insert into contacts table
                $stmt_c = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message, status) VALUES (:name, :email, :phone, :subject, :message, 'unread')");
                $stmt_c->execute([
                    'name'    => $fullname,
                    'email'   => !empty($email) ? $email : 'khachhang@pnmec.vn',
                    'phone'   => $phone,
                    'subject' => 'Yêu cầu tư vấn: ' . (!empty($service) ? $service : 'Dịch vụ tổng hợp'),
                    'message' => $message
                ]);

                $sent_status = true;
                $msg_text = 'Cảm ơn ông/bà <strong>' . htmlspecialchars($fullname) . '</strong>! Yêu cầu tư vấn / báo giá đã được gửi thành công. Đội ngũ kỹ sư của PNMEC sẽ liên hệ trực tiếp qua số điện thoại <strong>' . htmlspecialchars($phone) . '</strong> trong vòng 30 phút.';
            } catch (Exception $e) {
                $error_text = 'Lỗi hệ thống: ' . $e->getMessage();
            }
        } else {
            $sent_status = true;
            $msg_text = 'Cảm ơn ông/bà <strong>' . htmlspecialchars($fullname) . '</strong>! Yêu cầu tư vấn / báo giá đã được gửi thành công.';
        }
    }
}
?>

<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <h1>Liên Hệ Tư Vấn &amp; Báo Giá</h1>
    <div class="breadcrumb">
      <a href="/test/web_cty/index.php">Trang chủ</a> / <span>Liên hệ</span>
    </div>
  </div>
</section>

<!-- Contact Content -->
<section class="contact-section">
  <div class="container">
    
    <?php if ($sent_status): ?>
      <div class="alert alert-success" style="font-size: 15px; padding: 20px; margin-bottom: 30px;">
        <i class="fa-solid fa-circle-check" style="font-size: 24px;"></i>
        <div><?= $msg_text; ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($error_text)): ?>
      <div class="alert alert-danger" style="font-size: 15px; padding: 20px; margin-bottom: 30px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 24px;"></i>
        <div><?= $error_text; ?></div>
      </div>
    <?php endif; ?>

    <div class="contact-grid">
      <!-- Left Column: Company Info Card -->
      <div class="contact-info-card">
        <h3><?= get_site_info('company_short_name', 'PNMEC'); ?></h3>
        <p style="color: var(--text-light); font-size: 14px; margin-bottom: 30px;">
          Hãy liên hệ với chúng tôi để nhận bản vẽ khảo sát miễn phí và phương án dự toán tối ưu nhất cho công trình của quý vị.
        </p>

        <div class="contact-detail-item">
          <i class="fa-solid fa-location-dot"></i>
          <div>
            <strong style="color: #fff; display: block; font-size: 15px;">Trụ sở chính &amp; Văn phòng:</strong>
            <span style="color: var(--text-light); font-size: 14px;"><?= get_site_info('address'); ?></span>
          </div>
        </div>

        <div class="contact-detail-item">
          <i class="fa-solid fa-industry"></i>
          <div>
            <strong style="color: #fff; display: block; font-size: 15px;">Nhà xưởng chế tạo cơ khí:</strong>
            <span style="color: var(--text-light); font-size: 14px;"><?= get_site_info('factory_address'); ?></span>
          </div>
        </div>

        <div class="contact-detail-item">
          <i class="fa-solid fa-phone"></i>
          <div>
            <strong style="color: #fff; display: block; font-size: 15px;">Điện thoại tư vấn kỹ thuật:</strong>
            <span style="color: var(--text-light); font-size: 14px;"><?= get_site_info('phone'); ?> (Hotline: <?= get_site_info('hotline'); ?>)</span>
          </div>
        </div>

        <div class="contact-detail-item">
          <i class="fa-solid fa-envelope"></i>
          <div>
            <strong style="color: #fff; display: block; font-size: 15px;">Email tiếp nhận hồ sơ / Báo giá:</strong>
            <span style="color: var(--text-light); font-size: 14px;"><?= get_site_info('email'); ?></span>
          </div>
        </div>

        <div class="contact-detail-item">
          <i class="fa-solid fa-clock"></i>
          <div>
            <strong style="color: #fff; display: block; font-size: 15px;">Thời gian làm việc:</strong>
            <span style="color: var(--text-light); font-size: 14px;"><?= get_site_info('working_hours'); ?></span>
          </div>
        </div>
      </div>

      <!-- Right Column: Inquiry Form -->
      <div class="contact-form-box">
        <h3 class="contact-form-title">Gửi Yêu Cầu Báo Giá Trực Tuyến</h3>
        <p class="contact-form-sub">Vui lòng nhập thông tin yêu cầu của quý khách bên dưới.</p>

        <form action="" method="POST">
          <div class="form-grid-2col">
            <div class="form-group">
              <label>Họ và tên quý khách <span style="color: red;">*</span></label>
              <input type="text" name="fullname" class="form-control" placeholder="Ví dụ: Nguyễn Văn B" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Số điện thoại liên hệ <span style="color: red;">*</span></label>
              <input type="tel" name="phone" class="form-control" placeholder="09xxxxxxxx" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
          </div>

          <div class="form-grid-2col">
            <div class="form-group">
              <label>Địa chỉ Email</label>
              <input type="email" name="email" class="form-control" placeholder="email@domain.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>Dịch vụ cần báo giá</label>
              <select name="service" class="form-control">
                <optgroup label="⚡ CƠ KHÍ XÂY DỰNG">
                  <option value="Thiết Kế Thi Công Nhà Kết Cấu Thép" <?= ($selected_service == 'Thiết Kế Thi Công Nhà Kết Cấu Thép') ? 'selected' : '' ?>>Thiết Kế Thi Công Nhà Kết Cấu Thép</option>
                  <option value="Thiết Kế Thi Công Cầu Thang, Ban Công" <?= ($selected_service == 'Thiết Kế Thi Công Cầu Thang, Ban Công') ? 'selected' : '' ?>>Thiết Kế Thi Công Cầu Thang, Ban Công</option>
                  <option value="Thiết Kế Thi Công Mái Tôn, Mái Che Di Động" <?= ($selected_service == 'Thiết Kế Thi Công Mái Tôn, Mái Che Di Động') ? 'selected' : '' ?>>Thiết Kế Thi Công Mái Tôn, Mái Che Di Động</option>
                  <option value="Thiết Kế Thi Công Nhà Cơi Nới, Lồng Cơi Tập Thể" <?= ($selected_service == 'Thiết Kế Thi Công Nhà Cơi Nới, Lồng Cơi Tập Thể') ? 'selected' : '' ?>>Thiết Kế Thi Công Nhà Cơi Nới, Lồng Cơi</option>
                  <option value="Thiết Kế Thi Công Các Dạng Thang Thoát Hiểm" <?= ($selected_service == 'Thiết Kế Thi Công Các Dạng Thang Thoát Hiểm') ? 'selected' : '' ?>>Thiết Kế Thi Công Thang Thoát Hiểm PCCC</option>
                  <option value="Thiết Kế Thi Công Nhà Xe, Mái Che" <?= ($selected_service == 'Thiết Kế Thi Công Nhà Xe, Mái Che') ? 'selected' : '' ?>>Thiết Kế Thi Công Nhà Xe, Mái Che</option>
                  <option value="Thiết Kế Thi Công Mái Kính" <?= ($selected_service == 'Thiết Kế Thi Công Mái Kính') ? 'selected' : '' ?>>Thiết Kế Thi Công Mái Kính Canopy</option>
                  <option value="Thiết Kế Thi Công Sắt Mỹ Thuật" <?= ($selected_service == 'Thiết Kế Thi Công Sắt Mỹ Thuật') ? 'selected' : '' ?>>Thiết Kế Thi Công Sắt Mỹ Thuật</option>
                  <option value="Thiết Kế Thi Công Cửa Các Loại" <?= ($selected_service == 'Thiết Kế Thi Công Cửa Các Loại') ? 'selected' : '' ?>>Thiết Kế Thi Công Cửa Các Loại</option>
                </optgroup>
                <optgroup label="🏢 XÂY DỰNG & HOÀN THIỆN">
                  <option value="Thiết Kế Thi Công Nhà Trọn Gói" <?= ($selected_service == 'Thiết Kế Thi Công Nhà Trọn Gói') ? 'selected' : '' ?>>Thiết Kế Thi Công Nhà Trọn Gói</option>
                  <option value="Thiết Kế Thi Công Nội Ngoại Thất" <?= ($selected_service == 'Thiết Kế Thi Công Nội Ngoại Thất') ? 'selected' : '' ?>>Thiết Kế Thi Công Nội Ngoại Thất</option>
                  <option value="Cải Tạo Sửa Chữa Và Phá Dỡ" <?= ($selected_service == 'Cải Tạo Sửa Chữa Và Phá Dỡ') ? 'selected' : '' ?>>Cải Tạo Sửa Chữa Và Phá Dỡ</option>
                </optgroup>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Nội dung chi tiết yêu cầu &amp; Quy mô công trình</label>
            <textarea name="message" class="form-control" style="min-height: 120px;" placeholder="Ví dụ: Cần tư vấn bản vẽ & báo giá thi công tại Hà Nội..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px;"><i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Cho PNMEC</button>
        </form>
      </div>

    </div>

    <!-- Map Wrapper -->
    <div class="map-wrapper">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3720.8973656360216!2d105.748348!3d21.156525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3134fc2e7960fb81%3A0x6b876a44bfb2ef32!2sKCN%20Quang%20Minh!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
              width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
