<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID tin tuyển dụng không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$stmtOld = $db->prepare("SELECT * FROM recruitments WHERE id = :id LIMIT 1");
$stmtOld->execute(['id' => $id]);
$old = $stmtOld->fetch();

if (!$old) {
    api_response(false, null, 'Không tìm thấy tin tuyển dụng.', 404);
}

$title           = trim($input['title'] ?? $old['title']);
$employment_type = trim($input['employment_type'] ?? $old['employment_type']) ?: 'Toàn thời gian';
$salary          = trim($input['salary'] ?? $old['salary']) ?: 'Thỏa thuận';
$location        = trim($input['location'] ?? $old['location']) ?: 'Hà Nội';
$description     = isset($input['description']) ? trim($input['description']) : $old['description'];
$requirements    = isset($input['requirements']) ? trim($input['requirements']) : $old['requirements'];
$quantity        = isset($input['quantity']) ? max(1, (int)$input['quantity']) : (int)$old['quantity'];
$status          = in_array($input['status'] ?? '', ['published', 'draft'], true) ? $input['status'] : $old['status'];

if (empty($title)) {
    api_response(false, null, 'Vui lòng nhập chức danh tuyển dụng.', 400);
}

try {
    $stmt = $db->prepare("
        UPDATE recruitments
        SET title = :title,
            employment_type = :employment_type,
            salary = :salary,
            location = :location,
            description = :description,
            requirements = :requirements,
            quantity = :quantity,
            status = :status
        WHERE id = :id
    ");
    $stmt->execute([
        'title'           => $title,
        'employment_type' => $employment_type,
        'salary'          => $salary,
        'location'        => $location,
        'description'     => $description,
        'requirements'    => $requirements,
        'quantity'        => $quantity,
        'status'          => $status,
        'id'              => $id
    ]);

    api_response(true, [
        'id'              => $id,
        'title'           => $title,
        'employment_type' => $employment_type,
        'salary'          => $salary,
        'location'        => $location,
        'quantity'        => $quantity,
        'status'          => $status
    ], 'Cập nhật tin tuyển dụng thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật tin tuyển dụng: ' . $e->getMessage(), 500);
}
