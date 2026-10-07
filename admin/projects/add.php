<?php
$page_title = "Đăng Công Trình Mới";
require_once __DIR__ . '/../includes/admin-header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title           = trim($_POST['title'] ?? '');
    $category        = trim($_POST['category'] ?? '');
    $client          = trim($_POST['client'] ?? '');
    $location        = trim($_POST['location'] ?? '');
    $completion_date = !empty($_POST['completion_date']) ? $_POST['completion_date'] : null;
    $description     = trim($_POST['description'] ?? '');
    $content         = trim($_POST['content'] ?? '');

    if (empty($title) || empty($category) || empty($description)) {
        $error = 'Vui lòng nhập đầy đủ Tiêu đề công trình, Hạng mục và Mô tả ngắn.';
    } else {
        $slug = create_slug($title);
        $image_filename = 'default-project.jpg';

        // Xử lý upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_res = upload_image('image');
            if ($upload_res['status']) {
                $image_filename = $upload_res['filename'];
            } else {
                $error = $upload_res['message'];
            }
        }

        if (empty($error)) {
            $db = getDBConnection();
            if ($db) {
                try {
                    $stmt = $db->prepare("INSERT INTO projects (title, slug, category, description, content, image, client, location, completion_date, created_by) VALUES (:title, :slug, :category, :description, :content, :image, :client, :location, :completion_date, :created_by)");
                    $stmt->execute([
                        'title'           => $title,
                        'slug'            => $slug . '-' . time(),
                        'category'        => $category,
                        'description'     => $description,
                        'content'         => $content,
                        'image'           => $image_filename,
                        'client'          => $client,
                        'location'        => $location,
                        'completion_date' => $completion_date,
                        'created_by'      => $_SESSION['user_id'] ?? 1
                    ]);

                    set_flash_message('success', 'Đã thêm mới công trình thành công!');
                    header("Location: " . url('admin/projects/list.php'));
                    exit;
                } catch (Exception $e) {
                    error_log('DB Error in admin/projects/add.php: ' . $e->getMessage());
                    $error = 'Đã xảy ra lỗi máy chủ khi lưu công trình. Vui lòng thử lại sau.';
                }
            } else {
                set_flash_message('success', 'Đã tạo thành công công trình (chế độ demo).');
                header("Location: " . url('admin/projects/list.php'));
                exit;
            }
        }
    }
}
?>

