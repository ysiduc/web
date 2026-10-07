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
 * Hàm upload hình ảnh an toàn dùng chung cho toàn bộ hệ thống
 */
function secure_upload_image($file_input, $subfolder = '', $max_size_mb = 8) {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Không có tệp nào được tải lên hoặc có lỗi tải tệp.'];
    }

    $file = $_FILES[$file_input];
    $max_bytes = $max_size_mb * 1024 * 1024;

    if ($file['size'] > $max_bytes) {
        return ['status' => false, 'message' => 'Dung lượng ảnh vượt quá giới hạn (tối đa ' . $max_size_mb . 'MB).'];
    }

    $originalName = $file['name'];
    // Ngăn chặn double extension độc hại (vd: file.php.jpg)
    if (preg_match('/\.(php|phtml|phar|cgi|pl|py|sh|bash|html|js|exe)\./i', $originalName)) {
        return ['status' => false, 'message' => 'Tên tệp chứa phần mở rộng không an toàn.'];
    }

    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowed_extensions, true)) {
        return ['status' => false, 'message' => 'Định dạng file không hợp lệ (chỉ chấp nhận JPG, PNG, WEBP, GIF).'];
    }

    // Kiểm tra MIME thực sự bằng finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime, $allowed_mimes, true)) {
        return ['status' => false, 'message' => 'MIME type không được phép: ' . $mime];
    }

    // Kiểm tra cấu trúc hình ảnh hợp lệ
    if (!@getimagesize($file['tmp_name'])) {
        return ['status' => false, 'message' => 'Tệp tải lên không phải là ảnh hợp lệ.'];
    }

    $cleanSubfolder = trim(preg_replace('#[^a-zA-Z0-9_\-/]#', '', $subfolder), '/');
    $cleanSubfolder = str_replace('..', '', $cleanSubfolder);

    $baseUploadDir = defined('UPLOAD_DIR') ? UPLOAD_DIR : (dirname(__DIR__) . '/assets/uploads/');
    $targetDir = rtrim($baseUploadDir, '/') . '/' . ($cleanSubfolder ? $cleanSubfolder . '/' : '');

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Sinh tên file ngẫu nhiên bảo mật (không dùng tên gốc)
    $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        @chmod($targetFile, 0644);
        $relativePath = ($cleanSubfolder ? $cleanSubfolder . '/' : '') . $filename;
        $uploadUrl = defined('UPLOAD_URL') ? UPLOAD_URL : (defined('ROOT_URL') ? ROOT_URL . '/assets/uploads/' : '/assets/uploads/');
        return [
            'status'   => true,
            'filename' => $relativePath,
            'url'      => rtrim($uploadUrl, '/') . '/' . $relativePath,
        ];
    }

    return ['status' => false, 'message' => 'Có lỗi xảy ra khi lưu tệp vào máy chủ.'];
}

/**
 * Hàm upload hình ảnh legacy (wrapper cho secure_upload_image)
 */
function upload_image($file_input, $target_dir = null) {
    $subfolder = '';
    if ($target_dir && strpos($target_dir, 'projects') !== false) {
        $subfolder = 'projects';
    } elseif ($target_dir && strpos($target_dir, 'services') !== false) {
        $subfolder = 'services';
    } elseif ($target_dir && strpos($target_dir, 'news') !== false) {
        $subfolder = 'news';
    }
    return secure_upload_image($file_input, $subfolder, 8);
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
    $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
    $fallback = asset_url('images/service-cons.png');

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
        return asset_url('uploads/' . $image);
    }

    // Check in assets/uploads/services/
    if (file_exists($basePath . '/assets/uploads/services/' . $image)) {
        return asset_url('uploads/services/' . $image);
    }

    // Check in assets/images/
    if (file_exists($basePath . '/assets/images/' . $image)) {
        return asset_url('images/' . $image);
    }

    // Default image filename
    if ($image === 'default-service.jpg') {
        return $fallback;
    }

    return asset_url('uploads/' . $image);
}

/**
 * Lấy đường dẫn ảnh công trình an toàn
 */
function get_project_image_url($image) {
    $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
    $fallback = asset_url('images/service-cons.png');

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
        return asset_url('uploads/' . $image);
    }

    // Check in assets/uploads/projects/
    if (file_exists($basePath . '/assets/uploads/projects/' . $image)) {
        return asset_url('uploads/projects/' . $image);
    }

    // Check in assets/images/
    if (file_exists($basePath . '/assets/images/' . $image)) {
        return asset_url('images/' . $image);
    }

    if ($image === 'default-project.jpg') {
        return $fallback;
    }

    return asset_url('uploads/' . $image);
}

