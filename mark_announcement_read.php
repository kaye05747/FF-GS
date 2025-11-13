<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['announcement_id'])) {
    $announcement_id = sanitize($_POST['announcement_id']);

    $pdo = db();
    $stmt = $pdo->prepare("INSERT INTO announcement_reads (user_id, announcement_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $announcement_id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>