# Kiến Trúc Hệ Thống Website PNMEC

Dự án PNMEC được tổ chức theo mô hình **Monorepo** tinh gọn, kết hợp giữa giao diện người dùng Server-Side Rendered (PHP) và trang quản trị Single Page Application (React) trên nền RESTful API dùng chung cơ sở dữ liệu MySQL.

---

## 1. Bản Đồ Thành Phần Kiến Trúc

```text
┌────────────────────────────────────────────────────────────────────────┐
│                              NGƯỜI DÙNG                                │
└──────────────────┬─────────────────────────────────┬───────────────────┘
                   │ HTTP GET                        │ HTTP (Admin SPA)
                   ▼                                 ▼
┌────────────────────────────────────┐ ┌─────────────────────────────────┐
│     PUBLIC FRONTEND (PHP SSR)      │ │   ADMIN FRONTEND (React SPA)    │
│  - index.php, about.php, ...       │ │  - admin-dashboard/dist/        │
│  - Chuẩn SEO, tải nhanh            │ │  - React 18, Vite, Lucide, Axios│
│  - Render HTML trực tiếp từ DB     │ │  - HashRouter (tương thích host)│
└──────────────────┬─────────────────┘ └────────────────┬────────────────┘
                   │                                    │ AJAX / JSON
                   │ Gọi hàm nội bộ                     ▼
                   │                   ┌─────────────────────────────────┐
                   │                   │      BACKEND REST API (PHP)     │
                   │                   │  - api/auth, api/projects, ...  │
                   │                   │  - Xác thực Session + CSRF Guard│
                   │                   │  - Rate Limiter & Sanitize Input│
                   └───────────┬───────┴────────────────┬────────────────┘
                               │                        │
                               ▼                        ▼
                   ┌─────────────────────────────────────────────────────┐
                   │               LỚP DỮ LIỆU & TÀI NGUYÊN              │
                   │  - Database: MySQL / MariaDB (PDO, Prepared Stmts)  │
                   │  - Config: config/local.php (Tách biệt credentials) │
                   │  - Static Media: assets/images/ (Track bởi Git)     │
                   │  - Dynamic Media: assets/uploads/ (Runtime data)    │
                   │  - Cache/Rate-limit: storage/rate-limit/ (No Redis) │
                   └─────────────────────────────────────────────────────┘
```

---

## 2. Chi Tiết Các Lớp Chức Năng

### 1. Public Frontend (PHP Server-Side Rendering)
- **Vị trí:** Thư mục gốc (`/`, `includes/`, `assets/css/`, `assets/js/`).
- **Nhiệm vụ:** Phục vụ khách hàng truy cập website giới thiệu, xem dịch vụ, hồ sơ năng lực công trình, gửi biểu mẫu báo giá và liên hệ.
- **Đặc điểm:** Tối ưu hóa SEO, semantic HTML, hỗ trợ helper hàm `url()` và `asset_url()` để tự thích ứng với mọi base path (chạy ở localhost, root domain hoặc cPanel subfolder).

### 2. Admin Frontend (React Single Page Application)
- **Vị trí source:** `admin-dashboard/src/`
- **Vị trí build:** `admin-dashboard/dist/`
- **Nhiệm vụ:** Bảng điều khiển quản trị toàn diện: thống kê tổng quan, quản lý bài viết tin tức, quản lý dịch vụ nổi bật, quản lý công trình và trình soạn thảo **Project Detail Builder**.
- **Đặc điểm:** Chạy hoàn toàn dưới dạng tài nguyên tĩnh (HTML/JS/CSS), giao tiếp với Backend thông qua REST API cùng nguồn (Same-Origin). Sử dụng `HashRouter` để không phụ thuộc vào các quy tắc rewrite URL phức tạp của web server.

### 3. Backend REST API (PHP)
- **Vị trí:** `api/`
- **Nhiệm vụ:** Cung cấp các endpoints CRUD dữ liệu cho Admin Dashboard và tiếp nhận các submission từ phía khách hàng.
- **Bảo mật:**
  - **CSRF Protection:** Xác thực `X-CSRF-Token` trên tất cả mutation requests (POST/PUT/DELETE) bằng hàm `hash_equals()`.
  - **CORS Whitelist:** Chỉ cấp phát origin tin cậy (như Vite local dev), tuyệt đối không phản chiếu arbitrary origin khi `Credentials = true`.
  - **Rate Limiting:** Sử dụng file storage kèm khóa tập tin `flock()` để chống brute-force và spam mà không phụ thuộc vào Redis/Memcached.
  - **Sanitization:** Lọc mã HTML nguy hiểm (XSS) trong Custom Project Builder bằng bộ phân tích cú pháp `DOMDocument` allow-list.

### 4. Database (MySQL / MariaDB)
- **Baseline Schema:** `database/schema.sql` (Cấu trúc bảng chuẩn mực, chỉ mục, khóa ngoại).
- **Phát triển liên tục (Migrations):** `database/migrations/` (Các tập tin SQL gia tăng theo thời gian).
- **Dữ liệu mẫu (Seed):** `database/seed/dev_seed.sql` (Dữ liệu mẫu dùng cho môi trường phát triển).

### 5. Quản Lý Media & Tài Nguyên Tập Tin
- **Static Assets (`assets/images/`):** Chứa ảnh tĩnh của giao diện (logo, icon, banner nền) được commit vào Git.
- **Dynamic Uploads (`assets/uploads/`):** Chứa toàn bộ hình ảnh do người dùng và admin tải lên lúc runtime:
  - `projects/`, `projects/details/`
  - `services/`
  - `news/`
  - Được bảo vệ bằng `assets/uploads/.htaccess` để cấm hoàn toàn việc thực thi các kịch bản PHP/CGI.
  - Được gitignore để tránh đẩy tài nguyên runtime vào kho mã nguồn.

### 6. Cấu Hình Private (`config/local.php`)
- **Tập tin mẫu:** `config/local.example.php` (lưu trong Git).
- **Tập tin thực thi:** `config/local.php` (bị gitignore).
- Toàn bộ bí mật bao gồm mật khẩu CSDL, cấu hình base URL, cổng kết nối đều được quản lý tại file này hoặc thông qua biến môi trường của hệ thống.
