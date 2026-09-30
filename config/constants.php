<?php
/**
 * Constants & Global Configuration
 * PNMEC - Công Ty CP Cơ Khí & Xây Dựng
 */

// Base URL & Path Configuration
define('ROOT_URL', '/test/web_cty');
define('BASE_PATH', dirname(__DIR__));

// Upload Paths
define('UPLOAD_DIR', BASE_PATH . '/assets/uploads/');
define('UPLOAD_URL', ROOT_URL . '/assets/uploads/');
define('PROJECT_UPLOAD_DIR', UPLOAD_DIR . 'projects/');
define('SERVICE_UPLOAD_DIR', UPLOAD_DIR . 'services/');
define('NEWS_UPLOAD_DIR', UPLOAD_DIR . 'news/');

// Site Information Defaults
define('SITE_NAME_DEFAULT', 'Công Ty CP Cơ Khí & Xây Dựng PNMEC');
define('SITE_HOTLINE_DEFAULT', '0988.123.456');
define('SITE_EMAIL_DEFAULT', 'contact@pnmec.vn');
define('SITE_ADDRESS_DEFAULT', 'KCN Quang Minh, Mê Linh, Hà Nội');

// Mailer Configuration (Symptom/Fallback setup)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'contact@pnmec.vn');
define('MAIL_PASS', '');
