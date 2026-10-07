<?php
$page_title = "Hồ Sơ Năng Lực & Các Công Trình Đã Hoàn Thiện";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();

// Filters
$group_filter    = $_GET['group'] ?? 'all';
$category_filter = $_GET['category'] ?? 'all';
$search_query    = trim($_GET['search'] ?? '');

// Definition of 2 Main Groups and their Subcategories
$co_khi_subcats = [
    'Nhà kết cấu thép',
    'Cầu thang - Ban công',
    'Mái tôn - Mái che',
    'Nhà cơi nới - Gác lửng',
    'Thang thoát hiểm',
    'Nhà xe - Mái che',
    'Mái kính',
    'Sắt mỹ thuật',
    'Cửa các loại',
    'Cơ khí chế tạo',
    'Kết cấu thép'
];

$xay_dung_subcats = [
    'Xây nhà trọn gói',
    'Nội ngoại thất',
    'Cải tạo & Phá dỡ',
    'Xây dựng dân dụng',
    'Xây dựng công nghiệp'
];

$projects = [];

if ($db) {
    try {
        $sql = "SELECT * FROM projects WHERE status = 'published'";
        $params = [];

        // Category filter
        if ($category_filter !== 'all' && !empty($category_filter)) {
            $sql .= " AND category = :category";
            $params['category'] = $category_filter;
        } 
        // Group filter
        elseif ($group_filter === 'co_khi') {
            $placeholders = [];
            foreach ($co_khi_subcats as $i => $cat) {
                $key = ":ck_cat_$i";
                $placeholders[] = $key;
                $params[$key] = $cat;
            }
            $sql .= " AND category IN (" . implode(',', $placeholders) . ")";
        } 
        elseif ($group_filter === 'xay_dung') {
            $placeholders = [];
            foreach ($xay_dung_subcats as $i => $cat) {
                $key = ":xd_cat_$i";
                $placeholders[] = $key;
                $params[$key] = $cat;
            }
            $sql .= " AND category IN (" . implode(',', $placeholders) . ")";
        }

        // Search filter
        if (!empty($search_query)) {
            $sql .= " AND (title LIKE :search OR client LIKE :search OR location LIKE :search OR description LIKE :search OR category LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();
    } catch (Exception $e) {}
}

// Fallback project images map for preview
$image_fallbacks = [
    'Nhà kết cấu thép' => asset_url('images/service-cons.png'),
    'Cầu thang - Ban công' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80',
    'Mái tôn - Mái che' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=600&q=80',
    'Nhà cơi nới - Gác lửng' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=600&q=80',
    'Thang thoát hiểm' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=600&q=80',
    'Nhà xe - Mái che' => 'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=600&q=80',
    'Mái kính' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?auto=format&fit=crop&w=600&q=80',
    'Sắt mỹ thuật' => asset_url('images/service-cnc.png'),
    'Cửa các loại' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=600&q=80',
    'Xây nhà trọn gói' => asset_url('images/service-plant.png'),
    'Nội ngoại thất' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=600&q=80',
    'Cải tạo & Phá dỡ' => 'https://images.unsplash.com/photo-1581092795360-fd1ca04f0952?auto=format&fit=crop&w=600&q=80',
];
?>

<!-- ═══════════════════════════════════════════════════
     1. HERO BANNER
═══════════════════════════════════════════════════ -->
<section class="proj-hero">
  <div class="container">
    <div class="proj-hero__inner">
      <div class="proj-badge">
        <i class="fa-solid fa-trophy"></i> NĂNG LỰC THỰC TẾ
      </div>
      <h1 class="proj-hero__title">Hồ Sơ Năng Lực &amp; Các Công Trình Đã Hoàn Thiện</h1>
      <p class="proj-hero__subtitle">
        Tổng hợp các dự án tiêu biểu trong lĩnh vực Cơ Khí Xây Dựng và Xây Dựng Dân Dụng đã được PNMEC bàn giao đạt chuẩn an toàn &amp; chất lượng.
      </p>
      
      <div class="proj-breadcrumb">
        <a href="<?= url('index.php') ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span>Công trình</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     2. FILTER TOOLBAR & CATEGORIES CLASSIFICATION
═══════════════════════════════════════════════════ -->
<section class="proj-main-section">
  <div class="container proj-container">

    <!-- Main Classification Groups (2 Main Tabs) -->
    <div class="proj-main-tabs-wrap">
      <div class="proj-main-tabs">
        <a href="<?= url('projects.php?group=all') ?>" class="proj-main-tab <?= ($group_filter === 'all' && $category_filter === 'all') ? 'active' : '' ?>">
          <i class="fa-solid fa-layer-group"></i> Tất Cả Công Trình
        </a>
        <a href="<?= url('projects.php?group=co_khi') ?>" class="proj-main-tab proj-main-tab--gold <?= ($group_filter === 'co_khi' || in_array($category_filter, $co_khi_subcats)) ? 'active' : '' ?>">
          <i class="fa-solid fa-hammer"></i> Cơ Khí Xây Dựng
        </a>
        <a href="<?= url('projects.php?group=xay_dung') ?>" class="proj-main-tab proj-main-tab--blue <?= ($group_filter === 'xay_dung' || in_array($category_filter, $xay_dung_subcats)) ? 'active' : '' ?>">
          <i class="fa-solid fa-building"></i> Xây Dựng &amp; Hoàn Thiện
        </a>
      </div>

      <!-- Search Box -->
      <form action="<?= url('projects.php') ?>" method="GET" class="proj-search-form">
        <?php if ($group_filter !== 'all'): ?>
          <input type="hidden" name="group" value="<?= htmlspecialchars($group_filter) ?>">
        <?php endif; ?>
        <?php if ($category_filter !== 'all'): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($category_filter) ?>">
        <?php endif; ?>
        <div class="proj-search-input-wrap">
          <i class="fa-solid fa-magnifying-glass proj-search-ico"></i>
          <input type="text" name="search" class="proj-search-input" placeholder="Tìm kiếm tên dự án, địa điểm, loại công trình..." value="<?= htmlspecialchars($search_query) ?>">
          <?php if (!empty($search_query)): ?>
            <a href="<?= url('projects.php?group=' . urlencode($group_filter)) ?>" class="proj-search-clear"><i class="fa-solid fa-xmark"></i></a>
          <?php endif; ?>
        </div>
        <button type="submit" class="proj-search-btn">Tìm Kiếm</button>
      </form>
    </div>

    <!-- Detailed Subcategory Filter Pills (Chi Tiết Phân Loại) -->
    <div class="proj-subcats-box">
      <div class="proj-subcats-title">
        <i class="fa-solid fa-filter"></i> 
        <?php 
          if ($group_filter === 'co_khi') echo 'Phân loại chi tiết Cơ Khí Xây Dựng:';
          elseif ($group_filter === 'xay_dung') echo 'Phân loại chi tiết Xây Dựng &amp; Hoàn Thiện:';
          else echo 'Phân loại chi tiết theo từng hạng mục:';
        ?>
      </div>
      
      <div class="proj-subcats-list">
        <a href="<?= url('projects.php?group=' . urlencode($group_filter)) ?>" class="proj-subcat-pill <?= ($category_filter === 'all') ? 'active' : '' ?>">
          Tất cả hạng mục
        </a>

        <?php 
          $display_cats = [];
          if ($group_filter === 'co_khi') {
              $display_cats = $co_khi_subcats;
          } elseif ($group_filter === 'xay_dung') {
              $display_cats = $xay_dung_subcats;
          } else {
              $display_cats = array_merge($co_khi_subcats, $xay_dung_subcats);
          }
          // Remove duplicates
          $display_cats = array_unique($display_cats);
          
          foreach ($display_cats as $cat):
            $isActive = ($category_filter === $cat);
            $isCk = in_array($cat, $co_khi_subcats);
        ?>
        <a href="<?= url('projects.php?group=' . ($isCk ? 'co_khi' : 'xay_dung') . '&category=' . urlencode($cat)) ?>" class="proj-subcat-pill <?= $isActive ? 'active' : '' ?> <?= $isCk ? 'proj-subcat-pill--ck' : 'proj-subcat-pill--xd' ?>">
          <i class="fa-solid <?= $isCk ? 'fa-screwdriver-wrench' : 'fa-trowel-bricks' ?> fa-xs"></i> <?= htmlspecialchars($cat) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Results Status Bar -->
    <div class="proj-status-bar">
      <div class="proj-status-count">
        Hiển thị <strong><?= count($projects) ?></strong> công trình đã hoàn thiện
        <?php if ($category_filter !== 'all'): ?>
          cho hạng mục <strong>"<?= htmlspecialchars($category_filter) ?>"</strong>
        <?php elseif ($group_filter === 'co_khi'): ?>
          thuộc lĩnh vực <strong>"Cơ Khí Xây Dựng"</strong>
        <?php elseif ($group_filter === 'xay_dung'): ?>
          thuộc lĩnh vực <strong>"Xây Dựng &amp; Hoàn Thiện"</strong>
        <?php endif; ?>
        <?php if (!empty($search_query)): ?>
          khớp với từ khóa <strong>"<?= htmlspecialchars($search_query) ?>"</strong>
        <?php endif; ?>
      </div>
      <?php if ($category_filter !== 'all' || $group_filter !== 'all' || !empty($search_query)): ?>
        <a href="<?= url('projects.php') ?>" class="proj-reset-link"><i class="fa-solid fa-rotate-left"></i> Xem toàn bộ công trình</a>
      <?php endif; ?>
    </div>

    <!-- ═══════════════════════════════════════════════════
         3. PROJECTS GRID
    ═══════════════════════════════════════════════════ -->
    <div class="proj-grid">
      <?php if (count($projects) > 0): ?>
        <?php foreach ($projects as $p): 
          $isCoKhi = in_array($p['category'], $co_khi_subcats);
          $imgSrc = !empty($p['image']) && $p['image'] !== 'default-project.jpg' 
                    ? asset_url('images/' . htmlspecialchars($p['image']))
                    : ($image_fallbacks[$p['category']] ?? 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=600&q=80');
        ?>
          <div class="proj-card <?= $isCoKhi ? 'proj-card--ck' : 'proj-card--xd' ?>">
            <div class="proj-card__thumb">
              <span class="proj-card__badge <?= $isCoKhi ? 'proj-card__badge--gold' : 'proj-card__badge--blue' ?>">
                <i class="fa-solid <?= $isCoKhi ? 'fa-hammer' : 'fa-building' ?> fa-xs"></i> <?= htmlspecialchars($p['category']) ?>
              </span>
              <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy" onerror="this.src='<?= asset_url('images/service-cons.png') ?>'">
              <div class="proj-card__thumb-overlay">
                <a href="<?= url('project-detail.php?id=' . $p['id']) ?>" class="proj-card__quick-view">
                  <i class="fa-solid fa-eye"></i> Xem Chi Tiết
                </a>
              </div>
            </div>
            
            <div class="proj-card__content">
              <div class="proj-card__group-tag <?= $isCoKhi ? 'proj-card__group-tag--ck' : 'proj-card__group-tag--xd' ?>">
                <?= $isCoKhi ? 'CƠ KHÍ XÂY DỰNG' : 'XÂY DỰNG &amp; HOÀN THIỆN' ?>
              </div>
              <h3 class="proj-card__title">
                <a href="<?= url('project-detail.php?id=' . $p['id']) ?>"><?= htmlspecialchars($p['title']) ?></a>
              </h3>
              
              <div class="proj-card__meta-list">
                <div class="proj-card__meta-item">
                  <i class="fa-solid fa-user-tie"></i> 
                  <span><strong>Chủ đầu tư:</strong> <?= htmlspecialchars($p['client']) ?></span>
                </div>
                <div class="proj-card__meta-item">
                  <i class="fa-solid fa-location-dot"></i> 
                  <span><strong>Địa điểm:</strong> <?= htmlspecialchars($p['location']) ?></span>
                </div>
              </div>
              
              <p class="proj-card__desc"><?= htmlspecialchars($p['description']) ?></p>
              
              <div class="proj-card__footer">
                <a href="<?= url('project-detail.php?id=' . $p['id']) ?>" class="proj-card__more-btn">
                  Chi Tiết Công Trình <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="<?= url('quote.php?service=' . urlencode($p['title'])) ?>" class="proj-card__quote-btn">
                  Báo Giá Tương Tự
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="proj-empty-state">
          <div class="proj-empty-icon"><i class="fa-solid fa-folder-open"></i></div>
          <h3>Chưa Tìm Thấy Công Trình Phù Hợp</h3>
          <p>Hiện không có công trình nào phù hợp với bộ lọc bạn đã chọn. Vui lòng chọn hạng mục khác hoặc xóa từ khóa tìm kiếm.</p>
          <a href="<?= url('projects.php') ?>" class="btn btn-primary"><i class="fa-solid fa-rotate-left"></i> Xem Tất Cả Công Trình</a>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     4. CTA LIÊN HỆ TƯ VẤN DỰ ÁN MỚI
═══════════════════════════════════════════════════ -->
<section class="proj-cta">
  <div class="container">
    <div class="proj-cta__box">
      <div class="proj-cta__left">
        <span class="proj-cta__tag"><i class="fa-solid fa-award"></i> ĐỒNG HÀNH CÙNG MỌI CÔNG TRÌNH</span>
        <h3 class="proj-cta__title">Bạn Muốn Khởi Công Dự Án Với Tiến Độ &amp; Chất Lượng Tốt Nhất?</h3>
        <p class="proj-cta__desc">
          Hãy gửi thông tin yêu cầu hoặc liên hệ trực tiếp với chúng tôi để được tư vấn bản vẽ kỹ thuật và nhận báo giá dự toán chi tiết, minh bạch nhất.
        </p>
      </div>
      <div class="proj-cta__actions">
        <a href="<?= url('quote.php') ?>" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-calculator"></i> Nhận Báo Giá Dự Án
        </a>
        <a href="<?= url('contact.php') ?>" class="btn btn-outline btn-lg">
          <i class="fa-solid fa-headset"></i> Tư Vấn Kỹ Thuật
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
