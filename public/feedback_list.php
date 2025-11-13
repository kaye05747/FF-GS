<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$pdo = db();

$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user']['id']]);
$items = $stmt->fetchAll();
?>
<!doctype html>
<html><head><link rel="stylesheet" href="/css/main.css"></head><body>
<h2>My Feedbacks</h2>
<table border="1" cellpadding="6">
<tr><th>Title</th><th>Status</th><th>Photo</th><th>Actions</th></tr>
<?php foreach($items as $it): ?>
  <tr>
    <td><?= htmlspecialchars($it['title']) ?></td>
    <td><?= $it['status'] ?></td>
    <td><?php if($it['photo']) echo "<a href='/uploads/{$it['photo']}' target='_blank'>View</a>"; ?></td>
    <td>
      <?php if($it['status'] === 'Pending'): ?>
        <a href="edit_feedback.php?id=<?= $it['id'] ?>">Edit</a> |
        <a href="delete_feedback.php?id=<?= $it['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
      <?php else: ?>
        <em>Locked</em>
      <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>
<p><a href="submit_feedback.php">Submit new</a></p>
</body></html>
