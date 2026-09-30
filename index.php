<?php
$page_title = "Trang Chủ - Gia Công Cơ Khí & Thi Công Xây Dựng";
require_once __DIR__ . '/includes/header.php';

// Load projects
$db = getDBConnection();
$projects = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM projects ORDER BY views DESC, id DESC LIMIT 3");
        $projects = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($projects)) {
    $projects = [
        ['id'=>1,'title'=>'Nhà Xưởng Công Nghiệp Tập Đoàn Samsung Bắc Ninh','category'=>'Xây dựng công nghiệp','client'=>'Tập đoàn Samsung Electronics','location'=>'KCN Yên Phong, Bắc Ninh','image'=>'','description'=>'Thi công tổng thầu nhà xưởng sản xuất quy mô 25.000m² với kết cấu thép vượt khổ lớn.'],
        ['id'=>2,'title'=>'Gia Công Hệ Thống Băng Tải Luyện Kim Hoà Phát','category'=>'Cơ khí chế tạo','client'=>'Tập đoàn Hòa Phát','location'=>'KKT Dung Quất, Quảng Ngãi','image'=>'','description'=>'Chế tạo và gia công hệ thống truyền động băng tải chịu nhiệt cho khu liên hợp thép Dung Quất.'],
        ['id'=>3,'title'=>'Tòa Nhà Văn Phòng & Showroom Ô Tô VinFast','category'=>'Kết cấu thép','client'=>'Tập đoàn Vingroup','location'=>'Cầu Giấy, Hà Nội','image'=>'','description'=>'Thi công khung kết cấu thép chịu lực 8 tầng kết hợp vách kính hiện đại.'],
    ];
}
$about_text = get_site_info('about_summary','PNMEC Group là đơn vị tiên phong trong lĩnh vực gia công cơ khí chính xác và thi công xây dựng công nghiệp tại Việt Nam. Chúng tôi cung cấp giải pháp trọn gói từ thiết kế, chế tạo đến thi công và bàn giao công trình với tiêu chuẩn quốc tế.');
$hero_title = get_site_info('hero_title','GIẢI PHÁP CƠ KHÍ CHẾ TẠO & THI CÔNG XÂY DỰNG TIÊN TIẾN');
$hero_sub   = get_site_info('hero_subtitle','Cung cấp giải pháp tổng thầu cơ khí & xây dựng công nghiệp uy tín — đúng tiến độ, đúng chất lượng, tối ưu chi phí.');
?>


<!-- ═══════════════════════════════════════════════════
     SECTION 1 · HERO BANNER
