<?php
require_once __DIR__ . '/functions.php';

$site_name = get_site_info('site_name', 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC');
$address = get_site_info('address', 'Khu Công Nghiệp Quang Minh, Mê Linh, Hà Nội');
$factory_address = get_site_info('factory_address', 'Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên');
$phone = get_site_info('phone', '0988.123.456');
$email = get_site_info('email', 'contact@pnmec.vn');
?>
  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: Brand Info -->
        <div class="footer-col">
          <img src="<?php echo asset_url('images/logo.png?v=2'); ?>" alt="<?php echo get_site_info('company_short_name', 'PNMEC'); ?>" style="height: 72px; width: auto; margin-bottom: 16px; display: block; filter: brightness(0) invert(1);">
          <p><?php echo get_site_info('about_summary'); ?></p>
          <div style="display: flex; gap: 12px; margin-top: 15px;">
            <a href="<?php echo get_site_info('facebook_url', '#'); ?>" target="_blank" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff;"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="<?php echo get_site_info('youtube_url', '#'); ?>" target="_blank" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff;"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="footer-col">
          <h3>Liên Kết Nhanh</h3>
          <ul class="footer-links">
            <li><a href="<?php echo url('/index.php'); ?>"><i class="fa-solid fa-angle-right"></i> Trang chủ</a></li>
            <li><a href="<?php echo url('/about.php'); ?>"><i class="fa-solid fa-angle-right"></i> Giới thiệu công ty</a></li>
            <li><a href="<?php echo url('/services.php'); ?>"><i class="fa-solid fa-angle-right"></i> Dịch vụ cơ khí & xây dựng</a></li>
            <li><a href="<?php echo url('/projects.php'); ?>"><i class="fa-solid fa-angle-right"></i> Dự án công trình đã thực hiện</a></li>
            <li><a href="<?php echo url('/recruitment.php'); ?>"><i class="fa-solid fa-angle-right"></i> Cơ hội việc làm - Tuyển dụng</a></li>
            <li><a href="<?php echo url('/news.php'); ?>"><i class="fa-solid fa-angle-right"></i> Tin tức</a></li>
          </ul>
        </div>

        <!-- Col 3: Services -->
        <div class="footer-col">
          <h3>Hạng Mục Thi Công</h3>
          <ul class="footer-links">
            <li><a href="<?php echo url('/services.php'); ?>"><i class="fa-solid fa-wrench"></i> Gia công Cơ khí chính xác</a></li>
            <li><a href="<?php echo url('/services.php'); ?>"><i class="fa-solid fa-building"></i> Thi công Nhà xưởng kết cấu thép</a></li>
            <li><a href="<?php echo url('/services.php'); ?>"><i class="fa-solid fa-industry"></i> Xây dựng Công trình Công nghiệp</a></li>
            <li><a href="<?php echo url('/services.php'); ?>"><i class="fa-solid fa-vial"></i> Chế tạo Bồn bể & Đường ống áp lực</a></li>
          </ul>
        </div>

        <!-- Col 4: Contact Info -->
        <div class="footer-col">
          <h3>Thông Tin Liên Hệ</h3>
          <ul class="footer-links">
            <li><i class="fa-solid fa-location-dot" style="color: var(--accent-gold); margin-right: 8px;"></i> <strong>Văn phòng:</strong> <?php echo $address; ?></li>
            <li><i class="fa-solid fa-industry" style="color: var(--accent-gold); margin-right: 8px;"></i> <strong>Nhà xưởng:</strong> <?php echo $factory_address; ?></li>
            <li><i class="fa-solid fa-phone" style="color: var(--accent-gold); margin-right: 8px;"></i> <strong>Điện thoại:</strong> <?php echo $phone; ?></li>
            <li><i class="fa-solid fa-envelope" style="color: var(--accent-gold); margin-right: 8px;"></i> <strong>Email:</strong> <?php echo $email; ?></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $site_name; ?>. Tất cả quyền được bảo lưu.</p>
      </div>
    </div>
  </footer>

  <!-- Main JavaScript File -->
  <script src="<?php echo asset_url('js/main.js'); ?>"></script>
</body>
</html>
