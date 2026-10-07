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

<section style="padding: 60px 0; background: var(--bg-light);">
  <div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
      <?php foreach ($news_list as $n): ?>
        <div style="background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); display: flex; flex-direction: column;">
          <img src="<?php echo htmlspecialchars($n['image']); ?>" alt="<?php echo htmlspecialchars($n['title']); ?>" style="height: 200px; width: 100%; object-fit: cover;">
          <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
            <span style="font-size: 12px; color: var(--accent-gold); font-weight: 700; margin-bottom: 6px;"><i class="fa-regular fa-calendar-days"></i> <?php echo format_date($n['created_at']); ?></span>
            <h3 style="font-size: 18px; margin-bottom: 10px; line-height: 1.4;">
              <a href="<?= url('news-detail.php?id=' . (int)$n['id']) ?>"><?php echo htmlspecialchars($n['title']); ?></a>
            </h3>
            <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;"><?php echo htmlspecialchars($n['summary']); ?></p>
            <a href="<?= url('news-detail.php?id=' . (int)$n['id']) ?>" style="margin-top: auto; font-weight: 700; color: var(--primary-navy); font-size: 14px;">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
