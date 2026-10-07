<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID bài viết không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$stmtOld = $db->prepare("SELECT * FROM news WHERE id = :id LIMIT 1");
$stmtOld->execute(['id' => $id]);
$oldNews = $stmtOld->fetch();

if (!$oldNews) {
    api_response(false, null, 'Không tìm thấy bài viết.', 404);
}

$title   = trim($input['title'] ?? $oldNews['title']);
$author  = trim($input['author'] ?? $oldNews['author']);
$summary = trim($input['summary'] ?? $oldNews['summary']);
$content = trim($input['content'] ?? $oldNews['content']);
$status  = in_array($input['status'] ?? '', ['published', 'draft']) ? $input['status'] : ($oldNews['status'] ?? 'published');

$image_filename = $oldNews['image'];
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploadError = (int)$_FILES['image']['error'];
    if ($uploadError !== UPLOAD_ERR_OK) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . get_upload_error_message($uploadError), 400);
    }

    $upload_res = api_upload_image('image', 'news');
    if (!$upload_res['status']) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . $upload_res['message'], 400);
    }

    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__, 2) . '/assets/uploads/');
    $targetFilePath = rtrim($baseUploadDir, '/') . '/' . $upload_res['filename'];
    if (!file_exists($targetFilePath)) {
        api_response(false, null, 'Không tìm thấy tệp ảnh vừa tải lên trên máy chủ lưu trữ.', 500);
    }

    if (!empty($oldNews['image']) && !str_starts_with($oldNews['image'], 'default-') && !str_starts_with($oldNews['image'], 'http') && !str_starts_with($oldNews['image'], '/')) {
        $oldPath = rtrim($baseUploadDir, '/') . '/' . $oldNews['image'];
        if (file_exists($oldPath) && is_file($oldPath)) {
            @unlink($oldPath);
        }
    }
    $image_filename = $upload_res['filename'];
} elseif (array_key_exists('image', $input)) {
    $inputImg = trim((string)$input['image']);
    if (in_array($inputImg, ['default-news.jpg', 'default-service.jpg', 'default-project.jpg'], true)) {
        $image_filename = '';
    } else {
        $image_filename = $inputImg;
    }
}

$slug = $oldNews['slug'];
if (!empty($input['slug'])) {
    $slug = create_slug($input['slug']);
}

try {
    $stmt = $db->prepare("UPDATE news SET title = :title, slug = :slug, summary = :summary, content = :content, image = :image, author = :author, status = :status WHERE id = :id");
    $stmt->execute([
        'title'   => $title,
        'slug'    => $slug,
        'summary' => $summary,
        'content' => $content,
        'image'   => $image_filename,
        'author'  => $author,
        'status'  => $status,
        'id'      => $id
    ]);

    api_response(true, [
        'id' => $id,
        'slug' => $slug,
        'image' => $image_filename
    ], 'Cập nhật bài viết thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật bài viết: ' . $e->getMessage(), 500);
}
