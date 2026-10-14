<?php
require_once __DIR__ . '/functions.php';

$current_page = basename($_SERVER['SCRIPT_NAME']);
$site_name = get_site_info('site_name', 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC');
$short_name = get_site_info('company_short_name', 'PNMEC');
$hotline = get_site_info('hotline', '0911391999');
$phone = get_site_info('phone', '0981700888');
$email = get_site_info('email', 'pnmec.vn@gmail.com');
$working_hours = get_site_info('working_hours', '24/7');
$address = get_site_info('address', 'Số 26 Ngõ 139, Phố Hoa Lâm, Việt Hưng, Hà Nội');
$factory_address = get_site_info('factory_address', '');
$facebook_url = get_site_info('facebook_url', '');
$youtube_url = get_site_info('youtube_url', '');
$zalo_url = get_site_info('zalo_url', '');
$site_summary = get_site_info('about_summary', 'PNMEC là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công nhà xưởng kết cấu thép.');

// 1. Meta Title Calculation
if (!empty($page_title)) {
    if (stripos($page_title, $short_name) !== false) {
        $meta_title = $page_title;
    } else {
        $meta_title = $page_title . ' | ' . $short_name;
    }
} else {
    $meta_title = $site_name . ' - Thiết Kế & Thi Công Cơ Khí Xây Dựng';
}

// 2. Meta Description Calculation (Clean, Max 165 chars)
if (!empty($page_description)) {
    $meta_description = mb_substr(trim(strip_tags($page_description)), 0, 165, 'UTF-8');
} else {
    $meta_description = mb_substr(trim(strip_tags($site_summary)), 0, 165, 'UTF-8');
}

// 3. Robots Meta Tag
$meta_robots = !empty($page_robots) ? $page_robots : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

// 4. Canonical URL Calculation
if (!empty($page_canonical)) {
    $canonical_url = $page_canonical;
} else {
    $clean_current = ($current_page === 'index.php') ? '' : str_replace('.php', '', $current_page);
    $canonical_url = absolute_url($clean_current ? '/' . $clean_current : '/');
}

// 5. Open Graph & Twitter Image
if (!empty($page_image)) {
    $og_image = absolute_url($page_image);
} else {
    $og_image = absolute_url('assets/images/logo.png');
}

$og_type = !empty($page_og_type) ? $page_og_type : (($current_page === 'news-detail.php') ? 'article' : 'website');

// 6. JSON-LD Organization & WebSite Schemas
$same_as = [];
if (!empty($facebook_url)) $same_as[] = $facebook_url;
if (!empty($youtube_url))  $same_as[] = $youtube_url;
if (!empty($zalo_url))     $same_as[] = $zalo_url;

$org_schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'GeneralContractor',
    '@id'      => 'https://pnmec.vn/#organization',
    'name'     => $site_name,
    'alternateName' => $short_name,
    'url'      => 'https://pnmec.vn/',
    'logo'     => absolute_url('assets/images/logo.png'),
    'image'    => absolute_url('assets/images/logo.png'),
    'description' => $site_summary,
    'telephone' => $hotline ?: $phone,
    'email'     => $email,
    'address'   => [
        '@type' => 'PostalAddress',
        'streetAddress' => $address,
        'addressCountry' => 'VN'
    ]
];
if (!empty($same_as)) {
    $org_schema['sameAs'] = $same_as;
}

$website_schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    '@id'      => 'https://pnmec.vn/#website',
    'url'      => 'https://pnmec.vn/',
    'name'     => $short_name,
    'publisher' => [
        '@id' => 'https://pnmec.vn/#organization'
    ],
    'inLanguage' => 'vi'
];

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
  <title><?php echo htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8'); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="robots" content="<?php echo htmlspecialchars($meta_robots, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="google-site-verification" content="eY3wJJr9J71D5tuFAimHx_iGle9pTkIK6v8Ccabd5OU">

  <!-- Canonical URL -->
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="<?php echo htmlspecialchars($og_type, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:site_name" content="<?php echo htmlspecialchars($short_name, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:locale" content="vi_VN">
  <?php if (!empty($article_published_time)): ?>
  <meta property="article:published_time" content="<?php echo htmlspecialchars($article_published_time, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endif; ?>
  <?php if (!empty($article_author)): ?>
  <meta property="article:author" content="<?php echo htmlspecialchars($article_author, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endif; ?>

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">

  <!-- Structured Data (JSON-LD) -->
  <script type="application/ld+json">
<?php echo json_encode($org_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT); ?>
  </script>
  <script type="application/ld+json">
<?php echo json_encode($website_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT); ?>
  </script>
  <?php if (!empty($schema_data)): ?>
  <script type="application/ld+json">
