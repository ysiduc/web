<?php
$page_title = "Quản Lý Công Trình";
require_once __DIR__ . '/../includes/admin-header.php';

$db = getDBConnection();
$group_filter    = $_GET['group'] ?? 'all';
$category_filter = $_GET['category'] ?? '';
$search_query    = trim($_GET['search'] ?? '');

$co_khi_cats = [
    'Nhà kết cấu thép', 'Cầu thang - Ban công', 'Mái tôn - Mái che',
    'Nhà cơi nới - Gác lửng', 'Thang thoát hiểm', 'Nhà xe - Mái che',
    'Mái kính', 'Sắt mỹ thuật', 'Cửa các loại', 'Cơ khí chế tạo', 'Kết cấu thép'
];

$xay_dung_cats = [
    'Xây nhà trọn gói', 'Nội ngoại thất', 'Cải tạo & Phá dỡ',
    'Xây dựng dân dụng', 'Xây dựng công nghiệp'
];

$projects = [];
$total_count = 0;
$ck_count = 0;
$xd_count = 0;

if ($db) {
    try {
        // Count totals
        $all_cats = $db->query("SELECT category FROM projects")->fetchAll();
        $total_count = count($all_cats);
        foreach ($all_cats as $c) {
            if (in_array($c['category'], $co_khi_cats)) $ck_count++;
            else $xd_count++;
        }

        $sql = "SELECT p.*, u.fullname as author_name FROM projects p LEFT JOIN users u ON p.created_by = u.id WHERE 1=1";
        $params = [];

        if (!empty($category_filter)) {
            $sql .= " AND p.category = :category";
            $params['category'] = $category_filter;
        } elseif ($group_filter === 'co_khi') {
            $placeholders = [];
            foreach ($co_khi_cats as $i => $cat) {
                $key = ":ck_$i";
                $placeholders[] = $key;
                $params[$key] = $cat;
            }
            $sql .= " AND p.category IN (" . implode(',', $placeholders) . ")";
        } elseif ($group_filter === 'xay_dung') {
            $placeholders = [];
            foreach ($xay_dung_cats as $i => $cat) {
                $key = ":xd_$i";
                $placeholders[] = $key;
                $params[$key] = $cat;
            }
            $sql .= " AND p.category IN (" . implode(',', $placeholders) . ")";
        }

        if (!empty($search_query)) {
            $sql .= " AND (p.title LIKE :search OR p.client LIKE :search OR p.location LIKE :search OR p.category LIKE :search)";
            $params['search'] = '%' . $search_query . '%';
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();
    } catch (Exception $e) {}
}
?>

<style>
.adm-compact-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  background: #ffffff;
  padding: 12px 18px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  margin-bottom: 16px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

.adm-pills {
  display: flex;
  gap: 8px;
  align-items: center;
}

.adm-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  background: #f1f5f9;
  color: #475569;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

.adm-pill:hover {
  background: #e2e8f0;
  color: #0f172a;
}

.adm-pill.active {
  background: #0f172a;
  color: #ffffff;
}

.adm-pill--gold.active {
  background: #f59e0b;
  color: #0f172a;
  font-weight: 700;
}

.adm-pill--blue.active {
  background: #2563eb;
  color: #ffffff;
}

.adm-filter-row {
  display: flex;
  gap: 10px;
  align-items: center;
  flex-wrap: wrap;
}

.adm-compact-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}

.adm-compact-table th {
  background: #f8fafc;
  color: #475569;
  font-weight: 700;
  font-size: 12.5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 10px 14px;
  border-bottom: 1px solid #e2e8f0;
  white-space: nowrap;
}

.adm-compact-table td {
  padding: 10px 14px;
  border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}

.adm-compact-table tr:hover td {
  background: #f8fafc;
}

.adm-thumb {
  width: 52px;
  height: 38px;
  border-radius: 6px;
  object-fit: cover;
  display: block;
  border: 1px solid #e2e8f0;
}

.adm-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 8px;
  border-radius: 5px;
  font-size: 11.5px;
  font-weight: 700;
  white-space: nowrap;
}

.adm-badge--ck {
  background: rgba(245, 158, 11, 0.12);
  color: #b45309;
  border: 1px solid rgba(245, 158, 11, 0.35);
}

