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

    $sql = "SELECT * FROM quotes WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (fullname LIKE :s1 OR phone LIKE :s2 OR email LIKE :s3 OR service_type LIKE :s4 OR project_location LIKE :s5 OR message LIKE :s6)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
        $params['s4'] = "%$search%";
        $params['s5'] = "%$search%";
        $params['s6'] = "%$search%";
    }

    if ($status !== '') {
        $sql .= " AND status = :st";
        $params['st'] = $status;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $quotes = $stmt->fetchAll();

    api_response(true, [
        'quotes' => $quotes,
        'total' => count($quotes)
    ], 'Lấy danh sách yêu cầu báo giá thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
