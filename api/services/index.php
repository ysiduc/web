<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $search   = trim($_GET['search'] ?? '');
    $sector   = trim($_GET['sector'] ?? ''); // 'co_khi' or 'xay_dung'
    $featured = isset($_GET['featured']) && $_GET['featured'] !== '' ? (int)$_GET['featured'] : null;

    $sql = "SELECT * FROM services WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (title LIKE :s1 OR code LIKE :s2 OR summary LIKE :s3)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
    }

    if ($sector === 'co_khi') {
        $sql .= " AND code LIKE 'CK%'";
    } elseif ($sector === 'xay_dung') {
        $sql .= " AND code LIKE 'XD%'";
    }

    if ($featured !== null) {
        $sql .= " AND featured = :feat";
        $params['feat'] = $featured;
    }

    $sql .= " ORDER BY id ASC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $services = $stmt->fetchAll();

    api_response(true, [
        'services' => $services,
        'total' => count($services)
    ], 'Lấy danh sách dịch vụ thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy danh sách dịch vụ: ' . $e->getMessage(), 500);
}