═══════════════════════════════════════════════════ -->
<section class="h-hero">
  <div class="container">
    <div class="h-hero__inner">
      <div class="h-hero__badge">
        <i class="fa-solid fa-certificate"></i> Đơn Vị Uy Tín Hàng Đầu Việt Nam
      </div>
      <h1 class="h-hero__title"><?= htmlspecialchars($hero_title) ?></h1>
      <p class="h-hero__sub"><?= htmlspecialchars($hero_sub) ?></p>
      <div class="h-hero__btns">
        <a href="/test/web_cty/projects.php" class="h-btn-gold">
          <i class="fa-solid fa-helmet-safety"></i> Xem Dự Án Công Trình
        </a>
        <a href="/test/web_cty/contact.php" class="h-btn-outline">
          <i class="fa-solid fa-headset"></i> Tư Vấn &amp; Báo Giá
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 2 · DỊCH VỤ NỔI BẬT (5 CARDS)
═══════════════════════════════════════════════════ -->
<section class="h-svc">
  <div class="container">
    <div class="h-svc__head">
      <div>
        <span class="h-svc__eyebrow">— PNMEC GROUP</span>
        <h2 class="h-svc__title">DỊCH VỤ NỔI BẬT</h2>
      </div>
      <a href="/test/web_cty/services.php" class="h-svc__all">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="h-svc__grid" id="svcGrid">
      <?php
      /* 
       * DANH SÁCH DỊCH VỤ NỔI BẬT (Chỉnh sửa / Thêm / Xóa thủ công tại đây)
       * Các file ảnh nằm trong đường dẫn: assets/images/
       */
      $svc_cards = [
        [
          'tag'  => 'PNMEC-CNC',
          'img'  => '/test/web_cty/assets/images/service-cnc.png',
          'desc' => 'Gia công chi tiết máy CNC chính xác, dầm tổ hợp chịu lực & cắt Laser Fiber thép tấm công nghiệp.'
        ],
        [
          'tag'  => 'PNMEC-CONS',
          'img'  => '/test/web_cty/assets/images/service-cons.png',
          'desc' => 'Sản xuất & thi công lắp dựng khung nhà xưởng kết cấu thép khẩu độ lớn đạt chuẩn quốc tế.'
        ],
        [
          'tag'  => 'PNMEC-PLANT',
          'img'  => '/test/web_cty/assets/images/service-plant.png',
          'desc' => 'Hạ tầng công nghiệp, móng cọc & bê tông xoa nền Epoxy đáp ứng tải trọng cao.',
          'active' => true
        ],
        [
          'tag'  => 'PNMEC-PIPING',
          'img'  => '/test/web_cty/assets/images/service-piping.png',
          'desc' => 'Chế tạo bồn chứa Inox áp lực cao & hệ thống đường ống công nghiệp dẫn hóa chất an toàn.'
        ],
        [
          'tag'  => 'PNMEC-ECO',
          'img'  => '/test/web_cty/assets/images/service-eco.png',
          'desc' => 'Di dời, lắp đặt dây chuyền máy móc công nghiệp nặng và bảo dưỡng công trình định kỳ.'
        ],
      ];
      foreach($svc_cards as $c): ?>
      <a href="/test/web_cty/services.php" class="h-svc__card<?= !empty($c['active'])?' active':'' ?>">
        <img src="<?= $c['img'] ?>" alt="<?= $c['tag'] ?>" loading="lazy">
        <div class="h-svc__badge-tag"><?= $c['tag'] ?></div>
        <div class="h-svc__overlay">
          <p class="h-svc__desc"><?= $c['desc'] ?></p>
          <span class="h-svc__link">XEM CHI TIẾT <i class="fa-solid fa-arrow-right"></i></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 3 · VỀ CHÚNG TÔI
═══════════════════════════════════════════════════ -->
<section class="h-about">
  <div class="container">
    <div class="h-about__grid">
      <!-- Left -->
      <div>
        <div class="h-about__stats">
          <div>
            <div class="h-about__num">10<sup>+</sup></div>
            <div class="h-about__lbl">Năm Kinh Nghiệm</div>
          </div>
          <div class="h-about__sep"></div>
          <div>
            <div class="h-about__num">200<sup>+</sup></div>
            <div class="h-about__lbl">Công Trình Hoàn Thành</div>
          </div>
        </div>
        <span class="h-about__tag">Về Chúng Tôi</span>
        <h2 class="h-about__heading">Năng Lực Thiết Kế &amp; Thi Công<br>Cơ Khí Xây Dựng Toàn Diện</h2>
        <p class="h-about__body"><?= htmlspecialchars($about_text) ?></p>
        <a href="/test/web_cty/about.php" class="h-about__btn">Tìm Hiểu Thêm <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <!-- Right -->
      <div class="h-about__img-wrap">
        <img class="h-about__img"
             src="/test/web_cty/assets/images/home-about.png"
             alt="Đội ngũ PNMEC" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 4 · CÁC CÔNG TRÌNH ĐÃ ĐĂNG
