<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';
checkAdmin();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE feedbacks SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
}

header("Location: admin_ui.php#view-feedback");
exit;
?>