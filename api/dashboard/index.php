<?php
require_once dirname(__DIR__) . '/bootstrap.php';

require_api_login();

$db = getDBConnection();
if (!$db) {
    api_response(false, null, 'Không thể kết nối cơ sở dữ liệu.', 500);
}

try {
    $co_khi_cats = [
        'Nhà kết cấu thép', 'Cầu thang - Ban công', 'Mái tôn - Mái che',
        'Nhà cơi nới - Gác lửng', 'Thang thoát hiểm', 'Nhà xe - Mái che',
        'Mái kính', 'Sắt mỹ thuật', 'Cửa các loại', 'Cơ khí chế tạo', 'Kết cấu thép', 'Cơ khí xây dựng'
    ];

    // 1. Projects stats
    $projectsTotal = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $projectsPublished = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'published'")->fetchColumn();
    $projectsDraft = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'draft'")->fetchColumn();
    
    $allProjects = $db->query("SELECT category FROM projects")->fetchAll(PDO::FETCH_COLUMN);
    $projectsCK = 0;
    $projectsXD = 0;
    foreach ($allProjects as $cat) {
        if (in_array($cat, $co_khi_cats)) {
            $projectsCK++;
        } else {
            $projectsXD++;
        }
    }

    // 2. Services stats
    $servicesTotal = (int)$db->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $servicesFeatured = (int)$db->query("SELECT COUNT(*) FROM services WHERE featured = 1")->fetchColumn();
    $servicesCK = (int)$db->query("SELECT COUNT(*) FROM services WHERE code LIKE 'CK%'")->fetchColumn();
    $servicesXD = (int)$db->query("SELECT COUNT(*) FROM services WHERE code LIKE 'XD%'")->fetchColumn();

    // 3. News stats
    $newsTotal = (int)$db->query("SELECT COUNT(*) FROM news")->fetchColumn();

    // 4. Quotes stats
    $quotesTotal = (int)$db->query("SELECT COUNT(*) FROM quotes")->fetchColumn();
    $quotesNew = (int)$db->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
    $quotesProcessing = (int)$db->query("SELECT COUNT(*) FROM quotes WHERE status = 'processing'")->fetchColumn();
    $quotesCompleted = (int)$db->query("SELECT COUNT(*) FROM quotes WHERE status = 'completed'")->fetchColumn();

    // 5. Contacts stats
    $contactsTotal = (int)$db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    $contactsUnread = (int)$db->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

    // 6. Users stats
    $usersTotal = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // 7. Recent Quotes
    $recentQuotes = $db->query("SELECT id, fullname, phone, email, service_type, status, created_at FROM quotes ORDER BY id DESC LIMIT 5")->fetchAll();

    // 8. Recent Contacts
    $recentContacts = $db->query("SELECT id, name, email, phone, subject, status, created_at FROM contacts ORDER BY id DESC LIMIT 5")->fetchAll();

    // 9. Recent Projects
    $recentProjects = $db->query("SELECT id, title, category, client, location, image, status, created_at FROM projects ORDER BY id DESC LIMIT 5")->fetchAll();

    // 10. Monthly trend for chart (last 6 months)
    $monthlyStats = [];
    for ($i = 5; $i >= 0; $i--) {
        $monthStart = date('Y-m-01 00:00:00', strtotime("-$i months"));
        $monthEnd   = date('Y-m-t 23:59:59', strtotime("-$i months"));
        $label      = date('m/Y', strtotime("-$i months"));

        $qStmt = $db->prepare("SELECT COUNT(*) FROM quotes WHERE created_at BETWEEN :start AND :end");
        $qStmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
        $qCount = (int)$qStmt->fetchColumn();

        $cStmt = $db->prepare("SELECT COUNT(*) FROM contacts WHERE created_at BETWEEN :start AND :end");
        $cStmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
        $cCount = (int)$cStmt->fetchColumn();

        $pStmt = $db->prepare("SELECT COUNT(*) FROM projects WHERE created_at BETWEEN :start AND :end");
        $pStmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
        $pCount = (int)$pStmt->fetchColumn();

        $monthlyStats[] = [
            'month' => $label,
            'quotes' => $qCount,
            'contacts' => $cCount,
            'projects' => $pCount,
        ];
    }

    api_response(true, [
        'stats' => [
            'projects' => [
                'total' => $projectsTotal,
                'published' => $projectsPublished,
                'draft' => $projectsDraft,
                'co_khi' => $projectsCK,
                'xay_dung' => $projectsXD,
            ],
            'services' => [
                'total' => $servicesTotal,
                'featured' => $servicesFeatured,
                'co_khi' => $servicesCK,
                'xay_dung' => $servicesXD,
            ],
            'news' => [
                'total' => $newsTotal,
            ],
            'quotes' => [
                'total' => $quotesTotal,
                'new' => $quotesNew,
                'processing' => $quotesProcessing,
                'completed' => $quotesCompleted,
            ],
            'contacts' => [
                'total' => $contactsTotal,
                'unread' => $contactsUnread,
            ],
            'users' => [
                'total' => $usersTotal,
            ],
        ],
        'recent_quotes' => $recentQuotes,
        'recent_contacts' => $recentContacts,
        'recent_projects' => $recentProjects,
        'monthly_trends' => $monthlyStats,
    ], 'Lấy dữ liệu thống kê dashboard thành công.');
} catch (Exception $e) {
    api_response(false, null, 'Lỗi lấy thống kê: ' . $e->getMessage(), 500);
}
