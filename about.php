<?php
$page_title = "Giới Thiệu Doanh Nghiệp";
require_once __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════════
     1. HERO BANNER
═══════════════════════════════════════════════════ -->
<section class="ab-hero">
  <div class="container">
    <div class="ab-hero__inner">
      <div class="ab-badge">
        <i class="fa-solid fa-shield-halved"></i> VỀ CHÚNG TÔI
      </div>
      <h1 class="ab-hero__title">Giới Thiệu Doanh Nghiệp</h1>
      <p class="ab-hero__subtitle">
        Đơn vị chuyên nghiệp hàng đầu trong lĩnh vực thi công cơ khí xây dựng và xây dựng công trình dân dụng.
      </p>
      <div class="ab-breadcrumb">
        <a href="<?= url('index.php') ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span>Giới thiệu</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     2. OVERVIEW & FOUNDATION (GIỚI THIỆU CHUNG)
═══════════════════════════════════════════════════ -->
<section class="ab-overview">
  <div class="container">
    <div class="ab-overview__grid">
      <!-- Left Column: Story & Principles -->
      <div class="ab-overview__content">
        <!-- Stats Counter (Năm Kinh Nghiệm & Công Trình Hoàn Thành) -->
        <div class="ab-stats-bar">
          <div class="ab-stat-item">
            <div class="ab-stat-num">10<sup>+</sup></div>
            <div class="ab-stat-lbl">Năm Kinh Nghiệm</div>
          </div>
          <div class="ab-stat-sep"></div>
          <div class="ab-stat-item">
            <div class="ab-stat-num">200<sup>+</sup></div>
            <div class="ab-stat-lbl">Công Trình Hoàn Thành</div>
          </div>
        </div>

        <span class="ab-section-eyebrow">✦ VỀ CHÚNG TÔI</span>
        <h2 class="ab-section-title">Năng Lực Thiết Kế & Thi Công Cơ Khí Xây Dựng Toàn Diện</h2>
        
        <p class="ab-text-lead">
          <strong>PNMEC</strong> là đơn vị tiên phong trong lĩnh vực thiết kế, gia công cơ khí chính xác và thi công công trình kết cấu thép, xây dựng công nghiệp & dân dụng trọn gói với hơn <strong>10+ năm kinh nghiệm</strong> thực tế:
        </p>

        <!-- 4 Core Pillars Banner -->
        <div class="ab-pillars-bar">
          <div class="ab-pillar-item">
            <div class="ab-pillar-icon"><i class="fa-solid fa-award"></i></div>
            <div class="ab-pillar-text">
              <span class="ab-pillar-tag">Phương châm 01</span>
              <strong>Uy Tín</strong>
            </div>
          </div>
          <div class="ab-pillar-item">
            <div class="ab-pillar-icon"><i class="fa-solid fa-gem"></i></div>
            <div class="ab-pillar-text">
              <span class="ab-pillar-tag">Phương châm 02</span>
              <strong>Chất Lượng</strong>
            </div>
          </div>
          <div class="ab-pillar-item">
            <div class="ab-pillar-icon"><i class="fa-solid fa-shield-heart"></i></div>
            <div class="ab-pillar-text">
              <span class="ab-pillar-tag">Phương châm 03</span>
              <strong>An Toàn</strong>
            </div>
          </div>
          <div class="ab-pillar-item">
            <div class="ab-pillar-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="ab-pillar-text">
              <span class="ab-pillar-tag">Phương châm 04</span>
              <strong>Hiệu Quả</strong>
            </div>
          </div>
        </div>

        <p class="ab-text-desc">
          Với đội ngũ kỹ sư, cán bộ kỹ thuật và công nhân lành nghề giàu kinh nghiệm, công ty luôn mang đến cho khách hàng những công trình bền vững, thẩm mỹ cao và tối ưu chi phí đầu tư.
        </p>

        <!-- Highlights Grid -->
        <div class="ab-highlights-row">
          <div class="ab-highlight-box">
            <div class="ab-highlight-icon"><i class="fa-solid fa-user-gear"></i></div>
            <div class="ab-highlight-info">
              <h4>Kỹ Sư & Công Nhân Lành Nghề</h4>
              <p>Trình độ kỹ thuật cao, giàu kinh nghiệm thực tế</p>
            </div>
          </div>
          <div class="ab-highlight-box">
            <div class="ab-highlight-icon"><i class="fa-solid fa-coins"></i></div>
            <div class="ab-highlight-info">
              <h4>Tối Ưu Hóa Chi Phí</h4>
              <p>Giải pháp thi công kinh tế, thẩm mỹ & bền đẹp</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Visual Artwork & Feature Cards -->
      <div class="ab-overview__visual">
        <div class="ab-image-frame">
          <img src="<?= asset_url('images/home-about.png') ?>" alt="Công ty cơ khí xây dựng" class="ab-main-img">
          
          <!-- Floating Badge Top -->
          <div class="ab-floating-badge ab-floating-badge--top">
            <i class="fa-solid fa-circle-check"></i>
            <div>
              <strong>Tiêu Chuẩn Chuẩn Mực</strong>
              <span>Đạt chuẩn an toàn & chất lượng</span>
            </div>
          </div>

          <!-- Floating Badge Bottom -->
          <div class="ab-floating-badge ab-floating-badge--bottom">
            <i class="fa-solid fa-medal"></i>
            <div>
              <strong>Đối Tác Tin Cậy</strong>
              <span>Uy tín - Tiến độ - Hiệu quả</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     3. LĨNH VỰC HOẠT ĐỘNG (2 MAIN FIELDS)
