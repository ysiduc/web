<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    api_response(false, null, 'ID công trình không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT p.*, u.fullname as author_name FROM projects p LEFT JOIN users u ON p.created_by = u.id WHERE p.id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch();

    if (!$project) {
        api_response(false, null, 'Không tìm thấy công trình.', 404);
    }

    api_response(true, $project, 'Lấy chi tiết công trình thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
