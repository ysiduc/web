<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $settings = get_all_site_info();
    $stmt = $db->query("SELECT info_key, info_value FROM site_info ORDER BY id ASC");
    $rows = $stmt->fetchAll();

    api_response(true, [
        'settings' => $settings,
        'raw' => $rows
    ], 'Lấy cài đặt cấu hình thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy cài đặt: ' . $e->getMessage(), 500);
}
