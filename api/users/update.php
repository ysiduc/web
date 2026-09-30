<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$currentUser = require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID người dùng không hợp lệ.', 400);
}

// Only admin can edit other users; staff can only edit themselves
if ($currentUser['role'] !== 'admin' && $currentUser['id'] !== $id) {
    api_response(false, null, 'Bạn chỉ có thể chỉnh sửa thông tin tài khoản của chính mình.', 403);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$stmtOld = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmtOld->execute(['id' => $id]);
$oldUser = $stmtOld->fetch();

if (!$oldUser) {
    api_response(false, null, 'Không tìm thấy người dùng.', 404);
}

$fullname = trim($input['fullname'] ?? $oldUser['fullname']);
$email    = trim($input['email'] ?? $oldUser['email']);
$phone    = trim($input['phone'] ?? $oldUser['phone']);

// Role and status can only be modified by admin
$role = $oldUser['role'];
$status = $oldUser['status'];
if ($currentUser['role'] === 'admin') {
    if (isset($input['role']) && in_array($input['role'], ['admin', 'staff'])) {
        $role = $input['role'];
    }
    if (isset($input['status']) && in_array($input['status'], ['active', 'inactive'])) {
        // Prevent disabling current admin
        if ($id === $currentUser['id'] && $input['status'] === 'inactive') {
            api_response(false, null, 'Không thể tự vô hiệu hóa tài khoản của chính mình.', 400);
        }
        $status = $input['status'];
    }
}

// Handle password change if provided
$newPassword = trim($input['password'] ?? '');
$passwordHash = $oldUser['password'];
if (!empty($newPassword)) {
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
}

try {
    $stmt = $db->prepare("UPDATE users SET fullname = :fn, email = :em, phone = :ph, role = :ro, status = :st, password = :p WHERE id = :id");
    $stmt->execute([
        'fn' => $fullname,
        'em' => $email,
        'ph' => $phone,
        'ro' => $role,
        'st' => $status,
        'p'  => $passwordHash,
        'id' => $id
    ]);

    // If updated self, update session
    if ($id === $currentUser['id']) {
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email']    = $email;
        $_SESSION['role']     = $role;
    }

    api_response(true, [
        'id'       => $id,
        'fullname' => $fullname,
        'email'    => $email,
        'phone'    => $phone,
        'role'     => $role,
        'status'   => $status
    ], 'Cập nhật thông tin người dùng thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật: ' . $e->getMessage(), 500);
}
