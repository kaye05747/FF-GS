<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();

$stmt = $pdo->query("SELECT id, message, created_at FROM announcements ORDER BY created_at DESC");
$announcements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Announcements & Updates</title>
  <link rel="stylesheet" href="css/announcement.css">
</head>
<body>
  <div class="dashboard-container">
    <h2>Announcements & Updates</h2>
    <?php if (!$announcements): ?>
      <p>No announcements yet.</p>
    <?php else: ?>
      <?php foreach ($announcements as $a): ?>
        <div class="announcement" id="announcement-<?= $a['id'] ?>">
          <h3><?= htmlspecialchars($a['id']) ?></h3>
          <p><?= nl2br(htmlspecialchars($a['message'])) ?></p>
          <small>Posted on: <?= htmlspecialchars($a['created_at']) ?></small>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
  </div>
  <?php include 'includes/footer.php'; ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const announcements = document.querySelectorAll('.announcement');
      announcements.forEach(announcement => {
        const announcementId = announcement.id.replace('announcement-', '');
        fetch('mark_announcement_read.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'announcement_id=' + announcementId
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            console.log('Announcement ' + announcementId + ' marked as read.');
            if (typeof window.fetchNotifications === 'function') {
              window.fetchNotifications(); // Update notification count in header
            }
          } else {
            console.error('Failed to mark announcement ' + announcementId + ' as read.');
          }
        })
        .catch(error => {
          console.error('Error marking announcement ' + announcementId + ' as read:', error);
        });
      });
    });
  </script>
</body>
</html>
