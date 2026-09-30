<?php
$page_title = "Quản Lý Bài Viết Tin Tức";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$news = [];
if ($db) {
    try {
        $news = $db->query("SELECT * FROM news ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}
?>
<div class="table-card">
  <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; font-size: 18px;"><i class="fa-solid fa-newspaper" style="color: var(--accent-gold); margin-right: 8px;"></i> Danh Sách Bài Viết Tin Tức</h3>
    <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Đăng Bài Viết Mới</button>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tiêu Đề Bài Viết</th>
          <th>Tác Giả</th>
          <th>Lượt Xem</th>
          <th>Ngày Đăng</th>
          <th>Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($news)): ?>
          <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">Chưa có bài viết tin tức nào.</td></tr>
        <?php else: foreach ($news as $n): ?>
          <tr>
            <td>#<?php echo $n['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($n['title']); ?></strong></td>
            <td><?php echo htmlspecialchars($n['author']); ?></td>
            <td><?php echo number_format($n['views']); ?></td>
            <td><?php echo format_date($n['created_at']); ?></td>
            <td>
              <button class="btn btn-sm btn-outline"><i class="fa-solid fa-pen-to-square"></i></button>
              <button class="btn btn-sm btn-confirm-delete" style="color: #ef4444; border-color: #ef4444;"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
