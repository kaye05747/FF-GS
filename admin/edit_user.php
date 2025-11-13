<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';
checkAdmin();

$pdo = db();
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admin_ui.php#manage-users");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $is_active = $_POST['is_active'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, is_active = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $is_active, $role, $id]);

    header("Location: admin_ui.php#manage-users");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body>
    <div class="main-content">
        <div class="dashboard-section">
            <h2>Edit User</h2>
            <form method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>">

                <label for="is_active">Active</label>
                <select id="is_active" name="is_active">
                    <option value="1" <?= $user['is_active'] ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= !$user['is_active'] ? 'selected' : '' ?>>No</option>
                </select>

                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <button type="submit">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>