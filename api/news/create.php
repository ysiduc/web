<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$user = require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();

$title   = trim($input['title'] ?? '');
$author  = trim($input['author'] ?? $user['fullname'] ?: 'PNMEC');
$summary = trim($input['summary'] ?? '');
$content = trim($input['content'] ?? '');
$status  = in_array($input['status'] ?? '', ['published', 'draft']) ? $input['status'] : 'published';

if (empty($title)) {
    api_response(false, null, 'Vui lòng nhập tiêu đề bài viết.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

// Xử lý tải ảnh tin tức
$image_filename = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploadError = (int)$_FILES['image']['error'];
    if ($uploadError !== UPLOAD_ERR_OK) {
        api_response(false, null, 'Lỗi tải ảnh: ' . get_upload_error_message($uploadError), 400);
    }

    $upload_res = api_upload_image('image', 'news');
    if (!$upload_res['status']) {
        api_response(false, null, 'Lỗi tải ảnh: ' . $upload_res['message'], 400);
    }

    $image_filename = $upload_res['filename'];
    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__, 2) . '/assets/uploads/');
    $targetFilePath = rtrim($baseUploadDir, '/') . '/' . $image_filename;
    if (!file_exists($targetFilePath)) {
        api_response(false, null, 'Không tìm thấy tệp ảnh vừa tải lên trên máy chủ lưu trữ.', 500);
    }
} elseif (isset($input['image']) && is_string($input['image'])) {
    $providedImage = trim($input['image']);
    if ($providedImage !== '' && !in_array($providedImage, ['default-news.jpg', 'default-service.jpg', 'default-project.jpg'], true)) {
        $image_filename = $providedImage;
    }
}

$slug = create_slug($title) . '-' . time();

try {
    $stmt = $db->prepare("INSERT INTO news (title, slug, summary, content, image, author, status) VALUES (:title, :slug, :summary, :content, :image, :author, :status)");
    $stmt->execute([
        'title'   => $title,
        'slug'    => $slug,
        'summary' => $summary,
        'content' => $content,
        'image'   => $image_filename,
        'author'  => $author,
        'status'  => $status
    ]);

    $newId = (int)$db->lastInsertId();

    api_response(true, [
        'id' => $newId,
        'slug' => $slug,
        'image' => $image_filename
    ], 'Tạo bài viết mới thành công!', 201);
} catch (Exception $e) {
    api_response(false, null, 'Lỗi tạo bài viết: ' . $e->getMessage(), 500);
}
