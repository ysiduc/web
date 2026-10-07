<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

require_login();
$user = get_logged_user();
$current_page = basename($_SERVER['SCRIPT_NAME']);
$current_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' - Antigravity Admin' : 'Hệ Thống Quản Trị'; ?></title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= asset_url('css/admin/layout.css') ?>">
  <link rel="stylesheet" href="<?= asset_url('css/admin/dashboard.css') ?>">
  <link rel="stylesheet" href="<?= asset_url('css/admin/tables.css') ?>">
  <link rel="stylesheet" href="<?= asset_url('css/admin/forms.css') ?>">
</head>
<body class="admin-body">

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <i class="fa-solid fa-gears"></i>
      <h2>QUẢN TRỊ PNMEC</h2>
    </div>

    <ul class="sidebar-nav">
      <li class="nav-category">Tổng quan</li>
      <li class="nav-item">
        <a href="<?= url('admin/index.php') ?>" class="nav-link-admin <?php echo ($current_page == 'index.php' && $current_dir == 'admin') ? 'active' : ''; ?>">
          <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
      </li>

      <li class="nav-category">Quản lý Công trình</li>
      <li class="nav-item">
        <a href="<?= url('admin/projects/list.php') ?>" class="nav-link-admin <?php echo ($current_dir == 'projects' && $current_page == 'list.php') ? 'active' : ''; ?>">
          <i class="fa-solid fa-list-check"></i> Danh sách công trình
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= url('admin/projects/add.php') ?>" class="nav-link-admin <?php echo ($current_dir == 'projects' && $current_page == 'add.php') ? 'active' : ''; ?>">
          <i class="fa-solid fa-circle-plus"></i> Đăng công trình mới
        </a>
      </li>

      <?php if (is_admin()): ?>
      <li class="nav-category">Hệ thống Admin</li>
      <li class="nav-item">
        <a href="<?= url('admin/settings/site_info.php') ?>" class="nav-link-admin <?php echo ($current_dir == 'settings' && $current_page == 'site_info.php') ? 'active' : ''; ?>">
          <i class="fa-solid fa-sliders"></i> Thông tin Website
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= url('admin/settings/users.php') ?>" class="nav-link-admin <?php echo ($current_dir == 'settings' && $current_page == 'users.php') ? 'active' : ''; ?>">
          <i class="fa-solid fa-users-gear"></i> Tài khoản nhân viên
        </a>
      </li>
      <?php endif; ?>

      <li class="nav-category">Tiện ích</li>
      <li class="nav-item">
        <a href="<?= url('index.php') ?>" target="_blank" class="nav-link-admin">
          <i class="fa-solid fa-globe"></i> Xem trang chủ
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= url('admin/logout.php') ?>" class="nav-link-admin" style="color: #ef4444;">
          <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
      </li>
    </ul>
  </aside>

  <!-- Admin Main Content Container -->
  <main class="admin-main">
    <header class="admin-header">
      <h1><?php echo isset($page_title) ? $page_title : 'Bảng Điều Khiển'; ?></h1>
      
      <div class="user-profile">
        <div class="user-avatar">
          <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
        </div>
        <div class="user-info">
          <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
          <span><?php echo $user['role'] === 'admin' ? 'Quản trị viên (Admin)' : 'Nhân viên (Staff)'; ?></span>
        </div>
      </div>
    </header>

    <div class="admin-container">
      <?php 
      $flash = get_flash_message();
      if ($flash): 
      ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
          <i class="fa-solid fa-circle-info"></i> <?php echo htmlspecialchars($flash['text']); ?>
        </div>
      <?php endif; ?>
