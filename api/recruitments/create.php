<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();

$title           = trim($input['title'] ?? '');
$employment_type = trim($input['employment_type'] ?? '') ?: 'Toàn thời gian';
$salary          = trim($input['salary'] ?? '') ?: 'Thỏa thuận';
$location        = trim($input['location'] ?? '') ?: 'Hà Nội';
$description     = trim($input['description'] ?? '');
$requirements    = trim($input['requirements'] ?? '');
$quantity        = isset($input['quantity']) ? (int)$input['quantity'] : 1;
if ($quantity <= 0) {
    $quantity = 1;
}
$status          = in_array($input['status'] ?? '', ['published', 'draft'], true) ? $input['status'] : 'published';

if (empty($title)) {
    api_response(false, null, 'Vui lòng nhập chức danh tuyển dụng.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("
        INSERT INTO recruitments (title, employment_type, salary, location, description, requirements, quantity, status)
        VALUES (:title, :employment_type, :salary, :location, :description, :requirements, :quantity, :status)
    ");
    $stmt->execute([
        'title'           => $title,
        'employment_type' => $employment_type,
        'salary'          => $salary,
        'location'        => $location,
        'description'     => $description,
        'requirements'    => $requirements,
        'quantity'        => $quantity,
        'status'          => $status
    ]);

    $newId = (int)$db->lastInsertId();

    api_response(true, [
        'id'              => $newId,
        'title'           => $title,
        'employment_type' => $employment_type,
        'salary'          => $salary,
        'location'        => $location,
        'quantity'        => $quantity,
        'status'          => $status
    ], 'Tạo tin tuyển dụng mới thành công!', 201);
} catch (Exception $e) {
    api_response(false, null, 'Lỗi tạo tin tuyển dụng: ' . $e->getMessage(), 500);
}
