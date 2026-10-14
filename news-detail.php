<?php
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = getDBConnection();
$news_item = null;

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM news WHERE id = :id AND status = 'published' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $news_item = $stmt->fetch();
        if ($news_item) {
            $db->query("UPDATE news SET views = views + 1 WHERE id = " . $id);
        }
    } catch (Exception $e) {}
}

if ($news_item) {
    $page_title = $news_item['title'];
    $page_description = !empty($news_item['summary']) ? $news_item['summary'] : mb_substr(strip_tags($news_item['content']), 0, 160, 'UTF-8');
    $page_image = get_news_image_url($news_item['image']);
    $page_canonical = absolute_url('/news-detail?id=' . (int)$news_item['id']);
    $page_robots = 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $page_og_type = 'article';
    $article_published_time = !empty($news_item['created_at']) ? date('c', strtotime($news_item['created_at'])) : date('c');
    $article_author = !empty($news_item['author']) ? $news_item['author'] : 'PNMEC';

    $schema_data = [
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        '@id'              => absolute_url('/news-detail?id=' . (int)$news_item['id'] . '#article'),
        'headline'         => $news_item['title'],
        'description'      => $page_description,
        'image'            => [absolute_url($page_image)],
        'datePublished'    => $article_published_time,
        'dateModified'     => $article_published_time,
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => $page_canonical
        ],
        'author'           => [
            '@type' => 'Person',
            'name'  => $article_author
        ],
        'publisher'        => [
            '@id' => 'https://pnmec.vn/#organization'
        ]
    ];
} else {
    http_response_code(404);
    $page_title = "Không Tìm Thấy Bài Viết";
    $page_description = "Bài viết bạn đang tìm kiếm không tồn tại hoặc đã bị gỡ bỏ trên hệ thống PNMEC.";
    $page_robots = 'noindex, follow';
    $page_canonical = absolute_url('/news');
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($news_item): ?>
<div class="page-banner">
  <div class="container">
    <div style="font-size: 28px; font-weight: 700; margin-bottom: 8px;"><?php echo htmlspecialchars($news_item['title']); ?></div>
    <div class="breadcrumb">
      <a href="<?= url('/') ?>">Trang chủ</a> / <a href="<?= url('/news') ?>">Tin tức</a> / <span>Bài viết</span>
    </div>
  </div>
</div>

<section class="news-detail-section">
  <div class="container">
    <article class="news-detail-article">
      <div class="news-detail-meta">
        <i class="fa-regular fa-calendar-days"></i> <?php echo format_date($news_item['created_at']); ?> | Tác giả: <?php echo htmlspecialchars($news_item['author'] ?? 'PNMEC'); ?>
      </div>
      <h1 class="news-detail-title"><?php echo htmlspecialchars($news_item['title']); ?></h1>
      
      <p class="news-detail-lead">
        <?php echo htmlspecialchars($news_item['summary']); ?>
      </p>

      <img src="<?php echo htmlspecialchars(get_news_image_url($news_item['image'])); ?>" alt="<?php echo htmlspecialchars($news_item['title']); ?>" class="news-detail-img" onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">

      <div class="news-detail-body">
        <?php echo nl2br(htmlspecialchars($news_item['content'])); ?>
      </div>
    </article>
  </div>
</section>
<?php else: ?>
<div class="page-banner">
  <div class="container">
    <h1>Không Tìm Thấy Bài Viết</h1>
    <div class="breadcrumb">
      <a href="<?= url('/') ?>">Trang chủ</a> / <a href="<?= url('/news') ?>">Tin tức</a> / <span>Không tìm thấy</span>
    </div>
  </div>
</div>

<section style="padding: 80px 20px; text-align: center;">
  <div class="container">
    <i class="fa-regular fa-newspaper" style="font-size: 48px; color: #94a3b8; margin-bottom: 20px; display: block; opacity: 0.6;"></i>
    <h2 style="font-size: 22px; color: var(--primary-navy, #0f172a); margin-bottom: 8px;">Bài viết không tồn tại hoặc đã bị gỡ bỏ</h2>
    <p style="color: #64748b; max-width: 500px; margin: 10px auto 25px;">Nội dung bạn đang tìm kiếm hiện không khả dụng trong hệ thống.</p>
    <a href="<?= url('/news') ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tin tức</a>
  </div>
</section>
<?php endif; ?>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
