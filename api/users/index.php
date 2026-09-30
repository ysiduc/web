<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_admin();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->query("SELECT id, username, fullname, email, phone, role, status, created_at FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll();

    api_response(true, [
        'users' => $users,
        'total' => count($users)
    ], 'Lấy danh sách người dùng thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
