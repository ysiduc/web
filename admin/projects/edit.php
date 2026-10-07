<?php
$page_title = "Chỉnh Sửa Công Trình";
require_once __DIR__ . '/../includes/admin-header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDBConnection();
$project = null;

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch();
    } catch (Exception $e) {}
}

// Fallback demo data for editing
if (!$project) {
    $project = [
        'id' => $id > 0 ? $id : 1,
        'title' => 'Nhà Xưởng Công Nghiệp Tập Đoàn Samsung Bắc Ninh',
        'category' => 'Xây dựng công nghiệp',
        'client' => 'Tập đoàn Samsung Electronics',
        'location' => 'KCN Yên Phong, Bắc Ninh',
        'completion_date' => '2025-11-20',
        'image' => 'default-project.jpg',
        'description' => 'Thi công tổng thầu nhà xưởng sản xuất quy mô 25.000m2 với kết cấu thép vượt khổ lớn.',
        'content' => 'Dự án Tổng thầu Xây dựng Nhà xưởng Sản xuất Linh kiện số 3 Samsung Bắc Ninh đòi hỏi tiêu chuẩn khắt khe về tải trọng và độ chính xác kết cấu thép. Tân Phát E&C đã áp dụng công nghệ hàn tự động dầm H và lắp dựng đạt tiến độ trước 15 ngày.'
    ];
}

$error = '';

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
        $image_filename = $project['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_res = upload_image('image');
            if ($upload_res['status']) {
                $image_filename = $upload_res['filename'];
            } else {
                $error = $upload_res['message'];
            }
        }

        if (empty($error)) {
            if ($db) {
                try {
                    $stmt = $db->prepare("UPDATE projects SET title = :title, category = :category, client = :client, location = :location, completion_date = :completion_date, description = :description, content = :content, image = :image WHERE id = :id");
                    $stmt->execute([
                        'title'           => $title,
                        'category'        => $category,
                        'client'          => $client,
                        'location'        => $location,
                        'completion_date' => $completion_date,
                        'description'     => $description,
                        'content'         => $content,
                        'image'           => $image_filename,
                        'id'              => $project['id']
                    ]);

                    set_flash_message('success', 'Đã cập nhật công trình thành công!');
                    header("Location: " . url('admin/projects/list.php'));
                    exit;
                } catch (Exception $e) {
                    error_log('DB Error in admin/projects/edit.php: ' . $e->getMessage());
                    $error = 'Đã xảy ra lỗi máy chủ khi cập nhật công trình. Vui lòng thử lại sau.';
                }
            } else {
                set_flash_message('success', 'Đã lưu chỉnh sửa công trình (chế độ demo).');
                header("Location: " . url('admin/projects/list.php'));
                exit;
            }
        }
    }
}
?>

