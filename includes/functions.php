<?php
/**
 * File hàm tiện ích dùng chung cho toàn bộ website
 * web_cty - Công ty CP Cơ khí & Xây dựng
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Làm sạch dữ liệu đầu vào chống XSS
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Lấy thông tin cấu hình trang web từ CSDL `site_info`
 */
function get_site_info($key, $default = '') {
    $db = getDBConnection();
    if (!$db) {
        // Trả về giá trị mặc định nếu chưa khởi tạo database
        $defaults = [
            'site_name' => 'Công ty TNHH THIẾT KẾ & THI CÔNG CƠ KHÍ XÂY DỰNG PNMEC',
            'company_short_name' => 'PNMEC',
            'phone' => '0988.123.456',
            'hotline' => '1900.6868',
            'email' => 'contact@pnmec.vn',
            'address' => 'Khu Công Nghiệp Quang Minh, Mê Linh, Hà Nội',
            'factory_address' => 'Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên',
            'working_hours' => 'Thứ 2 - Thứ 7: 07:30 - 17:30',
            'hero_title' => 'GIẢI PHÁP CƠ KHÍ CHẾ TẠO & THI CÔNG XÂY DỰNG TIÊN TIẾN',
            'hero_subtitle' => 'Đồng hành cùng hàng trăm nhà xưởng, dự án kết cấu thép và công trình công nghiệp quy mô lớn.',
            'about_summary' => 'PNMEC là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công nhà xưởng kết cấu thép.'
        ];
        return $defaults[$key] ?? $default;
    }

    try {
        $stmt = $db->prepare("SELECT info_value FROM site_info WHERE info_key = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();
        return $row ? $row['info_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Lấy tất cả cài đặt dưới dạng mảng key-value
 */
function get_all_site_info() {
    $db = getDBConnection();
    if (!$db) return [];
    try {
        $stmt = $db->query("SELECT info_key, info_value FROM site_info");
        $results = $stmt->fetchAll();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['info_key']] = $row['info_value'];
        }
        return $settings;
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Chuyển chuỗi tiếng Việt thành Slug chuẩn SEO
 */
function create_slug($str) {
    $str = trim(mb_strtolower($str, 'UTF-8'));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', $str);
    return trim($str, '-');
}

/**
 * Hàm upload hình ảnh dự án vào thư mục `assets/uploads/`
 */
function upload_image($file_input, $target_dir = __DIR__ . '/../assets/uploads/') {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Không có tệp nào được tải lên hoặc có lỗi tải tệp.'];
    }

    $file = $_FILES[$file_input];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        return ['status' => false, 'message' => 'Định dạng tệp không hợp lệ (Chỉ chấp nhận JPG, PNG, WEBP, GIF).'];
    }

    if ($file['size'] > $max_size) {
        return ['status' => false, 'message' => 'Kích thước tệp quá lớn (Tối đa 5MB).'];
    }

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . strtolower($extension);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['status' => true, 'filename' => $filename];
    } else {
        return ['status' => false, 'message' => 'Có lỗi xảy ra khi lưu tệp vào máy chủ.'];
    }
}

/**
 * Định dạng ngày tháng năm tiếng Việt (dd/mm/YYYY)
 */
function format_date($date_string) {
    if (!$date_string) return 'N/A';
    $time = strtotime($date_string);
    return date('d/m/Y', $time);
}

/**
 * Thiết lập thông báo ngắn Flash message
 */
function set_flash_message($type, $message) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'danger', 'info', 'warning'
        'text' => $message
    ];
}

/**
 * Lấy và xóa Flash message
 */
function get_flash_message() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

/**
 * Lấy đường dẫn ảnh dịch vụ an toàn (hỗ trợ upload mới, ảnh legacy và fallback)
 */
function get_service_image_url($image) {
    $root = defined('ROOT_URL') ? ROOT_URL : '/test/web_cty';
    $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
    $fallback = $root . '/assets/images/service-cons.png';

    if (empty($image)) {
        return $fallback;
    }

    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '//')) {
        return $image;
    }

    if (str_starts_with($image, '/')) {
        return $image;
    }

    // Check in assets/uploads/
    if (file_exists($basePath . '/assets/uploads/' . $image)) {
        return $root . '/assets/uploads/' . $image;
    }

    // Check in assets/uploads/services/
    if (file_exists($basePath . '/assets/uploads/services/' . $image)) {
        return $root . '/assets/uploads/services/' . $image;
    }

    // Check in assets/images/
    if (file_exists($basePath . '/assets/images/' . $image)) {
        return $root . '/assets/images/' . $image;
    }

    // Default image filename
    if ($image === 'default-service.jpg') {
        return $fallback;
    }

    return $root . '/assets/uploads/' . $image;
}

/**
 * Lấy đường dẫn ảnh công trình an toàn
 */
function get_project_image_url($image) {
    $root = defined('ROOT_URL') ? ROOT_URL : '/test/web_cty';
    $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
    $fallback = 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=1000&q=80';

    if (empty($image)) {
        return $fallback;
    }

    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '//')) {
        return $image;
    }

    if (str_starts_with($image, '/')) {
        return $image;
    }

    // Check in assets/uploads/
    if (file_exists($basePath . '/assets/uploads/' . $image)) {
        return $root . '/assets/uploads/' . $image;
    }

    // Check in assets/uploads/projects/
    if (file_exists($basePath . '/assets/uploads/projects/' . $image)) {
        return $root . '/assets/uploads/projects/' . $image;
    }

    // Check in assets/images/
    if (file_exists($basePath . '/assets/images/' . $image)) {
        return $root . '/assets/images/' . $image;
    }

    if ($image === 'default-project.jpg') {
        return $fallback;
    }

    return $root . '/assets/uploads/' . $image;
}
