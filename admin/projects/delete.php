<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $db = getDBConnection();
    if ($db) {
        try {
            // Lấy thông tin tệp ảnh cũ để xóa khỏi máy chủ nếu không phải ảnh mặc định
            $stmt = $db->prepare("SELECT image FROM projects WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $proj = $stmt->fetch();

            if ($proj && !empty($proj['image']) && $proj['image'] !== 'default-project.jpg') {
                $image_path = __DIR__ . '/../../assets/uploads/' . $proj['image'];
                if (file_exists($image_path)) {
                    @unlink($image_path);
                }
            }

            $stmtDel = $db->prepare("DELETE FROM projects WHERE id = :id");
            $stmtDel->execute(['id' => $id]);

            set_flash_message('success', 'Đã xóa công trình #' . $id . ' thành công khỏi hệ thống.');
        } catch (Exception $e) {
            set_flash_message('danger', 'Không thể xóa công trình do lỗi cơ sở dữ liệu.');
        }
    } else {
        set_flash_message('success', 'Đã xóa công trình #' . $id . ' (chế độ demo).');
    }
}

header("Location: /test/web_cty/admin/projects/list.php");
exit;