<div style="max-width: 900px; margin: 0 auto;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2><i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa Công Trình #<?php echo $project['id']; ?></h2>
    <a href="<?= url('admin/projects/list.php') ?>" class="btn-action btn-edit"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?></div>
  <?php endif; ?>

  <div style="background: #fff; padding: 30px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <form action="" method="POST" enctype="multipart/form-data">
      
      <div class="form-group">
        <label>Tên / Tiêu đề công trình <span style="color: red;">*</span></label>
        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($project['title']); ?>">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Hạng mục thi công <span style="color: red;">*</span></label>
          <select name="category" class="form-control" required>
            <option value="">-- Chọn hạng mục thi công --</option>
            <optgroup label="⚡ 1. CƠ KHÍ XÂY DỰNG">
              <option value="Nhà kết cấu thép" <?= $project['category'] === 'Nhà kết cấu thép' ? 'selected' : '' ?>>Nhà kết cấu thép & Nhà xưởng</option>
              <option value="Cầu thang - Ban công" <?= $project['category'] === 'Cầu thang - Ban công' ? 'selected' : '' ?>>Cầu thang, ban công & Lan can</option>
              <option value="Mái tôn - Mái che" <?= $project['category'] === 'Mái tôn - Mái che' ? 'selected' : '' ?>>Mái tôn, mái che di động</option>
              <option value="Nhà cơi nới - Gác lửng" <?= $project['category'] === 'Nhà cơi nới - Gác lửng' ? 'selected' : '' ?>>Nhà cơi nới, lồng cơi tập thể</option>
              <option value="Thang thoát hiểm" <?= $project['category'] === 'Thang thoát hiểm' ? 'selected' : '' ?>>Thang thoát hiểm PCCC</option>
              <option value="Nhà xe - Mái che" <?= $project['category'] === 'Nhà xe - Mái che' ? 'selected' : '' ?>>Nhà xe, mái che công nghiệp</option>
              <option value="Mái kính" <?= $project['category'] === 'Mái kính' ? 'selected' : '' ?>>Mái kính Canopy & Giếng trời</option>
              <option value="Sắt mỹ thuật" <?= $project['category'] === 'Sắt mỹ thuật' ? 'selected' : '' ?>>Sắt mỹ thuật & Hoa sắt nghệ thuật</option>
              <option value="Cửa các loại" <?= $project['category'] === 'Cửa các loại' ? 'selected' : '' ?>>Cửa các loại (Sắt, cuốn, nhôm kính)</option>
            </optgroup>
            <optgroup label="🏢 2. XÂY DỰNG & HOÀN THIỆN">
              <option value="Xây nhà trọn gói" <?= $project['category'] === 'Xây nhà trọn gói' ? 'selected' : '' ?>>Xây nhà trọn gói (Nhà phố, Biệt thự)</option>
              <option value="Nội ngoại thất" <?= $project['category'] === 'Nội ngoại thất' ? 'selected' : '' ?>>Thi công nội ngoại thất cao cấp</option>
              <option value="Cải tạo & Phá dỡ" <?= $project['category'] === 'Cải tạo & Phá dỡ' ? 'selected' : '' ?>>Cải tạo, sửa chữa & Phá dỡ công trình</option>
            </optgroup>
          </select>
        </div>

        <div class="form-group">
          <label>Tên Khách hàng / Chủ đầu tư</label>
          <input type="text" name="client" class="form-control" value="<?php echo htmlspecialchars($project['client']); ?>">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label>Địa điểm thi công</label>
          <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($project['location']); ?>">
        </div>

        <div class="form-group">
          <label>Ngày hoàn thành / Nghiệm thu</label>
          <input type="date" name="completion_date" class="form-control" value="<?php echo htmlspecialchars($project['completion_date']); ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Hình ảnh công trình (Tải ảnh mới nếu muốn thay đổi)</label>
        <input type="file" name="image" id="project_image_input" class="form-control" accept="image/*">
        <div style="margin-top: 10px; display: flex; align-items: center; gap: 15px;">
          <div>
            <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Ảnh hiện tại:</span>
            <img src="<?= asset_url('uploads/' . htmlspecialchars($project['image'])) ?>" alt="Current" style="height: 100px; border-radius: 6px; border: 1px solid #cbd5e1;" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=150&q=80'">
          </div>
          <div>
            <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Xem trước ảnh mới:</span>
            <img id="project_image_preview" src="#" alt="Preview" style="display: none; height: 100px; border-radius: 6px; border: 1px solid #cbd5e1;">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Mô tả ngắn <span style="color: red;">*</span></label>
        <textarea name="description" class="form-control" style="min-height: 80px;" required><?php echo htmlspecialchars($project['description']); ?></textarea>
      </div>

      <div class="form-group">
        <label>Nội dung chi tiết công trình</label>
        <textarea name="content" class="form-control" style="min-height: 180px;"><?php echo htmlspecialchars($project['content']); ?></textarea>
      </div>

      <div style="margin-top: 30px; display: flex; gap: 12px;">
        <button type="submit" class="btn-action" style="padding: 12px 24px; background: #0f172a; color: #fff; font-weight: 700; font-size: 15px; border-radius: 8px;">
          <i class="fa-solid fa-floppy-disk"></i> Cập Nhật Thay Đổi
        </button>
        <a href="<?= url('admin/projects/list.php') ?>" class="btn-action" style="padding: 12px 24px; background: #e2e8f0; color: #334155; font-size: 15px; border-radius: 8px;">
          Hủy bỏ
        </a>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