<div style="max-width: 900px; margin: 0 auto;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2><i class="fa-solid fa-folder-plus"></i> Đăng Công Trình Mới</h2>
    <a href="<?= url('admin/projects/list.php') ?>" class="btn-action btn-edit"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div>
  <?php endif; ?>

  <div style="background: #fff; padding: 30px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="" method="POST" enctype="multipart/form-data">
      
      <div class="form-group">
        <label>Tên / Tiêu đề công trình <span style="color: red;">*</span></label>
        <input type="text" name="title" class="form-control" placeholder="Ví dụ: Thi công nhà xưởng cơ khí chính xác Canon Bắc Ninh" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Hạng mục thi công <span style="color: red;">*</span></label>
          <select name="category" class="form-control" required>
            <option value="">-- Chọn hạng mục thi công --</option>
            <optgroup label="⚡ 1. CƠ KHÍ XÂY DỰNG">
              <option value="Nhà kết cấu thép" <?= (($_POST['category'] ?? '') === 'Nhà kết cấu thép') ? 'selected' : '' ?>>Nhà kết cấu thép & Nhà xưởng</option>
              <option value="Cầu thang - Ban công" <?= (($_POST['category'] ?? '') === 'Cầu thang - Ban công') ? 'selected' : '' ?>>Cầu thang, ban công & Lan can</option>
              <option value="Mái tôn - Mái che" <?= (($_POST['category'] ?? '') === 'Mái tôn - Mái che') ? 'selected' : '' ?>>Mái tôn, mái che di động</option>
              <option value="Nhà cơi nới - Gác lửng" <?= (($_POST['category'] ?? '') === 'Nhà cơi nới - Gác lửng') ? 'selected' : '' ?>>Nhà cơi nới, lồng cơi tập thể</option>
              <option value="Thang thoát hiểm" <?= (($_POST['category'] ?? '') === 'Thang thoát hiểm') ? 'selected' : '' ?>>Thang thoát hiểm PCCC</option>
              <option value="Nhà xe - Mái che" <?= (($_POST['category'] ?? '') === 'Nhà xe - Mái che') ? 'selected' : '' ?>>Nhà xe, mái che công nghiệp</option>
              <option value="Mái kính" <?= (($_POST['category'] ?? '') === 'Mái kính') ? 'selected' : '' ?>>Mái kính Canopy & Giếng trời</option>
              <option value="Sắt mỹ thuật" <?= (($_POST['category'] ?? '') === 'Sắt mỹ thuật') ? 'selected' : '' ?>>Sắt mỹ thuật & Hoa sắt nghệ thuật</option>
              <option value="Cửa các loại" <?= (($_POST['category'] ?? '') === 'Cửa các loại') ? 'selected' : '' ?>>Cửa các loại (Sắt, cuốn, nhôm kính)</option>
            </optgroup>
            <optgroup label="🏢 2. XÂY DỰNG & HOÀN THIỆN">
              <option value="Xây nhà trọn gói" <?= (($_POST['category'] ?? '') === 'Xây nhà trọn gói') ? 'selected' : '' ?>>Xây nhà trọn gói (Nhà phố, Biệt thự)</option>
              <option value="Nội ngoại thất" <?= (($_POST['category'] ?? '') === 'Nội ngoại thất') ? 'selected' : '' ?>>Thi công nội ngoại thất cao cấp</option>
              <option value="Cải tạo & Phá dỡ" <?= (($_POST['category'] ?? '') === 'Cải tạo & Phá dỡ') ? 'selected' : '' ?>>Cải tạo, sửa chữa & Phá dỡ công trình</option>
            </optgroup>
          </select>
        </div>

        <div class="form-group">
          <label>Tên Khách hàng / Chủ đầu tư</label>
          <input type="text" name="client" class="form-control" placeholder="Ví dụ: Tập đoàn Samsung / Canon" value="<?php echo htmlspecialchars($_POST['client'] ?? ''); ?>">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Địa điểm thi công</label>
          <input type="text" name="location" class="form-control" placeholder="Ví dụ: KCN Tiên Sơn, Bắc Ninh" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label>Ngày hoàn thành / Nghiệm thu</label>
          <input type="date" name="completion_date" class="form-control" value="<?php echo htmlspecialchars($_POST['completion_date'] ?? ''); ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Hình ảnh công trình chính</label>
        <input type="file" name="image" id="project_image_input" class="form-control" accept="image/*">
        <div style="margin-top: 10px;">
          <img id="project_image_preview" src="#" alt="Preview" style="display: none; max-height: 180px; border-radius: 8px; border: 1px solid #cbd5e1;">
        </div>
      </div>

      <div class="form-group">
        <label>Mô tả ngắn <span style="color: red;">*</span></label>
        <textarea name="description" class="form-control" style="min-height: 80px;" placeholder="Tóm tắt quy mô công trình và hạng mục chính..." required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
      </div>

      <div class="form-group">
        <label>Nội dung chi tiết công trình & Giải pháp kỹ thuật</label>
        <textarea name="content" class="form-control" style="min-height: 180px;" placeholder="Mô tả chi tiết tiến độ, chủng loại vật liệu cơ khí / thép sử dụng, diện tích nhà xưởng..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
      </div>

      <div style="margin-top: 30px; display: flex; gap: 12px;">
        <button type="submit" class="btn-action" style="padding: 12px 24px; background: #f59e0b; color: #0f172a; font-weight: 700; font-size: 15px; border-radius: 8px;">
          <i class="fa-solid fa-floppy-disk"></i> Lưu & Xuất Bản
        </button>
        <a href="<?= url('admin/projects/list.php') ?>" class="btn-action" style="padding: 12px 24px; background: #e2e8f0; color: #334155; font-size: 15px; border-radius: 8px;">
          Hủy bỏ
        </a>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
