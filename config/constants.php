<?php
/**
 * Constants & Global Configuration
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

// Load local configuration if exists
$localConfig = [];
$localConfigFile = __DIR__ . '/local.php';
if (file_exists($localConfigFile)) {
    $loaded = require $localConfigFile;
    if (is_array($loaded)) {
        $localConfig = $loaded;
    }
}

// 1. Base URL Configuration
if (!defined('APP_BASE_URL')) {
    if (isset($localConfig['app_base_url'])) {
        $baseUrl = rtrim($localConfig['app_base_url'], '/');
    } elseif (getenv('APP_BASE_URL') !== false) {
        $baseUrl = rtrim(getenv('APP_BASE_URL'), '/');
    } else {
        // Auto-detect base URL from SCRIPT_NAME if in /test/web_cty
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        if (strpos($scriptName, '/test/web_cty') === 0) {
            $baseUrl = '/test/web_cty';
        } else {
            $baseUrl = '';
        }
    }
    define('APP_BASE_URL', $baseUrl);
}

// Backward compatibility alias for ROOT_URL
if (!defined('ROOT_URL')) {
    define('ROOT_URL', APP_BASE_URL);
}

// 2. Base Filesystem Path
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// 3. Upload Directories & URLs
if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', BASE_PATH . '/assets/uploads/');
}
if (!defined('UPLOAD_URL')) {
    define('UPLOAD_URL', ROOT_URL . '/assets/uploads/');
}
if (!defined('PROJECT_UPLOAD_DIR')) {
    define('PROJECT_UPLOAD_DIR', UPLOAD_DIR . 'projects/');
}
if (!defined('SERVICE_UPLOAD_DIR')) {
    define('SERVICE_UPLOAD_DIR', UPLOAD_DIR . 'services/');
}
if (!defined('NEWS_UPLOAD_DIR')) {
    define('NEWS_UPLOAD_DIR', UPLOAD_DIR . 'news/');
}

// 4. Site Defaults
if (!defined('SITE_NAME_DEFAULT')) {
    define('SITE_NAME_DEFAULT', 'Công Ty CP Cơ Khí & Xây Dựng PNMEC');
}
if (!defined('SITE_HOTLINE_DEFAULT')) {
    define('SITE_HOTLINE_DEFAULT', '0988.123.456');
}
if (!defined('SITE_EMAIL_DEFAULT')) {
    define('SITE_EMAIL_DEFAULT', 'contact@pnmec.vn');
}
if (!defined('SITE_ADDRESS_DEFAULT')) {
    define('SITE_ADDRESS_DEFAULT', 'KCN Quang Minh, Mê Linh, Hà Nội');
}

// 5. Environment & Debug
if (!defined('APP_ENV')) {
    define('APP_ENV', $localConfig['app_env'] ?? (getenv('APP_ENV') ?: 'production'));
}
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', (bool)($localConfig['app_debug'] ?? (getenv('APP_DEBUG') === 'true')));
}

// 6. URL Helpers
if (!function_exists('url')) {
    function url($path = '') {
        $path = '/' . ltrim($path, '/');
        return rtrim(APP_BASE_URL, '/') . $path;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        return url('/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('api_url')) {
    function api_url($path = '') {
        return url('/api/' . ltrim($path, '/'));
    }
}
