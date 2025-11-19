<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();

// --- Filters from GET ---
$dateFilter = $_GET['date'] ?? 'all';
$categoryFilter = $_GET['category'] ?? 'all';
$search = trim($_GET['q'] ?? '');

$whereClauses = [];
$params = [];

// Date filter
if ($dateFilter === 'today') {
    $whereClauses[] = "DATE(created_at) = CURDATE()";
} elseif ($dateFilter === 'week') {
    $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($dateFilter === 'month') {
    $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

if ($categoryFilter !== 'all' && $categoryFilter !== '') {
    $whereClauses[] = "category = :category";
    $params[':category'] = $categoryFilter;
}

if ($search !== '') {
    $whereClauses[] = "message LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

$catsStmt = $pdo->query("SELECT DISTINCT category FROM announcements ORDER BY category ASC");
$categories = $catsStmt->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT id, message, created_at, COALESCE(is_read,0) AS is_read, COALESCE(category,'General') AS category
        FROM announcements
        $whereSQL
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Announcements & Updates</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f4f6f9; font-family: "Poppins", sans-serif; }
    .announcement-card { border-left: 6px solid #198754; border-radius: 12px; padding: 18px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
    .announcement-title { font-size: 1.15rem; color: #198754; font-weight: 600; }
    .date-text { font-size: 0.85rem; color: #6c757d; }
    .badge-cat { background: #e9f7ef; color:#19692f; border-radius: 6px; padding: 4px 8px; font-weight:600; font-size:0.8rem; }
    .new-badge { background: #dc3545; color: #fff; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; margin-left: 8px; }
    .footer-small { text-align:center; padding:18px; color:#6c757d; font-size:0.9rem; }
  </style>
</head>
<body>
<div class="container py-4">

  <h2 class="mb-4"><i class="bi bi-megaphone-fill me-2"></i>Announcements & Updates</h2>

  <!-- Filters -->
  <form method="GET" class="row g-2 align-items-center filters mb-4">
    <div class="col-sm-4">
      <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search announcements..." class="form-control" />
    </div>

    <div class="col-sm-3">
      <select name="date" class="form-select">
        <option value="all" <?= $dateFilter === 'all' ? 'selected' : '' ?>>All dates</option>
        <option value="today" <?= $dateFilter === 'today' ? 'selected' : '' ?>>Today</option>
        <option value="week" <?= $dateFilter === 'week' ? 'selected' : '' ?>>This week</option>
        <option value="month" <?= $dateFilter === 'month' ? 'selected' : '' ?>>This month</option>
      </select>
    </div>

    <div class="col-sm-3">
      <select name="category" class="form-select">
        <option value="all">All categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat) ?>
            </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-sm-2 d-grid">
      <button class="btn btn-success" type="submit"><i class="bi bi-funnel-fill me-1"></i>Filter</button>
    </div>
  </form>

  <!-- Announcement List -->
  <?php if (empty($announcements)): ?>
    <div class="alert alert-warning"><i class="bi bi-exclamation-circle me-1"></i>No announcements found.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach ($announcements as $a): ?>
        <div class="col-12 mb-3">
          <div class="announcement-card" id="announcement-<?= (int)$a['id'] ?>">

            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="announcement-title">
                  Announcement #<?= htmlspecialchars($a['id']) ?>
                  <?php if ((int)$a['is_read'] === 0): ?>
                    <span class="new-badge">NEW</span>
                  <?php endif; ?>
                </div>
                <span class="badge-cat mt-1"><?= htmlspecialchars($a['category']) ?></span>
              </div>

              <div class="text-end date-text">
                <i class="bi bi-clock me-1"></i><?= htmlspecialchars($a['created_at']) ?>
              </div>
            </div>

            <p class="mt-3"><?= nl2br(htmlspecialchars($a['message'])) ?></p>

            <div class="d-flex justify-content-end">
              <button class="btn btn-sm btn-outline-primary me-2" onclick="openShare(<?= (int)$a['id'] ?>)">Share</button>
              <button class="btn btn-sm btn-outline-secondary" onclick="markRead(<?= (int)$a['id'] ?>, this)" <?= (int)$a['is_read'] ? 'disabled' : '' ?>>
                <i class="bi bi-check2-circle me-1"></i>Mark as Read
              </button>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- BACK TO DASHBOARD BUTTON AT BOTTOM -->
  <div class="text-center mt-4">
    <a href="dashboard.php" class="btn btn-success px-4">
      <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
    </a>
  </div>

</div>

<!-- INTERNAL FOOTER (instead of include) -->
<div class="footer-small bg-green">
  &copy; <?= date('Y') ?> Farmer Feedback & Equipment System. All rights reserved.
</div>

<script>
  function markRead(id, btn) {
    fetch('mark_announcement_read.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'announcement_id=' + id
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        btn.disabled = true;
        let card = document.getElementById('announcement-' + id);
        let nb = card.querySelector('.new-badge');
        if (nb) nb.remove();
        if (window.fetchNotifications) window.fetchNotifications();
      }
    });
  }

  function openShare(id) {
    const msg = document.querySelector('#announcement-' + id + ' p').innerText;
    if (navigator.share) {
      navigator.share({ title: 'Announcement #' + id, text: msg });
    } else {
      navigator.clipboard.writeText(msg).then(() => alert("Copied to clipboard!"));
    }
  }
</script>

</body>
</html>
