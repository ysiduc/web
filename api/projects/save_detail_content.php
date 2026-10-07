<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_response(false, null, 'Phương thức không được hỗ trợ.', 405);
}

$input = get_api_input();
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
    api_response(false, null, 'ID công trình không hợp lệ.', 400);
}

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

// Verify project exists
$stmtCheck = $db->prepare("SELECT id FROM projects WHERE id = :id LIMIT 1");
$stmtCheck->execute(['id' => $id]);
if (!$stmtCheck->fetch()) {
    api_response(false, null, 'Không tìm thấy công trình.', 404);
}

$detail_mode = in_array($input['detail_mode'] ?? '', ['basic', 'custom']) ? $input['detail_mode'] : 'basic';
$raw_blocks = $input['detail_blocks'] ?? [];

if (!is_array($raw_blocks)) {
    api_response(false, null, 'Dữ liệu blocks phải là một danh sách hợp lệ.', 400);
}

/**
 * Sanitize custom HTML using DOMDocument allow-list
 */
function sanitize_custom_html(string $html): string {
    return sanitize_html_content($html);
}

$allowed_types = ['heading', 'paragraph', 'image', 'gallery', 'callout', 'divider', 'html'];
$clean_blocks = [];

foreach ($raw_blocks as $idx => $b) {
    if (!is_array($b)) continue;
    $type = trim($b['type'] ?? '');
    if (!in_array($type, $allowed_types)) continue;

    $block_id = !empty($b['id']) ? trim($b['id']) : ('blk_' . uniqid() . '_' . $idx);
    $clean_block = [
        'id'   => $block_id,
        'type' => $type
    ];

    switch ($type) {
        case 'heading':
            $level = (int)($b['level'] ?? 2);
            if ($level < 2 || $level > 4) $level = 2;
            $clean_block['level'] = $level;
            $clean_block['text']  = trim($b['text'] ?? '');
            break;

        case 'paragraph':
            $clean_block['text'] = trim($b['text'] ?? '');
            break;

        case 'image':
            $clean_block['src']     = trim($b['src'] ?? '');
            $clean_block['alt']     = trim($b['alt'] ?? '');
            $clean_block['caption'] = trim($b['caption'] ?? '');
            break;

        case 'gallery':
            $raw_images = is_array($b['images'] ?? null) ? $b['images'] : [];
            $clean_images = [];
            foreach ($raw_images as $img) {
                if (!is_array($img)) continue;
                $src = trim($img['src'] ?? '');
                if (!empty($src)) {
                    $clean_images[] = [
                        'src'     => $src,
                        'alt'     => trim($img['alt'] ?? ''),
                        'caption' => trim($img['caption'] ?? '')
                    ];
                }
            }
            $clean_block['images'] = $clean_images;
            break;

        case 'callout':
            $clean_block['text']    = trim($b['text'] ?? '');
            $clean_block['title']   = trim($b['title'] ?? '');
            $clean_block['variant'] = in_array($b['variant'] ?? '', ['info', 'warning', 'success', 'gold']) ? $b['variant'] : 'gold';
            break;

        case 'divider':
            // No extra properties required
            break;

        case 'html':
            $clean_block['content'] = sanitize_custom_html($b['content'] ?? '');
            break;
    }

    $clean_blocks[] = $clean_block;
}

$json_blocks = json_encode($clean_blocks, JSON_UNESCAPED_UNICODE);

try {
    $stmt = $db->prepare("UPDATE projects SET detail_mode = :detail_mode, detail_blocks = :detail_blocks WHERE id = :id");
    $stmt->execute([
        'detail_mode'   => $detail_mode,
        'detail_blocks' => $json_blocks,
        'id'            => $id
    ]);

    api_response(true, [
        'id'            => $id,
        'detail_mode'   => $detail_mode,
        'detail_blocks' => $clean_blocks
    ], 'Lưu nội dung chi tiết dự án thành công!');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lưu chi tiết dự án: ' . $e->getMessage(), 500);
}
