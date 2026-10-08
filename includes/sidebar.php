<?php
/**
 * Shared Sidebar Component (Used in detail/category pages)
 */
require_once __DIR__ . '/functions.php';

$sb_phone = get_site_info('phone', '0981700888');
$sb_hotline = get_site_info('hotline', '0911391999');
$sb_hours = get_site_info('working_hours', '24/7');
$primary_phone = $sb_hotline ?: $sb_phone;
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
    <p style="font-size: 13px; color: var(--text-light); margin-bottom: 16px;">Đội ngũ kỹ sư PNMEC sẵn sàng giải đáp mọi thắc mắc <?php echo htmlspecialchars($sb_hours); ?>.</p>
    <a href="<?php echo tel_url($primary_phone); ?>" class="btn btn-primary" style="width: 100%; text-align: center;"><i class="fa-solid fa-phone"></i> Hotline: <?php echo htmlspecialchars($primary_phone); ?></a>
  </div>
</aside>
