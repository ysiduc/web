<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    api_response(false, null, 'ID dịch vụ không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch();

    if (!$service) {
        api_response(false, null, 'Không tìm thấy dịch vụ.', 404);
    }

    api_response(true, $service, 'Lấy chi tiết dịch vụ thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
