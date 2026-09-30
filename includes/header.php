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
    'index.php'          => '/test/web_cty/assets/css/user/home.css',
    'about.php'          => '/test/web_cty/assets/css/user/about.css',
    'services.php'       => '/test/web_cty/assets/css/user/services.css',
    'service-detail.php' => '/test/web_cty/assets/css/user/service-detail.css',
    'projects.php'       => '/test/web_cty/assets/css/user/projects.css',
    'project-detail.php'  => '/test/web_cty/assets/css/user/project-detail.css',
    'quote.php'          => '/test/web_cty/assets/css/user/quote.css',
    'news.php'           => '/test/web_cty/assets/css/user/news.css',
    'news-detail.php'    => '/test/web_cty/assets/css/user/news-detail.css',
    'contact.php'        => '/test/web_cty/assets/css/user/contact.css',
    'recruitment.php'    => '/test/web_cty/assets/css/user/recruitment.css',
];
$active_css = $page_css_map[$current_page] ?? '/test/web_cty/assets/css/user/home.css';
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
  
  <!-- Common CSS System -->
  <link rel="stylesheet" href="/test/web_cty/assets/css/common/reset.css?v=2">
  <link rel="stylesheet" href="/test/web_cty/assets/css/common/variables.css?v=2">
  <link rel="stylesheet" href="/test/web_cty/assets/css/common/header.css?v=2">
  <link rel="stylesheet" href="/test/web_cty/assets/css/common/footer.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/common/components.css?v=2">
  
  <!-- User Page Specific CSS -->
  <link rel="stylesheet" href="<?php echo $active_css; ?>?v=<?php echo time(); ?>">
</head>
<body>

  <!-- Top Contact Bar -->
  <div class="topbar">
    <div class="header-container topbar-content">
      <div class="topbar-info">
        <span><i class="fa-solid fa-phone"></i> Hotline: <a href="tel:<?php echo $phone; ?>" class="topbar-link"><?php echo $phone; ?></a></span>
        <span><i class="fa-solid fa-envelope"></i> <?php echo $email; ?></span>
        <span><i class="fa-solid fa-clock"></i> <?php echo $working_hours; ?></span>
      </div>
      <div class="topbar-right">
        <a href="/test/web_cty/admin/login.php" class="topbar-link"><i class="fa-solid fa-lock"></i> Đăng nhập Nhân viên</a>
      </div>
    </div>
  </div>

  <!-- Main Sticky Header & Navigation -->
  <header class="main-header">
    <div class="header-container navbar">
      <a href="/test/web_cty/index.php" class="brand-logo">
        <img src="/test/web_cty/assets/images/logo.png?v=2" alt="<?php echo $short_name; ?> - THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG" class="site-logo-img">
      </a>

      <button class="menu-toggle" aria-label="Toggle Navigation">
        <i class="fa-solid fa-bars"></i>
      </button>

      <ul class="nav-menu">
        <li><a href="/test/web_cty/index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Trang chủ</a></li>
        <li><a href="/test/web_cty/about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">Giới thiệu</a></li>
        <li><a href="/test/web_cty/services.php" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Dịch vụ</a></li>
        <li><a href="/test/web_cty/projects.php" class="nav-link <?php echo ($current_page == 'projects.php' || $current_page == 'project-detail.php') ? 'active' : ''; ?>">Công trình</a></li>
        <li><a href="/test/web_cty/recruitment.php" class="nav-link <?php echo ($current_page == 'recruitment.php') ? 'active' : ''; ?>">Tuyển dụng</a></li>
        <li><a href="/test/web_cty/contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Liên hệ</a></li>
        <li><a href="/test/web_cty/contact.php" class="btn btn-primary btn-sm nav-cta-btn"><i class="fa-solid fa-calculator"></i> BÁO GIÁ NHANH</a></li>
      </ul>
    </div>
  </header>
