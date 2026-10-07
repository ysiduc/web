<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

// Rate limiting for login attempts (10 attempts / 5 mins / IP)
enforce_rate_limit_api('login', 10, 300);

$input = get_api_input();
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($username) || empty($password)) {
    api_response(false, null, 'Vui lòng nhập tên đăng nhập và mật khẩu.', 400);
}

$result = login_user($username, $password);

if ($result['status']) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT id, username, fullname, email, phone, role, status, created_at FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    api_response(true, [
        'user'       => $user,
        'csrf_token' => get_csrf_token()
    ], 'Đăng nhập thành công!');
} else {
    api_response(false, null, $result['message'], 401);
}
