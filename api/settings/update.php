<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$settings = $input['settings'] ?? $input;

if (!is_array($settings)) {
    api_response(false, null, 'Dữ liệu cài đặt không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $stmt = $db->prepare("INSERT INTO site_info (info_key, info_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE info_value = VALUES(info_value)");
    
    $allowed_keys = [
        'site_name', 'company_short_name', 'phone', 'hotline',
        'email', 'address', 'factory_address', 'working_hours',
        'hero_title', 'hero_subtitle', 'about_summary',
        'facebook_url', 'youtube_url', 'zalo_url', 'logo_url'
    ];

    foreach ($settings as $key => $val) {
        if (in_array($key, $allowed_keys)) {
            $stmt->execute(['k' => $key, 'v' => trim((string)$val)]);
        }
    }

    api_response(true, $settings, 'Cập nhật cấu hình website thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi cập nhật cấu hình: ' . $e->getMessage(), 500);
}
