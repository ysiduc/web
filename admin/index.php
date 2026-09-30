<?php
$page_title = "Dashboard Thống Kê & Quản Trị Hệ Thống";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$stats = [
    'services_count' => 12,
    'projects_count' => 0,
    'projects_ck'    => 0,
    'projects_xd'    => 0,
    'quotes_count'   => 0,
    'contacts_count' => 0,
];

$co_khi_cats = [
    'Nhà kết cấu thép', 'Cầu thang - Ban công', 'Mái tôn - Mái che',
    'Nhà cơi nới - Gác lửng', 'Thang thoát hiểm', 'Nhà xe - Mái che',
    'Mái kính', 'Sắt mỹ thuật', 'Cửa các loại', 'Cơ khí chế tạo', 'Kết cấu thép'
];

if ($db) {
    try {
        $stats['services_count'] = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
        $stats['projects_count'] = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $stats['quotes_count']   = $db->query("SELECT COUNT(*) FROM quotes")->fetchColumn();
        $stats['contacts_count'] = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

        // Count per sector
        $all_projects = $db->query("SELECT category FROM projects")->fetchAll();
        foreach ($all_projects as $p) {
            if (in_array($p['category'], $co_khi_cats)) {
                $stats['projects_ck']++;
            } else {
                $stats['projects_xd']++;
            }
        }
    } catch (Exception $e) {}
}
?>

<!-- 4 Top Stat Cards -->
<div class="stats-grid-admin">
  <div class="stat-card-admin">
    <div class="stat-icon-admin" style="background: rgba(245, 158, 11, 0.15); color: #d97706;">
      <i class="fa-solid fa-screwdriver-wrench"></i>
    </div>
    <div>
      <h3 style="font-size: 28px; margin: 0; color: var(--primary-navy);"><?= $stats['projects_ck']; ?></h3>
      <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Công Trình Cơ Khí (9 Mục)</p>
    </div>
  </div>

  <div class="stat-card-admin">
    <div class="stat-icon-admin" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;">
      <i class="fa-solid fa-trowel-bricks"></i>
    </div>
    <div>
      <h3 style="font-size: 28px; margin: 0; color: var(--primary-navy);"><?= $stats['projects_xd']; ?></h3>
      <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Công Trình Xây Dựng (3 Mục)</p>
    </div>
  </div>

  <div class="stat-card-admin">
    <div class="stat-icon-admin" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
      <i class="fa-solid fa-calculator"></i>
    </div>
    <div>
      <h3 style="font-size: 28px; margin: 0; color: var(--primary-navy);"><?= $stats['quotes_count']; ?></h3>
      <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Yêu Cầu Báo Giá</p>
    </div>
  </div>

  <div class="stat-card-admin">
    <div class="stat-icon-admin" style="background: rgba(139, 92, 246, 0.15); color: #8b5cf6;">
      <i class="fa-solid fa-envelope"></i>
    </div>
    <div>
      <h3 style="font-size: 28px; margin: 0; color: var(--primary-navy);"><?= $stats['contacts_count']; ?></h3>
      <p style="margin: 0; color: var(--text-muted); font-size: 13px;">Khách Hàng Liên Hệ</p>
    </div>
  </div>
</div>

<!-- 2 Action Quick Cards -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
  <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h4 style="margin: 0 0 6px; font-size: 16px; color: var(--primary-navy);">Đăng Công Trình Hoàn Thiện Mới</h4>
      <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Cập nhật hình ảnh và hồ sơ kỹ thuật cho 12 phân loại công trình.</p>
    </div>
    <a href="/test/web_cty/admin/projects/add.php" class="btn btn-primary btn-sm" style="white-space: nowrap;"><i class="fa-solid fa-plus"></i> Thêm Công Trình</a>
  </div>

  <div style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h4 style="margin: 0 0 6px; font-size: 16px; color: var(--primary-navy);">Xem Trước Website Doanh Nghiệp</h4>
      <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Trang chủ, Trang Dịch Vụ, Trang Công Trình &amp; Giới Thiệu.</p>
    </div>
    <a href="/test/web_cty/index.php" target="_blank" class="btn btn-outline btn-sm" style="white-space: nowrap;"><i class="fa-solid fa-arrow-up-right-from-square"></i> Mở Website</a>
  </div>
</div>

<!-- Recent Quotes Table -->
<div class="table-card" style="margin-bottom: 24px;">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-clock-rotate-left" style="color: var(--accent-gold); margin-right: 8px;"></i> Yêu Cầu Báo Giá Gần Đây</h3>
    <a href="/test/web_cty/admin/manage-quotes.php" class="btn btn-outline btn-sm">Xem Tất Cả</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ &amp; Tên Khách Hàng</th>
          <th>Số Điện Thoại</th>
          <th>Dịch Vụ Cần Tư Vấn</th>
          <th>Trạng Thái</th>
          <th>Ngày Gửi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $latest_quotes = [];
        if ($db) {
            try {
                $latest_quotes = $db->query("SELECT * FROM quotes ORDER BY id DESC LIMIT 5")->fetchAll();
            } catch (Exception $e) {}
        }
        if (empty($latest_quotes)):
        ?>
          <tr>
            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">Chưa có yêu cầu báo giá nào mới.</td>
          </tr>
        <?php else: foreach ($latest_quotes as $q): ?>
          <tr>
            <td>#<?= $q['id']; ?></td>
            <td><strong><?= htmlspecialchars($q['fullname']); ?></strong></td>
            <td><?= htmlspecialchars($q['phone']); ?></td>
            <td><?= htmlspecialchars($q['service_type']); ?></td>
            <td><span class="badge-status badge-new"><?= htmlspecialchars($q['status']); ?></span></td>
            <td><?= format_date($q['created_at']); ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Recent Projects Table -->
<div class="table-card">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-building" style="color: #2563eb; margin-right: 8px;"></i> Công Trình Đã Hoàn Thiện Vừa Đăng</h3>
    <a href="/test/web_cty/admin/projects/list.php" class="btn btn-outline btn-sm">Quản Lý Tất Cả Công Trình</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên Công Trình</th>
          <th>Phân Loại</th>
          <th>Khách Hàng / CĐT</th>
          <th>Địa Điểm</th>
          <th>Ngày Tạo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $recent_proj = [];
        if ($db) {
            try {
                $recent_proj = $db->query("SELECT * FROM projects ORDER BY id DESC LIMIT 5")->fetchAll();
            } catch (Exception $e) {}
        }
        if (empty($recent_proj)):
        ?>
          <tr>
            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">Chưa có công trình nào.</td>
          </tr>
        <?php else: foreach ($recent_proj as $rp): 
          $isCk = in_array($rp['category'], $co_khi_cats);
        ?>
          <tr>
            <td>#<?= $rp['id']; ?></td>
            <td><strong><?= htmlspecialchars($rp['title']); ?></strong></td>
            <td>
              <span class="badge-status <?= $isCk ? 'badge-completed' : 'badge-new' ?>">
                <?= $isCk ? '⚡ Cơ khí:' : '🏢 Xây dựng:' ?> <?= htmlspecialchars($rp['category']); ?>
              </span>
            </td>
            <td><?= htmlspecialchars($rp['client']); ?></td>
            <td><?= htmlspecialchars($rp['location']); ?></td>
            <td><?= format_date($rp['created_at']); ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
