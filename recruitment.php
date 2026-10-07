<?php
$page_title = "Tuyển Dụng Nhân Sự";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$recruitments = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM recruitments WHERE status = 'published' ORDER BY id DESC");
        $recruitments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $recruitments = [];
    }
}
?>

<!-- Page Banner -->
<section class="page-banner">
  <div class="container">
    <h1>Tuyển Dụng Nhân Tài - Phát Triển Sự Nghiệp</h1>
    <div class="breadcrumb">
      <a href="<?= url('index.php') ?>">Trang chủ</a> / <span>Tuyển dụng</span>
    </div>
  </div>
</section>

<!-- Recruitment Overview -->
<section class="recruitment-section">
  <div class="container">
    <div class="section-title" style="text-align: center; margin-bottom: 40px;">
      <span style="color: var(--accent-gold); font-weight: 700; text-transform: uppercase; font-size: 13px;">Cơ Hội Việc Làm</span>
      <h2 style="font-size: 30px; margin-top: 6px;">Các Vị Trí Đang Tuyển Dụng</h2>
      <p style="color: var(--text-muted); font-size: 14px; max-width: 600px; margin: 8px auto 0;">Gia nhập đội ngũ PNMEC để làm việc trong môi trường chuyên nghiệp, đãi ngộ hấp dẫn và cơ hội thăng tiến rộng mở.</p>
    </div>

    <!-- Vacancy Grid / Empty State -->
    <?php if (empty($recruitments)): ?>
      <div class="vacancy-empty-state" style="text-align: center; padding: 48px 24px; background: var(--bg-light); border: 1px dashed var(--border-color); border-radius: 12px; margin-bottom: 50px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(245, 158, 11, 0.12); color: var(--accent-gold); display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px;">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <h3 style="font-size: 20px; color: var(--primary-navy); margin-bottom: 8px;">Hiện tại chưa có đợt tuyển dụng mới</h3>
        <p style="color: var(--text-muted); font-size: 14px; max-width: 560px; margin: 0 auto;">PNMEC luôn hoan nghênh những nhân tài có chuyên môn kỹ thuật và nhiệt huyết. Quý ứng viên quan tâm có thể chủ động gửi thông tin và vị trí mong muốn qua mẫu đăng ký bên dưới. Bộ phận Nhân sự sẽ lưu trữ và liên hệ ngay khi có cơ hội phù hợp.</p>
      </div>
    <?php else: ?>
      <div class="vacancy-grid">
        <?php foreach ($recruitments as $item): ?>
          <?php
          $display_title = htmlspecialchars($item['title']);
          if ((int)$item['quantity'] > 1 && !preg_match('/^\d+/', $item['title'])) {
              $display_title = sprintf('%02d ', (int)$item['quantity']) . $display_title;
          }

          $bullets = [];
          if (!empty($item['description'])) {
              $desc_lines = preg_split('/[\r\n]+/', trim($item['description']));
              foreach ($desc_lines as $line) {
                  $clean = trim(ltrim(trim($line), '•-* '));
                  if ($clean !== '') {
                      $bullets[] = $clean;
                  }
              }
          }
          if (!empty($item['requirements'])) {
              $req_lines = preg_split('/[\r\n]+/', trim($item['requirements']));
              foreach ($req_lines as $line) {
                  $clean = trim(ltrim(trim($line), '•-* '));
                  if ($clean !== '') {
                      $bullets[] = $clean;
                  }
              }
          }
          ?>
          <div class="vacancy-card">
            <span class="vacancy-badge"><?= htmlspecialchars($item['employment_type'] ?: 'Toàn thời gian') ?></span>
            <h3 class="vacancy-title"><?= $display_title ?></h3>
            <div class="vacancy-meta">
              <?php if (!empty($item['salary'])): ?>
                <span><i class="fa-solid fa-money-bill-wave"></i> <?= htmlspecialchars($item['salary']) ?></span>
              <?php endif; ?>
              <?php if (!empty($item['salary']) && !empty($item['location'])): ?> | <?php endif; ?>
              <?php if (!empty($item['location'])): ?>
                <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($item['location']) ?></span>
              <?php endif; ?>
              <?php if ((int)$item['quantity'] > 0): ?>
                | <span><i class="fa-solid fa-users"></i> <?= (int)$item['quantity'] ?> chỉ tiêu</span>
              <?php endif; ?>
            </div>

            <?php if (!empty($bullets)): ?>
              <ul class="vacancy-list">
                <?php foreach (array_slice($bullets, 0, 4) as $bullet): ?>
                  <li>• <?= htmlspecialchars($bullet) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <a href="#apply-form" class="btn btn-navy btn-sm" onclick="selectPosition(<?= htmlspecialchars(json_encode($item['title']), ENT_QUOTES, 'UTF-8') ?>)">
              <i class="fa-solid fa-paper-plane"></i> Ứng Tuyển Ngay
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Application Form Block -->
    <div id="apply-form" class="apply-form-box">
      <h3 class="apply-form-title">Nộp Hồ Sơ Ứng Tuyển Trực Tuyến</h3>
      <p class="apply-form-sub">Bộ phận Nhân sự sẽ liên hệ phỏng vấn trong vòng 48 giờ làm việc.</p>

      <form action="<?= url('contact.php?applied=true') ?>" method="POST">
        <div class="form-grid-2col">
          <div class="form-group">
            <label>Họ và tên ứng viên <span style="color: red;">*</span></label>
            <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
          </div>
          <div class="form-group">
            <label>Số điện thoại <span style="color: red;">*</span></label>
            <input type="tel" name="phone" class="form-control" placeholder="09xxxxxxxx" required>
          </div>
        </div>

        <div class="form-grid-2col">
          <div class="form-group">
            <label>Email cá nhân</label>
            <input type="email" name="email" class="form-control" placeholder="ungvien@gmail.com">
          </div>
          <div class="form-group">
            <label>Vị trí ứng tuyển <span style="color: red;">*</span></label>
            <select id="position-select" name="position" class="form-control" required>
              <?php if (!empty($recruitments)): ?>
                <?php foreach ($recruitments as $rec): ?>
                  <option value="<?= htmlspecialchars($rec['title']) ?>"><?= htmlspecialchars($rec['title']) ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
              <option value="Vị trí khác" <?= empty($recruitments) ? 'selected' : '' ?>>Vị trí khác</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Tóm tắt kinh nghiệm làm việc & Ghi chú</label>
          <textarea name="experience" class="form-control" placeholder="Mô tả ngắn kinh nghiệm làm việc hoặc đính kèm link CV của bạn..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-paper-plane"></i> Gửi Hồ Sơ Ứng Tuyển</button>
      </form>
    </div>

  </div>
</section>

<script>
function selectPosition(title) {
  var sel = document.getElementById('position-select');
  if (!sel) return;
  for (var i = 0; i < sel.options.length; i++) {
    if (sel.options[i].value === title) {
      sel.selectedIndex = i;
      break;
    }
  }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
