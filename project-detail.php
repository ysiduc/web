<?php
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDBConnection();
$project = null;
$related_projects = [];

if ($db && $id > 0) {
    try {
        $db->query("UPDATE projects SET views = views + 1 WHERE id = " . $id);
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch();

        if ($project) {
            $stmtRel = $db->prepare("SELECT * FROM projects WHERE category = :category AND id != :id ORDER BY id DESC LIMIT 3");
            $stmtRel->execute(['category' => $project['category'], 'id' => $id]);
            $related_projects = $stmtRel->fetchAll();
        }
    } catch (Exception $e) {}
}

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
        'content' => "Dự án Tổng thầu Xây dựng Nhà xưởng Sản xuất Linh kiện số 3 Samsung Bắc Ninh đòi hỏi tiêu chuẩn khắt khe về tải trọng và độ chính xác kết cấu thép.\n\nPNMEC đã áp dụng công nghệ hàn tự động dầm H và lắp dựng đạt tiến độ trước 15 ngày so với hợp đồng ban đầu. Toàn bộ cấu kiện thép được gia công phủ sơn epoxy 3 lớp chống chịu môi trường công nghiệp hóa chất nhẹ.",
        'views' => 450
    ];
}

$page_title = $project['title'];
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <span style="color: var(--accent-gold); font-weight: 700; text-transform: uppercase; font-size: 13px; letter-spacing: 1px;"><?php echo htmlspecialchars($project['category']); ?></span>
    <h1 style="font-size: 32px; margin: 10px 0;"><?php echo htmlspecialchars($project['title']); ?></h1>
    <div class="breadcrumb">
      <a href="/test/web_cty/index.php">Trang chủ</a> / <a href="/test/web_cty/projects.php">Công trình</a> / <span>Chi tiết</span>
    </div>
  </div>
</section>

<!-- Main Project Content Detail -->
<section class="project-detail-section">
  <div class="container">
    <div class="project-detail-grid">
      
      <!-- Left Column: Details -->
      <div>
        <div class="project-featured-img">
          <img src="/test/web_cty/assets/uploads/<?php echo !empty($project['image']) ? htmlspecialchars($project['image']) : 'default-project.jpg'; ?>" 
               alt="<?php echo htmlspecialchars($project['title']); ?>"
               onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=1000&q=80'">
        </div>

        <div class="project-meta-box">
          <div class="meta-box-item">
            <span>Chủ Đầu Tư:</span>
            <strong><?php echo htmlspecialchars($project['client']); ?></strong>
          </div>
          <div class="meta-box-item">
            <span>Địa Điểm Thi Công:</span>
            <strong><?php echo htmlspecialchars($project['location']); ?></strong>
          </div>
          <div class="meta-box-item">
            <span>Hoàn Thành:</span>
            <strong><?php echo format_date($project['completion_date']); ?></strong>
          </div>
          <div class="meta-box-item">
            <span>Lượt Xem:</span>
            <strong style="color: var(--accent-gold);"><?php echo number_format($project['views'] ?? 0); ?> lượt</strong>
          </div>
        </div>

        <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-navy);">Tổng Quan Dự Án & Giải Pháp Kỹ Thuật</h3>
        <p class="project-highlight-desc">
          <?php echo htmlspecialchars($project['description']); ?>
        </p>

        <div style="font-size: 15px; line-height: 1.8; color: var(--text-main); white-space: pre-line;">
          <?php echo htmlspecialchars($project['content']); ?>
        </div>
      </div>

      <!-- Right Column: Sidebar CTA & Related Projects -->
      <div>
        <div class="cta-sidebar-card">
          <h3>Tư Vấn Thiết Kế Công Trình Tương Tự</h3>
          <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">
            Quý khách hàng có nhu cầu thi công nhà xưởng, gia công cơ khí hoặc nhận báo giá dự toán chi tiết?
          </p>
          <a href="/test/web_cty/contact.php" class="btn btn-primary" style="width: 100%; text-align: center;"><i class="fa-solid fa-headset"></i> Gửi Yêu Cầu Báo Giá</a>
        </div>

        <?php if (count($related_projects) > 0): ?>
          <div class="related-projects-box">
            <h3 style="font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid var(--accent-gold); padding-bottom: 8px;">Dự Án Cùng Hạng Mục</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <?php foreach ($related_projects as $rel): ?>
                <div style="display: flex; gap: 12px; align-items: center;">
                  <img src="/test/web_cty/assets/uploads/<?php echo !empty($rel['image']) ? htmlspecialchars($rel['image']) : 'default-project.jpg'; ?>" 
                       style="width: 70px; height: 55px; object-fit: cover; border-radius: 6px;"
                       onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=150&q=80'">
                  <div>
                    <h4 style="font-size: 13px; line-height: 1.3;"><a href="/test/web_cty/project-detail.php?id=<?php echo $rel['id']; ?>"><?php echo htmlspecialchars($rel['title']); ?></a></h4>
                    <span style="font-size: 11px; color: var(--text-muted);"><?php echo htmlspecialchars($rel['location']); ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
