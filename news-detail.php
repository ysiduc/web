<?php
$page_title = "Chi Tiết Bài Viết - PNMEC";
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = getDBConnection();
$news_item = null;

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $news_item = $stmt->fetch();
    } catch (Exception $e) {}
}

if (!$news_item) {
    $news_item = [
        'title' => 'PNMEC Hoàn Thành Bàn Giao Dự Án Nhà Xưởng KCN Yên Phong',
        'summary' => 'Dự án nhà xưởng kết cấu thép quy mô 25.000m2 cho đối tác Samsung đã hoàn thành vượt tiến độ 15 ngày.',
        'content' => 'Nhà xưởng sản xuất quy mô 25.000m2 đạt tiêu chuẩn kỹ thuật nghiêm ngặt đã chính thức bàn giao cho chủ đầu tư. Toàn bộ hệ thống dầm khung thép được gia công cắt CNC chính xác, phun sơn 3 lớp chống rỉ chống cháy tiêu chuẩn PCCC.',
        'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=1200&q=80',
        'author' => 'Ban Biền Tập PNMEC',
        'created_at' => '2026-02-10'
    ];
}
?>

<div class="page-banner">
  <div class="container">
    <h1><?php echo htmlspecialchars($news_item['title']); ?></h1>
    <div class="breadcrumb">
      <a href="/test/web_cty/index.php">Trang chủ</a> / <a href="/test/web_cty/news.php">Tin tức</a> / <span>Bài viết</span>
    </div>
  </div>
</div>

<section style="padding: 60px 0; background: #fff;">
  <div class="container" style="max-width: 860px; margin: 0 auto;">
    <span style="color: var(--accent-gold); font-weight: 700; font-size: 13px;"><i class="fa-regular fa-calendar-days"></i> <?php echo format_date($news_item['created_at']); ?> | Tác giả: <?php echo htmlspecialchars($news_item['author']); ?></span>
    <h1 style="font-size: 32px; margin: 10px 0 20px; color: var(--primary-navy);"><?php echo htmlspecialchars($news_item['title']); ?></h1>
    
    <p style="font-size: 16px; font-weight: 600; color: var(--text-main); margin-bottom: 24px; line-height: 1.6; font-style: italic;">
      <?php echo htmlspecialchars($news_item['summary']); ?>
    </p>

    <img src="<?php echo htmlspecialchars($news_item['image']); ?>" alt="<?php echo htmlspecialchars($news_item['title']); ?>" style="width: 100%; height: 420px; object-fit: cover; border-radius: 12px; margin-bottom: 30px;">

    <div style="font-size: 16px; color: var(--text-main); line-height: 1.8;">
      <?php echo nl2br(htmlspecialchars($news_item['content'])); ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
