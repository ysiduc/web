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
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_res = api_upload_image('image', 'news');
    if ($upload_res['status']) {
        if (!empty($oldNews['image']) && !str_starts_with($oldNews['image'], 'default-')) {
            $oldPath = UPLOAD_DIR . $oldNews['image'];
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
