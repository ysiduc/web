<?php
$page_title = "Dịch Vụ Thiết Kế & Thi Công Cơ Khí - Xây Dựng";
require_once __DIR__ . '/includes/header.php';

$co_khi_services = [
    [
        'id' => 'CK-01',
        'title' => 'Thiết Kế Thi Công Nhà Kết Cấu Thép',
        'img' => '/test/web_cty/assets/images/service-cons.png',
        'desc' => 'Thiết kế, sản xuất cấu kiện thép tại nhà máy và tổ chức lắp dựng an toàn khung kèo nhà xưởng, nhà tiền chế khẩu độ lớn đạt chuẩn chất lượng.',
        'bullets' => [
            'Khung kèo vượt nhịp lớn không cột chịu tải cao',
            'Tấm lợp tôn cách nhiệt 3 lớp PU/EPS chống nóng',
            'Thi công nhanh chóng, độ bền kết cấu trên 30 năm'
        ]
    ],
    [
        'id' => 'CK-02',
        'title' => 'Thiết Kế Thi Công Cầu Thang, Ban Công',
        'img' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Gia công và lắp đặt cầu thang sắt hộp, cầu thang xoắn ốc, cầu thang xương cá kết hợp ban công sắt mỹ thuật, tay vịn gỗ hoặc kính cường lực cao cấp.',
        'bullets' => [
            'Thiết kế chuẩn phong thủy, kiến trúc hiện đại',
            'Mối hàn mài phẳng mịn, sơn tĩnh điện cao cấp',
            'Kết cấu vững chắc, an toàn tuyệt đối khi sử dụng'
        ]
    ],
    [
        'id' => 'CK-03',
        'title' => 'Thiết Kế Thi Công Mái Tôn, Mái Che Di Động',
        'img' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Thi công khung kèo xà gồ thép mạ kẽm lợp tôn lạnh chống nóng 3 lớp, tôn lấy sáng Polycarbonate và hệ thống mái xếp lượn sóng, mái che bạt kéo tự động.',
        'bullets' => [
            'Chống thấm dột tuyệt đối, cách âm cách nhiệt tốt',
            'Khung thép chịu sức gió giật bão lớn an toàn',
            'Vận hành kéo mở nhẹ nhàng, tiện lợi cho mọi mặt bằng'
        ]
    ],
    [
        'id' => 'CK-04',
        'title' => 'Thiết Kế Thi Công Nhà Cơi Nới, Lồng Cơi Tập Thể',
        'img' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Giải pháp cơi nới mở rộng không gian sống, làm lồng sắt an toàn (chuồng cọp), gác lửng khung thép hộp và sàn bê tông nhẹ Cemboard cho nhà phố, nhà tập thể.',
        'bullets' => [
            'Khung thép siêu nhẹ giảm tải trọng cho móng cũ',
            'Gia cố an toàn chống trộm đột nhập hiệu quả',
            'Tích hợp cửa thoát hiểm PCCC khẩn cấp tiện dụng'
        ]
    ],
    [
        'id' => 'CK-05',
        'title' => 'Thiết Kế Thi Công Các Dạng Thang Thoát Hiểm',
        'img' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Sản xuất và lắp dựng hệ thống cầu thang thoát hiểm ngoài trời cho tòa nhà văn phòng, chung cư, khách sạn, nhà hàng đáp ứng nghiêm ngặt tiêu chuẩn PCCC.',
        'bullets' => [
            'Kết cấu dầm thép hình chữ I, U chịu tải trọng lớn',
            'Bậc tôn gân dập nhám chống trơn trượt hiệu quả',
            'Sơn chống cháy cao cấp, hỗ trợ kiểm định PCCC'
        ]
    ],
    [
        'id' => 'CK-06',
        'title' => 'Thiết Kế Thi Công Nhà Xe, Mái Che',
        'img' => 'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Thi công hệ mái che nhà để xe ô tô, xe máy cho cơ quan, nhà máy, trường học, bệnh viện với kết cấu khung vòm thép và tôn cách nhiệt bền bỉ.',
        'bullets' => [
            'Khẩu độ rộng tối đa hóa diện tích sắp xếp vị trí đỗ',
            'Hệ thống máng xối thu gom nước mưa thoát nhanh',
            'Khung cột vững chãi, thẩm mỹ và che chắn tối ưu'
        ]
    ],
    [
        'id' => 'CK-07',
        'title' => 'Thiết Kế Thi Công Mái Kính',
        'img' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Lắp đặt mái kính cường lực canopy, mái sảnh nghệ thuật, giếng trời tự động khung thép mạ kẽm định hình cho biệt thự, nhà phố và tòa nhà hiện đại.',
        'bullets' => [
            'Kính cường lực an toàn 2 lớp chất lượng cao',
            'Đón ánh sáng tự nhiên tối đa, ngăn tia cực tím UV',
            'Bơm keo kết cấu chống thấm dột rò rỉ nước 100%'
        ]
    ],
    [
        'id' => 'CK-08',
        'title' => 'Thiết Kế Thi Công Sắt Mỹ Thuật',
        'img' => '/test/web_cty/assets/images/service-cnc.png',
        'desc' => 'Gia công hoa sắt nghệ thuật, cổng sắt uốn mỹ nghệ, hàng rào biệt thự, lan can hoa văn cổ điển và tân cổ điển tinh xảo theo bản vẽ kiến trúc.',
        'bullets' => [
            'Cắt Laser Fiber CNC sắc nét, hoa văn chuẩn xác',
            'Rèn uốn thủ công kết hợp công nghệ hiện đại',
            'Sơn mạ kẽm nhúng nóng chống ăn mòn, bền đẹp trọn đời'
        ]
    ],
    [
        'id' => 'CK-09',
        'title' => 'Thiết Kế Thi Công Cửa Các Loại',
        'img' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Sản xuất và lắp đặt trọn gói cửa cổng sắt 2 cánh, 4 cánh, cửa lùa tự động, cửa chống cháy, cửa cuốn và cửa nhôm kính hệ cao cấp.',
        'bullets' => [
            'Phụ kiện bản lề, khóa thông minh đồng bộ chính hãng',
            'Sơn tĩnh điện 2 mặt chống bong tróc, trầy xước',
            'Vận hành êm ái, cách âm cách nhiệt và bảo mật cao'
        ]
    ],
];

