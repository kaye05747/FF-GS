<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();

$stmt = $pdo->query("SELECT name, description FROM services ORDER BY name ASC");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Support Services</title>
  <link rel="stylesheet" href="css/services.css">
</head>
<body>
  <div class="dashboard-container">
    <h2>Farmer Support Services</h2>
    <?php if (!$services): ?>
      <p>No services available at the moment.</p>
    <?php else: ?>
      <?php foreach ($services as $s): ?>
        <div class="service-card">
          <h3><?= htmlspecialchars($s['name']) ?></h3>
          <p><?= nl2br(htmlspecialchars($s['description'])) ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>
