<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$user = require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();

$title           = trim($input['title'] ?? '');
$category        = trim($input['category'] ?? 'Cơ khí xây dựng');
$client          = trim($input['client'] ?? 'Khách hàng cá nhân / Doanh nghiệp');
$location        = trim($input['location'] ?? 'Hà Nội, Việt Nam');
$completion_date = !empty($input['completion_date']) ? $input['completion_date'] : null;
$description     = trim($input['description'] ?? '');
$content         = trim($input['content'] ?? '');
$status          = in_array($input['status'] ?? '', ['published', 'draft']) ? $input['status'] : 'published';
$gallery         = isset($input['gallery']) ? (is_array($input['gallery']) ? json_encode($input['gallery']) : trim($input['gallery'])) : null;

if (empty($title) || empty($category)) {
    api_response(false, null, 'Vui lòng nhập tiêu đề và hạng mục công trình.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

// Handle image upload if provided
$image_filename = trim($input['image'] ?? 'default-project.jpg');
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_res = api_upload_image('image', 'projects');
    if ($upload_res['status']) {
        $image_filename = $upload_res['filename'];
    } else {
        api_response(false, null, 'Lỗi tải ảnh: ' . $upload_res['message'], 400);
    }
}

$base_slug = create_slug($title);
$slug = $base_slug . '-' . time();

try {
    $stmt = $db->prepare("INSERT INTO projects (title, slug, category, client, location, completion_date, description, content, image, gallery, status, created_by) VALUES (:title, :slug, :category, :client, :location, :completion_date, :description, :content, :image, :gallery, :status, :created_by)");
    $stmt->execute([
        'title'           => $title,
        'slug'            => $slug,
        'category'        => $category,
        'client'          => $client,
        'location'        => $location,
        'completion_date' => $completion_date,
        'description'     => $description,
        'content'         => $content,
        'image'           => $image_filename,
        'gallery'         => $gallery,
        'status'          => $status,
        'created_by'      => $user['id']
    ]);

    $newId = (int)$db->lastInsertId();

    api_response(true, [
        'id' => $newId,
        'slug' => $slug,
        'image' => $image_filename,
    ], 'Tạo công trình mới thành công!', 201);
} catch (Exception $e) {
    api_response(false, null, 'Lỗi tạo công trình: ' . $e->getMessage(), 500);
}
