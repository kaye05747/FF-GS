<?php
require_once __DIR__ . '/config/db.php';

$pdo = db();

$stmt = $pdo->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($feedbacks)) {
    echo "No feedbacks found in the database.";
} else {
    echo "<pre>";
    print_r($feedbacks);
    echo "</pre>";
}