═══════════════════════════════════════════════════ -->
<section class="h-proj">
  <div class="container">
    <div class="h-sec-title">
      <span class="h-sec-title__tag">Dự Án Tiêu Biểu</span>
      <h2>Các Công Trình Đã Đăng</h2>
      <p>Những công trình cơ khí &amp; xây dựng tiêu biểu thể hiện năng lực và chất lượng thi công vượt trội.</p>
    </div>
    <div class="h-proj__grid">
      <?php
      $fallback_imgs = [
        'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=600&q=80',
      ];
      foreach($projects as $i => $p):
        $img = !empty($p['image']) && $p['image']!='default-project.jpg'
               ? '/test/web_cty/assets/uploads/'.htmlspecialchars($p['image'])
               : $fallback_imgs[$i%3];
      ?>
      <div class="h-proj__card">
        <div class="h-proj__thumb">
          <span class="h-proj__cat"><?= htmlspecialchars($p['category']) ?></span>
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy"
               onerror="this.src='<?= $fallback_imgs[$i%3] ?>'">
        </div>
        <div class="h-proj__body">
          <h3><a href="/test/web_cty/project-detail.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a></h3>
          <div class="h-proj__meta">
            <span><i class="fa-solid fa-building-user"></i><?= htmlspecialchars($p['client']) ?></span>
            <span><i class="fa-solid fa-location-dot"></i><?= htmlspecialchars($p['location']) ?></span>
          </div>
          <p class="h-proj__desc"><?= htmlspecialchars($p['description']) ?></p>
          <a href="/test/web_cty/project-detail.php?id=<?= $p['id'] ?>" class="h-proj__link">
            Chi tiết công trình <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="h-proj__cta">
      <a href="/test/web_cty/projects.php" class="h-btn-navy"><i class="fa-solid fa-grip"></i> Xem Tất Cả Công Trình</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 5 · KHÁCH HÀNG NÓI GÌ
═══════════════════════════════════════════════════ -->
<section class="h-testi">
  <div class="container">
    <div class="h-testi__head">
      <p class="h-testi__tag">Đánh Giá</p>
      <h2>Khách Hàng Nói Gì Về Chúng Tôi?</h2>
      <p>Những phản hồi chân thực từ các đối tác và khách hàng đã tin tưởng hợp tác cùng PNMEC.</p>
    </div>

    <div class="h-testi__shell">
      <button class="h-testi__arr" id="testiPrev" aria-label="Prev">
        <i class="fa-solid fa-chevron-left"></i>
      </button>

      <div class="h-testi__viewport">
        <div class="h-testi__track" id="testiTrack">
          <!-- SLIDE 1 -->
          <div class="h-testi__slide">
            <div class="h-testi__card">
              <div class="h-testi__quote"><i class="fa-solid fa-quote-left"></i></div>
              <p class="h-testi__text">PNMEC đã thi công nhà xưởng cho chúng tôi đúng tiến độ và chất lượng vượt mong đợi. Đội ngũ kỹ sư rất chuyên nghiệp, tận tâm trong từng hạng mục. Chúng tôi rất hài lòng và sẽ tiếp tục hợp tác lâu dài.</p>
              <div class="h-testi__author">
                <div class="h-testi__ava"><i class="fa-solid fa-user"></i></div>
                <div class="h-testi__author-info">
                  <strong>Anh Tuấn Nguyễn</strong>
                  <span>Giám đốc - Công ty TNHH Sản Xuất ABC</span>
                </div>
              </div>
            </div>
            <div class="h-testi__card">
              <div class="h-testi__quote"><i class="fa-solid fa-quote-left"></i></div>
              <p class="h-testi__text">Rất ấn tượng với năng lực gia công cơ khí CNC của PNMEC. Độ chính xác cao, tiến độ đúng hẹn và giá cả cạnh tranh. Đây là đối tác tin cậy để chúng tôi phát triển dây chuyền sản xuất hiện đại.</p>
              <div class="h-testi__author">
                <div class="h-testi__ava"><i class="fa-solid fa-user"></i></div>
                <div class="h-testi__author-info">
                  <strong>Anh Long Phạm</strong>
                  <span>Kỹ sư trưởng - Tập đoàn XYZ</span>
                </div>
              </div>
            </div>
          </div>
          <!-- SLIDE 2 -->
          <div class="h-testi__slide">
            <div class="h-testi__card">
              <div class="h-testi__quote"><i class="fa-solid fa-quote-left"></i></div>
              <p class="h-testi__text">Chúng tôi đã giao phó dự án nhà xưởng lớn cho PNMEC và kết quả thật sự ngoài mong đợi. Từ khâu thiết kế đến thi công hoàn thiện đều được thực hiện bài bản, chuyên nghiệp.</p>
              <div class="h-testi__author">
                <div class="h-testi__ava"><i class="fa-solid fa-user"></i></div>
                <div class="h-testi__author-info">
                  <strong>Chị Mai Trần</strong>
                  <span>Chủ đầu tư - Dự án Khu Công Nghiệp</span>
                </div>
              </div>
            </div>
            <div class="h-testi__card">
              <div class="h-testi__quote"><i class="fa-solid fa-quote-left"></i></div>
              <p class="h-testi__text">Hợp tác với PNMEC là quyết định đúng đắn nhất. Họ luôn đặt chất lượng và uy tín lên hàng đầu. Sản phẩm bàn giao đúng thông số kỹ thuật, đạt tiêu chuẩn xuất khẩu quốc tế.</p>
              <div class="h-testi__author">
                <div class="h-testi__ava"><i class="fa-solid fa-user"></i></div>
                <div class="h-testi__author-info">
                  <strong>Anh Hùng Lê</strong>
                  <span>Trưởng phòng mua hàng - Tập đoàn DEF</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button class="h-testi__arr" id="testiNext" aria-label="Next">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>

    <div class="h-testi__dots">
      <span class="h-testi__dot on" data-i="0"></span>
      <span class="h-testi__dot" data-i="1"></span>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 6 · CÂU HỎI THƯỜNG GẶP
