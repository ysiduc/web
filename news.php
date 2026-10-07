<?php
$page_title = "Tin Tức & Hoạt Động Doanh Nghiệp - PNMEC";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$news_list = [];
if ($db) {
    try {
        $news_list = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}
?>

<div class="page-banner">
  <div class="container">
    <h1>Tin Tức & Sự Kiện</h1>
    <div class="breadcrumb">
      <a href="<?= url('index.php') ?>">Trang chủ</a> / <span>Tin tức</span>
    </div>
  </div>
</div>

<section class="news-section">
  <div class="container">
    <?php if (!empty($news_list)): ?>
    <div class="news-grid">
      <?php foreach ($news_list as $n): ?>
        <article class="news-card">
          <img src="<?php echo htmlspecialchars(get_news_image_url($n['image'])); ?>" alt="<?php echo htmlspecialchars($n['title']); ?>" class="news-card-img" loading="lazy" onerror="this.onerror=null;this.src='<?= asset_url('images/service-cons.png') ?>';">
          <div class="news-card-body">
            <span class="news-card-date"><i class="fa-regular fa-calendar-days"></i> <?php echo format_date($n['created_at']); ?></span>
            <h2 class="news-card-title">
              <a href="<?= url('news-detail.php?id=' . (int)$n['id']) ?>"><?php echo htmlspecialchars($n['title']); ?></a>
            </h2>
            <p class="news-card-desc"><?php echo htmlspecialchars($n['summary']); ?></p>
            <a href="<?= url('news-detail.php?id=' . (int)$n['id']) ?>" class="news-card-link">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="news-empty-state" style="text-align: center; padding: 60px 20px; color: var(--text-muted, #94a3b8); font-size: 15px;">
      <i class="fa-regular fa-newspaper" style="font-size: 40px; margin-bottom: 14px; display: block; opacity: 0.5;"></i>
      <h3 style="font-size: 20px; margin-bottom: 8px; color: var(--primary-navy, #0f172a);">Chưa Có Tin Tức Nào Được Đăng</h3>
      <p style="margin: 0;">Hiện chưa có bài viết tin tức hoặc sự kiện nào trong hệ thống.</p>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
