# Hướng Dẫn Triển Khai Website Lên cPanel (PNMEC)

Tài liệu hướng dẫn quy trình triển khai toàn diện dự án website PNMEC lên môi trường cPanel shared hosting / Cloud VPS chạy Apache/LiteSpeed, PHP (>= 8.0) và MySQL/MariaDB.

---

## 1. Kiến Trúc Môi Trường Production
- **Web Server:** Apache hoặc LiteSpeed (hỗ trợ `mod_rewrite`, `mod_headers`).
- **PHP:** Phiên bản 8.0, 8.1 hoặc 8.2 trở lên (yêu cầu extensions: `pdo_mysql`, `fileinfo`, `gd` hoặc `imagick`, `mbstring`, `dom`).
- **Cơ sở dữ liệu:** MySQL 5.7+ hoặc MariaDB 10.3+.
- **Node.js:** KHÔNG cần chạy Node.js trên server. Ứng dụng React Admin Dashboard chạy hoàn toàn dưới dạng tĩnh (`dist/`).

---

## 2. Quy Trình Triển Khai Chi Tiết

### Bước 1: Chuẩn bị Source Code & Build React Admin

Tuỳ vào hosting của bạn có hỗ trợ Node.js hay không:

#### Trường hợp A: Hosting KHÔNG có Node.js (Phổ biến nhất trên cPanel)
1. Tại máy tính local (hoặc máy CI/CD):
   ```bash
   bash scripts/build-cpanel.sh
   # Hoặc thủ công:
   cd admin-dashboard
   npm install
   npm run build
   ```
2. Thao tác này sinh ra thư mục `admin-dashboard/dist/`.
3. Đóng gói mã nguồn (hoặc push qua Git) và tải lên hosting sao cho thư mục `admin-dashboard/dist/` đã có sẵn các file `index.html`, `assets/...`.

#### Trường hợp B: Hosting CÓ Terminal & Node.js
1. Pull source code về thư mục website trên cPanel:
   ```bash
   git clone https://github.com/ysiduc/web.git .
   ```
2. Chạy script build:
   ```bash
   bash scripts/build-cpanel.sh
   ```

---

### Bước 2: Cấu Hình Môi Trường (`config/local.php`)

Mã nguồn được thiết kế tách biệt hoàn toàn cấu hình khỏi Git (`config/local.php` được gitignore).

1. Trên cPanel File Manager hoặc SSH, copy file mẫu:
   ```bash
   cp config/local.example.php config/local.php
   ```
2. Mở file `config/local.php` và điền thông tin phù hợp:
   ```php
   <?php
   return [
       // CẤU HÌNH ĐƯỜNG DẪN GỐC (APP_BASE_URL)
       // - Nếu website chạy ở tên miền chính (https://example.com/): để chuỗi rỗng ''
       // - Nếu website chạy ở thư mục con (https://example.com/web/): để '/web'
       'app_base_url' => '',

       // CẤU HÌNH CƠ SỞ DỮ LIỆU
       'db_host' => 'localhost',
       'db_name' => 'cpaneluser_webcty',
       'db_user' => 'cpaneluser_dbuser',
       'db_pass' => 'MatKhauPhucTap_Random123!',
       'db_port' => 3306,

       // WHITELIST CORS (Chỉ dùng nếu gọi API chéo domain/subdomain)
       'cors_allowed_origins' => [],
   ];
   ```

---

### Bước 3: Tạo Database & Import Dữ Liệu Ban Đầu

1. Vào **cPanel > MySQL Databases**:
   - Tạo mới một Database (ví dụ: `cpaneluser_webcty`).
   - Tạo mới một Database User và đặt mật khẩu an toàn.
   - Gán quyền User vào Database với **ALL PRIVILEGES**.
2. Vào **cPanel > phpMyAdmin**:
   - Chọn database vừa tạo.
   - Nhấn tab **Import**:
     - Import file schema nền tảng: `database/schema.sql`.
     - Nếu có các file cập nhật tiếp theo trong `database/migrations/*.sql`, chạy tuần tự theo thứ tự số tăng dần.
     - *(Tùy chọn)* Nếu cần dữ liệu mẫu cho môi trường staging, có thể import `database/seed/dev_seed.sql`. **KHÔNG** import dev seed trên live production nếu đã có dữ liệu thực tế.

---

### Bước 4: Thiết Lập Quyền File & Thư Mục (File Permissions)

Tuân thủ nguyên tắc bảo mật tối thiểu:
- **Thư mục (Directories):** `0755`
- **Tập tin (Files):** `0644`
- **File cấu hình (`config/local.php`):** `0600` hoặc `0640` (ngăn người dùng khác trên cùng shared server đọc được thông tin database).
- **Thư mục upload (`assets/uploads/`):** `0755` (hoặc `0775` tùy theo cấu hình user/group của PHP handler suPHP, FastCGI, LSPHP). **Tuyệt đối KHÔNG gán 0777.**
- **Thư mục dữ liệu (`storage/rate-limit/`):** `0755` (đảm bảo PHP web server có quyền ghi file rate limit).

