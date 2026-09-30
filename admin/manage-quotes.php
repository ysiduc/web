<?php
$page_title = "Quản Lý Yêu Cầu Báo Giá";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$quotes = [];
if ($db) {
    try {
        $quotes = $db->query("SELECT * FROM quotes ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<div class="table-card">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-calculator" style="color: var(--accent-gold); margin-right: 8px;"></i> Danh Sách Yêu Cầu Báo Giá Từ Khách Hàng</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ & Tên</th>
          <th>Số Điện Thoại</th>
          <th>Email</th>
          <th>Loại Dịch Vụ</th>
          <th>Ngày Gửi</th>
          <th>Trạng Thái</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($quotes)): ?>
          <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Chưa có dữ liệu báo giá.</td></tr>
        <?php else: foreach ($quotes as $q): ?>
          <tr>
            <td>#<?php echo $q['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($q['fullname']); ?></strong></td>
            <td><a href="tel:<?php echo htmlspecialchars($q['phone']); ?>"><?php echo htmlspecialchars($q['phone']); ?></a></td>
            <td><?php echo htmlspecialchars($q['email'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($q['service_type']); ?></td>
            <td><?php echo format_date($q['created_at']); ?></td>
            <td><span class="badge-status badge-new"><?php echo htmlspecialchars($q['status']); ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
