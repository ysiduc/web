<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $search = trim($_GET['search'] ?? '');
    $status = trim($_GET['status'] ?? '');

    $sql = "SELECT * FROM news WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (title LIKE :s1 OR summary LIKE :s2 OR author LIKE :s3)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
    }

    if ($status !== '') {
        $sql .= " AND status = :st";
        $params['st'] = $status;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $news = $stmt->fetchAll();

    api_response(true, [
        'news' => $news,
        'total' => count($news)
    ], 'Lấy danh sách tin tức thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy tin tức: ' . $e->getMessage(), 500);
}
