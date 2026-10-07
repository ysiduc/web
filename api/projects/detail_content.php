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
    $stmt = $db->prepare("SELECT id, title, slug, detail_mode, detail_blocks FROM projects WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch();

    if (!$project) {
        api_response(false, null, 'Không tìm thấy công trình.', 404);
    }

    $blocks = [];
    if (!empty($project['detail_blocks'])) {
        $decoded = json_decode($project['detail_blocks'], true);
        if (is_array($decoded)) {
            $blocks = $decoded;
        }
    }

    api_response(true, [
        'id'            => (int)$project['id'],
        'title'         => $project['title'],
        'slug'          => $project['slug'],
        'detail_mode'   => $project['detail_mode'] ?: 'basic',
        'detail_blocks' => $blocks
    ], 'Lấy nội dung chi tiết thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
