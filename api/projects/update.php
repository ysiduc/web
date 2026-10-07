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
$start_date      = array_key_exists('start_date', $input) ? (!empty($input['start_date']) ? $input['start_date'] : null) : $oldProject['start_date'];
$completion_date = array_key_exists('completion_date', $input) ? (!empty($input['completion_date']) ? $input['completion_date'] : null) : $oldProject['completion_date'];
$description     = trim($input['description'] ?? $oldProject['description']);
$content         = trim($input['content'] ?? $oldProject['content']);
$status          = in_array($input['status'] ?? '', ['published', 'draft']) ? $input['status'] : $oldProject['status'];
$gallery         = isset($input['gallery']) ? (is_array($input['gallery']) ? json_encode($input['gallery']) : trim($input['gallery'])) : $oldProject['gallery'];

// Handle image replacement if uploaded
$image_filename = $oldProject['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploadError = (int)$_FILES['image']['error'];
    if ($uploadError !== UPLOAD_ERR_OK) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . get_upload_error_message($uploadError), 400);
    }

    $upload_res = api_upload_image('image', 'projects');
    if (!$upload_res['status']) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . $upload_res['message'], 400);
    }

    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__, 2) . '/assets/uploads/');
    $targetFilePath = rtrim($baseUploadDir, '/') . '/' . $upload_res['filename'];
    if (!file_exists($targetFilePath)) {
        api_response(false, null, 'Không tìm thấy tệp ảnh vừa tải lên trên máy chủ lưu trữ.', 500);
    }

    // Optionally remove old image if not default or static
    if (!empty($oldProject['image']) && !str_starts_with($oldProject['image'], 'default-') && !str_contains($oldProject['image'], 'home-') && !str_starts_with($oldProject['image'], 'http') && !str_starts_with($oldProject['image'], '/')) {
        $oldPath = rtrim($baseUploadDir, '/') . '/' . $oldProject['image'];
        if (file_exists($oldPath) && is_file($oldPath)) {
            @unlink($oldPath);
        }
    }
    $image_filename = $upload_res['filename'];
} elseif (array_key_exists('image', $input)) {
    $inputImg = trim((string)$input['image']);
    if (in_array($inputImg, ['default-project.jpg', 'default-service.jpg', 'default-news.jpg'], true)) {
        $image_filename = '';
    } else {
        $image_filename = $inputImg;
    }
}

// Update slug if title changed significantly and requested
$slug = $oldProject['slug'];
if (!empty($input['slug'])) {
    $slug = create_slug($input['slug']);
}

try {
    $stmt = $db->prepare("UPDATE projects SET title = :title, slug = :slug, category = :category, client = :client, location = :location, start_date = :start_date, completion_date = :completion_date, description = :description, content = :content, image = :image, gallery = :gallery, status = :status WHERE id = :id");
    $stmt->execute([
        'title'           => $title,
        'slug'            => $slug,
        'category'        => $category,
        'client'          => $client,
        'location'        => $location,
        'start_date'      => $start_date,
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
