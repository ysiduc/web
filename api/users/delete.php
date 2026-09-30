<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$currentUser = require_api_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID người dùng không hợp lệ.', 400);
}

if ($id === $currentUser['id']) {
    api_response(false, null, 'Không thể tự xóa tài khoản đang đăng nhập.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $del = $db->prepare("DELETE FROM users WHERE id = :id");
    $del->execute(['id' => $id]);

    api_response(true, ['id' => $id], 'Đã xóa tài khoản người dùng thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi xóa tài khoản: ' . $e->getMessage(), 500);
}
