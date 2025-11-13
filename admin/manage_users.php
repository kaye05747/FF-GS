<?php
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
$pdo = db();

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'")->execute([$id]);
    header("Location: manage_users.php");
    exit;
}

$users = $pdo->query("SELECT id, username, is_active, role FROM users WHERE role='user' ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <title>Manage Users</title>
  <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body>

  <main class="dashboard">
    <h2>Manage Users</h2>
    <table>
      <tr><th>ID</th><th>Username</th><th>Status</th><th>Action</th></tr>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= $u['is_active'] ? 'Active' : 'Inactive' ?></td>
          <td><a href="?delete=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
</body>
</html>