<?php echo json_encode($schema_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT); ?>
  </script>
  <?php endif; ?>

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
          <a href="<?php echo tel_url($hotline ?: $phone); ?>" class="topbar-link">
            <?php echo htmlspecialchars($hotline ?: $phone); ?>
          </a>
        </span>

        <?php if (!empty($phone) && $phone !== $hotline): ?>
        <span class="topbar-item topbar-item--phone">
          <i class="fa-solid fa-headset"></i>
          Tư vấn:
          <a href="<?php echo tel_url($phone); ?>" class="topbar-link">
            <?php echo htmlspecialchars($phone); ?>
          </a>
        </span>
        <?php endif; ?>

        <span class="topbar-item topbar-item--email">
          <i class="fa-solid fa-envelope"></i>
          <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="topbar-link">
            <?php echo htmlspecialchars($email); ?>
          </a>
        </span>

        <span class="topbar-item topbar-item--hours">
          <i class="fa-solid fa-clock"></i>
          <?php echo htmlspecialchars($working_hours); ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Main Sticky Header & Navigation -->
  <header class="main-header">
    <div class="header-container navbar">
      <a href="<?php echo url('/'); ?>" class="brand-logo" aria-label="<?php echo $short_name; ?> - Trang chủ">
        <img src="<?php echo versioned_asset_url('images/logo.png'); ?>" alt="<?php echo $short_name; ?> - THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG" class="site-logo-img">
      </a>

      <button class="menu-toggle" id="menuToggle" aria-label="Mở menu điều hướng" aria-expanded="false" aria-controls="mobileDrawer">
        <i class="fa-solid fa-bars"></i>
      </button>

      <!-- Desktop Nav Menu -->
      <ul class="nav-menu">
        <li><a href="<?php echo url('/'); ?>" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Trang chủ</a></li>
        <li><a href="<?php echo url('/about'); ?>" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">Giới thiệu</a></li>
        <li><a href="<?php echo url('/services'); ?>" class="nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Dịch vụ</a></li>
        <li><a href="<?php echo url('/projects'); ?>" class="nav-link <?php echo ($current_page == 'projects.php' || $current_page == 'project-detail.php') ? 'active' : ''; ?>">Công trình</a></li>
        <li><a href="<?php echo url('/recruitment'); ?>" class="nav-link <?php echo ($current_page == 'recruitment.php') ? 'active' : ''; ?>">Tuyển dụng</a></li>
        <li><a href="<?php echo url('/news'); ?>" class="nav-link <?php echo ($current_page == 'news.php' || $current_page == 'news-detail.php') ? 'active' : ''; ?>">Tin tức</a></li>
        <li><a href="<?php echo url('/contact'); ?>" class="btn btn-primary btn-sm nav-cta-btn"><i class="fa-solid fa-calculator"></i> Liên hệ + Báo giá</a></li>
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
        <li><a href="<?php echo url('/'); ?>" class="mobile-nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
        <li><a href="<?php echo url('/about'); ?>" class="mobile-nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>"><i class="fa-solid fa-building"></i> Giới thiệu</a></li>
        <li><a href="<?php echo url('/services'); ?>" class="mobile-nav-link <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>"><i class="fa-solid fa-wrench"></i> Dịch vụ</a></li>
        <li><a href="<?php echo url('/projects'); ?>" class="mobile-nav-link <?php echo ($current_page == 'projects.php' || $current_page == 'project-detail.php') ? 'active' : ''; ?>"><i class="fa-solid fa-layer-group"></i> Công trình</a></li>
        <li><a href="<?php echo url('/news'); ?>" class="mobile-nav-link <?php echo ($current_page == 'news.php' || $current_page == 'news-detail.php') ? 'active' : ''; ?>"><i class="fa-solid fa-newspaper"></i> Tin tức</a></li>
        <li><a href="<?php echo url('/recruitment'); ?>" class="mobile-nav-link <?php echo ($current_page == 'recruitment.php') ? 'active' : ''; ?>"><i class="fa-solid fa-user-plus"></i> Tuyển dụng</a></li>
        <li><a href="<?php echo url('/contact'); ?>" class="mobile-nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>"><i class="fa-solid fa-phone"></i> Liên hệ</a></li>
      </ul>

      <div class="mobile-drawer__cta">
        <a href="<?php echo url('/contact'); ?>" class="btn btn-primary mobile-cta-btn">
          <i class="fa-solid fa-calculator"></i> Liên hệ + Báo giá
        </a>
      </div>

      <div class="mobile-drawer__info">
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-phone"></i>
          <div>
            <span>Hotline 24/7:</span>
            <a href="<?php echo tel_url($hotline ?: $phone); ?>"><strong><?php echo htmlspecialchars($hotline ?: $phone); ?></strong></a>
          </div>
        </div>
        <?php if (!empty($phone) && $phone !== $hotline): ?>
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-headset"></i>
          <div>
            <span>Tư vấn kỹ thuật:</span>
            <a href="<?php echo tel_url($phone); ?>"><strong><?php echo htmlspecialchars($phone); ?></strong></a>
          </div>
        </div>
        <?php endif; ?>
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-envelope"></i>
          <div>
            <span>Email hỗ trợ:</span>
            <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a>
          </div>
        </div>
        <div class="mobile-drawer__info-item">
          <i class="fa-solid fa-clock"></i>
          <div>
            <span>Giờ làm việc:</span>
            <span><?php echo htmlspecialchars($working_hours); ?></span>
          </div>
        </div>
      </div>
    </div>
  </aside>
