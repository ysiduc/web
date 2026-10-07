<?php
require_once __DIR__ . '/functions.php';

$current_page = basename($_SERVER['SCRIPT_NAME']);
$site_name = get_site_info('site_name', 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC');
$short_name = get_site_info('company_short_name', 'PNMEC');
$hotline = get_site_info('hotline', '1900.6868');
$phone = get_site_info('phone', '0988.123.456');
$email = get_site_info('email', 'contact@pnmec.vn');
$working_hours = get_site_info('working_hours', 'Thứ 2 - Thứ 7: 07:30 - 17:30');

// Dynamic Page CSS Map
$page_css_map = [
    'index.php'          => 'css/user/home.css',
    'about.php'          => 'css/user/about.css',
    'services.php'       => 'css/user/services.css',
    'service-detail.php' => 'css/user/service-detail.css',
    'projects.php'       => 'css/user/projects.css',
    'project-detail.php' => 'css/user/project-detail.css',
    'quote.php'          => 'css/user/quote.css',
    'news.php'           => 'css/user/news.css',
    'news-detail.php'    => 'css/user/news-detail.css',
    'contact.php'        => 'css/user/contact.css',
    'recruitment.php'    => 'css/user/recruitment.css',
];
$active_css_path = $page_css_map[$current_page] ?? 'css/user/home.css';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' - ' . $short_name : $site_name; ?></title>
  <meta name="description" content="<?php echo get_site_info('about_summary'); ?>">
  
  <!-- Google Fonts (Montserrat) & FontAwesome CDN -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Common CSS System (Dynamic Cache-Busting via filemtime) -->
  <link rel="stylesheet" href="<?php echo versioned_asset_url('css/common/reset.css'); ?>">
  <link rel="stylesheet" href="<?php echo versioned_asset_url('css/common/variables.css'); ?>">
  <link rel="stylesheet" href="<?php echo versioned_asset_url('css/common/header.css'); ?>">
  <link rel="stylesheet" href="<?php echo versioned_asset_url('css/common/footer.css'); ?>">
  <link rel="stylesheet" href="<?php echo versioned_asset_url('css/common/components.css'); ?>">
  
  <!-- User Page Specific CSS -->
  <link rel="stylesheet" href="<?php echo versioned_asset_url($active_css_path); ?>">
</head>
<body>

  <!-- Top Contact Bar -->
  <div class="topbar">
    <div class="header-container topbar-content">
      <div class="topbar-info">
        <span class="topbar-item topbar-item--hotline">
          <i class="fa-solid fa-phone"></i>
          Hotline:
          <a href="tel:<?php echo $phone; ?>" class="topbar-link">
            <?php echo $phone; ?>
          </a>
        </span>

        <span class="topbar-item topbar-item--email">
          <i class="fa-solid fa-envelope"></i>
          <?php echo $email; ?>
        </span>

        <span class="topbar-item topbar-item--hours">
          <i class="fa-solid fa-clock"></i>
          <?php echo $working_hours; ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Main Sticky Header & Navigation -->
  <header class="main-header">
    <div class="header-container navbar">
      <a href="<?php echo url('/index.php'); ?>" class="brand-logo" aria-label="<?php echo $short_name; ?> - Trang chủ">
        <img src="<?php echo versioned_asset_url('images/logo.png'); ?>" alt="<?php echo $short_name; ?> - THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG" class="site-logo-img">
      </a>

      <button class="menu-toggle" id="menuToggle" aria-label="Mở menu điều hướng" aria-expanded="false" aria-controls="mobileDrawer">
        <i class="fa-solid fa-bars"></i>
      </button>

      <!-- Desktop Nav Menu -->
      <ul class="nav-menu">
        <li><a href="<?php echo url('/index.php'); ?>" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Trang chủ</a></li>
        <li><a href="<?php echo url('/about.php'); ?>" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">Giới thiệu</a></li>
        <li><a href="<?php echo url('/services.php'); ?>" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Dịch vụ</a></li>
        <li><a href="<?php echo url('/projects.php'); ?>" class="nav-link <?php echo ($current_page == 'projects.php' || $current_page == 'project-detail.php') ? 'active' : ''; ?>">Công trình</a></li>
        <li><a href="<?php echo url('/recruitment.php'); ?>" class="nav-link <?php echo ($current_page == 'recruitment.php') ? 'active' : ''; ?>">Tuyển dụng</a></li>
        <li><a href="<?php echo url('/news.php'); ?>" class="nav-link <?php echo ($current_page == 'news.php' || $current_page == 'news-detail.php') ? 'active' : ''; ?>">Tin tức</a></li>
        <li><a href="<?php echo url('/contact.php'); ?>" class="btn btn-primary btn-sm nav-cta-btn"><i class="fa-solid fa-calculator"></i> Liên hệ + Báo giá</a></li>
      </ul>
    </div>
  </header>

  <!-- Mobile Navigation Drawer & Backdrop -->
  <div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop" aria-hidden="true"></div>
  <aside class="mobile-drawer" id="mobileDrawer" aria-label="Mobile Navigation" aria-hidden="true">
    <div class="mobile-drawer__head">
      <div class="mobile-drawer__brand">
        <span class="mobile-drawer__brand-title"><?php echo $short_name; ?></span>
        <span class="mobile-drawer__brand-sub">Cơ Khí &amp; Xây Dựng</span>
      </div>
      <button class="mobile-drawer__close" id="mobileDrawerClose" aria-label="Đóng menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <div class="mobile-drawer__body">
      <ul class="mobile-nav-list">
        <li><a href="<?php echo url('/index.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
        <li><a href="<?php echo url('/about.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>"><i class="fa-solid fa-building"></i> Giới thiệu</a></li>
        <li><a href="<?php echo url('/services.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>"><i class="fa-solid fa-wrench"></i> Dịch vụ</a></li>
        <li><a href="<?php echo url('/projects.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'projects.php' || $current_page == 'project-detail.php') ? 'active' : ''; ?>"><i class="fa-solid fa-layer-group"></i> Công trình</a></li>
        <li><a href="<?php echo url('/news.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'news.php' || $current_page == 'news-detail.php') ? 'active' : ''; ?>"><i class="fa-solid fa-newspaper"></i> Tin tức</a></li>
        <li><a href="<?php echo url('/recruitment.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'recruitment.php') ? 'active' : ''; ?>"><i class="fa-solid fa-user-plus"></i> Tuyển dụng</a></li>
        <li><a href="<?php echo url('/contact.php'); ?>" class="mobile-nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>"><i class="fa-solid fa-phone"></i> Liên hệ</a></li>
      </ul>

      <div class="mobile-drawer__cta">
        <a href="<?php echo url('/contact.php'); ?>" class="btn btn-primary mobile-cta-btn">
          <i class="fa-solid fa-calculator"></i> Liên hệ + Báo giá
        </a>
      </div>

      <div class="mobile-drawer__info">
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-phone"></i>
          <div>
            <span>Hotline 24/7:</span>
            <a href="tel:<?php echo $phone; ?>"><strong><?php echo $phone; ?></strong></a>
          </div>
        </div>
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-envelope"></i>
          <div>
            <span>Email hỗ trợ:</span>
            <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
          </div>
        </div>
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-clock"></i>
          <div>
            <span>Giờ làm việc:</span>
            <span><?php echo $working_hours; ?></span>
          </div>
        </div>
      </div>
    </div>
  </aside>
