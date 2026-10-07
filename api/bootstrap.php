<?php
/**
 * API Bootstrap & Common Utilities
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

// Load core configurations first
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/rate_limiter.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
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

// Security Response Headers
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

// CORS: Whitelist-only origin checking (never reflect arbitrary Origin with credentials)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
];

// Check local config for custom allowed origins
$localConfigFile = dirname(__DIR__) . '/config/local.php';
if (file_exists($localConfigFile)) {
    $localConfig = require $localConfigFile;
    if (is_array($localConfig) && !empty($localConfig['cors_allowed_origins']) && is_array($localConfig['cors_allowed_origins'])) {
        $allowedOrigins = array_merge($allowedOrigins, $localConfig['cors_allowed_origins']);
    }
}

if ($origin && in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-Token");
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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
 * CSRF Token Helpers
 */
function get_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(): bool {
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    // Only check mutating methods
    if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? '';
        if (empty($token)) {
            $input = get_api_input();
            $token = $input['csrf_token'] ?? '';
        }

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (empty($sessionToken) || empty($token) || !hash_equals($sessionToken, $token)) {
            api_response(false, null, 'CSRF token không hợp lệ hoặc đã hết hạn.', 403);
            return false;
        }
    }
    return true;
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
 * Check logged-in user for API and enforce CSRF on mutations
 */
function require_api_login() {
    if (!is_logged_in()) {
        api_response(false, null, 'Bạn chưa đăng nhập hoặc phiên làm việc đã hết hạn.', 401);
    }

    // Enforce CSRF protection for authenticated mutations
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        verify_csrf_token();
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
 * Upload helper using unified secure implementation
 */
function api_upload_image($file_key, $subfolder = '') {
    return secure_upload_image($file_key, $subfolder, 8);
}
