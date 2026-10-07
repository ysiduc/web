#!/usr/bin/env bash
# ==============================================================================
# Script Backup Toàn Diện (MySQL Dump + assets/uploads)
# Dự án: Web Công ty Cơ khí & Xây dựng PNMEC
# ==============================================================================
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
BACKUP_DIR="$ROOT_DIR/backups"
TIMESTAMP="$(date +'%Y%m%d_%H%M%S')"

mkdir -p "$BACKUP_DIR"

echo "======================================================"
echo " [PNMEC] Bắt đầu quá trình sao lưu hệ thống: $TIMESTAMP"
echo "======================================================"

# 1. Đọc cấu hình từ config/local.php nếu có
PHP_BIN="php"
if [ -x "/opt/lampp/bin/php" ]; then
    PHP_BIN="/opt/lampp/bin/php"
fi

CONFIG_FILE="$ROOT_DIR/config/local.php"
if [ -f "$CONFIG_FILE" ]; then
    echo "[+] Đang đọc thông tin cấu hình từ config/local.php..."
    DB_HOST=$($PHP_BIN -r '$c = require "'$CONFIG_FILE'"; echo $c["db_host"] ?? "localhost";')
    DB_NAME=$($PHP_BIN -r '$c = require "'$CONFIG_FILE'"; echo $c["db_name"] ?? "web_cty";')
    DB_USER=$($PHP_BIN -r '$c = require "'$CONFIG_FILE'"; echo $c["db_user"] ?? "root";')
    DB_PASS=$($PHP_BIN -r '$c = require "'$CONFIG_FILE'"; echo $c["db_pass"] ?? "";')
    DB_PORT=$($PHP_BIN -r '$c = require "'$CONFIG_FILE'"; echo $c["db_port"] ?? "3306";')
else
    echo "[!] Không tìm thấy config/local.php, sử dụng biến môi trường hoặc mặc định..."
    DB_HOST="${DB_HOST:-localhost}"
    DB_NAME="${DB_NAME:-web_cty}"
    DB_USER="${DB_USER:-root}"
    DB_PASS="${DB_PASS:-}"
    DB_PORT="${DB_PORT:-3306}"
fi

# Cho phép override qua tham số dòng lệnh nếu cần
# Cú pháp: ./backup-local.sh [db_name] [db_user] [db_pass] [db_host]
if [ -n "$1" ]; then DB_NAME="$1"; fi
if [ -n "$2" ]; then DB_USER="$2"; fi
if [ -n "$3" ]; then DB_PASS="$3"; fi
if [ -n "$4" ]; then DB_HOST="$4"; fi

SQL_FILE="$BACKUP_DIR/db_${DB_NAME}_${TIMESTAMP}.sql"
SQL_GZ_FILE="$SQL_FILE.gz"
UPLOADS_TAR="$BACKUP_DIR/uploads_${TIMESTAMP}.tar.gz"

# 2. Dump MySQL database
MYSQLDUMP_BIN="mysqldump"
if [ -x "/opt/lampp/bin/mysqldump" ]; then
    MYSQLDUMP_BIN="/opt/lampp/bin/mysqldump"
fi

echo "[+] Đang xuất cơ sở dữ liệu '$DB_NAME'..."
if [ -z "$DB_PASS" ]; then
    $MYSQLDUMP_BIN -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" --single-transaction --quick > "$SQL_FILE"
else
    MYSQL_PWD="$DB_PASS" $MYSQLDUMP_BIN -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" --single-transaction --quick > "$SQL_FILE"
fi

gzip -f "$SQL_FILE"
echo "[✓] Đã tạo file CSDL: $SQL_GZ_FILE ($(du -h "$SQL_GZ_FILE" | cut -f1))"

# 3. Nén thư mục assets/uploads/
if [ -d "$ROOT_DIR/assets/uploads" ]; then
    echo "[+] Đang nén tài nguyên ảnh runtime trong assets/uploads/..."
    tar -czf "$UPLOADS_TAR" -C "$ROOT_DIR/assets" uploads
    echo "[✓] Đã tạo file uploads: $UPLOADS_TAR ($(du -h "$UPLOADS_TAR" | cut -f1))"
else
    echo "[!] Thư mục assets/uploads không tồn tại, bỏ qua nén uploads."
fi

echo "======================================================"
echo "[✓] QUÁ TRÌNH SAO LƯU HOÀN TẤT!"
echo "    Thư mục lưu trữ: $BACKUP_DIR"
echo "    Lưu ý: Các file sao lưu được tự động ignore bởi Git."
echo "======================================================"
