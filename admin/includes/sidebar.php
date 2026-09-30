<?php
/**
 * Admin Sidebar Navigation Component - PNMEC
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['SCRIPT_NAME']);
$user = $_SESSION['user'] ?? ['fullname' => 'Admin', 'role' => 'admin'];
?>
<aside class="admin-sidebar">
  <div class="sidebar-header">
    <i class="fa-solid fa-gears" style="font-size: 24px; color: var(--accent-gold);"></i>
    <h2>QUẢN TRỊ PNMEC</h2>
  </div>

  <ul class="sidebar-nav">
    <li class="nav-category">TỔNG QUAN HỆ THỐNG</li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/index.php" class="nav-link-admin <?= ($current_page == 'index.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-chart-line"></i> Dashboard Tổng Quan
      </a>
    </li>

    <li class="nav-category">QUẢN LÝ NỘI DUNG &amp; DỰ ÁN</li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/projects/list.php" class="nav-link-admin <?= ($current_page == 'list.php' || $current_page == 'add.php' || $current_page == 'edit.php' || $current_page == 'manage-projects.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-building"></i> Quản Lý Công Trình
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/manage-services.php" class="nav-link-admin <?= ($current_page == 'manage-services.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-screwdriver-wrench"></i> Quản Lý Dịch Vụ (12 Mục)
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/manage-quotes.php" class="nav-link-admin <?= ($current_page == 'manage-quotes.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-calculator"></i> Yêu Cầu Báo Giá
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/manage-contacts.php" class="nav-link-admin <?= ($current_page == 'manage-contacts.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-envelope-open-text"></i> Khách Hàng Liên Hệ
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/manage-news.php" class="nav-link-admin <?= ($current_page == 'manage-news.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-newspaper"></i> Tin Tức &amp; Hoạt Động
      </a>
    </li>

    <li class="nav-category">CÀI ĐẶT &amp; LIÊN KẾT</li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/settings/site_info.php" class="nav-link-admin <?= ($current_page == 'site_info.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-sliders"></i> Cấu Hình Doanh Nghiệp
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/settings/users.php" class="nav-link-admin <?= ($current_page == 'users.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-users-gear"></i> Quản Trị Viên &amp; Kỹ Sư
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/index.php" target="_blank" class="nav-link-admin">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Website Khách Hàng
      </a>
    </li>
    <li class="nav-item">
      <a href="/test/web_cty/admin/logout.php" class="nav-link-admin" style="color: #ef4444;">
        <i class="fa-solid fa-right-from-bracket"></i> Đăng Xuất Hệ Thống
      </a>
    </li>
  </ul>
</aside>
