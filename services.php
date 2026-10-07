<?php
$page_title = "Dịch Vụ Thiết Kế & Thi Công Cơ Khí - Xây Dựng";
require_once __DIR__ . '/includes/header.php';

$db = getDBConnection();
$services = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT id, title, slug, code, summary, content, image, featured, views, status, created_at FROM services WHERE status = 'active' ORDER BY id ASC");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}

$co_khi_services = [];
$xay_dung_services = [];

foreach ($services as $s) {
    $code = strtoupper(trim($s['code'] ?? ''));
    if (str_starts_with($code, 'XD')) {
        $xay_dung_services[] = $s;
    } else {
        $co_khi_services[] = $s;
    }
}
?>

<!-- ═══════════════════════════════════════════════════
     1. HERO BANNER
═══════════════════════════════════════════════════ -->
<section class="srv-hero">
  <div class="container">
    <div class="srv-hero__inner">
      <div class="srv-badge">
        <i class="fa-solid fa-gears"></i> DỊCH VỤ TOÀN DIỆN
      </div>
      <h1 class="srv-hero__title">Dịch Vụ Cơ Khí &amp; Xây Dựng</h1>
      <p class="srv-hero__subtitle">
        Giải pháp trọn gói từ gia công chế tạo kết cấu thép chính xác đến tổng thầu thi công xây dựng dân dụng tiêu chuẩn cao.
      </p>
      
      <!-- Quick Nav Pills -->
      <div class="srv-quick-nav">
        <a href="#co-khi" class="srv-nav-pill srv-nav-pill--gold">
          <i class="fa-solid fa-hammer"></i> Cơ Khí Xây Dựng (<?= count($co_khi_services) ?> Dịch Vụ)
        </a>
        <a href="#xay-dung" class="srv-nav-pill srv-nav-pill--blue">
          <i class="fa-solid fa-building"></i> Xây Dựng Dân Dụng (<?= count($xay_dung_services) ?> Dịch Vụ)
        </a>
        <a href="#quy-trinh" class="srv-nav-pill srv-nav-pill--outline">
          <i class="fa-solid fa-list-check"></i> Quy Trình 5 Bước
        </a>
      </div>

      <div class="srv-breadcrumb">
        <a href="<?= url('index.php') ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span>Dịch vụ</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     2. PHẦN 1: DỊCH VỤ CƠ KHÍ XÂY DỰNG
═══════════════════════════════════════════════════ -->
<section class="srv-section" id="co-khi">
  <div class="container srv-container">
    
    <!-- Section Head -->
    <div class="srv-sec-head">
      <div class="srv-sec-badge">
        <i class="fa-solid fa-screwdriver-wrench"></i> LĨNH VỰC 01
      </div>
      <h2 class="srv-sec-title">DỊCH VỤ CƠ KHÍ XÂY DỰNG</h2>
      <p class="srv-sec-desc">
        Gia công chế tạo chính xác, gia cường cấu kiện và thi công lắp dựng các hạng mục cơ khí kết cấu thép bền bỉ theo tiêu chuẩn kỹ thuật cao.
      </p>
      <div class="srv-sec-divider"></div>
    </div>

    <?php if (!empty($co_khi_services)): ?>
    <div class="srv-grid srv-grid--3cols">
      <?php foreach ($co_khi_services as $item): 
        $desc = !empty($item['summary']) ? $item['summary'] : (mb_substr(strip_tags($item['content'] ?? ''), 0, 160) . '...');
      ?>
      <div class="srv-card">
        <div class="srv-card__media">
          <img src="<?= htmlspecialchars(get_service_image_url($item['image'])) ?>" 
               alt="<?= htmlspecialchars($item['title']) ?>" 
               loading="lazy" 
               onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">
          <?php if (!empty($item['code'])): ?>
          <div class="srv-card__badge-code"><?= htmlspecialchars($item['code']) ?></div>
          <?php endif; ?>
          <div class="srv-card__overlay">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>" class="srv-card__overlay-btn">
              <i class="fa-solid fa-eye"></i> Xem Chi Tiết
            </a>
          </div>
        </div>
        
        <div class="srv-card__body">
          <h3 class="srv-card__title">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a>
          </h3>
          <p class="srv-card__desc"><?= htmlspecialchars($desc) ?></p>

          <div class="srv-card__footer">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>" class="srv-card__action-btn">
              <i class="fa-solid fa-eye"></i> Chi Tiết Dịch Vụ
            </a>
            <a href="<?= url('quote.php?service=' . urlencode($item['title'])) ?>" class="srv-card__proj-btn">
              Báo Giá <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="srv-empty-state">
      <i class="fa-solid fa-screwdriver-wrench"></i>
      <h4>Chưa có dịch vụ cơ khí nào được kích hoạt</h4>
      <p>Hiện chưa có dịch vụ cơ khí nào trong cơ sở dữ liệu hoặc đang được cập nhật.</p>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     3. PHẦN 2: DỊCH VỤ XÂY DỰNG DÂN DỤNG
