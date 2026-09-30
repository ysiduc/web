<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();

$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');
$fullname = trim($input['fullname'] ?? '');
$email    = trim($input['email'] ?? '');
$phone    = trim($input['phone'] ?? '');
$role     = in_array($input['role'] ?? '', ['admin', 'staff']) ? $input['role'] : 'staff';
$status   = in_array($input['status'] ?? '', ['active', 'inactive']) ? $input['status'] : 'active';

if (empty($username) || empty($password) || empty($fullname)) {
    api_response(false, null, 'Vui lòng điền đầy đủ Tên đăng nhập, Mật khẩu và Họ tên.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

// Check existing username
$chk = $db->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
$chk->execute(['u' => $username]);
if ($chk->fetch()) {
    api_response(false, null, 'Tên đăng nhập đã tồn tại trong hệ thống.', 409);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $db->prepare("INSERT INTO users (username, password, fullname, email, phone, role, status) VALUES (:u, :p, :fn, :em, :ph, :ro, :st)");
    $stmt->execute([
        'u'  => $username,
        'p'  => $hash,
        'fn' => $fullname,
        'em' => $email,
        'ph' => $phone,
        'ro' => $role,
        'st' => $status
    ]);

    $newId = (int)$db->lastInsertId();

    api_response(true, [
        'id'       => $newId,
        'username' => $username,
        'fullname' => $fullname,
        'email'    => $email,
        'role'     => $role,
        'status'   => $status
    ], 'Tạo tài khoản người dùng thành công!', 201);
} catch (Exception $e) {
    api_response(false, null, 'Lỗi tạo tài khoản: ' . $e->getMessage(), 500);
}
