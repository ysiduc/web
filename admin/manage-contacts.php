<?php
$page_title = "Quản Lý Khách Hàng Liên Hệ";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$contacts = [];
if ($db) {
    try {
        $contacts = $db->query("SELECT * FROM contacts ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<div class="table-card">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-envelope-open-text" style="color: var(--accent-gold); margin-right: 8px;"></i> Danh Sách Khách Hàng Gửi Liên Hệ</h3>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ & Tên</th>
          <th>Email</th>
          <th>Số Điện Thoại</th>
          <th>Tiêu Đề</th>
          <th>Trạng Thái</th>
          <th>Ngày Gửi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($contacts)): ?>
          <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Chưa có liên hệ nào từ khách hàng.</td></tr>
        <?php else: foreach ($contacts as $c): ?>
          <tr>
            <td>#<?php echo $c['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
            <td><a href="mailto:<?php echo htmlspecialchars($c['email']); ?>"><?php echo htmlspecialchars($c['email']); ?></a></td>
            <td><?php echo htmlspecialchars($c['phone'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($c['subject'] ?? 'Tư vấn'); ?></td>
            <td><span class="badge-status badge-new"><?php echo htmlspecialchars($c['status']); ?></span></td>
            <td><?php echo format_date($c['created_at']); ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