═══════════════════════════════════════════════════ -->
<section class="ab-sectors">
  <div class="container">
    <div class="ab-section-header text-center">
      <span class="ab-section-eyebrow">✦ NĂNG LỰC CHUYÊN MÔN</span>
      <h2 class="ab-section-title">LĨNH VỰC HOẠT ĐỘNG</h2>
      <p class="ab-section-lead">
        Cung cấp các giải pháp toàn diện từ gia công cơ khí chính xác đến tổng thầu xây dựng dân dụng chất lượng cao.
      </p>
    </div>

    <div class="ab-sectors__grid">
      
      <!-- Sector Card 1: Thi công cơ khí xây dựng -->
      <div class="ab-sector-card">
        <div class="ab-sector-card__media">
          <img src="<?= asset_url('images/service-cons.png') ?>" alt="Thi công cơ khí xây dựng">
          <div class="ab-sector-card__overlay">
            <span class="ab-sector-badge"><i class="fa-solid fa-gears"></i> LĨNH VỰC 01</span>
          </div>
        </div>
        
        <div class="ab-sector-card__body">
          <div class="ab-sector-title-wrap">
            <span class="ab-sector-icon-symbol">✦</span>
            <h3 class="ab-sector-title">Thi công cơ khí xây dựng</h3>
          </div>
          <p class="ab-sector-intro">
            Gia công chế tạo chính xác, gia cường cấu kiện và lắp dựng kết cấu thép bền bỉ theo tiêu chuẩn kỹ thuật cao.
          </p>

          <ul class="ab-service-list">
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công nhà kết cấu thép</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công cầu thang, ban công</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công mái tôn, mái che di động</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công nhà cơi nới, lồng cơi tập thể</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công các dạng thang thoát hiểm</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công nhà xe, mái che</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công mái kính</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công sắt mỹ thuật</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công cửa các loại</span>
            </li>
          </ul>

          <div class="ab-sector-footer">
            <a href="<?= url('services.php') ?>" class="ab-link-btn">
              Xem chi tiết dịch vụ <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Sector Card 2: Thi công xây dựng dân dụng -->
      <div class="ab-sector-card">
        <div class="ab-sector-card__media">
          <img src="<?= asset_url('images/service-plant.png') ?>" alt="Thi công xây dựng dân dụng">
          <div class="ab-sector-card__overlay">
            <span class="ab-sector-badge"><i class="fa-solid fa-building"></i> LĨNH VỰC 02</span>
          </div>
        </div>
        
        <div class="ab-sector-card__body">
          <div class="ab-sector-title-wrap">
            <span class="ab-sector-icon-symbol">✦</span>
            <h3 class="ab-sector-title">Thi công xây dựng dân dụng</h3>
          </div>
          <p class="ab-sector-intro">
            Hiện thực hóa các không gian sống và làm việc hiện đại, kiên cố, tiện nghi với chất lượng thi công vượt trội.
          </p>

          <ul class="ab-service-list">
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công nhà trọn gói</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Thiết kế thi công nội ngoại thất</span>
            </li>
            <li>
              <span class="ab-bullet"><i class="fa-solid fa-check"></i></span>
              <span class="ab-list-text">Cải tạo sửa chữa và phá dỡ</span>
            </li>
          </ul>

          <div class="ab-sector-footer">
            <a href="<?= url('services.php') ?>" class="ab-link-btn">
              Xem chi tiết dịch vụ <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     4. THẾ MẠNH CỦA CHÚNG TÔI (5 STRENGTH CARDS)
