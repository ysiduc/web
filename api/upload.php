<?php
require_once __DIR__ . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$subfolder = trim($_POST['folder'] ?? 'general');
$cleanFolder = trim(preg_replace('#[^a-zA-Z0-9_\-/]#', '', $subfolder), '/');
$cleanFolder = str_replace('..', '', $cleanFolder);

$upload = api_upload_image('file', $cleanFolder ?: 'general');

if ($upload['status']) {
    api_response(true, [
        'filename' => $upload['filename'],
        'url'      => $upload['url']
    ], 'Tải lên hình ảnh thành công!');
} else {
    api_response(false, null, $upload['message'], 400);
}
