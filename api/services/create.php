<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();

$title    = trim($input['title'] ?? '');
$code     = trim($input['code'] ?? '');
$summary  = trim($input['summary'] ?? '');
$content  = trim($input['content'] ?? '');
$featured = !empty($input['featured']) ? 1 : 0;
$status   = in_array($input['status'] ?? '', ['active', 'inactive']) ? $input['status'] : 'active';

if (empty($title)) {
    api_response(false, null, 'Vui lòng nhập tên dịch vụ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$image_filename = trim($input['image'] ?? 'default-service.jpg');
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_res = api_upload_image('image', 'services');
    if ($upload_res['status']) {
        $image_filename = $upload_res['filename'];
    } else {
        api_response(false, null, 'Lỗi tải ảnh: ' . $upload_res['message'], 400);
    }
}

$slug = create_slug($title) . '-' . time();

try {
    $stmt = $db->prepare("INSERT INTO services (title, slug, code, summary, content, image, featured, status) VALUES (:title, :slug, :code, :summary, :content, :image, :featured, :status)");
    $stmt->execute([
        'title'    => $title,
        'slug'     => $slug,
        'code'     => $code ?: null,
        'summary'  => $summary,
        'content'  => $content,
        'image'    => $image_filename,
        'featured' => $featured,
        'status'   => $status
    ]);

    $newId = (int)$db->lastInsertId();

    api_response(true, [
        'id' => $newId,
        'slug' => $slug,
        'image' => $image_filename
    ], 'Tạo dịch vụ mới thành công!', 201);
} catch (Exception $e) {
    api_response(false, null, 'Lỗi tạo dịch vụ: ' . $e->getMessage(), 500);
}