═══════════════════════════════════════════════════ -->
<section class="ab-strengths">
  <div class="container">
    <div class="ab-section-header text-center">
      <span class="ab-section-eyebrow ab-section-eyebrow--gold">✦ TẠI SAO CHỌN CHÚNG TÔI</span>
      <h2 class="ab-section-title ab-section-title--white">THẾ MẠNH CỦA CHÚNG TÔI</h2>
      <p class="ab-section-lead ab-section-lead--muted">
        Những giá trị khác biệt làm nên uy tín và thương hiệu vững mạnh trên thị trường
      </p>
    </div>

    <div class="ab-strengths__grid">
      
      <!-- Strength 01 -->
      <div class="ab-strength-card">
        <div class="ab-strength-card__num">01</div>
        <div class="ab-strength-check">✓</div>
        <div class="ab-strength-body">
          <h3 class="ab-strength-heading">Đội Ngũ Chuyên Môn Cao</h3>
          <p class="ab-strength-desc">Đội ngũ kỹ thuật chuyên môn cao, làm việc chuyên nghiệp</p>
        </div>
      </div>

      <!-- Strength 02 -->
      <div class="ab-strength-card">
        <div class="ab-strength-card__num">02</div>
        <div class="ab-strength-check">✓</div>
        <div class="ab-strength-body">
          <h3 class="ab-strength-heading">Máy Móc Hiện Đại</h3>
          <p class="ab-strength-desc">Hệ thống máy móc, thiết bị hiện đại</p>
        </div>
      </div>

      <!-- Strength 03 -->
      <div class="ab-strength-card">
        <div class="ab-strength-card__num">03</div>
        <div class="ab-strength-check">✓</div>
        <div class="ab-strength-body">
          <h3 class="ab-strength-heading">Đảm Bảo Tiến Độ</h3>
          <p class="ab-strength-desc">Quy trình thi công chặt chẽ, đảm bảo tiến độ</p>
        </div>
      </div>

      <!-- Strength 04 -->
      <div class="ab-strength-card">
        <div class="ab-strength-card__num">04</div>
        <div class="ab-strength-check">✓</div>
        <div class="ab-strength-body">
          <h3 class="ab-strength-heading">An Toàn & Chất Lượng</h3>
          <p class="ab-strength-desc">Cam kết an toàn lao động và chất lượng công trình</p>
        </div>
      </div>

      <!-- Strength 05 -->
      <div class="ab-strength-card ab-strength-card--wide">
        <div class="ab-strength-card__num">05</div>
        <div class="ab-strength-check">✓</div>
        <div class="ab-strength-body">
          <h3 class="ab-strength-heading">Giá Thành Cạnh Tranh</h3>
          <p class="ab-strength-desc">Giá thành cạnh tranh, minh bạch</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     5. CAM KẾT VÀ TỔNG KẾT
═══════════════════════════════════════════════════ -->
<section class="ab-commitments">
  <div class="container">
    <div class="ab-commitments__wrapper">
      
      <!-- Top Title Box -->
      <div class="ab-commit-header">
        <span class="ab-section-eyebrow">✦ TRÁCH NHIỆM & TẬN TÂM</span>
        <h2 class="ab-section-title">CAM KẾT</h2>
        <p class="ab-commit-lead">
          Chúng tôi luôn đặt lợi ích của khách hàng lên hàng đầu, đảm bảo:
        </p>
      </div>

      <!-- 4 Commitments Grid -->
      <div class="ab-commit-grid">
        <div class="ab-commit-card">
          <div class="ab-commit-icon"><i class="fa-solid fa-compass-drafting"></i></div>
          <div class="ab-commit-content">
            <h4>Đúng Kỹ Thuật & Tiến Độ</h4>
            <p>Thi công đúng kỹ thuật, đúng tiến độ</p>
          </div>
        </div>

        <div class="ab-commit-card">
          <div class="ab-commit-icon"><i class="fa-solid fa-layer-group"></i></div>
          <div class="ab-commit-content">
            <h4>Vật Tư Đạt Chuẩn</h4>
            <p>Sử dụng vật tư đạt chuẩn chất lượng</p>
          </div>
        </div>

        <div class="ab-commit-card">
          <div class="ab-commit-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="ab-commit-content">
            <h4>Bảo Hành Uy Tín</h4>
            <p>Bảo hành công trình theo cam kết</p>
          </div>
        </div>

        <div class="ab-commit-card">
          <div class="ab-commit-icon"><i class="fa-solid fa-handshake-angle"></i></div>
          <div class="ab-commit-content">
            <h4>Đồng Hành Lâu Dài</h4>
            <p>Hỗ trợ và đồng hành lâu dài cùng khách hàng</p>
          </div>
        </div>
      </div>

      <!-- Concluding Statement Card -->
      <div class="ab-conclusion-box">
        <div class="ab-conclusion-icon">
          <i class="fa-solid fa-quote-left"></i>
        </div>
        <div class="ab-conclusion-text">
          <p>
            Với kinh nghiệm thực tế qua nhiều dự án lớn nhỏ, công ty tự tin trở thành đối tác tin cậy trong lĩnh vực cơ khí xây dựng và xây dựng dân dụng, góp phần kiến tạo nên những công trình bền vững theo thời gian.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     6. CTA CALLOUT (LIÊN HỆ & BÁO GIÁ)
═══════════════════════════════════════════════════ -->
<section class="ab-cta">
  <div class="container">
    <div class="ab-cta__box">
      <div class="ab-cta__left">
        <span class="ab-cta-tag"><i class="fa-solid fa-bolt"></i> TƯ VẤN TRỰC TIẾP</span>
        <h3 class="ab-cta-title">Sẵn Sàng Hợp Tác Cùng Dự Án Của Bạn</h3>
        <p class="ab-cta-desc">
          Hãy liên hệ với đội ngũ kỹ sư của chúng tôi để được khảo sát thực tế và nhận báo giá tối ưu nhất.
        </p>
      </div>
      <div class="ab-cta__actions">
        <a href="<?= url('contact.php') ?>" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-phone"></i> Liên Hệ Ngay
        </a>
        <a href="<?= url('projects.php') ?>" class="btn btn-navy btn-lg">
          <i class="fa-solid fa-helmet-safety"></i> Xem Dự Án Thực Tế
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
