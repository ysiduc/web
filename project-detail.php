<?php
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDBConnection();
$project = null;
$related_projects = [];

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id AND status = 'published' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $project = $stmt->fetch();

        if ($project) {
            $db->query("UPDATE projects SET views = views + 1 WHERE id = " . $id);
            $stmtRel = $db->prepare("SELECT * FROM projects WHERE category = :category AND id != :id AND status = 'published' ORDER BY id DESC LIMIT 3");
            $stmtRel->execute(['category' => $project['category'], 'id' => $id]);
            $related_projects = $stmtRel->fetchAll();
        }
    } catch (Exception $e) {}
}

$page_title = $project ? $project['title'] : "Không Tìm Thấy Công Trình";
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($project): ?>

<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <span style="color: var(--accent-gold); font-weight: 700; text-transform: uppercase; font-size: 13px; letter-spacing: 1px;"><?php echo htmlspecialchars($project['category']); ?></span>
    <h1 style="font-size: 32px; margin: 10px 0;"><?php echo htmlspecialchars($project['title']); ?></h1>
    <div class="breadcrumb">
      <a href="<?= url('/index.php') ?>">Trang chủ</a> / <a href="<?= url('/projects.php') ?>">Công trình</a> / <span>Chi tiết</span>
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
          <img src="<?= htmlspecialchars(get_project_image_url($project['image'])) ?>" 
               alt="<?php echo htmlspecialchars($project['title']); ?>"
               onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">
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
            <span>Khởi Công:</span>
            <strong><?php echo !empty($project['start_date']) ? format_date($project['start_date']) : 'N/A'; ?></strong>
          </div>
          <div class="meta-box-item">
            <span>Hoàn Thành:</span>
            <strong><?php echo !empty($project['completion_date']) ? format_date($project['completion_date']) : 'N/A'; ?></strong>
          </div>
          <div class="meta-box-item">
            <span>Lượt Xem:</span>
            <strong style="color: var(--accent-gold);"><?php echo number_format($project['views'] ?? 0); ?> lượt</strong>
          </div>
        </div>

        <?php
        $is_custom = ($project['detail_mode'] ?? 'basic') === 'custom';
        $blocks = [];
        if ($is_custom && !empty($project['detail_blocks'])) {
            $decoded = json_decode($project['detail_blocks'], true);
            if (is_array($decoded) && count($decoded) > 0) {
                $blocks = $decoded;
            } else {
                $is_custom = false;
            }
        } else {
            $is_custom = false;
        }
        ?>

        <?php if ($is_custom): ?>
          <div class="project-custom-builder-content">
            <?php foreach ($blocks as $blk): 
              $type = $blk['type'] ?? '';
            ?>
              <?php if ($type === 'heading'): 
                $lvl = in_array((int)($blk['level'] ?? 2), [2, 3, 4]) ? (int)$blk['level'] : 2;
                $tag = "h" . $lvl;
              ?>
                <<?= $tag ?> class="builder-heading builder-heading-<?= $lvl ?>"><?= htmlspecialchars($blk['text'] ?? '') ?></<?= $tag ?>>

              <?php elseif ($type === 'paragraph'): ?>
                <p class="builder-paragraph"><?= nl2br(htmlspecialchars($blk['text'] ?? '')) ?></p>

              <?php elseif ($type === 'image' && !empty($blk['src'])): ?>
                <figure class="builder-image-box">
                  <img src="<?= htmlspecialchars(get_project_image_url($blk['src'])) ?>" alt="<?= htmlspecialchars($blk['alt'] ?? '') ?>" loading="lazy" />
                  <?php if (!empty($blk['caption'])): ?>
                    <figcaption class="builder-caption"><?= htmlspecialchars($blk['caption']) ?></figcaption>
                  <?php endif; ?>
                </figure>

              <?php elseif ($type === 'gallery' && !empty($blk['images'])): ?>
                <div class="builder-gallery-grid">
                  <?php foreach ($blk['images'] as $gImg): 
                    if (empty($gImg['src'])) continue;
                  ?>
                    <figure class="builder-gallery-item">
                      <img src="<?= htmlspecialchars(get_project_image_url($gImg['src'])) ?>" alt="<?= htmlspecialchars($gImg['alt'] ?? '') ?>" loading="lazy" />
                      <?php if (!empty($gImg['caption'])): ?>
                        <figcaption class="builder-gallery-caption"><?= htmlspecialchars($gImg['caption']) ?></figcaption>
                      <?php endif; ?>
                    </figure>
                  <?php endforeach; ?>
                </div>

              <?php elseif ($type === 'callout'): ?>
                <div class="builder-callout builder-callout-<?= htmlspecialchars($blk['variant'] ?? 'gold') ?>">
                  <?php if (!empty($blk['title'])): ?>
                    <h4 class="builder-callout-title"><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($blk['title']) ?></h4>
                  <?php endif; ?>
                  <div class="builder-callout-body"><?= nl2br(htmlspecialchars($blk['text'] ?? '')) ?></div>
                </div>

              <?php elseif ($type === 'divider'): ?>
                <hr class="builder-divider" />

              <?php elseif ($type === 'html' && !empty($blk['content'])): ?>
                <div class="builder-custom-html">
                  <?= sanitize_html_content($blk['content']) ?>
                </div>

              <?php endif; ?>
            <?php endforeach; ?>
          </div>

        <?php else: ?>
          <!-- Basic mode -->
          <h3 style="font-size: 22px; margin-bottom: 16px; color: var(--primary-navy);">Tổng Quan Dự Án & Giải Pháp Kỹ Thuật</h3>
          <p class="project-highlight-desc">
            <?php echo htmlspecialchars($project['description']); ?>
          </p>

          <div style="font-size: 15px; line-height: 1.8; color: var(--text-main); white-space: pre-line;">
            <?php echo htmlspecialchars($project['content']); ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right Column: Sidebar CTA & Related Projects -->
      <div>
        <div class="cta-sidebar-card">
          <h3>Tư Vấn Thiết Kế Công Trình Tương Tự</h3>
          <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">
            Quý khách hàng có nhu cầu thi công nhà xưởng, gia công cơ khí hoặc nhận báo giá dự toán chi tiết?
          </p>
          <a href="<?= url('/contact.php') ?>" class="btn btn-primary" style="width: 100%; text-align: center;"><i class="fa-solid fa-headset"></i> Gửi Yêu Cầu Báo Giá</a>
        </div>

        <?php if (count($related_projects) > 0): ?>
          <div class="related-projects-box">
            <h3 style="font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid var(--accent-gold); padding-bottom: 8px;">Dự Án Cùng Hạng Mục</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
              <?php foreach ($related_projects as $rel): ?>
                <div style="display: flex; gap: 12px; align-items: center;">
                  <img src="<?= htmlspecialchars(get_project_image_url($rel['image'])) ?>" 
                       style="width: 70px; height: 55px; object-fit: cover; border-radius: 6px;"
                       onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">
                  <div>
                    <h4 style="font-size: 13px; line-height: 1.3;"><a href="<?= url('/project-detail.php?id=' . $rel['id']) ?>"><?php echo htmlspecialchars($rel['title']); ?></a></h4>
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
<?php else: ?>
<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <h1>Không Tìm Thấy Công Trình</h1>
    <div class="breadcrumb">
      <a href="<?= url('/index.php') ?>">Trang chủ</a> / <a href="<?= url('/projects.php') ?>">Công trình</a> / <span>Không tìm thấy</span>
    </div>
  </div>
</section>

<section style="padding: 80px 20px; text-align: center;">
  <div class="container">
    <i class="fa-solid fa-folder-open" style="font-size: 48px; color: #94a3b8; margin-bottom: 20px; display: block; opacity: 0.6;"></i>
    <h2 style="font-size: 22px; color: var(--primary-navy, #0f172a); margin-bottom: 8px;">Công trình không tồn tại hoặc chưa được công bố</h2>
    <p style="color: #64748b; max-width: 500px; margin: 10px auto 25px;">Dự án bạn đang tìm kiếm hiện không khả dụng trong hệ thống.</p>
    <a href="<?= url('/projects.php') ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách công trình</a>
  </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
