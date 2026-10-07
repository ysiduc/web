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

    $sql = "SELECT * FROM recruitments WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (title LIKE :s1 OR location LIKE :s2 OR employment_type LIKE :s3 OR salary LIKE :s4)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
        $params['s4'] = "%$search%";
    }

    if ($status !== '' && in_array($status, ['published', 'draft'], true)) {
        $sql .= " AND status = :st";
        $params['st'] = $status;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $recruitments = $stmt->fetchAll();

    api_response(true, [
        'recruitments' => $recruitments,
        'total'        => count($recruitments)
    ], 'Lấy danh sách tin tuyển dụng thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy tin tuyển dụng: ' . $e->getMessage(), 500);
}