.adm-badge--xd {
  background: rgba(37, 99, 235, 0.1);
  color: #1d4ed8;
  border: 1px solid rgba(37, 99, 235, 0.3);
}

.adm-btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 6px;
  color: #475569;
  background: #f1f5f9;
  text-decoration: none;
  font-size: 12px;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

.adm-btn-icon:hover {
  background: #e2e8f0;
  color: #0f172a;
}

.adm-btn-icon--edit:hover {
  background: #eff6ff;
  color: #2563eb;
  border-color: #bfdbfe;
}

.adm-btn-icon--del:hover {
  background: #fef2f2;
  color: #dc2626;
  border-color: #fecaca;
}
</style>

<!-- Top Compact Bar (Filter Tabs + Add Button) -->
<div class="adm-compact-bar">
  <div class="adm-pills">
    <a href="<?= url('admin/projects/list.php') ?>" class="adm-pill <?= ($group_filter === 'all' && empty($category_filter)) ? 'active' : '' ?>">
      <i class="fa-solid fa-layer-group"></i> Tất Cả (<?= $total_count ?>)
    </a>
    <a href="<?= url('admin/projects/list.php?group=co_khi') ?>" class="adm-pill adm-pill--gold <?= ($group_filter === 'co_khi' || in_array($category_filter, $co_khi_cats)) ? 'active' : '' ?>">
      <i class="fa-solid fa-hammer"></i> Cơ Khí (<?= $ck_count ?>)
    </a>
    <a href="<?= url('admin/projects/list.php?group=xay_dung') ?>" class="adm-pill adm-pill--blue <?= ($group_filter === 'xay_dung' || in_array($category_filter, $xay_dung_cats)) ? 'active' : '' ?>">
      <i class="fa-solid fa-building"></i> Xây Dựng (<?= $xd_count ?>)
    </a>
  </div>

  <a href="<?= url('admin/projects/add.php') ?>" class="btn-action" style="background: #f59e0b; color: #0f172a; font-weight: 700; padding: 7px 16px; border-radius: 6px; font-size: 13px;">
    <i class="fa-solid fa-plus"></i> Đăng Công Trình
  </a>
</div>

<!-- Search & Category Filter Form -->
<div class="adm-compact-bar" style="padding: 10px 18px; margin-bottom: 16px;">
  <form action="" method="GET" class="adm-filter-row" style="width: 100%; justify-content: space-between;">
    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
      <input type="text" name="search" class="form-control" style="width: 240px; padding: 6px 12px; font-size: 13px; height: 34px;" placeholder="Tìm tên, khách hàng, địa điểm..." value="<?= htmlspecialchars($search_query); ?>">
      
      <select name="category" class="form-control" style="width: 230px; padding: 5px 10px; font-size: 13px; height: 34px;" onchange="this.form.submit()">
        <option value="">-- Lọc theo hạng mục --</option>
        <optgroup label="⚡ 1. CƠ KHÍ XÂY DỰNG">
          <option value="Nhà kết cấu thép" <?= $category_filter === 'Nhà kết cấu thép' ? 'selected' : ''; ?>>Nhà kết cấu thép</option>
          <option value="Cầu thang - Ban công" <?= $category_filter === 'Cầu thang - Ban công' ? 'selected' : ''; ?>>Cầu thang - Ban công</option>
          <option value="Mái tôn - Mái che" <?= $category_filter === 'Mái tôn - Mái che' ? 'selected' : ''; ?>>Mái tôn - Mái che</option>
          <option value="Nhà cơi nới - Gác lửng" <?= $category_filter === 'Nhà cơi nới - Gác lửng' ? 'selected' : ''; ?>>Nhà cơi nới - Lồng cơi</option>
          <option value="Thang thoát hiểm" <?= $category_filter === 'Thang thoát hiểm' ? 'selected' : ''; ?>>Thang thoát hiểm PCCC</option>
          <option value="Nhà xe - Mái che" <?= $category_filter === 'Nhà xe - Mái che' ? 'selected' : ''; ?>>Nhà xe - Mái che</option>
          <option value="Mái kính" <?= $category_filter === 'Mái kính' ? 'selected' : ''; ?>>Mái kính Canopy</option>
          <option value="Sắt mỹ thuật" <?= $category_filter === 'Sắt mỹ thuật' ? 'selected' : ''; ?>>Sắt mỹ thuật</option>
          <option value="Cửa các loại" <?= $category_filter === 'Cửa các loại' ? 'selected' : ''; ?>>Cửa các loại</option>
        </optgroup>
        <optgroup label="🏢 2. XÂY DỰNG & HOÀN THIỆN">
          <option value="Xây nhà trọn gói" <?= $category_filter === 'Xây nhà trọn gói' ? 'selected' : ''; ?>>Xây nhà trọn gói</option>
          <option value="Nội ngoại thất" <?= $category_filter === 'Nội ngoại thất' ? 'selected' : ''; ?>>Nội ngoại thất</option>
          <option value="Cải tạo & Phá dỡ" <?= $category_filter === 'Cải tạo & Phá dỡ' ? 'selected' : ''; ?>>Cải tạo & Phá dỡ</option>
        </optgroup>
      </select>

      <button type="submit" class="btn-action" style="background: #0f172a; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
        <i class="fa-solid fa-magnifying-glass"></i> Tìm
      </button>

      <?php if (!empty($category_filter) || !empty($search_query) || $group_filter !== 'all'): ?>
        <a href="<?= url('admin/projects/list.php') ?>" style="color: #ef4444; font-size: 12.5px; margin-left: 6px; text-decoration: none;">
          <i class="fa-solid fa-xmark"></i> Xóa lọc
        </a>
      <?php endif; ?>
    </div>

    <span style="font-size: 12.5px; color: #64748b;">
      Hiển thị: <strong><?= count($projects) ?></strong> công trình
    </span>
  </form>
