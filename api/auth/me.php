<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if (!is_logged_in()) {
    api_response(false, null, 'Chưa đăng nhập.', 401);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$stmt = $db->prepare("SELECT id, username, fullname, email, phone, role, status, created_at FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['status'] !== 'active') {
    logout_user();
    api_response(false, null, 'Tài khoản không tồn tại hoặc đã bị vô hiệu hóa.', 403);
}

api_response(true, [
    'user'       => $user,
    'csrf_token' => get_csrf_token()
], 'Thông tin tài khoản hiện tại.');
