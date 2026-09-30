<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID công trình không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("SELECT image FROM projects WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch();

    if (!$project) {
        api_response(false, null, 'Không tìm thấy công trình.', 404);
    }

    // Clean up uploaded image if custom
    if (!empty($project['image']) && !str_starts_with($project['image'], 'default-') && !str_contains($project['image'], 'home-')) {
        $filePath = UPLOAD_DIR . $project['image'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    $delStmt = $db->prepare("DELETE FROM projects WHERE id = :id");
    $delStmt->execute(['id' => $id]);

    api_response(true, ['id' => $id], 'Đã xóa công trình thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi xóa công trình: ' . $e->getMessage(), 500);
}
