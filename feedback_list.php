<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();

$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user']['id']]);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Feedbacks</title>
  <link rel="stylesheet" href="css/feedback_list.css">
</head>
<body>
<main>
  <div class="feedback-container">
    <h2>My Submitted Feedbacks</h2>

    <?php if (isset($_GET['created']) && $_GET['created'] == 1): ?>
      <p class="success-message">Your feedback has been submitted successfully!</p>
    <?php endif; ?>

    <div class="feedback-list">
      <?php if ($feedbacks): ?>
        <?php foreach ($feedbacks as $f): ?>
          <div class="card feedback-item <?= (isset($f['is_read']) && $f['is_read'] == 0 && $f['admin_reply']) ? 'unread' : '' ?>" id="feedback-<?= $f['id'] ?>" data-feedback-id="<?= $f['id'] ?? '' ?>">
            <div class="card-body">
              <h5 class="card-title"><strong>Tittle:</strong> <?= htmlspecialchars($f['title']) ?></h5>
              <p class="card-text"><strong>Type:</strong> <?= htmlspecialchars($f['type']) ?></p>
              <p class="card-text"><strong>Description:</strong> <?= nl2br(htmlspecialchars($f['description'])) ?></p>
              <p class="card-text"><strong>Photo:</strong> 
                <?= $f['photo'] ? '<a href="uploads/' . htmlspecialchars($f['photo']) . '" target="_blank">View</a>' : '<em>None</em>' ?>
              </p>
              <p class="card-text"><strong>Status:</strong> <?= htmlspecialchars($f['status']) ?></p>
              <p class="card-text"><strong>Admin Reply:</strong> <?= nl2br(htmlspecialchars($f['admin_reply'] ?? 'No reply yet')) ?></p>
              <small class="text-muted">Submitted on: <?= htmlspecialchars($f['created_at']) ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center;">No feedback submitted yet.</p>
      <?php endif; ?>
    </div>

    <div class="actions">
      <a href="submit_feedback.php">Submit New Feedback</a> |
      <a href="dashboard.php">Back to Dashboard</a> |
      <a href="profile.php">Profile</a> 
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="js/feedback_list.js"></script>
</body>
</html>
