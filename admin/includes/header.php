<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

require_login();
$user = get_logged_user();
$page_title = isset($page_title) ? $page_title : 'Hệ Thống Quản Trị Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title); ?> - PNMEC Admin</title>

  <!-- FontAwesome & Admin CSS Modular Files -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/admin/layout.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/admin/dashboard.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/admin/tables.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/admin/forms.css">
</head>
<body class="admin-body">

  <!-- Admin Sidebar -->
  <?php require_once __DIR__ . '/sidebar.php'; ?>

  <!-- Admin Main Content -->
  <main class="admin-main">
    <header class="admin-header">
      <h1><?php echo htmlspecialchars($page_title); ?></h1>

      <div class="user-profile" style="display: flex; align-items: center; gap: 12px;">
        <div class="user-avatar" style="width: 40px; height: 40px; background: var(--accent-gold); color: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800;">
          <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
        </div>
        <div class="user-info">
          <strong style="display: block; font-size: 14px; color: var(--primary-navy);"><?php echo htmlspecialchars($user['fullname']); ?></strong>
          <span style="font-size: 12px; color: var(--text-muted);"><?php echo $user['role'] === 'admin' ? 'Quản trị viên (Admin)' : 'Nhân viên (Staff)'; ?></span>
        </div>
      </div>
    </header>

    <div class="admin-container">
      <?php 
      $flash = get_flash_message();
      if ($flash): 
      ?>
        <div class="alert alert-<?php echo $flash['type']; ?>" style="margin-bottom: 20px;">
          <i class="fa-solid fa-circle-info"></i> <?php echo htmlspecialchars($flash['text']); ?>
        </div>
      <?php endif; ?>
