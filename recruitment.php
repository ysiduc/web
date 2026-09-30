<?php
$page_title = "Tuyển Dụng Nhân Sự";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <h1>Tuyển Dụng Nhân Tài - Phát Triển Sự Nghiệp</h1>
    <div class="breadcrumb">
      <a href="/test/web_cty/index.php">Trang chủ</a> / <span>Tuyển dụng</span>
    </div>
  </div>
</section>

<!-- Recruitment Overview -->
<section class="recruitment-section">
  <div class="container">
    <div class="section-title" style="text-align: center; margin-bottom: 40px;">
      <span style="color: var(--accent-gold); font-weight: 700; text-transform: uppercase; font-size: 13px;">Cơ Hội Việc Làm</span>
      <h2 style="font-size: 30px; margin-top: 6px;">Các Vị Trí Đang Tuyển Dụng</h2>
      <p style="color: var(--text-muted); font-size: 14px; max-width: 600px; margin: 8px auto 0;">Gia nhập đội ngũ PNMEC để làm việc trong môi trường chuyên nghiệp, đãi ngộ hấp dẫn và cơ hội thăng tiến rộng mở.</p>
    </div>

    <!-- Vacancy Grid -->
    <div class="vacancy-grid">
      
      <!-- Vacancy 1 -->
      <div class="vacancy-card">
        <span class="vacancy-badge">Toàn thời gian</span>
        <h3 class="vacancy-title">02 Kỹ Sư Thiết Kế Kết Cấu Thép</h3>
        <div class="vacancy-meta">
          <span><i class="fa-solid fa-money-bill-wave"></i> 18 - 25 Triệu / tháng</span> | 
          <span><i class="fa-solid fa-location-dot"></i> Hà Nội</span>
        </div>
        <ul class="vacancy-list">
          <li>• Thành thạo Tekla Structures, AutoCAD, Sap2000.</li>
          <li>• Đọc hiểu bản vẽ kỹ thuật và triển khai bản vẽ shop-drawing gia công.</li>
          <li>• Kinh nghiệm từ 2 năm tại các công ty kết cấu thép.</li>
        </ul>
        <a href="#apply-form" class="btn btn-navy btn-sm"><i class="fa-solid fa-paper-plane"></i> Ứng Tuyển Ngay</a>
      </div>

      <!-- Vacancy 2 -->
      <div class="vacancy-card">
        <span class="vacancy-badge">Toàn thời gian</span>
        <h3 class="vacancy-title">03 Kỹ Sư Giám Sát Thi Công Công Trường</h3>
        <div class="vacancy-meta">
          <span><i class="fa-solid fa-money-bill-wave"></i> 15 - 22 Triệu / tháng</span> | 
          <span><i class="fa-solid fa-location-dot"></i> Theo dự án</span>
        </div>
        <ul class="vacancy-list">
          <li>• Trực tiếp quản lý tiến độ, chất lượng lắp dựng kết cấu thép.</li>
          <li>• Phối hợp với Chủ đầu tư nghiệm thu công việc hiện trường.</li>
          <li>• Tốt nghiệp Đại học Xây dựng, Giao thông hoặc Kiến trúc.</li>
        </ul>
        <a href="#apply-form" class="btn btn-navy btn-sm"><i class="fa-solid fa-paper-plane"></i> Ứng Tuyển Ngay</a>
      </div>

      <!-- Vacancy 3 -->
      <div class="vacancy-card">
        <span class="vacancy-badge">Nhà máy</span>
        <h3 class="vacancy-title">10 Thợ Gia Công Cơ Khí & Hàn 6G</h3>
        <div class="vacancy-meta">
          <span><i class="fa-solid fa-money-bill-wave"></i> 12 - 18 Triệu / tháng</span> | 
          <span><i class="fa-solid fa-location-dot"></i> KCN Thăng Long II, Hưng Yên</span>
        </div>
        <ul class="vacancy-list">
          <li>• Thực hiện công việc gá đính, hàn MIG, hàn TIG dầm tổ hợp.</li>
          <li>• Kiểm tra mối hàn đạt tiêu chuẩn siêu âm NDT.</li>
          <li>• Có phụ cấp nhà ở và ăn ca tại nhà máy.</li>
        </ul>
        <a href="#apply-form" class="btn btn-navy btn-sm"><i class="fa-solid fa-paper-plane"></i> Ứng Tuyển Ngay</a>
      </div>

    </div>

    <!-- Application Form Block -->
    <div id="apply-form" class="apply-form-box">
      <h3 class="apply-form-title">Nộp Hồ Sơ Ứng Tuyển Trực Tuyến</h3>
      <p class="apply-form-sub">Bộ phận Nhân sự sẽ liên hệ phỏng vấn trong vòng 48 giờ làm việc.</p>

      <form action="/test/web_cty/contact.php?applied=true" method="POST">
        <div class="form-grid-2col">
          <div class="form-group">
            <label>Họ và tên ứng viên <span style="color: red;">*</span></label>
            <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
          </div>
          <div class="form-group">
            <label>Số điện thoại <span style="color: red;">*</span></label>
            <input type="tel" name="phone" class="form-control" placeholder="09xxxxxxxx" required>
          </div>
        </div>

        <div class="form-grid-2col">
          <div class="form-group">
            <label>Email cá nhân</label>
            <input type="email" name="email" class="form-control" placeholder="ungvien@gmail.com">
          </div>
          <div class="form-group">
            <label>Vị trí ứng tuyển <span style="color: red;">*</span></label>
            <select name="position" class="form-control" required>
              <option value="Kỹ sư thiết kế kết cấu thép">Kỹ sư thiết kế kết cấu thép</option>
              <option value="Kỹ sư giám sát thi công">Kỹ sư giám sát thi công</option>
              <option value="Thợ gia công cơ khí / Hàn 6G">Thợ gia công cơ khí / Hàn 6G</option>
              <option value="Vị trí khác">Vị trí khác</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Tóm tắt kinh nghiệm làm việc & Ghi chú</label>
          <textarea name="experience" class="form-control" placeholder="Mô tả ngắn kinh nghiệm làm việc hoặc đính kèm link CV của bạn..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-paper-plane"></i> Gửi Hồ Sơ Ứng Tuyển</button>
      </form>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
