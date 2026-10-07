<?php
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = getDBConnection();
$service = null;

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM services WHERE id = :id AND status = 'active' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch();
        if ($service) {
            $db->query("UPDATE services SET views = views + 1 WHERE id = " . $id);
        }
    } catch (Exception $e) {}
}

$page_title = $service ? $service['title'] : "Không Tìm Thấy Dịch Vụ";
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($service): ?>
<div class="page-banner">
  <div class="container">
    <h1><?php echo htmlspecialchars($service['title']); ?></h1>
    <div class="breadcrumb">
      <a href="<?= url('index.php') ?>">Trang chủ</a> / <a href="<?= url('services.php') ?>">Dịch vụ</a> / <span>Chi tiết</span>
    </div>
  </div>
</div>

<section class="service-detail-section">
  <div class="container service-detail-container">
    <div class="service-detail-main">
      <img src="<?php echo htmlspecialchars(get_service_image_url($service['image'])); ?>" 
           alt="<?php echo htmlspecialchars($service['title']); ?>" 
           class="service-detail-img"
           onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">
      
      <h2 class="service-detail-title"><?php echo htmlspecialchars($service['title']); ?></h2>
      <p class="service-detail-summary">
        <?php echo htmlspecialchars($service['summary']); ?>
      </p>

      <div class="service-detail-content">
        <?php echo nl2br(htmlspecialchars($service['content'])); ?>
      </div>

      <div class="service-cta-card">
        <h3>Bạn cần tư vấn giải pháp kỹ thuật cho dự án?</h3>
        <p>Liên hệ ngay đội ngũ kỹ sư PNMEC để nhận hỗ trợ khảo sát và phương án thi công tối ưu.</p>
        <a href="<?= url('quote.php') ?>" class="btn btn-primary service-cta-btn"><i class="fa-solid fa-calculator"></i> Nhận Báo Giá Nhanh</a>
      </div>
    </div>

    <aside class="service-detail-sidebar">
      <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    </aside>
  </div>
</section>
<?php else: ?>
<div class="page-banner">
  <div class="container">
    <h1>Không Tìm Thấy Dịch Vụ</h1>
    <div class="breadcrumb">
      <a href="<?= url('index.php') ?>">Trang chủ</a> / <a href="<?= url('services.php') ?>">Dịch vụ</a> / <span>Không tìm thấy</span>
    </div>
  </div>
</div>

<section style="padding: 80px 20px; text-align: center;">
  <div class="container">
    <i class="fa-solid fa-screwdriver-wrench" style="font-size: 48px; color: #94a3b8; margin-bottom: 20px; display: block; opacity: 0.6;"></i>
    <h2 style="font-size: 22px; color: var(--primary-navy, #0f172a); margin-bottom: 8px;">Dịch vụ không tồn tại hoặc đã tạm dừng</h2>
    <p style="color: #64748b; max-width: 500px; margin: 10px auto 25px;">Dịch vụ bạn đang tìm kiếm không tồn tại hoặc đã ngừng cung cấp.</p>
    <a href="<?= url('services.php') ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách dịch vụ</a>
  </div>
</section>
<?php endif; ?>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
