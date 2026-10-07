<?php
$page_title = "Quản Lý Tài Khoản Nhân Viên";
require_once __DIR__ . '/../includes/admin-header.php';

require_admin(); // Bắt buộc phải là Admin

$db = getDBConnection();
$error = '';

// Thêm tài khoản mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $role     = $_POST['role'] === 'admin' ? 'admin' : 'staff';

    if (empty($username) || empty($password) || empty($fullname)) {
        $error = 'Vui lòng nhập đầy đủ Tên đăng nhập, Mật khẩu và Họ tên.';
    } else {
        if ($db) {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, password, fullname, email, phone, role) VALUES (:username, :password, :fullname, :email, :phone, :role)");
                $stmt->execute([
                    'username' => $username,
                    'password' => $hash,
                    'fullname' => $fullname,
                    'email'    => $email,
                    'phone'    => $phone,
                    'role'     => $role
                ]);
                set_flash_message('success', 'Đã tạo tài khoản nhân viên mới thành công!');
                header("Location: " . url('admin/settings/users.php'));
                exit;
            } catch (Exception $e) {
                $error = 'Tên đăng nhập đã tồn tại hoặc lỗi CSDL!';
            }
        } else {
            set_flash_message('success', 'Đã thêm nhân viên mới (chế độ demo).');
            header("Location: " . url('admin/settings/users.php'));
            exit;
        }
    }
}

// Lấy danh sách users
$users_list = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM users ORDER BY id ASC");
        $users_list = $stmt->fetchAll();
    } catch (Exception $e) {}
}

if (empty($users_list)) {
    $users_list = [
        [
            'id' => 1,
            'username' => 'admin',
            'fullname' => 'Quản Trị Viên Hướng Nam',
            'email' => 'admin@cokhixaydung.vn',
            'phone' => '0912345678',
            'role' => 'admin',
            'created_at' => '2026-01-01'
        ],
        [
            'id' => 2,
            'username' => 'staff',
            'fullname' => 'Nguyễn Văn Kỹ Sư',
            'email' => 'kysustaff@cokhixaydung.vn',
            'phone' => '0987654321',
            'role' => 'staff',
            'created_at' => '2026-01-05'
        ]
    ];
}
?>

<div style="display: grid; grid-template-columns: 2fr 1.2fr; gap: 30px;">
  <!-- Left Column: Users List Table -->
  <div>
    <div class="card-table">
      <div class="card-header-table">
        <h3><i class="fa-solid fa-users"></i> Danh Sách Tài Khoản Hệ Thống</h3>
        <span class="badge badge-admin"><i class="fa-solid fa-shield"></i> Quyền Admin</span>
      </div>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Đăng Nhập</th>
              <th>Họ & Tên</th>
              <th>Email / SĐT</th>
              <th>Vai Trò</th>
              <th>Ngày Tạo</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users_list as $u): ?>
              <tr>
                <td>#<?php echo $u['id']; ?></td>
                <td><strong style="color: #0f172a;"><?php echo htmlspecialchars($u['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                <td>
                  <div style="font-size: 13px;"><?php echo htmlspecialchars($u['email']); ?></div>
                  <div style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars($u['phone']); ?></div>
                </td>
                <td>
                  <?php if ($u['role'] === 'admin'): ?>
                    <span class="badge badge-admin">Admin</span>
                  <?php else: ?>
                    <span class="badge badge-staff">Staff</span>
                  <?php endif; ?>
                </td>
                <td><?php echo format_date($u['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Right Column: Add New User Form -->
  <div>
    <div style="background: #fff; padding: 25px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
      <h3 style="font-size: 16px; margin-bottom: 20px; color: #0f172a; border-bottom: 2px solid #f59e0b; padding-bottom: 8px;">
        <i class="fa-solid fa-user-plus"></i> Thêm Nhân Viên Mới
      </h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="hidden" name="action" value="add">

        <div class="form-group">
          <label>Tên đăng nhập <span style="color: red;">*</span></label>
          <input type="text" name="username" class="form-control" placeholder="Ví dụ: nhanvien01" required>
        </div>

        <div class="form-group">
          <label>Mật khẩu khởi tạo <span style="color: red;">*</span></label>
          <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
        </div>

        <div class="form-group">
          <label>Họ và tên nhân viên <span style="color: red;">*</span></label>
          <input type="text" name="fullname" class="form-control" placeholder="Ví dụ: Trần Văn Giám Sát" required>
        </div>

        <div class="form-group">
          <label>Địa chỉ Email</label>
          <input type="email" name="email" class="form-control" placeholder="nhanvien@cokhixaydung.vn">
        </div>

        <div class="form-group">
          <label>Số điện thoại</label>
          <input type="text" name="phone" class="form-control" placeholder="09xxxxxxxx">
        </div>

        <div class="form-group">
          <label>Phân quyền truy cập <span style="color: red;">*</span></label>
          <select name="role" class="form-control" required>
            <option value="staff">Staff (Chỉ được đăng/sửa Công trình)</option>
            <option value="admin">Admin (Toàn quyền hệ thống & cài đặt)</option>
          </select>
        </div>

        <button type="submit" class="btn-action" style="width: 100%; padding: 12px; background: #f59e0b; color: #0f172a; font-weight: 700; font-size: 14px; border-radius: 8px; justify-content: center; margin-top: 10px;">
          <i class="fa-solid fa-user-check"></i> Tạo Tài Khoản Mới
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
