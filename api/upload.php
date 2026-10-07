<?php
require_once __DIR__ . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

// Rate limiting for upload (30 uploads / 10 minutes)
enforce_rate_limit_api('upload', 30, 600);

$subfolder = trim($_POST['folder'] ?? 'general');
$cleanFolder = trim(preg_replace('#[^a-zA-Z0-9_\-/]#', '', $subfolder), '/');
$cleanFolder = str_replace('..', '', $cleanFolder);

$upload = api_upload_image('file', $cleanFolder ?: 'general');

if ($upload['status']) {
    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__) . '/assets/uploads/');
    $targetFilePath = rtrim($baseUploadDir, '/') . '/' . $upload['filename'];
    if (!file_exists($targetFilePath)) {
        api_response(false, null, 'Không tìm thấy tệp ảnh vừa tải lên trên máy chủ lưu trữ.', 500);
    }

    api_response(true, [
        'filename' => $upload['filename'],
        'url'      => $upload['url']
    ], 'Tải lên hình ảnh thành công!');
} else {
    api_response(false, null, $upload['message'], 400);
}