/**
 * Làm sạch mã HTML tùy chỉnh bằng DOMDocument allow-list
 * Ngăn chặn XSS, thẻ độc hại (script, iframe, on*, javascript:...)
 */
function sanitize_html_content(string $html): string {
    if (trim($html) === '') {
        return '';
    }

    $allowedTags = [
        'section', 'div', 'p', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li', 'strong', 'b', 'em', 'i', 'u', 'a',
        'table', 'thead', 'tbody', 'tr', 'th', 'td', 'blockquote',
        'br', 'hr', 'img', 'figure', 'figcaption', 'code', 'pre'
    ];

    $allowedAttrs = [
        'class', 'id', 'href', 'title', 'target', 'rel', 'src', 'alt', 'width', 'height', 'style'
    ];

    libxml_use_internal_errors(true);
    $dom = new DOMDocument('1.0', 'UTF-8');
    // Bọc thẻ html/body và mã hóa UTF-8 để không bị lỗi tiếng Việt
    $wrapped = '<?xml encoding="utf-8" ?><html><body>' . $html . '</body></html>';
    $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//*');

    $nodesToRemove = [];

    foreach ($nodes as $node) {
        if (!($node instanceof DOMElement)) continue;
        $tagName = strtolower($node->nodeName);
        if ($tagName === 'html' || $tagName === 'body') continue;

        if (!in_array($tagName, $allowedTags, true)) {
            $nodesToRemove[] = $node;
            continue;
        }

        // Kiểm tra thuộc tính
        if ($node->hasAttributes()) {
            $attrsToRemove = [];
            foreach ($node->attributes as $attr) {
                $attrName = strtolower($attr->name);

                // Loại bỏ mọi thuộc tính event bắt đầu bằng on* (onclick, onerror, onload...)
                if (str_starts_with($attrName, 'on') || !in_array($attrName, $allowedAttrs, true)) {
                    $attrsToRemove[] = $attr->name;
                    continue;
                }

                // Kiểm tra liên kết href và src
                if ($attrName === 'href' || $attrName === 'src') {
                    $val = trim(strtolower($attr->value));
                    $val = preg_replace('/[\x00-\x1f\x7f]/', '', $val);
                    if (str_starts_with($val, 'javascript:') || str_starts_with($val, 'vbscript:') || str_starts_with($val, 'data:')) {
                        $attrsToRemove[] = $attr->name;
                        continue;
                    }
                }

                // Kiểm tra inline style
                if ($attrName === 'style') {
                    $styleVal = strtolower($attr->value);
                    if (strpos($styleVal, 'expression') !== false ||
                        strpos($styleVal, 'javascript') !== false ||
                        strpos($styleVal, 'behavior') !== false ||
                        strpos($styleVal, '-moz-binding') !== false) {
                        $attrsToRemove[] = $attr->name;
                        continue;
                    }
                }
            }

            foreach ($attrsToRemove as $attrName) {
                $node->removeAttribute($attrName);
            }
        }

        // Tự động thêm rel="noopener noreferrer" cho target="_blank"
        if ($tagName === 'a' && strtolower($node->getAttribute('target')) === '_blank') {
            $node->setAttribute('rel', 'noopener noreferrer');
        }
    }

    foreach ($nodesToRemove as $node) {
        if ($node->parentNode) {
            $node->parentNode->removeChild($node);
        }
    }

    $body = $dom->getElementsByTagName('body')->item(0);
    if (!$body) {
        return '';
    }

    $cleanHtml = '';
    foreach ($body->childNodes as $child) {
        $cleanHtml .= $dom->saveHTML($child);
    }

    return trim($cleanHtml);
}

if (!function_exists('versioned_asset_url')) {
    function versioned_asset_url($path = '') {
        $cleanPath = explode('?', $path)[0];
        $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $file = $base . '/assets/' . ltrim($cleanPath, '/');
        $url = function_exists('asset_url') ? asset_url($cleanPath) : ('/assets/' . ltrim($cleanPath, '/'));
        if (is_file($file)) {
            return $url . '?v=' . filemtime($file);
        }
        return function_exists('asset_url') ? asset_url($path) : ('/assets/' . ltrim($path, '/'));
    }
}