</div>

<!-- Compact Table Card -->
<div class="card-table">
  <div class="table-responsive">
    <table class="adm-compact-table">
      <thead>
        <tr>
          <th style="width: 45px; text-align: center;">#</th>
          <th style="width: 65px; text-align: center;">Ảnh</th>
          <th>Tên Công Trình &amp; Địa Điểm</th>
          <th>Hạng Mục Phân Loại</th>
          <th>Khách Hàng / CĐT</th>
          <th>Ngày Đăng</th>
          <th style="text-align: right; width: 110px;">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($projects) > 0): ?>
          <?php foreach ($projects as $item): 
            $isCoKhi = in_array($item['category'], $co_khi_cats);
            $imgSrc = get_project_image_url($item['image']);
          ?>
            <tr>
              <td style="text-align: center; color: #94a3b8; font-weight: 600;"><?= $item['id']; ?></td>
              <td style="text-align: center;">
                <img src="<?= $imgSrc; ?>" alt="Thumb" class="adm-thumb" onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>'">
              </td>
              <td>
                <div style="font-weight: 700; color: #0f172a;"><?= htmlspecialchars($item['title']); ?></div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                  <i class="fa-solid fa-location-dot" style="color: #94a3b8; font-size: 11px;"></i> <?= htmlspecialchars($item['location']); ?>
                </div>
              </td>
              <td>
                <span class="adm-badge <?= $isCoKhi ? 'adm-badge--ck' : 'adm-badge--xd' ?>">
                  <i class="fa-solid <?= $isCoKhi ? 'fa-hammer' : 'fa-building' ?> fa-xs"></i> <?= htmlspecialchars($item['category']); ?>
                </span>
              </td>
              <td style="color: #334155; font-size: 13px;"><?= htmlspecialchars($item['client']); ?></td>
              <td style="color: #64748b; font-size: 12.5px;"><?= date('d/m/Y', strtotime($item['created_at'])); ?></td>
              <td style="text-align: right; white-space: nowrap;">
                <a href="<?= url('project-detail.php?id=' . $item['id']) ?>" target="_blank" class="adm-btn-icon" title="Xem trên Web"><i class="fa-solid fa-eye"></i></a>
                <a href="<?= url('admin/projects/edit.php?id=' . $item['id']) ?>" class="adm-btn-icon adm-btn-icon--edit" title="Chỉnh sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                <a href="<?= url('admin/projects/delete.php?id=' . $item['id']) ?>" class="adm-btn-icon adm-btn-icon--del btn-confirm-delete" title="Xóa công trình"><i class="fa-solid fa-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align: center; color: #64748b; padding: 36px;">
              <i class="fa-solid fa-folder-open" style="font-size: 28px; display: block; margin-bottom: 8px; color: #cbd5e1;"></i>
              Không tìm thấy công trình nào phù hợp với bộ lọc.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