═══════════════════════════════════════════════════ -->
<?php
$faqs = [
  'q1'=>['q'=>'Bảng báo giá thi công nhà xưởng thông thường bao gồm những gì?',
         'a'=>'Báo giá thi công nhà xưởng của PNMEC thường bao gồm: thiết kế bản vẽ kỹ thuật, cung cấp vật liệu thép kết cấu, gia công chế tạo tại xưởng, vận chuyển và lắp dựng tại công trường, nghiệm thu và bàn giao. Chúng tôi báo giá trọn gói minh bạch không phát sinh.'],
  'q2'=>['q'=>'Thời gian hoàn thành một dự án nhà xưởng tiêu chuẩn là bao lâu?',
         'a'=>'Tùy quy mô dự án, một nhà xưởng tiêu chuẩn từ 500–2.000m² thường hoàn thành trong 45–90 ngày từ khi ký hợp đồng. Dự án lớn trên 5.000m² có thể kéo dài 3–6 tháng. PNMEC cam kết đúng tiến độ với lịch thi công chi tiết từng tuần.'],
  'q3'=>['q'=>'Chi phí gia công cơ khí được tính theo phương thức nào?',
         'a'=>'Chi phí gia công cơ khí được tính dựa trên: đơn giá/kg sản phẩm hoàn thiện hoặc đơn giá/chi tiết tùy loại. Các yếu tố ảnh hưởng gồm độ phức tạp, vật liệu, công nghệ gia công và khối lượng đặt hàng. Đặt số lượng lớn sẽ được ưu đãi.'],
  'q4'=>['q'=>'Tôi có thể sử dụng dịch vụ tư vấn thiết kế trước khi thi công không?',
         'a'=>'Hoàn toàn có. PNMEC cung cấp dịch vụ tư vấn thiết kế miễn phí cho các dự án nhà xưởng và kết cấu thép. Đội ngũ kỹ sư sẽ khảo sát thực địa, phân tích nhu cầu và đề xuất phương án tối ưu về kỹ thuật lẫn chi phí.'],
  'q5'=>['q'=>'Công ty có hỗ trợ bảo hành sau khi bàn giao không?',
         'a'=>'PNMEC cam kết bảo hành kết cấu thép 36 tháng, hệ thống MEP 12 tháng. Trong thời gian bảo hành, chúng tôi xử lý miễn phí mọi sự cố phát sinh do lỗi thi công. Ngoài bảo hành còn cung cấp dịch vụ bảo trì định kỳ theo hợp đồng.'],
];
$first_faq = reset($faqs);
?>
<section class="h-faq">
  <div class="h-faq__bg"></div>
  <div class="container h-faq__inner">
    <div class="h-faq__layout">
      <!-- Left: title + list -->
      <div>
        <span class="h-faq__tag">Hỗ Trợ</span>
        <h2 class="h-faq__heading">Câu Hỏi Thường Gặp</h2>
        <div class="h-faq__list" id="faqList">
          <?php $fi=0; foreach($faqs as $key=>$f): ?>
          <div class="h-faq__item<?= $fi===0?' on':'' ?>" data-ans="<?= htmlspecialchars($f['a']) ?>">
            <button class="h-faq__item-btn">
              <span><?= htmlspecialchars($f['q']) ?></span>
              <i class="fa-solid fa-plus h-faq__ico"></i>
            </button>
          </div>
          <?php $fi++; endforeach; ?>
        </div>
      </div>
      <!-- Right: answer panel -->
      <div class="h-faq__panel-wrap">
        <div class="h-faq__panel">
          <div class="h-faq__indicator"></div>
          <p class="h-faq__ans" id="faqAns"><?= htmlspecialchars($first_faq['a']) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECTION 7 · NHẬN TƯ VẤN & BÁO GIÁ