═══════════════════════════════════════════════════ -->
<section class="srv-section srv-section--alt" id="xay-dung">
  <div class="container srv-container">
    
    <!-- Section Head -->
    <div class="srv-sec-head">
      <div class="srv-sec-badge srv-sec-badge--blue">
        <i class="fa-solid fa-trowel-bricks"></i> LĨNH VỰC 02
      </div>
      <h2 class="srv-sec-title">DỊCH VỤ XÂY DỰNG DÂN DỤNG &amp; HOÀN THIỆN</h2>
      <p class="srv-sec-desc">
        Hiện thực hóa các công trình bền vững, kiến tạo không gian sống tiện nghi, sang trọng với chất lượng thi công vượt trội và bảo hành lâu dài.
      </p>
      <div class="srv-sec-divider srv-sec-divider--blue"></div>
    </div>

    <?php if (!empty($xay_dung_services)): ?>
    <div class="srv-grid srv-grid--3cols">
      <?php foreach ($xay_dung_services as $item): 
        $desc = !empty($item['summary']) ? $item['summary'] : (mb_substr(strip_tags($item['content'] ?? ''), 0, 160) . '...');
      ?>
      <div class="srv-card srv-card--highlight">
        <div class="srv-card__media">
          <img src="<?= htmlspecialchars(get_service_image_url($item['image'])) ?>" 
               alt="<?= htmlspecialchars($item['title']) ?>" 
               loading="lazy" 
               onerror="this.onerror=null;this.src='<?= asset_url('images/no-image.svg') ?>';">
          <?php if (!empty($item['code'])): ?>
          <div class="srv-card__badge-code srv-card__badge-code--blue"><?= htmlspecialchars($item['code']) ?></div>
          <?php endif; ?>
          <div class="srv-card__overlay">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>" class="srv-card__overlay-btn">
              <i class="fa-solid fa-eye"></i> Xem Chi Tiết
            </a>
          </div>
        </div>
        
        <div class="srv-card__body">
          <h3 class="srv-card__title">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a>
          </h3>
          <p class="srv-card__desc"><?= htmlspecialchars($desc) ?></p>

          <div class="srv-card__footer">
            <a href="<?= url('service-detail.php?id=' . $item['id']) ?>" class="srv-card__action-btn srv-card__action-btn--blue">
              <i class="fa-solid fa-eye"></i> Chi Tiết Dịch Vụ
            </a>
            <a href="<?= url('quote.php?service=' . urlencode($item['title'])) ?>" class="srv-card__proj-btn">
              Báo Giá <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="srv-empty-state">
      <i class="fa-solid fa-trowel-bricks"></i>
      <h4>Chưa có dịch vụ xây dựng nào được kích hoạt</h4>
      <p>Hiện chưa có dịch vụ xây dựng nào trong cơ sở dữ liệu hoặc đang được cập nhật.</p>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     4. QUY TRÌNH 5 BƯỚC LÀM VIỆC CHUYÊN NGHIỆP
