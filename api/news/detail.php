<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    api_response(false, null, 'ID bài viết không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();

    if (!$item) {
        api_response(false, null, 'Không tìm thấy bài viết.', 404);
    }

    api_response(true, $item, 'Lấy chi tiết bài viết thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
