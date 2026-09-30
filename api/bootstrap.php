<?php
/**
 * API Bootstrap & Common Utilities
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Cookie params to ensure session cookie is accessible in subdirectories
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path'     => '/',
        'domain'   => $cookieParams['domain'],
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// CORS & Response Headers
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load core configurations
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';

/**
 * Standard JSON Response Helper
 */
function api_response($success, $data = null, $message = '', $status_code = 200) {
    http_response_code($status_code);
    $response = [
        'success'   => (bool)$success,
        'message'   => $message,
        'data'      => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Read request payload (supports JSON and form-data)
 */
function get_api_input() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : [];
    }
    return $_POST;
}

/**
 * Check logged-in user for API
 */
function require_api_login() {
    if (!is_logged_in()) {
        api_response(false, null, 'Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn.', 401);
    }
    return get_logged_user();
}

/**
 * Check admin role for API
 */
function require_api_admin() {
    $user = require_api_login();
    if (!is_admin()) {
        api_response(false, null, 'Bạn không có quyền thực hiện thao tác này (yêu cầu Admin).', 403);
    }
    return $user;
}

/**
 * Helper to upload image safely
 */
function api_upload_image($file_key, $subfolder = '') {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Không có tệp nào được gửi lên.'];
    }

    $file = $_FILES[$file_key];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $max_size = 8 * 1024 * 1024; // 8MB

    if ($file['size'] > $max_size) {
        return ['status' => false, 'message' => 'Dung lượng ảnh vượt quá giới hạn (tối đa 8MB).'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions)) {
        return ['status' => false, 'message' => 'Định dạng file không hợp lệ (chỉ chấp nhận JPG, PNG, WEBP, GIF).'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types)) {
        return ['status' => false, 'message' => 'MIME type không được phép: ' . $mime];
    }

    $targetDir = rtrim(UPLOAD_DIR, '/') . '/' . ($subfolder ? trim($subfolder, '/') . '/' : '');
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $relativePath = ($subfolder ? trim($subfolder, '/') . '/' : '') . $filename;
        return [
            'status' => true,
            'filename' => $relativePath,
            'url' => UPLOAD_URL . $relativePath
        ];
    }

    return ['status' => false, 'message' => 'Không thể lưu file trên máy chủ.'];
}
