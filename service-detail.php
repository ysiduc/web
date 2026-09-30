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
      <a href="/test/web_cty/index.php">Trang chủ</a> / <a href="/test/web_cty/services.php">Dịch vụ</a> / <span>Chi tiết</span>
    </div>
  </div>
</div>

<section style="padding: 60px 0; background: #fff;">
  <div class="container" style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 40px;">
    <div>
      <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" style="width: 100%; height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 30px;">
      
      <h2 style="font-size: 28px; margin-bottom: 16px; color: var(--primary-navy);"><?php echo htmlspecialchars($service['title']); ?></h2>
      <p style="font-size: 16px; font-weight: 600; color: var(--accent-gold); margin-bottom: 24px; line-height: 1.6;">
        <?php echo htmlspecialchars($service['summary']); ?>
      </p>

      <div style="font-size: 15px; color: var(--text-main); line-height: 1.8;">
        <?php echo nl2br(htmlspecialchars($service['content'])); ?>
      </div>

      <div style="margin-top: 40px; padding: 30px; background: var(--bg-light); border-radius: 12px; border-left: 4px solid var(--accent-gold);">
        <h3 style="font-size: 18px; margin-bottom: 10px;">Bạn cần tư vấn giải pháp kỹ thuật cho dự án?</h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">Liên hệ ngay đội ngũ kỹ sư PNMEC để nhận hỗ trợ khảo sát và phương án thi công tối ưu.</p>
        <a href="/test/web_cty/quote.php" class="btn btn-primary"><i class="fa-solid fa-calculator"></i> Nhận Báo Giá Nhanh</a>
      </div>
    </div>

    <div>
      <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
