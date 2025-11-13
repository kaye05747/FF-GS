<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user']['id'];
error_log("Fetching notifications for user ID: " . $user_id);

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

error_log("Fetched notifications: " . print_r($notifications, true));

echo json_encode($notifications);
?>