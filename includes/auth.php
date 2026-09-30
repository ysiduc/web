<?php
/**
 * File xử lý xác thực đăng nhập & phân quyền người dùng
 * web_cty - Công ty CP Cơ khí & Xây dựng
 */

if (session_status() === PHP_SESSION_NONE) {
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
        header("Location: /test/web_cty/admin/login.php?error=unauthorized");
        exit;
    }
}

/**
 * Bắt buộc phải có quyền Admin, nếu là Staff sẽ bị từ chối truy cập
 */
function require_admin() {
    require_login();
    if (!is_admin()) {
        header("Location: /test/web_cty/admin/index.php?error=forbidden");
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
        return ['status' => false, 'message' => 'Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra file config/db.php.'];
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

        if (password_verify($password, $user['password']) || $password === $user['password']) {
            if ($password === $user['password'] && !password_verify($password, $user['password'])) {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $update = $db->prepare("UPDATE users SET password = :p WHERE id = :id");
                $update->execute(['p' => $new_hash, 'id' => $user['id']]);
            }

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
        return ['status' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
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
