<?php
/**
 * Shared Sidebar Component (Used in detail/category pages)
 */
?>
<aside class="sidebar-widget-container">
  <div class="sidebar-widget">
    <h3 style="font-size: 18px; margin-bottom: 16px; border-bottom: 2px solid var(--accent-gold); padding-bottom: 8px;">
      <i class="fa-solid fa-list-check" style="color: var(--accent-gold); margin-right: 8px;"></i> Danh Mục Dịch Vụ
    </h3>
    <ul class="footer-links" style="color: var(--text-main);">
      <li><a href="<?php echo url('/services.php'); ?>" style="color: var(--text-main);"><i class="fa-solid fa-angle-right"></i> Gia Công Cơ Khí CNC</a></li>
      <li><a href="<?php echo url('/services.php'); ?>" style="color: var(--text-main);"><i class="fa-solid fa-angle-right"></i> Kết Cấu Thép Nhà Xưởng</a></li>
      <li><a href="<?php echo url('/services.php'); ?>" style="color: var(--text-main);"><i class="fa-solid fa-angle-right"></i> Hạ Tầng & Công Trình</a></li>
      <li><a href="<?php echo url('/services.php'); ?>" style="color: var(--text-main);"><i class="fa-solid fa-angle-right"></i> Chế Tạo Bồn Bể & Đường Ống</a></li>
      <li><a href="<?php echo url('/services.php'); ?>" style="color: var(--text-main);"><i class="fa-solid fa-angle-right"></i> Bảo Dưỡng Công Nghiệp</a></li>
    </ul>
  </div>

  <div class="sidebar-widget" style="background: var(--primary-navy); color: #fff; border-radius: 12px; padding: 24px;">
    <h3 style="color: #fff; font-size: 18px; margin-bottom: 12px;"><i class="fa-solid fa-headset" style="color: var(--accent-gold);"></i> Tư Vấn Trực Tiếp</h3>
    <p style="font-size: 13px; color: var(--text-light); margin-bottom: 16px;">Đội ngũ kỹ sư PNMEC sẵn sàng giải đáp mọi thắc mắc 24/7.</p>
    <a href="tel:0988123456" class="btn btn-primary" style="width: 100%; text-align: center;"><i class="fa-solid fa-phone"></i> Hotline: 0988.123.456</a>
  </div>
</aside>
