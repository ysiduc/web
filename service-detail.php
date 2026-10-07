<?php
$page_title = "Chi Tiết Dịch Vụ - PNMEC";
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$db = getDBConnection();
$service = null;

if ($db && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch();
    } catch (Exception $e) {}
}

if (!$service) {
    $service = [
        'title' => 'Thi Công Khung Kèo Kết Cấu Thép Khẩu Độ Thép Lớn',
        'code' => 'PNMEC-CONS',
        'summary' => 'Sản xuất & thi công lắp dựng khung nhà xưởng kết cấu thép khẩu độ lớn đạt chuẩn chất lượng quốc tế.',
        'content' => 'PNMEC tự hào là đơn vị tổng thầu thi công kết cấu thép nhà xưởng hàng đầu miền Bắc. Hệ thống xưởng sản xuất hiện đại 15.000m2 trang bị máy cắt Laser Fiber, máy gá dầm tổ hợp tự động và dây chuyền phun sơn khép kín.',
        'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=1200&q=80'
    ];
}
?>

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
           onerror="this.onerror=null;this.src='<?= asset_url('images/service-cons.png') ?>';">
      
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


<?php require_once __DIR__ . '/includes/footer.php'; ?>
