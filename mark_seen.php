<?php
// mark_seen.php
session_start();
require_once __DIR__ . '/config/db.php';
$pdo = db();

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$userId = $_SESSION['user_id'] ?? null;

header('Content-Type: application/json');

if (!$userId || !$id) {
    echo json_encode(['ok' => false, 'error' => 'missing']);
    exit;
}

// ensure feedback belongs to user
$stmt = $pdo->prepare("SELECT user_id FROM feedback WHERE id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row || (int)$row['user_id'] !== (int)$userId) {
    echo json_encode(['ok' => false, 'error' => 'not_allowed']);
    exit;
}

$ustmt = $pdo->prepare("UPDATE feedback SET reply_seen = 1 WHERE id = :id");
$ustmt->execute([':id' => $id]);

echo json_encode(['ok' => true]);