═══════════════════════════════════════════════════ -->
<section class="srv-workflow" id="quy-trinh">
  <div class="container">
    <div class="srv-sec-head srv-sec-head--white">
      <div class="srv-sec-badge">
        <i class="fa-solid fa-list-check"></i> TIÊU CHUẨN KỸ THUẬT
      </div>
      <h2 class="srv-sec-title srv-sec-title--white">5 BƯỚC LÀM VIỆC CHUYÊN NGHIỆP</h2>
      <p class="srv-sec-desc srv-sec-desc--light">
        Đảm bảo tính minh bạch, chuẩn kỹ thuật, tối ưu chi phí và bàn giao công trình hoàn hảo đúng thời hạn.
      </p>
    </div>

    <div class="srv-workflow__grid">
      <div class="srv-step-card">
        <div class="srv-step-num">01</div>
        <div class="srv-step-icon"><i class="fa-solid fa-comments"></i></div>
        <h4>Tiếp Nhận &amp; Khảo Sát</h4>
        <p>Khảo sát hiện trạng mặt bằng, đo đạc kích thước thực tế và lắng nghe nhu cầu của khách hàng.</p>
      </div>

      <div class="srv-step-card">
        <div class="srv-step-num">02</div>
        <div class="srv-step-icon"><i class="fa-solid fa-compass-drafting"></i></div>
        <h4>Thiết Kế &amp; Báo Giá</h4>
        <p>Lập bản vẽ kỹ thuật 2D/3D chi tiết, tối ưu phương án thi công và gửi báo giá minh bạch rõ ràng.</p>
      </div>

      <div class="srv-step-card">
        <div class="srv-step-num">03</div>
        <div class="srv-step-icon"><i class="fa-solid fa-industry"></i></div>
        <h4>Gia Công Tại Xưởng</h4>
        <p>Cắt Laser, hàn liên kết, tổ hợp khung thép chuẩn xác và sơn phủ bảo vệ chất lượng cao tại nhà máy.</p>
      </div>

      <div class="srv-step-card">
        <div class="srv-step-num">04</div>
        <div class="srv-step-icon"><i class="fa-solid fa-helmet-safety"></i></div>
        <h4>Thi Công Lắp Dựng</h4>
        <p>Vận chuyển cấu kiện đến công trường, tổ chức thi công lắp ghép an toàn, chuẩn xác và đúng tiến độ.</p>
      </div>

      <div class="srv-step-card">
        <div class="srv-step-num">05</div>
        <div class="srv-step-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <h4>Nghiệm Thu &amp; Bảo Hành</h4>
        <p>Kiểm tra kỹ lưỡng từng chi tiết, bàn giao công trình hoàn thiện và thực hiện cam kết bảo hành lâu dài.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     5. CTA LIÊN HỆ & BÁO GIÁ NHANH
═══════════════════════════════════════════════════ -->
<section class="srv-cta">
  <div class="container">
    <div class="srv-cta__box">
      <div class="srv-cta__left">
        <span class="srv-cta__tag"><i class="fa-solid fa-bolt"></i> BÁO GIÁ NHANH &amp; CHÍNH XÁC</span>
        <h3 class="srv-cta__title">Bạn Cần Tư Vấn Phương Án Thi Công Tối Ưu Nhất?</h3>
        <p class="srv-cta__desc">
          Đội ngũ kỹ sư giàu kinh nghiệm của PNMEC luôn sẵn sàng khảo sát thực tế và đưa ra giải pháp kỹ thuật tiết kiệm chi phí nhất cho công trình của bạn.
        </p>
      </div>
      <div class="srv-cta__actions">
        <a href="<?= url('quote.php') ?>" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-calculator"></i> Nhận Báo Giá Ngay
        </a>
        <a href="<?= url('contact.php') ?>" class="btn btn-outline btn-lg">
          <i class="fa-solid fa-headset"></i> Liên Hệ Trực Tiếp
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
