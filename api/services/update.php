<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID dịch vụ không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

$stmtOld = $db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
$stmtOld->execute(['id' => $id]);
$oldService = $stmtOld->fetch();

if (!$oldService) {
    api_response(false, null, 'Không tìm thấy dịch vụ.', 404);
}

$title    = trim($input['title'] ?? $oldService['title']);
$code     = trim($input['code'] ?? $oldService['code']);
$summary  = trim($input['summary'] ?? $oldService['summary']);
$content  = trim($input['content'] ?? $oldService['content']);
$featured = isset($input['featured']) ? (!empty($input['featured']) ? 1 : 0) : $oldService['featured'];
$status   = in_array($input['status'] ?? '', ['active', 'inactive']) ? $input['status'] : ($oldService['status'] ?? 'active');

$image_filename = $oldService['image'];

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $uploadError = (int)$_FILES['image']['error'];
    if ($uploadError !== UPLOAD_ERR_OK) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . get_upload_error_message($uploadError), 400);
    }

    $upload_res = api_upload_image('image', 'services');
    if (!$upload_res['status']) {
        api_response(false, null, 'Lỗi tải ảnh mới: ' . $upload_res['message'], 400);
    }

    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__, 2) . '/assets/uploads/');
    $targetFilePath = rtrim($baseUploadDir, '/') . '/' . $upload_res['filename'];
    if (!file_exists($targetFilePath)) {
        api_response(false, null, 'Không tìm thấy tệp ảnh vừa tải lên trên máy chủ lưu trữ.', 500);
    }

    if (!empty($oldService['image']) && !str_starts_with($oldService['image'], 'default-') && !str_starts_with($oldService['image'], 'service-') && !str_starts_with($oldService['image'], 'http') && !str_starts_with($oldService['image'], '/')) {
        $oldPath = rtrim($baseUploadDir, '/') . '/' . $oldService['image'];
        if (file_exists($oldPath) && is_file($oldPath)) {
            @unlink($oldPath);
        }
    }
    $image_filename = $upload_res['filename'];
} elseif (array_key_exists('image', $input)) {
    $inputImg = trim((string)$input['image']);
    if (in_array($inputImg, ['default-service.jpg', 'default-project.jpg', 'default-news.jpg'], true)) {
        $image_filename = '';
    } else {
        $image_filename = $inputImg;
    }
}

$slug = $oldService['slug'];
if (!empty($input['slug'])) {
    $slug = create_slug($input['slug']);
}

try {
    $stmt = $db->prepare("UPDATE services SET title = :title, slug = :slug, code = :code, summary = :summary, content = :content, image = :image, featured = :featured, status = :status WHERE id = :id");
    $stmt->execute([
        'title'    => $title,
        'slug'     => $slug,
        'code'     => $code ?: null,
        'summary'  => $summary,
        'content'  => $content,
        'image'    => $image_filename,
        'featured' => $featured,
        'status'   => $status,
        'id'       => $id
    ]);

    api_response(true, [
        'id' => $id,
        'slug' => $slug,
        'image' => $image_filename
    ], 'Cập nhật dịch vụ thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật dịch vụ: ' . $e->getMessage(), 500);
}
