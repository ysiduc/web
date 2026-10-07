#!/usr/bin/env bash
# ==============================================================================
# Script Build React Admin cho cPanel Deployment
# Dự án: Web Công ty Cơ khí & Xây dựng PNMEC
# ==============================================================================
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
ADMIN_DIR="$ROOT_DIR/admin-dashboard"

echo "======================================================"
echo " [PNMEC] Bắt đầu quá trình build React Admin cho cPanel"
echo "======================================================"

# 1. Kiểm tra Node.js và npm
if ! command -v node >/dev/null 2>&1; then
    echo "[-] LỖI: Node.js chưa được cài đặt trên máy này."
    echo "    Vui lòng cài đặt Node.js LTS (>= 18) để thực hiện build."
    exit 1
fi

if ! command -v npm >/dev/null 2>&1; then
    echo "[-] LỖI: npm chưa được cài đặt."
    exit 1
fi

echo "[+] Node.js: $(node -v)"
echo "[+] npm: $(npm -v)"

# 2. Cài đặt dependencies và build
cd "$ADMIN_DIR"
echo "[+] Đang cài đặt thư viện npm tại admin-dashboard..."
npm install

echo "[+] Đang thực hiện build production (npm run build)..."
npm run build

# 3. Kiểm tra tính toàn vẹn của dist/
if [ ! -f "$ADMIN_DIR/dist/index.html" ]; then
    echo "[-] LỖI: Thư mục dist/index.html không tồn tại sau khi build!"
    exit 1
fi

echo "======================================================"
echo "[✓] BUILD THÀNH CÔNG!"
echo "    Thư mục output: $ADMIN_DIR/dist"
echo "======================================================"
echo "HƯỚNG DẪN DEPLOY LÊN CPANEL:"
echo "1. Nếu cPanel KHÔNG CÓ Node.js:"
echo "   - Copy toàn bộ nội dung thư mục 'admin-dashboard/dist/'"
echo "     lên hosting tại đường dẫn tương ứng: 'public_html/admin-dashboard/dist/'"
echo "2. Nếu cPanel CÓ Terminal / Node.js:"
echo "   - Có thể chạy trực tiếp: bash scripts/build-cpanel.sh ngay trên hosting."
echo "3. Website PHP backend chạy độc lập, không cần bất kỳ tiến trình Node.js nền nào."
echo "======================================================"
