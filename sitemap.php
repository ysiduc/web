<?php
/**
 * Dynamic XML Sitemap Generator for PNMEC
 * Generates search-engine compliant XML sitemap from MySQL database
 * Only includes published projects, active services, published news, and valid public pages.
 */
require_once __DIR__ . '/includes/functions.php';

header("Content-Type: application/xml; charset=utf-8");
header("X-Robots-Tag: noindex, follow", true);

$base_url = 'https://pnmec.vn';

$db = getDBConnection();
$services = [];
$projects = [];
$news = [];

if ($db) {
    try {
        $stmtS = $db->query("SELECT id, created_at FROM services WHERE status = 'active' ORDER BY id DESC");
        $services = $stmtS ? $stmtS->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Exception $e) {}

    try {
        $stmtP = $db->query("SELECT id, created_at FROM projects WHERE status = 'published' ORDER BY id DESC");
        $projects = $stmtP ? $stmtP->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Exception $e) {}

    try {
        $stmtN = $db->query("SELECT id, created_at FROM news WHERE status = 'published' ORDER BY id DESC");
        $news = $stmtN ? $stmtN->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (Exception $e) {}
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <!-- Core Static Pages -->
  <url>
    <loc><?= $base_url ?>/</loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/about</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/services</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/projects</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/recruitment</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/news</loc>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/contact</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base_url ?>/quote</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>

  <!-- Published Services (Real DB Records) -->
  <?php foreach ($services as $svc): 
    $lastmod = !empty($svc['created_at']) ? date('Y-m-d', strtotime($svc['created_at'])) : date('Y-m-d');
    $loc = $base_url . '/service-detail?id=' . (int)$svc['id'];
  ?>
  <url>
    <loc><?= htmlspecialchars($loc, ENT_XML1, 'UTF-8') ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>

  <!-- Published Projects (Real DB Records) -->
  <?php foreach ($projects as $prj): 
    $lastmod = !empty($prj['created_at']) ? date('Y-m-d', strtotime($prj['created_at'])) : date('Y-m-d');
    $loc = $base_url . '/project-detail?id=' . (int)$prj['id'];
  ?>
  <url>
    <loc><?= htmlspecialchars($loc, ENT_XML1, 'UTF-8') ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>

  <!-- Published News Articles (Real DB Records) -->
  <?php foreach ($news as $n): 
    $lastmod = !empty($n['created_at']) ? date('Y-m-d', strtotime($n['created_at'])) : date('Y-m-d');
    $loc = $base_url . '/news-detail?id=' . (int)$n['id'];
  ?>
  <url>
    <loc><?= htmlspecialchars($loc, ENT_XML1, 'UTF-8') ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  <?php endforeach; ?>
</urlset>