═══════════════════════════════════════════════════ -->
<section class="h-cta">
  <div class="container">
    <div class="h-cta__title">
      <p class="h-cta__etag">Liên Hệ</p>
      <h2>NHẬN TƯ VẤN &amp; BÁO GIÁ XÂY DỰNG</h2>
    </div>

    <div class="h-cta__grid">
      <!-- Left: company info -->
      <div>
        <div class="h-cta__logo-row">
          <img src="/test/web_cty/assets/images/logo.png?v=2" alt="PNMEC GROUP" style="height: 72px; width: auto; max-width: 100%;">
        </div>
        <ul class="h-cta__info">
          <li><i class="fa-solid fa-location-dot"></i><span>KCN Quang Minh, Mê Linh, Hà Nội (VP)</span></li>
          <li><i class="fa-solid fa-industry"></i><span>Lô C2, KCN Thăng Long II, Yên Mỹ, Hưng Yên</span></li>
          <li><i class="fa-solid fa-phone"></i><a href="tel:0988123456">0988.123.456</a></li>
          <li><i class="fa-solid fa-envelope"></i><a href="mailto:contact@pnmec.vn">contact@pnmec.vn</a></li>
        </ul>
        <div class="h-cta__social">
          <a href="#" class="h-cta__soc-btn"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="h-cta__soc-btn"><i class="fa-brands fa-youtube"></i></a>
          <a href="#" class="h-cta__soc-btn"><i class="fa-solid fa-comment-dots"></i></a>
        </div>
      </div>

      <!-- Right: form -->
      <div class="h-cta__form-box">
        <form method="POST" action="/test/web_cty/contact.php">
          <input type="hidden" name="action" value="contact_form">
          <div class="h-cta__row2">
            <div class="h-cta__field">
              <label for="fn">Họ Tên *</label>
              <input type="text" id="fn" name="name" placeholder="Nguyễn Văn A" required>
            </div>
            <div class="h-cta__field">
              <label for="fp">Số Điện Thoại *</label>
              <input type="tel" id="fp" name="phone" placeholder="0988.xxx.xxx" required>
            </div>
          </div>
          <div class="h-cta__field">
            <label for="fs">Loại Dịch Vụ Cần Tư Vấn?</label>
            <select id="fs" name="service">
              <option value="">Loại Dịch Vụ Cần Tư Vấn...</option>
              <option>Gia công Cơ khí CNC chính xác</option>
              <option>Thi công Nhà xưởng Kết cấu thép</option>
              <option>Xây dựng Công trình Công nghiệp</option>
              <option>Chế tạo Bồn bể &amp; Đường ống</option>
              <option>Lắp đặt Máy móc &amp; Thiết bị</option>
            </select>
          </div>
          <div class="h-cta__field">
            <label for="fm">Nội Dung Cần Tư Vấn</label>
            <textarea id="fm" name="message" rows="4" placeholder="Mô tả ngắn về dự án, quy mô, vị trí, tiến độ mong muốn..."></textarea>
          </div>
          <button type="submit" class="h-cta__submit">
            <i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Tư Vấn
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ─── HOME PAGE SCRIPTS ─── -->
<script>
(function(){
  /* ── Service cards slide-out animation: center to sides (scroll down & up) ── */
  const svcSection = document.querySelector('.h-svc');
  const svcHead    = document.querySelector('.h-svc__head');
  const svcGrid    = document.getElementById('svcGrid');
  const cards      = svcGrid ? svcGrid.querySelectorAll('.h-svc__card') : [];
  if(svcSection && cards.length){
    let timers = [];
    const clearTimers = () => { timers.forEach(t => clearTimeout(t)); timers = []; };
    
    // Từ giữa (thẻ số 2) lan sang 2 bên (thẻ 1 & 3, rồi tới 0 & 4)
    const animSequence = [
      { idx: 2, delay: 0 },
      { idx: 1, delay: 110 },
      { idx: 3, delay: 110 },
      { idx: 0, delay: 230 },
      { idx: 4, delay: 230 }
    ];

    const svcObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        clearTimers();
        if(entry.isIntersecting){
          if(svcHead) svcHead.classList.add('in');
          animSequence.forEach(item => {
            if(cards[item.idx]){
              const t = setTimeout(() => cards[item.idx].classList.add('in'), item.delay);
              timers.push(t);
            }
          });
        } else {
          // Khi cuộn ra khỏi vùng nhìn thấy (kéo xuống hết hoặc kéo lên trên), reset lại
          if(svcHead) svcHead.classList.remove('in');
          cards.forEach(card => card.classList.remove('in'));
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -40px 0px'
    });
    svcObserver.observe(svcSection);
  }

  /* ── Testimonials slider ──────────────────────── */
  const track  = document.getElementById('testiTrack');
  const dots   = document.querySelectorAll('.h-testi__dot');
  const prev   = document.getElementById('testiPrev');
  const next   = document.getElementById('testiNext');
  let cur=0, timer;
  const total = dots.length;

  function go(n){
    cur = (n+total)%total;
    if(track) track.style.transform = `translateX(-${cur*100}%)`;
    dots.forEach((d,i)=>d.classList.toggle('on', i===cur));
  }
  function restart(){ clearInterval(timer); timer=setInterval(()=>go(cur+1),5500); }
  if(track){
    dots.forEach(d=>d.addEventListener('click',()=>{go(+d.dataset.i);restart();}));
    prev && prev.addEventListener('click',()=>{go(cur-1);restart();});
    next && next.addEventListener('click',()=>{go(cur+1);restart();});
    restart();
  }

  /* ── FAQ two-column panel ─────────────────────── */
  const items  = document.querySelectorAll('.h-faq__item');
  const ansEl  = document.getElementById('faqAns');
  items.forEach(item=>{
    item.addEventListener('click',function(){
      items.forEach(i=>i.classList.remove('on'));
      this.classList.add('on');
      if(ansEl) ansEl.textContent = this.dataset.ans||'';
    });
  });

  /* ── Mobile nav ────────────────────────────────── */
  const toggle = document.querySelector('.menu-toggle');
  const menu   = document.querySelector('.nav-menu');
  if(toggle&&menu) toggle.addEventListener('click',()=>menu.classList.toggle('show'));

  /* ── Admin confirm delete ─────────────────────── */
  document.querySelectorAll('.btn-confirm-delete').forEach(b=>{
    b.addEventListener('click',e=>{
      if(!confirm('Xác nhận xóa?')) e.preventDefault();
    });
  });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
