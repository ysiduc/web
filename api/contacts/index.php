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

    $sql = "SELECT * FROM contacts WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (name LIKE :s1 OR email LIKE :s2 OR phone LIKE :s3 OR subject LIKE :s4 OR message LIKE :s5)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
        $params['s4'] = "%$search%";
        $params['s5'] = "%$search%";
    }

    if ($status !== '') {
        $sql .= " AND status = :st";
        $params['st'] = $status;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $contacts = $stmt->fetchAll();

    api_response(true, [
        'contacts' => $contacts,
        'total' => count($contacts)
    ], 'Lấy danh sách liên hệ thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi: ' . $e->getMessage(), 500);
}