$xay_dung_services = [
    [
        'id' => 'XD-01',
        'title' => 'Thiết Kế Thi Công Nhà Trọn Gói',
        'img' => '/test/web_cty/assets/images/service-plant.png',
        'desc' => 'Tổng thầu chìa khóa trao tay (Design & Build) từ xin phép xây dựng, thiết kế kiến trúc - kết cấu 3D, thi công phần thô đến hoàn thiện nhà phố, biệt thự.',
        'bullets' => [
            'Cam kết không phát sinh bất kỳ chi phí ngoài hợp đồng',
            'Sử dụng vật tư chính hãng đúng chủng loại cam kết',
            'Kỹ sư trưởng trực tiếp giám sát chất lượng tại công trường'
        ]
    ],
    [
        'id' => 'XD-02',
        'title' => 'Thiết Kế Thi Công Nội Ngoại Thất',
        'img' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Thiết kế và thi công hoàn thiện nội ngoại thất cao cấp, ốp lát gạch đá Granite, trần thạch cao, sơn bả tường, cảnh quan sân vườn theo phong cách hiện đại.',
        'bullets' => [
            'Dựng phối cảnh 3D trực quan trước khi thi công thực tế',
            'Đường nét thi công sắc sảo, chuẩn gu thẩm mỹ gia chủ',
            'Tối ưu hóa công năng sử dụng và chuẩn mực phong thủy'
        ]
    ],
    [
        'id' => 'XD-03',
        'title' => 'Cải Tạo Sửa Chữa Và Phá Dỡ',
        'img' => 'https://images.unsplash.com/photo-1581092795360-fd1ca04f0952?auto=format&fit=crop&w=600&q=80',
        'desc' => 'Dịch vụ phá dỡ công trình cũ an toàn, dọn phế thải, cấy ghép dầm cột cơi nới nâng tầng, xử lý triệt để thấm dột và sửa chữa nâng cấp toàn diện nhà ở.',
        'bullets' => [
            'Máy móc chuyên dụng thi công nhanh gọn, đúng tiến độ',
            'Đảm bảo an toàn tuyệt đối cho kết cấu nhà liền kề',
            'Xử lý dứt điểm các hiện tượng nứt, lún, thấm mốc tường'
        ]
    ],
];
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
          <i class="fa-solid fa-hammer"></i> Cơ Khí Xây Dựng (9 Dịch Vụ)
        </a>
        <a href="#xay-dung" class="srv-nav-pill srv-nav-pill--blue">
          <i class="fa-solid fa-building"></i> Xây Dựng Dân Dụng (3 Dịch Vụ)
        </a>
        <a href="#quy-trinh" class="srv-nav-pill srv-nav-pill--outline">
          <i class="fa-solid fa-list-check"></i> Quy Trình 5 Bước
        </a>
      </div>

      <div class="srv-breadcrumb">
        <a href="/test/web_cty/index.php"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <i class="fa-solid fa-angle-right"></i>
        <span>Dịch vụ</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     2. PHẦN 1: DỊCH VỤ CƠ KHÍ XÂY DỰNG (9 MỤC)
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

    <!-- 9 Items Grid -->
    <div class="srv-grid srv-grid--3cols">
      <?php foreach ($co_khi_services as $item): ?>
      <div class="srv-card">
        <div class="srv-card__media">
          <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
          <div class="srv-card__badge-code"><?= $item['id'] ?></div>
          <div class="srv-card__overlay">
            <a href="/test/web_cty/quote.php?service=<?= urlencode($item['title']) ?>" class="srv-card__overlay-btn">
              <i class="fa-solid fa-calculator"></i> Báo Giá Nhanh
            </a>
          </div>
        </div>
        
        <div class="srv-card__body">
          <h3 class="srv-card__title"><?= htmlspecialchars($item['title']) ?></h3>
          <p class="srv-card__desc"><?= htmlspecialchars($item['desc']) ?></p>
          
          <ul class="srv-card__bullets">
            <?php foreach ($item['bullets'] as $b): ?>
            <li>
              <span class="srv-card__bullet-ico"><i class="fa-solid fa-check"></i></span>
              <span><?= htmlspecialchars($b) ?></span>
            </li>
            <?php endforeach; ?>
          </ul>

          <div class="srv-card__footer">
            <a href="/test/web_cty/contact.php?service=<?= urlencode($item['title']) ?>" class="srv-card__action-btn">
              <i class="fa-solid fa-headset"></i> Tư Vấn Ngay
            </a>
            <a href="/test/web_cty/projects.php?category=<?= urlencode('Cơ khí chế tạo') ?>" class="srv-card__proj-btn">
              Công Trình Đã Hoàn Thiện <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     3. PHẦN 2: DỊCH VỤ XÂY DỰNG DÂN DỤNG (3 MỤC)
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

    <!-- 3 Items Grid -->
    <div class="srv-grid srv-grid--3cols">
      <?php foreach ($xay_dung_services as $item): ?>
      <div class="srv-card srv-card--highlight">
        <div class="srv-card__media">
          <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
          <div class="srv-card__badge-code srv-card__badge-code--blue"><?= $item['id'] ?></div>
          <div class="srv-card__overlay">
            <a href="/test/web_cty/quote.php?service=<?= urlencode($item['title']) ?>" class="srv-card__overlay-btn">
              <i class="fa-solid fa-calculator"></i> Báo Giá Nhanh
            </a>
          </div>
        </div>
        
        <div class="srv-card__body">
          <h3 class="srv-card__title"><?= htmlspecialchars($item['title']) ?></h3>
          <p class="srv-card__desc"><?= htmlspecialchars($item['desc']) ?></p>
          
          <ul class="srv-card__bullets">
            <?php foreach ($item['bullets'] as $b): ?>
            <li>
              <span class="srv-card__bullet-ico srv-card__bullet-ico--blue"><i class="fa-solid fa-check"></i></span>
              <span><?= htmlspecialchars($b) ?></span>
            </li>
            <?php endforeach; ?>
          </ul>

          <div class="srv-card__footer">
            <a href="/test/web_cty/contact.php?service=<?= urlencode($item['title']) ?>" class="srv-card__action-btn srv-card__action-btn--blue">
              <i class="fa-solid fa-headset"></i> Tư Vấn Ngay
            </a>
            <a href="/test/web_cty/projects.php?category=<?= urlencode('Xây dựng dân dụng') ?>" class="srv-card__proj-btn">
              Công Trình Đã Hoàn Thiện <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

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
        <a href="/test/web_cty/quote.php" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-calculator"></i> Nhận Báo Giá Ngay
        </a>
        <a href="/test/web_cty/contact.php" class="btn btn-outline btn-lg">
          <i class="fa-solid fa-headset"></i> Liên Hệ Trực Tiếp
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
