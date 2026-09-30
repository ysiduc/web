<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$user = require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID công trình không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

// Fetch existing project
$stmtOld = $db->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
$stmtOld->execute(['id' => $id]);
$oldProject = $stmtOld->fetch();

if (!$oldProject) {
    api_response(false, null, 'Không tìm thấy công trình để cập nhật.', 404);
}

$title           = trim($input['title'] ?? $oldProject['title']);
$category        = trim($input['category'] ?? $oldProject['category']);
$client          = trim($input['client'] ?? $oldProject['client']);
$location        = trim($input['location'] ?? $oldProject['location']);
$completion_date = !empty($input['completion_date']) ? $input['completion_date'] : $oldProject['completion_date'];
$description     = trim($input['description'] ?? $oldProject['description']);
$content         = trim($input['content'] ?? $oldProject['content']);
$status          = in_array($input['status'] ?? '', ['published', 'draft']) ? $input['status'] : $oldProject['status'];
$gallery         = isset($input['gallery']) ? (is_array($input['gallery']) ? json_encode($input['gallery']) : trim($input['gallery'])) : $oldProject['gallery'];

// Handle image replacement if uploaded
$image_filename = $oldProject['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_res = api_upload_image('image', 'projects');
    if ($upload_res['status']) {
        // Optionally remove old image if not default
        if (!empty($oldProject['image']) && !str_starts_with($oldProject['image'], 'default-') && !str_contains($oldProject['image'], 'home-')) {
            $oldPath = UPLOAD_DIR . $oldProject['image'];
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
        $image_filename = $upload_res['filename'];
    } else {
        api_response(false, null, 'Lỗi tải ảnh: ' . $upload_res['message'], 400);
    }
} elseif (isset($input['image']) && !empty($input['image'])) {
    $image_filename = trim($input['image']);
}

// Update slug if title changed significantly and requested
$slug = $oldProject['slug'];
if (!empty($input['slug'])) {
    $slug = create_slug($input['slug']);
}

try {
    $stmt = $db->prepare("UPDATE projects SET title = :title, slug = :slug, category = :category, client = :client, location = :location, completion_date = :completion_date, description = :description, content = :content, image = :image, gallery = :gallery, status = :status WHERE id = :id");
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
        'id'              => $id
    ]);

    api_response(true, [
        'id' => $id,
        'slug' => $slug,
        'image' => $image_filename,
    ], 'Cập nhật công trình thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật công trình: ' . $e->getMessage(), 500);
}
