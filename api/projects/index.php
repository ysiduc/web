<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $search   = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? '');
    $status   = trim($_GET['status'] ?? '');
    $sector   = trim($_GET['sector'] ?? ''); // 'co_khi' or 'xay_dung'

    $co_khi_cats = [
        'Nhà kết cấu thép', 'Cầu thang - Ban công', 'Mái tôn - Mái che',
        'Nhà cơi nới - Gác lửng', 'Thang thoát hiểm', 'Nhà xe - Mái che',
        'Mái kính', 'Sắt mỹ thuật', 'Cửa các loại', 'Cơ khí chế tạo', 'Kết cấu thép', 'Cơ khí xây dựng'
    ];

    $sql = "SELECT p.*, u.fullname as author_name FROM projects p LEFT JOIN users u ON p.created_by = u.id WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (p.title LIKE :s1 OR p.client LIKE :s2 OR p.location LIKE :s3 OR p.category LIKE :s4)";
        $params['s1'] = "%$search%";
        $params['s2'] = "%$search%";
        $params['s3'] = "%$search%";
        $params['s4'] = "%$search%";
    }

    if ($category !== '') {
        $sql .= " AND p.category = :category";
        $params['category'] = $category;
    }

    if ($status !== '') {
        $sql .= " AND p.status = :status";
        $params['status'] = $status;
    }

    if ($sector === 'co_khi') {
        $in = [];
        foreach ($co_khi_cats as $idx => $c) {
            $k = ":ck_$idx";
            $in[] = $k;
            $params[$k] = $c;
        }
        $sql .= " AND p.category IN (" . implode(',', $in) . ")";
    } elseif ($sector === 'xay_dung') {
        $in = [];
        foreach ($co_khi_cats as $idx => $c) {
            $k = ":not_ck_$idx";
            $in[] = $k;
            $params[$k] = $c;
        }
        $sql .= " AND p.category NOT IN (" . implode(',', $in) . ")";
    }

    $sql .= " ORDER BY p.id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();

    api_response(true, [
        'projects' => $projects,
        'total' => count($projects)
    ], 'Lấy danh sách công trình thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy danh sách công trình: ' . $e->getMessage(), 500);
}
