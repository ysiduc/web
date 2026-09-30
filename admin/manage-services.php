<?php
$page_title = "Quản Lý Dịch Vụ Thi Công";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$services = [];
if ($db) {
    try {
        $services = $db->query("SELECT * FROM services ORDER BY id ASC")->fetchAll();
    } catch (Exception $e) {}
}

$co_khi_services = array_filter($services, function($s) {
    return strpos($s['code'] ?? '', 'CK') === 0;
});

$xay_dung_services = array_filter($services, function($s) {
    return strpos($s['code'] ?? '', 'XD') === 0;
});
?>
<div class="table-card" style="margin-bottom: 30px;">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
      <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-screwdriver-wrench" style="color: var(--accent-gold); margin-right: 8px;"></i> 1. Danh Sách Dịch Vụ Cơ Khí Xây Dựng (<?= count($co_khi_services) ?> Hạng Mục)</h3>
      <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted);">Phục vụ gia công kết cấu thép, thang thoát hiểm, mái tôn, cầu thang, ban công, sắt mỹ thuật...</p>
    </div>
    <a href="/test/web_cty/services.php#co-khi" target="_blank" class="btn btn-sm btn-outline"><i class="fa-solid fa-globe"></i> Xem Trên Website</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã Dịch Vụ</th>
          <th>Tên Dịch Vụ Cơ Khí</th>
          <th>Tóm Tắt Giải Pháp</th>
          <th>Nổi Bật</th>
          <th>Lượt Xem</th>
          <th style="text-align: right;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($co_khi_services as $s): ?>
          <tr>
            <td><code style="background: rgba(245,158,11,0.15); color: #b45309; padding: 3px 8px; border-radius: 4px; font-weight: 700;"><?= htmlspecialchars($s['code']); ?></code></td>
            <td><strong><?= htmlspecialchars($s['title']); ?></strong></td>
            <td style="max-width: 360px; font-size: 13px; color: #64748b;"><?= htmlspecialchars($s['summary']); ?></td>
            <td><?= $s['featured'] ? '<span class="badge-status badge-completed">Nổi bật</span>' : '<span style="color:#94a3b8; font-size:12px;">Thường</span>'; ?></td>
            <td><?= number_format($s['views']); ?></td>
            <td style="text-align: right; white-space: nowrap;">
              <a href="/test/web_cty/services.php#co-khi" target="_blank" class="btn btn-sm btn-outline"><i class="fa-solid fa-eye"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="table-card">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
      <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-trowel-bricks" style="color: #2563eb; margin-right: 8px;"></i> 2. Danh Sách Dịch Vụ Xây Dựng &amp; Hoàn Thiện (<?= count($xay_dung_services) ?> Hạng Mục)</h3>
      <p style="margin: 4px 0 0; font-size: 13px; color: var(--text-muted);">Phục vụ tổng thầu xây nhà trọn gói, hoàn thiện nội ngoại thất và cải tạo phá dỡ công trình.</p>
    </div>
    <a href="/test/web_cty/services.php#xay-dung" target="_blank" class="btn btn-sm btn-outline"><i class="fa-solid fa-globe"></i> Xem Trên Website</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Mã Dịch Vụ</th>
          <th>Tên Dịch Vụ Xây Dựng</th>
          <th>Tóm Tắt Giải Pháp</th>
          <th>Nổi Bật</th>
          <th>Lượt Xem</th>
          <th style="text-align: right;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($xay_dung_services as $s): ?>
          <tr>
            <td><code style="background: rgba(37,99,235,0.12); color: #2563eb; padding: 3px 8px; border-radius: 4px; font-weight: 700;"><?= htmlspecialchars($s['code']); ?></code></td>
            <td><strong><?= htmlspecialchars($s['title']); ?></strong></td>
            <td style="max-width: 360px; font-size: 13px; color: #64748b;"><?= htmlspecialchars($s['summary']); ?></td>
            <td><?= $s['featured'] ? '<span class="badge-status badge-completed">Nổi bật</span>' : '<span style="color:#94a3b8; font-size:12px;">Thường</span>'; ?></td>
            <td><?= number_format($s['views']); ?></td>
            <td style="text-align: right; white-space: nowrap;">
              <a href="/test/web_cty/services.php#xay-dung" target="_blank" class="btn btn-sm btn-outline"><i class="fa-solid fa-eye"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
