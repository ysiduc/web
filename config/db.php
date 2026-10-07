<?php
/**
 * Database Connection (PDO/MySQL)
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

require_once __DIR__ . '/constants.php';

// Load config from local.php if available
$localConfig = [];
$localConfigFile = __DIR__ . '/local.php';
if (file_exists($localConfigFile)) {
    $loaded = require $localConfigFile;
    if (is_array($loaded)) {
        $localConfig = $loaded;
    }
}

if (!defined('DB_HOST')) {
    define('DB_HOST', $localConfig['db_host'] ?? getenv('DB_HOST') ?: 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', $localConfig['db_user'] ?? getenv('DB_USER') ?: 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', $localConfig['db_pass'] ?? (getenv('DB_PASS') !== false ? getenv('DB_PASS') : ''));
}
if (!defined('DB_NAME')) {
    define('DB_NAME', $localConfig['db_name'] ?? getenv('DB_NAME') ?: 'web_cty');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', (int)($localConfig['db_port'] ?? getenv('DB_PORT') ?: 3306));
}

function getDBConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return false;
    }
}
