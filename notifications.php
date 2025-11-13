<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$pdo = db();

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM feedbacks WHERE user_id = ? AND is_read = 0 AND admin_reply IS NOT NULL");
$stmt->execute([$_SESSION['user']['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
?>