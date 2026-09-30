<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header("Location: /test/web_cty/admin/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ Tên đăng nhập và Mật khẩu.';
    } else {
        $result = login_user($username, $password);
        if ($result['status']) {
            header("Location: /test/web_cty/admin/index.php");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập Quản Trị - PNMEC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/test/web_cty/assets/css/admin/login.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div style="text-align: center; margin-bottom: 30px;">
      <i class="fa-solid fa-gears" style="font-size: 42px; color: var(--accent-gold); margin-bottom: 10px;"></i>
      <h2 style="font-size: 22px; color: var(--primary-navy); margin-bottom: 4px;">PNMEC ADMIN</h2>
      <p style="font-size: 13px; color: var(--text-muted);">Đăng nhập tài khoản quản trị hệ thống</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger" style="padding: 10px 14px; font-size: 13px; margin-bottom: 20px; background: #fee2e2; color: #b91c1c; border-radius: 8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group" style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px;">Tên đăng nhập</label>
        <input type="text" name="username" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="admin" required autofocus>
      </div>

      <div class="form-group" style="margin-bottom: 24px;">
        <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px;">Mật khẩu</label>
        <input type="password" name="password" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px;" placeholder="••••••••" required>
      </div>

      <button type="submit" style="width: 100%; padding: 14px; background: var(--accent-gold); color: var(--primary-navy); border: none; border-radius: 8px; font-weight: 800; cursor: pointer; font-size: 15px;">
        <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập Hệ Thống
      </button>
    </form>
  </div>
</body>
</html>