Lệnh SSH nhanh nếu có quyền terminal:
```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod 600 config/local.php
chmod -R 755 assets/uploads storage
```

---

### Bước 5: Kiểm Tra Bảo Mật Apache (.htaccess)

Hệ thống đã tích hợp các file bảo vệ:
1. **Root `.htaccess`**: Tự động deny truy cập trực tiếp qua HTTP tới `database/`, `config/`, `storage/`, và các phần mở rộng `.sql`, `.env`, `.log`, `.bak`.
2. **`assets/uploads/.htaccess`**: Tắt Directory Browsing (`Options -Indexes`) và chặn tuyệt đối thực thi script PHP (`php_flag engine off` hoặc `Require all denied` cho file mã nguồn).
3. **`storage/.htaccess`**: Chặn toàn bộ truy cập từ trình duyệt (`Require all denied`).

---

### Bước 6: Kiểm Tra Hoạt Động (Verification Checklist)

1. **Trang chủ & Public Pages:**
   - Truy cập `https://yourdomain.com/`
   - Kiểm tra `about.php`, `services.php`, `projects.php`, `news.php`, `contact.php`, `quote.php`.
   - Xác nhận CSS, ảnh tĩnh và hình ảnh tải đầy đủ, không bị lỗi 404/500.
2. **React Admin Dashboard:**
   - Truy cập `https://yourdomain.com/admin/` (hoặc `https://yourdomain.com/admin-dashboard/dist/`).
   - Đăng nhập tài khoản quản trị (mặc định: `admin` / mật khẩu đã đổi).
   - Kiểm tra các màn hình: Dashboard, Công trình (CRUD + Custom Detail Editor), Dịch vụ, Tin tức, Báo giá, Liên hệ, Cài đặt.
   - Thử nghiệm upload một ảnh công trình mới để xác nhận quyền ghi thư mục hoạt động chuẩn xác.
3. **Kiểm tra ngăn chặn truy cập tệp nhạy cảm:**
   - Chạy thử từ máy ngoài:
     ```bash
     curl -I https://yourdomain.com/config/db.php
     curl -I https://yourdomain.com/database/schema.sql
     ```
     Phải nhận về mã phản hồi `403 Forbidden` hoặc `404 Not Found`.

---

## 3. Checklist An Ninh Nâng Cao (WAF, SSL, Cloudflare)

DDoS và tấn công diện rộng phải được ngăn chặn trước tầng PHP:

```text
Internet
   ↓
Cloudflare (DDoS Mitigation, Edge SSL, WAF, Bot Fight Mode)
   ↓
cPanel Server (ModSecurity + OWASP Core Rule Set)
   ↓
Apache / LiteSpeed (.htaccess rules + Security Headers)
   ↓
PHP Application (Rate Limiter, CSRF Guard, DOMPurify Sanitizer, Upload Validator)
   ↓
MySQL Database (Bcrypt Passwords, PDO Prepared Statements)
```

- [ ] **Bật AutoSSL / Let's Encrypt:** Đảm bảo toàn bộ traffic chạy qua HTTPS trước khi bàn giao.
- [ ] **Bật ModSecurity:** Kích hoạt ModSecurity trong cPanel và bật bộ luật OWASP CRS nếu nhà cung cấp hỗ trợ.
- [ ] **Cấu hình Cloudflare (khuyên dùng):**
  - Trỏ DNS qua proxy đám mây (bật đám mây màu cam).
  - Bật **Always Use HTTPS** và thiết lập SSL mode là **Full (Strict)**.
  - Bật **Browser Integrity Check** và **Bot Fight Mode**.
  - Thiết lập **Cloudflare Rate Limiting Rule** cho các đường dẫn nhạy cảm:
    - `/api/auth/login.php`
    - `/contact.php`
    - `/quote.php`
    - `/api/upload.php`

---

## 4. Chiến Lược Sao Lưu (Backup Strategy)

> **Cảnh báo quan trọng:** GitHub chỉ lưu trữ mã nguồn tĩnh. Toàn bộ nội dung bài viết, công trình, thông tin khách hàng và hình ảnh upload thực tế nằm ở **MySQL Database** và thư mục **`assets/uploads/`**.

### Kế hoạch backup định kỳ:
1. **Sao lưu thủ công qua script:**
   Chạy script được cung cấp sẵn trên server:
   ```bash
   bash scripts/backup-local.sh
   ```
   Script sẽ tự động sinh file dump database (`.sql.gz`) và gói nén uploads (`.tar.gz`) trong thư mục `backups/`.
2. **Sao lưu tự động qua cPanel Backup Wizard:**
   - Thiết lập cron job hàng tuần hoặc sử dụng tính năng **cPanel Backup Wizard** / **JetBackup** của nhà cung cấp hosting để tự động lưu sang dịch vụ lưu trữ từ xa (Google Drive, Amazon S3, v.v.).
