<?php
$page_title = "Tin Tức & Hoạt Động Doanh Nghiệp - PNMEC";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$news_list = [];
if ($db) {
    try {
        $news_list = $db->query("SELECT * FROM news ORDER BY id DESC")->fetchAll();
    } catch (Exception $e) {}
}

if (empty($news_list)) {
    $news_list = [
        [
            'id' => 1,
            'title' => 'PNMEC Hoàn Thành Bàn Giao Dự Án Nhà Xưởng KCN Yên Phong',
            'summary' => 'Dự án nhà xưởng kết cấu thép quy mô 25.000m2 cho đối tác Samsung đã hoàn thành vượt tiến độ 15 ngày.',
            'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=600&q=80',
            'created_at' => '2026-02-10'
        ],
        [
            'id' => 2,
            'title' => 'Đầu Tư Dây Chuyền Cắt Laser Fiber Công Suất 20KW Mới',
            'summary' => 'PNMEC chính thức đưa vào vận hành hệ thống máy gia công cắt tấm kim loại chính xác cao công nghệ Châu Âu.',
            'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80',
            'created_at' => '2026-01-18'
        ],
        [
            'id' => 3,
            'title' => 'Hội Nghị Khách Hàng & Vinh Danh Đối Tác Chiến Lược PNMEC',
            'summary' => 'Sự kiện thường niên tri ân các đơn vị đối tác, nhà thầu phụ và khách hàng đồng hành trong năm qua.',
            'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=600&q=80',
            'created_at' => '2025-12-25'
        ]
    ];
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
    <div class="news-grid">
      <?php foreach ($news_list as $n): ?>
        <article class="news-card">
          <img src="<?php echo htmlspecialchars($n['image']); ?>" alt="<?php echo htmlspecialchars($n['title']); ?>" class="news-card-img" loading="lazy">
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
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
