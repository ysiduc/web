<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID yêu cầu báo giá không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $delStmt = $db->prepare("DELETE FROM quotes WHERE id = :id");
    $delStmt->execute(['id' => $id]);

    api_response(true, ['id' => $id], 'Đã xóa yêu cầu báo giá thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi xóa báo giá: ' . $e->getMessage(), 500);
}
