<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id     = (int)($input['id'] ?? 0);
$status = trim($input['status'] ?? '');

$allowed = ['unread', 'read', 'replied'];
if ($id <= 0 || !in_array($status, $allowed)) {
    api_response(false, null, 'Dữ liệu không hợp lệ. Trạng thái chỉ nhận: ' . implode(', ', $allowed), 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("UPDATE contacts SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);

    api_response(true, ['id' => $id, 'status' => $status], 'Cập nhật trạng thái liên hệ thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật: ' . $e->getMessage(), 500);
}
