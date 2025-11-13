<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';
checkAdmin();

if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);
    $pdo = db();
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_dashboard.php?section=announcements");
exit;
?>