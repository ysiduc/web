<?php
/**
 * File xử lý xác thực đăng nhập & phân quyền người dùng
 * web_cty - Công ty CP Cơ kh & Xây dựng
 */

require_once __DIR__ . '/../config/constants.php';

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

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Lấy thông tin tài khoản đang đăng nhập từ Session
 */
function get_logged_user() {
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? '',
        'fullname' => $_SESSION['fullname'] ?? '',
        'email'    => $_SESSION['email'] ?? '',
        'role'     => $_SESSION['role'] ?? 'staff',
    ];
}

/**
 * Kiểm tra quyền Admin
 */
function is_admin() {
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Bắt buộc phải đăng nhập mới được xem trang, nếu chưa thì chuyển về login.php
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . url('/admin/login.php?error=unauthorized'));
        exit;
    }
}

/**
 * Bắt buộc phải có quyền Admin, nếu là Staff sẽ bị từ chối truy cập
 */
function require_admin() {
    require_login();
    if (!is_admin()) {
        header("Location: " . url('/admin/index.php?error=forbidden'));
        exit;
    }
}

/**
 * Xử lý đăng nhập tài khoản quản trị
 */
function login_user($username, $password) {
    require_once __DIR__ . '/../config/database.php';
    $db = getDBConnection();
    if (!$db) {
        return ['status' => false, 'message' => 'Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra cấu hình.'];
    }

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['status' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.'];
        }

        if ($user['status'] !== 'active') {
            return ['status' => false, 'message' => 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt.'];
        }

        // Kiểm tra mật khẩu mã hóa an toàn bằng password_verify
        if (password_verify($password, $user['password'])) {
            // Tái tạo Session ID chống Session Fixation
            session_regenerate_id(true);

            // Sinh CSRF token mới cho phiên làm việc
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['user']     = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ];

            return ['status' => true, 'message' => 'Đăng nhập thành công!'];
        } else {
            return ['status' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.'];
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return ['status' => false, 'message' => 'Lỗi hệ thống máy chủ trong quá trình xác thực. Vui lòng thử lại sau.'];
    }
}

/**
 * Đăng xuất và xóa phiên làm việc
 */
function logout_user() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
