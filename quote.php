<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/includes/rate_limiter.php';
    $rl = check_rate_limit('quote', 5, 300);
    if (!$rl['allowed']) {
        http_response_code(429);
        header("Retry-After: " . $rl['retry_after']);
        $error = 'Bạn đã gửi yêu cầu quá nhiều lần. Vui lòng thử lại sau ' . $rl['retry_after'] . ' giây.';
    } else {
        $fullname = sanitize($_POST['fullname'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $service_type = sanitize($_POST['service_type'] ?? '');
        $project_location = sanitize($_POST['project_location'] ?? '');
        $message = sanitize($_POST['message'] ?? '');

        if (empty($fullname) || empty($phone)) {
            $error = 'Vui lòng điền đầy đủ Họ và tên và Số điện thoại liên hệ.';
        } else {
            $db = getDBConnection();
            if ($db) {
                try {
                    $stmt = $db->prepare("INSERT INTO quotes (fullname, phone, email, service_type, project_location, message) VALUES (:fullname, :phone, :email, :service_type, :project_location, :message)");
                    $stmt->execute([
                        'fullname' => $fullname,
                        'phone' => $phone,
                        'email' => $email,
                        'service_type' => $service_type,
                        'project_location' => $project_location,
                        'message' => $message
                    ]);
                    $success = 'Gửi yêu cầu báo giá thành công! Kỹ sư tư vấn PNMEC sẽ phản hồi quý khách trong thời gian sớm nhất.';
                } catch (Exception $e) {
                    error_log("Quote submit error: " . $e->getMessage());
                    $error = 'Có lỗi xảy ra khi lưu thông tin. Vui lòng thử lại sau.';
                }
            } else {
                $success = 'Yêu cầu báo giá của bạn đã được ghi nhận. Chúng tôi sẽ gọi lại ngay!';
            }
        }
    }
}

$page_title = "Yêu Cầu Báo Giá Thi Công - PNMEC";
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-banner">
  <div class="container">
    <h1>Yêu Cầu Báo Giá Thi Công</h1>
    <div class="breadcrumb">
      <a href="<?= url('/index.php') ?>">Trang chủ</a> / <span>Nhận Báo Giá</span>
    </div>
  </div>
</div>

<section class="quote-page-section">
  <div class="container">
    <div class="quote-page-card">
      <div class="quote-card-header">
        <span class="quote-badge">Tư Vấn Trọn Gói</span>
        <h2 class="quote-title">Đăng Ký Nhận Báo Giá Nhanh</h2>
        <p class="quote-sub">Quý khách vui lòng cung cấp thông tin dự án để chuyên viên PNMEC lên phương án và bảng giá chi tiết.</p>
      </div>

      <?php if ($success): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo $success; ?></div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="form-grid-2col">
          <div class="form-group">
            <label for="fullname">Họ và Tên *</label>
            <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
          </div>
          <div class="form-group">
            <label for="phone">Số Điện Thoại *</label>
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="0988.xxx.xxx" required>
          </div>
        </div>

        <div class="form-grid-2col">
          <div class="form-group">
            <label for="email">Địa Chỉ Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="example@domain.com">
          </div>
          <div class="form-group">
            <label for="project_location">Địa Điểm Xây Dựng</label>
            <input type="text" id="project_location" name="project_location" class="form-control" placeholder="Hà Nội, Hưng Yên, Bắc Ninh...">
          </div>
        </div>

        <div class="form-group">
          <label for="service_type">Hạng Mục / Dịch Vụ Cần Báo Giá</label>
          <select id="service_type" name="service_type" class="form-control">
            <option value="Thi công nhà xưởng kết cấu thép">Thi công nhà xưởng kết cấu thép</option>
            <option value="Gia công cơ khí CNC chính xác">Gia công cơ khí CNC chính xác</option>
            <option value="Xây dựng hạ tầng công nghiệp">Xây dựng hạ tầng công nghiệp</option>
            <option value="Chế tạo bồn bể & đường ống">Chế tạo bồn bể & đường ống công nghiệp</option>
            <option value="Di dời & bảo dưỡng máy móc">Di dời & bảo dưỡng máy móc nhà xưởng</option>
          </select>
        </div>

        <div class="form-group">
          <label for="message">Ghi Chú Mô Tả Quy Mô / Yêu Cầu Chi Tiết</label>
          <textarea id="message" name="message" class="form-control" placeholder="Ví dụ: Diện tích xưởng 2.000m2, chiều cao 9m, tiến độ thi công 60 ngày..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary quote-submit-btn">
          <i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Báo Giá
        </button>
      </form>
    </div>
  </div>
</section>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
