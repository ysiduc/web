<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID tin tuyển dụng không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT id FROM recruitments WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();

    if (!$item) {
        api_response(false, null, 'Không tìm thấy tin tuyển dụng.', 404);
    }

    $delStmt = $db->prepare("DELETE FROM recruitments WHERE id = :id");
    $delStmt->execute(['id' => $id]);

    api_response(true, ['id' => $id], 'Đã xóa tin tuyển dụng thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi xóa tin tuyển dụng: ' . $e->getMessage(), 500);
}
