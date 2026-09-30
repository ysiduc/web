<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID bài viết không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT image FROM news WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();

    if (!$item) {
        api_response(false, null, 'Không tìm thấy bài viết.', 404);
    }

    if (!empty($item['image']) && !str_starts_with($item['image'], 'default-')) {
        $path = UPLOAD_DIR . $item['image'];
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    $delStmt = $db->prepare("DELETE FROM news WHERE id = :id");
    $delStmt->execute(['id' => $id]);

    api_response(true, ['id' => $id], 'Đã xóa bài viết thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi xóa bài viết: ' . $e->getMessage(), 500);
}
