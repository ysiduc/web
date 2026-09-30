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
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_res = api_upload_image('image', 'services');
    if ($upload_res['status']) {
        if (!empty($oldService['image']) && !str_starts_with($oldService['image'], 'default-') && !str_starts_with($oldService['image'], 'service-')) {
            $oldPath = UPLOAD_DIR . $oldService['image'];
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
