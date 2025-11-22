<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__. '/includes/header.php';
require_login();
$pdo = db();

// --- Filters ---
$dateFilter = $_GET['date'] ?? 'all';
$categoryFilter = $_GET['category'] ?? 'all';

$whereClauses = [];
$params = [];

if ($dateFilter === 'today') $whereClauses[] = "DATE(created_at)=CURDATE()";
elseif ($dateFilter === 'week') $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
elseif ($dateFilter === 'month') $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";

if ($categoryFilter !== 'all' && $categoryFilter !== '') {
    $whereClauses[] = "category = :category";
    $params[':category'] = $categoryFilter;
}

$whereSQL = '';
if (!empty($whereClauses)) $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);

$catsStmt = $pdo->query("SELECT DISTINCT category FROM announcements ORDER BY category ASC");
$categories = $catsStmt->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT id, message, created_at, COALESCE(is_read,0) AS is_read, COALESCE(category,'General') AS category
        FROM announcements $whereSQL ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Category colors and icons
$categoryColors = [
    'General' => '#198754',
    'Update'  => '#0d6efd',
    'Event'   => '#ffc107',
];
$categoryIcons = [
    'General' => '<i class="bi bi-info-circle-fill me-1"></i>',
    'Update'  => '<i class="bi bi-newspaper me-1"></i>',
    'Event'   => '<i class="bi bi-calendar-event-fill me-1"></i>',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements & Updates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
html, body { height:100%; margin:0; font-family:"Poppins",sans-serif; display:flex; flex-direction:column; background:#f4f6f9; }
.container { flex:1; max-width:900px; margin:auto; padding:30px 20px; }
h2 { font-weight:600; color:#0b5132; margin-bottom:30px; }

.filters select { border-radius:6px; }

/* Timeline-style card */
.timeline {
  position: relative;
  margin-left: 20px;
  padding-left: 20px;
}
.timeline::before {
  content:'';
  position:absolute;
  left:0;
  top:0;
  bottom:0;
  width:4px;
  background:#dee2e6;
  border-radius:2px;
}

.announcement-card {
  position: relative;
  border-left:6px solid #198754;
  background:#fff;
  border-radius:10px;
  padding:18px 20px;
  margin-bottom:25px;
  box-shadow:0 2px 8px rgba(0,0,0,0.06);
  transition:0.2s all;
}
.announcement-card:hover { transform:translateY(-4px); box-shadow:0 6px 20px rgba(0,0,0,0.12); }

.announcement-title { font-size:1.15rem; font-weight:600; color:#198754; }
.badge-cat { border-radius:6px; padding:4px 8px; font-weight:600; font-size:0.85rem; }
.new-badge { background:#dc3545; color:#fff; padding:4px 8px; border-radius:6px; font-size:0.75rem; margin-left:8px; }
.date-text { font-size:0.85rem; color:#6c757d; }
.btn-small { font-size:0.85rem; padding:4px 10px; transition:0.2s all; }
.btn-small:hover { opacity:0.9; }

.footer-small { text-align:center; padding:18px; color:white; font-size:0.9rem; background:#10721d; }
</style>
</head>
<body>

<div class="container">
<h2><i class="bi bi-megaphone-fill me-2"></i>Announcements & Updates</h2>

<!-- Filters -->
<form method="GET" class="row g-2 align-items-center mb-4">
  <div class="col-sm-3">
    <select name="date" class="form-select">
      <option value="all" <?= $dateFilter==='all'?'selected':'' ?>>All dates</option>
      <option value="today" <?= $dateFilter==='today'?'selected':'' ?>>Today</option>
      <option value="week" <?= $dateFilter==='week'?'selected':'' ?>>This week</option>
      <option value="month" <?= $dateFilter==='month'?'selected':'' ?>>This month</option>
    </select>
  </div>
  <div class="col-sm-3">
    <select name="category" class="form-select">
      <option value="all">All categories</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat?'selected':'' ?>><?= htmlspecialchars($cat) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-sm-2 d-grid">
    <button class="btn btn-success" type="submit"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
  </div>
</form>

<div class="timeline">
<?php if(empty($announcements)): ?>
  <div class="alert alert-warning"><i class="bi bi-exclamation-circle me-1"></i>No announcements found.</div>
<?php else: ?>
  <?php foreach($announcements as $a):
      $color = $categoryColors[$a['category']] ?? '#198754';
      $icon = $categoryIcons[$a['category']] ?? '';
  ?>
  <div class="announcement-card" style="border-left-color:<?= $color ?>;" id="announcement-<?= (int)$a['id'] ?>">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <div class="announcement-title">
          <?= $icon ?>Announcement #<?= htmlspecialchars($a['id']) ?>
          <?php if((int)$a['is_read']===0): ?><span class="new-badge">NEW</span><?php endif; ?>
        </div>
        <span class="badge-cat" style="background:<?= $color ?>33; color:<?= $color ?>"><?= htmlspecialchars($a['category']) ?></span>
      </div>
      <div class="text-end date-text"><i class="bi bi-clock me-1"></i><?= htmlspecialchars($a['created_at']) ?></div>
    </div>
    <p class="mt-3"><?= nl2br(htmlspecialchars($a['message'])) ?></p>
    <div class="d-flex justify-content-end">
      <button class="btn btn-sm btn-outline-primary me-2 btn-small" onclick="openShare(<?= (int)$a['id'] ?>)">Share</button>
      <button class="btn btn-sm btn-outline-secondary btn-small" onclick="markRead(<?= (int)$a['id'] ?>, this)" <?= (int)$a['is_read']?'disabled':'' ?>><i class="bi bi-check2-circle me-1"></i>Mark as Read</button>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>
</div>

<div class="text-center mt-4">
  <a href="dashboard.php" class="btn btn-success px-4"><i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard</a>
</div>

</div>

<div class="footer-small">&copy; <?= date('Y') ?> Farmer Feedback & Equipment System. All rights reserved.</div>

<script>
function markRead(id, btn){
  fetch('mark_announcement_read.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'announcement_id='+id })
  .then(r=>r.json()).then(data=>{ if(data.success){ btn.disabled=true; let card=document.getElementById('announcement-'+id); let nb=card.querySelector('.new-badge'); if(nb) nb.remove(); } });
}

function openShare(id){
  const msg=document.querySelector('#announcement-'+id+' p').innerText;
  if(navigator.share){ navigator.share({title:'Announcement #'+id, text:msg}); } else { navigator.clipboard.writeText(msg).then(()=>alert("Copied to clipboard!")); }
}
</script>

</body>
</html>
