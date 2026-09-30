<?php
/**
 * PNMEC Admin Dashboard Entrypoint
 * Redirects Apache requests from /admin-dashboard/ to production build /admin-dashboard/dist/
 */
if (file_exists(__DIR__ . '/dist/index.html')) {
    header("Location: dist/");
    exit;
} else {
    header("Content-Type: text/html; charset=UTF-8");
    echo '<!DOCTYPE html>
    <html lang="vi">
    <head><meta charset="UTF-8"><title>PNMEC Admin Build Required</title>
    <style>body{font-family:sans-serif;background:#0f172a;color:#fff;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center;}
    .box{background:#1e293b;padding:30px;border-radius:16px;max-width:500px;border:1px solid #334155;}
    h2{color:#f59e0b;margin-top:0;}code{background:#0a0f1d;padding:4px 8px;border-radius:6px;color:#38bdf8;font-size:14px;}</style></head>
    <body><div class="box">
    <h2>PNMEC Admin Dashboard</h2>
    <p>Chưa tìm thấy thư mục build <code>dist/</code>.</p>
    <p>Vui lòng chạy lệnh sau tại thư mục <code>admin-dashboard</code> để tạo bản build:</p>
    <p><code>npm run build</code></p>
    <p><small style="color:#94a3b8">Hoặc tải thư mục <code>dist/</code> đã build từ máy phát triển lên hosting.</small></p>
    </div></body></html>';
    exit;
}
